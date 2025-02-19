<?php
/* 
    Plugin Name: Bg Bible References 
    Plugin URI: http://wp-bible.info
    Description: The plugin will highlight the Bible references with hyperlinks to the Bible text and interpretation by the Holy Fathers.
    Version: 3.18.10
    Author: VBog
    Author URI: http://bogaiskov.ru 
	License:     GPL2
	Text Domain: bg_bibrefs
	Domain Path: /languages
	GitHub Plugin URI: https://github.com/VBog/Bg-Bible-References
*/

/*  Copyright 2013-2025  Vadim Bogaiskov  (email: vadim.bogaiskov@gmail.com)

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

define('BG_BIBREFS_VERSION', '3.18.10');
//define('BG_BIBREFS_SOURCE_URL', "http://plugins.svn.wordpress.org/bg-biblie-references/bible/");
define('BG_BIBREFS_SOURCE_URL', "https://github.com//VBog/Bible/raw/main/");

$upload_dir = wp_upload_dir();
define('BG_BIBREFS_UPLOAD_DIR', $upload_dir['basedir']);

$bg_bibrefs_start_time = microtime(true);


// Таблица стилей для плагина
function bg_enqueue_frontend_styles () {
	wp_enqueue_style( "bg_bibrefs_styles", plugins_url( '/css/styles.css', plugin_basename(__FILE__) ), array() , BG_BIBREFS_VERSION  );
}
add_action( 'wp_enqueue_scripts' , 'bg_enqueue_frontend_styles' );
add_action( 'admin_enqueue_scripts' , 'bg_enqueue_frontend_styles' );

// JS скрипт 
function bg_enqueue_frontend_scripts () {
    $bg_preq_val = get_option( 'bg_bibrefs_prereq' );
	if ($bg_preq_val == 'on') $preq = 1;
	else $preq = 0;
	$content=get_option( "bg_bibrefs_content" );
    $ajaxurl = trim(get_option( 'bg_bibrefs_ajaxurl' ));
	if (!$ajaxurl) $ajaxurl=admin_url('admin-ajax.php');
	wp_enqueue_script( 'bg_bibrefs_proc', plugins_url( 'js/bg_bibrefs_loader.js', __FILE__ ), false, BG_BIBREFS_VERSION, true );
	wp_localize_script( 'bg_bibrefs_proc', 'bg_bibrefs', 
		[ 	
			'main_script'=> plugins_url( 'js/bg_bibrefs.js', __FILE__ ),
			'ajaxurl' => $ajaxurl, 
			'content' => $content, 
			'preq' => $preq 
		] );
}	 
if ( !is_admin() ) {
	add_action( 'wp_enqueue_scripts' , 'bg_enqueue_frontend_scripts' ); 
}

// Загрузка интернационализации
add_action( 'plugins_loaded', 'bg_bibrefs_load_textdomain' );
function bg_bibrefs_load_textdomain() {
  load_plugin_textdomain( 'bg_bibrefs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}

// Подключаем дополнительные модули
include_once('includes/settings.php');
include_once('includes/references.php');
include_once('includes/quotes.php');
include_once('includes/search.php');
 
if ( defined('ABSPATH') && defined('WPINC') ) {
// Регистрируем крючок для обработки контента при его загрузке
	add_filter( 'the_content', 'bg_bibrefs' );
	add_filter( 'the_excerpt', 'bg_bibrefs' );

// Регистрируем крючок для добавления меню администратора
	add_action('admin_menu', 'bg_bibrefs_add_pages');
// Регистрируем крючок на удаление плагина
	if (function_exists('register_uninstall_hook')) {
		register_uninstall_hook(__FILE__, 'bg_bibrefs_deinstall');
	}
// Регистрируем шорт-код bible
	add_shortcode( 'bible', 'bg_bibrefs_qoutes' );
// Регистрируем шорт-код bible_epigraph
	add_shortcode( 'bible_epigraph', 'bg_bibrefs_epigraph' );
// Регистрируем шорт-код references
	add_shortcode( 'references', 'bg_bibrefs_references' );
// Регистрируем шорт-код norefs
	add_shortcode( 'norefs', 'bg_bibrefs_norefs' );
// Регистрируем шорт-код bible_search
	add_shortcode( 'bible_search', 'bg_bibrefs_bible_search' );
// Регистрируем шорт-код bible_omnisearch
	add_shortcode( 'bible_omnisearch', 'bg_bibrefs_bible_omnisearch' );

// Инициализируем значения параметров настройки плагина по умолчанию

	bg_bibrefs_options_ini ();	
	bg_bibrefs_get_options ();
}

// Функция, исполняемая при активации плагина.
function  bg_bibrefs_activate() {
	$folders=array("ru");
	$bible_lang = get_bloginfo('language');	
	$bible_lang = substr($bible_lang,0, 2);
	$response = wp_remote_get (BG_BIBREFS_SOURCE_URL."filelist.xml");
	$xml = wp_remote_retrieve_body( $response );
	if ($xml) {
		$files = json_decode(json_encode((array)simplexml_load_string($xml)),1);
		$file = $files['file'];
		foreach ($file as $f){
			$lang = basename($f['filename'], ".zip");
			if ($lang == $bible_lang) {
				$folders=array($lang);
				break;
			}
		}
	}
	foreach ($folders as $book) {
		bg_bibrefs_addFolder($book.'.zip');
	}
	update_option( 'bg_bibrefs_version', BG_BIBREFS_VERSION );
}

register_activation_hook( __FILE__, 'bg_bibrefs_activate' );

// Проверяем текущую версию плагина и обновляем папки с книгами Библии
function bg_bibrefs_upload_folders() {
	$version = get_option('bg_bibrefs_version');
	if (!$version) {
		$folders=array('be','cu','en','ru','uk');
		update_option( 'bg_bibrefs_folders', $folders );
	}
	if ( version_compare( $version, BG_BIBREFS_VERSION, '<' ) ) {
		$folders=get_option('bg_bibrefs_folders');
		if (!$folders) $folders = array("ru");			// Если нет папок, то по умолчанию русский язык
		foreach ($folders as $book) bg_bibrefs_addFolder($book.'.zip');

		update_option( 'bg_bibrefs_version', BG_BIBREFS_VERSION );
	}
}
add_action( 'plugins_loaded', 'bg_bibrefs_upload_folders' );

/** ************************************************************************
 * Добавить папку с Библией на сайт
 **************************************************************************/
function bg_bibrefs_addFolder($book) {

	$source_url = BG_BIBREFS_SOURCE_URL.$book;
	$local_url = dirname(__FILE__ ).'/'.$book;
	$subfolder = dirname(__FILE__ ).'/bible/'.basename($book, ".zip").'/';
	if (!file_exists($local_url)) {
        $response = wp_remote_get ($source_url);
		$body = wp_remote_retrieve_body( $response );
		if ($body) {
			file_put_contents ($local_url, $body);
			$zip = new ZipArchive; 
			$zip->open($local_url); 
			$zip->extractTo($subfolder); 
			$zip->close(); 
			unlink ( $local_url );
		}		
		$folders = bg_bibrefs_getFolders();
		update_option( 'bg_bibrefs_folders', $folders );
	}
}
/** ************************************************************************
 * Удалить папку с Библией с сайта
 **************************************************************************/
function bg_bibrefs_removeFolder($book) {
	$path = dirname( __FILE__ ).'/bible/';
	$dir=$path.basename($book, ".zip"); 
	if ($objs = glob($dir."/*")) {
		foreach($objs as $obj) {
			is_dir($obj) ? bg_bibrefs_removeFolder($obj) : unlink($obj);
		}
	}
	@rmdir($dir);
	$folders = bg_bibrefs_getFolders();
	update_option( 'bg_bibrefs_folders', $folders );
}
/** ************************************************************************
 * Получить список папок с Библией на сайте
 **************************************************************************/
function bg_bibrefs_getFolders() {
	$folders = array();
	$path = dirname( __FILE__ ).'/bible/';
	$id = 0;
	if ($handle = @opendir($path)) {
		while (false !== ($dir = readdir($handle))) { 
			if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
				$folders[$id] = $dir;
				$id++;
			}
		}
		closedir($handle); 
	}
	return $folders;
}


/*****************************************************************************************
	Функции установки языка Библии 
	
******************************************************************************************/
function set_bible_lang() {
	global $post;
	
	$bible_lang = get_bloginfo('language');										// Сначала берем язык блога (1)
	if (function_exists ( 'bg_custom_lang' )) $bible_lang = bg_custom_lang();	// Если определена внешняя функция определения языка, то используем ее (2)
	$bible_lang = substr($bible_lang, 0, 2);

	$bg_verses_lang_val = get_option( 'bg_bibrefs_verses_lang' );
	if ($bg_verses_lang_val) 													// Если задан язык Библии в настройках плагина,
		$bible_lang = $bg_verses_lang_val;										// то язык из настроек (3)
	
	$bible_lang_posts_val = ($post)?get_post_meta($post->ID, 'bible_lang', true):"";	
	if ($bible_lang_posts_val) 													// Если задан язык Библии для поста,
		$bible_lang = $bible_lang_posts_val;									// то язык из поста (4)
	
	$file_books = dirname( __FILE__ ).'/bible/'.$bible_lang.'/books.php';		// Если для установеннного языка отсутствует каталог с Библией,
	$file_books_uploaded = BG_BIBREFS_UPLOAD_DIR.'/bible/'.$bible_lang.'/books.php';	
	if (!file_exists($file_books) && 
		!file_exists($file_books_uploaded)) {
			$bible_lang = 'ru';	 				// то по умолчанию русский язык (5)
			$file_books = dirname( __FILE__ ).'/bible/'.$bible_lang.'/books.php';		// Если для русского языка отсутствует каталог с Библией,
			if (!file_exists($file_books)) $bible_lang = '';							// то язык не установлен
		}
	return $bible_lang;
}

/*****************************************************************************************
	Функции подключения языкового файла списка книг Библии 
	
******************************************************************************************/
function include_books($lang) {
	global $bg_bibrefs_lang_name, $bg_bibrefs_book_letters, $bg_bibrefs_book_length;
	global $bg_bibrefs_chapter, $bg_bibrefs_ch, $bg_bibrefs_psalm, $bg_bibrefs_ps;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;
	
	$file_books = dirname( __FILE__ ).'/bible/'.$lang.'/books.php';
	if (!file_exists($file_books)) // Если нет в папке плагина, то ищем в /wp-content/uploads
		$file_books = BG_BIBREFS_UPLOAD_DIR.'/bible/'.$lang.'/books.php';
	if (!file_exists($file_books)) {
		$lang = set_bible_lang(); // Если язык задан неверно, устанавливаем язык системы
		$file_books = dirname( __FILE__ ).'/bible/'.$lang.'/books.php';
	}
	if ($lang) include($file_books);
	return $lang;
}

/*****************************************************************************************
	Функции запуска плагина
	
******************************************************************************************/
 
// Функция обработки ссылок на Библию 
function bg_bibrefs($content) {
	$content = bg_bibrefs_bible_proc($content);
	return $content;
}

// Функция действия перед крючком добавления меню
function bg_bibrefs_add_pages() {
    // Добавим новое подменю в раздел Параметры 
    add_options_page( __('Bible References', 'bg_bibrefs' ), __('Bible References', 'bg_bibrefs' ), 'manage_options', __FILE__, 'bg_bibrefs_options_page');
}

/*****************************************************************************************
	Шорт-коды
	Функции обработки шорт-кода
******************************************************************************************/
//  [bible]
function bg_bibrefs_qoutes( $atts, $content=null ) {
	extract( shortcode_atts( array(
		'ref' => '',
		'book' => '',
		'ch' => '1-999',
		'type' => 'verses',
		'lang' => '',
		'prll' => ''
	), $atts ) );
	$quote = bg_bibrefs_bible( $ref, $book, $ch, $type, $lang, $prll, $content );
	return "{$quote}";
}
function bg_bibrefs_bible( $ref='', $book='', $ch='1-999', $type='verses', $lang='', $prll='', $content=null ) {
// Если $ref задано значение "get", то получаем $book и $ch из ссылки	
	if ($ref == "get") {
		$ref = $_GET["bs"] ?? "";
		$book = $_GET["book"] ?? "";
		$ch = $_GET["ch"] ?? "";
		if ($ch == "") $ch = "1-999";
		$l = $_GET["lang"] ?? "";
		if ($l != "") $lang = $l;
		$p = $_GET["prll"] ?? "";
		if ($p != "") $prll = $p;
	}
// это и все нововведения для версии 3.7
	if (!$lang) $lang = set_bible_lang();
	$book = bg_bibrefs_getBook($book, $lang);
	if ($ref == "rnd" || $ref == "days" || is_numeric ($ref)) $ref = bg_bibrefs_bible_quote_refs($ref, $lang);

	if ($content) {
		$content = do_shortcode($content);
		$quote = bg_bibrefs_bible_proc($content, $type, $lang, $prll);
	}
	else if ($ref) $quote = bg_bibrefs_bible_proc($ref, $type, $lang, $prll);
	else if ($book != '') {
		if ($type == 'link') $quote = '('.bg_bibrefs_get_url($book, $ch, bg_bibrefs_getshortTitle($book).' '.$ch, $lang).')';
		else $quote = bg_bibrefs_getQuotes($book, $ch, $type, $lang, $prll);
	}
	else return "";
	if ($quote != "") {
		$class_val = get_option( 'bg_bibrefs_class' );
		if ($class_val == "") $class_val = 'bg_bibrefs';
		$quote = "<span class='".$class_val."'>".$quote."</span>";
	}
	return $quote;
}
// [bible_epigraph]
function bg_bibrefs_epigraph( $atts ) {
	extract( shortcode_atts( array(
		'ref' => 'rnd',
		'lang' => ''
	), $atts ) );
	$quote = bg_bibrefs_bible_epigraph( $ref, $lang );
	return "{$quote}";
}
function bg_bibrefs_bible_epigraph( $ref='rnd', $lang='' ) {
	$quote = "";
	if (!$lang) $lang = set_bible_lang();
	if ($ref == "rnd" || $ref == "days" || is_numeric ($ref)) $ref = bg_bibrefs_bible_quote_refs($ref, $lang);
	if ($ref != "") $quote = bg_bibrefs_bible_proc($ref, 'quote', $lang);
	if ($quote != "") {
		$class_val = get_option( 'bg_bibrefs_class' );
		if ($class_val == "") $class_val = 'bg_bibrefs';
		$quote = "<span class='".$class_val."'>&laquo;".$quote."...&raquo; (".$ref.")</span>";
	}
	return $quote;
}

// [references]
function bg_bibrefs_references( $atts ) {
	extract( shortcode_atts( array(
		'type' => 'list',
		'separator' => ', ',
		'list' => 'o',		
		'col' => 1
	), $atts ) );
	global $bg_bibrefs_all_refs;
	$references = '<div class="bg_refs_list">';
	$j=0;
	
	$cnt = count($bg_bibrefs_all_refs);
	for ($i = 0; $i < $cnt; $i++) {
		$ref = $bg_bibrefs_all_refs[$i];
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
function bg_bibrefs_norefs( $atts, $content = null ) {
	 return do_shortcode($content);
}

//  [bible_search]
function bg_bibrefs_bible_search( $atts ) {
	extract( shortcode_atts( array(
		'context' => 'get',
		'book' => '',
		'ch' => '1-999',
		'type' => 'b_verses',
		'lang' => '',
		'prll' => ''
	), $atts ) );
// Если $context задано значение "get", то получаем $context из ссылки	
	if ($context == "get") {
		if (isset($_GET["bs"])) $context = $_GET["bs"];
		else $context = "";
		if (isset($_GET["book"])) $book = $_GET["book"];
		if (isset($_GET["ch"])) $ch = $_GET["ch"];
		if ($ch == "") $ch = "1-999";
		if (isset($_GET["lang"])) $l = $_GET["lang"];
		else $l = "";
		if ($l != "") $lang = $l;
		if (isset($_GET["prll"])) $prll = $_GET["prll"];
		if (!isset($_GET["bs"]) && !isset($_GET["book"])) {
			$keys = array_keys($_GET); 
			if ($keys) {
				$context = $keys[0]; 
				$context = str_replace ( '_' , ' ' , $context );
				$context = trim ($context);
				if ($context == "ch" || $context == "type" || $context == "lang" || $context == "prll") $context = '';
			}
		}
	}
	if ($book)	{
		$book = bg_bibrefs_getBook($book, $lang);
		$context = $book.$ch;
	}
	$context = trim($context);
	if (!$context) return "";
	$quote = bg_bibrefs_bible_proc($context, $type, $lang, $prll);
	
	if (!$quote || $quote == $context) $quote = bg_bibrefs_search_result($context, $type, $lang, $prll);
	
	if ($quote != "") {
		$class_val = get_option( 'bg_bibrefs_class' );
		if ($class_val == "") $class_val = 'bg_bibrefs';
		$quote = "<span class='".$class_val."'>".$quote."</span>";
	}
	return "{$quote}";
}

//  [bible_omnisearch]
function bg_bibrefs_bible_omnisearch( $atts ) {
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;
	extract( shortcode_atts( array(
		'lang' => '',
		'page' => ''
	), $atts ) );
	
	$quote = '';

	if (!$lang) $lang = set_bible_lang();
	$lang = include_books($lang);
	
	if (!$page)	$page = get_permalink(); 


//	Искомое слово или фраза	
	$quote .= '<input class="required" id="bg_omnisearch_ptrnId" type="search" placeholder="'.__('Search', 'bg_bibrefs' ).'&hellip;" value="" style="width:100%" onblur= "bg_omnisearch_goToPage()" onkeypress="return bg_omnisearch_testKey(event)">';
//	Ссылка на страницу в шорт-кодом			
	$quote .= '<input id="bg_omnisearch_pageId" type="hidden" value="'. $page .'">';
//	Язык Библии					
	$quote .= '<input id="bg_omnisearch_langId" type="hidden" value="'. $lang. '">';

	$quote .= '<script>
		if (window.localStorage["bg_omnisearch_ptrn"]) {
			document.getElementById("bg_omnisearch_ptrnId").value = window.localStorage["bg_omnisearch_ptrn"];
		}

		function bg_omnisearch_goToPage() {
			var bg_omnisearch_page = document.getElementById("bg_omnisearch_pageId").value;
			var bg_omnisearch_lang = document.getElementById("bg_omnisearch_langId").value;
			var bg_omnisearch_ptrn = document.getElementById("bg_omnisearch_ptrnId").value;
			document.location.href = encodeURI(bg_omnisearch_page + "?bs=" + bg_omnisearch_ptrn + "&lang=" + bg_omnisearch_lang);
			window.localStorage["bg_omnisearch_ptrn"] = bg_omnisearch_ptrn;
		}
		function bg_omnisearch_testKey(e) {
			// Проверяем доступность event.charCode
			var key = (typeof e.charCode == "undefined" ? e.keyCode : e.charCode);
			// Нажата клавиша Enter ?
			if (key == 13) {bg_omnisearch_goToPage(); return false;}
			else return true;
		}

	</script>';

	return "{$quote}";
}
/*****************************************************************************************
	Формирует список книг Библии на заданном языке в виде объекта
	
******************************************************************************************/
function bg_bibrefs_booklist ($lang) {
	global $bg_bibrefs_bookTitle;
	$lang = include_books($lang);
	$num_books = count($bg_bibrefs_bookTitle);
	$books = array_keys ( $bg_bibrefs_bookTitle);
	$booklist = array();
	for ($i = 0; $i< $num_books; $i++) { 
		$booklist[$i]['value']=$books[$i];
		$booklist[$i]['name']=$bg_bibrefs_bookTitle[$books[$i]];
	} 
	echo json_encode ($booklist);
}
/*****************************************************************************************
	Генератор ответа AJAX
	
******************************************************************************************/
add_action ('wp_ajax_bg_bibrefs', 'bg_bibrefs_callback');
add_action ('wp_ajax_nopriv_bg_bibrefs', 'bg_bibrefs_callback');

function bg_bibrefs_callback() {
	
	if ( isset($_GET["blang"]) ) bg_bibrefs_booklist ($_GET["blang"]);		
	else {
		$title = $_GET["title"];
		$chapter = $_GET["chapter"];
		if (!$chapter) $chapter = '1-999';
		$lang = $_GET["lang"];
		$type = $_GET["type"];
		if (!$type) $type = 'verses';
		$verses = bg_bibrefs_getQuotes($title, $chapter, $type, $lang);
		if ($verses) {
			$expand_button = '<img src="'.plugins_url( '/js/expand.png' , __FILE__ ).'" style="cursor:pointer; margin-right: 8px;" align="left" width=16 height=16 data-title1="'.(__('Expand', 'bg_bibrefs' )).'" data-title2="'.(__('Hide', 'bg_bibrefs' )).'" />';
			echo $expand_button. $verses;
		} 
	}
	wp_die();
}
function get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__  );
	return $plugin_data['Version'];
}

/*****************************************************************************************
	Добавляем блок в основную колонку на страницах постов и пост. страниц 
	
******************************************************************************************/
add_action('admin_init', 'bg_bibrefs_extra_fields', 1);
// Создание блока
function bg_bibrefs_extra_fields() {
    add_meta_box( 'bg_bibrefs_extra_fields', __('Bible References', 'bg_bibrefs'), 'bg_bibrefs_extra_fields_box_func', 'post', 'normal', 'high'  );
}
// Добавление полей
function bg_bibrefs_extra_fields_box_func( $post ){
?>
	<label for="bg_verses_lang"><?php _e('Language of references and tooltips', 'bg_bibrefs' ); ?></label>
		<select id="bg_verses_lang" name="bg_bibrefs_extra[bible_lang]">
		<?php $bg_verses_lang_val = get_post_meta($post->ID, 'bible_lang', 1); ?>
			<option <?php if($bg_verses_lang_val=="") echo "selected" ?> value=""><?php _e('Default', 'bg_bibrefs' ); ?></option>
			<?php 
			$path = dirname( __FILE__ ).'/bible/';
			if (is_dir($path) && $handle = opendir($path)) {
				while (false !== ($dir = readdir($handle))) { 
					if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
						include ($path.$dir.'/books.php');
						echo "<option ";
						if($bg_verses_lang_val==$dir) echo "selected";
						echo " value=".$dir.">".$bg_bibrefs_lang_name."</option>\n";
					}
				}
				closedir($handle); 
			} 
			$path = BG_BIBREFS_UPLOAD_DIR.'/bible/';
			if (is_dir($path) && $handle = opendir($path)) {
				while (false !== ($dir = readdir($handle))) { 
					if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
						include ($path.$dir.'/books.php');
						echo "<option ";
						if($bg_verses_lang_val==$dir) echo "selected";
						echo " value=".$dir.">".$bg_bibrefs_lang_name."</option>\n";
					}
				}
				closedir($handle); 
			} 
			?>
		</select>
	&nbsp;
	<label for="bg_norefs"><?php _e('Ban to highlight references', 'bg_bibrefs' ); ?></label>
		<select id="bg_norefs" name="bg_bibrefs_extra[norefs]">
		<?php $bg_norefs_val = get_post_meta($post->ID, 'norefs', 1); ?>
			<option <?php if($bg_norefs_val=="") echo "selected" ?> value=""><?php _e('Off', 'bg_bibrefs' ); ?></option>
			<option <?php if($bg_norefs_val) echo "selected" ?> value="on"><?php _e('On', 'bg_bibrefs' ); ?></option>
		</select>
	
    <input type="hidden" name="bg_bibrefs_extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
<?php
}
// Сохранение значений произвольных полей при автосохранении поста
add_action('save_post', 'bg_bibrefs_extra_fields_update', 0);

// Сохранение значений произвольных полей при сохранении поста
function bg_bibrefs_extra_fields_update( $post_id ){

	if (!isset ($_POST['bg_bibrefs_extra_fields_nonce']) ) return false;
    if ( !wp_verify_nonce($_POST['bg_bibrefs_extra_fields_nonce'], __FILE__) ) return false;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false;
    if ( !current_user_can('edit_post', $post_id) ) return false;

    if( !isset($_POST['bg_bibrefs_extra']) ) return false; 

    $_POST['bg_bibrefs_extra'] = array_map('trim', $_POST['bg_bibrefs_extra']);
    foreach( $_POST['bg_bibrefs_extra'] as $key=>$value ){
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
        parent::__construct("bg_bibrefs_bible_widget", __('Bible References Widget', 'bg_bibrefs' ),
            array("description" =>  __('Create reference to the Bible', 'bg_bibrefs' )));
    }
	// Создаем форму для ввода данных на странице виджетов
	public function form($instance) {
		$title = "";
		$page = "";
		$dlang = "";
		$storage = "";
		// если instance не пустой, достанем значения
		if (!empty($instance)) {
			$title = $instance["title"];
			$page = $instance["page"];
			$dlang = $instance["dlang"];
			$storage = $instance["storage"];
		}
		// Заголовок виджета в сайдбаре
		$titleId = $this->get_field_id("title");
		$titleName = $this->get_field_name("title");
		echo '<p><label for="' . $titleId . '">' . __('Title:', 'bg_bibrefs' ) . '</label><br>';
		echo '<input id="' . $titleId . '" type="text" name="' . $titleName . '" value="' . $title . '" size="50"></p>';
		// Ссылка на предварительно созданную страницу для вывода текста Библии
		$pageId = $this->get_field_id("page");
		$pageName = $this->get_field_name("page");
		echo '<p><label for="' . $pageId . '">' . __('Permalink to page:', 'bg_bibrefs' ) . '</label><br>';
		echo '<input id="' . $pageId . '" type="text" name="' . $pageName . '" value="' . $page . '" size="50"></p>';
		// Язык Библии по-умолчанию
		$dlangId = $this->get_field_id("dlang");
		$dlangName = $this->get_field_name("dlang");
		echo '<p><input id="' . $dlangId . '" type="checkbox" name="' . $dlangName. '"'; 
		if ($dlang) echo " checked";
		echo '>' . __('Default language', 'bg_bibrefs' ) . '</p>';
		// Сохранять параметры выбора (на стороне пользователя)
		$storageId = $this->get_field_id("storage");
		$storageName = $this->get_field_name("storage");
		echo '<p><input id="' . $storageId . '" type="checkbox" name="' . $storageName. '"'; 
		if ($storage) echo " checked";
		echo '>' . __('Save selected options (on user side only)', 'bg_bibrefs' ) . '</p>';
	}
	// Сохранение настроек
	public function update($newInstance, $oldInstance) {
		$values = array();
		$values["title"] = htmlentities($newInstance["title"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		$values["page"] = htmlentities($newInstance["page"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		if (!array_key_exists('dlang', $newInstance)) $values["dlang"]="";
		else $values["dlang"] = htmlentities($newInstance["dlang"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		if (!array_key_exists('storage', $newInstance)) $values["storage"]="";
		else $values["storage"] = htmlentities($newInstance["storage"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		return $values;
	}
	// Отображение виджета непосредственно в сайдбаре на сайте
	public function widget($args, $instance) {
		global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;

		$title = $instance["title"];
		$page = $instance["page"];
		$dlang = $instance["dlang"];
		$storage = $instance["storage"];
		
		$lang = set_bible_lang();
		$lang = include_books($lang);
?>
		<aside id="bg-bibrefs-1" class="widget widget_bg-bibrefs">
			<h2 class="widget-title"><?php echo $title; ?></h2>
<!--	Ссылка на страницу в шорт-кодом			-->
			<input id="bg_quote_pageId" type="hidden" value="<?php echo $page; ?>">
		
<!--	Список книг Библии			-->
			<p><label class="widget-title" for="bg_quote_bookId"><?php _e('Book', 'bg_bibrefs' ); ?></label><br>
			<select class="required" id="bg_quote_bookId" style="width: 100%">
				<?php 
				$num_books = count($bg_bibrefs_bookTitle);
				$books = array_keys ( $bg_bibrefs_bookTitle);
				for ($i = 0; $i< $num_books; $i++) { 
					echo "<option value=".$books[$i].">".$bg_bibrefs_bookTitle[$books[$i]]."</option>\n";
				} 
				?>
			</select></p>
<!--	Номера глав и стихов		-->
			<p><label class="widget-title" for="bg_quote_chId"><?php _e('Chapter', 'bg_bibrefs' ); ?></label><br>
			<input class="required" id="bg_quote_chId" type="text" value="" onkeypress="return bg_quote_testKey(event)" placeholder="<?php _e('Chapters and verses', 'bg_bibrefs' ); ?>&hellip;"></p>		
<!--	Язык Библии					-->
		<?php if (!$dlang) { ?>
			<p><label class="widget-title" for="bg_quote_langId"><?php _e('Language', 'bg_bibrefs' ); ?></label><br>
			<select class="required" id="bg_quote_langId" onchange="bg_bibrefs_booklist();" multiple>
				<?php 
				$path = dirname( __FILE__ ).'/bible/';
				if ($handle = opendir($path)) {
					while (false !== ($dir = readdir($handle))) { 
						if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
							include ($path.$dir.'/books.php');
							echo "<option ";
							if($lang==$dir) echo "selected";
							echo " value=".$dir.">".$bg_bibrefs_lang_name."</option>\n";
						}
					}
					closedir($handle); 
				} 
				$path = BG_BIBREFS_UPLOAD_DIR.'/bible/';
				if ($handle = @opendir($path)) {
					while (false !== ($dir = readdir($handle))) { 
						if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
							include ($path.$dir.'/books.php');
							echo "<option ";
							if($lang==$dir) echo "selected";
							echo " value=".$dir.">".$bg_bibrefs_lang_name."</option>\n";
						}
					}
					closedir($handle); 
				} 
				?>
			</select></p>
		<?php } else { ?>
			<input id="bg_quote_langId" type="hidden" value="<?php echo $lang; ?>">
		<?php } ?>	
			<p><input type="submit" value="<?php _e('Go', 'bg_bibrefs' ); ?>" onclick="bg_quote_goToPage()"></p>
		</aside>
		<script>
			function bg_quote_goToPage() {
				var bg_quote_page = document.getElementById('bg_quote_pageId').value;
				var bg_quote_book = document.getElementById('bg_quote_bookId').value;
				var bg_quote_ch = document.getElementById('bg_quote_chId').value;

			<?php if ($dlang) { ?>
				var bg_quote_lang = document.getElementById('bg_quote_langId').value;
			<?php } else { ?>
				var bg_quote_lang = "";
				var len = document.getElementById("bg_quote_langId").options.length;
				for (var n = 0; n < len; n++) {
					if (document.getElementById("bg_quote_langId").options[n].selected==true) {
						bg_quote_lang = bg_quote_lang + "~" + document.getElementById("bg_quote_langId").options[n].value;
					}
				}
				bg_quote_lang = bg_quote_lang.slice(1);
			<?php } ?>

				document.location.href = encodeURI(bg_quote_page + "?book=" + bg_quote_book + ((bg_quote_ch!="")?"&ch=":"") + bg_quote_ch + ((bg_quote_lang!="")?"&lang=":"") + bg_quote_lang);
				window.localStorage['bg_quote_book'] = bg_quote_book;
				window.localStorage['bg_quote_ch'] = bg_quote_ch;
				window.localStorage['bg_quote_lang'] = bg_quote_lang;
			}
			function bg_quote_testKey(e) {
				// Проверяем доступность event.charCode
				var key = (typeof e.charCode == 'undefined' ? e.keyCode : e.charCode);
				// Игнорируем специальные кнопки
				if (e.ctrlKey || e.altKey || key < 32) return true;
				key = String.fromCharCode(key);
				// Допустимы цифры, запятая, двоеточие и тире
				return /[\d\,\:\-]/.test(key);
			}
			function bg_bibrefs_booklist(select) {
				var el = document.getElementById('bg_quote_bookId');
				if (!select) select = el.value;

			<?php if ($dlang) { ?>
				var bg_quote_lang = document.getElementById('bg_quote_langId').value;
			<?php } else { ?>
				var bg_quote_lang = "";
				var len = document.getElementById("bg_quote_langId").options.length;
				for (var n = 0; n < len; n++) {
					if (document.getElementById("bg_quote_langId").options[n].selected==true) {
						bg_quote_lang = bg_quote_lang + "~" + document.getElementById("bg_quote_langId").options[n].value;
					}
				}
				bg_quote_lang = bg_quote_lang.slice(1);
			<?php } ?>

				jQuery.ajax({
					type: 'GET',
					cache: false,
					async: true,											// Асинхронный запрос
					dataType: 'json',
					url: '/wp-admin/admin-ajax.php?blang='+bg_quote_lang,	// Запрос загрузки данных
					data: {
						action: 'bg_bibrefs'
					},
					success: function (t, textStatus) {
						if (t) {
							el.options.length = 0;
							for (i=0; i<t.length; i++)
								el.options[i] = new Option(t[i].name, t[i].value);
							el.value = select;
						}
					}
				});
				<?php if ($storage) { ?>
				window.localStorage['bg_quote_lang'] = bg_quote_lang;
				<?php } ?>
			}
		</script>
		<?php if ($storage) { ?>
		<script>
			<?php if (!$dlang) { ?>
			if (window.localStorage['bg_quote_lang']) {
//				document.getElementById('bg_quote_langId').value = window.localStorage['bg_quote_lang'];

				var langs = window.localStorage['bg_quote_lang'].split('~');
				var len = document.getElementById("bg_quote_langId").options.length;
				for (var n = 0; n < len; n++) {
					if (langs.indexOf(document.getElementById("bg_quote_langId").options[n].value) != -1) {
						document.getElementById("bg_quote_langId").options[n].selected = true;
					} else {
						document.getElementById("bg_quote_langId").options[n].selected = false;
					}
				}
			}
			bg_bibrefs_booklist(window.localStorage['bg_quote_book']);
			<?php } ?>
			if (window.localStorage['bg_quote_book'])
				document.getElementById('bg_quote_bookId').value = window.localStorage['bg_quote_book'];
			if (window.localStorage['bg_quote_ch'])
				document.getElementById('bg_quote_chId').value = window.localStorage['bg_quote_ch'];
		</script>
		<?php } 
	}	
}

/*****************************************************************************************
	Виджет для поиска в Библии
	
******************************************************************************************/
class BibleSearchWidget extends WP_Widget
{
    public function __construct() {
        parent::__construct("bg_bibrefs_bible_search_widget", __('Bible Search Widget', 'bg_bibrefs' ),
            array("description" =>  __('Finds words or phrases in the Bible', 'bg_bibrefs' )));
    }
	// Создаем форму для ввода данных на странице виджетов
	public function form($instance) {
		$title = "";
		$page = "";
		$dlang = "";
		$storage = "";
		// если instance не пустой, достанем значения
		if (!empty($instance)) {
			$title = $instance["title"];
			$page = $instance["page"];
			$dlang = $instance["dlang"];
			$storage = $instance["storage"];
		}
		// Заголовок виджета в сайдбаре
		$titleId = $this->get_field_id("title");
		$titleName = $this->get_field_name("title");
		echo '<p><label for="' . $titleId . '">' . __('Title:', 'bg_bibrefs' ) . '</label><br>';
		echo '<input id="' . $titleId . '" type="text" name="' . $titleName . '" value="' . $title . '" size="50"></p>';
		// Ссылка на предварительно созданную страницу для вывода результатов поиска
		$pageId = $this->get_field_id("page");
		$pageName = $this->get_field_name("page");
		echo '<p><label for="' . $pageId . '">' . __('Permalink to page:', 'bg_bibrefs' ) . '</label><br>';
		echo '<input id="' . $pageId . '" type="text" name="' . $pageName . '" value="' . $page . '" size="50"></p>';
		// Язык Библии по-умолчанию
		$dlangId = $this->get_field_id("dlang");
		$dlangName = $this->get_field_name("dlang");
		echo '<p><input id="' . $dlangId . '" type="checkbox" name="' . $dlangName. '"'; 
		if ($dlang) echo " checked";
		echo '>' . __('Default language', 'bg_bibrefs' ) . '</p>';
		// Сохранять параметры выбора (на стороне пользователя)
		$storageId = $this->get_field_id("storage");
		$storageName = $this->get_field_name("storage");
		echo '<p><input id="' . $storageId . '" type="checkbox" name="' . $storageName. '"'; 
		if ($storage) echo " checked";
		echo '>' . __('Save selected options (on user side only)', 'bg_bibrefs' ) . '</p>';
	}
	// Сохранение настроек
	public function update($newInstance, $oldInstance) {
		$values = array();
		$values["title"] = htmlentities($newInstance["title"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		$values["page"] = htmlentities($newInstance["page"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		if (!array_key_exists('dlang', $newInstance)) $values["dlang"]="";
		else $values["dlang"] = htmlentities($newInstance["dlang"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		if (!array_key_exists('storage', $newInstance)) $values["storage"]="";
		else $values["storage"] = htmlentities($newInstance["storage"], ENT_COMPAT | ENT_HTML401, "UTF-8");
		return $values;
	}
	// Отображение виджета непосредственно в сайдбаре на сайте
	public function widget($args, $instance) {
		global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;

		$lang = set_bible_lang();
		$lang = include_books($lang);

		$num_books = count($bg_bibrefs_bookTitle);
		$books = array_keys ( $bg_bibrefs_bookTitle);
		$title = $instance["title"];
		$page = $instance["page"];
		$dlang = $instance["dlang"];
		$storage = $instance["storage"];
?>
		<aside id="bg-bibrefs-2" class="widget widget_bg-bibrefs">
			<h2 class="widget-title"><?php echo $title; ?></h2>
<!--	Ссылка на страницу в шорт-кодом			-->
			<input id="bg_search_pageId" type="hidden" value="<?php echo $page; ?>">
		
<!--	Искомое слово или фраза		-->
			<input class="required" id="bg_search_ptrnId" type="text" placeholder="<?php _e('Search', 'bg_bibrefs' ); ?>&hellip;" value=""><br>		
<!--	Язык Библии					-->
		<?php if (!$dlang) { ?>
		
			<label class="widget-title" for="bg_search_langId"><?php _e('Language', 'bg_bibrefs' ); ?></label><br>
			<select class="required" id="bg_search_langId">
				<?php 
				$path = dirname( __FILE__ ).'/bible/';
				if ($handle = opendir($path)) {
					while (false !== ($dir = readdir($handle))) { 
						if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
							include ($path.$dir.'/books.php');
							echo "<option ";
							if($lang==$dir) echo "selected";
							echo " value=".$dir.">".$bg_bibrefs_lang_name."</option>\n";
						}
					}
					closedir($handle); 
				} 
				$path = BG_BIBREFS_UPLOAD_DIR.'/bible/';
				if ($handle = opendir($path)) {
					while (false !== ($dir = readdir($handle))) { 
						if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
							include ($path.$dir.'/books.php');
							echo "<option ";
							if($lang==$dir) echo "selected";
							echo " value=".$dir.">".$bg_bibrefs_lang_name."</option>\n";
						}
					}
					closedir($handle); 
				} 
				?>
			</select>
		<?php } else { ?>
			<input id="bg_search_langId" type="hidden" value="">
		<?php } ?>		
			</p>
			<p><input type="submit" value="<?php _e('Search', 'bg_bibrefs' ); ?>" onclick="bg_search_goToPage()"></p>
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
        parent::__construct("bg_bibrefs_quote_widget", __('Bible Quote Widget', 'bg_bibrefs' ),
            array("description" =>  __('Display Random Quote or Day\'s Quote from the Bible', 'bg_bibrefs' )));
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
		echo '<p><label for="' . $titleId . '">' . __('Title:', 'bg_bibrefs' ) . '</label><br>';
		echo '<input id="' . $titleId . '" type="text" name="' . $titleName . '" value="' . $title . '" size="50"></p>';
		// Тип цитаты 
		$refId = $this->get_field_id("ref");
		$refName = $this->get_field_name("ref");
		echo '<p><label for="' . $refId . '">' . __('Type of quote:', 'bg_bibrefs' ) . '</label><br>';
		echo '<input id="' . $refId . '" type="radio" name="' . $refName . '" value="days"';
		if ($ref=="days") echo " checked";
		echo '>' . __('Day\'s Quote', 'bg_bibrefs' ) . '   ';
		echo '<input id="' . $refId . '" type="radio" name="' . $refName . '" value="rnd"';
		if ($ref=="rnd") echo " checked";
		echo '>' . __('Random Quote', 'bg_bibrefs' ) . '</p>';
		// Язык цитаты
		$langId = $this->get_field_id("lang");
		$langName = $this->get_field_name("lang");
		
		echo '<p><label for="' . $langId . '">' . __('Language of the Bible:', 'bg_bibrefs' ) . '</label><br>';
		echo '<select class="required" id="' . $langId . '" name="'.$langName.'" type="text">';

			echo "<option ";
			if(!$lang) echo "selected ";
			echo " value=''>".__('Default', 'bg_bibrefs' )."</option>\n";
			
			$path = dirname( __FILE__ ).'/bible/';
			if ($handle = opendir($path)) {
				while (false !== ($dir = readdir($handle))) { 
					if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
						include ($path.$dir.'/books.php');
						echo "<option ";
						if($lang==$dir) echo "selected ";
						echo " value=".$dir.">".$bg_bibrefs_lang_name."</option>\n";
					}
				}
				closedir($handle); 
			}
			$path = BG_BIBREFS_UPLOAD_DIR.'/bible/';
			if ($handle = opendir($path)) {
				while (false !== ($dir = readdir($handle))) { 
					if (is_dir ( $path.$dir ) && $dir != '.' && $dir != '..') {
						include ($path.$dir.'/books.php');
						echo "<option ";
						if($lang==$dir) echo "selected ";
						echo " value=".$dir.">".$bg_bibrefs_lang_name."</option>\n";
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
		global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;

		$title = $instance["title"];
		$ref = $instance["ref"];
		$lang = $instance["lang"];

		if (!$lang) $lang = set_bible_lang();
		$lang = include_books($lang);
		$num_books = count($bg_bibrefs_bookTitle);
		$books = array_keys ( $bg_bibrefs_bookTitle);
?>
		<aside id="bg-bibrefs-3" class="widget widget_bg-bibrefs">
			<h2 class="widget-title"><?php echo $title; ?></h2>
			<p> <?php $quote = bg_bibrefs_bible_epigraph( $ref, $lang );
				echo $quote; ?>
			</p>
		</aside>
<?php
	}	
}
// Регистрируем виджеты
function register_widgets() {
    register_widget("BibleWidget");
    register_widget("BibleSearchWidget");
    register_widget("QuotesWidget");
};
add_action("widgets_init", "register_widgets");



/*****************************************************************************************
	Параметры плагина
	
******************************************************************************************/
// Задание параметров по умолчанию
function bg_bibrefs_options_ini () {
	add_option('bg_bibrefs_site', "azbyka");
	add_option('bg_bibrefs_c_lang', "c");
	add_option('bg_bibrefs_r_lang', "r");
	add_option('bg_bibrefs_k_lang');
	add_option('bg_bibrefs_v_lang');
	add_option('bg_bibrefs_z_lang');
	add_option('bg_bibrefs_a_lang');
	add_option('bg_bibrefs_h_lang');
	add_option('bg_bibrefs_g_lang');
	add_option('bg_bibrefs_l_lang');
	add_option('bg_bibrefs_i_lang');
	add_option('bg_bibrefs_c_font', "ucs");
	add_option('bg_bibrefs_page', "");
	add_option('bg_bibrefs_verses_lang', "");
	add_option('bg_bibrefs_show_fn', "");
	add_option('bg_bibrefs_target', "_blank");
	add_option('bg_bibrefs_headers', "on");
	add_option('bg_bibrefs_interpret', "on");
	add_option('bg_bibrefs_parallel', "");
	add_option('bg_bibrefs_norm_refs');
	add_option('bg_bibrefs_show_verses', "on");
	
	add_option('bg_bibrefs_dot', "on");
    add_option('bg_bibrefs_romeh', "on");
    add_option('bg_bibrefs_sepc', "on");
	add_option('bg_bibrefs_strip_space');
	add_option('bg_bibrefs_exceptions',"");
	
	add_option('bg_bibrefs_curl', "on");
	add_option('bg_bibrefs_fgc', "on");
	add_option('bg_bibrefs_fopen', "on");
	add_option('bg_bibrefs_preload');
	add_option('bg_bibrefs_prereq');
	add_option('bg_bibrefs_maxtime', "60");
	add_option('bg_bibrefs_ajaxurl', "");
	add_option('bg_bibrefs_content', "#content");
	add_option('bg_bibrefs_class', "bg_bibrefs");
	add_option('bg_bibrefs_refs_file', "");
	add_option('bg_bibrefs_debug', "");
	
	add_option('bg_bibrefs_version', 0 );
	add_option('bg_bibrefs_folders', array('ru'));
}

// Очистка таблицы параметров при удалении плагина
function bg_bibrefs_deinstall() {
	delete_option('bg_bibrefs_site');
	delete_option('bg_bibrefs_c_lang');
	delete_option('bg_bibrefs_r_lang');
	delete_option('bg_bibrefs_k_lang');
	delete_option('bg_bibrefs_v_lang');
	delete_option('bg_bibrefs_z_lang');
	delete_option('bg_bibrefs_a_lang');
	delete_option('bg_bibrefs_h_lang');
	delete_option('bg_bibrefs_g_lang');
	delete_option('bg_bibrefs_l_lang');
	delete_option('bg_bibrefs_i_lang');
	delete_option('bg_bibrefs_c_font');
	delete_option('bg_bibrefs_page');
	delete_option('bg_bibrefs_verses_lang');
	delete_option('bg_bibrefs_show_fn');
	delete_option('bg_bibrefs_target');
	delete_option('bg_bibrefs_headers');
	delete_option('bg_bibrefs_interpret');
	delete_option('bg_bibrefs_parallel');
	delete_option('bg_bibrefs_norm_refs');
	delete_option('bg_bibrefs_show_verses');
	
	delete_option('bg_bibrefs_dot');
    delete_option('bg_bibrefs_romeh');
    delete_option('bg_bibrefs_sepc');
    delete_option('bg_bibrefs_sepd');
    delete_option('bg_bibrefs_seps');
    delete_option('bg_bibrefs_separator');
	delete_option('bg_bibrefs_strip_space');
	delete_option('bg_bibrefs_exceptions');
	
	delete_option('bg_bibrefs_curl');
	delete_option('bg_bibrefs_fgc');
	delete_option('bg_bibrefs_fopen');
	delete_option('bg_bibrefs_preload');
	delete_option('bg_bibrefs_prereq');
	delete_option('bg_bibrefs_maxtime');
	delete_option('bg_bibrefs_cashe');
	delete_option('bg_bibrefs_ajaxurl');
	delete_option('bg_bibrefs_content');
	delete_option('bg_bibrefs_class');
	delete_option('bg_bibrefs_refs_file');
	delete_option('bg_bibrefs_debug');

	delete_option('bg_bibrefs_submit_hidden');
	delete_option('bg_bibrefs_options_udate');
	
	delete_option('bg_bibrefs_version');
	delete_option('bg_bibrefs_folders');
}

function bg_bibrefs_get_options () {
	global $bg_bibrefs_option;

// Читаем существующие значения опций из базы данных
	$opt = "";
	$c_lang_val = get_option( 'bg_bibrefs_c_lang' );
	$font_val = get_option( 'bg_bibrefs_c_font' );
    $r_lang_val = get_option( 'bg_bibrefs_r_lang' );
    $k_lang_val = get_option( 'bg_bibrefs_k_lang' );
    $v_lang_val = get_option( 'bg_bibrefs_v_lang' );
    $z_lang_val = get_option( 'bg_bibrefs_z_lang' );
    $a_lang_val = get_option( 'bg_bibrefs_a_lang' );
    $h_lang_val = get_option( 'bg_bibrefs_h_lang' );
    $g_lang_val = get_option( 'bg_bibrefs_g_lang' );
    $l_lang_val = get_option( 'bg_bibrefs_l_lang' );
    $i_lang_val = get_option( 'bg_bibrefs_i_lang' );
	
	if ($c_lang_val=="c" && $font_val == "ucs") $c_lang_val='utfcs';
	
	$langs_arr = array_filter( array($c_lang_val,$r_lang_val,$k_lang_val,$v_lang_val,$z_lang_val,$a_lang_val,$h_lang_val,$g_lang_val,$l_lang_val,$i_lang_val));
	if (count($langs_arr)) $opt = "&".implode('~', $langs_arr);
	$bg_bibrefs_option['azbyka'] = $opt;
	
// Общие параметры	отображения ссылок
	$bg_bibrefs_option['site'] = get_option( 'bg_bibrefs_site' );
    $bg_bibrefs_option['page'] = get_option( 'bg_bibrefs_page' );
    $bg_bibrefs_option['target'] = get_option( 'bg_bibrefs_target' );
    $bg_bibrefs_option['content'] = get_option( 'bg_bibrefs_content' );
	if ($bg_bibrefs_option['content'] == "") $bg_bibrefs_option['content'] = 'body';
    $bg_bibrefs_option['class'] = get_option( 'bg_bibrefs_class' );
	if ($bg_bibrefs_option['class'] == "") $bg_bibrefs_option['class'] = 'bg_bibrefs';
	$bg_bibrefs_option['show_verses'] = get_option( 'bg_bibrefs_show_verses' );	

    $bg_bibrefs_option['verses_lang'] = get_option( 'bg_bibrefs_verses_lang' );
    $bg_bibrefs_option['show_fn'] = get_option( 'bg_bibrefs_show_fn' );

    $bg_bibrefs_option['headers'] = get_option( 'bg_bibrefs_headers' );
    $bg_bibrefs_option['interpret'] = get_option( 'bg_bibrefs_interpret' );
    $bg_bibrefs_option['parallel'] = get_option( 'bg_bibrefs_parallel' );

    $bg_bibrefs_option['norm_refs'] = get_option( 'bg_bibrefs_norm_refs' );

	$bg_bibrefs_option['dot'] = get_option( 'bg_bibrefs_dot' );
	$bg_bibrefs_option['romeh'] = get_option( 'bg_bibrefs_romeh' );
	$bg_bibrefs_option['sepc'] = get_option( 'bg_bibrefs_sepc' );
    $bg_bibrefs_option['strip_space'] = get_option( 'bg_bibrefs_strip_space' );

    $bg_bibrefs_option['curl'] = get_option( 'bg_bibrefs_curl' );
    $bg_bibrefs_option['fgc'] = get_option( 'bg_bibrefs_fgc' );
    $bg_bibrefs_option['fopen'] = get_option( 'bg_bibrefs_fopen' );

    $bg_bibrefs_option['pload'] = get_option('bg_bibrefs_preload' );
    $bg_bibrefs_option['preq'] = get_option('bg_bibrefs_prereq' );
	
    $bg_bibrefs_option['maxtime'] = (int) get_option( 'bg_bibrefs_maxtime' );
	
    $bg_bibrefs_option['refs_file'] = get_option( 'bg_bibrefs_refs_file' );
	
    $bg_bibrefs_option['debug'] = get_option('bg_bibrefs_debug' );
}

