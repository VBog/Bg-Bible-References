=== Bg Bible References ===

Contributors: VBog

Donate link: http://bogaiskov.ru/about-me/donate/

Tags: bible, orthodoxy, Christianity, Библия, православие, христианство, Священное Писание, Завет, ορθοδοξία, χριστιανισμός, Αγία Γραφή

Requires at least: 3.0.1

Tested up to: 4.5.3

Stable tag: trunk

License: GPLv2

License URI: http://www.gnu.org/licenses/gpl-2.0.html


...will highlight the Bible references with hyperlinks to the Bible text and interpretation by the Holy Fathers.

== Description ==

### Описание плагина

[see also English description below]

Плагин подсвечивает ссылки на текст Библии с помощью гиперссылок на сайт Православной энциклопедии "Азбука веры" (http://azbyka.ru/biblia). 
Текст Библии представлен на церковнославянском, русском, греческом, еврейском и латинском языках. 
В настройках Вы можете переопределить эти гиперссылки на свой собственный сайт или даже отключить их.
А также на толкования Святого Писания Святыми отцами и Учителями Церкви на сайте монастыря Оптина Пустынь (http://bible.optina.ru).
В настройках Вы также можете включить отображение ссылок на параллельные места в Библии. Ссылки на параллельные места указаны в конце каждого стиха в фигурных скобках.

Плагин обрабатывает ссылки следующего формата:

* (Ин. 3:16), где «Ин.» - это название книги, 3 - это глава, а 16 - это номер стиха;
* (Ин. 3:16—18) (Книга. Глава: с этого [—] по этот стих);
* (Ин. 3:16—18, 21, 34—36) (Книга. Глава: с этого [—] по этот стих, этот стих, с этого [—] по этот стих);
* (Ин. 3:16—18, 4:4—6) (Книга. Глава: с этого [—] по этот стих, глава: с этого [—] по этот стих);
* (Ин. 3:16—4:6) (Книга. Глава: этот стих [—] по глава: этот стих);
* (Мф. 5—6) (Книга. С этой [—] по эту главу). 

Допускается указание ссылок в квадратных скобках и без точки после наименования книги. 
При указании номера главы (сразу после названия книги) можно использовать запятую вместо двоеточия. 
Также допускается указывать несколько книг вподряд,используя точку с запятой в качестве разделителя (см.: Зах.4; 2Кор.1:21; 1Ин.2:27).
Плагин поддерживает старую нотацию с римскими цифрами в обозначении книг и глав.
Пробелы игнорируются.

В настройках плагина Вы можете выбрать языки, на которых будет отображаться текст Библии: церковно-славянский, русский, греческий, латинский и иврит.
Для церковно-славянского языка можно также выбрать шрифт: церковно-славянский шрифт, русские буквы ("старый" стиль) или HIP-стандарт.
Вы также можете указать, где открывать страницу с текстом Библии - в новом или текущем окне.
Для настройки вида ссылок используйте класс bg_bibrefs. Вы можете изменить имя класса в настройках.

При наведении курсора мыши на ссылку отображается всплывающая подсказка содержащая стихи Библии. Вы можете выбрать язык отображения подсказки: 

* церковно-славянский (Елизаветинская Библия)
* русский (Синодальный перевод)
* украинский (перевод И.И.Огиенко)
* белорусский (Новый Завет в переводе Библейской Комиссии при Белорусском Экзархате)
* английский (King James Version)

При отключении этой опции вместо стихов отображается номер главы и номера стихов.
Вы можете выбрать язык отображения стихов Библии в настройках, по умолчанию устанавливается язык WP. 
Если Вам необходимо переопределить язык для отдельной заметки, создайте для заметки произвольное поле с именем bible_lang  и присвойте ему двухбуквенное обозначение языка. 
В настройках можно включить опцию позволяющую показывать оригинальные номера стихов в скобках после номера стихов русского Синодального перевода в подсказках и цитатах.
Стихи, помеченные звездочкой * отсутствуют в оригинальном переводе. * - отображаются всегда!

Для чтения файлов Библии используются PHP  file_get_contents() или fopen() или cURL. 
Плагин пытается загружать файлы Библии этими методами в указанном порядке. 
Чтобы сделать загрузку более быстрой отключите лишние методы. 
Предупреждение: Некоторые методы могут быть недоступны на Вашем сервере. 
Если Вы разместили свой блог на медленном сервере попробуйте опцию "Предварительная загрузка стихов из Библии во всплывающие подсказки". 
Предупреждение: Вы можете иметь проблемы с ограничениями Ajax-запросов на сервере.

Если Вы включите опцию 'Преобразовывать ссылки к нормализованному виду', то ссылки на Библию будут заключены в круглые скобки, в них будут удалены лишние пробелы, а названия книг, обозначения глав и стихов будут приведены к стандартному виду, соответствующему восточной традиции.

Самозакрывающийся шорт-код [bible book='Mt' ch='2:3-6' type='verses' lang='ru' prll='on' /] выводит цитаты из Библии в тексте страницы.
Здесь: book – обозначение книги, ch – номера глав и стихов, type – формат вывода, lang - язык текста Библии (по умолчанию, язык Библии поста), prll='on'|'off' - вкл.|выкл. отображение ссылок на паралельные места в Библии.
Допускается вместо book и ch указать полную ссылку в параметре ref. В этом случае параметры book и ch игнорируются. 
Если ref='rnd', то выводится случайная цитата из перечня, представленного в файле quotes.txt. 
Если ref='days', то выводится цитата дня в соответствии с порядковым номером дня в текущем году. 
Если ref - любое целое число, то выводится цитата, с соответствующим порядковым номером. 
Вы можете также создать свой собственный перечень цитат в отдельном текстовом файле, разделяя ссылки любыми пробельными символами (пробел, табуляция, перевод строки и т.д.) и указать имя этого файла в настройках плагина.
Если  type=’book’, то отображаются наименование книги, заголовки глав и стихи, каждый отдельным абзацем, с указанием его номера.
Если  type=’verses’, то отображаются только стихи, каждый отдельным абзацем, с указанием номера главы и номера стиха.
Если  type=’b_verses’, то отображаются только стихи, каждый отдельным абзацем, с указанием короткого названия книги, номера главы и номера стиха.
Если  type=’t_verses’, то отображаются наименование книги и стихи, каждый отдельным абзацем, с указанием номера главы и номера стиха.
Если  type=’quote’, то отображаются только стихи без деления их на абзацы.
Если  type=’link’, то отображается ссылка на Библию.
Ограждающий шорт-код [bible type='verses' lang='ru']...[/bible] преобразует все ссылки в содержимом в цитаты из Библии.
При этом параметры book и ch игнорируются. 

Шорт-код [bible_epigraph ref='rnd' lang='ru'] выводит на экран цитату в форме эпиграфа. 

Шорт-код [bible_search context='Отче наш' type='verses' lang='ru' prll='on' /] выводит на экран результаты поиска указанной в параметре 'context' фразы. В поисковом запросе можно использовать специальные символы: "$" - 1 любая буква, "%" - 0 или 1 любая буква, "*" - несколько любых букв.

Шорт-код [bible_omnisearch lang='ru' page='{URL}'/] выводит на экран элемент формы, позволяющий пользователю вводить поисковый запрос (см. [bible_search]). В параметре 'page' необходимо указать URL страницы, на которой будут выведены результаты поиска (указан шорт-код [bible_search]). Если параметр 'page' не задан, то подразумевается эта же страница.

Шорт-код [references type='list' separator=', ' list='o' col=1 /] выводит список ссылок на Библию, встречающиеся в статье.
Здесь: type – формат отображения списка (по умолчанию 'list').
Если  type=’string’, то список отображается в виде строки в тегах `<p>...</p>`, при этом используется дополнительный параметр 'separator', в котором указывается разделитель между ссылками (по умолчанию запятая и пробел).
Если  type=’list’, то список отображается в виде списка, при этом если дополнительному параметру 'list' присвоено значение 'u', то это ненумерованный список, а если 'o', то - нумерованный(по умолчанию 'o'). В дополнительном параметре 'col' указывается количество колонок(по умолчанию 1). 
Если  type=’table’, то список отображается в виде таблицы, в дополнительном параметре 'col' указывается количество колонок(по умолчанию 1). Список ссылок выводится в таблице построчно.
Список ссылок выводится на экран в контейнере `<div class=”bg_refs_list”>…</div>`. Используйте класс bg_refs_list для задания свойств объектов списка.

Шорт-код [norefs]...[/norefs] запрещает подсветку гиперссылок на Библию в тексте, ограниченном этим шорт-кодом. 
Если необходимо запретить подсветку в заметке в целом создайте рубрику или метку с ярлыком norefs и поместите заметку в эту рубрику/метку, или создайте для заметки произвольное поле с именем norefs  и присвойте ему произвольное значение.

Чтобы выделить текст стихов в подсказке для последующего копирования в буфер обмена просто щелките мышью в области стихов, например, по номеру стиха. 
А затем используйте Ctrl+C или контекстное меню для копирования.

Плагин содержит 3 виджета: 
* Виджет "Ссылки на Библию" позволяет разместить в сайдбаре форму для вывода текста цитат из Библии на странице Вашего сайта.
* Виджет "Поиск в Библии" позволяет разместить в сайдбаре форму для поиска слова или фразы Библии. 
* Виджет "Цитата из Библии" выводит в сайдбаре Случайную Цитату или Цитату Дня из Библии, аналогично тому, как это делает шорт-код [bible_epigraph].

Настройки плагина включают файловый менеджер для библейских книг. Вы можете добавлять и удалять папки с книгами Библии на вашем сайте.

Вы также можете получать отрывки из Библии от внешнего AJAX Proxy. Введите путь к внешнему AJAX Proxy (например, http://my-ajax-server.com/wp-admin/admin-ajax.php) в настройках.

Кроме того, добавьте в *functions.php* на этом сервере следующий PHP-код:
`function allow_origin () {
    header ( "Access-Control-Allow-Origin: http://my-site1.com " );
    header ( "Access-Control-Allow-Origin: http://my-site2.com " );
	...
    header ( "Access-Control-Allow-Origin: http://my-siteN.com " );
}
add_action ( "init", "allow_origin" );`



### English plugin discription

The plugin will highlight references to the Bible text with links to site of Orthodox encyclopedia "The Alphabet of Faith" (http://azbyka.ru/biblia).
The Bible is presented in Church, Russian, Greek, Hebrew and Latin.
In the settings, you can redirect the hyperlink to your own website, or even turn off them.
And also on the interpretation of Holy Scripture by the Holy Fathers and Doctors of the Church on the site of Optina Pustyn monastery (http://bible.optina.ru).
In the settings you can also choose to display references to parallel passages in the Bible. Links to parallel passages are listed at the end of each verse in the curly brackets.

The plugin handles the references with the format:

* (Ин. 3:16), where «Ин.» - book title, 3 - chapter, а 16 - verse number;
* (Ин. 3:16—18) (Book. Chapter: from this verse [—] till this verse);
* (Ин. 3:16—18, 21, 34—36) (Book. Chapter: from this verse [—] till this verse, this verse, from this verse [—] till this verse);
* (Ин. 3:16—18, 4:4—6) (Book. Chapter: from this verse [—] till this verse, chapter: from this verse [—] till this verse);
* (Ин. 3:16—4:6) (Book. Chapter: this verse [—] till chapter: this verse);
* (Мф. 5—6) (Book. From this chapter [—] till this chapter). 

You can specify the reference in brackets and without a point after the title of the book. 
If you specify a chapter (after the title of the book), you can use comma instead of colon.
Also you can specify a few books in row using semicolon as the separator (см.: Зах.4; 2Кор.1:21; 1Ин.2:27).
The plugin supports the old notation with Roman numerals in naming books and in chapters.
Spaces are ignored.

In the plugin settings you can select the languages ​​in which the text will be displayed Bible: Church Slavic, Russian, Greek, Latin and Hebrew.
For the Church Slavonic language, you can also select a font: Church Slavic font, Russian letters (the "old" style) or HIP-standard.
You can also specify where to open a page with the Bible text  - in new or current window.
To customize the appearance of reference  links, use class bg_bibrefs. You can change the class name in the settings.

When you hover your mouse over the link displayed tooltip containing the Bible verses. You can select the language for tooltip: 

* Church Slavic (Elizabeth Bible) 
* Russian (Synodal translation) 
* Ukrainian (translation by I.I.Ogienko) 
* Belarusian (New Testament translated by Biblical Commission of the Belarusian Exarchate) 
* English (King James Version)

If you disable this option, the number of the chapter and verse numbers will displayed instead of verses.
You can change the language for display of the Bible verses in the settings (default - language WP). 
If you need to override the language for a single post, create a custom field for the post with a name bible_lang  and set it two-letter language code.
In the settings you can turn on the option allows you to show the original verse numbers in parentheses after the verse numbers of Russian Synodal Translation in the tooltips and quotes.
Verses marked with asterisk '*' are absent in the original translation. '*' - always visible!

To read Bible files used PHP-functions cURL or file_get_contents() or fopen(). 
Plugin tries to read Bible files with one of this methods in the order listed.
To do the reading faster, disable unnecessary methods in settings - you need one only. 
Warning: Some methods may not be available on your server.
If you placed your blog on a slow server try the option "Preload Bible verses in tooltips". 
Warning: you can have problem with ajax-requests limiting on the server.

If you enable the 'Convert References to the normalized form', the Bible references will within brackets, there are removed extra spaces, and the book titles, chapters and verses will be have the standard form corresponding to the Eastern tradition.

Self-closing shortcode [bible book = 'Mt' ch = '2 :3-6 'type =' verses' lang='ru' prll='on' /] displays quotes from the Bible in the text of the page.
Here: book - the designation of the book, ch - numbers of chapters and verses, type - the output format, lang - language of the Bible, prll='on'|'off' - turn on|off links to parallel passages in the Bible.
Allowed instead of 'book' and 'ch' specify the full reference  in the parameter 'ref'. In this case, the parameters 'book' and 'ch' are ignored.
If ref = 'rnd', it displays a random quote from the list in the file quotes.txt.
If ref = 'days', it displays the quote of the day according with the serial number of the day in the current year.
If ref - any integer, it displays a quote with the appropriate serial number.
Also you can create your own list of quotes in a text file, by separating references with any white spaces (space, tab, newline, etc.), and then specify filename in the plugin settings.
If type = 'book', it displays the name of the book, chapter and verse in separate paragraph with verse number.
If type = 'verses', it displays only the verses in separate paragraph with chapter number and verse number.
If type = 'b_verses', it displays only the verses in separate paragraph with short book title, chapter number and verse number.
If type = ’t_verses’, it displays the name of the book and verse in separate paragraph with chapter number and verse number.
If type = 'quote', it displays only the verses without dividing them into paragraphs.
If type=’link’, it displays Bible reference.
Enclosing shortcode [bible type = 'verses' lang='ru'] ... [/ bible] converts all references in content to quotes from the Bible. 
The parameters of the book and ch ignored.

Shortcode [bible_epigraph ref = 'rnd' lang = 'ru'] displays the quote in the format of epigraph.

Shortcode [bible_search context = 'Our Father' type = 'verses' lang = 'ru' prll='on' /] displays the search results of phrase (parameter 'context'). In the search query you can use wildcards: "$" - 1 аny letter, "%" - 0 or 1 аny letter, "*" - more any letters.

Shortcode [bible_omnisearch lang = 'ru' page = '{URL}' /] displays a form element, allowing the user to enter a search query (see: [Bible_search]). In the parameter 'page' you must specify the URL of the page where search results will be displayed (specified shortcode [bible_search]). If the parameter 'page' is not set, it means the same page.

Shortcode [references type = 'list' separator = ',' list = 'o' col = 1 /] displays list of Bible references are finded in the article.
Here : type - list display format(default 'list').
If type = 'string', the list is displayed as string in tags `<p>...</p>`, the additional parameter 'separator' contains the separator between the references (default comma and space).
If type = 'list', the list is displayed as list, if the additional parameter 'list' is set to 'u', then this is an unordered list, and if it is 'o', then - numbered (default 'o'). Аdditional parameter 'col' specifies the number of columns (default 1).
If type = 'table', the list is displayed as table, additional parameter 'col' specifies the number of columns (default 1). List of links displayed in the table by line.
A list of references displayed in the container `<div class="bg_refs_list"> ... </div>`. Use bg_refs_list class to set the properties of list objects.

Shortcode [norefs]...[/norefs] prohibits highlighting the Bible references in the text enclosed with this shortcode.
If you want to disable highlight of Bible references in the whole post, create a category or tag  with a label norefs and place a post in this category/tag, or create custom field with name norefs for this post and set it any value.

To select verses text in tooltip for later copying to the clipboard click the left mouse button in the verse field, for example, on verse number.
And then use Ctrl+C or the context menu for copy.

The plugin contains 3 widgets:
* Bible References Widget allows you to place the form in the sidebar to display Bible quotes on the page of your site. 
* Bible Search Widget allows you to place the form in the sidebar to search for words or phrases in the Bible.
* Bible Quote Widget in the sidebar displays a Random Quote or Day's Quote from the Bible the same way as it makes the shortcode [bible_epigraph].

Plugin settings include the file manager for Bible books. You can add and delete folders with Bible books on your site.

You can receive Bible verses from external AJAX Proxy. Enter path to external AJAX Proxy (e.g. http://my-ajax-server.com/wp-admin/admin-ajax.php) in settings. 

Also add into *functions.php* on this server the following PHP-code
`function allow_origin () {
    header ( "Access-Control-Allow-Origin: http://my-site1.com " );
    header ( "Access-Control-Allow-Origin: http://my-site2.com " );
	...
    header ( "Access-Control-Allow-Origin: http://my-siteN.com " );
}
add_action ( "init", "allow_origin" );`


== Installation ==

1. Upload 'bg-biblie-references' directory to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= How do I know that the script works? =

Any references to Bible verses on your page will be replaced by hyperlink.

= Why can't I use a script? =

* Make sure that your browser supports JavaScript, and JavaScript enabled in your browser.
* Verify that the reference to the Bible is framed in accordance with the generally accepted rules.

== Screenshots ==

1. An example work of the plugin - highlight references
2. Expanded tooltip.
3. Page of Bible verses
4. Interpretation of Holy Scripture
5. Search in the Bible

== Changelog ==

= 3.12.2-4 =

* Fixed bugs.

= 3.12.1 =

* Added links to interpretation by Lopuchin on the site azbyka.ru
* Fixed bugs.

= 3.12.0 =

* Added file manager for Bible books.
* Now you can receive Bible verses from external AJAX Proxy.
* Functions -  analogs of the shortcodes: `bg_bibrefs_bible ( (string) $ref='', (string) $book='', (string) $ch='1-999', (string) $type='verses', (string) $lang='',(string)  $prll='' )` and `bg_bibrefs_bible_epigraph ( (string) $ref='rnd', (string) $lang='' )`.

= 3.11.7 =

* Plugin validates the references. Invalid references marks as errors.
* Added additional classes for display verses: bg_bibrefs_<type>.
* Changed delay to 0.2 sec.

= 3.11.6 =

* Added delay in 0.5 sec. when you hover over the link to avoid loading theBible books, if you accidentally moved the cursor around the screen.
* Added object cache for downloaded books.
* Added option which allows you to upload Bible verses in links when plugin parse of the text (before display it on the screen).
* Added option to set the maximum execution time.

= 3.11.5 =

* Now plugin interface is available in the Belarusian language. Many thanks to Basil Matveev.

= 3.11.4 =

* Now you can easily add any edition of the Bible.

= 3.11.3 =

* Added missing in KJV apocryphal verses (Prov.4:28-29; Prov.13:14; Prov.18:8; Ps.151) from Brenton's Septuagint.

= 3.11.2 =

* Added apocryphal books and chapters and missing verses from the King James Bible (KJV). 

= 3.11.1 =

* Fixed minor bugs. 

= 3.11.0 =

* Fixed old typos: bg_bibfers to bg_bibrefs (in function names, in option names, in text domain neme, in class names, etc.).
* Fixed minor bugs. 

= 3.10.5 =

* Fixed bug on settings page.

= 3.10.4 =

* Now plugin highlight the Bible references in the post summary (field "Excerpt", see https://codex.wordpress.org/Excerpt).

= 3.10.3 =

* Added 'Hebrews' in Belarusian language.
* Fixed minor bugs

= 3.10.2 =

* Added parameter 'prll' in shortcodes [bible] and [bible_search].
* Fixed minor bugs

= 3.10.1 =

* Fixed critical bug

= 3.10.0 =

* You can redirect the hyperlinks of references to your own website, or even turn off them.
* You can turn on references to parallel passages in the Bible.

= 3.9.0 =

* Added shortcode [bible_omnisearch].
* Improved support for multilingual sites.
* Multilingual support of Readings in the plugin Bg Orthodox Calendar.

= 3.8.0 =

* Added "Bible Search" widget and shortcode [bible_search].

= 3.7.1 =

* Bug fixed.

= 3.7.0 =

* Added "Bible References" widget and "Bible Quote" widget.
* New plugin's site http://wp-bible.info .
* Fixed code of Church Slavonic language from "sc" to "cu" (in according with ISO 639-1). Please make the changes to your website!
* Bug fixed.

= 3.6.1 =

* Bug fixed.

= 3.6.0 =

* A random quote and quote of day.
* Added shortcode [bible_epigraph].
* Added three books of the New Testament in the Belarusian language.

= 3.5.3 =

* Now you can define container (div ID or body tag), inside which will display tooltips.

= 3.5.2 =

* Added books of New Testament in Belarusian language.

= 3.5.1 =

* Added Kihg James Bible (English language).

= 3.5 =

* The plugin supports the old notation with Roman numerals in naming books and in chapters.
* Allow semicolons in the numbering of the chapters and verses.
* Minor bugs fixed.

= 3.4.1 =

* Optimized algorithm of main loop. Parsing of posts became faster.
* Bugs fixed.
* Debug option.

= 3.4 =

* To add a language, you can add a folder with the books into directory  'bible' of the plugin.
* Added support for Cyrillic characters ёіїєґўЁІЇЄҐЎ.
* Values ​​of custom fields 'bible_lang' and 'norefs' you can select on edit page of posts and pages.
* Added Bible in Church Slavonic language.

= 3.3 =

* Added Four Gospels in the Belarusian language.

= 3.2 =

* Added option "Show original verse numbers". 
* Now disabled highlighting of Bible references in links (tag <a>).

= 3.1 =

* Bible in multiple languages in  blog and posts. 
* Button expand/hide now at left.
* Minor bugs fixed.

= 3.0 =

* Multilanguage Bible verses in tooltip. 
* Added Ukrainian language.

= 2.10.1 =

* Bug fixed.

= 2.10 =

* Added enclosing shortcode [bible]...[/bible].
* Allowed references like (Ин. 3:16—4:6).

= 2.9.1-3 =

* Minor bugs fixed.

= 2.9.0 =

* New view of tooltips.
* Now you can expand tooltip for easy reading.
* Selection of text in tooltip for copying to the clipboard.
* Hyphen and other similar symbols (#8208-#8213) instead of the dash (#45) allowed to use in references.
* Fixed minor bugs.

= 2.8.0 =

* Added short-code [norefs]...[/norefs].
* Option to disable highlighting of Bible references in the whole post.
* List of Bible references (short code [references]) displays in "normalized form" without repeats. 

= 2.7.0 =

* Added option "Highlight references in the headers H1...H6". Thank you very much, Pozharko Andrej, for good idea.
* Added option "Convert references to the normalized form". 
* For short code [references type="list"] specified additional parameter 'col' - the number of columns.
* Now you can specify a few books in row using semicolon as the separator (см.: Зах.4; 2Кор.1:21; 1Ин.2:27).

= 2.6.0 =

* Added short code [references] - displays quotes from the Bible in the text of the page. Thanks Pozharko Andrej for idea.

= 2.5.6 =

* Fixed a bug where on the page, there are some similar references without brackets, the shortest link was displayed in all places.

= 2.5.5 =

* To read Bible files used PHP-functions cURL or file_get_contents() or fopen(). 
Plugin tries to read Bible files with one of this methods in the order listed.
To do the reading faster, disable unnecessary methods in settings - you need one only. 
Warning: Some methods may not be available on your server.

= 2.5.4 =

* To read Bible files used PHP-functions cURL or file_get_contents(). Check the availability of these features on your server.

= 2.5.3 =

* Allowed to use a semicolon instead of a comma in references.
* Added some new abbreviations of the Bible books.
* Some minor fixing.

= 2.5.2 =

* Added option "Preload Bible verses in tooltips". Try this option on a slow server, but can be problem with ajax-requests limiting on the server.
* Fixed bug when WP files was placed  isn't the root directory.

= 2.5.1 =

* Removed debug code AJAX.

= 2.5.0 =

* Added links to the interpretation of Holy Scripture on the site of the monastery Optina Pustyn (http://bible.optina.ru).
* Allowed use in shortcode any valid (not only Latin) abbreviation of Bible books.

= 2.4.5 =

* Some improvements of tooltip, incl. FireFox bug.

= 2.4.4 =

* Fixed bug. WP didn't allow visitors that are not logged in to see verses in the tooltip.

= 2.4.3 =

* Fixed upload errors. 

= 2.4.0 =

* New algoritm to get and display Bible verses. 
* Added type='t_verses' in shortcode [bible].

= 2.3.5 =

* Fixed bugs. 

= 2.3.4 =

* Algorithm optimization. 

= 2.3.3 =

* Fixed mp3 eq. [Мр.3] error (Lat/Cyr mixing). 
* Other minor fixing.(Thanks, versusbassz)

= 2.3.2 =

* Some PHP E~Notices fixed and some corrections for compliance to Wordpress coding standards (Thanks, versusbassz)

= 2.3.1 =

* Allowed point instead of a colon (In.3.16-18) after first chapter number.

= 2.3.0 =

* Allowed to use references without brackets.

= 2.2.1 =

* Added type='b_verses' in Short code [bible].

= 2.2.0 =

* Short code [bible book='Mt' ch='2:3-6 'type='verses'] displays quotes from the Bible in the text.

= 2.1.1 =

* Fixed some bugs.

= 2.1.0 =

* Fastest display page. The tooltip is created when you hover the cursor over it only.

= 2.0.0 =

* When you hover your mouse over the link displayed tooltip containing the Bible verses (only in Russian).

= 1.0.0 =

* The first stable version.

= 0.12 =

* Plugin internationalization (i18n).

= 0.11 =

* Added new options

= 0.10 =

* Fixed minor bugs in i18n

= 0.9 =

* Added Plugin's options

= 0.8 =

* Parsing algorithm of references was rewrite in PHP

= 0.7 =

* Trying to solve the conflict with the built-in script Yandex Maps

= 0.6 =

* Fixed a bug causing a conflict with other plugins

= 0.5 =

* Allowed см.:(see) just after the opening bracket. Options: см.: | см. | см: | см
* New: Pop-up window when you specify the cursor on the link contains the full title of the book of the Bible
* Fixed some bugs. 

= 0.4 =

* Major changed algorithm. Now available complex references, such as (Ин. 3:16—18, 4:4—6)
* Added new abbreviations 

= 0.3 =

* Optimized algorithm, added format of reference in brackets
* Script are uploaded before in footer </body> tag. Loading of the script is independent the availability of wp_head and wp_footer in WP theme

= 0.2 =

* Added references  (Ин.1:4,12-15), (Ин 1:4,12-15).
* Script are uploaded in the <head> section now (before in footer)

= 0.1 =

* Plugin in beta testing mode

== Upgrade Notice ==

= 3.12.2-4 =

* Fixed bugs.


== Notes for Translators ==

You can translate this plugin using POT-file in languages folder with program PoEdit (http://www.poedit.net/). 
More in detail about translation WordPress plugins, see "Translating WordPress" (http://codex.wordpress.org/Translating_WordPress).

Send me your PO-files. I will insert them in plugin in next version.

== License ==

GNU General Public License v2

