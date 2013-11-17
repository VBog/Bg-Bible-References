=== Bg Bible References ===

Contributors: Vadim Bogaiskov

Donate link: http://bogaiskov.ru/about-me/donate/

Tags: bible, orthodoxy, Christianity, Библия, православие, христианство, Священное Писание, Завет, ορθοδοξία, χριστιανισμός, Αγία Γραφή

Requires at least: 3.0.1

Tested up to: 3.6.1

Stable tag: trunk

License: GPLv2

License URI: http://www.gnu.org/licenses/gpl-2.0.html


...will highlight references to the Bible text with links to site "The Alphabet of Faith".


== Description ==

Russian:

Плагин подсвечивает ссылки на текст Библии с помощью гиперссылок на сайт Православной энциклопедии "Азбука веры" (http://azbyka.ru/biblia). 
Текст Библии представлен на церковнославянском, русском, греческом, еврейском и латинском языках. 

Плагин обрабатывает ссылки следующего формата:

* (Ин. 3:16), где «Ин.» - это название книги, 3 - это глава, а 16 - это номер стиха;
* (Ин. 3:16—18) (Книга. Глава: с этого [—] по этот стих);
* (Ин. 3:16—18, 21, 34—36) (Книга. Глава: с этого [—] по этот стих, этот стих, с этого [—] по этот стих);
* (Ин. 3:16—18, 4:4—6) (Книга. Глава: с этого [—] по этот стих, глава: с этого [—] по этот стих);
* (Мф. 5—6) (Книга. С этой [—] по эту главу). 

Допускается указание ссылок в квадратных скобках и без точки после наименования книги. 
При указании номера главы (сразу после названия книги) можно использовать запятую вместо двоеточия. 
Пробелы игнорируются.

В настройках плагина Вы можете выбрать языки, на которых будет отображаться текст Библии: церковно-славянский, русский, греческий, латинский и иврит.
Для церковно-славянского языка можно также выбрать шрифт: церковно-славянский шрифт, русские буквы ("старый" стиль) или HIP-стандарт.
Вы также можете указать, где открывать страницу с текстом Библии - в новом или текущем окне.
Для настройки вида ссылок используйте класс bg_bibrefs. Вы можете изменить имя класса в настройках.

В версии 2.0 при наведении курсора мыши на ссылку отображается всплывающая подсказка  содержащая стихи Библии (только на русском языке). 
Файлы книг Библии взяты с сайта patriarchia.ru и теперь поставляется вместе с плагином. 
При отключении этой опции вместо стихов отображается номер главы и номера стихов.

Шорт-код [bible book='Mt' ch='2:3-6' type='verses'] выводит цитаты из Библии в тексте страницы.
Здесь: book – обозначение книги (латинскими буквами), ch – номера глав и стихов, type – формат вывода.
Если  type=’book’, то отображаются наименование книги, заголовки глав и стихи, каждый отдельным абзацем, с указанием его номера.
Если  type=’verses’, то отображаются только стихи, каждый отдельным абзацем, с указанием номера главы и номера стиха.
Если  type=’b_verses’, то отображаются только стихи, каждый отдельным абзацем, с указанием короткого названия книги, номера главы и номера стиха.
Если  type=’quote’, то отображаются только стихи без деления их на абзацы.

English: 

The plugin will highlight references to the Bible text with links to site of Orthodox encyclopedia "The Alphabet of Faith" (http://azbyka.ru/biblia).
The Bible is presented in Church, Russian, Greek, Hebrew and Latin. 

The plugin handles the references with the format:

* (Ин. 3:16), where «Ин.» - book title, 3 - chapter, а 16 - verse number;
* (Ин. 3:16—18) (Book. Chapter: from this verse [—] till this verse);
* (Ин. 3:16—18, 21, 34—36) (Book. Chapter: from this verse [—] till this verse, this verse, from this verse [—] till this verse);
* (Ин. 3:16—18, 4:4—6) (Book. Chapter: from this verse [—] till this verse, chapter: from this verse [—] till this verse);
* (Мф. 5—6) (Book. From this chapter [—] till this chapter). 

You can specify the reference in brackets and without a point after the title of the book. 
If you specify a chapter (after the title of the book), you can use comma instead of colon.
Spaces are ignored.

In the plugin settings you can select the languages ​​in which the text will be displayed Bible: Church Slavic, Russian, Greek, Latin and Hebrew.
For the Church Slavonic language, you can also select a font: Church Slavic font, Russian letters (the "old" style) or HIP-standard.
You can also specify where to open a page with the Bible text  - in new or current window.
To customize the appearance of reference  links, use class bg_bibrefs. You can change the class name in the settings.

In version 2.0, when you hover your mouse over the link displayed tooltip containing the Bible verses (only in Russian).
Bible E-books are taken from the site patriarchia.ru and now comes with the plugin.
If you disable this option, the number of the chapter and verse numbers will displayed instead of verses.

Short code [bible book = 'Mt' ch = '2 :3-6 'type =' verses'] displays quotes from the Bible in the text of the page.
Here: book - the designation of the book (Latin letters), ch - numbers of chapters and verses, type - the output format.
If type = 'book', it displays the name of the book, chapter and verse in separate paragraph with verse number.
If type = 'verses', it displays only the verses in separate paragraph with chapter number and verse number.
If type = 'b_verses', it displays only the verses in separate paragraph with short book title, chapter number and verse number.
If type = 'quote', it displays only the verses without dividing them into paragraphs.

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
2. Page of Bible verses

== Changelog ==

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

= 2.3.2 =

* Some PHP E~Notices fixed and some corrections for compliance to Wordpress coding standards (Thanks, versusbassz)

= 2.3.1 =

Allowed point instead of a colon (In.3.16-18) after first chapter number.

= 2.3.0 =

* Allowed to use references without brackets.

= 2.2.1 =

* Added type='b_verses' in Short code [bible].

= 2.2.0 =

* Short code [bible book='Mt' ch='2:3-6 'type='verses'] displays quotes from the Bible in the text.

= 2.1.1 =

* Fixed some bugs.

= 2.1.0 =

* Fastest display page. The tooltip is created when you hover the cursor over it only. jQuery  must be available.

= 2.0.0 =

* In version 2.0, when you hover your mouse over the link displayed tooltip containing the Bible verses (only in Russian).

= 1.0.0 =

* The first stable version.

= 0.12 =

* Plugin internationalization (i18n). Added possibility translate the plugin to your native language.

= 0.11 =

* Added new options: choose target to open Bible page and links class name.

= 0.10 =

* Fixed minor bugs in i18n

= 0.9 =

* Added Plugin's options.

= 0.8 =

* The problem of using internal script in content is solved radically. Parsing algorithm of references was rewrite in PHP. Highly recommended upgrade.

= 0.7 =

* If a script  is integrated in the content , the conflict may appear collaboration. 
In the case with Yandex Maps conflict disappears when to start our script immediately after the output of the content on display.
I'm afraid that is incomplete solution, and in other cases the conflict may emerge. :(

= 0.6 =

* Conflict with other plugins: $content was not filtered. Error fixed. Upgrade immediately.

= 0.5 =

* Development of plugins. Fixed of errors detected.

= 0.4 =

* Development of plugins. Fixed of errors detected.

= 0.3 =

* Enhance feature. Fixed of errors detected. Upgrade immediately.

= 0.2 =

* Enhance feature. Fixed of errors detected. Upgrade immediately.


== Notes for Translators ==

You can translate this plugin using POT-file in languages folder with program PoEdit (http://www.poedit.net/). 
More in detail about translation WordPress plugins, see "Translating WordPress" (http://codex.wordpress.org/Translating_WordPress).

Note on the translation abbreviations of the books of the Bible:

* You can specify multiple alternatives of the abbreviations. To do this, separate them using the symbol |, for example: Mt|Mtw|Matth (Gospel of Matthew).

* If the abbreviation include the punctuation marks (point, comma, dash, etc.) escape them using a double slash (Let.Jer => Let\\\\.Jer). 
There are rules for PHP regular expressions (http://en.wikipedia.org/wiki/Regular_expression).

* Abbreviation should not contain spaces.

* Note that in the English language abbreviation and title of the books "Jab" and "Joel" are the same. Translate them according to the context (an abbreviation first, then the title).

Send me your PO-files. I will insert them in plugin in next version.

== License ==

GNU General Public License v2

