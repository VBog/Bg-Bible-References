<?php 
/******************************************************************************************
	Страница настроек
    отображает содержимое страницы для подменю Bible References
*******************************************************************************************/
function bg_bibfers_options_page() {
// http://azbyka.ru/biblia/?Lk.4:25-5:13,6:1-13&crgli&rus&num=cr


    // имена опций и полей
    $c_lang_name = 'bg_bibfers_c_lang';					// Церковно-славянский
    $r_lang_name = 'bg_bibfers_r_lang';					// Русский
    $g_lang_name = 'bg_bibfers_g_lang';					// Греческий
    $l_lang_name = 'bg_bibfers_l_lang';					// Латинский
    $i_lang_name = 'bg_bibfers_i_lang';					// Иврит
    $c_font_name = 'bg_bibfers_c_font';					// Шрифт для церковно-славянского текста

	$bg_verses_lang = 'bg_bibfers_verses_lang';			// Язык стихов из Библии во всплывающей подсказке
    $bg_show_fn = 'bg_bibfers_show_fn';					// Отображать оригинальные номера стихов

    $target_window = 'bg_bibfers_target';				// Где открыть страницу с текстом Библии
	$bg_headers = 'bg_bibfers_headers';					// Подсвечивать ссылки в заголовках H1-H6
	$bg_interpret = 'bg_bibfers_interpret';				// Включить ссылки на толкование Священного Писания

	$bg_norm_refs = 'bg_bibfers_norm_refs';				// Преобразовывать ссылки к нормализованному виду
	$bg_verses_name = 'bg_bibfers_show_verses';			// Отображать стихи из Библии во всплывающей подсказке

	$bg_curl_name = 'bg_bibfers_curl';					// Чтение файлов Библии с помощью cURL
	$bg_fgc_name = 'bg_bibfers_fgc';					// Чтение файлов Библии с помощью file_get_contents()
	$bg_fopen_name = 'bg_bibfers_fopen';				// Чтение файлов Библии с помощью fopen()
	
	$bg_preq = 'bg_bibfers_prereq';						// Предварительно загружать стихи из Библии в всплывающие подсказки

    $links_class = 'bg_bibfers_class';					// CSS класс для ссылок на Библию
	
	$bg_bibfers_debug_name = 'bg_bibfers_debug';		// Включить запись в лог
	
    $hidden_field_name = 'bg_bibfers_submit_hidden';	// Скрытое поле для проверки обновления информацции в форме
	
	bg_bibrefs_options_ini (); 			// Параметры по умолчанию
	
    // Читаем существующие значения опций из базы данных
    $c_lang_val = get_option( $c_lang_name );
    $r_lang_val = get_option( $r_lang_name );
    $g_lang_val = get_option( $g_lang_name );
    $l_lang_val = get_option( $l_lang_name );
    $i_lang_val = get_option( $i_lang_name );
    $font_val = get_option( $c_font_name );

    $bg_verses_lang_val = get_option( $bg_verses_lang );
    $bg_show_fn_val = get_option( $bg_show_fn );

    $target_val = get_option( $target_window );
    $bg_headers_val = get_option( $bg_headers );
    $bg_interpret_val = get_option( $bg_interpret );

    $bg_norm_refs_val = get_option( $bg_norm_refs );
    $bg_verses_val = get_option( $bg_verses_name );

    $bg_curl_val = get_option( $bg_curl_name );
    $bg_fgc_val = get_option( $bg_fgc_name );
    $bg_fopen_val = get_option( $bg_fopen_name );

    $bg_preq_val = get_option( $bg_preq );

    $class_val = get_option( $links_class );
	
    $bg_bibfers_debug_val = get_option( $bg_bibfers_debug_name );
	
// Проверяем, отправил ли пользователь нам некоторую информацию
// Если "Да", в это скрытое поле будет установлено значение 'Y'
    if( isset( $_POST[ $hidden_field_name ] ) && $_POST[ $hidden_field_name ] == 'Y' ) {

	// Сохраняем отправленное значение в БД
		$c_lang_val = ( isset( $_POST[$c_lang_name] ) && $_POST[$c_lang_name] ) ? $_POST[$c_lang_name] : '' ;
		update_option( $c_lang_name, $c_lang_val );

		$r_lang_val = ( isset( $_POST[$r_lang_name] ) && $_POST[$r_lang_name] ) ? $_POST[$r_lang_name] : '' ;
		update_option( $r_lang_name, $r_lang_val );

		$g_lang_val =( isset( $_POST[$g_lang_name] ) && $_POST[$g_lang_name] ) ? $_POST[$g_lang_name] : '' ;
		update_option( $g_lang_name, $g_lang_val );

		$l_lang_val = ( isset( $_POST[$l_lang_name] ) && $_POST[$l_lang_name] ) ? $_POST[$l_lang_name] : '' ;
		update_option( $l_lang_name, $l_lang_val );

		$i_lang_val = ( isset( $_POST[$i_lang_name] ) && $_POST[$i_lang_name] ) ? $_POST[$i_lang_name] : '' ;
		update_option( $i_lang_name, $i_lang_val );

		$font_val = ( isset( $_POST[$c_font_name] ) && $_POST[$c_font_name] ) ? $_POST[$c_font_name] : '' ;
		update_option( $c_font_name, $font_val );

		$bg_verses_lang_val = ( isset( $_POST[$bg_verses_lang] ) && $_POST[$bg_verses_lang] ) ? $_POST[$bg_verses_lang] : '' ;
		update_option( $bg_verses_lang, $bg_verses_lang_val );

		$bg_show_fn_val = ( isset( $_POST[$bg_show_fn] ) && $_POST[$bg_show_fn] ) ? $_POST[$bg_show_fn] : '' ;
		update_option( $bg_show_fn, $bg_show_fn_val );

		$target_val = ( isset( $_POST[$target_window] ) && $_POST[$target_window] ) ? $_POST[$target_window] : '' ;
		update_option( $target_window, $target_val );

		$bg_headers_val = ( isset( $_POST[$bg_headers] ) && $_POST[$bg_headers] ) ? $_POST[$bg_headers] : '' ;
		update_option( $bg_headers, $bg_headers_val );

		$bg_interpret_val = ( isset( $_POST[$bg_interpret] ) && $_POST[$bg_interpret] ) ? $_POST[$bg_interpret] : '' ;
		update_option( $bg_interpret, $bg_interpret_val );

		$bg_norm_refs_val = ( isset( $_POST[$bg_norm_refs] ) && $_POST[$bg_norm_refs] ) ? $_POST[$bg_norm_refs] : '' ;
		update_option( $bg_norm_refs, $bg_norm_refs_val );

		$bg_verses_val = ( isset( $_POST[$bg_verses_name] ) && $_POST[$bg_verses_name] ) ? $_POST[$bg_verses_name] : '' ;
		update_option( $bg_verses_name, $bg_verses_val );

		$bg_curl_val = ( isset( $_POST[$bg_curl_name] ) && $_POST[$bg_curl_name] ) ? $_POST[$bg_curl_name] : '' ;
		update_option( $bg_curl_name, $bg_curl_val );

		$bg_fgc_val = ( isset( $_POST[$bg_fgc_name] ) && $_POST[$bg_fgc_name] ) ? $_POST[$bg_fgc_name] : '' ;
		update_option( $bg_fgc_name, $bg_fgc_val );

		$bg_fopen_val = ( isset( $_POST[$bg_fopen_name] ) && $_POST[$bg_fopen_name] ) ? $_POST[$bg_fopen_name] : '' ;
		update_option( $bg_fopen_name, $bg_fopen_val );

		$bg_preq_val = ( isset( $_POST[$bg_preq] ) && $_POST[$bg_preq] ) ? $_POST[$bg_preq] : '' ;
		update_option( $bg_preq, $bg_preq_val );

		$class_val = ( isset( $_POST[$links_class] ) && $_POST[$links_class] ) ? $_POST[$links_class] : '' ;
		update_option( $links_class, $class_val );

 		$bg_bibfers_debug_val = ( isset( $_POST[$bg_bibfers_debug_name] ) && $_POST[$bg_bibfers_debug_name] ) ? $_POST[$bg_bibfers_debug_name] : '' ;
		update_option( $bg_bibfers_debug_name, $bg_bibfers_debug_val );

       // Вывести сообщение об обновлении параметров на экран
		echo '<div class="updated"><p><strong>'.__('Options saved.', 'bg_bibfers' ).'</strong></p></div>';
    }
?>
<!--  форма опций -->
    
<table width="100%">
<tr><td valign="top">
<!--  Теперь отобразим опции на экране редактирования -->
<div class="wrap">
<!--  Заголовок -->
<h2><?php _e( 'Bg Bible References Plugin Options', 'bg_bibfers' ); ?></h2>
<p><?php printf( __( 'Version', 'bg_bibfers' ).' <b>'.get_plugin_version().'</b>' ); ?></p>


<!-- Форма настроек -->
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

<!--  Основные параметры -->
<table class="form-table">

<tr valign="top">
<th scope="row"><?php printf(__('Languages of the Bible text on', 'bg_bibfers' ).' <a href="http://azbyka.ru/biblia/" target=_blank>azbyka.ru</a>'); ?></th>
<td>
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<input type="checkbox" id="c_lang" name="<?php echo $c_lang_name ?>" <?php if($c_lang_val=="c") echo "checked" ?> value="c" onclick='c_lang_checked();'> <?php _e('Church Slavic', 'bg_bibfers' ); ?><br />
<input type="checkbox" id="r_lang" name="<?php echo $r_lang_name ?>" <?php if($r_lang_val=="r") echo "checked" ?>  value="r"> <?php _e('Russian', 'bg_bibfers' ); ?><br />
<input type="checkbox" id="g_lang" name="<?php echo $g_lang_name ?>" <?php if($g_lang_val=="g") echo "checked" ?>  value="g"> <?php _e('Greek', 'bg_bibfers' ); ?><br />
<input type="checkbox" id="l_lang" name="<?php echo $l_lang_name ?>" <?php if($l_lang_val=="l") echo "checked" ?>  value="l"> <?php _e('Latin', 'bg_bibfers' ); ?><br />
<input type="checkbox" id="i_lang" name="<?php echo $i_lang_name ?>" <?php if($i_lang_val=="i") echo "checked" ?>  value="i"> <?php _e('Hebrew', 'bg_bibfers' ); ?><br />
</td></tr>
<tr valign="top">
<th scope="row"><?php _e('Font for Church Slavonic text', 'bg_bibfers' ); ?></th>
<td>
<input type="radio" id="ucs" name="<?php echo $c_font_name ?>" <?php if($font_val=="ucs") echo "checked" ?> value="ucs"> <?php _e('Church Slavic font', 'bg_bibfers' ); ?><br />
<input type="radio" id="rus" name="<?php echo $c_font_name ?>" <?php if($font_val=="rus") echo "checked" ?> value="rus"> <?php _e('Russian font ("Old" style)', 'bg_bibfers' ); ?><br />
<input type="radio" id="hip" name="<?php echo $c_font_name ?>" <?php if($font_val=="hip") echo "checked" ?> value="hip"> <?php _e('HIP-standard', 'bg_bibfers' ); ?><br />
<script>
function c_lang_checked() {
	elRadio = document.getElementsByName('<?php echo $c_font_name ?>');
	for (var i = 0; i < elRadio.length; i++) {
		if (document.getElementById('c_lang').checked == true) {elRadio[i].disabled = false;}
		else {elRadio[i].disabled = true;}
	}
}
c_lang_checked();
</script>
</td></tr>
<tr valign="top">
<th scope="row"><?php _e('Language of references and tooltips', 'bg_bibfers' ); ?></th>
<td>
<select id="bg_verses_lang" name="<?php echo $bg_verses_lang ?>"> 
	<option <?php if($bg_verses_lang_val=="") echo "selected" ?> value=""><?php _e('Default', 'bg_bibfers' ); ?></option>
	<?php
		$path = dirname(dirname( __FILE__ )).'/bible/';
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
		}
	?>
</select>
</td></tr>
<tr valign="top">
<th scope="row"><?php _e('Show original verse numbers', 'bg_bibfers' ); ?></th>
<td>
<input type="checkbox" id="bg_show_fn" name="<?php echo $bg_show_fn ?>" <?php if($bg_show_fn_val=="on") echo "checked" ?>  value="on"> <?php _e('<br><i>(Show the original verse numbers in parentheses after the verse numbers of Russian Synodal Translation in the tooltips and quotes.<br>Verses marked with asterisk * are absent in the original translation. * - always visible!)</i>', 'bg_bibfers' ); ?> <br />
</td></tr>
<tr valign="top">
<th scope="row"><?php _e('Open links', 'bg_bibfers' ); ?></th>
<td>
<input type="radio" id="blank_window" name="<?php echo $target_window ?>" <?php if($target_val=="_blank") echo "checked" ?> value="_blank"> <?php _e('in new window', 'bg_bibfers' ); ?><br />
<input type="radio" id="self_window" name="<?php echo $target_window ?>" <?php if($target_val=="_self") echo "checked" ?> value="_self"> <?php _e('in current window', 'bg_bibfers' ); ?><br />
</td></tr>
<tr valign="top">
<th scope="row"><?php _e('Highlight references in the headers H1...H6', 'bg_bibfers' ); ?></th>
<td>
<input type="checkbox" id="bg_headers" name="<?php echo $bg_headers ?>" <?php if($bg_headers_val=="on") echo "checked" ?>  value="on"> <br />
</td></tr>
<tr valign="top">
<th scope="row"><?php _e('Enable links to the interpretation of the Holy Scriptures', 'bg_bibfers' ); ?></th>
<td>
<input type="checkbox" id="bg_interpret" name="<?php echo $bg_interpret ?>" <?php if($bg_interpret_val=="on") echo "checked" ?>  value="on"> <?php _e('<br><i>(Tooltips and Short Codes)</i>', 'bg_bibfers' ); ?> <br />
</td></tr></table>

<!--  Дополнительные параметры -->
<a href='#' onclick='options_view();'><?php _e( 'Additional options...', 'bg_bibfers' ); ?></a>
<script>
function options_view() {
	var el = document.getElementById('add_options');
	if (el.style.display == 'none') {
		el.style.display = '';
	} else {
		el.style.display = 'none';
	}
}
</script>

<table id='add_options' class="form-table" style='display: none'>
<tr valign="top">
<th scope="row"><?php _e('Convert references to the normalized form', 'bg_bibfers' ); ?></th>
<td>
<input type="checkbox" id="bg_norm_refs" name="<?php echo $bg_norm_refs ?>" <?php if($bg_norm_refs_val=="on") echo "checked" ?>  value="on"> <br />
</td></tr>
<tr valign="top">
<th scope="row"><?php _e('Show Bible verses in popup', 'bg_bibfers' ); ?></th>
<td>
<input type="checkbox" id="bg_verses" name="<?php echo $bg_verses_name ?>" <?php if($bg_verses_val=="on") echo "checked" ?>  value="on" onclick='bg_verses_checked();'> <?php _e('<br><i>(If this option is disabled or data are not received from the server,<br>popup showing the Bible book title, chapter number and verse numbers)</i>', 'bg_bibfers' ); ?> <br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Preload Bible verses in tooltips', 'bg_bibfers' ); ?></th>
<td>
<input type="checkbox" id="bg_preq" name="<?php echo $bg_preq ?>" <?php if($bg_preq_val=="on") echo "checked" ?>  value="on"> <?php _e('<br><i>(Try this option on a slow server.<br><u>Warning:</u> You can have a problem with ajax-requests limiting on the server.)</i>', 'bg_bibfers' ); ?> <br />
</td></tr>
<script>
function bg_verses_checked() {
	if (document.getElementById('bg_verses').checked == true) {
		document.getElementById('bg_preq').disabled = false;
	} else {
		document.getElementById('bg_preq').disabled = true;
		document.getElementById('bg_preq').checked = false;
	}
}
bg_verses_checked();
</script>

<tr valign="top">
<th scope="row"><?php _e('Method of reading files', 'bg_bibfers' ); ?></th>
<td>
<input type="checkbox" id="bg_fgc" name="<?php echo $bg_fgc_name ?>" <?php if($bg_fgc_val=="on") echo "checked" ?>  value="on" onclick='reading_off_checked();'> file_get_contents()<br />
<input type="checkbox" id="bg_fopen" name="<?php echo $bg_fopen_name ?>" <?php if($bg_fopen_val=="on") echo "checked" ?>  value="on" onclick='reading_off_checked();'> fopen() - fread() - fclose()<br />
<input type="checkbox" id="bg_curl" name="<?php echo $bg_curl_name ?>" <?php if($bg_curl_val=="on") echo "checked" ?> value="on" onclick='reading_off_checked();'> cURL<br />
<?php _e('<i>(Plugin tries to read Bible files with marked methods in the order listed.<br>To do the reading faster, disable unnecessary methods - you need one only. <br><u>Warning:</u> Some methods may not be available on your server.)</i>', 'bg_bibfers' ); ?> <br />
</td></tr>
<script>
function reading_off_checked() {
	if (document.getElementById('bg_curl').checked == true || document.getElementById('bg_fgc').checked == true || document.getElementById('bg_fopen').checked == true) {
		document.getElementById('bg_verses').disabled = false;
	} else {
		document.getElementById('bg_verses').disabled = true;
		document.getElementById('bg_verses').checked = false;
		document.getElementById('bg_preq').disabled = true;
		document.getElementById('bg_preq').checked = false;
	}
}
reading_off_checked();
</script>
<tr valign="top">
<th scope="row"><?php _e('Reference links CSS class', 'bg_bibfers' ); ?></th>
<td>
<input type="text" id="links_class" name="<?php echo $links_class ?>" size="20" value="<?php echo $class_val ?>"><br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Debug', 'bg_bibfers' ); ?></th>
<td>
<input type="checkbox" id="bg_bibfers_debug" name="<?php echo $bg_bibfers_debug_name ?>" <?php if($bg_bibfers_debug_val=="on") echo "checked" ?>  value="on"'> <?php _e('<br><i>(If you enable this option the debug information will written to the file "debug.log" in the plugin directory.<br>The file will be updated in 30 minutes after the last record, or the filesize exceed 2 Mb.<br><font color="red"><b>Disable this option after the end of debugging!</b></font>)</i>', 'bg_bibfers' ); ?> <br />
</td></tr>

</table>
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'bg_bibfers' ) ?>" />
</p>

</form>
</div>
</td>

<!-- Информация о плагине -->
<td valign="top" align="left" width="45em">

<div class="bg_bibfers_info_box">

	<h3><?php _e('Thanks for using Bg Biblie References', 'bg_bibfers') ?></h3>
	<p class="bg_bibfers_gravatar"><a href="http://bogaiskov.ru" target="_blank"><?php echo get_avatar("vadim.bogaiskov@gmail.com", '64'); ?></a></p>
	<p><?php _e('Dear brothers and sisters!<br />Thank you for using my plugin!<br />I hope it is useful for your site.', 'bg_bibfers') ?></p>
	<p class="bg_bibfers_author"><a href="http://bogaiskov.ru" target="_blank"><?php _e('Vadim Bogaiskov', 'bg_bibfers') ?></a></p>

	<h3><?php _e('I like this plugin<br>– how can I thank you?', 'bg_bibfers') ?></h3>
	<p><?php _e('There are several ways for you to say thanks:', 'bg_bibfers') ?></p>
	<ul>
		<li><?php printf(__('<a href="%1$s" target="_blank">Give a donation</a>  for the construction of the church of Sts. Peter and Fevronia in Marino', 'bg_bibfers'), "http://hpf.ru.com/donate/") ?></li>
		<li><?php printf(__('<a href="%1$s" target="_blank">Give 5 stars</a> over at the WordPress Plugin Directory', 'bg_bibfers'), "http://wordpress.org/support/view/plugin-reviews/bg-biblie-references") ?></li>
		<li><?php printf(__('Share infotmation or make a nice blog post about the plugin', 'bg_bibfers')) ?></li>
	</ul>
	<div class="share42init" align="center" data-url="http://bogaiskov.ru/bg_bibfers/" data-title="<?php _e('Bg Bible References really cool plugin for Orthodox WordPress sites', 'bg_bibfers') ?>"></div>
	<script type="text/javascript" src="<?php printf( plugins_url( 'share42/share42.js' , dirname(__FILE__) ) ) ?>"></script>

	<h3><?php _e('Support', 'bg_bibfers') ?></h3>
	<p><?php printf(__('Please see the <a href="%1$s" target="_blank">support forum</a> or my <a href="%2$s" target="_blank">personal site</a> for help.', 'bg_bibfers'), "http://wordpress.org/support/plugin/bg-biblie-references", "http://bogaiskov.ru/bg_bibfers/") ?></p>
	
	<p class="bg_bibfers_close"><?php _e("God protect you!", 'bg_bibfers') ?></p>
</div>
</td></tr></table>
<?php 

} 

// Задание параметров по умолчанию
function bg_bibrefs_options_ini () {
	add_option('bg_bibfers_c_lang', "c");
	add_option('bg_bibfers_r_lang', "r");
	add_option('bg_bibfers_g_lang');
	add_option('bg_bibfers_l_lang');
	add_option('bg_bibfers_i_lang');
	add_option('bg_bibfers_c_font', "ucs");
	add_option('bg_bibfers_verses_lang', "");
	add_option('bg_bibfers_show_fn', "");
	add_option('bg_bibfers_target', "_blank");
	add_option('bg_bibfers_headers', "on");
	add_option('bg_bibfers_interpret', "on");
	add_option('bg_bibfers_norm_refs');
	add_option('bg_bibfers_show_verses', "on");
	add_option('bg_bibfers_curl', "on");
	add_option('bg_bibfers_fgc', "on");
	add_option('bg_bibfers_fopen', "on");
	add_option('bg_bibfers_prereq');
	add_option('bg_bibfers_class', "bg_bibfers");
	add_option('bg_bibfers_debug', "");
}

// Очистка таблицы параметров при удалении плагина
function bg_bibfers_deinstall() {
	delete_option('bg_bibfers_c_lang');
	delete_option('bg_bibfers_r_lang');
	delete_option('bg_bibfers_g_lang');
	delete_option('bg_bibfers_l_lang');
	delete_option('bg_bibfers_i_lang');
	delete_option('bg_bibfers_c_font');
	delete_option('bg_bibfers_verses_lang');
	delete_option('bg_bibfers_show_fn');
	delete_option('bg_bibfers_target');
	delete_option('bg_bibfers_headers');
	delete_option('bg_bibfers_interpret');
	delete_option('bg_bibfers_norm_refs');
	delete_option('bg_bibfers_show_verses');
	delete_option('bg_bibfers_curl');
	delete_option('bg_bibfers_fgc');
	delete_option('bg_bibfers_fopen');
	delete_option('bg_bibfers_prereq');
	delete_option('bg_bibfers_class');
	delete_option('bg_bibfers_debug');

	delete_option('bg_bibfers_submit_hidden');
}

function bg_bibfers_get_options () {
	global $bg_bibfers_option;

// Читаем существующие значения опций из базы данных
	$opt = "";
	$c_lang_val = get_option( 'bg_bibfers_c_lang' );
    $r_lang_val = get_option( 'bg_bibfers_r_lang' );
    $g_lang_val = get_option( 'bg_bibfers_g_lang' );
    $l_lang_val = get_option( 'bg_bibfers_l_lang' );
    $i_lang_val = get_option( 'bg_bibfers_i_lang' );
	$lang_val = $c_lang_val.$r_lang_val.$g_lang_val.$l_lang_val.$i_lang_val;
	$font_val = get_option( 'bg_bibfers_c_font' );
	if ($lang_val) $opt = "&".$lang_val;
	if ($font_val && $c_lang_val) $opt = $opt."&".$font_val;
	$bg_bibfers_option['azbyka'] = $opt;
	
// Общие параметры	отображения ссылок
    $bg_bibfers_option['target'] = get_option( 'bg_bibfers_target' );
    $bg_bibfers_option['class'] = get_option( 'bg_bibfers_class' );
	if ($bg_bibfers_option['class'] == "") $bg_bibfers_option['class'] = 'bg_bibfers';
	$bg_bibfers_option['show_verses'] = get_option( 'bg_bibfers_show_verses' );	

    $bg_bibfers_option['verses_lang'] = get_option( 'bg_bibfers_verses_lang' );
    $bg_bibfers_option['show_fn'] = get_option( 'bg_bibfers_show_fn' );

    $bg_bibfers_option['headers'] = get_option( 'bg_bibfers_headers' );
    $bg_bibfers_option['interpret'] = get_option( 'bg_bibfers_interpret' );

    $bg_bibfers_option['norm_refs'] = get_option( 'bg_bibfers_norm_refs' );

    $bg_bibfers_option['curl'] = get_option( 'bg_bibfers_curl' );
    $bg_bibfers_option['fgc'] = get_option( 'bg_bibfers_fgc' );
    $bg_bibfers_option['fopen'] = get_option( 'bg_bibfers_fopen' );

    $bg_bibfers_option['preq'] = get_option('bg_bibfers_prereq' );
	
    $bg_bibfers_option['debug'] = get_option('bg_bibfers_debug' );
}