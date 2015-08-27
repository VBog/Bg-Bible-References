<?php
/*******************************************************************************
   Формирование результатов поиска
   Вызывает bg_bibfers_printVerses() - см. ниже
*******************************************************************************/  
function bg_bibfers_search_result($context, $type, $lang, $prll='') {
	global $bg_bibfers_option;
	global $bg_bibfers_chapter, $bg_bibfers_ch;
	global $bg_bibfers_url, $bg_bibfers_bookTitle, $bg_bibfers_shortTitle, $bg_bibfers_bookFile;
	$lang = include_books($lang);
	bg_bibfers_get_options ();
	$verses = "";
	$bkr = "";

/*******************************************************************************
   Построение паттерна

*******************************************************************************/  
//	echo "context=". $context. "<br>";					// Отладка
	$pattern = trim($context);									// убираем пробелы по краям
	$pattern  = preg_replace("/\s+/ui", ' ', $pattern);			// удаляем двойные пробелы
	
	$pattern  = preg_replace("/\\\/ui", '\\', $pattern);		// переобразуем спецсимвол в обычный \
	$pattern  = preg_replace("/\//ui", '\/', $pattern);			// переобразуем спецсимвол в обычный /
	$pattern  = preg_replace("/\^/ui", '\^', $pattern);			// переобразуем спецсимвол в обычный ^
	$pattern  = preg_replace("/\?/ui", '\?', $pattern);			// переобразуем спецсимвол в обычный ?
	$pattern  = preg_replace("/\./ui", '\.', $pattern);			// переобразуем спецсимвол в обычный .
	$pattern  = preg_replace("/\+/ui", '\+', $pattern);			// переобразуем спецсимвол в обычный +
	$pattern  = preg_replace("/\{/ui", '\{', $pattern);			// переобразуем спецсимвол в обычный {
	$pattern  = preg_replace("/\}/ui", '\}', $pattern);			// переобразуем спецсимвол в обычный }
	$pattern  = preg_replace("/\(/ui", '\(', $pattern);			// переобразуем спецсимвол в обычный (
	$pattern  = preg_replace("/\)/ui", '\)', $pattern);			// переобразуем спецсимвол в обычный )
	$pattern  = preg_replace("/\[/ui", '\[', $pattern);			// переобразуем спецсимвол в обычный [
	$pattern  = preg_replace("/\]/ui", '\]', $pattern);			// переобразуем спецсимвол в обычный ]
	
	$pattern  = preg_replace("/\\$/ui", '\w',  $pattern);		// $ - строго 1 любая буква
	$pattern  = preg_replace("/\%/ui", '\w?',  $pattern);		// % - 0 или 1 любая буква
	$pattern  = preg_replace("/\*/ui", '\w*',  $pattern);		// * - 0 или несколько любых букв
	
	$pattern  = preg_replace("/\s/ui", '\s*', $pattern);		// пробельные символы в тексте Библии могут быть любыми
	$pattern = "/\b".$pattern."\b/ui";			// Только целое слово
	
//	echo "pattern=". $pattern. "<br>";					// Отладка

/*******************************************************************************
   Организуем просмотр всех книг Библии

*******************************************************************************/  
	foreach ($bg_bibfers_bookFile as $book => $book_file) {
		$book_file = 'bible/'.$book_file;						// Имя файла книги
/*******************************************************************************
   Чтение и преобразование файла книги
   
*******************************************************************************/  
	// Получаем данные из файла	
		$code = false;
		if ($bg_bibfers_option['fgc'] == 'on' && function_exists('file_get_contents')) {		// Попытка1. Если данные не получены попробуем применить file_get_contents()
			$url = dirname(dirname(__FILE__ )).'/'.$book_file;										// Локальный URL файла
			$code = file_get_contents($url);		
		}

		if ($bg_bibfers_option['fopen'] == 'on' && !$code) {									// Попытка 2. Если данные опять не получены попробуем применить fopen() 
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
		if ($bg_bibfers_option['curl'] == 'on' && function_exists('curl_init') && !$code) {		// Попытка3. Если установлен cURL				
			$url = plugins_url( $book_file , dirname(__FILE__ ) );									// URL файла
			$ch = curl_init($url);																	// создание нового ресурса cURL
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);											// возврат результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер
			$code = curl_exec($ch);																	// загрузка текста
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);										
			if ($httpCode != '200') $code = false;													// Проверка на код http 200
			curl_close($ch);																		// завершение сеанса и освобождение ресурсов
		} 

		if (!$code) return "";																	// Увы. Паранойя хостера достигла апогея. Файл не прочитан или ошибка

// Преобразовать json в массив
		$json = json_decode($code, true);															
/*******************************************************************************
   Поиск вхождения в текст стиха Библии
   и формирование результатов поиска
*******************************************************************************/  
		$cn_json = count($json);
		$chr = 0;
		for ($i=0; $i < $cn_json; $i++) {

			if (!preg_match ( $pattern, $json[$i]['text'] )) continue;		// Если нет вхождений ищем в следующем стихе

			if ($bkr != $book) {
				if ($type == "book") $verses = $verses."<h3>".bg_bibfers_getTitle($book)."</h3>";
				else if ($type == "t_verses") $verses = $verses."<strong>".bg_bibfers_getTitle($book)."</strong><br>";
				$bkr = $book;
			}
			$ch = (int)$json[$i]['part'];
			$vr = (int)$json[$i]['stix'];
			$verses = $verses.bg_bibfers_printVerses ($json, $book, $chr, $ch, $ch, $vr, $vr, $type, $lang, $prll);
			$chr = $ch;
			
		}
	}
	$verses  = preg_replace($pattern, '<strong class="search-excerpt">\0</strong>',  $verses);
	if (!$verses) $verses = '<p>&laquo;<strong class="search-excerpt">'.$context.'</strong>&raquo; &mdash; '.__( 'sorry, but nothing matched your search terms. Please try again with some different keywords.', 'bg_bibfers' ).'</p>';
	return $verses;
}
