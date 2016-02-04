// Хранилище для заданных значений ширины, максимальной высоты и вертикального положения подсказки
var bg_bibrefs_tipWidth;
var bg_bibrefs_tipMaxHeight;	
var bg_bibrefs_tipTop;	
var bg_bibrefs_ajaxurl;
var bg_bibrefs_content;

/*******************************************************************************
   При создании страницы для всех элементов 'a.bg_data_title' 
   запрашивает текст Библии и заполняет всплывающую подсказку
*******************************************************************************/  
jQuery(document).ready(function(){

	// Сохраняем заданные значения ширины, максимальной высоты и вертикального положения подсказки
	var tooltip = jQuery('span.bg_data_tooltip:first');	
	bg_bibrefs_tipWidth = parseInt(tooltip.css('width'));
	bg_bibrefs_tipMaxHeight = parseInt(tooltip.css('max-height'));	
	bg_bibrefs_tipTop = parseInt(tooltip.css('top'));	

	var allParams = parseUrlQuery();
	bg_bibrefs_ajaxurl = decodeURIComponent (allParams.ajaxurl);
	bg_bibrefs_content = decodeURIComponent (allParams.content);
	if (allParams.preq != '1') return; 							// Запрет на предварительную загрузку

	jQuery('span.bg_data_title').each (function(){
		var el = jQuery(this);
		var tooltip = el.children('span.bg_data_tooltip');	
		if (tooltip.css('position')=='fixed') return;
		if (el.attr('data-title') != "") {						// Книга задана
			jQuery.ajax({
				type: 'GET',
				cache: false,
				async: true,									// Асинхронный запрос
				dataType: 'text',
				url: bg_bibrefs_ajaxurl+el.attr('data-title'),	// Запрос стихов Библии
				data: {
					action: 'bg_bibrefs',
					func: 'each'
				},
				success: function (verses, textStatus) {
					if (verses) {
						tooltip.html(verses);					// Добавляем стихи в подсказку
						el.attr('data-title', "");
						el.attr('title', "");
					} else {
						el.attr('title', "");
						el.attr('data-title', "");
						el.css("border-bottom", "1px dashed red");
						e = el.children('a');
						e.before(e.text()).remove();
					}
				}
			});
		}
	});
}); 

/*******************************************************************************
   При наведении мыши на ссылку, если подсказка не пуста, 
   отображает подсказку на экране
*******************************************************************************/  
var bg_bibrefs_intervalID;

jQuery('span.bg_data_title')
	.mouseenter(function(e){
		var el = jQuery(this);
		var tooltip = el.children('span.bg_data_tooltip');	
		if (tooltip.css('position')=='fixed') return;

		var allParams = parseUrlQuery();
		if (el.attr('data-title') != "" && allParams.preq == '0') {	// Книга задана
		bg_bibrefs_intervalID = setTimeout(	function() {
			var ttl= el.attr('title');							// Сохраняем на всякий случай содержимое стандартной подсказки
			el.attr('title', "");								// Убираем стандартную подсказку

			jQuery.ajax({
				type: 'GET',
				cache: false,
				async: true,									// Асинхронный запрос
				dataType: 'text',
				url: bg_bibrefs_ajaxurl+el.attr('data-title'),	// Запрос стихов Библии
				data: {
					action: 'bg_bibrefs',
					func: 'mouseenter'
				},
				success: function (verses, textStatus) {		// Если что-то пришло с сервера 
					if (verses) {							// и не пустая сторока
						el.attr('data-title', "");				// Опустошаем, чтобы больше не загружать
						tooltip.html(verses);					// Добавляем стихи в подсказку 
						if (el.filter(':hover').length)			// Проверяем находится ли все еще курсор на ссылке
							tooltip_mini(tooltip, el, e);		// и выводим подсказку на экран
					} else {
						el.attr('title', ttl);					// Если пусто, то восстанавливаем стандартную подсказку
						el.attr('data-title', "");
						el.css("border-bottom", "2px dotted red");
						e = el.children('a');
						e.before(e.text()).remove();
					}
				},
				error: function () {
					el.attr('title', ttl);						// Если ошибка, то восстанавливаем стандартную подсказку
				}
			}); 
		}, 200);
		}
	// Выводим подсказку на экран
		tooltip_mini(tooltip, el, e);
	})
/*******************************************************************************
   При удалении мыши от ссылки, удаляет подсказку с экрана
*******************************************************************************/  
	.mouseleave (function(e){
		clearInterval(bg_bibrefs_intervalID);
		var tooltip = jQuery(this).children('span.bg_data_tooltip');
		if (tooltip.css('position')=='fixed') return;
		tooltip.css('display', "none");
	});

/*******************************************************************************
   Отображение подсказки под ссылкой
*******************************************************************************/  
function tooltip_mini(tooltip, el, e) {	
	if (!tooltip.html()) return;
	// Восстанавливаем заданные значения ширины, максимальной высоты и вертикального положения подсказки 
	tooltip.css({
		'width': bg_bibrefs_tipWidth+"px",		// Восстанавливаем заданную ширину подсказки
		'max-height': bg_bibrefs_tipMaxHeight+"px",	// Восстанавливаем заданную максимальную высоту подсказки
		'top': bg_bibrefs_tipTop+"px",			// Восстанавливаем вертикальное положение подсказки
		'position':'absolute',					// Абсолютная позиция
		'display': "block"						// Строчно-блочный элемент 
	});
	tooltip.children('span').css({
		'margin':  '5px -5px -5px -10px',		// Отступ
		'padding': '5px 5px 5px 10px',			// Поля 
		'overflow-y': 'auto', 					// Разрешить при необходимости скроллинг по вертикали 
		'overflow-x': 'hidden', 				// Запретить скроллинг по горизонтали 
		'border-top': "1px solid #767676" 		// Параметры рамки 
	});

	var padding = parseInt(tooltip.css('paddingLeft'))+parseInt(tooltip.css('paddingRight'))+parseInt(tooltip.css('border-Left-Width'))+parseInt(tooltip.css('border-Right-Width'));
	// Координаты контейнера, внутри которого будут отображаться подсказки. Например для <div id="content">, это "#content"
	var content = jQuery(bg_bibrefs_content);
	if (content.length < 1) content = jQuery('body');	// Если тема не содержит, указанный контейнер, то определяем положение body
	var c_left = content.position().left+parseInt(content.css('paddingLeft'))+parseInt(content.css('marginLeft'))+parseInt(content.css('border-Left-Width'));
	var c_right =c_left+content.width();
//	alert(c_left+" "+c_right);
	
	var tipWidth = parseInt(tooltip.css('width'));	// Заданная ширина подсказки

	var pos = el.position();						// Позиция родительского элемента
	var x = e.pageX-(el.offset().left-pos.left)-12;	// Получаем координаты по оси X - 12
	
	var y =  pos.top+el.height(); 					// Получаем координаты по оси Y
	tooltip.css('height', "auto");					// Высота определяется автоматически
	var tipHeight = tooltip.height(); 				// Вычисляем высоту подсказки

	// Подсказка не должна выходить за пределы контейнера
	if (tipWidth+padding > content.width()) tipWidth = content.width()-padding;
	if (x < c_left) x = c_left;
	if (x+tipWidth+padding > c_right) x = c_right-tipWidth-padding-1;

	// Задаем размеры контейнера с текстом
	container = tooltip.children('span');
	var padding_w = parseInt(container.css('marginLeft'))+parseInt(container.css('marginRight'))+parseInt(container.css('paddingLeft'))+parseInt(container.css('paddingRight'))+parseInt(container.css('border-Left-Width'))+parseInt(container.css('border-Right-Width'));
	var divWidth = tipWidth - padding_w;
	var padding_h = parseInt(container.css('paddingTop'))+parseInt(container.css('paddingBottom'))+parseInt(container.css('border-Top-Width'))+parseInt(container.css('border-Bottom-Width'));
	var divHeight = parseInt(tooltip.css('max-height')) - container.position().top - padding_h;
	container.css({
		'width': divWidth+"px",
		'max-height': divHeight+"px"
	});
	
	// Определяем дистанцию от ниждего края окна браузера до блока, содержащего подсказку        
	tipHeight = tooltip.height(); 				// Вычисляем высоту подсказки
	var tipVisY = jQuery(window).scrollTop()+jQuery(window).height() - (y + tipHeight+(el.offset().top-pos.top));
	if ( tipVisY < 20 ) { // Если высота подсказки превышает расстояние от нижнего края окна браузера до курсора,
		y = pos.top-tipHeight-el.height()/2;  		// то распологаем область с подсказкой над курсором
	} 
	//Присваиваем найденные координаты области, содержащей подсказку
	x = Math.round(x);							
	y = Math.round(y);
	tooltip.css({
		'width': tipWidth+"px",
		'top': y+"px",
		'left': x+"px",
		'position':'absolute',					// Абсолютная позиция
		'display': "block"						// Строчно-блочный элемент 
	});	
	// Назначаем название и действие кнопке
	var img = jQuery('span.bg_data_tooltip img');
	img.unbind();								// Удаляем все обработчики событий
	img.attr('title', img.attr('title1'));		//  Название 1
	img.click (function () {
		var tooltip = jQuery(this).parent();	
		tooltip.css({
			'position': 'absolute',				// Абсолютная позиция
			'display': "none"					// Скрыть подсказку
		});
		tooltip_maxi(tooltip);					// Развернуть подсказку			
	});
	// Выделение текста по щелчку
	tooltip.children('span').click(function() {
		var e=this; 
		if(window.getSelection){ 
			var s=window.getSelection(); 
			if(s.setBaseAndExtent){ 
				s.setBaseAndExtent(e,0,e,e.innerText.length-1); 
			}else{ 
				var r=document.createRange(); 
				r.selectNodeContents(e); 
				s.removeAllRanges(); 
				s.addRange(r);
			} 
		}else if(document.getSelection){ 
			var s=document.getSelection(); 
			var r=document.createRange(); 
			r.selectNodeContents(e); 
			s.removeAllRanges(); 
			s.addRange(r); 
		}else if(document.selection){ 
			var r=document.body.createTextRange(); 
			r.moveToElementText(e); 
			r.select();
		}
	});
}	
/*******************************************************************************
   Отображение подсказки посередине экрана
*******************************************************************************/  
function tooltip_maxi(tooltip) {

	// Создаем блок для затемнения фона в том же контексте, при этом z-index должен быть меньше чем у tooltip
	var data_title=jQuery(tooltip).parent();
	jQuery("<div/>", { "id": "bg_BG_overlay" }).appendTo(data_title);
	// Восстанавливаем заданные значения ширины, максимальной высоты и вертикального положения подсказки 
	tooltip.css({
		'width': bg_bibrefs_tipWidth+"px",			// Восстанавливаем заданную ширину подсказки
		'max-height': bg_bibrefs_tipMaxHeight+"px",	// Восстанавливаем заданную максимальную высоту подсказки
		'top': bg_bibrefs_tipTop+"px",				// Восстанавливаем вертикальное положение подсказки
		'position':'fixed',						// Фиксированная позиция
		'display': "block"						// Строчно-блочный элемент 
	});
	var padding = parseInt(tooltip.css('paddingLeft'))+parseInt(tooltip.css('paddingRight'))+parseInt(tooltip.css('border-Left-Width'))+parseInt(tooltip.css('border-Right-Width'));
	// Координаты body (размещаем по центру экрана)
	var content = jQuery('body');
	var cc_left = content.offset().left+parseInt(content.css('paddingLeft'))+parseInt(content.css('border-Left-Width'));

	var tipWidth = content.width()-padding-40;
	var tipWidthMax = parseInt(tooltip.css('max-width'));
	if (tipWidth > tipWidthMax) tipWidth = tipWidthMax;
	var tipHeight = jQuery(window).height() - 2*bg_bibrefs_tipTop;
	tooltip.css({
		'width': tipWidth+"px",
		'max-height': tipHeight+"px",
		'height': "auto"
	});
	tipWidth = tooltip.width();
	var x = cc_left+(content.width() - tipWidth-padding)/2;

	// Задаем размеры контейнера с текстом
	container = tooltip.children('span');
	var padding_w = parseInt(container.css('marginLeft'))+parseInt(container.css('marginRight'))+parseInt(container.css('paddingLeft'))+parseInt(container.css('paddingRight'))+parseInt(container.css('border-Left-Width'))+parseInt(container.css('border-Right-Width'));
	var divWidth = tipWidth - padding_w;
	var padding_h = parseInt(container.css('paddingTop'))+parseInt(container.css('paddingBottom'))+parseInt(container.css('border-Top-Width'))+parseInt(container.css('border-Bottom-Width'));
	var divHeight = tipHeight - container.position().top - padding_h;
	container.css({
		'width': divWidth+"px",
		'max-height': divHeight+"px"
	});
	tipHeight = tooltip.height();
	var y = (jQuery(window).height() - tipHeight)/2;
	

	//Присваиваем найденные координаты области, содержащей подсказку
	x = Math.round(x);							
	y = Math.round(y);
	tooltip.css({
		'width': tipWidth+"px",
		'top': y+"px",
		'left': x+"px",
		'position':'fixed',						// Фиксированная позиция
		'display': "block"						// Строчно-блочный элемент 
	});	
	// Назначаем название и действие кнопке
	var img = jQuery('span.bg_data_tooltip img');
	img.unbind();								// Удаляем все обработчики событий
	img.attr('title', img.attr('title2'));		//  Название 2
	img.click (function () {					// Щелчок по кнопке
		jQuery(this).parent().css({
			'position': 'absolute',				// Абсолютная позиция
			'display': "none"					// Скрыть подсказку
		});
		jQuery( "div" ).remove( "#bg_BG_overlay" );	// Удаляем блок затемнения фона
	});
	jQuery(document).unbind();
	jQuery(document).click(function(event) {	// Щелчок за пределами подсказки
		if (jQuery(event.target).closest("span.bg_data_tooltip").length) return;
		jQuery('span.bg_data_tooltip').css({
			'position': 'absolute',				// Абсолютная позиция
			'display': "none"					// Скрыть подсказку
		});
		jQuery( "div" ).remove( "#bg_BG_overlay" );	// Удаляем блок затемнения фона
	});
}
/*******************************************************************************
   Получить параметры JS-файла
*******************************************************************************/  
function parseUrlQuery() {
    var data = {}
        ,   pair = false
        ,   param = false;
	var jsfile = '';
	jQuery('script[src]').each (function(){
		jsfile = jQuery(this).attr('src');
		if (jsfile && jsfile.indexOf('bg_bibrefs.js') > -1) {
			jsfile = jsfile.substr(jsfile.indexOf('?'));
			pair = (jsfile.substr(1)).split('&');
			for(var i = 0; i < pair.length; i ++) {
				param = pair[i].split('=');
				data[param[0]] = param[1];
			}
			return;
		}
	});
    return data;
}
