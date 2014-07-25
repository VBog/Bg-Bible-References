<?php 
include_once (realpath (substr(__FILE__, 0, strpos(__FILE__, 'wp-content')).'wp-blog-header.php'));
include_once ('../includes/quotes.php');
echo bg_bibfers_getQuotes($_GET["title"], $_GET["chapter"], $_GET["type"]);