<?php 
// Получить данные
	$title = $_GET["title"];
	if (!$title) die();
	$chapter = $_GET["chapter"];
	if (!$chapter) $chapter = '1-99';
	$type = $_GET["type"];
	if (!$type) $type = 'verses';
// Подключаем WP и данные плагина	
	include_once (realpath (substr(__FILE__, 0, strpos(__FILE__, 'wp-content')).'wp-blog-header.php'));
	include_once ('../../includes/quotes.php');
    $class_val = get_option( 'bg_bibfers_class' );
	if ($class_val == "") $class_val = 'bg_bibfers';
// Формируем страницу
?>
<?php get_header();		// Заголовок	?>
	<div id="content">	
		<span class='<?php echo $class_val ?>'><?php echo bg_bibfers_getQuotes($title, $chapter, $type) ?></span>
	</div>
<?php get_footer();		// Подвал		?>