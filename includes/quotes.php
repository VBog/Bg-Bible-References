<?php
/*******************************************************************************
   Создание контента цитаты 
   Вызывает bg_bibrefs_printVerses() - см. ниже
*******************************************************************************/  
function bg_bibrefs_getQuotes($book, $chapter, $type, $lang, $prll='') {
	global $bg_bibrefs_option;
	global $bg_bibrefs_chapter, $bg_bibrefs_ch;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;
	$lang = include_books($lang);

/*******************************************************************************
   Преобразование обозначения книги из формата azbyka.ru в формат patriarhia.ru
   чтение и преобразование файла книги
*******************************************************************************/  
	if (!$book) return "";
	if (!$bg_bibrefs_bookFile[$book]) return "";
	$key='bible-'.$book.'_'.$lang;
	if (!$bg_bibrefs_option['cashe'] || (false===($code=wp_cache_get($key,'bg-bible-refs'))) ){
		$book_file = 'bible/'.$bg_bibrefs_bookFile[$book];										// Имя файла книги

	// Получаем данные из файла	
		$code = false;
		if ($bg_bibrefs_option['fgc'] == 'on' && function_exists('file_get_contents')) {		// Попытка1. Если данные не получены попробуем применить file_get_contents()
			$url = dirname(dirname(__FILE__ )).'/'.$book_file;										// Локальный URL файла
			$code = file_get_contents($url);		
		}

		if ($bg_bibrefs_option['fopen'] == 'on' && !$code) {									// Попытка 2. Если данные опять не получены попробуем применить fopen() 
			$url = dirname(dirname(__FILE__ )).'/'.$book_file;										// Локальный URL файла
			$ch=fopen($url, "r" );																	// Открываем файл для чтения
			if($ch)
			{
				while (!feof($ch))	{
					$code .= fread($ch, 2097152);													// загрузка текста (не более 2097152 байт)
				}
				fclose($ch);																		// Закрываем файл
			}
		}
		if ($bg_bibrefs_option['curl'] == 'on' && function_exists('curl_init') && !$code) {		// Попытка3. Если установлен cURL				
			$url = plugins_url( $book_file , dirname(__FILE__ ) );									// URL файла
			$ch = curl_init($url);																	// создание нового ресурса cURL
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);											// возврат результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер
			$code = curl_exec($ch);																	// загрузка текста
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);										
			if ($httpCode != '200') $code = false;													// Проверка на код http 200
			curl_close($ch);																		// завершение сеанса и освобождение ресурсов
		} 

		if (!$code) return "";																	// Увы. Паранойя хостера достигла апогея. Файл не прочитан или ошибка

		if ($bg_bibrefs_option['cashe']) wp_cache_set($key,$code,'bg-bible-refs',3600);		
	}	
// Преобразовать json в массив
	$json = json_decode($code, true);															
	if ($type == "book") $verses = "<h3>".bg_bibrefs_getTitle($book)."</h3>";
	else if ($type == "t_verses") $verses = "<strong>".bg_bibrefs_getTitle($book)."</strong><br>";
	else $verses = "";
	if ($type <> "quote") $verses = $verses."<span style='display: block;'>";	
/*******************************************************************************
   Разбор ссылки и формирование текста стихов Библии
  
*******************************************************************************/  
	
	$jj = 0;
	$chr = 0;																					// Предыдущая глава

	while (preg_match("/\\d+/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {					// Должно быть число - номер главы 
		$jj++;																					// Защита от зацикливания (не более 10 циклов)
		if ($jj > 10) return "***";
		$ch1 = (int)$matches[0][0];
		$chapter = substr($chapter,(int)$matches[0][1]);
		if (preg_match("/\\:|\\,|\\-/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {			// Допускается: двоеточие, запятая, тире или <конец строки>
			$sp = $matches[0][0];
			$chapter = substr($chapter,(int)$matches[0][1]);
		} else $sp = "";
		
		if (strcasecmp ($sp, ":") == 0) {
//		Двоеточие - далее стихи
			$ii = 0;
			while (preg_match("/\\d+/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {			// Должно быть число - номер стиха 
				$ii++;																			// Защита от зацикливания (не более 10 циклов)
				if ($ii >10) return "***";
				$vr1 = (int)$matches[0][0];
				$chapter = substr($chapter,(int)$matches[0][1]);
				if (preg_match("/\\:|\\,|\\-/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {	// Допускается:  двоеточие, запятая, тире или <конец строки>
					$sp = $matches[0][0];
					$chapter = substr($chapter,(int)$matches[0][1]);
				} else $sp = "";
				if (strcasecmp ($sp, ":") == 0) {												// Если двоеточие, то не номер стиха, а номер главы и далее стихи
					$ch1 = $vr1;
				} else {
					$ch2 = $ch1;
					if (strcasecmp ($sp, "-") == 0) {
						preg_match("/\\d+/u", $chapter, $matches, PREG_OFFSET_CAPTURE);			// Должно быть число - номер стиха 
						$vr2 = (int)$matches[0][0];
						$chapter = substr($chapter,(int)$matches[0][1]);

						if (preg_match("/\\:|\\,/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {	// Допускается: двоеточие, запятая или <конец строки>
							$sp = $matches[0][0];
							$chapter = substr($chapter,(int)$matches[0][1]);
							if (strcasecmp ($sp, ":") == 0) {												// Если двоеточие, то не номер стиха, а номер главы и далее стихи
								$ch2 = $vr2;
								preg_match("/\\d+/u", $chapter, $matches, PREG_OFFSET_CAPTURE);				// Должно быть число - номер стиха 
								$vr2 = (int)$matches[0][0];
								if (preg_match("/\\,/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {	// Допускается: запятая или <конец строки>
									$sp = $matches[0][0];
									$chapter = substr($chapter,(int)$matches[0][1]);
								} else $sp = "";
							}
							else if (strcasecmp ($sp, ",") == 0) {
								preg_match("/\\d+/u", $chapter, $matches, PREG_OFFSET_CAPTURE);				// Должно быть число - номер стиха 
								$sp = $matches[0][0];
								$chapter = substr($chapter,(int)$matches[0][1]);
							} else $sp = "";
						} else $sp = "";
					} else {
						$vr2 = $vr1;
					}
					$verses = $verses.bg_bibrefs_printVerses ($json, $book, $chr, $ch1, $ch2, $vr1, $vr2, $type, $lang, $prll);
					$chr = $ch1;
					if ($sp == "") break;
				}
			}
		} else {
//		Далее до двоеточия только главы
			if (strcasecmp ($sp, "-") == 0) {
				preg_match("/\\d+/u", $chapter, $matches, PREG_OFFSET_CAPTURE);					// Должно быть число - номер главы 
				$ch2 = (int)$matches[0][0];
				$chapter = substr($chapter,(int)$matches[0][1]);
/*				if (preg_match("/\\,/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {			// Допускается: запятая или <конец строки>
					$sp = $matches[0][0];
					$chapter = substr($chapter,(int)$matches[0][1]);
				} else $sp = ""; */
				$vr1 = 0;
				$vr2 = 999;
				if (preg_match("/\\:|\\,/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {	// Допускается: двоеточие, запятая или <конец строки>
					$sp = $matches[0][0];
					$chapter = substr($chapter,(int)$matches[0][1]);
					if (strcasecmp ($sp, ":") == 0) {												// Если двоеточие, то не номер стиха, а номер главы и далее стихи
						preg_match("/\\d+/u", $chapter, $matches, PREG_OFFSET_CAPTURE);				// Должно быть число - номер стиха 
						$vr2 = (int)$matches[0][0];
						if (preg_match("/\\,/u", $chapter, $matches, PREG_OFFSET_CAPTURE)) {	// Допускается: запятая или <конец строки>
							$sp = $matches[0][0];
							$chapter = substr($chapter,(int)$matches[0][1]);
						} else $sp = "";
					}
					else if (strcasecmp ($sp, ",") == 0) {
						$sp = $matches[0][0];
						$chapter = substr($chapter,(int)$matches[0][1]);
					} else $sp = "";
				} else $sp = "";
			} else {
				$ch2 = $ch1;
				$vr1 = 0;
				$vr2 = 999;
			}
			$verses = $verses.bg_bibrefs_printVerses ($json, $book, $chr, $ch1, $ch2, $vr1, $vr2, $type, $lang, $prll);
			$chr = $ch2;
		}
		if ($sp == "") break;
	}
	if ($type <> "quote") $verses = $verses."</span>";	

	return $verses;
}
/*******************************************************************************
	Формирование содержания цитаты
	Вызывает bg_bibrefs_optina() - см. ниже
*******************************************************************************/  
function bg_bibrefs_printVerses ($json, $book, $chr, $ch1, $ch2, $vr1, $vr2, $type, $lang, $prll='') {
	global $bg_bibrefs_option;
	global $bg_bibrefs_chapter, $bg_bibrefs_ch;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;

    $bg_show_fn = get_option( 'bg_bibrefs_show_fn' );

	$shortTitle = $bg_bibrefs_shortTitle[$book];
	$verses = "";
	$cv1 = $ch1 *1000 + $vr1;
	$cv2 = $ch2 *1000 + $vr2;
	$cn_json = count($json);
	for ($i=0; $i < $cn_json; $i++) {
		$ch = (int)$json[$i]['part'];
		$vr = (int)$json[$i]['stix'];
		$cv = $ch *1000 + $vr;
		if ( $cv >= $cv1 && $cv <= $cv2) {
			if (isset($json[$i]['stix_fn'])) {
				$fn = $json[$i]['stix_fn'];
				if ($fn != '*' && $bg_show_fn != 'on') $fn="";
			} else $fn="";

			if ($type == 'book') { 																						// Тип: книга
				if ($chr != $ch) {
					$verses = $verses."<strong>".$bg_bibrefs_chapter." ".$ch."</strong><br>";					// Печатаем номер главы
					$chr = $ch;
				}
				if ($json[$i]['stix'] == 0) $pointer = "";
				else $pointer = "<em>".$json[$i]['stix_n'].$fn."</em> ";													// Только номер стиха
			} else if ($type == 'verses' || $type == 't_verses') { 														// Тип: стихи или стихи с названием книг
				if ($json[$i]['stix'] == 0) $pointer = "<em>".$json[$i]['part']."</em>   ";
				else $pointer = "<em>".$json[$i]['part'].":".$json[$i]['stix_n'].$fn."</em> ";							// Номер главы : номер стиха
			} else if ($type == 'b_verses') { 																			// Тип: стихи
				if ($json[$i]['stix'] == 0) $pointer = "<em>".$shortTitle.$json[$i]['part'].$fn."</em>   ";
				else $pointer = "<em>".$shortTitle.$json[$i]['part'].":".$json[$i]['stix_n'].$fn."</em> ";				// Книга. номер главы : номер стиха
			} else {																									// Тип: цитата
				$pointer = "";																							// Ничего
			}
			$txt = trim(strip_tags($json[$i]['text']));
			if ($txt) {
				if ($json[$i]['stix'] == 0) $txt = "<strong>".$pointer.$txt."</strong>";
				else  $txt = $pointer.bg_bibrefs_optina($txt, $book, $ch, $vr, $lang);

				$verses = $verses.$txt;
				if ($type == 'quote') {$verses = $verses." ";}															// Если цитата, строку не переводим
				else {
					if (($bg_bibrefs_option['parallel'] == 'on' && $prll != 'off') || $prll == 'on') {
						$cn_linksKey = count($json[$i]['linksKey']);
						if ($cn_linksKey) $verses .= " <span class='bg_bibrefs_passage'>{";
						for ($j=0; $j < $cn_linksKey; $j++) {
							$verses .= bg_bibrefs_linksKey($json[$i]['linksKey'][$j][1], $lang);
						}
						if ($cn_linksKey) $verses .= "}</span>";
					}
					$verses .= "<br>";
				}
			} 
		}
	}
	return $verses;
}
/*******************************************************************************
	Формирование ссылки на паралельные места
	$txt = $json[$i]['linksKey'][1];
	 &#128279; - символ ссылки
*******************************************************************************/  
function bg_bibrefs_linksKey( $linksKey, $lang) {
	global $bg_bibrefs_option;
	global $bg_bibrefs_chapter, $bg_bibrefs_ch;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;

	$quote = $linksKey;
	$template = "/(\d?\s*[А-яA-z]{2,8})(\.*|\s*)(\d+\,\s*\d+)/ui";
	preg_match($template, $linksKey, $mt);
	$cn = count($mt);
	if ($cn > 0) {
		$title = preg_replace("/\s/u", '',$mt[1]); 					// Убираем пробельные символы, включая пробел, табуляцию, переводы строки 
		$title = bg_bibrefs_getBook($title);						// Стандартное обозначение книги
		$chapter = preg_replace("/\s/u", '', $mt[3]);				// и другие юникодные пробельные символы
		$chapter = preg_replace("/\,/u", ':', $chapter);			// Если глава отделена запятой, заменяем ее на двоеточие.
		if ($title != "") {						
			if (!$lang) $lang = set_bible_lang();
			$quote = bg_bibrefs_get_url($title, $chapter, '&#128279;'.$bg_bibrefs_shortTitle[$title].'&nbsp;'.$chapter.' ', $lang);
		}
	}
	return $quote;
}

/*******************************************************************************
	Создание ссылки на толкование Священного Писания на сайте Оптиной пустыни.
  
*******************************************************************************/  
function bg_bibrefs_optina($txt, $book, $chapter, $verse, $lang) {

	$opt = array(						// Таблица соответствия azbyka.ru и bible.optina.ru
		// Ветхий Завет				
		// Пятикнижие Моисея				
		'Gen'	 	=>'old:gen:',		//'Книга Бытия', 
		'Ex'	 	=>'old:ish:',		//'Книга Исход', 
		'Lev'	 	=>'old:lev:',		//'Книга Левит', 
		'Num'	 	=>'old:chis:',		//'Книга Числа', 
		'Deut'	 	=>'old:vtor:',		//'Второзаконие',
		// «Пророки» (Невиим) 				
		'Nav'	 	=>'old:nav:',		//'Книга Иисуса Навина',
		'Judg'		=>'old:sud:',		//'Книга Судей Израилевых', 
		'Rth'	 	=>'old:ruf:',		//'Книга Руфь',
		'1Sam'	 	=>'old:1ts:',		//'Первая книга Царств (Первая книга Самуила)', 
		'2Sam'	 	=>'old:2ts:',		//'Вторая книга Царств (Вторая книга Самуила)', 
		'1King' 	=>'old:3ts:',		//'Третья книга Царств (Первая книга Царей)', 
		'2King' 	=>'old:4ts:',		//'Четвёртая книга Царств (Вторая книга Царей)',
		'1Chron' 	=>'old:1par:',		//'Первая книга Паралипоменон (Первая книга Хроник, Первая Летопись)', 
		'2Chron' 	=>'old:2par:',		//'Вторая книга Паралипоменон (Вторая книга Хроник, Вторая Летопись)', 
		'Ezr'	 	=>'old:ezd:',		//'Книга Ездры (Первая книга Ездры)', 
		'Nehem' 	=>'old:neem:',		//'Книга Неемии', 
		'Est'	 	=>'old:esf:',		//'Книга Есфири',  
		// «Писания» (Ктувим)				
		'Job'	 	=>'old:iov:',		//'Книга Иова',
		'Ps' 		=>'old:ps:',		//'Псалтирь', 
		'Prov'	 	=>'old:pr:',		//'Книга Притчей Соломоновых', 
		'Eccl'	 	=>'old:ekl:',		//'Книга Екклезиаста, или Проповедника', 
		'Song'	 	=>'old:pp:',		//'Песнь песней Соломона',
						
		'Is' 		=>'old:is:',		//'Книга пророка Исайи', 
		'Jer' 		=>'old:ier:',		//'Книга пророка Иеремии',
		'Lam' 		=>'old:pier:',		//'Книга Плач Иеремии', 
		'Ezek'	 	=>'old:iez:',		//'Книга пророка Иезекииля',
		'Dan' 		=>'old:dan:',		//'Книга пророка Даниила', 
		// Двенадцать малых пророков 				
		'Hos' 		=>'old:os:',		//'Книга пророка Осии', 
		'Joel'	 	=>'old:iol:',		//'Книга пророка Иоиля',
		'Am' 		=>'old:am:',		//'Книга пророка Амоса', 
		'Avd' 		=>'old:av:',		//'Книга пророка Авдия', 
		'Jona'	 	=>'old:ion:',		//'Книга пророка Ионы',
		'Mic' 		=>'old:mih:',		//'Книга пророка Михея', 
		'Naum' 		=>'old:naum:',		//'Книга пророка Наума',
		'Habak' 	=>'old:avm:',		//'Книга пророка Аввакума', 
		'Sofon' 	=>'old:sof:',		//'Книга пророка Софонии', 
		'Hag' 		=>'old:ag:',		//'Книга пророка Аггея', 
		'Zah' 		=>'old:zah:',		//'Книга пророка Захарии',
		'Mal' 		=>'old:mal:',		//'Книга пророка Малахии',
		// Второканонические книги				
		'1Mac'	 	=>'old:1mak:',		//'Первая книга Маккавейская',
		'2Mac'	 	=>'old:2mak:',		//'Вторая книга Маккавейская', 
		'3Mac'	 	=>'old:3mak:',		//'Третья книга Маккавейская', 
		'Bar' 		=>'old:var:',		//'Книга пророка Варуха', 
		'2Ezr' 		=>'old:2ezd:',		//'Вторая книга Ездры', 
		'3Ezr' 		=>'old:3ezd:',		//'Третья книга Ездры',
		'Judf' 		=>'old:iud:',		//'Книга Иудифи', 
		'pJer' 		=>'old:pos:',		//'Послание Иеремии', 
		'Solom' 	=>'old:prs:',		//'Книга Премудрости Соломона',
		'Sir' 		=>'old:prsir:',		//'Книга Премудрости Иисуса, сына Сирахова', 
		'Tov' 		=>'old:tov:',		//'Книга Товита',
		// Новый Завет				
		// Евангилие				
		'Mt' 		=>'new:mf:',		//'Евангелие от Матфея',
		'Mk' 		=>'new:mk:',		//'Евангелие от Марка', 
		'Lk' 		=>'new:lk:',		//'Евангелие от Луки', 
		'Jn' 		=>'new:in:',		//'Евангелие от Иоанна', 
		// Деяния и послания Апостолов				
		'Act' 		=>'new:act:',		//'Деяния святых Апостолов', 
		'Jac'	 	=>'new:iak:',		//'Послание Иакова', 
		'1Pet'	 	=>'new:1pet:',		//'Первое послание Петра', 
		'2Pet'	 	=>'new:2pet:',		//'Второе послание Петра',
		'1Jn'	 	=>'new:1in:',		//'Первое послание Иоанна', 
		'2Jn'	 	=>'new:2in:',		//'Второе послание Иоанна', 
		'3Jn'	 	=>'new:3in:',		//'Третье послание Иоанна',
		'Juda'	 	=>'new:iud:',		//'Послание Иуды', 
		// Послания апостола Павла				
		'Rom' 		=>'new:rim:',		//'Послание апостола Павла к Римлянам', 
		'1Cor'	 	=>'new:1kor:',		//'Первое послание апостола Павла к Коринфянам', 
		'2Cor'	 	=>'new:2kor:',		//'Второе послание апостола Павла к Коринфянам',
		'Gal' 		=>'new:gal:',		//'Послание апостола Павла к Галатам', 
		'Eph' 		=>'new:ef:',		//'Послание апостола Павла к Ефесянам', 
		'Phil'	 	=>'new:fil:',		//'Послание апостола Павла к Филиппийцам', 
		'Col' 		=>'new:kol:',		//'Послание апостола Павла к Колоссянам',
		'1Thes' 	=>'new:1sol:',		//'Первое послание апостола Павла к Фессалоникийцам (Солунянам)',
		'2Thes' 	=>'new:2sol:',		//'Второе послание апостола Павла к Фессалоникийцам (Солунянам)',  
		'1Tim' 		=>'new:1tim:',		//'Первое послание апостола Павла к Тимофею', 
		'2Tim' 		=>'new:2tim:',		//'Второе послание апостола Павла к Тимофею',
		'Tit' 		=>'new:tit:',		//'Послание апостола Павла к Титу', 
		'Phlm'	 	=>'new:fm:',		//'Послание апостола Павла к Филимону', 
		'Hebr'	 	=>'new:evr:',		//'Послание апостола Павла к Евреям', 
		'Apok'	 	=>'new:otkr:');		//'Откровение Иоанна Богослова (Апокалипсис)'

	global $bg_bibrefs_option;
//	$bg_interpret_val = get_option( 'bg_bibrefs_interpret' );
	if ($bg_bibrefs_option['interpret'] == 'on') {
		$ch = str_pad($chapter, strcasecmp($book,'Ps')?2:3, "0", STR_PAD_LEFT);
		$vr = str_pad($verse, 2, "0", STR_PAD_LEFT);
		return ("<a href='http://bible.optina.ru/".$opt[$book].$ch.":".$vr."' title='".(__( 'Click to go to interpretation', 'bg_bibrefs' ))."' target='".$bg_bibrefs_option['target']."'>".$txt."</a>");
	}
	elseif ($bg_bibrefs_option['site'] == 'azbyka') {
		return "<a href='"."http://azbyka.ru/biblia/?".$book.".". $chapter.":".$verse.$bg_bibrefs_option['azbyka']."' title='".(__( 'Click to go to the azbyka.ru', 'bg_bibrefs' ))."' target='".$bg_bibrefs_option['target']."'>" .$txt. "</a>";	// Полный адрес ссылки на azbyka.ru
	}
	elseif ($bg_bibrefs_option['site'] == 'this') {
		$page = $bg_bibrefs_option['page'];
		if ($page == "") $page = get_permalink(); 
		return "<a href='".$page."?bs=".$book.$chapter.":".$verse."&lang=".$lang."' title='".(__( 'Click to view on page', 'bg_bibrefs' ))."' target='".$bg_bibrefs_option['target']."'>" .$txt. "</a>";			// Полный адрес ссылки на текущий сайт
	}
	else return $txt;

}

/*******************************************************************************
	Возвращает ссылку цитаты из Библии из файла quotes.txt
  
*******************************************************************************/  
function bg_bibrefs_bible_quote_refs($ref, $lang) {
	global $bg_bibrefs_option;
	global $bg_bibrefs_url, $bg_bibrefs_bookTitle, $bg_bibrefs_shortTitle, $bg_bibrefs_bookFile;
//	bg_bibrefs_get_options ();
	$lang = include_books($lang);
	
	$refs_file = $bg_bibrefs_option['refs_file'];

	$url = dirname(dirname(__FILE__ )).'/'.$refs_file;										// Локальный URL файла
	if (!is_file ( $url )) {																// Если пользовательский файл не существует, то файл по умолчанию
		$refs_file = 'quotes.txt';
		$url = dirname(dirname(__FILE__ )).'/'.$refs_file;									
	}
// Получаем данные из файла	
	$text = false;
	if ($bg_bibrefs_option['fgc'] == 'on' && function_exists('file_get_contents')) {		// Попытка1. Если данные не получены попробуем применить file_get_contents()
		$text = file_get_contents($url);		
	}
	if ($bg_bibrefs_option['fopen'] == 'on' && !$text) {									// Попытка 2. Если данные опять не получены попробуем применить fopen() 
		$ch=fopen($url, "r" );																	// Открываем файл для чтения
		if($ch)	{
			while (!feof($ch))	{
				$text .= fread($ch, 2097152);													// загрузка текста (не более 2097152 байт)
			}
			fclose($ch);																		// Закрываем файл
		}
	}
	if ($bg_bibrefs_option['curl'] == 'on' && function_exists('curl_init') && !$text) {		// Попытка3. Если установлен cURL				
		$url = plugins_url( $refs_file , dirname(__FILE__ ) );
		$ch = curl_init($url);																	// создание нового ресурса cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);											// возврат результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер
		$text = curl_exec($ch);																	// загрузка текста
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);										
		if ($httpCode != '200') $text = false;													// Проверка на код http 200
		curl_close($ch);																		// завершение сеанса и освобождение ресурсов
	} 
	if (!$text) return "";																	// Увы. Паранойя хостера достигла апогея. Файл не прочитан или ошибка

	$text= trim($text);												// Удаляем пробелы (или другие символы) из начала и конца текста

	$refs = preg_split ("/\s+/sui", $text);							// Разделим текст на ссылки
	$cnt = count($refs);											// Количество ссылок
	if ($ref == 'rnd') $z = rand(0, $cnt-1);							// Случайная ссылка
	else if ($ref == 'days') {
		$z = date('z');													// Порядковый номер дня в году
		if ($cnt < $z) $z = $z%$cnt;
	}
	else {
		$z = $ref - 1;													// Номер записи (отсчет от нуля)
		if ($z < 0) $z = 0;
		else if ($z > $cnt-1) $z = $cnt-1;
	}
	$ref = $refs[$z];												
	$ref= trim($ref);												// Удаляем пробелы (или другие символы) из начала и конца строки
	$part=explode(".", $ref);
	if (isset ($bg_bibrefs_url[$part[0]])) $book = $bg_bibrefs_url[$part[0]];	// Обозначение книги
	else return "";
	$book = $bg_bibrefs_shortTitle[$book];
	if (!$book) return "";											// Если нет такой книги, то возвращаем пустое значение

	return $book.$part[1];
}
/*******************************************************************************
   Преобразования заголовков Чтений Святого Писания в плагине "Православный календарь"
   Вызывается функцией showDayInfo() - файл days.php плагина Bg Orthodox Calendar
*******************************************************************************/  
function bg_bibrefs_convertTitles($q, $type) {
	if ($type != 'on' && $type != '' && $type != 'quote') {
		$q = preg_replace("/;/u", '<br>', $q);		// Если выводится текст Библии, то замена точки с запятой на перевод строки
	// Раздел <h3> (Чтение Апостола и Евангелие - по умолчанию)
		$q = preg_replace("/<em><strong>Евангелие и Апостол:<\/strong><\/em><br>/u", '<h3>'.__( 'Gospel and Apostolic readings', 'bg_bibrefs' ).'</h3>', $q);		
		$q = preg_replace("/<em><strong>Псалтирь:<\/strong><\/em><br>/u", '<h3>'.__( 'Reading of the Psalms', 'bg_bibrefs' ).'</h3>', $q);		
	// Названия служб <h4>
		$q = preg_replace("/<em> На утр.: - <\/em>/u", '<h4>'. __( 'At Matins', 'bg_bibrefs' ) .'</h4>', $q);		
		$q = preg_replace("/<em> На лит.: - <\/em>/u", '<h4>'. __( 'At Liturgy', 'bg_bibrefs' ) .'</h4>', $q);		
		$q = preg_replace("/<em> На веч.: - <\/em>/u", '<h4>'. __( 'At Vespers', 'bg_bibrefs' ) .'</h4>', $q);		
		$q = preg_replace("/<em> На 1-м часе: - <\/em>/u", '<h4>'. __( 'At the 1st hour', 'bg_bibrefs' ) .'</h4>', $q);		
		$q = preg_replace("/<em> На 3-м часе: - <\/em>/u", '<h4>'. __( 'At the 3rd hour', 'bg_bibrefs' ) .'</h4>', $q);		
		$q = preg_replace("/<em> На 6-м часе: - <\/em>/u", '<h4>'. __( 'At the 6th hour', 'bg_bibrefs' ) .'</h4>', $q);		
		$q = preg_replace("/<em> На 9-м часе: - <\/em>/u", '<h4>'. __( 'At the 9th hour', 'bg_bibrefs' ) .'</h4>', $q);		
	// Подзаголовки служб <h5>
		$q = preg_replace("/<em> Ап.: <\/em>/u", '<h5>'. __( 'Apostol', 'bg_bibrefs' ) .'</h5>', $q);		
		$q = preg_replace("/<em> Ев.: <\/em>/u", '<h5>'. __( 'Gospel', 'bg_bibrefs' ) .'</h5>', $q);		
	// Комментарии
		$q = preg_replace("/Праздник:/u", __( 'Reading on holiday:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/Ряд.: /u", __( 'Serial reading:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/Под зач.: /u", __( 'Reading after pericope:', 'bg_bibrefs' ), $q);		
	}
	else {	// Просто обеспечиваем многоязычность
	// Раздел <h3> (Чтение Апостола и Евангелие - по умолчанию)
		$q = preg_replace("/Евангелие и Апостол:/u", __( 'Gospel and Apostol:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/Псалтирь:/u", __( 'Psalms:', 'bg_bibrefs' ), $q);		
	// Названия служб <h4>
		$q = preg_replace("/На утр.:/u", __( 'At Mat.:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/На лит.:/u", __( 'At Lit.:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/На веч.:/u", __( 'At Ves.:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/На 1-м часе:/u", __( 'At 1st hour:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/На 3-м часе:/u", __( 'At 3rd hour:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/На 6-м часе:/u", __( 'At 6th hour:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/На 9-м часе:/u", __( 'At 9th hour:', 'bg_bibrefs' ), $q);		
	// Подзаголовки служб <h5>
		$q = preg_replace("/Ап.:/u", __( 'Ap.:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/Ев.:/u", __( 'Gos.:', 'bg_bibrefs' ), $q);		
	// Комментарии
		$q = preg_replace("/Праздник:/u", __( 'Holiday:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/Ряд.: /u", __( 'Ser.:', 'bg_bibrefs' ), $q);		
		$q = preg_replace("/Под зач.: /u", __( 'After per.:', 'bg_bibrefs' ), $q);		
	}
	$q = bg_bibrefs_bible_proc($q, $type);
	return $q;
}