<?php
/******************************************************************************************************************************************
Плагин подсвечивает ссылки на текст Библии с помощью гиперссылок на сайт Православной энциклопедии "Азбука веры" (https://azbyka.ru/biblia). 
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
$bg_bibrefs_bookChapters = array(			// Количество глав в книгах Библии
	// Ветхий Завет
	// Пятикнижие Моисея
	'Gen'		=> 50, 						
	'Ex'		=> 40, 						
	'Lev'		=> 27, 						
	'Num'		=> 36, 						
	'Deut'		=> 34, 						
	// «Пророки» (Невиим) 
	'Nav'		=> 24, 						
	'Judg'		=> 21, 						
	'Rth'		=> 4, 						
	'1Sam'		=> 31, 						
	'2Sam'		=> 24, 						
	'1King'		=> 22, 						
	'2King'		=> 25, 						
	'1Chron'	=> 29, 						
	'2Chron'	=> 37, 						
	'Ezr'		=> 10, 						
	'Nehem'		=> 13, 						
	'Est'		=> 10, 						
	// «Писания» (Ктувим)
	'Job'		=> 42, 						
	'Ps'		=> 151, 						
	'Prov'		=> 31, 						
	'Eccl'		=> 12, 						
	'Song'		=> 8, 						

	'Is'		=> 66, 						
	'Jer'		=> 52, 						
	'Lam'		=> 5, 						
	'Ezek'		=> 48, 						
	'Dan'		=> 14, 						
	// Двенадцать малых пророков 
	'Hos'		=> 14, 						
	'Joel'		=> 3, 						
	'Am'		=> 9, 						
	'Avd'		=> 1, 						
	'Jona'		=> 4, 						
	'Mic'		=> 7, 						
	'Naum'		=> 3, 						
	'Habak'		=> 3, 						
	'Sofon'		=> 3, 						
	'Hag'		=> 2, 						
	'Zah'		=> 14, 						
	'Mal'		=> 4, 						
	// Второканонические книги
	'1Mac'		=> 16, 						
	'2Mac'		=> 15, 						
	'3Mac'		=> 7, 						
	'Bar'		=> 5, 						
	'2Ezr'		=> 9, 						
	'3Ezr'		=> 16, 						
	'Judf'		=> 16, 						
	'pJer'		=> 1, 						
	'Solom'		=> 19, 						
	'Sir'		=> 51, 						
	'Tov'		=> 14, 						
	// Новый Завет
	// Евангилие
	'Mt'		=> 28, 						
	'Mk'		=> 16, 						
	'Lk'		=> 24, 						
	'Jn'		=> 21, 						
	// Деяния и послания Апостолов
	'Act'		=> 28, 						
	'Jac'		=> 5, 						
	'1Pet'		=> 5, 						
	'2Pet'		=> 3, 						
	'1Jn'		=> 5, 						
	'2Jn'		=> 1, 						
	'3Jn'		=> 1, 						
	'Juda'		=> 1, 						
	// Послания апостола Павла
	'Rom'		=> 16, 						
	'1Cor'		=> 16, 						
	'2Cor'		=> 13, 						
	'Gal'		=> 6, 						
	'Eph'		=> 6, 						
	'Phil'		=> 4, 						
	'Col'		=> 4, 						
	'1Thes'		=> 5, 						
	'2Thes'		=> 3, 						
	'1Tim'		=> 6, 						
	'2Tim'		=> 4, 						
	'Tit'		=> 3, 						
	'Phlm'		=> 1, 						
	'Hebr'		=> 13, 						
	'Apok'		=> 22);
	
$bg_bibrefs_all_refs=array();				// Перечень всех ссылок 
$sps = "(?:\s|\x{00A0}|\x{00C2}|(?:&nbsp;))";
$dashes = "(?:[\x{2010}-\x{2015}]|(&#820[8-9];)|(&#821[0-3];))";
/******************************************************************************************
	Основная функция разбора текста и формирования ссылок,
    для работы требуется bg_bibrefs_get_url() - см. ниже
*******************************************************************************************/
function bg_bibrefs_bible_proc($txt, $type='', $lang='', $prll='') {
	global $sps, $dashes;
	global $post, $bg_bibrefs_start_time;
	global $bg_bibrefs_option;
	global $bg_bibrefs_all_refs;
	global $bg_bibrefs_lang_name, $bg_bibrefs_book_letters, $bg_bibrefs_book_length;
	global $bg_bibrefs_chapter, $bg_bibrefs_ch, $bg_bibrefs_psalm, $bg_bibrefs_ps;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile, $bg_bibrefs_bookChapters;

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
		error_log("Время ожидания начала работы скрипта: ". round((microtime(true) - $bg_bibrefs_start_time), 2)." сек.\n", 3, $debug_file);
	}
/*******************************************************************/	
	if (!$lang) $lang = set_bible_lang();
	
	$lang = include_books($lang);

	if (!empty($post)) {
		$norefs_posts_val = get_post_meta($post->ID, 'norefs', true);
		if ($norefs_posts_val || in_category( 'norefs' ) || has_tag( 'norefs' )) return $txt;
	}
	
    if ($bg_bibrefs_option['strip_space']) $txt = bg_bibrefs_strip_space($txt);

// Ищем все вхождения ссылок <a ...</a>, заголовков <h. ... </h.> и шорт-кодов [norefs]...[/norefs] и [bible]...[/bible]
	preg_match_all("/<a\\s.*?<\/a>/sui", $txt, $hdr_a, PREG_OFFSET_CAPTURE);
	preg_match_all("/<h([1-6])(.*?)<\/h\\1>/sui", $txt, $hdr_h, PREG_OFFSET_CAPTURE);
	preg_match_all("/\[norefs.*?\[\/norefs\]/sui", $txt, $hdr_norefs, PREG_OFFSET_CAPTURE);
	preg_match_all("/\[bible.*?\[\/bible\]/sui", $txt, $hdr_bible, PREG_OFFSET_CAPTURE);
	

// Ищем все вхождения ссылок на Библию
	$spss = $sps."*";
	// Разрешить отсутствие точки после обозначения книги
	if ($bg_bibrefs_option['dot']) $dot = "\.?";
	else $dot = "\.";
	// Разрешить римские цифры
	if ($bg_bibrefs_option['romeh']) {
		$romeh = '|I{1,3}|IV|V';
		$romeс = '|[IVXLC]+';
	} else {
		$romeh = "";
		$romeс = "";
	}
	$letters = 'A-Za-zА-Яа-яёіїєґўЁІЇЄҐЎ'.(isset($bg_bibrefs_book_letters)?$bg_bibrefs_book_letters:'');
	$wordsize = '{2,'.(isset($bg_bibrefs_book_length)?$bg_bibrefs_book_length:'8').'}';

//	$template = "((?:[1-4]".$romeh.")?".$spss."['A-Za-zА-Яа-яёіїєґўЁІЇЄҐЎ]{2,8})".$spss.$dot.$spss."((\d+".$romeс.")(".$spss."([".$sepd.$seps.":,-]|".$dashes.")".$spss."(\d+".$romeс."))*)";
	$ch_pattern = "(\d+".$romeс.")(".$spss."([\.:,-]|".$dashes.")".$spss."\d+)";
	$template = "((?:[1-5]".$romeh.")?".$spss."['".$letters."]".$wordsize.")".$spss.$dot.$spss."((".$ch_pattern."*)(".$spss.";".$spss."(".$ch_pattern."+))*)";

	$separator2 = "(?![0-9".$letters.":]|".$dashes.")";
	$separator1 = "(?<![0-9".$letters."])";
		
	preg_match_all("/".$separator1.$template.$separator2."/u", $txt, $matches, PREG_OFFSET_CAPTURE);
	$cnt = count($matches[0]);

	$text = "";
	$start = 0;
	$j = 0;

	$time0 = 0;
	$cycle_time = 1;
	$stime = microtime(true);
	
/****************** ОТЛАДКА ****************************************/	
	if ($bg_bibrefs_option['debug']) {
		$this_time = microtime(true)*1000;
		$time = ($this_time- $start_time);
		error_log("    Начальная обработка (регулярные выражения): ". round($time, 2)." мсек.\n", 3, $debug_file);
		$start_time = $this_time;
	}
/*******************************************************************/	
// Устанавливаем максимальное время работы скрипта
	$maxtime = $bg_bibrefs_option['maxtime'];
	if (!function_exists ('set_time_limit') || !(@set_time_limit ($maxtime))) {
		$systemtime = ini_get('max_execution_time'); 
		if (!$systemtime) $systemtime = 30;
		else $systemtime = intval($systemtime);
		$maxtime = $systemtime;
		$pretime = microtime(true) - $bg_bibrefs_start_time;
		$maxtime = $systemtime - $pretime - 2;
/****************** ОТЛАДКА ****************************************/	
		if ($bg_bibrefs_option['debug']) {
			error_log("    Ограничение времени работы скрипта: ". round($systemtime, 2)." сек.\n", 3, $debug_file);
			error_log("    Осталось: ". round($maxtime, 2)." сек.\n", 3, $debug_file);
		}
/*******************************************************************/	
		if ($maxtime < 2) return $txt;
	} 
	$exceptions = preg_split  ("/[;\n]/u", get_option('bg_bibrefs_exceptions'));
	for ($i = 0; $i < $cnt; $i++) {
	
		$mtch = trim (preg_replace("/".$sps."+/u", ' ', $matches[0][$i][0]));
		foreach ($exceptions as $exception) {
			$exception = trim (preg_replace("/".$sps."+/u", ' ', $exception));
			if (!$exception) continue;

			if (!strnatcasecmp ($mtch, $exception)) continue 2;
		}
	// Проверим по каждому паттерну. 
		preg_match("/".$template."/u", $matches[0][$i][0], $mt);
		
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
			$chapter = preg_replace("/(?<=[IVXLC]),\./u", ":", $chapter);		// Римскими цифрами обозначаются только главы (после них должно идти ":", а не "," или ".")

		// Замена римских цифр на арабские
			preg_match("/(I{1,3}|IV)(?=".$sps."*['".$letters."]".$wordsize.")/u", $title, $rome);
			if ($rome) {
				$title = preg_replace("/".$rome[0]."/u", rome_to_arab($rome[0]), $title, 1);
			}
			preg_match_all("/[IVXLC]+/u", $chapter, $rome, PREG_OFFSET_CAPTURE);
			$crome = count($rome[0]);
			for ($r = 0; $r < $crome; $r++) {
				$chapter = preg_replace("/".$rome[0][$r][0]."/u", rome_to_arab($rome[0][$r][0]), $chapter, 1);
			}

			$title = bg_bibrefs_getBook($title);// Обозначение книги
			$ref = $matches[0][$i][0];
			$ref = trim ( $ref, "\x20\f\t\v\n\r\xA0\xC2" );
			if (strcasecmp($title, "") != 0) { 
				$chapters = $bg_bibrefs_bookChapters[$title];
				// В книгах с одной главой, допускается указывать только номер стиха
				if ($chapters==1 && strpos($chapter, '1:')!==0 && strpos($chapter, ':')===false ) $chapter = "1:".$chapter;
 
				if (bg_bibrefs_check_tag($hdr_a, $matches[0][$i][1]) 
					&& (($bg_bibrefs_option['headers']=='on') || bg_bibrefs_check_tag($hdr_h, $matches[0][$i][1])) 
					&&  bg_bibrefs_check_tag($hdr_norefs, $matches[0][$i][1])
					&&  bg_bibrefs_check_tag($hdr_bible, $matches[0][$i][1])) {
					if (intval($chapter) <= $chapters) {	// Номер главы не больше максимального
						// Проверяем, не примеяется ли западная нотация?
						if (isWesternNotation ($chapter, $chapters)) {
							// Заменяем запятую на двоеточие, оставляя запятые как разделители глав

							$chapter = preg_replace_callback ("/(\d+)([-:,\.;])/", function ($match) {
								static $prevDelimeter=',', $prevDigit = 999;
								$mt = $match[2];
								if (($mt == ',') && 
									($prevDigit>=$match[1]) &&
									($prevDelimeter == ',' || $prevDelimeter == ';' || $prevDelimeter == '.' || $prevDelimeter == '-')) $mt = ':';
								$prevDelimeter = $mt;
								$prevDigit = $match[1];
								return $match[1].$mt;
							}, $chapter);		
						}
						
						$chapter = preg_replace("/\./u", ',', $chapter);	// Заменяем точку на запятую
						$chapter = preg_replace("/;/u", ',', $chapter);		// Заменяем точку с запятой на запятую

						if ($type == '' || $type == 'link') {
							$book = bg_bibrefs_getshortTitle($title);					// Короткое наименование книги
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
							$newmt = bg_bibrefs_getQuotes($title, $chapter, $type, $lang, $prll );
						}
					} else $newmt = "<span class='bg_data_title ".$bg_bibrefs_option['class']."' data-title='' title='' style='border-bottom: 2px dotted red;'><span class='bg_data_tooltip'></span>".$matches[0][$i][0]."</span>";
				} else $newmt = $matches[0][$i][0];
				$text = $text.substr($txt, $start, $matches[0][$i][1]-$start).str_replace($ref, $newmt, $matches[0][$i][0]);
				$start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
			}
		}
	// Ограничение времени работы функции
		$time = microtime(true) - $stime;
		if ($time-$time0 > $cycle_time) $cycle_time = $time-$time0;
		$time0 = $time;
		if ($maxtime && $time > $maxtime-$cycle_time) break;
	}
	$txt = $text.substr($txt, $start);

	
/****************** ОТЛАДКА ****************************************/	
	if ($bg_bibrefs_option['debug']) {
		$this_time = microtime(true)*1000;
		$time = ($this_time- $start_time);
		error_log(date('d.m.Y h:i:s').": ". get_permalink()."\n", 3, $debug_file);
		error_log("    Обработано: ". $i." Всего патернов: ". $cnt." за: ". round($time, 2)." мсек.\n", 3, $debug_file);
		$time = ($this_time- $s_time);
		error_log("    Полное время обработки: ". round($time, 2)." мсек.\n", 3, $debug_file);
		error_log("Полное время работы скрипта: ". round((microtime(true) - $bg_bibrefs_start_time), 2)." сек.\n\n", 3, $debug_file);
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
	Формирование ссылки на https://azbyka.ru/biblia/
	Используется в функции bg_bibrefs_bible_proc(),
	для работы требуется bg_bibrefs_getTitle() - см. ниже
	и bg_bibrefs_getBook() - см. ниже
*******************************************************************************************/
function bg_bibrefs_get_url($book, $chapter, $link, $lang) {
	global $bg_bibrefs_chapter, $bg_bibrefs_ch, $bg_bibrefs_psalm, $bg_bibrefs_ps;
	
/*******************************************************************************
   Проверяем настройки
*******************************************************************************/  
	global $bg_bibrefs_option;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;
	
	if ($book != "") {	
		if ($bg_bibrefs_option['site'] == 'azbyka')
			$fullurl = "<a href='"."https://azbyka.ru/biblia/?".$book.".". $chapter.$bg_bibrefs_option['azbyka']."' target='".$bg_bibrefs_option['target']."'>" .$link. "</a>";	// Полный адрес ссылки на azbyka.ru
		elseif ($bg_bibrefs_option['site'] == 'this') {
			$page = $bg_bibrefs_option['page'];
			if ($page == "") $page = get_permalink(); 
//			$fullurl = "<a href='".$page."?bs=".$book.'.'.$chapter."&lang=".$lang."' target='".$bg_bibrefs_option['target']."'>" .$link. "</a>";			// Полный адрес ссылки на текущий сайт
			$fullurl = "<a href='".$page."?book=".$book.'&ch='.$chapter."&lang=".$lang."' target='".$bg_bibrefs_option['target']."'>" .$link. "</a>";			// Полный адрес ссылки на текущий сайт
		}
		else $fullurl = $link;
		
		if (isset($bg_bibrefs_ps) && $book == 'Ps')
			$the_title =  $bg_bibrefs_bookTitle[$book]." ".$bg_bibrefs_ps." ".$chapter;				// Название книги, номера псалмов и стихов	
		else
			$the_title =  $bg_bibrefs_bookTitle[$book]." ".$bg_bibrefs_ch." ".$chapter;				// Название книги, номера глав и стихов	

		if ($bg_bibrefs_option['show_verses'] == 'on') {											// Текст  стихов
//			$ajax_url = admin_url("admin-ajax.php?title=".$book."&chapter=".$chapter."&type=t_verses&lang=".$lang);
			$ajax_url = "?title=".$book."&chapter=".$chapter."&type=t_verses&lang=".$lang;
		} else {
			$ajax_url = "";
		}
		if ($bg_bibrefs_option['pload']) {
			$verses = bg_bibrefs_getQuotes($book, $chapter, 't_verses', $lang); 
			if ($verses) {
				$expand_button = "<img src='".plugins_url( '../js/expand.png' , __FILE__ )."' style='cursor:pointer; margin-right: 8px;' align='left' width=16 height=16 data-title1='".(__('Expand', 'bg_bibrefs' ))."' data-title2='".(__('Hide', 'bg_bibrefs' ))."' />";
				$verses = $expand_button.$verses; 
				$url = "<span class='bg_data_title ".$bg_bibrefs_option['class']."' data-title='' title=''><span class='bg_data_tooltip'>".$verses."</span>".$fullurl."</span>"; 
			} else $url = "<span class='bg_data_title ".$bg_bibrefs_option['class']."' data-title='' title='".$the_title."' style='border-bottom: 2px dotted red;'><span class='bg_data_tooltip'></span>".$link."</span>";
			return $url;
		} else {
			return "<span class='bg_data_title ".$bg_bibrefs_option['class']."' data-title='".$ajax_url."' title='".$the_title."'><span class='bg_data_tooltip'></span>".$fullurl."</span>"; 
		}
	}
	else return "";
}

function bg_bibrefs_getBook($title, $lang=null) {

	global $bg_bibrefs_url;
	if ($lang) $lang = include_books($lang);
	if (isset ($bg_bibrefs_url[$title])) return $bg_bibrefs_url[$title];// Обозначение книги Библии
	else return "";
}

/*******************************************************************************
   Полное наименование книги Библии
   Используется в функции bg_bibrefs_get_url()
*******************************************************************************/  
function bg_bibrefs_getTitle($book, $lang=null) {
	global $bg_bibrefs_bookTitle;
	if ($lang) $lang = include_books($lang);
	return $bg_bibrefs_bookTitle[$book];								// Полное наименование книги Библии
}

/*******************************************************************************
   Короткое наименование книги Библии
   Используется в функции bg_bibrefs_bible_proc()
*******************************************************************************/  
function bg_bibrefs_getshortTitle($book, $lang=null) {
	global $bg_bibrefs_shortTitle;
	if ($lang) $lang = include_books($lang);
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

/*******************************************************************************
   Функция удаляет пробелы в обозначениях книг, начинающихся с цифр
   Используется в функции bg_bibrefs_bible_proc()
*******************************************************************************/  
function bg_bibrefs_strip_space($txt) {
	global $bg_bibrefs_url, $sps;
	
	// Формируем массив допустимых сокращений книг с номерами
	$keys = array_keys ($bg_bibrefs_url);
	$dbooks = array();
	$i=0;
	foreach ($keys as $key) {
		if (preg_match ( "/\d.*/iu" , $key)) {
			$dbooks[$i]=$key;
			$i++;
		}
	}
	// Удаляем пробелы
	foreach ($dbooks as $dbook) {
		$txt = preg_replace_callback  ("/(".$dbook[0].$sps."*".substr($dbook, 1).")(".$sps."*\.?".$sps."*(\d+|[IVXLC]+))/iu", 
			function ($matches) {
				global $sps;
				$dbook = preg_replace ("/(".$sps.")+/iu","", $matches[1]);
				return $dbook.$matches[2];
			}, $txt);
	}
	return $txt;
}
/*******************************************************************************
	Функция проверяет, является ли запись западной нотацией

*******************************************************************************/  
function isWesternNotation ($ch, $chapters) {
	global $bg_bibrefs_option;
	if (preg_match("/^(\d{1,3}),/m", $ch)) {						// Если после первой цифры идет запятая 
		if (preg_match("/[;\.\-]/u", $ch)) return true;				// и при этом выражение содержит точку с запятой, точку или тире
		// Особый случай: два числа, разделенных запятой
		if (preg_match("/^\d{1,3},\d{1,3}$/m", $ch)) {
			$arr = explode(',', $ch);
			// Западная нотация всегда если:
			if (intval ($arr[0]) >= intval ($arr[1]) ||		// Первая цифра больше или равна второй
				intval ($arr[1]) > $chapters)				// Вторая цифра больше кол-ва глав в книге
				return true;
		}
		if ($bg_bibrefs_option['sepc']) return true;	// Опция включена - западная нотация
	}
	return false;
}
