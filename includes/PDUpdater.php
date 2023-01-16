<?php
/*****************************************************************************

Updater class for GitHub by Ciprian Popescu: 
https://getbutterfly.com/how-to-update-your-wordpress-plugin-from-github/

A GitHub plugin updater for private repositories for WordPress plugins. 
This will work both with WordPress and ClassicPress plugins. Requires PHP 7+.
Updated to use the latest GitHub API authorization method.

In main plugin file, or in plugin’s functions file, initialize the class and pass the details. 
The access token as a license key stored in an option get_option('my_licence_key').

if ((string) get_option('my_licence_key') !== '') {
    include_once plugin_dir_path(__FILE__) . '/path/to/PDUpdater.php';

    $updater = new PDUpdater(__FILE__);
    $updater->set_username('username-here');
    $updater->set_repository('repository-name-here');
    $updater->authorize(get_option('my_licence_key'));
    $updater->initialize();
}

******************************************************************************/
class PDUpdater {
    private $file;
    private $plugin;
    private $basename;
    private $active;
    private $username;
    private $repository;
    private $authorize_token;
    private $github_response;

    public function __construct($file) {
        $this->file = $file;
        add_action('admin_init', [$this, 'set_plugin_properties']);

        return $this;
    }

    public function set_plugin_properties() {
        $this->plugin = get_plugin_data($this->file);
        $this->basename = plugin_basename($this->file);
        $this->active = is_plugin_active($this->basename);
    }

    public function set_username($username) {
        $this->username = $username;
    }

    public function set_repository($repository) {
        $this->repository = $repository;
    }

    public function authorize($token) {
        $this->authorize_token = $token;
    }

    private function get_repository_info() {
        if (is_null($this->github_response)) {
            $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository);

            // Switch to HTTP Basic Authentication for GitHub API v3
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $request_uri,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: token " . $this->authorize_token,
                    "User-Agent: PDUpdater/1.2.3"
                ]
            ]);

            $response = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($response, true);

            if (is_array($response)) {
                $response = current($response);
            }

            if ($this->authorize_token) {
                $response['zipball_url'] = add_query_arg('access_token', $this->authorize_token, $response['zipball_url']);
            }

            $this->github_response = $response;
        }
    }

    public function initialize() {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'modify_transient'], 10, 1);
        add_filter('plugins_api', [$this, 'plugin_popup'], 10, 3);
        add_filter('upgrader_post_install', [$this, 'after_install'], 10, 3);
    }

    public function modify_transient($transient) {
        if (property_exists($transient, 'checked')) {
            if ($checked = $transient->checked) {
                $this->get_repository_info();

                $out_of_date = version_compare($this->github_response['tag_name'], $checked[$this->basename], 'gt');

                if ($out_of_date) {
                    $new_files = $this->github_response['zipball_url'];
                    $slug = current(explode('/', $this->basename));

                    $plugin = [
                        'url' => $this->plugin['PluginURI'],
                        'slug' => $slug,
                        'package' => $new_files,
                        'new_version' => $this->github_response['tag_name']
                    ];

                    $transient->response[$this->basename] = (object) $plugin;
                }
            }
        }

        return $transient;
    }

    public function plugin_popup($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return false;
        }

        if (!empty($args->slug)) {
            if ($args->slug == current(explode('/' , $this->basename))) {
                $this->get_repository_info();

                $plugin = [
                    'name' => $this->plugin['Name'],
                    'slug' => $this->basename,
                    'requires' => '5.3',
                    'tested' => '5.4',
                    'version' => $this->github_response['tag_name'],
                    'author' => $this->plugin['AuthorName'],
                    'author_profile' => $this->plugin['AuthorURI'],
                    'last_updated' => $this->github_response['published_at'],
                    'homepage' => $this->plugin['PluginURI'],
                    'short_description' => $this->plugin['Description'],
                    'sections' => [
                        'Description' => $this->plugin['Description'],
                        'Updates' => $this->github_response['body'],
                    ],
                    'download_link' => $this->github_response['zipball_url']
                ];

                return (object) $plugin;
            }
        }

        return $result;
    }

    public function after_install($response, $hook_extra, $result) {
        global $wp_filesystem;

        $install_directory = plugin_dir_path($this->file);
        $wp_filesystem->move($result['destination'], $install_directory);
        $result['destination'] = $install_directory;

        if ($this->active) {
            activate_plugin($this->basename);
        }

        return $result;
    }
}