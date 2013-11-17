<?php
/* 
    Plugin Name: Bg Bible References 
    Plugin URI: http://bogaiskov.ru/bg_bibfers/
    Description: Плагин подсвечивает ссылки на текст Библии с помощью гиперссылок на сайт <a href="http://azbyka.ru/">Православной энциклопедии "Азбука веры"</a>. / The plugin will highlight references to the Bible text with links to site of <a href="http://azbyka.ru/">Orthodox encyclopedia "The Alphabet of Faith"</a>.
    Author: Vadim Bogaiskov
    Version: 2.3.2
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

// Таблица стилей для плагина
	add_action( 'wp_enqueue_scripts' , 'bg_enqueue_frontend_styles' );
	add_action( 'admin_enqueue_scripts' , 'bg_enqueue_frontend_styles' );
	function bg_enqueue_frontend_styles () {
		wp_enqueue_style( "bg_bibfers_styles", plugins_url( '/css/styles.css' , plugin_basename(__FILE__) ) );
	}

// Загрузка интернационализации
load_plugin_textdomain( 'bg_bibfers', false, dirname( plugin_basename( __FILE__ )) . '/languages/' );

// Подключаем дополнительные модули
include_once('includes/settings.php');
include_once('includes/references.php');
include_once('includes/quotes.php');


define('BG_BIBREFS_VERSION', '2.3.2');
if ( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts' , 'bg_enqueue_frontend_scripts' );
	function bg_enqueue_frontend_scripts () {
		wp_enqueue_script( 'bg_bibrefs_proc', plugins_url( 'js/bg_bibrefs.js' , __FILE__ ), false, BG_BIBREFS_VERSION, true );
	}
}

if ( defined('ABSPATH') && defined('WPINC') ) {
// Регистрируем крючок для обработки контента при его загрузке
	add_filter( 'the_content', 'bg_bibfers' );
// Регистрируем крючок для добавления меню администратора
	add_action('admin_menu', 'bg_bibfers_add_pages');
	delete_option('g_bibfers_show_verses');					// Исправление ошибки - удалить строку в следующей версии
// Регистрируем крючок на удаление плагина
	if (function_exists('register_uninstall_hook')) {
		register_uninstall_hook(__FILE__, 'bg_bibfers_deinstall');
	}
// Регистрируем шорт-код bible
	add_shortcode( 'bible', 'bg_bibfers_qoutes' );
}
 
/*****************************************************************************************
	Функции запуска плагина
	
******************************************************************************************/
 
// Функция обработки ссылок на Библию 
function bg_bibfers($content) {
//	if ( is_single() || is_page())
		$content = bg_bibfers_bible_proc($content);
	return $content;
}
// Функция обработки шорт-кода bible
function bg_bibfers_qoutes( $atts ) {
	extract( shortcode_atts( array(
		'book' => '',
		'ch' => '1-99',
		'type' => 'verses'		
	), $atts ) );
	$quote = bg_bibfers_getQuotes($book, $ch, $type);
	return "{$quote}";
}
// Функция действия перед крючком добавления меню
function bg_bibfers_add_pages() {
    // Добавим новое подменю в раздел Параметры 
    add_options_page( __('Bible References', 'bg_bibfers' ), __('Bible References', 'bg_bibfers' ), 'manage_options', __FILE__, 'bg_bibfers_options_page');
}	
// Задание параметров по умолчанию
function bg_bibrefs_options_ini () {
	add_option('bg_bibfers_c_lang', "c");
	add_option('bg_bibfers_r_lang', "r");
	add_option('bg_bibfers_g_lang');
	add_option('bg_bibfers_l_lang');
	add_option('bg_bibfers_i_lang');
	add_option('bg_bibfers_c_font', "ucs");
	add_option('bg_bibfers_target', "_blank");
	add_option('bg_bibfers_class', "bg_bibfers");
	add_option('bg_bibfers_show_verses', "on");
}

// Очистка таблицы параметров при удалении плагина
function bg_bibfers_deinstall() {
	delete_option('bg_bibfers_c_lang');
	delete_option('bg_bibfers_r_lang');
	delete_option('bg_bibfers_g_lang');
	delete_option('bg_bibfers_l_lang');
	delete_option('bg_bibfers_i_lang');
	delete_option('bg_bibfers_c_font');
	delete_option('bg_bibfers_target');
	delete_option('bg_bibfers_class');
	delete_option('bg_bibfers_show_verses');

	delete_option('bg_bibfers_submit_hidden');
}


