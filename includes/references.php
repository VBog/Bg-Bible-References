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
$bg_bibrefs_all_refs=array();				// Перечень всех ссылок 
/******************************************************************************************
	Основная функция разбора текста и формирования ссылок,
    для работы требуется bg_bibrefs_get_url() - см. ниже
*******************************************************************************************/
function bg_bibrefs_bible_proc($txt, $type='', $lang='', $prll='') {
	global $post;
	global $bg_bibrefs_option;
	global $bg_bibrefs_all_refs;
	global $bg_bibrefs_chapter, $bg_bibrefs_ch;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;
//	bg_bibrefs_get_options ();

/****************** ОТЛАДКА ****************************************/	
	if ($bg_bibrefs_option['debug']) {
		$debug_file = dirname(dirname(__FILE__ ))."/debug.log";
		if (file_exists($debug_file)) {
			$size = filesize ($debug_file);
			$lasttime = filectime($debug_file);
			if ($lasttime+30*60 < time() || $size > 2*1024*1024) unlink ( $debug_file );
		}
		$start_time = microtime(true)*1000;
		$s_time = $start_time;
		error_log(date('d.m.Y h:i:s').": ". get_permalink()."\n", 3, $debug_file);
	}
/*******************************************************************/	
	if (!$lang) $lang = set_bible_lang();
	
	$lang = include_books($lang);

	$norefs_posts_val = get_post_meta($post->ID, 'norefs', true);
	if ($norefs_posts_val || in_category( 'norefs' ) || has_tag( 'norefs' )) return $txt;
	
// Ищем все вхождения ссылок <a ...</a>, заголовков <h. ... </h.> и шорт-кодов [norefs]...[/norefs] и [bible]...[/bible]
	preg_match_all("/<a\\s.*?<\/a>/sui", $txt, $hdr_a, PREG_OFFSET_CAPTURE);
	preg_match_all("/<h([1-6])(.*?)<\/h\\1>/sui", $txt, $hdr_h, PREG_OFFSET_CAPTURE);
	preg_match_all("/\[norefs.*?\[\/norefs\]/sui", $txt, $hdr_norefs, PREG_OFFSET_CAPTURE);
	preg_match_all("/\[bible.*?\[\/bible\]/sui", $txt, $hdr_bible, PREG_OFFSET_CAPTURE);
	

// Ищем все вхождения ссылок на Библию
//	$template = "/[\\(\\[](см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)]/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)\\.]?/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\;\\,\\.\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)\\.]?/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)(\\;|\\.)]?/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-—–](\\s|&nbsp\\;)*\\d+)*)[(\\s|&nbsp\\;)\\]\\)(\\;|\\.)]?/ui";

//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?([1-4]?(\\s|&nbsp\\;)*[А-яёіїєґўЁІЇЄҐЎA-z\']{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-‐‑‒–——―](\\s|&nbsp\\;)*\\d+)*)[(\\s|&nbsp\\;)\\]\\)(\\;|\\.)]?/uxi";

	$sps = "(?:\s|\x{00A0}|\x{00C2}|(?:&nbsp;))";
	$dashes = "(?:[\x{2010}-\x{2015}]|(&#820[8-9];)|(&#821[0-3];))";
	$template = "/(?<!\w)((?:[1-4]|I{1,3}|IV)?".$sps."*['A-Za-zА-Яа-яёіїєґўЁІЇЄҐЎ]{2,8})".$sps."*\.?".$sps."*((\d+|[IVXLC]+)(".$sps."*([\.;:,-]|".$dashes.")".$sps."*(\d+|[IVXLC]+))*)(?!\w)/u";

	preg_match_all($template, $txt, $matches, PREG_OFFSET_CAPTURE);
	$cnt = count($matches[0]);

	$text = "";
	$start = 0;
	$j = 0;

/****************** ОТЛАДКА ****************************************/	
	if ($bg_bibrefs_option['debug']) {
		$this_time = microtime(true)*1000;
		$time = ($this_time- $start_time);
		error_log("  Начальная обработка: ". round($time, 2)." мсек.\n", 3, $debug_file);
		$start_time = $this_time;
	}
/*******************************************************************/	

	for ($i = 0; $i < $cnt; $i++) {
		
	// Проверим по каждому паттерну. 
		preg_match($template, $matches[0][$i][0], $mt);
		
		
		
/****************** ОПЕРАТИВНАЯ ОТЛАДКА ****************************	
		echo "<b>". $matches[0][$i][0]."</b> => ";
		$mcnt = count ($mt);
		for ($k=1; $k < $mcnt; $k++) {
			echo " <sup>|".$k."|</sup> ".$mt[$k];
		}	
		echo "<br>";
/*******************************************************************/	
			
		$cn = count($mt);
		if ($cn > 0) {

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

			preg_match("/[\\:\\,\\.\\-]/u", $chapter, $mtchs);
			if ($mtchs) {
				if (strcasecmp($mtchs[0], ',') == 0 || strcasecmp($mtchs[0], '.') == 0) {
						$chapter = preg_replace("/\,/u", ':', $chapter, 1);		// Первое число всегда номер главы. Если глава отделена запятой, заменяем ее на двоеточие.
						$chapter = preg_replace("/\./u", ':', $chapter, 1);		// Первое число всегда номер главы. Если глава отделена точкой, заменяем ее на двоеточие.
				}
			}
			$title = bg_bibrefs_getBook($title);

			if (strcasecmp($title, "") != 0 
				&& bg_bibrefs_check_tag($hdr_a, $matches[0][$i][1]) 
				&& (($bg_bibrefs_option['headers']=='on') || bg_bibrefs_check_tag($hdr_h, $matches[0][$i][1])) 
				&&  bg_bibrefs_check_tag($hdr_norefs, $matches[0][$i][1])
				&&  bg_bibrefs_check_tag($hdr_bible, $matches[0][$i][1])) {
				$ref = $matches[0][$i][0];
				$ref = trim ( $ref, "\x20\f\t\v\n\r\xA0\xC2" );
				$book = bg_bibrefs_getBook($title);								// Обозначение книги
				if ($type == '' || $type == 'link') {
					$book = bg_bibrefs_getshortTitle($book);					// Короткое наименование книги
					if ($bg_bibrefs_option['norm_refs']) {						// Преобразовать ссылку к нормализованному виду
						$newmt = bg_bibrefs_get_url($title, $chapter, $book.' '.$chapter, $lang);
					}
					else $newmt = bg_bibrefs_get_url($title, $chapter, $ref, $lang);
					$listmt = bg_bibrefs_get_url($title, $chapter, $book.' '.$chapter, $lang);
					$double = false;
					for ($k=0; $k < $j; $k++) {									// Проверяем не совпадают ли ссылки?
						if ($bg_bibrefs_all_refs[$k] == $listmt) {
							$double = true;
							break;
						}
					}
					if (!$double) {												// Дубликат не найден
						$bg_bibrefs_all_refs[$j]=$listmt;
						$j++;
					}
				} else {
					$newmt = bg_bibrefs_getQuotes($book, $chapter, $type, $lang, $prll );
				}
				$text = $text.substr($txt, $start, $matches[0][$i][1]-$start).str_replace($ref, $newmt, $matches[0][$i][0]);
				$start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
			}
		}
	}
	$txt = $text.substr($txt, $start);

	
/****************** ОТЛАДКА ****************************************/	
	if ($bg_bibrefs_option['debug']) {
		error_log(" Всего патернов : ". $i."\n", 3, $debug_file);
		$this_time = microtime(true)*1000;
		$time = ($this_time- $start_time);
		error_log("  * Обработка патернов: ". round($time, 2)." мсек.\n", 3, $debug_file);
		$time = ($this_time- $s_time);
		error_log(" Полное время : ". round($time, 2)." мсек.\n\n", 3, $debug_file);
		$start_time = $this_time;
	}
/*******************************************************************/	
	return $txt;
}
/******************************************************************************************
	Проверяем находится ли указанная позиция текста внутри тега  tag1 ...tag2,
    если "да" - возвращаем false, "нет" - true 
*******************************************************************************************/
function bg_bibrefs_check_tag($hdr, $pos) {

	$chrd = count($hdr[0]);

	for ($k = 0; $k < $chrd; $k++) {
		$start = $hdr[0][$k][1];
		$finish = $start + strlen($hdr[0][$k][0])-1;
		if ($pos >= $start && $pos <= $finish) return false;
	}
	return true; 
}
/******************************************************************************************
	Формирование ссылки на http://azbyka.ru/biblia/
	Используется в функции bg_bibrefs_bible_proc(),
	для работы требуется bg_bibrefs_getTitle() - см. ниже
	и bg_bibrefs_getBook() - см. ниже
*******************************************************************************************/
function bg_bibrefs_get_url($book, $chapter, $link, $lang) {
	global $bg_bibrefs_ch;
	
/*******************************************************************************
   Проверяем настройки
*******************************************************************************/  
	global $bg_bibrefs_option;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;
	
	if ($book != "") {	
		if ($bg_bibrefs_option['site'] == 'azbyka')
			$fullurl = "<a href='"."http://azbyka.ru/biblia/?".$book.".". $chapter.$bg_bibrefs_option['azbyka']."' target='".$bg_bibrefs_option['target']."'>" .$link. "</a>";	// Полный адрес ссылки на azbyka.ru
		elseif ($bg_bibrefs_option['site'] == 'this') {
			$page = $bg_bibrefs_option['page'];
			if ($page == "") $page = get_permalink(); 
			$fullurl = "<a href='".$page."?bs=".$book.$chapter."&lang=".$lang."' target='".$bg_bibrefs_option['target']."'>" .$link. "</a>";			// Полный адрес ссылки на текущий сайт
		}
		else $fullurl = $link;
		
		$the_title =  $bg_bibrefs_bookTitle[$book]." ".$bg_bibrefs_ch." ".$chapter;					// Название книги, номера глав и стихов	
		if ($bg_bibrefs_option['show_verses'] == 'on') {											// Текст  стихов
			$ajax_url = admin_url("admin-ajax.php?title=".$book."&chapter=".$chapter."&type=t_verses&lang=".$lang);
		} else {
			$ajax_url = "";
		}
		return "<span class='bg_data_title ".$bg_bibrefs_option['class']."' data-title='".$ajax_url."' title='".$the_title."'><span class='bg_data_tooltip'></span>".$fullurl."</span>"; 
	}
	else return "";
}

function bg_bibrefs_getBook($title) {

	global $bg_bibrefs_url;
	if (isset ($bg_bibrefs_url[$title])) return $bg_bibrefs_url[$title];// Обозначение книги Библии
	else return "";
}

/*******************************************************************************
   Полное наименование книги Библии
   Используется в функции bg_bibrefs_get_url()
*******************************************************************************/  
function bg_bibrefs_getTitle($book) {
	global $bg_bibrefs_bookTitle;
	return $bg_bibrefs_bookTitle[$book];								// Полное наименование книги Библии
}

/*******************************************************************************
   Короткое наименование книги Библии
   Используется в функции bg_bibrefs_bible_proc()
*******************************************************************************/  
function bg_bibrefs_getshortTitle($book) {
	global $bg_bibrefs_shortTitle;
	return $bg_bibrefs_shortTitle[$book];								// Короткое наименование книги Библии
}

/*******************************************************************************
  Преобразование римского числа в арабское

*******************************************************************************/  
function rome_to_arab($text) {
$font_arab = array(1,4,5,9,10,40,50,90,100);
$font_rome = array("I","IV","V","IX","X","XL","L","XC","C");	
	$rezult = 0;
	$pos = 0;
	$n = count($font_rome) - 1;
	$length = strlen($text);
	while ($n >= 0 && $pos < $length) {
		$len = strlen($font_rome[$n]);
		if (substr($text, $pos, $len) == $font_rome[$n]) {
			$rezult += $font_arab[$n];
			$pos += $len;
		}
		else $n--;
	}
	return $rezult;
}
