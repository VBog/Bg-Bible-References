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
	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яёіїєґўЁІЇЄҐЎA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-‐‑‒–——―](\\s|&nbsp\\;)*\\d+)*)[(\\s|&nbsp\\;)\\]\\)(\\;|\\.)]?/ui";
	preg_match_all($template, $txt, $matches, PREG_OFFSET_CAPTURE);
	$cnt = count($matches[0]);

	$text = "";
	$start = 0;
	$j = 0;

	for ($i = 0; $i < $cnt; $i++) {
		
	// Проверим по каждому паттерну. 
		preg_match($template, $matches[0][$i][0], $mt);
		$cn = count($mt);
		if ($cn > 0) {
			$title = preg_replace("/\\s|&nbsp\\;/u", '',$mt[5]); 				// Убираем пробельные символы, включая пробел, табуляцию, переводы строки 
			$chapter = preg_replace("/\\s|&nbsp\\;/u", '', $mt[9]);				// и другие юникодные пробельные символы, а также неразрывные пробелы &nbsp;
			$chapter = preg_replace("/[‐‑‒–——―]/u", '-', $chapter);				// Замена разных вариантов тире на обычный
//			$chapter = preg_replace("/\\;/u", ',', $chapter);					// Замена точки с запятой на запятую
			preg_match("/[\\:\\,\\.\\-]/u", $chapter, $mtchs);
			if ($mtchs) {
				if (strcasecmp($mtchs[0], ',') == 0 || strcasecmp($mtchs[0], '.') == 0) {
						$chapter = preg_replace("/\,/u", ':', $chapter, 1);		// Первое число всегда номер главы. Если глава отделена запятой, заменяем ее на двоеточие.
						$chapter = preg_replace("/\./u", ':', $chapter, 1);		// Первое число всегда номер главы. Если глава отделена точкой, заменяем ее на двоеточие.
				}
			}
			$addr = bg_bibfers_get_url($title, $chapter, $lang);
			if (strcasecmp($addr, "") != 0 && bg_bibfers_check_links($txt, $matches[0][$i][1]) && bg_bibfers_check_headers($txt, $matches[0][$i][1]) && bg_bibfers_check_norefs($txt, $matches[0][$i][1])) {
				$ref = trim ( $matches[0][$i][0], "\x20\f\t\v\n\r\xA0\xC2" );
				$book = bg_bibfers_getBook($title);								// Обозначение книги
				if ($type == '' || $type == 'link') {
					$book = bg_bibfers_getshortTitle($book);						// Короткое наименование книги
					if (get_option( 'bg_bibfers_norm_refs' )) {						// Преобразовать ссылку к нормализованному виду
						$newmt = '('.$addr .$book.' '.$chapter. "</a></span>".')';
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