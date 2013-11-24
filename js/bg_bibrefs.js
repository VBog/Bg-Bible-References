/*******************************************************************************
   При создании страницы для всех элементов 'a.bg_data_title' 
   запрашивает текст Библии и заполняет всплывающую подсказку
*******************************************************************************/  
jQuery(document).ready(function(){
	jQuery('a.bg_data_title').each (function(){
		var el = jQuery(this);
		if (el.attr('data-title') == "") return;				// Книга не задана
		var tooltip = el.children('span.bg_data_tooltip');	
		jQuery.ajax({
			type: 'GET',
			async: true,
			dataType: 'html',
			url: '/wp-admin/admin-ajax.php?'+el.attr('data-title'),	// Запрос стихов Библии
			data: {
				action: 'bg_bibrefs'
			},
			success: function (verses) {
				tooltip.html(verses);						// Добавляем стихи в подсказку
				el.attr('data-title', "");
			},
			error:function (XMLHttpRequest, textStatus, errorThrown) {
				tooltip.append("AJAX error: "+XMLHttpRequest.status+" "+errorThrown);
			}
		});
	});
});

/*******************************************************************************
   При наведении мыши на ссылку, если подсказка не пуста, 
   отображает подсказку на экране
*******************************************************************************/  
jQuery('a.bg_data_title')
	.mouseenter(function(e){
		var el = jQuery(this);
		var tooltip = el.children('span.bg_data_tooltip');	
		if (el.attr('data-title') != "") {		// Книга задана
			jQuery.ajax({
				type: 'GET',
				async: true,
				dataType: 'html',
				url: '/wp-admin/admin-ajax.php?'+el.attr('data-title'),	// Запрос стихов Библии
				data: {
					action: 'bg_bibrefs'
				},
				success: function (verses) {
					tooltip.html(verses);								// Добавляем стихи в подсказку
					el.attr('data-title', "");
				},
				error:function (XMLHttpRequest, textStatus, errorThrown) {
					tooltip.append("AJAX error: "+XMLHttpRequest.status+" "+errorThrown);
				}
			});
		}
		if (tooltip.html() == '') return;					// Подсказка еще пустая, подождем
	// Определяем положение подсказки на экране
		var pos = el.offset();									// Позиция родительского элемента
		var mousex = e.pageX - 24; 								// Получаем координаты по оси X - 20
		var mousey =  pos.top+el.height(); 						// Получаем координаты по оси Y
		var tipWidth = tooltip.width(); 						// Вычисляем ширину подсказки
		var tipHeight = tooltip.height(); 						// Вычисляем высоту подсказки
		if (tipHeight < 10)
			tooltip.css('height', parseInt(tooltip.css('max-height'))+"px");// Задаем высоту подсказки как максимальную
		else tooltip.css('height', "auto");						// иначе высота определяется автоматически
		tipHeight = tooltip.height(); 							// Снова вычисляем высоту подсказки
		
		// Определяем дистанцию от правого края окна браузера до блока, содержащего подсказку
		var tipVisX = jQuery(window).scrollLeft()+jQuery(window).width() - (mousex + tipWidth);
		// Определяем дистанцию от ниждего края окна браузера до блока, содержащего подсказку        
		var tipVisY = jQuery(window).scrollTop()+jQuery(window).height() - (mousey + tipHeight);

		if ( tipVisX < 20 ) { // Если ширина подсказки превышает расстояние от правого края окна браузера до курсора,
			mousex = e.pageX - tipWidth + 24; // то распологаем область с подсказкой по другую сторону от курсора
		} 
		if ( tipVisY < 20 ) { // Если высота подсказки превышает расстояние от нижнего края окна браузера до курсора,
			mousey = pos.top - tipHeight;  						// то распологаем область с подсказкой над курсором
		} 
		else tooltip.css('height', "auto");						// иначе высота определяется автоматически
		mousex = mousex - pos.left;								// Координаты относительно родительского элемента
		mousey = mousey - pos.top;
		//Непосредственно присваиваем найденные координаты области, содержащей подсказку
		tooltip.css('top', mousey+"px");
		tooltip.css('left', mousex+"px");
		tooltip.css('display', "block");						// Строчно-блочный элемент 
	})
/*******************************************************************************
   При удалении мыши от ссылки, удаляет подсказку с экрана
*******************************************************************************/  
	.mouseleave(function(e) {
		var el = jQuery(this);
		var tooltip = el.children('span.bg_data_tooltip');	
		tooltip.css('display', "none");							// Скрыть элемент 	
	});
