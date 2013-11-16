/*******************************************************************************
   При наведении мыши на ссылку запрашивает текст Библии
   и заполняет всплывающую подсказку
*******************************************************************************/  
jQuery('a.bg_data_title').mouseenter(function(e){
	var el = jQuery(this);
	if (el.attr('data-title') == "") return;			// Книга не задана
	var tooltip = el.children('span.bg_data_tooltip');	
	if (tooltip.attr('display') != 'opened') {		// Подсказка еще не открыта
		tooltip.attr('display', 'opened');			// Вешаем флаг "Открыто"
		jQuery.ajax({
			type: 'GET',
			dataType: 'text',
			url:  el.attr('data-title'),					// Запрос стихов Библии
			success: function (verses) {
				tooltip.append(verses);						// Добавляем стихи в подсказку
			}
		});
	}
	var pos = el.offset();									// Позиция родительского элемента
	var mousex = e.pageX - 20; 								// Получаем координаты по оси X - 20
	var mousey =  pos.top+el.height(); 						// Получаем координаты по оси Y
	var tipWidth = tooltip.width(); 						// Вычисляем ширину подсказки
	var tipHeight = parseInt(tooltip.css('max-height')); 	// Задаем высоту подсказки как максимальную
	// Определяем дистанцию от правого края окна браузера до блока, содержащего подсказку
	var tipVisX = jQuery(window).scrollLeft()+jQuery(window).width() - (mousex + tipWidth);
	// Определяем дистанцию от ниждего края окна браузера до блока, содержащего подсказку        
	var tipVisY = jQuery(window).scrollTop()+jQuery(window).height() - (mousey + tipHeight);
		tooltip.css('height', "auto");						// то задаем ее высоту равной максимальной

	if ( tipVisX < 20 ) { // Если ширина подсказки превышает расстояние от правого края окна браузера до курсора,
		mousex = e.pageX - tipWidth + 20; // то распологаем область с подсказкой по другую сторону от курсора
	} 
	if ( tipVisY < 0 ) { // Если высота подсказки превышает расстояние от нижнего края окна браузера до курсора,
		tooltip.css('height', tipHeight+"px");				// то задаем ее высоту равной максимальной
		mousey = pos.top - tipHeight;  						// и распологаем область с подсказкой над курсором
	} 
	mousex = mousex - pos.left;								// Координаты относительно родительского элемента
	mousey = mousey - pos.top;
	//Непосредственно присваиваем найденные координаты области, содержащей подсказку
	tooltip.css('top', mousey+"px");
	tooltip.css('left', mousex+"px");
});

