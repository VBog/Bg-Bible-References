<?php
/*******************************************************************************
   Создание контента цитаты 
   Вызывает bg_bibfers_printVerses() - см. ниже
*******************************************************************************/  
function bg_bibfers_getQuotes($book, $chapter, $type, $lang) {

		$pat = array(						// Таблица соответствия azbyka.ru и patriarhia.ru
		// Ветхий Завет
		// Пятикнижие Моисея
		'Gen'	 	=>'gen',							//'Книга Бытия', 
		'Ex'	 	=>'ex',								//'Книга Исход', 
		'Lev'	 	=>'lev',							//'Книга Левит', 
		'Num'	 	=>'num',							//'Книга Числа', 
		'Deut'	 	=>'deu',							//'Второзаконие',
		// «Пророки» (Невиим) 
		'Nav'	 	=>'nav',							//'Книга Иисуса Навина',
		'Judg'		=>'sud',							//'Книга Судей Израилевых', 
		'Rth'	 	=>'ruf',							//'Книга Руфь',
		'1Sam'	 	=>'king1',							//'Первая книга Царств (Первая книга Самуила)', 
		'2Sam'	 	=>'king2',							//'Вторая книга Царств (Вторая книга Самуила)', 
		'1King' 	=>'king3',							//'Третья книга Царств (Первая книга Царей)', 
		'2King' 	=>'king4',							//'Четвёртая книга Царств (Вторая книга Царей)',
		'1Chron' 	=>'para1',							//'Первая книга Паралипоменон (Первая книга Хроник, Первая Летопись)', 
		'2Chron' 	=>'para2',							//'Вторая книга Паралипоменон (Вторая книга Хроник, Вторая Летопись)', 
		'Ezr'	 	=>'ezr1',							//'Книга Ездры (Первая книга Ездры)', 
		'Nehem' 	=>'nee',							//'Книга Неемии', 
		'Est'	 	=>'esf',							//'Книга Есфири',  
		// «Писания» (Ктувим)
		'Job'	 	=>'iov',							//'Книга Иова',
		'Ps' 		=>'ps',								//'Псалтирь', 
		'Prov'	 	=>'prov',							//'Книга Притчей Соломоновых', 
		'Eccl'	 	=>'eccl',							//'Книга Екклезиаста, или Проповедника', 
		'Song'	 	=>'song',							//'Песнь песней Соломона',

		'Is' 		=>'isa',							//'Книга пророка Исайи', 
		'Jer' 		=>'jer',							//'Книга пророка Иеремии',
		'Lam' 		=>'lam',							//'Книга Плач Иеремии', 
		'Ezek'	 	=>'eze',							//'Книга пророка Иезекииля',
		'Dan' 		=>'dan',							//'Книга пророка Даниила', 
		// Двенадцать малых пророков 
		'Hos' 		=>'hos',							//'Книга пророка Осии', 
		'Joel'	 	=>'joe',							//'Книга пророка Иоиля',
		'Am' 		=>'am',								//'Книга пророка Амоса', 
		'Avd' 		=>'avd',							//'Книга пророка Авдия', 
		'Jona'	 	=>'jona',							//'Книга пророка Ионы',
		'Mic' 		=>'mih',							//'Книга пророка Михея', 
		'Naum' 		=>'nau',							//'Книга пророка Наума',
		'Habak' 	=>'avv',							//'Книга пророка Аввакума', 
		'Sofon' 	=>'sof',							//'Книга пророка Софонии', 
		'Hag' 		=>'agg',							//'Книга пророка Аггея', 
		'Zah' 		=>'zah',							//'Книга пророка Захарии',
		'Mal' 		=>'mal',							//'Книга пророка Малахии',
		// Второканонические книги
		'1Mac'	 	=>'mak1',							//'Первая книга Маккавейская',
		'2Mac'	 	=>'mak2',							//'Вторая книга Маккавейская', 
		'3Mac'	 	=>'mak3',							//'Третья книга Маккавейская', 
		'Bar' 		=>'varuh',							//'Книга пророка Варуха', 
		'2Ezr' 		=>'ezr2',							//'Вторая книга Ездры', 
		'3Ezr' 		=>'ezr3',							//'Третья книга Ездры',
		'Judf' 		=>'jdi',							//'Книга Иудифи', 
		'pJer' 		=>'posjer',							//'Послание Иеремии', 
		'Solom' 	=>'prem',							//'Книга Премудрости Соломона',
		'Sir' 		=>'sir',							//'Книга Премудрости Иисуса, сына Сирахова', 
		'Tov' 		=>'tov',							//'Книга Товита',
		// Новый Завет
		// Евангилие
		'Mt' 		=>'mf',								//'Евангелие от Матфея',
		'Mk' 		=>'mk',								//'Евангелие от Марка', 
		'Lk' 		=>'lk',								//'Евангелие от Луки', 
		'Jn' 		=>'jn',								//'Евангелие от Иоанна', 
		// Деяния и послания Апостолов
		'Act' 		=>'act',							//'Деяния святых Апостолов', 
		'Jac'	 	=>'jak',							//'Послание Иакова', 
		'1Pet'	 	=>'pe1',							//'Первое послание Петра', 
		'2Pet'	 	=>'pe2',							//'Второе послание Петра',	
		'1Jn'	 	=>'jn1',							//'Первое послание Иоанна', 
		'2Jn'	 	=>'jn2',							//'Второе послание Иоанна', 
		'3Jn'	 	=>'jn3',							//'Третье послание Иоанна',
		'Juda'	 	=>'jud',							//'Послание Иуды', 
		// Послания апостола Павла
		'Rom' 		=>'rom',							//'Послание апостола Павла к Римлянам', 
		'1Cor'	 	=>'co1',							//'Первое послание апостола Павла к Коринфянам', 
		'2Cor'	 	=>'co2',							//'Второе послание апостола Павла к Коринфянам',
		'Gal' 		=>'gal',							//'Послание апостола Павла к Галатам', 
		'Eph' 		=>'eph',							//'Послание апостола Павла к Ефесянам', 
		'Phil'	 	=>'flp',							//'Послание апостола Павла к Филиппийцам', 
		'Col' 		=>'col',							//'Послание апостола Павла к Колоссянам',
		'1Thes' 	=>'fe1',							//'Первое послание апостола Павла к Фессалоникийцам (Солунянам)',
		'2Thes' 	=>'fe2',							//'Второе послание апостола Павла к Фессалоникийцам (Солунянам)',  
		'1Tim' 		=>'ti1',							//'Первое послание апостола Павла к Тимофею', 
		'2Tim' 		=>'ti2',							//'Второе послание апостола Павла к Тимофею',
		'Tit' 		=>'tit',							//'Послание апостола Павла к Титу', 
		'Phlm'	 	=>'flm',							//'Послание апостола Павла к Филимону', 
		'Hebr'	 	=>'heb',							//'Послание апостола Павла к Евреям', 
		'Apok'	 	=>'rev');							//'Откровение Иоанна Богослова (Апокалипсис)'

	global $bg_bibfers_url, $bg_bibfers_bookTitle, $bg_bibfers_shortTitle;
	include(dirname(dirname(__FILE__ )).'/bible/'.$lang.'/books.php');
/*******************************************************************************
   Преобразование обозначения книги из формата azbyka.ru в формат patriarhia.ru
   чтение и преобразование файла книги
*******************************************************************************/  
	if (!$book) return "";
	if (!$pat[$book]) return "";
	$book_file = 'bible/'.$lang."/".$pat[$book];		// Имя файла книги

    $bg_curl_val = get_option( 'bg_bibfers_curl' );
    $bg_fgc_val = get_option( 'bg_bibfers_fgc' );
    $bg_fopen_val = get_option( 'bg_bibfers_fopen' );

// Получаем данные из файла	
	$code = false;
	if ($bg_curl_val == 'on' && function_exists('curl_init'))	{							// Попытка1. Если установлен cURL				
		$url = plugins_url( $book_file , dirname(__FILE__ ) );									// URL файла
		$ch = curl_init($url);																	// создание нового ресурса cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);											// возврат результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер
		$code = curl_exec($ch);																	// загрузка текста
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);										
		if ($httpCode != '200') $code = false;													// Проверка на код http 200
		curl_close($ch);																		// завершение сеанса и освобождение ресурсов
	} 

	if ($bg_fgc_val == 'on' && !$code) {													// Попытка2. Если данные не получены попробуем применить file_get_contents()
		$url = dirname(dirname(__FILE__ )).$book_file;											// Локальный URL файла
		$code = file_get_contents($url);		
	}

	if ($bg_fopen_val == 'on' && !$code) {													// Попытка 3. Если данные опять не получены попробуем применить fopen() 
		$url = dirname(dirname(__FILE__ )).$book_file;											// Локальный URL файла
		$ch=fopen($url, "r" );																	// Открываем файл для чтения
		if($ch)
		{
			while (!feof($ch))	{
				$code .= fread($ch, 2097152);													// загрузка текста (не более 2097152 байт)
			}
			fclose($ch);																		// Закрываем файл
		}
	}
	if (!$code) return "";																	// Увы. Паранойя хостера достигла апогея. Файл не прочитан или ошибка

// Преобразовать json в массив
	$json = json_decode($code, true);															

	if ($type == "book") $verses = "<h3>".bg_bibfers_getTitle($book)."</h3>";
	else if ($type == "t_verses") $verses = "<strong>".bg_bibfers_getTitle($book)."</strong><br>";
	else $verses = "";
	if ($type <> "quote") $verses = $verses."<div>";	
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
					$verses = $verses.bg_bibfers_printVerses ($json, $book, $chr, $ch1, $ch2, $vr1, $vr2, $type);
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
			$verses = $verses.bg_bibfers_printVerses ($json, $book, $chr, $ch1, $ch2, $vr1, $vr2, $type);
			$chr = $ch2;
		}
		if ($sp == "") break;
	}
	if ($type <> "quote") $verses = $verses."</div>";	
	return $verses;
}
/*******************************************************************************
	Формирование содержания цитаты
	Вызывает bg_bibfers_optina() - см. ниже
*******************************************************************************/  
function bg_bibfers_printVerses ($json, $book, $chr, $ch1, $ch2, $vr1, $vr2, $type) {
    $bg_show_fn = get_option( 'bg_bibfers_show_fn' );
//    $bg_show_fn = 'on';
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
						$verses = $verses."<strong>".__('Chapter', 'bg_bibfers')." ".$ch."</strong><br>";					// Печатаем номер главы
						$chr = $ch;
					}
					if ($json[$i]['stix'] == 0) $pointer = "";
					else $pointer = "<em>".$json[$i]['stix'].$fn."</em> ";													// Только номер стиха
				} else if ($type == 'verses' || $type == 't_verses') { 														// Тип: стихи или стихи с названием книг
					if ($json[$i]['stix'] == 0) $pointer = "<em>".$json[$i]['part']."</em>   ";
					else $pointer = "<em>".$json[$i]['part'].":".$json[$i]['stix'].$fn."</em> ";							// Номер главы : номер стиха
				} else if ($type == 'b_verses') { 																			// Тип: стихи
					if ($json[$i]['stix'] == 0) $pointer = "<em>".$json[$i]['ru_book'].".".$json[$i]['part'].$fn."</em>   ";
					else $pointer = "<em>".$json[$i]['ru_book'].".".$json[$i]['part'].":".$json[$i]['stix'].$fn."</em> ";	// Книга. номер главы : номер стиха
				} else {																									// Тип: цитата
					$pointer = "";																							// Ничего
				}
				$txt = trim(strip_tags($json[$i]['text']));
				if ($txt) {
					if ($json[$i]['stix'] == 0) $txt = "<strong>".$pointer.$txt."</strong>";
					else  $txt = $pointer.bg_bibfers_optina($txt, $book, $ch, $vr);

					$verses = $verses.$txt;
					if ($type == 'quote') {$verses = $verses." ";}															// Если цитата, строку не переводим
					else {$verses = $verses."<br>";}
				} 
		}
	}
	return $verses;
}

/*******************************************************************************
	Создание ссылки на толкование Священного Писания на сайте Оптиной пустыни.
  
*******************************************************************************/  
function bg_bibfers_optina($txt, $book, $chapter, $verse) {

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
		'Eccl'	 	=>'old:elk:',		//'Книга Екклезиаста, или Проповедника', 
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

	$bg_interpret_val = get_option( 'bg_bibfers_interpret' );
	if ($bg_interpret_val != 'on') return $txt;
	$target_val = get_option( 'bg_bibfers_target' );
	$ch = str_pad($chapter, strcasecmp($book,'Ps')?2:3, "0", STR_PAD_LEFT);
	$vr = str_pad($verse, 2, "0", STR_PAD_LEFT);
	return ("<a href='http://bible.optina.ru/".$opt[$book].$ch.":".$vr."' title='".(__( 'Click to go to interpretation', 'bg_bibfers' ))."' target='".$target_val."'>".$txt."</a>");
}