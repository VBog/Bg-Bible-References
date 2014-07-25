<?php
/* 
    Plugin Name: Bg Bible References 
    Plugin URI: http://bogaiskov.ru/bg_bibfers/
    Description: Плагин подсвечивает ссылки на текст Библии с помощью гиперссылок на сайт <a href="http://azbyka.ru/">Православной энциклопедии "Азбука веры"</a> и толкование Священного Писания на сайте <a href="http://bible.optina.ru/">монастыря "Оптина Пустынь"</a>. / The plugin will highlight references to the Bible text with links to site of <a href="http://azbyka.ru/">Orthodox encyclopedia "The Alphabet of Faith"</a> and interpretation of Scripture on the site of the <a href="http://bible.optina.ru/">monastery "Optina Pustyn"</a>.
    Author: Vadim Bogaiskov
    Version: 3.0
    Author URI: http://bogaiskov.ru 
*/

/*  Copyright 2013  Vadim Bogaiskov  (email: vadim.bogaiskov@gmail.com)

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

define('BG_BIBREFS_VERSION', '3.0');

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
if ( !is_admin() ) {
	add_action( 'wp_enqueue_scripts' , 'bg_enqueue_frontend_scripts' ); 

}

// Загрузка интернационализации
load_plugin_textdomain( 'bg_bibfers', false, dirname( plugin_basename( __FILE__ )) . '/languages/' );

// Подключаем дополнительные модули
include_once('includes/settings.php');
include_once('includes/references.php');
include_once('includes/quotes.php');
$bg_verses_lang_val = get_option( 'bg_bibfers_verses_lang' );
$bible_lang = (($bg_verses_lang_val=="")?__('ru', 'bg_bibfers' ):$bg_verses_lang_val);
include_once('bible/'.$bible_lang.'/books.php');

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
// Регистрируем шорт-код references
	add_shortcode( 'references', 'bg_bibfers_references' );
// Регистрируем шорт-код no_refs
	add_shortcode( 'norefs', 'bg_bibfers_norefs' );
}
 
/*****************************************************************************************
	Функции запуска плагина
	
******************************************************************************************/
 
// Функция обработки ссылок на Библию 
function bg_bibfers($content) {
	global $post;
	$norefs_posts_val = get_post_meta($post->ID, 'norefs', true);
	if (!$norefs_posts_val && !in_category( 'norefs' ) && !has_tag( 'norefs' )) $content = bg_bibfers_bible_proc($content);
	return $content;
}
// Функция обработки шорт-кода bible
function bg_bibfers_qoutes( $atts, $content=null ) {
	extract( shortcode_atts( array(
		'book' => '',
		'ch' => '1-999',
		'type' => 'verses'
	), $atts ) );
	
	$book = bg_bibfers_getBook($book);
	if ($content) $quote = bg_bibfers_bible_proc($content, $type);
	else if ($book != '') $quote = bg_bibfers_getQuotes($book, $ch, $type);
	else return "";
	if ($quote != "") {
		$class_val = get_option( 'bg_bibfers_class' );
		if ($class_val == "") $class_val = 'bg_bibfers';
		$quote = "<span class='".$class_val."'>".$quote."</span>";
	}
	return "{$quote}";
}

// Функция обработки шорт-кода references
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

// Функция обработки шорт-кода norefs
function bg_bibfers_norefs( $atts, $content = null ) {
	 return do_shortcode($content);
}

// Функция действия перед крючком добавления меню
function bg_bibfers_add_pages() {
    // Добавим новое подменю в раздел Параметры 
    add_options_page( __('Bible References', 'bg_bibfers' ), __('Bible References', 'bg_bibfers' ), 'manage_options', __FILE__, 'bg_bibfers_options_page');
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
	if (!$type) $type = 'verses';
	$expand_button = '<img src="'.plugins_url( '/js/expand.png' , __FILE__ ).'" style="cursor:pointer" align="right" width=16 height=16 title1="'.(__('Expand', 'bg_bibfers' )).'" title2="'.(__('Hide', 'bg_bibfers' )).'" />';
	echo $expand_button.bg_bibfers_getQuotes($title, $chapter, $type); 
	
	die();
}
function get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__  );
	return $plugin_data['Version'];
}