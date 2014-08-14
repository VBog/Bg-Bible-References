<?php
/******************************************************************************************************************************************
Плагин подсвечивает ссылки на текст Библии с помощью гиперссылок на сайт Православной энциклопедии "Азбука веры" (http://azbyka.ru/biblia). 
Текст Библии представлен на церковнославянском, русском, греческом, еврейском и латинском языках. Не требуется никаких настроек. 
Плагин обрабатывает ссылки следующего формата:
	(Ин. 3:16), где «Ин.» - это название книги, 3 - это глава, а 16 - это номер стиха.
	(Ин. 3:16—18) (Книга. Глава: с этого [—] по этот стих)
	(Ин. 3:16—18, 21, 34—36) (Книга. Глава: с этого [—] по этот стих, этот стих, с этого [—] по этот стих)
	(Ин. 3:16—18, 4:4—6) (Книга. Глава: с этого [—] по этот стих, глава: с этого [—] по этот стих)
	(Мф. 5—6) (Книга. С этой [—] по эту главу). 
Допускается указание ссылок в квадратных скобках и без точки после наименования книги. Пробелы игнорируются.
Допускается указание см.: сразу после открывающейся скобки. Варианты: см.: / см. / см: / см

********************************************************************************************************************************************/
$bg_bibfers_all_refs=array();				// Перечень всех ссылок 
/******************************************************************************************
	Основная функция разбора текста и формирования ссылок,
    для работы требуется bg_bibfers_get_url() - см. ниже
*******************************************************************************************/
function bg_bibfers_bible_proc($txt, $type='', $lang='') {
	global $bg_bibfers_all_refs;
	global $bg_bibfers_chapter, $bg_bibfers_ch;
	global $bg_bibfers_url, $bg_bibfers_bookTitle, $bg_bibfers_shortTitle, $bg_bibfers_bookFile;
	if (!$lang) $lang = set_bible_lang();
	include(dirname(dirname(__FILE__ )).'/bible/'.$lang.'/books.php');

// Ищем все вхождения ссылок на Библию
//	$template = "/[\\(\\[](см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)]/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)\\.]?/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\;\\,\\.\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)\\.]?/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)(\\;|\\.)]?/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-—–](\\s|&nbsp\\;)*\\d+)*)[(\\s|&nbsp\\;)\\]\\)(\\;|\\.)]?/ui";
<<<<<<< HEAD
<<<<<<< HEAD

//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?([1-4]?(\\s|&nbsp\\;)*[А-яёіїєґўЁІЇЄҐЎA-z\']{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-‐‑‒–——―](\\s|&nbsp\\;)*\\d+)*)[(\\s|&nbsp\\;)\\]\\)(\\;|\\.)]?/uxi";

	$sps = "(?:\s|\x{00A0}|\x{00C2}|(?:&nbsp;))";
	$dashes = "(?:[\x{2010}-\x{2015}]|(&#820[8-9];)|(&#821[0-3];))";
	$template = "/(?<!\w)((?:[1-4]|I{1,3}|IV)?".$sps."*['A-Za-zА-Яа-яёіїєґўЁІЇЄҐЎ]{2,8})".$sps."*\.?".$sps."*((\d+|[IVXLC]+)(".$sps."*([\.;:,-]|".$dashes.")".$sps."*(\d+|[IVXLC]+))*)(?!\w)/u";

=======
	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?([1-4]?(\\s|&nbsp\\;)*[А-яёіїєґўЁІЇЄҐЎA-z\']{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-‐‑‒–——―](\\s|&nbsp\\;)*\\d+)*)[(\\s|&nbsp\\;)\\]\\)(\\;|\\.)]?/ui";
>>>>>>> parent of bf1e3d9... Revert "Version 3.4.1"
=======
	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яёіїєґўЁІЇЄҐЎA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-‐‑‒–——―](\\s|&nbsp\\;)*\\d+)*)[(\\s|&nbsp\\;)\\]\\)(\\;|\\.)]?/ui";
>>>>>>> parent of e6a30e1... Version 3.5
	preg_match_all($template, $txt, $matches, PREG_OFFSET_CAPTURE);
	$cnt = count($matches[0]);

	$text = "";
	$start = 0;
	$j = 0;

<<<<<<< HEAD
/****************** ОТЛАДКА ****************************************/	
	if ($bg_bibfers_option['debug']) {
		$this_time = microtime(true)*1000;
		$time = ($this_time- $start_time);
<<<<<<< HEAD
		error_log("  Начальная обработка: ". round($time, 2)." мсек.\n", 3, $debug_file);
=======
		error_log(" Начало цикла проверки патернов: ". round($time, 2) ." мсек.\n", 3, $debug_file);
>>>>>>> parent of bf1e3d9... Revert "Version 3.4.1"
		$start_time = $this_time;
	}
/*******************************************************************/	

=======
>>>>>>> parent of e6a30e1... Version 3.5
	for ($i = 0; $i < $cnt; $i++) {
		
	// Проверим по каждому паттерну. 
		preg_match($template, $matches[0][$i][0], $mt);
<<<<<<< HEAD
<<<<<<< HEAD
		
		
		
/****************** ОПЕРАТИВНАЯ ОТЛАДКА ****************************	
		echo "<b>". $matches[0][$i][0]."</b> => ";
		$mcnt = count ($mt);
		for ($k=1; $k < $mcnt; $k++) {
			echo " <sup>|".$k."|</sup> ".$mt[$k];
		}	
		echo "<br>";
/*******************************************************************/	
=======
>>>>>>> parent of bf1e3d9... Revert "Version 3.4.1"
			
		$cn = count($mt);
		if ($cn > 0) {

<<<<<<< HEAD
			$title = preg_replace("/".$sps."/u", '',$mt[1]); 					// Убираем пробельные символы, включая пробел, табуляцию, переводы строки 
			$chapter = preg_replace("/".$sps."/u", '', $mt[2]);					// и другие юникодные пробельные символы, а также неразрывные пробелы &nbsp;
			$chapter = preg_replace("/".$dashes."/u", '-', $chapter);			// Замена разных вариантов тире на обычный
			$chapter = preg_replace("/;/u", ',', $chapter);						// Замена точки с запятой на запятую
			$chapter = preg_replace("/(?<=[IVXLC]),\./u", ":", $chapter);		// Римскими цифрами обозначаются только главы (после них должно идти ":", а не "," или ".")

		// Замена римских цифр на арабские
			preg_match("/(I{1,3}|IV)(?=".$sps."*['A-Za-zА-Яа-яёіїєґўЁІЇЄҐЎ]{2,8})/u", $title, $rome);
			if ($rome) {
				$title = preg_replace("/".$rome[0]."/u", rome_to_arab($rome[0]), $title, 1);
			}
			preg_match_all("/[IVXLC]+/u", $chapter, $rome, PREG_OFFSET_CAPTURE);
			$crome = count($rome[0]);
			for ($r = 0; $r < $crome; $r++) {
				$chapter = preg_replace("/".$rome[0][$r][0]."/u", rome_to_arab($rome[0][$r][0]), $chapter, 1);
			}

=======
			$title = preg_replace("/\\s|&nbsp\\;/u", '',$mt[5]); 				// Убираем пробельные символы, включая пробел, табуляцию, переводы строки 
			$chapter = preg_replace("/\\s|&nbsp\\;/u", '', $mt[9]);				// и другие юникодные пробельные символы, а также неразрывные пробелы &nbsp;
			$chapter = preg_replace("/[‐‑‒–——―]/u", '-', $chapter);				// Замена разных вариантов тире на обычный
			$chapter = preg_replace("/\\;/u", ',', $chapter);					// Замена точки с запятой на запятую
>>>>>>> parent of bf1e3d9... Revert "Version 3.4.1"
=======
		$cn = count($mt);
		if ($cn > 0) {
			$title = preg_replace("/\\s|&nbsp\\;/u", '',$mt[5]); 				// Убираем пробельные символы, включая пробел, табуляцию, переводы строки 
			$chapter = preg_replace("/\\s|&nbsp\\;/u", '', $mt[9]);				// и другие юникодные пробельные символы, а также неразрывные пробелы &nbsp;
			$chapter = preg_replace("/[‐‑‒–——―]/u", '-', $chapter);				// Замена разных вариантов тире на обычный
//			$chapter = preg_replace("/\\;/u", ',', $chapter);					// Замена точки с запятой на запятую
>>>>>>> parent of e6a30e1... Version 3.5
			preg_match("/[\\:\\,\\.\\-]/u", $chapter, $mtchs);
			if ($mtchs) {
				if (strcasecmp($mtchs[0], ',') == 0 || strcasecmp($mtchs[0], '.') == 0) {
						$chapter = preg_replace("/\,/u", ':', $chapter, 1);		// Первое число всегда номер главы. Если глава отделена запятой, заменяем ее на двоеточие.
						$chapter = preg_replace("/\./u", ':', $chapter, 1);		// Первое число всегда номер главы. Если глава отделена точкой, заменяем ее на двоеточие.
				}
			}

			$addr = bg_bibfers_get_url($title, $chapter, $lang);
<<<<<<< HEAD

			if (strcasecmp($addr, "") != 0 
				&& bg_bibfers_check_tag($hdr_a, $matches[0][$i][1]) 
				&& (($bg_bibfers_option['headers']=='on') || bg_bibfers_check_tag($hdr_h, $matches[0][$i][1])) 
				&&  bg_bibfers_check_tag($hdr_norefs, $matches[0][$i][1])
				&&  bg_bibfers_check_tag($hdr_bible, $matches[0][$i][1])) {
<<<<<<< HEAD
				$ref = $matches[0][$i][0];
//				$ref = substr($ref, 1, strlen($ref)-2);							//Обрезаем первый и последний символы
				$ref = trim ( $ref, "\x20\f\t\v\n\r\xA0\xC2" );
=======
				$ref = trim ( $matches[0][$i][0], "\x20\f\t\v\n\r\xA0\xC2" );
>>>>>>> parent of bf1e3d9... Revert "Version 3.4.1"
				$book = bg_bibfers_getBook($title);								// Обозначение книги
				if ($type == '' || $type == 'link') {
					$book = bg_bibfers_getshortTitle($book);					// Короткое наименование книги
					if ($bg_bibfers_option['norm_refs']) {						// Преобразовать ссылку к нормализованному виду
<<<<<<< HEAD
						$newmt = $addr .$book.' '.$chapter. "</a></span>";
=======
						$newmt = '('.$addr .$book.' '.$chapter. "</a></span>".')';
>>>>>>> parent of bf1e3d9... Revert "Version 3.4.1"
=======
			if (strcasecmp($addr, "") != 0 && bg_bibfers_check_links($txt, $matches[0][$i][1]) && bg_bibfers_check_headers($txt, $matches[0][$i][1]) && bg_bibfers_check_norefs($txt, $matches[0][$i][1])) {
				$ref = trim ( $matches[0][$i][0], "\x20\f\t\v\n\r\xA0\xC2" );
				$book = bg_bibfers_getBook($title);								// Обозначение книги
				if ($type == '' || $type == 'link') {
					$book = bg_bibfers_getshortTitle($book);						// Короткое наименование книги
					if (get_option( 'bg_bibfers_norm_refs' )) {						// Преобразовать ссылку к нормализованному виду
						$newmt = '('.$addr .$book.' '.$chapter. "</a></span>".')';
>>>>>>> parent of e6a30e1... Version 3.5
					}
					else $newmt = $addr .$ref. "</a></span>";
					$listmt = $addr .$book.' '.$chapter. "</a></span>";
					$double = false;
					for ($k=0; $k < $j; $k++) {										// Проверяем не совпадают ли ссылки?
						if ($bg_bibfers_all_refs[$k] == $listmt) {
							$double = true;
							break;
						}
					}
					if (!$double) {													// Дубликат не найден
						$bg_bibfers_all_refs[$j]=$listmt;
						$j++;
					}
				} else {
					$newmt = bg_bibfers_getQuotes($book, $chapter, $type, $lang );
				}
				$text = $text.substr($txt, $start, $matches[0][$i][1]-$start).str_replace($ref, $newmt, $matches[0][$i][0]);
				$start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
			}
		}
	}
	$txt = $text.substr($txt, $start);
	return $txt;
}
/******************************************************************************************
	Проверяем находится ли указанная позиция текста внутри тега ссылки <a ...</a>,
    если "да" - возвращаем false, "нет" - true 
*******************************************************************************************/
function bg_bibfers_check_links($txt, $pos) {

// Ищем все вхождения ссылок <a ...</a>
	$headers = "/<a\\s.*?<\/a>/sui";
	preg_match_all($headers, $txt, $hdr, PREG_OFFSET_CAPTURE);
	$chrd = count($hdr[0]);

	for ($k = 0; $k < $chrd; $k++) {
		$start = $hdr[0][$k][1];
		$finish = $start + strlen($hdr[0][$k][0]);
		if ($pos >= $start && $pos <= $finish) return false;
	}
	return true;
}

/******************************************************************************************
	Проверяем находится ли указанная позиция текста в теле заголовка,
    если "да" - возвращаем false, "нет" - true 
*******************************************************************************************/
function bg_bibfers_check_headers($txt, $pos) {

	if (get_option( 'bg_bibfers_headers' )) return true;
// Ищем все вхождения заголовков h1...h6
	$headers = "/<h\\d.*?>(.*)<\/h\\d>/sui";
	preg_match_all($headers, $txt, $hdr, PREG_OFFSET_CAPTURE);
	$chrd = count($hdr[0]);

	for ($k = 0; $k < $chrd; $k++) {
		$start = $hdr[0][$k][1];
		$finish = $start + strlen($hdr[0][$k][0]);
		if ($pos >= $start && $pos <= $finish) return false;
	}
	return true;
}

/******************************************************************************************
	Проверяем находится ли указанная позиция текста внутри шорт-кодов
	[norefs]...[/norefs] и [bible...[/bible],
    если "да" - возвращаем false, "нет" - true 
*******************************************************************************************/
function bg_bibfers_check_norefs($txt, $pos) {

// Ищем все вхождения [norefs]...[/norefs]
	$norefs = "/\[norefs\](.*)\[\/norefs\]/sui";
	preg_match_all($norefs, $txt, $hdr, PREG_OFFSET_CAPTURE);
	$chrd = count($hdr[0]);
	
	for ($k = 0; $k < $chrd; $k++) {
		$start = $hdr[0][$k][1];
		$finish = $start + strlen($hdr[0][$k][0]);
		if ($pos >= $start && $pos <= $finish) return false;
	}
	
// Ищем все вхождения [bible...[/bible]
	$norefs = "/\[bible(.*)\[\/bible\]/sui";
	preg_match_all($norefs, $txt, $hdr, PREG_OFFSET_CAPTURE);
	$chrd = count($hdr[0]);
	
	for ($k = 0; $k < $chrd; $k++) {
		$start = $hdr[0][$k][1];
		$finish = $start + strlen($hdr[0][$k][0]);
		if ($pos >= $start && $pos <= $finish) return false;
	}
	
	return true;
}
/******************************************************************************************
	Формирование ссылки на http://azbyka.ru/biblia/
	Используется в функции bg_bibfers_bible_proc(),
	для работы требуется bg_bibfers_getTitle() - см. ниже
	и bg_bibfers_getBook() - см. ниже
*******************************************************************************************/
function bg_bibfers_get_url($title, $chapter, $lang) {
	global $bg_bibfers_ch;
		
// http://azbyka.ru/biblia/?Lk.4:25-5:13,6:1-13&crgli&rus&num=cr 
	bg_bibrefs_options_ini (); 			// Параметры по умолчанию
	
/*******************************************************************************
   Проверяем настройки
*******************************************************************************/  
// Задание языков и шрифтов для отображения на сайте azbyka.ru
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
// Общие параметры	отображения ссылок
    $target_val = get_option( 'bg_bibfers_target' );
    $class_val = get_option( 'bg_bibfers_class' );
	if ($class_val == "") $class_val = 'bg_bibfers';
	$bg_verses_val = get_option( 'bg_bibfers_show_verses' );	
	
	$book = bg_bibfers_getBook($title);
	if ($book != "") {						
		$fullurl = "http://azbyka.ru/biblia/?".$book.".". $chapter;					// Полный адрес ссылки на azbyka.ru
		$the_title =  bg_bibfers_getTitle($book)." ".$bg_bibfers_ch." ".$chapter;	// Название книги, номера глав и стихов						
		if ($bg_verses_val == 'on') {												// Текст  стихов
			$ajax_url = admin_url("admin-ajax.php?title=".$book."&chapter=".$chapter."&type=t_verses&lang=".$lang);
		} else {
			$ajax_url = "";
		}
		
		return "<span class='bg_data_title ".$class_val."' data-title='".$ajax_url."' title='".$the_title."'><span class='bg_data_tooltip'></span><a href='".$fullurl.$opt."' target='".$target_val."'>"; 
	}
	else return "";
}

function bg_bibfers_getBook($title) {
	global $bg_bibfers_url;
<<<<<<< HEAD
<<<<<<< HEAD
	if (isset ($bg_bibfers_url[$title])) return $bg_bibfers_url[$title];// Обозначение книги Библии
=======
	if (isset ($bg_bibfers_url[$title])) return $bg_bibfers_url[$title];		// Обозначение книги Библии
>>>>>>> parent of bf1e3d9... Revert "Version 3.4.1"
	else return "";
=======
	$cn_url = count($bg_bibfers_url) / 2;
	for ($i=0; $i < $cn_url; $i++) {											// Просматриваем всю таблицу соответствия сокращений наименований книг
		$regvar = "/".$bg_bibfers_url[$i*2+1]."|".$bg_bibfers_url[$i*2]."/iu";	// Формируем регулярное выражение для поиска обозначения, включая латинское наименование
		preg_match_all($regvar, $title, $mts);									// Ищем все вхождения указанного наименования
		$cnt = count($mts[0]);
		for ($k=0; $k < $cnt; $k++) {											// Из всех вхождений находим точное соответствие указанному наименованию
			if (strcasecmp($mts[0][$k],  $title) == 0) {						
				return $bg_bibfers_url[$i*2];									// Обозначение книги латынью
			}
		}
	}
	return "";
>>>>>>> parent of e6a30e1... Version 3.5
}

/*******************************************************************************
   Полное наименование книги Библии
   Используется в функции bg_bibfers_get_url()
*******************************************************************************/  
function bg_bibfers_getTitle($book) {
	global $bg_bibfers_bookTitle;
	return $bg_bibfers_bookTitle[$book];							// Полное наименование книги Библии
}

/*******************************************************************************
   Короткое наименование книги Библии
   Используется в функции bg_bibfers_bible_proc()
*******************************************************************************/  
function bg_bibfers_getshortTitle($book) {
	global $bg_bibfers_shortTitle;
	return $bg_bibfers_shortTitle[$book];						// Короткое наименование книги Библии
}
