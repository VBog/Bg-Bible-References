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

/******************************************************************************************
	Основная функция разбора текста и формирования ссылок,
    для работы требуется bg_bibfers_get_url() - см. ниже
*******************************************************************************************/
function bg_bibfers_bible_proc($txt) {
// Ищем все вхождения ссылок на Библию
//	$template = "/[\\(\\[](см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)]/ui";
//	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)\\.]?/ui";
	$template = "/(\\s|&nbsp\\;)?\\(?\\[?((\\s|&nbsp\\;)*см\\.?\\:?(\\s|&nbsp\\;)*)?(\\d?(\\s|&nbsp\\;)*[А-яA-z]{2,8})((\\.|\\s|&nbsp\\;)*)(\\d+((\\s|&nbsp\\;)*[\\:\\,\\.\\-—–](\\s|&nbsp\\;)*\\d+)*)(\\s|&nbsp\\;)*[\\]\\)\\.]?/ui";
	preg_match_all($template, $txt, $matches);
	
	$cnt = count($matches[0]);
	if ($cnt > 0) {
		for ($i = 0; $i < $cnt; $i++) {
		// Проверим по каждому паттерну. 
			preg_match($template, $matches[0][$i], $mt);
			$cn = count($mt);
			if ($cn > 0) {
				$title = preg_replace("/\\s|&nbsp\\;/u", '',$mt[5]); 			// Убираем пробельные символы, включая пробел, табуляцию, переводы строки 
				$chapter = preg_replace("/\\s|&nbsp\\;/u", '', $mt[9]);			// и другие юникодные пробельные символы, а также неразрывные пробелы &nbsp;
				$chapter = preg_replace("/—|–/u", '-', $chapter);				// Замена разных вариантов тире на обычный
				preg_match("/[\\:\\,\\.\\-]/u", $chapter, $mtchs);
				if (strcasecmp($mtchs[0], ',') == 0 || strcasecmp($mtchs[0], '.') == 0) {
						$chapter = preg_replace("/\,/u", ':', $chapter, 1);		// Первое число всегда номер главы. Если глава отделена запятой, заменяем ее на двоеточие.
						$chapter = preg_replace("/\./u", ':', $chapter, 1);		// Первое число всегда номер главы. Если глава отделена точкой, заменяем ее на двоеточие.
				}
				$addr = bg_bibfers_get_url($title, $chapter);
				if (strcasecmp($addr, "") != 0) {
					$ref = trim ( $matches[0][$i], "\x20\f\t\v\n\r\xA0\xC2" );
					$newmt = $addr .$ref. "</a>";
					$txt = str_replace($ref, $newmt, $txt);
				}			
			}
		}
	}
	return $txt;
}

/******************************************************************************************
	Формирование ссылки на http://azbyka.ru/biblia/
	Используется в функции bg_bibfers_bible_proc(),
	для работы требуется bg_bibfers_getЕшеду() - см. ниже
*******************************************************************************************/
function bg_bibfers_get_url($title, $chapter) {
	$url = array(						// Книги Священного Писания
		// Ветхий Завет
		// Пятикнижие Моисея															
		// translators: abbr. Genesis
		'Gen', 		__('Gen', 'bg_bibfers'), 'Gen', 'Быт', 								
		// translators: abbr. Exodus
		'Ex', 		__('Ex', 'bg_bibfers'), 'Ex', 'Исх',  								
		// translators: abbr. Leviticus
		'Lev', 		__('Lev', 'bg_bibfers'), 'Lev', 'Лев',  							
		// translators: abbr. Numbers
		'Num', 		__('Num', 'bg_bibfers'), 'Num', 'Числ|Чис',  						
		// translators: abbr. Deuteronomy
		'Deut', 	__('Deut', 'bg_bibfers'), 'Deut', 'Втор', 							
		// «Пророки» (Невиим) 
		// translators: abbr. Joshua (Iesous)
		'Nav', 		__('Nav', 'bg_bibfers'), 'Nav', 'Нав|ИсНав', 						
		// translators: abbr. Judges
		'Judg', 	__('Judg', 'bg_bibfers'), 'Judg', 'Судей|Суд', 						
		// translators: abbr. Ruth
		'Rth', 		__('Rth', 'bg_bibfers'), 'Rth', 'Руфь|Руф', 						
		// translators: abbr. 1 Samuel (1 Kingdoms)
		'1Sam', 	__('1Sam', 'bg_bibfers'), '1Sam', '1Цар|1Сам',						
		// translators: abbr. 2 Samuel (2 Kingdoms)
		'2Sam', 	__('2Sam', 'bg_bibfers'), '2Sam', '2Цар|2Сам',						
		// translators: abbr. 1 Kings (3 Kingdoms)
		'1King', 	__('1King', 'bg_bibfers'), '1King', '3Цар|1Царей', 					
		// translators: abbr. 2 Kings (4 Kingdoms)
		'2King', 	__('2King', 'bg_bibfers'), '2King', '4Цар|2Царей', 					
		// translators: abbr. 1 Chronicles (1 Paralipomenon)
		'1Chron', 	__('1Chron', 'bg_bibfers'), '1Chron', '1Пар|1Хр|1Хрон|1Лет',		
		// translators: abbr. 2 Chronicles (2 Paralipomenon)
		'2Chron', 	__('2Chron', 'bg_bibfers'), '2Chron', '2Пар|2Хр|2Хрон|2Лет',		
		// translators: abbr. 1 Esdras
		'Ezr', 		__('Ezr', 'bg_bibfers'), 'Ezr', '1Ездр|1Езд|Ездр|Езд', 				
		// translators: abbr. Nehemiah (2 Esdras)
		'Nehem', 	__('Nehem', 'bg_bibfers'), 'Nehem', 'Неем', 						
		// translators: abbr. Esther
		'Est', 		__('Est', 'bg_bibfers'), 'Est', 'Есф|Эсф', 							
		// «Писания» (Ктувим)
		// translators: abbr. Job
		'Job', 		__('Job', 'bg_bibfers'), 'Job', 'Иов',								
		// translators: abbr. Psalms
		'Ps', 		__('Ps', 'bg_bibfers'), 'Ps', 'Псал|Пс', 							
		// translators: abbr. Proverbs
		'Prov', 	__('Prov', 'bg_bibfers'), 'Prov', 'Притчи|Притч|Прит', 					
		// translators: abbr. Ecclesiastes
		'Eccl', 	__('Eccl', 'bg_bibfers'), 'Eccl', 'Еккл', 							
		// translators: abbr. Song of Songs (Aisma Aismaton)
		'Song', 	__('Song', 'bg_bibfers'), 'Song', 'Песн',							
		// translators: abbr. Isaiah
		'Is', 		__('Is', 'bg_bibfers'), 'Is', 'Исаи|Ис', 							
		// translators: abbr. Jeremiah
		'Jer', 		__('Jer', 'bg_bibfers'), 'Jer', 'Иер',								
		// translators: abbr. Lamentations
		'Lam', 		__('Lam', 'bg_bibfers'), 'Lam', 'Плч|Плач', 						
		// translators: abbr. Ezekiel
		'Ezek', 	__('Ezek', 'bg_bibfers'), 'Ezek', 'Иез',							
		// translators: abbr. Daniel
		'Dan', 		__('Dan', 'bg_bibfers'), 'Dan', 'Дан', 								
		// Двенадцать малых пророков 
		// translators: abbr. Hosea
		'Hos', 		__('Hos', 'bg_bibfers'), 'Hos', 'Осии|Ос', 							
		// translators: abbr. Joel
		'Joel', 	__('Joel', 'bg_bibfers'), 'Joel', 'Иоиль|Иоил', 					
		// translators: abbr. Amos
		'Am', 		__('Am', 'bg_bibfers'), 'Am', 'Амос|Ам',							
		// translators: abbr. Obadiah
		'Avd', 		__('Avd', 'bg_bibfers'), 'Avd', 'Авд', 								
		// translators: abbr. Jonah
		'Jona', 	__('Jona', 'bg_bibfers'), 'Jona', 'Иона|Ион', 						
		// translators: abbr. Micah
		'Mic', 		__('Mic', 'bg_bibfers'), 'Mic', 'Мих', 								
		// translators: abbr. Nahum
		'Naum', 	__('Naum', 'bg_bibfers'), 'Naum', 'Наум',							
		// translators: abbr. Habakkuk
		'Habak', 	__('Habak', 'bg_bibfers'), 'Habak', 'Авв', 							
		// translators: abbr. Zephaniah
		'Sofon', 	__('Sofon', 'bg_bibfers'), 'Sofon', 'Соф', 							
		// translators: abbr. Haggai
		'Hag', 		__('Hag', 'bg_bibfers'), 'Hag', 'Агг|Аг', 							
		// translators: abbr. Zechariah
		'Zah', 		__('Zah', 'bg_bibfers'), 'Zah', 'Зах',								
		// translators: abbr. Malachi
		'Mal', 		__('Mal', 'bg_bibfers'), 'Mal', 'Мал',								
		// Второканонические книги
		// translators: abbr. 1 Maccabees
		'1Mac', 	__('1Mac', 'bg_bibfers'), '1Mac', '1Мак',							
		// translators: abbr. 2 Maccabees
		'2Mac', 	__('2Mac', 'bg_bibfers'), '2Mac', '2Мак', 							
		// translators: abbr. 3 Maccabees
		'3Mac', 	__('3Mac', 'bg_bibfers'), '3Mac', '3Мак', 							
		// translators: abbr. Baruch
		'Bar', 		__('Bar', 'bg_bibfers'), 'Bar', 'Вар', 								
		// translators: abbr. 2 Esdras
		'2Ezr', 	__('2Ezr', 'bg_bibfers'), '2Ezr', '2Ездр|2Езд',						
		// translators: abbr. 3 Esdras
		'3Ezr', 	__('3Ezr', 'bg_bibfers'), '3Ezr', '3Ездр|3Езд',						
		// translators: abbr. Judith
		'Judf', 	__('Judf', 'bg_bibfers'), 'Judf', 'Иудифь|Иудиф', 					
		// translators: abbr. Letter of Jeremiah
		'pJer', 	__('pJer', 'bg_bibfers'), 'pJer', 'ПослИер|Посл\\.Иер', 			
		// translators: abbr. Wisdom
		'Solom', 	__('Solom', 'bg_bibfers'), 'Solom', 'Прем|ПремСол', 				
		// translators: abbr. Sirach
		'Sir', 		__('Sir', 'bg_bibfers'), 'Sir', 'Сирах|Сир', 						
		// translators: abbr. Tobit (Tobias)
		'Tov', 		__('Tov', 'bg_bibfers'), 'Tov', 'Товит|Тов', 						
		// Новый Завет
		// Евангилие
		// translators: abbr. Matthew			
		'Mt', 		__('Mt', 'bg_bibfers'), 'Mt', 'Мф|Мт|Матф', 						
		// translators: abbr. Mark					
		'Mk', 		__('Mk', 'bg_bibfers'), 'Mk', 'Мк|Мр|Марк|Мар', 					
		// translators: abbr. Luke
		'Lk', 		__('Lk', 'bg_bibfers'), 'Lk', 'Лк|Луки|Лук',						
		// translators: abbr. John
		'Jn', 		__('Jn', 'bg_bibfers'), 'Jn', 'Ин|Иоан',							
		// Деяния и послания Апостолов
		// translators: abbr. Acts
		'Act', 		__('Act', 'bg_bibfers'), 'Act', 'Деяния|Деян', 						
		// translators: abbr. James
		'Jac', 		__('Jac', 'bg_bibfers'), 'Jac', 'Иаков|Иак', 								
		// translators: abbr. 1 Peter
		'1Pet', 	__('1Pet', 'bg_bibfers'), '1Pet', '1Петра|1Пет',					
		// translators: abbr. 2 Peter
		'2Pet',		__('2Pet', 'bg_bibfers'), '2Pet', '2Петра|2Пет',					
		// translators: abbr. 1 John
		'1Jn', 		__('1Jn', 'bg_bibfers'), '1Jn', '1Ин|1Иоан', 						
		// translators: abbr. 2 John
		'2Jn', 		__('2Jn', 'bg_bibfers'), '2Jn', '2Ин|2Иоан', 						
		// translators: abbr. 3 John
		'3Jn', 		__('3Jn', 'bg_bibfers'), '3Jn', '3Ин|3Иоан', 						
		// translators: abbr. Jude
		'Juda', 	__('Juda', 'bg_bibfers'), 'Juda', 'Иуда|Иуд', 							
		// Послания апостола Павла
		// translators: abbr. Romans
		'Rom', 		__('Rom', 'bg_bibfers'), 'Rom', 'Римл|Рим', 						
		// translators: abbr. 1 Corinthians
		'1Cor', 	__('1Cor', 'bg_bibfers'), '1Cor', '1Кор', 							
		// translators: abbr. 2 Corinthians
		'2Cor', 	__('2Cor', 'bg_bibfers'), '2Cor', '2Кор',							
		// translators: abbr. Galatians
		'Gal', 		__('Gal', 'bg_bibfers'), 'Gal', 'Гал', 								
		// translators: abbr. Ephesians
		'Eph', 		__('Eph', 'bg_bibfers'), 'Eph', 'Ефес|Еф', 							
		// translators: abbr. Philippians
		'Phil', 	__('Phil', 'bg_bibfers'), 'Phil', 'Флп|Филип|Фил',  				
		// translators: abbr. Colossians
		'Col', 		__('Col', 'bg_bibfers'), 'Col', 'Кол',								
		// translators: abbr. 1 Thessalonians
		'1Thes', 	__('1Thes', 'bg_bibfers'), '1Thes', '1Сол|1Фес', 					
		// translators: abbr. 2 Thessalonians
		'2Thes', 	__('2Thes', 'bg_bibfers'), '2Thes', '2Сол|2Фес', 					
		// translators: abbr. 1 Timothy
		'1Tim', 	__('1Tim', 'bg_bibfers'), '1Tim', '1Тим', 							
		// translators: abbr. 2 Timothy
		'2Tim', 	__('2Tim', 'bg_bibfers'), '2Tim', '2Тим',							
		// translators: abbr. Titus
		'Tit', 		__('Tit', 'bg_bibfers'), 'Tit', 'Тит', 								
		// translators: abbr. Philemon
		'Phlm', 	__('Phlm', 'bg_bibfers'), 'Phlm', 'Флм|Филим', 						
		// translators: abbr. Hebrews
		'Hebr', 	__('Hebr', 'bg_bibfers'), 'Hebr', 'Евр', 							
		// translators: abbr. Revelation
		'Apok', 	__('Apok', 'bg_bibfers'), 'Apok', 'Откр|Отк|Апок');					
		
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
	
	$cn_url = count($url) / 2;
	for ($i=0; $i < $cn_url; $i++) {														// Просматриваем всю таблицу соответствия сокращений наименований книг
		$regvar = "/".$url[$i*2+1]."|".$url[$i*2]."/iu";									// Формируем регулярное выражение для поиска обозначения, включая латинское наименование
		preg_match_all($regvar, $title, $mts);												// Ищем все вхождения указанного наименования
		$cnt = count($mts[0]);
		for ($k=0; $k < $cnt; $k++) {														// Из всех вхождений находим точное соответствие указанному наименованию
			if (strcasecmp($mts[0][$k],  $title) == 0) {						
				$fullurl = "http://azbyka.ru/biblia/?".$url[$i*2].".". $chapter;			// Полный адрес ссылки на azbyka.ru
				$the_title ="" ;	// Название книги 	
				if ($bg_verses_val == 'on') {												// Текст  стихов
					$ajax_url = "title=".$url[$i*2]."&chapter=".$chapter."&type=t_verses";
				} else {
				// translators: ch. - is abbr. "chapter"
					$the_title =  "<strong>".bg_bibfers_getTitle($url[$i*2])."</strong><br>".(__('ch. ', 'bg_bibfers' ))." ".$chapter;		// Название книги, номера глав и стихов						
					$ajax_url = "";
				}
				return "<a href='".$fullurl.$opt."' class='bg_data_title ".$class_val."' target='".$target_val."' data-title='".$ajax_url."'><span class='bg_data_tooltip'>".$the_title."</span>"; 
			}
		}
	}
	return "";
}
/*******************************************************************************
   Полное наименование к4ниги Библии
   Используется в функции bg_bibfers_get_url()
*******************************************************************************/  

function bg_bibfers_getTitle($book) {
	$bookTitle = array(						// Полные названия Книг Священного Писания
		// Ветхий Завет
		// Пятикнижие Моисея
		'Gen' 		=>__('Genesis', 'bg_bibfers' ),							//'Книга Бытия', 
		'Ex' 		=>__('Exodus', 'bg_bibfers' ),							//'Книга Исход', 
		'Lev' 		=>__('Leviticus', 'bg_bibfers' ),							//'Книга Левит', 
		'Num' 		=>__('Numbers', 'bg_bibfers' ),							//'Книга Числа', 
		'Deut' 		=>__('Deuteronomy', 'bg_bibfers' ),						//'Второзаконие',
		// «Пророки» (Невиим) 
		'Nav' 		=>__('Joshua (Iesous)', 'bg_bibfers' ),					//'Книга Иисуса Навина',
		'Judg'		=>__('Judges', 'bg_bibfers' ),							//'Книга Судей Израилевых', 
		'Rth' 		=>__('Ruth', 'bg_bibfers' ),							//'Книга Руфь',
		'1Sam' 		=>__('1 Samuel (1 Kingdoms)', 'bg_bibfers' ),			//'Первая книга Царств (Первая книга Самуила)', 
		'2Sam' 		=>__('2 Samuel (2 Kingdoms)', 'bg_bibfers' ),			//'Вторая книга Царств (Вторая книга Самуила)', 
		'1King' 	=>__('1 Kings (3 Kingdoms)', 'bg_bibfers' ),			//'Третья книга Царств (Первая книга Царей)', 
		'2King' 	=>__('2 Kings (4 Kingdoms)', 'bg_bibfers' ),			//'Четвёртая книга Царств (Вторая книга Царей)',
		'1Chron' 	=>__('1 Chronicles (1 Paralipomenon)', 'bg_bibfers' ),	//'Первая книга Паралипоменон (Первая книга Хроник, Первая Летопись)', 
		'2Chron' 	=>__('2 Chronicles (2 Paralipomenon)', 'bg_bibfers' ),	//'Вторая книга Паралипоменон (Вторая книга Хроник, Вторая Летопись)', 
		'Ezr' 		=>__('1 Esdras', 'bg_bibfers' ),						//'Книга Ездры (Первая книга Ездры)', 
		'Nehem' 	=>__('Nehemiah (2 Esdras)', 'bg_bibfers' ),				//'Книга Неемии', 
		'Est' 		=>__('Esther', 'bg_bibfers' ),							//'Книга Есфири',  
		// «Писания» (Ктувим)
		'Job' 		=>__('Job', 'bg_bibfers' ),								//'Книга Иова',
		'Ps' 		=>__('Psalms', 'bg_bibfers' ),							//'Псалтирь', 
		'Prov' 		=>__('Proverbs', 'bg_bibfers' ),						//'Книга Притчей Соломоновых', 
		'Eccl' 		=>__('Ecclesiastes', 'bg_bibfers' ),					//'Книга Екклезиаста, или Проповедника', 
		'Song' 		=>__('Song of Songs (Aisma Aismaton)', 'bg_bibfers' ),	//'Песнь песней Соломона',

		'Is' 		=>__('Isaiah', 'bg_bibfers' ),							//'Книга пророка Исайи', 
		'Jer' 		=>__('Jeremiah', 'bg_bibfers' ),						//'Книга пророка Иеремии',
		'Lam' 		=>__('Lamentations', 'bg_bibfers' ),					//'Книга Плач Иеремии', 
		'Ezek'	 	=>__('Ezekiel', 'bg_bibfers' ),							//'Книга пророка Иезекииля',
		'Dan' 		=>__('Daniel', 'bg_bibfers' ),							//'Книга пророка Даниила', 
		// Двенадцать малых пророков 
		'Hos' 		=>__('Hosea', 'bg_bibfers' ),							//'Книга пророка Осии', 
		'Joel'	 	=>__('Joel', 'bg_bibfers' ),							//'Книга пророка Иоиля',
		'Am' 		=>__('Amos', 'bg_bibfers' ),							//'Книга пророка Амоса', 
		'Avd' 		=>__('Obadiah', 'bg_bibfers' ),							//'Книга пророка Авдия', 
		'Jona' 		=>__('Jonah', 'bg_bibfers' ),							//'Книга пророка Ионы',
		'Mic' 		=>__('Micah', 'bg_bibfers' ),							//'Книга пророка Михея', 
		'Naum' 		=>__('Nahum', 'bg_bibfers' ),							//'Книга пророка Наума',
		'Habak' 	=>__('Habakkuk', 'bg_bibfers' ),						//'Книга пророка Аввакума', 
		'Sofon' 	=>__('Zephaniah', 'bg_bibfers' ),						//'Книга пророка Софонии', 
		'Hag' 		=>__('Haggai', 'bg_bibfers' ),							//'Книга пророка Аггея', 
		'Zah' 		=>__('Zechariah', 'bg_bibfers' ),						//'Книга пророка Захарии',
		'Mal' 		=>__('Malachi', 'bg_bibfers' ),							//'Книга пророка Малахии',
		// Второканонические книги
		'1Mac' 		=>__('1 Maccabees', 'bg_bibfers' ),						//'Первая книга Маккавейская',
		'2Mac' 		=>__('2 Maccabees', 'bg_bibfers' ),						//'Вторая книга Маккавейская', 
		'3Mac' 		=>__('3 Maccabees', 'bg_bibfers' ),						//'Третья книга Маккавейская', 
		'Bar' 		=>__('Baruch', 'bg_bibfers' ),							//'Книга пророка Варуха', 
		'2Ezr' 		=>__('2 Esdras', 'bg_bibfers' ),						//'Вторая книга Ездры', 
		'3Ezr' 		=>__('3 Esdras', 'bg_bibfers' ),						//'Третья книга Ездры',
		'Judf' 		=>__('Judith', 'bg_bibfers' ),							//'Книга Иудифи', 
		'pJer' 		=>__('Letter of Jeremiah', 'bg_bibfers' ),				//'Послание Иеремии', 
		'Solom' 	=>__('Wisdom', 'bg_bibfers' ),							//'Книга Премудрости Соломона',
		'Sir' 		=>__('Sirach', 'bg_bibfers' ),							//'Книга Премудрости Иисуса, сына Сирахова', 
		'Tov' 		=>__('Tobit (Tobias)', 'bg_bibfers' ),					//'Книга Товита',
		// Новый Завет
		// Евангилие
		'Mt' 		=>__('Matthew', 'bg_bibfers' ),							//'Евангелие от Матфея',
		'Mk' 		=>__('Mark', 'bg_bibfers' ),							//'Евангелие от Марка', 
		'Lk' 		=>__('Luke', 'bg_bibfers' ),							//'Евангелие от Луки', 
		'Jn' 		=>__('John', 'bg_bibfers' ),							//'Евангелие от Иоанна', 
		// Деяния и послания Апостолов
		'Act' 		=>__('Acts', 'bg_bibfers' ),							//'Деяния святых Апостолов', 
		'Jac' 		=>__('James', 'bg_bibfers' ),							//'Послание Иакова', 
		'1Pet'	 	=>__('1 Peter', 'bg_bibfers' ),							//'Первое послание Петра', 
		'2Pet'	 	=>__('2 Peter', 'bg_bibfers' ),							//'Второе послание Петра',	
		'1Jn' 		=>__('1 John', 'bg_bibfers' ),							//'Первое послание Иоанна', 
		'2Jn' 		=>__('2 John', 'bg_bibfers' ),							//'Второе послание Иоанна', 
		'3Jn' 		=>__('3 John', 'bg_bibfers' ),							//'Третье послание Иоанна',
		'Juda'	 	=>__('Jude', 'bg_bibfers' ),							//'Послание Иуды', 
		// Послания апостола Павла
		'Rom' 		=>__('Romans', 'bg_bibfers' ),							//'Послание апостола Павла к Римлянам', 
		'1Cor' 		=>__('1 Corinthians', 'bg_bibfers' ),					//'Первое послание апостола Павла к Коринфянам', 
		'2Cor' 		=>__('2 Corinthians', 'bg_bibfers' ),					//'Второе послание апостола Павла к Коринфянам',
		'Gal'	 	=>__('Galatians', 'bg_bibfers' ),						//'Послание апостола Павла к Галатам', 
		'Eph' 		=>__('Ephesians', 'bg_bibfers' ),						//'Послание апостола Павла к Ефесянам', 
		'Phil' 		=>__('Philippians', 'bg_bibfers' ),						//'Послание апостола Павла к Филиппийцам', 
		'Col' 		=>__('Colossians', 'bg_bibfers' ),						//'Послание апостола Павла к Колоссянам',
		'1Thes' 	=>__('1 Thessalonians', 'bg_bibfers' ),					//'Первое послание апостола Павла к Фессалоникийцам (Солунянам)',
		'2Thes' 	=>__('2 Thessalonians', 'bg_bibfers' ),					//'Второе послание апостола Павла к Фессалоникийцам (Солунянам)',  
		'1Tim' 		=>__('1 Timothy', 'bg_bibfers' ),						//'Первое послание апостола Павла к Тимофею', 
		'2Tim'	 	=>__('2 Timothy', 'bg_bibfers' ),						//'Второе послание апостола Павла к Тимофею',
		'Tit' 		=>__('Titus', 'bg_bibfers' ),							//'Послание апостола Павла к Титу', 
		'Phlm'	 	=>__('Philemon', 'bg_bibfers' ),						//'Послание апостола Павла к Филимону', 
		'Hebr'	 	=>__('Hebrews', 'bg_bibfers' ),							//'Послание апостола Павла к Евреям', 
		'Apok' 		=>__('Revelation', 'bg_bibfers' ));						//'Откровение Иоанна Богослова (Апокалипсис)'

	// Возвражаем полный текст всплывающей подсказки
	return $bookTitle[$book];													// Полное наименование книги Библии
}


