<?php 
include ('../includes/quotes.php');
echo bg_bibfers_getQuotes($_GET["title"], $_GET["chapter"], $_GET["type"]);

