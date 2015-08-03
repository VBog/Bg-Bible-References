<?php
/* 
    Plugin Name: Bg Bible References 
    Plugin URI: http://bogaiskov.ru/bg_bibfers/
    Description: Плагин подсвечивает ссылки на текст Библии с помощью гиперссылок на сайт <a href="http://azbyka.ru/">Православной энциклопедии "Азбука веры"</a> и толкование Священного Писания на сайте <a href="http://bible.optina.ru/">монастыря "Оптина Пустынь"</a>. / The plugin will highlight references to the Bible text with links to site of <a href="http://azbyka.ru/">Orthodox encyclopedia "The Alphabet of Faith"</a> and interpretation of Scripture on the site of the <a href="http://bible.optina.ru/">monastery "Optina Pustyn"</a>.
    Author: VBog
    Version: 3.8.0
    Author URI: http://bogaiskov.ru 
*/

/*  Copyright 2015  Vadim Bogaiskov  (email: vadim.bogaiskov@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*****************************************************************************************
	Блок загрузки плагина
	
******************************************************************************************/

// Запрет прямого запуска скрипта
if ( !defined('ABSPATH') ) {
	die( 'Sorry, you are not allowed to access this page directly.' ); 
}

define('BG_BIBREFS_VERSION', '3.8.0');

// Таблица стилей для плагина
function bg_enqueue_frontend_styles () {
	wp_enqueue_style( "bg_bibfers_styles", plugins_url( '/css/styles.css', plugin_basename(__FILE__) ), array() , BG_BIBREFS_VERSION  );
}
add_action( 'wp_enqueue_scripts' , 'bg_enqueue_frontend_styles' );
add_action( 'admin_enqueue_scripts' , 'bg_enqueue_frontend_styles' );

// JS скрипт 
function bg_enqueue_frontend_scripts () {
    $bg_preq_val = get_option( 'bg_bibfers_prereq' );
	if ($bg_preq_val == 'on') $preq = 1;
	else $preq = 0;
	wp_enqueue_script( 'bg_bibrefs_proc', plugins_url( 'js/bg_bibrefs.js?preq='.$preq , __FILE__ ), false, BG_BIBREFS_VERSION, true );
}
function bg_bibrefs_js_options () { 
//	$content="#content";
	$content=get_option( "bg_bibfers_content" );
?>
	<script> var bg_bibrefs_content='<?php echo $content; ?>';</script>
<?php
}
if ( !is_admin() ) {
	add_action( 'wp_enqueue_scripts' , 'bg_enqueue_frontend_scripts' ); 
	add_action( 'wp_head' , 'bg_bibrefs_js_options' ); 
}

// Загрузка интернационализации
load_plugin_textdomain( 'bg_bibfers', false, dirname( plugin_basename( __FILE__ )) . '/languages/' );

// Подключаем дополнительные модули
include_once('includes/settings.php');
include_once('includes/references.php');
include_once('includes/quotes.php');
include_once('includes/search.php');

if ( defined('ABSPATH') && defined('WPINC') ) {
// Регистрируем крючок для обработки контента при его загрузке
	add_filter( 'the_content', 'bg_bibfers' );
// Регистрируем крючок для добавления меню администратора
	add_action('admin_menu', 'bg_bibfers_add_pages');
// Регистрируем крючок на удаление плагина
	if (function_exists('register_uninstall_hook')) {
		register_uninstall_hook(__FILE__, 'bg_bibfers_deinstall');
	}
// Регистрируем шорт-код bible
	add_shortcode( 'bible', 'bg_bibfers_qoutes' );
// Регистрируем шорт-код bible_epigraph
	add_shortcode( 'bible_epigraph', 'bg_bibfers_bible_epigraph' );
// Регистрируем шорт-код references
	add_shortcode( 'references', 'bg_bibfers_references' );
// Регистрируем шорт-код norefs
	add_shortcode( 'norefs', 'bg_bibfers_norefs' );
// Регистрируем шорт-код bible_search
	add_shortcode( 'bible_search', 'bg_bibfers_bible_search' );
}

/*****************************************************************************************
	Функции установки языка Библии 
	
******************************************************************************************/
function set_bible_lang() {
	global $post;
	$blog_lang = substr(get_bloginfo('language'), 0, 2);
	$file_books = dirname( __FILE__ ).'/bible/'.$blog_lang.'/books.php';
	if (!file_exists($file_books)) $blog_lang = 'en';	// По умолчанию английский язык

	$bg_verses_lang_val = get_option( 'bg_bibfers_verses_lang' );
	$bible_lang = ((!$bg_verses_lang_val)?$blog_lang:$bg_verses_lang_val);
	
	$bible_lang_posts_val = ($post)?get_post_meta($post->ID, 'bible_lang', true):"";
	if ($bible_lang_posts_val) {
		$bible_lang = $bible_lang_posts_val;
	}
	return $bible_lang;
}

/*****************************************************************************************
	Функции подключения языкового файла списка книг Библии 
	
******************************************************************************************/
function include_books($lang) {
	global $bg_bibfers_lang_name, $bg_bibfers_chapter, $bg_bibfers_ch;
	global $bg_bibfers_url, $bg_bibfers_bookTitle, $bg_bibfers_shortTitle, $bg_bibfers_bookFile;
	
	$file_books = dirname( __FILE__ ).'/bible/'.$lang.'/books.php';
	if (!file_exists($file_books)) $lang = set_bible_lang(); // Если язык задан неверно, устанавливаем язык системы
	include(dirname(__FILE__ ).'/bible/'.$lang.'/books.php');
	return $lang;
}

/*****************************************************************************************
	Функции запуска плагина
	
******************************************************************************************/
 
// Функция обработки ссылок на Библию 
function bg_bibfers($content) {
	$content = bg_bibfers_bible_proc($content);
	return $content;
}

// Функция действия перед крючком добавления меню
function bg_bibfers_add_pages() {
    // Добавим новое подменю в раздел Параметры 
    add_options_page( __('Bible References', 'bg_bibfers' ), __('Bible References', 'bg_bibfers' ), 'manage_options', __FILE__, 'bg_bibfers_options_page');
}

/*****************************************************************************************
	Шорт-коды
	Функции обработки шорт-кода
******************************************************************************************/
//  [bible]
function bg_bibfers_qoutes( $atts, $content=null ) {
	extract( shortcode_atts( array(
		'ref' => '',
		'book' => '',
		'ch' => '1-999',
		'type' => 'verses',
		'lang' => ''
	), $atts ) );
// Если $ref задано значение "get", то получаем $book и $ch из ссылки	
	if ($ref == "get") {
		$book = $_GET["book"];
		$ch = $_GET["ch"];
		if ($ch == "") $ch = "1-999";
		$l = $_GET["lang"];
		if ($l != "") $lang = $l;
		$ref = '';
	}
// это и все нововведения для версии 3.7
	
	$book = bg_bibfers_getBook($book);
	if (!$lang) $lang = set_bible_lang();
	if ($ref == "rnd" || $ref == "days" || is_numeric ($ref)) $ref = bg_bibfers_bible_quote_refs($ref, $lang);
	
	if ($content) $quote = bg_bibfers_bible_proc($content, $type, $lang);
	else if ($ref) $quote = bg_bibfers_bible_proc($ref, $type, $lang);
	else if ($book != '') {
		if ($type == 'link') {
			$addr = bg_bibfers_get_url($book, $ch, $lang);
			if (strcasecmp($addr, "") != 0) $quote = '('.$addr .bg_bibfers_getshortTitle($book).' '.$ch. "</a></span>".')';
			else return "";
		} else $quote = bg_bibfers_getQuotes($book, $ch, $type, $lang);
	}
	else return "";
	if ($quote != "") {
		$class_val = get_option( 'bg_bibfers_class' );
		if ($class_val == "") $class_val = 'bg_bibfers';
		$quote = "<span class='".$class_val."'>".$quote."</span>";
	}
	return "{$quote}";
}

// [bible_epigraph]
function bg_bibfers_bible_epigraph( $atts ) {
	extract( shortcode_atts( array(
		'ref' => 'rnd',
		'lang' => ''
	), $atts ) );
	$quote = bg_bibfers_get_bible_epigraph( $ref, $lang );
	return "{$quote}";
}
function bg_bibfers_get_bible_epigraph( $ref, $lang ) {
	if (!$lang) $lang = set_bible_lang();
	if ($ref == "rnd" || $ref == "days" || is_numeric ($ref)) $ref = bg_bibfers_bible_quote_refs($ref, $lang);
	if ($ref != "") $quote = bg_bibfers_bible_proc($ref, 'quote', $lang);
	if ($quote != "") {
		$class_val = get_option( 'bg_bibfers_class' );
		if ($class_val == "") $class_val = 'bg_bibfers';
		$quote = "<span class='".$class_val."'>&laquo;".$quote."...&raquo; (".$ref.")</span>";
	}
	return $quote;
}

// [references]
function bg_bibfers_references( $atts ) {
	extract( shortcode_atts( array(
		'type' => 'list',
		'separator' => ', ',
		'list' => 'o',		
		'col' => 1
	), $atts ) );
	global $bg_bibfers_all_refs;
	$references = '<div class="bg_refs_list">';
	$j=0;
	
	$cnt = count($bg_bibfers_all_refs);
	for ($i = 0; $i < $cnt; $i++) {
		$ref = $bg_bibfers_all_refs[$i];
		switch ($type) {
		case 'string':
			if ($i == 0) $references .= '<p>';
			$references .= $ref;
			if ($i == $cnt-1) $references .= '</p>';
			else $references .= $separator;
			break;
        case 'list': 
			if ($list == 'u' || $list == 'o') { 
				if ($i == 0) $references .= '<table><tr valign="top"><td><'.$list.'l>'; 
				$references .=  '<li>'.$ref.'</li>'; 
				if (!(($i+1) % ceil($cnt/$col)) && $i+1 < $cnt) $references .= '</'.$list.'l></td><td><'.$list.'l start="'.($i+2).'">';
				if ($i == $cnt-1) $references .= '</'.$list.'l></td></tr></table>'; 
			}
            break;
		case 'table':
			if ($i == 0) $references .= '<table>';
			if ($j == 0) $references .= '<tr>';
			$references .= '<td>'.$ref.'</td>';
			if ($j == $col-1) $references .= '</tr>';
			$j++;
			if ($j == $col) $j = 0;
			if ($i == $cnt-1) {
				while ($j < $col) {
					$references .= '<td>&nbsp;</td>';
					$j++;
				}
				$references .= '</table>';
			}
			break;
		}
	}
	$references .= '</div>';
	return "{$references}";
}

// [norefs]
function bg_bibfers_norefs( $atts, $content = null ) {
	 return do_shortcode($content);
}

//  [bible_search]
function bg_bibfers_bible_search( $atts, $content=null ) {
	extract( shortcode_atts( array(
		'context' => '',
		'type' => 'verses',
		'lang' => ''
	), $atts ) );
// Если $context задано значение "get", то получаем $context из ссылки	
	if ($context == "get") {
		$context = $_GET["bs"];
		$l = $_GET["lang"];
		if ($l != "") $lang = $l;
	}
	$context = trim($context);
	if (!$context) return "";
	
	$quote = bg_bibfers_search_result($context, $type, $lang);
	
	return "{$quote}";
}

/*****************************************************************************************
	Генератор ответа AJAX
	
******************************************************************************************/
add_action ('wp_ajax_bg_bibrefs', 'bg_bibrefs_callback');
add_action ('wp_ajax_nopriv_bg_bibrefs', 'bg_bibrefs_callback');

function bg_bibrefs_callback() {
	
	$title = $_GET["title"];
	$chapter = $_GET["chapter"];
	if (!$chapter) $chapter = '1-999';
	$type = $_GET["type"];
	$lang = $_GET["lang"];
	if (!$type) $type = 'verses';
	$expand_button = '<img src="'.plugins_url( '/js/expand.png' , __FILE__ ).'" style="cursor:pointer; margin-right: 8px;" align="left" width=16 height=16 title1="'.(__('Expand', 'bg_bibfers' )).'" title2="'.(__('Hide', 'bg_bibfers' )).'" />';
	echo $expand_button.bg_bibfers_getQuotes($title, $chapter, $type, $lang); 
	
	die();
}
function get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__  );
	return $plugin_data['Version'];
}

/*****************************************************************************************
	Добавляем блок в основную колонку на страницах постов и пост. страниц 
	
******************************************************************************************/
add_action('admin_init', 'bg_bibfers_extra_fields', 1);
// Создание блока
function bg_bibfers_extra_fields() {
    add_meta_box( 'bg_bibfers_extra_fields', __('Bible References', 'bg_bibfers'), 'bg_bibfers_extra_fields_box_func', 'post', 'normal', 'high'  );
}
// Добавление полей
function bg_bibfers_extra_fields_box_func( $post ){
?>
	<label for="bg_verses_lang"><?php _e('Language of references and tooltips', 'bg_bibfers' ); ?></label>
		<select id="bg_verses_lang" name="bg_bibfers_extra[bible_lang]">
		<?php $bg_verses_lang_val = get_post_meta($post->ID, 'bible_lang', 1); ?>
			<option <?php if($bg_verses_lang_val=="") echo "selected" ?> value=""><?php _e('Default', 'bg_bibfers' ); ?></option>
			<?php $path = dirname( __FILE__ ).'/bible/';
			if ($handle = opendir($path)) {
				while (false !== ($dir = readdir($handle))) { 
					if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
						include ($path.$dir.'/books.php');
						echo "<option ";
						if($bg_verses_lang_val==$dir) echo "selected";
						echo " value=".$dir.">".$bg_bibfers_lang_name."</option>\n";
					}
				}
				closedir($handle); 
			} ?>
		</select>
	&nbsp;
	<label for="bg_norefs"><?php _e('Ban to highlight references', 'bg_bibfers' ); ?></label>
		<select id="bg_norefs" name="bg_bibfers_extra[norefs]">
		<?php $bg_norefs_val = get_post_meta($post->ID, 'norefs', 1); ?>
			<option <?php if($bg_norefs_val=="") echo "selected" ?> value=""><?php _e('Off', 'bg_bibfers' ); ?></option>
			<option <?php if($bg_norefs_val) echo "selected" ?> value="on"><?php _e('On', 'bg_bibfers' ); ?></option>
		</select>
	
    <input type="hidden" name="bg_bibfers_extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
<?php
}
// Сохранение значений произвольных полей при автосохранении поста
add_action('save_post', 'bg_bibfers_extra_fields_update', 0);

// Сохранение значений произвольных полей при сохранении поста
function bg_bibfers_extra_fields_update( $post_id ){

	if (!isset ($_POST['bg_bibfers_extra_fields_nonce']) ) return false;
    if ( !wp_verify_nonce($_POST['bg_bibfers_extra_fields_nonce'], __FILE__) ) return false;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false;
    if ( !current_user_can('edit_post', $post_id) ) return false;

    if( !isset($_POST['bg_bibfers_extra']) ) return false; 

    $_POST['bg_bibfers_extra'] = array_map('trim', $_POST['bg_bibfers_extra']);
    foreach( $_POST['bg_bibfers_extra'] as $key=>$value ){
        if( empty($value) ) {
            delete_post_meta($post_id, $key);
			continue;
		}
        update_post_meta($post_id, $key, $value);
    }
    return $post_id;
}


/*****************************************************************************************
	Виджет для ввода ссылки на Библию
	
******************************************************************************************/
class BibleWidget extends WP_Widget
{
    public function __construct() {
        parent::__construct("bg_bibfers_bible_widget", __('Bible References Widget', 'bg_bibfers' ),
            array("description" =>  __('Create reference to the Bible', 'bg_bibfers' )));
    }
	// Создаем форму для ввода данных на странице виджетов
	public function form($instance) {
		$title = "";
		$page = "";
		// если instance не пустой, достанем значения
		if (!empty($instance)) {
			$title = $instance["title"];
			$page = $instance["page"];
			$storage = $instance["storage"];
		}
		// Заголовок виджета в сайдбаре
		$titleId = $this->get_field_id("title");
		$titleName = $this->get_field_name("title");
		echo '<p><label for="' . $titleId . '">' . __('Title:', 'bg_bibfers' ) . '</label><br>';
		echo '<input id="' . $titleId . '" type="text" name="' . $titleName . '" value="' . $title . '" size="50"></p>';
		// Ссылка на предварительно созданную страницу для вывода текста Библии
		$pageId = $this->get_field_id("page");
		$pageName = $this->get_field_name("page");
		echo '<p><label for="' . $pageId . '">' . __('Permalink to page:', 'bg_bibfers' ) . '</label><br>';
		echo '<input id="' . $pageId . '" type="text" name="' . $pageName . '" value="' . $page . '" size="50"></p>';
		// Сохранять параметры выбора (на стороне пользователя)
		$storageId = $this->get_field_id("storage");
		$storageName = $this->get_field_name("storage");
		echo '<p><input id="' . $storageId . '" type="checkbox" name="' . $storageName. '"'; 
		if ($storage) echo " checked";
		echo '>' . __('Save selected options (on user side only)', 'bg_bibfers' ) . '</p>';
	}
	// Сохранение настроек
	public function update($newInstance, $oldInstance) {
		$values = array();
		$values["title"] = htmlentities($newInstance["title"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		$values["page"] = htmlentities($newInstance["page"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		$values["storage"] = htmlentities($newInstance["storage"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		return $values;
	}
	// Отображение виджета непосредственно в сайдбаре на сайте
	public function widget($args, $instance) {
		global $bg_bibfers_url, $bg_bibfers_bookTitle, $bg_bibfers_shortTitle, $bg_bibfers_bookFile;

		$lang = get_option( $bg_verses_lang );
		if (!$lang) $lang = set_bible_lang();
		$lang = include_books($lang);

		$num_books = count($bg_bibfers_bookTitle);
		$books = array_keys ( $bg_bibfers_bookTitle);
		$title = $instance["title"];
		$page = $instance["page"];
		$storage = $instance["storage"];
?>
		<aside id="bg-bibrefs-1" class="widget widget_bg-bibrefs">
			<h2 class="widget-title"><?php echo $title; ?></h2>
<!--	Ссылка на страницу в шорт-кодом			-->
			<input id="bg_quote_pageId" type="hidden" value="<?php echo $page; ?>">
		
<!--	Список книг Библии			-->
			<p><label class="widget-title" for="bg_quote_bookId"><?php _e('Book', 'bg_bibfers' ); ?></label><br>
			<select class="required" id="bg_quote_bookId">
				<?php for ($i = 0; $i< $num_books; $i++) { 
					echo "<option value=".$books[$i].">".$bg_bibfers_bookTitle[$books[$i]]."</option>\n";
				} ?>
			</select><br>
<!--	Номера глав и стихов		-->
			<label class="widget-title" for="bg_quote_chId"><?php _e('Chapter', 'bg_bibfers' ); ?></label><br>
			<input class="required" id="bg_quote_chId" type="text" value="" onkeypress="return bg_quote_testKey(event)" placeholder="<?php _e('Chapters and verses', 'bg_bibfers' ); ?>&hellip;"><br>		
<!--	Язык Библии					-->
			<label class="widget-title" for="bg_quote_langId"><?php _e('Language', 'bg_bibfers' ); ?></label><br>
			<select class="required" id="bg_quote_langId">
				<?php $path = dirname( __FILE__ ).'/bible/';
				if ($handle = opendir($path)) {
					while (false !== ($dir = readdir($handle))) { 
						if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
							include ($path.$dir.'/books.php');
							echo "<option ";
							if($lang==$dir) echo "selected";
							echo " value=".$dir.">".$bg_bibfers_lang_name."</option>\n";
						}
					}
					closedir($handle); 
				} ?>
			</select></p>
			<p><input type="submit" value="<?php _e('Go', 'bg_bibfers' ); ?>" onclick="bg_quote_goToPage()"></p>
		</aside>
		<?php if ($storage) { ?>
		<script>
			if (window.localStorage['bg_quote_page'])
				document.getElementById('bg_quote_pageId').value = window.localStorage['bg_quote_page'];
			if (window.localStorage['bg_quote_book'])
				document.getElementById('bg_quote_bookId').value = window.localStorage['bg_quote_book'];
			if (window.localStorage['bg_quote_ch'])
				document.getElementById('bg_quote_chId').value = window.localStorage['bg_quote_ch'];
			if (window.localStorage['bg_quote_lang'])
				document.getElementById('bg_quote_langId').value = window.localStorage['bg_quote_lang'];
		</script>
	<?php } ?>
		<script>
			function bg_quote_goToPage()
			{
				var bg_quote_page = document.getElementById('bg_quote_pageId').value;
				var bg_quote_book = document.getElementById('bg_quote_bookId').value;
				var bg_quote_ch = document.getElementById('bg_quote_chId').value;
				var bg_quote_lang = document.getElementById('bg_quote_langId').value;
				document.location.href = encodeURI(bg_quote_page + "?book=" + bg_quote_book + ((bg_quote_ch!="")?"&ch=":"") + bg_quote_ch + "&lang=" + bg_quote_lang);
				window.localStorage['bg_quote_page'] = bg_quote_page;
				window.localStorage['bg_quote_book'] = bg_quote_book;
				window.localStorage['bg_quote_ch'] = bg_quote_ch;
				window.localStorage['bg_quote_lang'] = bg_quote_lang;
			}
			function bg_quote_testKey(e)
			{
			  // Make sure to use event.charCode if available
			  var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);

			  // Ignore special keys
			  if (e.ctrlKey || e.altKey || key < 32)
				return true;

			  key = String.fromCharCode(key);
			  return /[\d\,\:\-]/.test(key);
			}
		</script>
<?php
	}	
}

/*****************************************************************************************
	Виджет для поиска в Библии
	
******************************************************************************************/
class BibleSearchWidget extends WP_Widget
{
    public function __construct() {
        parent::__construct("bg_bibfers_bible_search_widget", __('Bible Search Widget', 'bg_bibfers' ),
            array("description" =>  __('Finds words or phrases in the Bible', 'bg_bibfers' )));
    }
	// Создаем форму для ввода данных на странице виджетов
	public function form($instance) {
		$title = "";
		$page = "";
		// если instance не пустой, достанем значения
		if (!empty($instance)) {
			$title = $instance["title"];
			$page = $instance["page"];
			$storage = $instance["storage"];
		}
		// Заголовок виджета в сайдбаре
		$titleId = $this->get_field_id("title");
		$titleName = $this->get_field_name("title");
		echo '<p><label for="' . $titleId . '">' . __('Title:', 'bg_bibfers' ) . '</label><br>';
		echo '<input id="' . $titleId . '" type="text" name="' . $titleName . '" value="' . $title . '" size="50"></p>';
		// Ссылка на предварительно созданную страницу для вывода результатов поиска
		$pageId = $this->get_field_id("page");
		$pageName = $this->get_field_name("page");
		echo '<p><label for="' . $pageId . '">' . __('Permalink to page:', 'bg_bibfers' ) . '</label><br>';
		echo '<input id="' . $pageId . '" type="text" name="' . $pageName . '" value="' . $page . '" size="50"></p>';
		// Сохранять параметры выбора (на стороне пользователя)
		$storageId = $this->get_field_id("storage");
		$storageName = $this->get_field_name("storage");
		echo '<p><input id="' . $storageId . '" type="checkbox" name="' . $storageName. '"'; 
		if ($storage) echo " checked";
		echo '>' . __('Save selected options (on user side only)', 'bg_bibfers' ) . '</p>';
	}
	// Сохранение настроек
	public function update($newInstance, $oldInstance) {
		$values = array();
		$values["title"] = htmlentities($newInstance["title"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		$values["page"] = htmlentities($newInstance["page"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		$values["storage"] = htmlentities($newInstance["storage"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		return $values;
	}
	// Отображение виджета непосредственно в сайдбаре на сайте
	public function widget($args, $instance) {
		global $bg_bibfers_url, $bg_bibfers_bookTitle, $bg_bibfers_shortTitle, $bg_bibfers_bookFile;

		$lang = get_option( $bg_verses_lang );
		if (!$lang) $lang = set_bible_lang();
		$lang = include_books($lang);

		$num_books = count($bg_bibfers_bookTitle);
		$books = array_keys ( $bg_bibfers_bookTitle);
		$title = $instance["title"];
		$page = $instance["page"];
		$storage = $instance["storage"];
?>
		<aside id="bg-bibrefs-2" class="widget widget_bg-bibrefs">
			<h2 class="widget-title"><?php echo $title; ?></h2>
<!--	Ссылка на страницу в шорт-кодом			-->
			<input id="bg_search_pageId" type="hidden" value="<?php echo $page; ?>">
		
<!--	Искомое слово или фраза		-->
			<input class="required" id="bg_search_ptrnId" type="text" placeholder="<?php _e('Search', 'bg_bibfers' ); ?>&hellip;" value=""><br>		
<!--	Язык Библии					-->
			<label class="widget-title" for="bg_search_langId"><?php _e('Language', 'bg_bibfers' ); ?></label><br>
			<select class="required" id="bg_search_langId">
				<?php $path = dirname( __FILE__ ).'/bible/';
				if ($handle = opendir($path)) {
					while (false !== ($dir = readdir($handle))) { 
						if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
							include ($path.$dir.'/books.php');
							echo "<option ";
							if($lang==$dir) echo "selected";
							echo " value=".$dir.">".$bg_bibfers_lang_name."</option>\n";
						}
					}
					closedir($handle); 
				} ?>
			</select></p>
			<p><input type="submit" value="<?php _e('Search', 'bg_bibfers' ); ?>" onclick="bg_search_goToPage()"></p>
		</aside>
		<?php if ($storage) { ?>
		<script>
			if (window.localStorage['bg_search_ptrn'])
				document.getElementById('bg_search_ptrnId').value = window.localStorage['bg_search_ptrn'];
			if (window.localStorage['bg_quote_lang'])
				document.getElementById('bg_search_langId').value = window.localStorage['bg_search_lang'];
		</script>
	<?php } ?>
		<script>
			function bg_search_goToPage()
			{
				var bg_search_page = document.getElementById('bg_search_pageId').value;
				var bg_search_ptrn = document.getElementById('bg_search_ptrnId').value;
				var bg_search_lang = document.getElementById('bg_search_langId').value;
				document.location.href = encodeURI(bg_search_page + "?bs=" + bg_search_ptrn + "&lang=" + bg_search_lang);
				window.localStorage['bg_search_ptrn'] = bg_search_ptrn;
				window.localStorage['bg_search_lang'] = bg_search_lang;
			}
		</script>
<?php
	}	
}

/*****************************************************************************************
	Виджет выводит на экран цитату дня из Библии
	
******************************************************************************************/
class QuotesWidget extends WP_Widget
{
    public function __construct() {
        parent::__construct("bg_bibfers_quote_widget", __('Bible Quote Widget', 'bg_bibfers' ),
            array("description" =>  __('Display Random Quote or Day\'s Quote from the Bible', 'bg_bibfers' )));
    }
	// Создаем форму для ввода данных на странице виджетов
	public function form($instance) {
		$title = "";
		$ref = 'days';
		$lang = set_bible_lang();
		// если instance не пустой, достанем значения
		if (!empty($instance)) {
			$title = $instance["title"];
			$ref = $instance["ref"];
			$lang = $instance["lang"];
		}
		// Заголовок виджета в сайдбаре
		$titleId = $this->get_field_id("title");
		$titleName = $this->get_field_name("title");
		echo '<p><label for="' . $titleId . '">' . __('Title:', 'bg_bibfers' ) . '</label><br>';
		echo '<input id="' . $titleId . '" type="text" name="' . $titleName . '" value="' . $title . '" size="50"></p>';
		// Тип цитаты 
		$reId = $this->get_field_id("ref");
		$refName = $this->get_field_name("ref");
		echo '<p><label for="' . $refId . '">' . __('Type of quote:', 'bg_bibfers' ) . '</label><br>';
		echo '<input id="' . $refId . '" type="radio" name="' . $refName . '" value="days"';
		if ($ref=="days") echo " checked";
		echo '>' . __('Day\'s Quote', 'bg_bibfers' ) . '   ';
		echo '<input id="' . $refId . '" type="radio" name="' . $refName . '" value="rnd"';
		if ($ref=="rnd") echo " checked";
		echo '>' . __('Random Quote', 'bg_bibfers' ) . '</p>';
		// Язык цитаты
		$langId = $this->get_field_id("lang");
		$langName = $this->get_field_name("lang");
		
		echo '<p><label for="' . $langId . '">' . __('Language of the Bible:', 'bg_bibfers' ) . '</label><br>';
		echo '<select class="required" id="' . $langId . '" name="'.$langName.'" type="text">';
			$path = dirname( __FILE__ ).'/bible/';
			if ($handle = opendir($path)) {
				while (false !== ($dir = readdir($handle))) { 
					if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
						include ($path.$dir.'/books.php');
						echo "<option ";
						if($lang==$dir) echo "selected ";
						echo " value=".$dir.">".$bg_bibfers_lang_name."</option>\n";
					}
				}
				closedir($handle); 
			}
		echo '</select></p>';
	}
	// Сохранение настроек
	public function update($newInstance, $oldInstance) {
		$values = array();
		$values["title"] = htmlentities($newInstance["title"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		$values["ref"] = htmlentities($newInstance["ref"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		$values["lang"] = htmlentities($newInstance["lang"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		return $values;
	}
	// Отображение виджета непосредственно в сайдбаре на сайте
	public function widget($args, $instance) {
		global $bg_bibfers_url, $bg_bibfers_bookTitle, $bg_bibfers_shortTitle, $bg_bibfers_bookFile;

		$lang = get_option( $bg_verses_lang );
		if (!$lang) $lang = set_bible_lang();
		$lang = include_books($lang);
		
		$num_books = count($bg_bibfers_bookTitle);
		$books = array_keys ( $bg_bibfers_bookTitle);
		$title = $instance["title"];
		$ref = $instance["ref"];
		$lang = $instance["lang"];
?>
		<aside id="bg-bibrefs-3" class="widget widget_bg-bibrefs">
			<h2 class="widget-title"><?php echo $title; ?></h2>
			<p> <?php $quote = bg_bibfers_get_bible_epigraph( $ref, $lang );
				echo $quote; ?>
			</p>
		</aside>
<?php
	}	
}

// Регистрируем виджеты
function  register_widgets() {
    register_widget("BibleWidget");
    register_widget("BibleSearchWidget");
    register_widget("QuotesWidget");
};
add_action("widgets_init", "register_widgets");

