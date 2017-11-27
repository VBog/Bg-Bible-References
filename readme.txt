=== Bg Bible References ===

Contributors: VBog
Donate link: http://bogaiskov.ru/about-me/donate/
Tags: bible, orthodoxy, Christianity, Библия, православие, христианство, Священное Писание, Завет, ορθοδοξία, χριστιανισμός, Αγία Γραφή
Requires PHP: 5.3
Requires at least: 3.0.1
Tested up to: 4.9.0
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html


...will highlight the Bible references with hyperlinks to the Bible text and interpretation by the Holy Fathers.

== Description ==

`[see also Russian description below]`

* The plugin will highlight references to the Bible text with links.
* Parallel passages in the Bible.
* Interpretation of Holy Scripture by the Holy Fathers and Doctors of the Church.
* Convert references to normalized form.
* Excerpts from the Bible in the article.
* Random quote and quote of the day.
* List of bible references contented in the article.
* You can add and delete folders with Bible books on your site.
* To customize the appearance of reference  links, use class bg_bibrefs.

### Bible in 5 languages: 
* Church Slavic (Elizabeth Bible) 
* Russian (Synodal translation) 
* Ukrainian (translation by I.I.Ogienko) 
* Belarusian (New Testament translated by Biblical Commission of the Belarusian Exarchate) 
* English (King James Version)

### Shortcode:
* `[bible]` displays quotes from the Bible in the text of the page.
* `[bible_epigraph]` displays the quote in the format of epigraph.
* `[bible_search]` displays the search results of phrase (parameter 'context'). In the search query you can use wildcards: "$" - 1 аny letter, "%" - 0 or 1 аny letter, "*" - more any letters.
* `[bible_omnisearch]` displays a form element, allowing the user to enter a search query (see: [Bible_search]).
* `[references]` displays list of Bible references are finded in the article.
* `[norefs]...[/norefs]` prohibits highlighting the Bible references in the text enclosed with this shortcode.

### The plugin contains 3 widgets:
* *Bible References Widget* allows you to place the form in the sidebar to display Bible quotes on the page of your site. 
* *Bible Search Widget* allows you to place the form in the sidebar to search for words or phrases in the Bible.
* *Bible Quote Widget* in the sidebar displays a Random Quote or Day's Quote from the Bible the same way as it makes the shortcode `[bible_epigraph]`.

-----

## Описание плагина

* Плагин подсвечивает ссылки на текст Библии с помощью гиперссылок.
* Параллельные места в Библии.
* Толкования Святого Писания Святыми отцами и Учителями Церкви.
* Преобразование ссылок к нормализованному виду.
* Отрывки из Библии в тексте статьи.
* Случайная цитата и Цитата дня.
* Список библейских ссылок в статье.
* Вы можете добавлять и удалять папки с книгами Библии на вашем сайте.
* Для настройки вида ссылок используйте класс bg_bibrefs.

### Текст Библии на 5 языках: 
* церковно-славянский (Елизаветинская Библия)
* русский (Синодальный перевод)
* украинский (перевод И.И.Огиенко)
* белорусский (Новый Завет в переводе Библейской Комиссии при Белорусском Экзархате)
* английский (King James Version)

### Шорт-коды:
* `[bible]` выводит цитаты из Библии в тексте страницы.
* `[bible_epigraph]` выводит на экран цитату в форме эпиграфа. 
* `[bible_search]` выводит на экран результаты поиска фразы в Библии. В поисковом запросе можно использовать специальные символы: "$" - 1 любая буква, "%" - 0 или 1 любая буква, "*" - несколько любых букв.
* `[bible_omnisearch]` выводит на экран элемент формы, позволяющий пользователю вводить поисковый запрос. 
* `[references]` выводит список ссылок на Библию, встречающиеся в статье.
* `[norefs]...[/norefs]` запрещает подсветку гиперссылок на Библию в тексте, ограниченном этим шорт-кодом. 

### Плагин содержит 3 виджета: 
* Виджет *"Ссылки на Библию"* позволяет разместить в сайдбаре форму для вывода текста цитат из Библии на странице Вашего сайта.
* Виджет *"Поиск в Библии"* позволяет разместить в сайдбаре форму для поиска слова или фразы Библии. 
* Виджет *"Цитата из Библии"* выводит в сайдбаре Случайную Цитату или Цитату Дня из Библии, аналогично тому, как это делает шорт-код `[bible_epigraph]`.

-----

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

= 3.13.1 =

* In the book "Psalms" the word "Chapter" is replaced by "Psalm".

= 3.13.0 =

* Added options that allow you to adjust the permissible deviations from the Eastern tradition in Bible references.

= 3.12 =

* Added option to disable links to interpretations.
* Allow shortcodes into `[bible]...[/bible]` tags.
* Added links to interpretation by Lopuchin on the site azbyka.ru
* Added file manager for Bible books.
* Now you can receive Bible verses from external AJAX Proxy.
* Functions -  analogs of the shortcodes: `bg_bibrefs_bible ( (string) $ref='', (string) $book='', (string) $ch='1-999', (string) $type='verses', (string) $lang='',(string)  $prll='' )` and `bg_bibrefs_bible_epigraph ( (string) $ref='rnd', (string) $lang='' )`.

= 3.11 =

* Plugin validates the references. Invalid references marks as errors.
* Added additional classes for display verses: `bg_bibrefs_<type>`.
* Added delay in 0.2 sec. when you hover over the link to avoid loading theBible books, if you accidentally moved the cursor around the screen.
* Added object cache for downloaded books.
* Added option which allows you to upload Bible verses in links when plugin parse of the text (before display it on the screen).
* Added option to set the maximum execution time.
* Now plugin interface is available in the Belarusian language. Many thanks to Basil Matveev.
* Now you can easily add any edition of the Bible.
* Added missing in KJV apocryphal verses (Prov.4:28-29; Prov.13:14; Prov.18:8; Ps.151) from Brenton's Septuagint.
* Added apocryphal books and chapters and missing verses from the King James Bible (KJV). 
* Fixed old typos: bg_bibfers to bg_bibrefs (in function names, in option names, in text domain neme, in class names, etc.).

= 3.10 =

* Now plugin highlight the Bible references in the post summary (field "Excerpt", see https://codex.wordpress.org/Excerpt).
* Added 'Hebrews' in Belarusian language.
* Added parameter 'prll' in shortcodes `[bible]` and `[bible_search]`.
* You can redirect the hyperlinks of references to your own website, or even turn off them.
* You can turn on references to parallel passages in the Bible.

= 3.9 =

* Added shortcode `[bible_omnisearch]`.
* Improved support for multilingual sites.
* Multilingual support of Readings in the plugin Bg Orthodox Calendar.

= 3.8 =

* Added "Bible Search" widget and shortcode `[bible_search]`.

= 3.7 =

* Added "Bible References" widget and "Bible Quote" widget.
* New plugin's site http://wp-bible.info .
* Fixed code of Church Slavonic language from "sc" to "cu" (in according with ISO 639-1). Please make the changes to your website!

= 3.6 =

* A random quote and quote of day.
* Added shortcode `[bible_epigraph]`.
* Added three books of the New Testament in the Belarusian language.

= 3.5 =

* Now you can define container (div ID or body tag), inside which will display tooltips.
* Added books of New Testament in Belarusian language.
* Added Kihg James Bible (English language).
* The plugin supports the old notation with Roman numerals in naming books and in chapters.
* Allow semicolons in the numbering of the chapters and verses.
* Minor bugs fixed.

= 3.4 =

* Optimized algorithm of main loop. Parsing of posts became faster.
* Debug option.
* To add a language, you can add a folder with the books into directory  'bible' of the plugin.
* Added support for Cyrillic characters ёіїєґўЁІЇЄҐЎ.
* Values ​​of custom fields 'bible_lang' and 'norefs' you can select on edit page of posts and pages.
* Added Bible in Church Slavonic language.

= 3.3 =

* Added Four Gospels in the Belarusian language.

= 3.2 =

* Added option "Show original verse numbers". 
* Now disabled highlighting of Bible references in links (tag `<a>`).

= 3.1 =

* Bible in multiple languages in  blog and posts. 
* Button expand/hide now at left.
* Minor bugs fixed.

= 3.0 =

* Multilanguage Bible verses in tooltip. 
* Added Ukrainian language.

= 2.10 =

* Added enclosing shortcode `[bible]...[/bible]`.
* Allowed references like (Ин. 3:16—4:6).

= 2.9 =

* New view of tooltips.
* Now you can expand tooltip for easy reading.
* Selection of text in tooltip for copying to the clipboard.
* Hyphen and other similar symbols (#8208-#8213) instead of the dash (#45) allowed to use in references.

= 2.8 =

* Added short-code `[norefs]...[/norefs]`.
* Option to disable highlighting of Bible references in the whole post.
* List of Bible references (short code `[references]`) displays in "normalized form" without repeats. 

= 2.7 =

* Added option "Highlight references in the headers H1...H6". Thank you very much, Pozharko Andrej, for good idea.
* Added option "Convert references to the normalized form". 
* For short code `[references type="list"]` specified additional parameter 'col' - the number of columns.
* Now you can specify a few books in row using semicolon as the separator (см.: Зах.4; 2Кор.1:21; 1Ин.2:27).

= 2.6 =

* Added short code `[references]` - displays quotes from the Bible in the text of the page. Thanks Pozharko Andrej for idea.

= 2.5 =

* To read Bible files used PHP-functions `cURL` or `file_get_contents()` or `fopen()`. 
Plugin tries to read Bible files with one of this methods in the order listed.
To do the reading faster, disable unnecessary methods in settings - you need one only. 
Warning: Some methods may not be available on your server.
* Allowed to use a semicolon instead of a comma in references.
* Added some new abbreviations of the Bible books.
* Added option "Preload Bible verses in tooltips". Try this option on a slow server, but can be problem with ajax-requests limiting on the server.
* Added links to the interpretation of Holy Scripture on the site of the monastery Optina Pustyn (http://bible.optina.ru).
* Allowed use in shortcode any valid (not only Latin) abbreviation of Bible books.

= 2.4 =

* New algoritm to get and display Bible verses. 
* Added type='t_verses' in shortcode `[bible]`.

= 2.3 =

* Algorithm optimization. 
* Fixed mp3 eq. `[Мр.3]` error (Lat/Cyr mixing). 
* Other minor fixing.(Thanks, versusbassz)
* Some PHP E~Notices fixed and some corrections for compliance to Wordpress coding standards (Thanks, versusbassz)
* Allowed point instead of a colon (In.3.16-18) after first chapter number.
* Allowed to use references without brackets.

= 2.2 =

* Added type='b_verses' in Short code `[bible]`.
* Short code `[bible book='Mt' ch='2:3-6 'type='verses']` displays quotes from the Bible in the text.

= 2.1 =

* Fastest display page. The tooltip is created when you hover the cursor over it only.

= 2.0 =

* When you hover your mouse over the link displayed tooltip containing the Bible verses (only in Russian).

= 1.0 =

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
* Script are uploaded before in footer `</body>` tag. Loading of the script is independent the availability of wp_head and wp_footer in WP theme

= 0.2 =

* Added references  (Ин.1:4,12-15), (Ин 1:4,12-15).
* Script are uploaded in the `<head>` section now (before in footer)

= 0.1 =

* Plugin in beta testing mode

== Upgrade Notice ==

= 3.13.1 =

* In the book "Psalms" the word "Chapter" is replaced by "Psalm".

== Notes for Translators ==

You can translate this plugin using POT-file in languages folder with program PoEdit (http://www.poedit.net/). 
More in detail about translation WordPress plugins, see "Translating WordPress" (http://codex.wordpress.org/Translating_WordPress).

Send me your PO-files. I will insert them in plugin in next version.

== License ==

GNU General Public License v2

