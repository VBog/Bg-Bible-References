<?php 
/******************************************************************************************
	Страница настроек
    отображает содержимое страницы для подменю Bible References
*******************************************************************************************/
function bg_bibrefs_options_page() {
// http://azbyka.ru/biblia/?Lk.4:25-5:13,6:1-13&crgli&rus&num=cr

	$active_tab = 'links';
	if( isset( $_GET[ 'tab' ] ) ) $active_tab = $_GET[ 'tab' ];
	
    // имена опций и полей
	$bg_bibrefs_site = 'bg_bibrefs_site';				// Имя ссылки
	
    $c_lang_name = 'bg_bibrefs_c_lang';					// Церковно-славянский
    $r_lang_name = 'bg_bibrefs_r_lang';					// Русский
    $g_lang_name = 'bg_bibrefs_g_lang';					// Греческий
    $l_lang_name = 'bg_bibrefs_l_lang';					// Латинский
    $i_lang_name = 'bg_bibrefs_i_lang';					// Иврит
    $c_font_name = 'bg_bibrefs_c_font';					// Шрифт для церковно-славянского текста

	$bg_bibrefs_page = 'bg_bibrefs_page';				// Ссылка на предварительно созданную страницу для вывода текста Библии

	$bg_verses_lang = 'bg_bibrefs_verses_lang';			// Язык стихов из Библии во всплывающей подсказке
    $bg_show_fn = 'bg_bibrefs_show_fn';					// Отображать оригинальные номера стихов

    $target_window = 'bg_bibrefs_target';				// Где открыть страницу с текстом Библии
	$bg_headers = 'bg_bibrefs_headers';					// Подсвечивать ссылки в заголовках H1-H6
	$bg_interpret = 'bg_bibrefs_interpret';				// Включить ссылки на толкование Священного Писания
	$bg_parallel = 'bg_bibrefs_parallel';				// Включить ссылки на паралельные места Священного Писания

	$bg_norm_refs = 'bg_bibrefs_norm_refs';				// Преобразовывать ссылки к нормализованному виду
	$bg_verses_name = 'bg_bibrefs_show_verses';			// Отображать стихи из Библии во всплывающей подсказке

	$bg_curl_name = 'bg_bibrefs_curl';					// Чтение файлов Библии с помощью cURL
	$bg_fgc_name = 'bg_bibrefs_fgc';					// Чтение файлов Библии с помощью file_get_contents()
	$bg_fopen_name = 'bg_bibrefs_fopen';				// Чтение файлов Библии с помощью fopen()
	
	$bg_bibrefs_pload = 'bg_bibrefs_preload';			// Предварительно загружать стихи из Библии в всплывающие подсказки - до создания строницы (php)
	$bg_bibrefs_preq = 'bg_bibrefs_prereq';				// Предварительно загружать стихи из Библии в всплывающие подсказки - после создания страницы (ajax)
	
	$bg_bibrefs_maxtime = "bg_bibrefs_maxtime";			// Максимальное время работы скрипта

    $bg_bibrefs_ajaxurl = "bg_bibrefs_ajaxurl";			// Внешний AJAX Proxy
	$bg_content = 'bg_bibrefs_content';					// Контейнер, внутри которого будут отображаться подсказки
	$links_class = 'bg_bibrefs_class';					// CSS класс для ссылок на Библию
	$bg_refs_file = 'bg_bibrefs_refs_file';				// Пользовательский файл цитат из Библии
	
	$bg_bibrefs_debug_name = 'bg_bibrefs_debug';		// Включить запись в лог
	
    $hidden_field_name = 'bg_bibrefs_submit_hidden';	// Скрытое поле для проверки обновления информацции в форме
	
	bg_bibrefs_options_ini (); 			// Параметры по умолчанию
	
    // Читаем существующие значения опций из базы данных
    $bg_bibrefs_site_val = get_option( $bg_bibrefs_site );
	
    $c_lang_val = get_option( $c_lang_name );
    $r_lang_val = get_option( $r_lang_name );
    $g_lang_val = get_option( $g_lang_name );
    $l_lang_val = get_option( $l_lang_name );
    $i_lang_val = get_option( $i_lang_name );
    $font_val = get_option( $c_font_name );

    $bg_bibrefs_page_val = get_option( $bg_bibrefs_page );
	
    $bg_verses_lang_val = get_option( $bg_verses_lang );
    $bg_show_fn_val = get_option( $bg_show_fn );

    $target_val = get_option( $target_window );
    $bg_headers_val = get_option( $bg_headers );
    $bg_interpret_val = get_option( $bg_interpret );
    $bg_parallel_val = get_option( $bg_parallel );

    $bg_norm_refs_val = get_option( $bg_norm_refs );
    $bg_verses_val = get_option( $bg_verses_name );

    $bg_curl_val = get_option( $bg_curl_name );
    $bg_fgc_val = get_option( $bg_fgc_name );
    $bg_fopen_val = get_option( $bg_fopen_name );

    $bg_bibrefs_pload_val = get_option( $bg_bibrefs_pload );
	$bg_bibrefs_preq_val = get_option( $bg_bibrefs_preq );
	
	$bg_bibrefs_maxtime_val = (int) get_option($bg_bibrefs_maxtime);

	$bg_bibrefs_ajaxurl_val = get_option($bg_bibrefs_ajaxurl);
    $bg_content_val = get_option( $bg_content );
    $class_val = get_option( $links_class );
    $bg_refs_file_val = get_option( $bg_refs_file );
	
    $bg_bibrefs_debug_val = get_option( $bg_bibrefs_debug_name );
	
// Проверяем, отправил ли пользователь нам некоторую информацию
// Если "Да", в это скрытое поле будет установлено значение 'Y'
    if( isset( $_POST[ $hidden_field_name ] ) && $_POST[ $hidden_field_name ] == 'Y' ) {

	// Сохраняем отправленное значение в БД
		$bg_bibrefs_site_val = ( isset( $_POST[$bg_bibrefs_site] ) && $_POST[$bg_bibrefs_site] ) ? $_POST[$bg_bibrefs_site] : '' ;
		update_option( $bg_bibrefs_site, $bg_bibrefs_site_val );

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

		$bg_bibrefs_page_val = ( isset( $_POST[$bg_bibrefs_page] ) && $_POST[$bg_bibrefs_page] ) ? $_POST[$bg_bibrefs_page] : '' ;
		update_option( $bg_bibrefs_page, $bg_bibrefs_page_val );

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

		$bg_parallel_val = ( isset( $_POST[$bg_parallel] ) && $_POST[$bg_parallel] ) ? $_POST[$bg_parallel] : '' ;
		update_option( $bg_parallel, $bg_parallel_val );

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

		$bg_bibrefs_pload_val = ( isset( $_POST[$bg_bibrefs_pload] ) && $_POST[$bg_bibrefs_pload] ) ? $_POST[$bg_bibrefs_pload] : '' ;
		update_option( $bg_bibrefs_pload, $bg_bibrefs_pload_val );

		$bg_bibrefs_preq_val = ( isset( $_POST[$bg_bibrefs_preq] ) && $_POST[$bg_bibrefs_preq] ) ? $_POST[$bg_bibrefs_preq] : '' ;
		update_option( $bg_bibrefs_preq, $bg_bibrefs_preq_val );

		$bg_bibrefs_maxtime_val = ( isset( $_POST[$bg_bibrefs_maxtime] ) && $_POST[$bg_bibrefs_maxtime] ) ? $_POST[$bg_bibrefs_maxtime] : '' ;
		update_option( $bg_bibrefs_maxtime, $bg_bibrefs_maxtime_val );

		$bg_bibrefs_ajaxurl_val = ( isset( $_POST[$bg_bibrefs_ajaxurl] ) && $_POST[$bg_bibrefs_ajaxurl] ) ? $_POST[$bg_bibrefs_ajaxurl] : '' ;
		update_option( $bg_bibrefs_ajaxurl, $bg_bibrefs_ajaxurl_val );

		$bg_content_val = ( isset( $_POST[$bg_content] ) && $_POST[$bg_content] ) ? $_POST[$bg_content] : '' ;
		update_option( $bg_content, $bg_content_val );

		$class_val = ( isset( $_POST[$links_class] ) && $_POST[$links_class] ) ? $_POST[$links_class] : '' ;
		update_option( $links_class, $class_val );

		$bg_refs_file_val = ( isset( $_POST[$bg_refs_file] ) && $_POST[$bg_refs_file] ) ? $_POST[$bg_refs_file] : '' ;
		update_option( $bg_refs_file, $bg_refs_file_val );

 		$bg_bibrefs_debug_val = ( isset( $_POST[$bg_bibrefs_debug_name] ) && $_POST[$bg_bibrefs_debug_name] ) ? $_POST[$bg_bibrefs_debug_name] : '' ;
		update_option( $bg_bibrefs_debug_name, $bg_bibrefs_debug_val );

       // Вывести сообщение об обновлении параметров на экран
		echo '<div class="updated"><p><strong>'.__('Options saved.', 'bg_bibrefs' ).'</strong></p></div>';
    }
?>
<!--  форма опций -->
    
<table width="100%">
<tr><td valign="top">
<!--  Теперь отобразим опции на экране редактирования -->
<div class="wrap">
<!--  Заголовок -->
<h2><?php _e( 'Bg Bible References Plugin Options', 'bg_bibrefs' ); ?></h2>
<div id="bg_bibrefs_resalt"></div>
<p><?php printf( __( 'Version', 'bg_bibrefs' ).' <b>'.get_plugin_version().'</b>' ); ?></p>

<h2 class="nav-tab-wrapper">
	<a href="?page=bg-biblie-references%2Fbg_bibrefs.php&tab=links" class="nav-tab <?php echo $active_tab == 'links' ? 'nav-tab-active' : ''; ?>"><?php _e('Links', 'bg_bibrefs') ?></a>
	<a href="?page=bg-biblie-references%2Fbg_bibrefs.php&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'bg_bibrefs') ?></a>
	<a href="?page=bg-biblie-references%2Fbg_bibrefs.php&tab=additional" class="nav-tab <?php echo $active_tab == 'additional' ? 'nav-tab-active' : ''; ?>"><?php _e('Additional options', 'bg_bibrefs') ?></a>
	<a href="?page=bg-biblie-references%2Fbg_bibrefs.php&tab=bible" class="nav-tab <?php echo $active_tab == 'bible' ? 'nav-tab-active' : ''; ?>"><?php _e('Bible books', 'bg_bibrefs') ?></a>
</h2>

<!-- Загрузка книг Библии -->
<?php if ($active_tab == 'bible') { 

	include_once ('books.php');

    //Create an instance of our package class...
    $bg_bibrefs_bible_ListTable = new bg_bibrefs_Bible_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $bg_bibrefs_bible_ListTable->prepare_items();
    
?>
<div class="wrap">
	<div id="icon-users" class="icon32"><br/></div>
	<h2><?php _e('Choice Bible books', 'bg_bibrefs') ?></h2>
<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="bg_bibrefs_books" method="get"> 
		<!-- For plugins, we also need to ensure that the form posts back to our current page -->
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<input type="hidden" name="tab" value="<?php echo $_REQUEST['tab'] ?>" />
		<!-- Now we can render the completed list table -->
		<?php $bg_bibrefs_bible_ListTable->display(); ?>
	</form> 
</div>
<?php } else { ?>

<!-- Форма настроек -->
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

<!--  Основные параметры -->

<!--  Адрес ссылки -->
<table class="form-table" style="display: <?php echo $active_tab == 'links' ? '' : 'none'; ?>;">
<tr valign="top">
<th scope="row">
<input type="radio" id="bg_bibrefs_site1" name="<?php echo $bg_bibrefs_site ?>" <?php if($bg_bibrefs_site_val=="azbyka") echo "checked" ?> value="azbyka" onclick='bg_bibrefs_site_checked();'> <?php _e('Links to the Bible on <a href="http://azbyka.ru/biblia/" target=_blank>azbyka.ru</a>', 'bg_bibrefs' ); ?>
</th>
<td id="bg_bibrefs_azbyka_lang">
<div>
<?php printf(__('Languages of the Bible text on', 'bg_bibrefs' ).' <a href="http://azbyka.ru/biblia/" target=_blank>azbyka.ru</a>'); ?><br />
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<input type="checkbox" id="c_lang" name="<?php echo $c_lang_name ?>" <?php if($c_lang_val=="c") echo "checked" ?> value="c" onclick='c_lang_checked();'> <?php _e('Church Slavic', 'bg_bibrefs' ); ?><br />
<input type="checkbox" id="r_lang" name="<?php echo $r_lang_name ?>" <?php if($r_lang_val=="r") echo "checked" ?>  value="r"> <?php _e('Russian', 'bg_bibrefs' ); ?><br />
<input type="checkbox" id="g_lang" name="<?php echo $g_lang_name ?>" <?php if($g_lang_val=="g") echo "checked" ?>  value="g"> <?php _e('Greek', 'bg_bibrefs' ); ?><br />
<input type="checkbox" id="l_lang" name="<?php echo $l_lang_name ?>" <?php if($l_lang_val=="l") echo "checked" ?>  value="l"> <?php _e('Latin', 'bg_bibrefs' ); ?><br />
<input type="checkbox" id="i_lang" name="<?php echo $i_lang_name ?>" <?php if($i_lang_val=="i") echo "checked" ?>  value="i"> <?php _e('Hebrew', 'bg_bibrefs' ); ?><br />
</div>
</td></tr>

<tr valign="top" id="bg_bibrefs_azbyka_font">
<th scope="row"></th>
<td>
<?php _e('Font for Church Slavonic text', 'bg_bibrefs' ); ?><br />
<input type="radio" id="ucs" name="<?php echo $c_font_name ?>" <?php if($font_val=="ucs") echo "checked" ?> value="ucs"> <?php _e('Church Slavic font', 'bg_bibrefs' ); ?><br />
<input type="radio" id="rus" name="<?php echo $c_font_name ?>" <?php if($font_val=="rus") echo "checked" ?> value="rus"> <?php _e('Russian font ("Old" style)', 'bg_bibrefs' ); ?><br />
<input type="radio" id="hip" name="<?php echo $c_font_name ?>" <?php if($font_val=="hip") echo "checked" ?> value="hip"> <?php _e('HIP-standard', 'bg_bibrefs' ); ?><br />
<script>
function c_lang_checked() {
	azbyka_font = document.getElementById('bg_bibrefs_azbyka_font');
	if (document.getElementById('c_lang').checked == true) azbyka_font.style.display = '';
	else azbyka_font.style.display = 'none';
}
c_lang_checked();
</script>
</td></tr>

<tr valign="top">
<th scope="row">
<input type="radio" id="bg_bibrefs_site2" name="<?php echo $bg_bibrefs_site ?>" <?php if($bg_bibrefs_site_val=="this") echo "checked" ?> value="this" onclick='bg_bibrefs_site_checked();'> <?php _e('Links to the Bible on this site', 'bg_bibrefs' ); ?><br />
</th>
<td id="bg_bibrefs_permalink">
<?php _e('Permalink to page for search result', 'bg_bibrefs' ); ?><br />
<input type="text" id="bg_bibrefs_page" name="<?php echo $bg_bibrefs_page ?>" size="60" value="<?php echo $bg_bibrefs_page_val ?>"><br />
</td></tr>


<tr valign="top">
<th scope="row">
<input type="radio" id="bg_bibrefs_site3" name="<?php echo $bg_bibrefs_site ?>" <?php if($bg_bibrefs_site_val=="none") echo "checked" ?> value="none" onclick='bg_bibrefs_site_checked();'> <?php _e('No links, popup only', 'bg_bibrefs' ); ?><br />
</th><td>
<script>
function bg_bibrefs_site_checked() {
	elRadio = document.getElementsByName('<?php echo $bg_bibrefs_site ?>');
	azbyka_lang = document.getElementById('bg_bibrefs_azbyka_lang');
	azbyka_font = document.getElementById('bg_bibrefs_azbyka_font');
	permalink = document.getElementById('bg_bibrefs_permalink');
	if (elRadio[0].checked) {
		azbyka_lang.style.display = '';
		c_lang_checked();
	}
	else {
		azbyka_lang.style.display = 'none';
		azbyka_font.style.display = 'none';
	}
	if (elRadio[1].checked) permalink.style.display = '';
	else permalink.style.display = 'none';
}
bg_bibrefs_site_checked();
</script>
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Enable links to the interpretation of the Holy Scriptures', 'bg_bibrefs' ); ?></th>
<td>
<input type="radio" id="bg_interpret_opt" name="<?php echo $bg_interpret ?>" <?php if($bg_interpret_val=="on") echo "checked" ?> value="on"> <?php _e('on Optina Pustyn site', 'bg_bibrefs' ); ?><br />
<input type="radio" id="bg_interpret_lop" name="<?php echo $bg_interpret ?>" <?php if($bg_interpret_val=="lopuhin") echo "checked" ?> value="lopuhin"> <?php _e('by A.Lopuhin on azbyka.ru', 'bg_bibrefs' ); ?><br />
<input type="radio" id="bg_interpret_off" name="<?php echo $bg_interpret ?>" <?php if($bg_interpret_val=="") echo "checked" ?> value=""> <?php _e('disabled', 'bg_bibrefs' ); ?><br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Enable links to the parallel passages in the Bible', 'bg_bibrefs' ); ?></th>
<td>
<input type="checkbox" id="bg_parallel" name="<?php echo $bg_parallel ?>" <?php if($bg_parallel_val=="on") echo "checked" ?>  value="on"> <?php _e('<br><i>(Tooltips and Short Codes)</i>', 'bg_bibrefs' ); ?> <br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Convert references to the normalized form', 'bg_bibrefs' ); ?></th>
<td>
<input type="checkbox" id="bg_norm_refs" name="<?php echo $bg_norm_refs ?>" <?php if($bg_norm_refs_val=="on") echo "checked" ?>  value="on"> <br />
</td></tr>

</table>

<!--  Главные настройки -->
<table class="form-table" style="display: <?php echo $active_tab == 'settings' ? '' : 'none'; ?>;">
<tr valign="top">
<th scope="row"><?php _e('Language of references and tooltips', 'bg_bibrefs' ); ?></th>
<td>
<select id="bg_verses_lang" name="<?php echo $bg_verses_lang ?>"> 
	<option <?php if($bg_verses_lang_val=="") echo "selected" ?> value=""><?php _e('Default', 'bg_bibrefs' ); ?></option>
	<?php
		$path = dirname(dirname( __FILE__ )).'/bible/';
		if ($handle = opendir($path)) {
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
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Show original verse numbers', 'bg_bibrefs' ); ?></th>
<td>
<input type="checkbox" id="bg_show_fn" name="<?php echo $bg_show_fn ?>" <?php if($bg_show_fn_val=="on") echo "checked" ?>  value="on"> <?php _e('<br><i>(Show the original verse numbers in parentheses after the verse numbers of Russian Synodal Translation in the tooltips and quotes.<br>Verses marked with asterisk * are absent in the original translation. * - always visible!)</i>', 'bg_bibrefs' ); ?> <br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Open links', 'bg_bibrefs' ); ?></th>
<td>
<input type="radio" id="blank_window" name="<?php echo $target_window ?>" <?php if($target_val=="_blank") echo "checked" ?> value="_blank"> <?php _e('in new window', 'bg_bibrefs' ); ?><br />
<input type="radio" id="self_window" name="<?php echo $target_window ?>" <?php if($target_val=="_self") echo "checked" ?> value="_self"> <?php _e('in current window', 'bg_bibrefs' ); ?><br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Highlight references in the headers H1...H6', 'bg_bibrefs' ); ?></th>
<td>
<input type="checkbox" id="bg_headers" name="<?php echo $bg_headers ?>" <?php if($bg_headers_val=="on") echo "checked" ?>  value="on"> <br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Show Bible verses in popup', 'bg_bibrefs' ); ?></th>
<td>
<input type="checkbox" id="bg_verses" name="<?php echo $bg_verses_name ?>" <?php if($bg_verses_val=="on") echo "checked" ?>  value="on" onclick='bg_verses_checked();'> <?php _e('<br><i>(If this option is disabled or data are not received from the server,<br>popup showing the Bible book title, chapter number and verse numbers)</i>', 'bg_bibrefs' ); ?> <br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Preload Bible verses in tooltips', 'bg_bibrefs' ); ?></th>
<td>
<input type="checkbox" id="bg_bibrefs_pload" name="<?php echo $bg_bibrefs_pload ?>" <?php if($bg_bibrefs_pload_val=="on") echo "checked" ?>  value="on" onclick='bg_bibrefs_preload();'> <?php _e(' - before upload of the post (using PHP)', 'bg_bibrefs' ); ?><?php _e('<br><i>(Requires a lot of of time to prepare and upload of the post.<br><u>Warning:</u> You can have a problem with limiting the maximum execution time for script on the server.)</i>', 'bg_bibrefs' ); ?><br /><br />
<input type="checkbox" id="bg_bibrefs_preq" name="<?php echo $bg_bibrefs_preq ?>" <?php if($bg_bibrefs_preq_val=="on") echo "checked" ?>  value="on" onclick='bg_bibrefs_prereq();'> <?php _e(' - after upload of the post (using AJAX)', 'bg_bibrefs' ); ?><?php _e('<br><i>(Try this option on a slow server.<br><u>Warning:</u> You can have a problem with ajax-requests limiting on the server.)</i>', 'bg_bibrefs' ); ?> <br />
</td></tr>
<script>
function bg_verses_checked() {
	if (document.getElementById('bg_verses').checked == true) {
		document.getElementById('bg_bibrefs_pload').disabled = false;
		document.getElementById('bg_bibrefs_preq').disabled = false;
	} else {
		document.getElementById('bg_bibrefs_pload').disabled = true;
		document.getElementById('bg_bibrefs_pload').checked = false;
		document.getElementById('bg_bibrefs_preq').disabled = true;
		document.getElementById('bg_bibrefs_preq').checked = false;
	}
}
bg_verses_checked();

function bg_bibrefs_preload() {
	if (document.getElementById('bg_bibrefs_pload').checked == true) {
		document.getElementById('bg_bibrefs_preq').checked = false;
	}
}
bg_bibrefs_preload();
function bg_bibrefs_prereq() {
	if (document.getElementById('bg_bibrefs_preq').checked == true){
		document.getElementById('bg_bibrefs_pload').checked = false;
	}
}
bg_bibrefs_prereq();
</script>
 
</table>

<!--  Дополнительные параметры -->
<table class="form-table" style="display: <?php echo $active_tab == 'additional' ? '' : 'none'; ?>;">
<tr valign="top">
<th scope="row"><?php _e('The maximum execution time', 'bg_bibrefs') ?></th>
<td>
<input type="number" name="bg_bibrefs_maxtime" value="<?php echo $bg_bibrefs_maxtime_val; ?>" /> <?php _e('sec.', 'bg_bibrefs' ); ?>
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Method of reading files', 'bg_bibrefs' ); ?></th>
<td>
<input type="checkbox" id="bg_fgc" name="<?php echo $bg_fgc_name ?>" <?php if($bg_fgc_val=="on") echo "checked" ?>  value="on" onclick='reading_off_checked();'> file_get_contents()<br />
<input type="checkbox" id="bg_fopen" name="<?php echo $bg_fopen_name ?>" <?php if($bg_fopen_val=="on") echo "checked" ?>  value="on" onclick='reading_off_checked();'> fopen() - fread() - fclose()<br />
<input type="checkbox" id="bg_curl" name="<?php echo $bg_curl_name ?>" <?php if($bg_curl_val=="on") echo "checked" ?> value="on" onclick='reading_off_checked();'> cURL<br />
<?php _e('<i>(Plugin tries to read Bible files with marked methods in the order listed.<br>To do the reading faster, disable unnecessary methods - you need one only. <br><u>Warning:</u> Some methods may not be available on your server.)</i>', 'bg_bibrefs' ); ?> <br />
</td></tr>
<script>
function reading_off_checked() {
	if (document.getElementById('bg_curl').checked == true || document.getElementById('bg_fgc').checked == true || document.getElementById('bg_fopen').checked == true) {
		document.getElementById('bg_verses').disabled = false;
	} else {
		document.getElementById('bg_verses').disabled = true;
		document.getElementById('bg_verses').checked = false;
		document.getElementById('bg_bibrefs_pload').disabled = true;
		document.getElementById('bg_bibrefs_pload').checked = false;
		document.getElementById('bg_bibrefs_preq').disabled = true;
		document.getElementById('bg_bibrefs_preq').checked = false;
	}
}
reading_off_checked();
</script>

<tr valign="top">
<th scope="row"><?php _e('External AJAX Proxy', 'bg_bibrefs' ); ?></th>
<td>
<input type="text" id="bg_bibrefs_ajaxurl" name="<?php echo $bg_bibrefs_ajaxurl ?>" size="60" value="<?php echo $bg_bibrefs_ajaxurl_val ?>"><br />
<details>
<summary><?php _e('Add into <em>functions.php</em> on this server the following PHP-code (see bellow)', "bg_bibrefs"); ?></summary>
<?php printf ('<code>function allow_origin () {<br>&nbsp;&nbsp;&nbsp;&nbsp;header ( "Access-Control-Allow-Origin: %1$s" );<br>}<br>add_action ( "init", "allow_origin" );</code>', get_site_url()); ?>
</details>
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Container, inside which will display tooltips', 'bg_bibrefs' ); ?></th>
<td>
<input type="text" id="bg_content" name="<?php echo $bg_content ?>" size="20" value="<?php echo $bg_content_val ?>"><br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Reference links CSS class', 'bg_bibrefs' ); ?></th>
<td>
<input type="text" id="links_class" name="<?php echo $links_class ?>" size="20" value="<?php echo $class_val ?>"><br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Custom file of Bible quotes', 'bg_bibrefs' ); ?></th>
<td>
<input type="text" id="bg_refs_file" name="<?php echo $bg_refs_file ?>" size="60" value="<?php echo $bg_refs_file_val ?>"><br />
</td></tr>

<tr valign="top">
<th scope="row"><?php _e('Debug', 'bg_bibrefs' ); ?></th>
<td>
<input type="checkbox" id="bg_bibrefs_debug" name="<?php echo $bg_bibrefs_debug_name ?>" <?php if($bg_bibrefs_debug_val=="on") echo "checked" ?>  value="on"'> <?php _e('<br><i>(If you enable this option the debug information will written to the file "debug.log" in the plugin directory.<br>The file will be updated in 30 minutes after the last record, or the filesize exceed 2 Mb.<br><font color="red"><b>Disable this option after the end of debugging!</b></font>)</i>', 'bg_bibrefs' ); ?> <br />
</td></tr>

</table>

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php _e('Update Options', 'bg_bibrefs' ) ?>" />
</p>

</form>

<?php } ?>

</div>
</td>

<!-- Информация о плагине -->
<td valign="top" align="left" width="45em">

<div class="bg_bibrefs_info_box">

	<h3><?php _e('Thanks for using Bg Biblie References', 'bg_bibrefs') ?></h3>
	<p class="bg_bibrefs_gravatar"><a href="http://bogaiskov.ru" target="_blank"><?php echo get_avatar("vadim.bogaiskov@gmail.com", '64'); ?></a></p>
	<p><?php _e('Dear brothers and sisters!<br />Thank you for using my plugin!<br />I hope it is useful for your site.', 'bg_bibrefs') ?></p>
	<p class="bg_bibrefs_author"><a href="http://bogaiskov.ru" target="_blank"><?php _e('Vadim Bogaiskov', 'bg_bibrefs') ?></a></p>

	<h3><?php _e('I like this plugin<br>– how can I thank you?', 'bg_bibrefs') ?></h3>
	<p><?php _e('There are several ways for you to say thanks:', 'bg_bibrefs') ?></p>
	<ul>
		<li><?php printf(__('<a href="%1$s" target="_blank">Give a donation</a>  for the construction of the church of Sts. Peter and Fevronia in Marino', 'bg_bibrefs'), "http://hpf.ru.com/donate/") ?></li>
		<li><?php printf(__('<a href="%1$s" target="_blank">Give 5 stars</a> over at the WordPress Plugin Directory', 'bg_bibrefs'), "http://wordpress.org/support/view/plugin-reviews/bg-biblie-references") ?></li>
		<li><?php printf(__('Share infotmation or make a nice blog post about the plugin', 'bg_bibrefs')) ?></li>
	</ul>
	<div class="share42init" align="center" data-url="http://bogaiskov.ru/bg_bibrefs/" data-title="<?php _e('Bg Bible References really cool plugin for Orthodox WordPress sites', 'bg_bibrefs') ?>"></div>
	<script type="text/javascript" src="<?php printf( plugins_url( 'share42/share42.js' , dirname(__FILE__) ) ) ?>"></script>

	<h3><?php _e('Support', 'bg_bibrefs') ?></h3>
	<p><?php printf(__('Please see the <a href="%1$s" target="_blank">support forum</a> or <a href="%2$s" target="_blank">plugin\'s site</a> for help.', 'bg_bibrefs'), "http://wordpress.org/support/plugin/bg-biblie-references", "http://wp-bible.info/") ?></p>
	
	<p class="bg_bibrefs_close"><?php _e("God protect you!", 'bg_bibrefs') ?></p>
</div>
</td></tr></table>
<?php 

} 

// Задание параметров по умолчанию
function bg_bibrefs_options_ini () {
	bg_bibrefs_change_options (); // Заменяем старые опции на новые, начиная с версии 3.11
	add_option('bg_bibrefs_site', "azbyka");
	add_option('bg_bibrefs_c_lang', "c");
	add_option('bg_bibrefs_r_lang', "r");
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
    $r_lang_val = get_option( 'bg_bibrefs_r_lang' );
    $g_lang_val = get_option( 'bg_bibrefs_g_lang' );
    $l_lang_val = get_option( 'bg_bibrefs_l_lang' );
    $i_lang_val = get_option( 'bg_bibrefs_i_lang' );
	$lang_val = $c_lang_val.$r_lang_val.$g_lang_val.$l_lang_val.$i_lang_val;
	$font_val = get_option( 'bg_bibrefs_c_font' );
	if ($lang_val) $opt = "&".$lang_val;
	if ($font_val && $c_lang_val) $opt = $opt."&".$font_val;
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

    $bg_bibrefs_option['curl'] = get_option( 'bg_bibrefs_curl' );
    $bg_bibrefs_option['fgc'] = get_option( 'bg_bibrefs_fgc' );
    $bg_bibrefs_option['fopen'] = get_option( 'bg_bibrefs_fopen' );

    $bg_bibrefs_option['pload'] = get_option('bg_bibrefs_preload' );
    $bg_bibrefs_option['preq'] = get_option('bg_bibrefs_prereq' );
	
    $bg_bibrefs_option['maxtime'] = (int) get_option( 'bg_bibrefs_maxtime' );
	
    $bg_bibrefs_option['refs_file'] = get_option( 'bg_bibrefs_refs_file' );
	
    $bg_bibrefs_option['debug'] = get_option('bg_bibrefs_debug' );
}
// Регистрируем виджеты
function register_widgets() {
    register_widget("BibleWidget");
    register_widget("BibleSearchWidget");
    register_widget("QuotesWidget");
};
add_action("widgets_init", "register_widgets");

function bg_bibrefs_change_options () {
	if (get_option('bg_bibrefs_options_udate') == "updated") return;
	
	$old_options = array (	'bg_bibfers_c_lang',
							'bg_bibfers_g_lang',
							'bg_bibfers_l_lang',
							'bg_bibfers_i_lang',
							'bg_bibfers_c_font',
							'bg_bibfers_site',
							'bg_bibfers_page',
							'bg_bibfers_target',
							'bg_bibfers_content',
							'bg_bibfers_class',
							'bg_bibfers_show_verses',
							'bg_bibfers_verses_lang',
							'bg_bibfers_show_fn',
							'bg_bibfers_headers',
							'bg_bibfers_interpret',
							'bg_bibfers_parallel',
							'bg_bibfers_norm_refs',
							'bg_bibfers_curl',
							'bg_bibfers_fgc',
							'bg_bibfers_fopen',
							'bg_bibrefs_preload',
							'bg_bibfers_prereq',
							'bg_bibrefs_maxtime',
							'bg_bibrefs_cashe',
							'bg_bibfers_refs_file',
							'bg_bibfers_debug');
	$new_options = array (	'bg_bibrefs_c_lang',
							'bg_bibrefs_g_lang',
							'bg_bibrefs_l_lang',
							'bg_bibrefs_i_lang',
							'bg_bibrefs_c_font',
							'bg_bibrefs_site',
							'bg_bibrefs_page',
							'bg_bibrefs_target',
							'bg_bibrefs_content',
							'bg_bibrefs_class',
							'bg_bibrefs_show_verses',
							'bg_bibrefs_verses_lang',
							'bg_bibrefs_show_fn',
							'bg_bibrefs_headers',
							'bg_bibrefs_interpret',
							'bg_bibrefs_parallel',
							'bg_bibrefs_norm_refs',
							'bg_bibrefs_curl',
							'bg_bibrefs_fgc',
							'bg_bibrefs_fopen',
							'bg_bibrefs_preload',
							'bg_bibrefs_prereq',
							'bg_bibrefs_maxtime',
							'bg_bibrefs_refs_file',
							'bg_bibrefs_debug');

// Читаем существующие значения опций из базы данных и сохраняем в новых
	$cnt = count($old_options);
	for ($i = 0; $i < $cnt; $i++) {
		$opt = get_option( $old_options[$i]);
		if ( $opt !== false ) {
			add_option( $new_options[$i], $opt );
			delete_option( $old_options[$i] );
		}
	}
// Переименовывем виджеты в БД
	global $wpdb;
	$options_list = $wpdb->get_results("SELECT option_name, option_value FROM wp_options", ARRAY_A);
	$cnt =count ($options_list);
	for ($i=0; $i<$cnt; $i++) {
		$options_list_new = preg_replace ('/bg_bibfers/i', 'bg_bibrefs', $options_list[$i]);
		$wpdb->update( 'wp_options', $options_list_new, $options_list[$i] );
	}
	add_option('bg_bibrefs_options_udate', "updated");
}
    /** ************************************************************************
     * Добавить папку с Библией на сайт
     **************************************************************************/
	function bg_bibrefs_addFolder($book) {
	
		$source_url = BG_BIBREFS_SOURCE_URL.$book;
		$local_url = dirname(dirname(__FILE__ )).'/'.$book;
		$subfolder = dirname(dirname(__FILE__ )).'/bible/'.basename($book, ".zip").'/';
		if (!file_exists($local_url)) {
			if (copy ( $source_url, $local_url )) {
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
 		$path = dirname(dirname( __FILE__ )).'/bible/';
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
		$path = dirname(dirname( __FILE__ )).'/bible/';
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

