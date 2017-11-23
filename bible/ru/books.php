<?php
	$bg_bibrefs_lang_name = 'Русский';
	$bg_bibrefs_book_lang = 'ru';
	$bg_bibrefs_chapter = 'Глава';
	$bg_bibrefs_ch = 'гл.';
	$bg_bibrefs_psalm = 'Псалом';
	$bg_bibrefs_ps = 'пс.';
	
	$bg_bibrefs_url = array(		// Допустимые обозначения книг Священного Писания
		// Ветхий Завет
		// Пятикнижие Моисея															
		'Gen'		=>'Gen',		//'Книга Бытия', 	
		'Бытие'		=>'Gen',					
		'Быт'		=>'Gen',					
		'Ex'		=>'Ex',	 		//'Книга Исход', 			
		'Исх'		=>'Ex',	 					
		'Lev'		=>'Lev', 		//'Книга Левит', 		
		'Левит'		=>'Lev', 							
		'Лев'		=>'Lev', 							
		'Лв'		=>'Lev', 							
		'Num'		=>'Num', 		//'Книга Числа',	
		'Числа'		=>'Num', 						
		'Числ'		=>'Num', 						
		'Чис'		=>'Num', 						
		'Чс'		=>'Num', 						
		'Deut'		=>'Deut',		//'Второзаконие',		
		'Втор'		=>'Deut',							
		// «Пророки» (Невиим) 
		'Nav'		=>'Nav', 		//'Книга Иисуса Навина',	
		'Нав'		=>'Nav', 						
		'ИсНав'		=>'Nav', 						
		'Judg'		=>'Judg', 		//'Книга Судей Израилевых', 	
		'Судей'		=>'Judg', 						
		'Суд'		=>'Judg', 						
		'Сд'		=>'Judg', 						
		'Rth'		=>'Rth',		//'Книга Руфь',	
		'Руфь'		=>'Rth',						
		'Руф'		=>'Rth',						
		'1Sam'		=>'1Sam',		//'Первая книга Царств (Первая книга Самуила)', 	
		'1Цар'		=>'1Sam',						
		'1Сам'		=>'1Sam',						
		'1См'		=>'1Sam',						
		'2Sam'		=>'2Sam',		//'Вторая книга Царств (Вторая книга Самуила)', 	
		'2Цар'		=>'2Sam',						
		'2Сам'		=>'2Sam',						
		'2См'		=>'2Sam',						
		'1King'		=>'1King', 		//'Третья книга Царств (Первая книга Царей)', 
		'3Цар'		=>'1King', 					
		'1Царей'	=>'1King', 					
		'2King'		=>'2King', 		//'Четвертая книга Царств (Вторая книга Царей)', 
		'4Цар'		=>'2King', 					
		'2Царей'	=>'2King', 					
		'1Chron'	=>'1Chron',		//'Первая книга Паралипоменон (Первая книга Хроник, Первая Летопись)', 
		'1Пар'		=>'1Chron',		
		'1Хрон'		=>'1Chron',		
		'1Хр'		=>'1Chron',		
		'1Лет'		=>'1Chron',		
		'2Chron'	=>'2Chron',		//'Вторая книга Паралипоменон (Вторая книга Хроник, Вторая Летопись)', 
		'2Пар'		=>'2Chron',		
		'2Хрон'		=>'2Chron',		
		'2Хр'		=>'2Chron',		
		'2Лет'		=>'2Chron',		
		'Ezr'		=>'Ezr', 		//'Книга Ездры (Первая книга Ездры)', 
		'1Ездр'		=>'Ezr', 				
		'1Езд'		=>'Ezr', 				
		'Ездр'		=>'Ezr', 				
		'Езд'		=>'Ezr', 				
		'Ез'		=>'Ezr', 				
		'Nehem'		=>'Nehem', 		//'Книга Неемии',	
		'Неем'		=>'Nehem', 						
		'Нм'		=>'Nehem', 						
		'Est'		=>'Est', 		//'Книга Есфири',  
		'Есф'		=>'Est', 							
		'Эсф'		=>'Est', 							
		// «Писания» (Ктувим)		
		'Job'		=>'Job', 		//'Книга Иова',			
		'Иов'		=>'Job', 								
		'Ps'		=>'Ps',			//'Псалтирь', 	
		'Псалт'		=>'Ps',							
		'Псал'		=>'Ps',							
		'Пс'		=>'Ps',							
		'Prov'		=>'Prov', 		//'Книга Притчей Соломоновых', 
		'Притчи'	=>'Prov', 					
		'Притч'		=>'Prov', 					
		'Прит'		=>'Prov', 					
		'Eccl'		=>'Eccl', 		//'Книга Екклезиаста, или Проповедника', 		
		'Еккл'		=>'Eccl', 							
		'Екк'		=>'Eccl', 							
		'Екл'		=>'Eccl', 							
		'Ек'		=>'Eccl', 							
		'Song'		=>'Song',		//'Песнь песней Соломона',		
		'Песня'		=>'Song',							
		'Песн'		=>'Song',							
		'Is'		=>'Is', 		//'Книга пророка Исайи',		
		'Исайи'		=>'Is', 							
		'Исаи'		=>'Is', 							
		'Ис'		=>'Is', 							
		'Jer'		=>'Jer',		//'Книга пророка Иеремии',			
		'Иер'		=>'Jer',								
		'Lam'		=>'Lam', 		//'Книга Плач Иеремии', 	
		'Плч'		=>'Lam', 						
		'Плач'		=>'Lam', 						
		'Ezek'		=>'Ezek',		//'Книга пророка Иезекииля',		
		'Иез'		=>'Ezek',							
		'Dan'		=>'Dan', 		//'Книга пророка Даниила',			
		'Дан'		=>'Dan', 								
		'Днл'		=>'Dan', 								
		// Двенадцать малых пророков 
		'Hos'		=>'Hos',  		//'Книга пророка Осии', 		
		'Осии'		=>'Hos',  							
		'Ос'		=>'Hos',  							
		'Joel'		=>'Joel', 		//'Книга пророка Иоиля',
		'Иоиль'		=>'Joel', 					
		'Иоил'		=>'Joel', 					
		'Am'		=>'Am',			//'Книга пророка Амоса',	
		'Амос'		=>'Am',							
		'Амс'		=>'Am',							
		'Ам'		=>'Am',							
		'Avd'		=>'Avd', 		//'Книга пророка Авдия',			
		'Авд'		=>'Avd', 								
		'Jona'		=>'Jona', 		//'Книга пророка Ионы',	
		'Иона'		=>'Jona', 						
		'Ион'		=>'Jona', 						
		'Mic'		=>'Mic', 		//'Книга пророка Михея',			
		'Михей'		=>'Mic', 								
		'Мих'		=>'Mic', 								
		'Мх'		=>'Mic', 								
		'Naum'		=>'Naum', 		//'Книга пророка Наума',		
		'Наум'		=>'Naum', 							
		'Habak'		=>'Habak', 		//'Книга пророка Аввакума',		
		'Аввак'		=>'Habak', 							
		'Авв'		=>'Habak', 							
		'Ав'		=>'Habak', 							
		'Sofon'		=>'Sofon', 		//'Книга пророка Софонии',					
		'Софон'		=>'Sofon', 							
		'Соф'		=>'Sofon', 							
		'Hag'		=>'Hag', 		//'Книга пророка Аггея',					
		'Агг'		=>'Hag', 							
		'Аг'		=>'Hag', 							
		'Zah'		=>'Zah',		//'Книга пророка Захарии',						
		'Захар'		=>'Zah',								
		'Зах'		=>'Zah',								
		'Зхр'		=>'Zah',								
		'Mal'		=>'Mal',		//'Книга пророка Малахии',						
		'Малах'		=>'Mal',								
		'Мал'		=>'Mal',								
		// Второканонические книги
		'1Mac'		=>'1Mac',		//'Первая книга Маккавейская',					
		'1Мак'		=>'1Mac',							
		'2Mac'		=>'2Mac', 		//'Вторая книга Маккавейская',					
		'2Мак'		=>'2Mac', 							
		'3Mac'		=>'3Mac', 		//'Третья книга Маккавейская',					
		'3Мак'		=>'3Mac', 							
		'Bar'		=>'Bar', 		//'Книга пророка Варуха',						
		'Варух'		=>'Bar', 								
		'Вар'		=>'Bar', 								
		'2Ezr'		=>'2Ezr',		//'Вторая книга Ездры', 				
		'2Ездр'		=>'2Ezr',						
		'2Езд'		=>'2Ezr',						
		'3Ezr'		=>'3Ezr',		//'Третья книга Ездры',				
		'3Ездр'		=>'3Ezr',						
		'3Езд'		=>'3Ezr',						
		'Judf'		=>'Judf', 		//'Книга Иудифи',			
		'Иудифь'	=>'Judf', 					
		'Иудиф'		=>'Judf', 					
		'pJer'		=>'pJer', 		//'Послание Иеремии',	
		'ПослИер'	=>'pJer', 			
		'Solom'		=>'Solom', 		//'Книга Премудрости Соломона',		
		'Прем'		=>'Solom', 				
		'ПремСол'	=>'Solom', 				
		'Sir'		=>'Sir', 		//'Книга Премудрости Иисуса, сына Сирахова', 				
		'Сирах'		=>'Sir', 						
		'Сир'		=>'Sir', 						
		'Tov'		=>'Tov', 		//'Книга Товита',				
		'Товит'		=>'Tov', 						
		'Тов'		=>'Tov', 						
		// Новый Завет
		// Евангилие
		'Mt'		=>'Mt', 		//'Евангелие от Матфея',				
		'Мф'		=>'Mt', 						
		'Мт'		=>'Mt', 						
		'Матфея'	=>'Mt', 						
		'Матф'		=>'Mt', 						
		'Mk'		=>'Mk', 		//'Евангелие от Марка',			
		'Марка'		=>'Mk', 					
		'Марк'		=>'Mk', 					
		'Мар'		=>'Mk', 					
		'Мр'		=>'Mk', 					
		'Мк'		=>'Mk', 					
		'Lk'		=>'Lk',			//'Евангелие от Луки',			
		'Луки'		=>'Lk',						
		'Лук'		=>'Lk',						
		'Лк'		=>'Lk',						
		'Jn'		=>'Jn',			//'Евангелие от Иоанна',				
		'Иоанна'	=>'Jn',							
		'Иоан'		=>'Jn',							
		'Ин'		=>'Jn',							
		// Деяния и послания Апостолов
		'Act'		=>'Act', 		//'Деяния святых Апостолов',				
		'Деяния'	=>'Act', 						
		'Деян'		=>'Act', 						
		'Jac'		=>'Jac', 		//'Послание Иакова',						
		'Иакова'	=>'Jac', 								
		'Иаков'		=>'Jac', 								
		'Иак'		=>'Jac', 								
		'1Pet'		=>'1Pet',		//'Первое послание Петра', 			
		'1Петра'	=>'1Pet',					
		'1Петр'		=>'1Pet',					
		'1Пет'		=>'1Pet',					
		'2Pet'		=>'2Pet',		//'Второе послание Петра',			
		'2Петра'	=>'2Pet',					
		'2Петр'		=>'2Pet',					
		'2Пет'		=>'2Pet',					
		'1Jn'		=>'1Jn', 		//'Первое послание Иоанна'				
		'1Иоанна'	=>'1Jn', 						
		'1Иоан'		=>'1Jn', 						
		'1Ин'		=>'1Jn', 						
		'2Jn'		=>'2Jn', 		//'Второе послание Иоанна',				
		'2Иоанна'	=>'2Jn', 						
		'2Иоан'		=>'2Jn', 						
		'2Ин'		=>'2Jn', 						
		'3Jn'		=>'3Jn', 		//'Третье послание Иоанна',				
		'3Иоанна'	=>'3Jn', 						
		'3Иоан'		=>'3Jn', 						
		'3Ин'		=>'3Jn', 						
		'Juda'		=>'Juda', 		//'Послание Иуды',					
		'Иуды'		=>'Juda', 							
		'Иуда'		=>'Juda', 							
		'Иуд'		=>'Juda', 							
		// Послания апостола Павла
		'Rom'		=>'Rom', 		//'Послание апостола Павла к Римлянам',				
		'Римл'		=>'Rom', 						
		'Рим'		=>'Rom', 						
		'1Cor'		=>'1Cor', 		//'Первое послание апостола Павла к Коринфянам',					
		'1Кор'		=>'1Cor', 							
		'2Cor'		=>'2Cor',		//'Второе послание апостола Павла к Коринфянам',					
		'2Кор'		=>'2Cor',							
		'Gal'		=>'Gal', 		//'Послание апостола Павла к Галатам',						
		'Галат'		=>'Gal', 								
		'Гал'		=>'Gal', 								
		'Eph'		=>'Eph', 		//'Послание апостола Павла к Ефесянам'					
		'Ефесян'	=>'Eph', 							
		'Ефес'		=>'Eph', 							
		'Еф'		=>'Eph', 							
		'Phil'		=>'Phil',  		//'Послание апостола Павла к Филиппийцам',		
		'Филип'		=>'Phil',  				
		'Фил'		=>'Phil',  				
		'Флп'		=>'Phil',  				
		'Col'		=>'Col',		//'Послание апостола Павла к Колоссянам',						
		'Колос'		=>'Col',								
		'Кол'		=>'Col',								
		'1Thes'		=>'1Thes', 		//'Первое послание апостола Павла к Фессалоникийцам (Солунянам)',			
		'1Солун'	=>'1Thes', 					
		'1Сол'		=>'1Thes', 					
		'1Фес'		=>'1Thes', 					
		'2Thes'		=>'2Thes', 		//'Второе послание апостола Павла к Фессалоникийцам (Солунянам)',			
		'2Солун'	=>'2Thes', 					
		'2Сол'		=>'2Thes', 					
		'2Фес'		=>'2Thes', 					
		'1Tim'		=>'1Tim', 		//'Первое послание апостола Павла к Тимофею', 					
		'1Тимоф'	=>'1Tim', 							
		'1Тим'		=>'1Tim', 							
		'2Tim'		=>'2Tim',		//'Второе послание апостола Павла к Тимофею',					
		'2Тимоф'	=>'2Tim',							
		'2Тим'		=>'2Tim',							
		'Tit'		=>'Tit', 		//'Послание апостола Павла к Титу', 						
		'Титу'		=>'Tit', 								
		'Тита'		=>'Tit', 								
		'Тит'		=>'Tit', 								
		'Phlm'		=>'Phlm', 		//'Послание апостола Павла к Филимону', 				
		'Филим'		=>'Phlm', 						
		'Флм'		=>'Phlm', 						
		'Hebr'		=>'Hebr', 		//'Послание апостола Павла к Евреям',					
		'Евреям'	=>'Hebr', 							
		'Евр'		=>'Hebr', 							
		'Apok'		=>'Apok',		//'Откровение Иоанна Богослова (Апокалипсис)'				
		'Откр'		=>'Apok',					
		'Отк'		=>'Apok',					
		'Апок'		=>'Apok');				


	$bg_bibrefs_bookTitle = array(			// Полные названия Книг Священного Писания
		// Ветхий Завет
		// Пятикнижие Моисея
		'Gen' 		=>'Книга Бытия', 
		'Ex' 		=>'Книга Исход', 
		'Lev' 		=>'Книга Левит', 
		'Num' 		=>'Книга Числа', 
		'Deut' 		=>'Второзаконие',
		// «Пророки» (Невиим) 
		'Nav' 		=>'Книга Иисуса Навина',
		'Judg'		=>'Книга Судей Израилевых', 
		'Rth' 		=>'Книга Руфь',
		'1Sam' 		=>'Первая книга Царств (Первая книга Самуила)', 
		'2Sam' 		=>'Вторая книга Царств (Вторая книга Самуила)', 
		'1King' 	=>'Третья книга Царств (Первая книга Царей)', 
		'2King' 	=>'Четвёртая книга Царств (Вторая книга Царей)',
		'1Chron' 	=>'Первая книга Паралипоменон (Первая книга Хроник, Первая Летопись)', 
		'2Chron' 	=>'Вторая книга Паралипоменон (Вторая книга Хроник, Вторая Летопись)', 
		'Ezr' 		=>'Книга Ездры (Первая книга Ездры)', 
		'Nehem' 	=>'Книга Неемии', 
		'Est' 		=>'Книга Есфири',  
		// «Писания» (Ктувим)
		'Job' 		=>'Книга Иова',
		'Ps' 		=>'Псалтирь', 
		'Prov' 		=>'Книга Притчей Соломоновых', 
		'Eccl' 		=>'Книга Екклезиаста, или Проповедника', 
		'Song' 		=>'Песнь песней Соломона',

		'Is' 		=>'Книга пророка Исайи', 
		'Jer' 		=>'Книга пророка Иеремии',
		'Lam' 		=>'Книга Плач Иеремии', 
		'Ezek'	 	=>'Книга пророка Иезекииля',
		'Dan' 		=>'Книга пророка Даниила', 
		// Двенадцать малых пророков 
		'Hos' 		=>'Книга пророка Осии', 
		'Joel'	 	=>'Книга пророка Иоиля',
		'Am' 		=>'Книга пророка Амоса', 
		'Avd' 		=>'Книга пророка Авдия', 
		'Jona' 		=>'Книга пророка Ионы',
		'Mic' 		=>'Книга пророка Михея', 
		'Naum' 		=>'Книга пророка Наума',
		'Habak' 	=>'Книга пророка Аввакума', 
		'Sofon' 	=>'Книга пророка Софонии', 
		'Hag' 		=>'Книга пророка Аггея', 
		'Zah' 		=>'Книга пророка Захарии',
		'Mal' 		=>'Книга пророка Малахии',
		// Второканонические книги
		'1Mac' 		=>'Первая книга Маккавейская',
		'2Mac' 		=>'Вторая книга Маккавейская', 
		'3Mac' 		=>'Третья книга Маккавейская', 
		'Bar' 		=>'Книга пророка Варуха', 
		'2Ezr' 		=>'Вторая книга Ездры', 
		'3Ezr' 		=>'Третья книга Ездры',
		'Judf' 		=>'Книга Иудифи', 
		'pJer' 		=>'Послание Иеремии', 
		'Solom' 	=>'Книга Премудрости Соломона',
		'Sir' 		=>'Книга Премудрости Иисуса, сына Сирахова', 
		'Tov' 		=>'Книга Товита',
		// Новый Завет
		// Евангилие
		'Mt' 		=>'Евангелие от Матфея',
		'Mk' 		=>'Евангелие от Марка', 
		'Lk' 		=>'Евангелие от Луки', 
		'Jn' 		=>'Евангелие от Иоанна', 
		// Деяния и послания Апостолов
		'Act' 		=>'Деяния святых Апостолов', 
		'Jac' 		=>'Послание Иакова', 
		'1Pet'	 	=>'Первое послание Петра', 
		'2Pet'	 	=>'Второе послание Петра',	
		'1Jn' 		=>'Первое послание Иоанна', 
		'2Jn' 		=>'Второе послание Иоанна', 
		'3Jn' 		=>'Третье послание Иоанна',
		'Juda'	 	=>'Послание Иуды', 
		// Послания апостола Павла
		'Rom' 		=>'Послание апостола Павла к Римлянам', 
		'1Cor' 		=>'Первое послание апостола Павла к Коринфянам', 
		'2Cor' 		=>'Второе послание апостола Павла к Коринфянам',
		'Gal'	 	=>'Послание апостола Павла к Галатам', 
		'Eph' 		=>'Послание апостола Павла к Ефесянам', 
		'Phil' 		=>'Послание апостола Павла к Филиппийцам', 
		'Col' 		=>'Послание апостола Павла к Колоссянам',
		'1Thes' 	=>'Первое послание апостола Павла к Фессалоникийцам (Солунянам)',
		'2Thes' 	=>'Второе послание апостола Павла к Фессалоникийцам (Солунянам)',  
		'1Tim' 		=>'Первое послание апостола Павла к Тимофею', 
		'2Tim'	 	=>'Второе послание апостола Павла к Тимофею',
		'Tit' 		=>'Послание апостола Павла к Титу', 
		'Phlm'	 	=>'Послание апостола Павла к Филимону', 
		'Hebr'	 	=>'Послание апостола Павла к Евреям', 
		'Apok' 		=>'Откровение Иоанна Богослова (Апокалипсис)');

	$bg_bibrefs_shortTitle = array(		// Стандартные обозначение книг Священного Писания
		// Ветхий Завет
		// Пятикнижие Моисея															
		'Gen'		=>"Быт.", 
		'Ex'		=>"Исх.", 
		'Lev'		=>"Лев.",
		'Num'		=>"Чис.",
		'Deut'		=>"Втор.",
		// «Пророки» (Невиим) 
		'Nav'		=>"Нав.",
		'Judg'		=>"Суд.",
		'Rth'		=>"Руф.",
		'1Sam'		=>"1Цар.",
		'2Sam'		=>"2Цар.",
		'1King'		=>"3Цар.",
		'2King'		=>"4Цар.",
		'1Chron'	=>"1Пар.",
		'2Chron'	=>"2Пар.",
		'Ezr'		=>"1Езд.",
		'Nehem'		=>"Неем.",
		'Est'		=>"Есф.",
		// «Писания» (Ктувим)
		'Job'		=>"Иов.",
		'Ps'		=>"Пс.",
		'Prov'		=>"Прит.", 
		'Eccl'		=>"Еккл.",
		'Song'		=>"Песн.",
		'Is'		=>"Ис.",
		'Jer'		=>"Иер.",
		'Lam'		=>"Плч.",
		'Ezek'		=>"Иез.",
		'Dan'		=>"Дан.",	
		// Двенадцать малых пророков 
		'Hos'		=>"Ос.",
		'Joel'		=>"Иоил.",
		'Am'		=>"Ам.",
		'Avd'		=>"Авд.",
		'Jona'		=>"Ион.",
		'Mic'		=>"Мих.",
		'Naum'		=>"Наум.",
		'Habak'		=>"Авв.",
		'Sofon'		=>"Соф.",
		'Hag'		=>"Аг.",
		'Zah'		=>"Зах.",
		'Mal'		=>"Мал.",
		// Второканонические книги
		'1Mac'		=>"1Мак.",
		'2Mac'		=>"2Мак.",
		'3Mac'		=>"3Мак.",
		'Bar'		=>"Вар.",
		'2Ezr'		=>"2Езд.",
		'3Ezr'		=>"3Езд.",
		'Judf'		=>"Иудиф.",
		'pJer'		=>"ПослИер.",
		'Solom'		=>"Прем.",
		'Sir'		=>"Сир.",
		'Tov'		=>"Тов.",
		// Новый Завет
		// Евангилие
		'Mt'		=>"Мф.",
		'Mk'		=>"Мк.",
		'Lk'		=>"Лк.",
		'Jn'		=>"Ин.",
		// Деяния и послания Апостолов
		'Act'		=>"Деян.",
		'Jac'		=>"Иак.",
		'1Pet'		=>"1Пет.",
		'2Pet'		=>"2Пет.",
		'1Jn'		=>"1Ин.", 
		'2Jn'		=>"2Ин.",
		'3Jn'		=>"3Ин.",
		'Juda'		=>"Иуд.",
		// Послания апостола Павла
		'Rom'		=>"Рим.",
		'1Cor'		=>"1Кор.",
		'2Cor'		=>"2Кор.",
		'Gal'		=>"Гал.",
		'Eph'		=>"Еф.",
		'Phil'		=>"Флп.",
		'Col'		=>"Кол.",
		'1Thes'		=>"1Фес.",
		'2Thes'		=>"2Фес.",
		'1Tim'		=>"1Тим.",
		'2Tim'		=>"2Тим.",
		'Tit'		=>"Тит.",
		'Phlm'		=>"Флм.",
		'Hebr'		=>"Евр.",
		'Apok'		=>"Отк.");

	$bg_bibrefs_bookFile = array(						// Таблица названий файлов книг
		// Ветхий Завет
		// Пятикнижие Моисея
		'Gen'	 	=>'ru/gen',							//'Книга Бытия', 
		'Ex'	 	=>'ru/ex',							//'Книга Исход', 
		'Lev'	 	=>'ru/lev',							//'Книга Левит', 
		'Num'	 	=>'ru/num',							//'Книга Числа', 
		'Deut'	 	=>'ru/deu',							//'Второзаконие',
		// «Пророки» (Невиим) 
		'Nav'	 	=>'ru/nav',							//'Книга Иисуса Навина',
		'Judg'		=>'ru/sud',							//'Книга Судей Израилевых', 
		'Rth'	 	=>'ru/ruf',							//'Книга Руфь',
		'1Sam'	 	=>'ru/king1',						//'Первая книга Царств (Первая книга Самуила)', 
		'2Sam'	 	=>'ru/king2',						//'Вторая книга Царств (Вторая книга Самуила)', 
		'1King' 	=>'ru/king3',						//'Третья книга Царств (Первая книга Царей)', 
		'2King' 	=>'ru/king4',						//'Четвёртая книга Царств (Вторая книга Царей)',
		'1Chron' 	=>'ru/para1',						//'Первая книга Паралипоменон (Первая книга Хроник, Первая Летопись)', 
		'2Chron' 	=>'ru/para2',						//'Вторая книга Паралипоменон (Вторая книга Хроник, Вторая Летопись)', 
		'Ezr'	 	=>'ru/ezr1',						//'Книга Ездры (Первая книга Ездры)', 
		'Nehem' 	=>'ru/nee',							//'Книга Неемии', 
		'Est'	 	=>'ru/esf',							//'Книга Есфири',  
		// «Писания» (Ктувим)
		'Job'	 	=>'ru/iov',							//'Книга Иова',
		'Ps' 		=>'ru/ps',							//'Псалтирь', 
		'Prov'	 	=>'ru/prov',						//'Книга Притчей Соломоновых', 
		'Eccl'	 	=>'ru/eccl',						//'Книга Екклезиаста, или Проповедника', 
		'Song'	 	=>'ru/song',						//'Песнь песней Соломона',

		'Is' 		=>'ru/isa',							//'Книга пророка Исайи', 
		'Jer' 		=>'ru/jer',							//'Книга пророка Иеремии',
		'Lam' 		=>'ru/lam',							//'Книга Плач Иеремии', 
		'Ezek'	 	=>'ru/eze',							//'Книга пророка Иезекииля',
		'Dan' 		=>'ru/dan',							//'Книга пророка Даниила', 
		// Двенадцать малых пророков 
		'Hos' 		=>'ru/hos',							//'Книга пророка Осии', 
		'Joel'	 	=>'ru/joe',							//'Книга пророка Иоиля',
		'Am' 		=>'ru/am',							//'Книга пророка Амоса', 
		'Avd' 		=>'ru/avd',							//'Книга пророка Авдия', 
		'Jona'	 	=>'ru/jona',						//'Книга пророка Ионы',
		'Mic' 		=>'ru/mih',							//'Книга пророка Михея', 
		'Naum' 		=>'ru/nau',							//'Книга пророка Наума',
		'Habak' 	=>'ru/avv',							//'Книга пророка Аввакума', 
		'Sofon' 	=>'ru/sof',							//'Книга пророка Софонии', 
		'Hag' 		=>'ru/agg',							//'Книга пророка Аггея', 
		'Zah' 		=>'ru/zah',							//'Книга пророка Захарии',
		'Mal' 		=>'ru/mal',							//'Книга пророка Малахии',
		// Второканонические книги
		'1Mac'	 	=>'ru/mak1',						//'Первая книга Маккавейская',
		'2Mac'	 	=>'ru/mak2',						//'Вторая книга Маккавейская', 
		'3Mac'	 	=>'ru/mak3',						//'Третья книга Маккавейская', 
		'Bar' 		=>'ru/varuh',						//'Книга пророка Варуха', 
		'2Ezr' 		=>'ru/ezr2',						//'Вторая книга Ездры', 
		'3Ezr' 		=>'ru/ezr3',						//'Третья книга Ездры',
		'Judf' 		=>'ru/jdi',							//'Книга Иудифи', 
		'pJer' 		=>'ru/posjer',						//'Послание Иеремии', 
		'Solom' 	=>'ru/prem',						//'Книга Премудрости Соломона',
		'Sir' 		=>'ru/sir',							//'Книга Премудрости Иисуса, сына Сирахова', 
		'Tov' 		=>'ru/tov',							//'Книга Товита',
		// Новый Завет
		// Евангилие
		'Mt' 		=>'ru/mf',							//'Евангелие от Матфея',
		'Mk' 		=>'ru/mk',							//'Евангелие от Марка', 
		'Lk' 		=>'ru/lk',							//'Евангелие от Луки', 
		'Jn' 		=>'ru/jn',							//'Евангелие от Иоанна', 
		// Деяния и послания Апостолов
		'Act' 		=>'ru/act',							//'Деяния святых Апостолов', 
		'Jac'	 	=>'ru/jak',							//'Послание Иакова', 
		'1Pet'	 	=>'ru/pe1',							//'Первое послание Петра', 
		'2Pet'	 	=>'ru/pe2',							//'Второе послание Петра',	
		'1Jn'	 	=>'ru/jn1',							//'Первое послание Иоанна', 
		'2Jn'	 	=>'ru/jn2',							//'Второе послание Иоанна', 
		'3Jn'	 	=>'ru/jn3',							//'Третье послание Иоанна',
		'Juda'	 	=>'ru/jud',							//'Послание Иуды', 
		// Послания апостола Павла
		'Rom' 		=>'ru/rom',							//'Послание апостола Павла к Римлянам', 
		'1Cor'	 	=>'ru/co1',							//'Первое послание апостола Павла к Коринфянам', 
		'2Cor'	 	=>'ru/co2',							//'Второе послание апостола Павла к Коринфянам',
		'Gal' 		=>'ru/gal',							//'Послание апостола Павла к Галатам', 
		'Eph' 		=>'ru/eph',							//'Послание апостола Павла к Ефесянам', 
		'Phil'	 	=>'ru/flp',							//'Послание апостола Павла к Филиппийцам', 
		'Col' 		=>'ru/col',							//'Послание апостола Павла к Колоссянам',
		'1Thes' 	=>'ru/fe1',							//'Первое послание апостола Павла к Фессалоникийцам (Солунянам)',
		'2Thes' 	=>'ru/fe2',							//'Второе послание апостола Павла к Фессалоникийцам (Солунянам)',  
		'1Tim' 		=>'ru/ti1',							//'Первое послание апостола Павла к Тимофею', 
		'2Tim' 		=>'ru/ti2',							//'Второе послание апостола Павла к Тимофею',
		'Tit' 		=>'ru/tit',							//'Послание апостола Павла к Титу', 
		'Phlm'	 	=>'ru/flm',							//'Послание апостола Павла к Филимону', 
		'Hebr'	 	=>'ru/heb',							//'Послание апостола Павла к Евреям', 
		'Apok'	 	=>'ru/rev');						//'Откровение Иоанна Богослова (Апокалипсис)'
