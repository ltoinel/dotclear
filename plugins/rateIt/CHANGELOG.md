rateIt 2.2.2 - 2016-11-07
=========================
* Fix issue that prevent to customize rateit.html template in theme.

rateIt 2.2.1 - 2016-11-04
=========================
* Fix issue with mysql db layer (use mysqli instead of mysql)

rateIt 2.2.0 - 2015-03-09
=========================
* A great thanks to Pierre Van Glabeke for following changes:
* Add options for widgets
* Update help (need more translations)
* Update admin pages to match dotclear 2.6
* Update icons
* Add link to favorite
* Fix filter for events

rateIt 2.1.0 - 2015-03-07
=========================
* Upgrade jquery rating plugin
* Add test to be sure to have jquery 1.10 at least

rateIt 2.0.0 - 2014-12-09
=========================
 * Not added ability to explode post type rating in subcategories
 * Not added rating on other post_type (see plugin muppet)
 * Not added public API
 * Not completed modules behaviors (delete/edit category, tag...)
 * Update jquery.rating plugin

2.0-beta1 20101120
 * Fixed install on nightly build
 * Added plural on rateItTotal (closes #605)

2.0-alpha6 20100828
 * Added rate on event (plugin eventHandler)
 * Opened to other post_type
 * Fixed bug on image path

2.0-alpha5 20100707
 * Fixed core call in module post
 * Fixed cast on pgsql vs mysql

2.0-alpha4 20100703
 * Fixed attributes 'type' and 'style' to rateIt in templates
 * Added condition for 'style' to rateItIf in templates

2.0-alpha3 20100701
 * Fixed filename

2.0-alpha2 20100625
 * Fixed (hope so) postgreSQL compatibility
 * Fixed high note on 'double' style
 * Fixed admin crash on non 2.2

2.0-alpha 20100613
 * Switched to DC 2.2
 * Added simple ratting mode
 * Added double rating mode
 * Fixed JS bug on multiple rating forms
 * Fixed IE8 bug (by cleaning html errors!)
 * Changed modules management (and behaviors)
 * Included rating on plugin cinecturlink2

1.1 20100307
 * Fixed bug for votes without javascript

1.0 20100131
 * Fixed bug with rate by cookie
 * Added option to exclude one category for post rate
 * Added option to limit to one category on widget rateItRank

0.9.7.2 20100130
 * Fixed issue on widget with other plugin that used coreBlogGetPosts
 * Fixed http header on file serving
 * Fixed limitation to current blog on some requests

0.9.7.1 20091109
 * Fixed missing settings for widget and image

0.9.7 20091108
 * Added entryFirstImage to wigdet
 * Fixed bug with date sorting on widget
 * Fixed bug with plugin ativityReport
 * Removed about tabs and fixed typo

0.9.6 20090923
 * Fixed DC 2.1.6 URL handlers ending
 * Added sort by sort rate by date (thanks to lottie14)
 * Cleaned some stuff

0.9.5 20090915
 * Fixed erreur 1071 in some MySQL servers
 * Added type ''meta'' equal to type ''tag''
 * Added support for plugin activityReport
 * Fixed typo

0.9.4 20090903
 * Fixed CSS features (validation, option, file length)
 * Fixed import/export behaviors
 * Added option to disable import/export in about:config
 * Added _uninstall.php support

0.9.3 20090820
 * Fixed context errors
 * Added cut string option on widget
 * Added attribute support for ''rateItIf'' and ''rateItTotal''
 * Removed uninstall functions
 * Removed urls edition as dcUrlHandlers work great for this
 * Changed, grouped, reordered class
 * Included ''tag'' and ''gallery''

0.9 20090816
 * Fixed php 5.3 compatibility
 * Included ''comment'' and ''category''
 * Changed javascript/jquery rating functions
 * Added ability to use rate bloc everywhere in templates
 * Changed structure of index.php
 * Fixed missing conditions everywhere

0.8.2 20090725
 * Fixed search for existing templates, css ans js on public side
 * Fixed widget conflict with same rateIt type
 * Fixed some l10n
 * Fixed titles bug on multiple rateit type

0.8.1 20090719:
 * Fixed errors on dates
 * Added summary tabs
 * Remove jquery.cookie.js as it be in default theme

0.8 20090715
 * Fixed sort order on widget
 * Fixed voting on navigator whitout js enable
 * Fixed setting for vote on post
 * Changed uses of common library for image path
 * Removed uses of constant for url prefix
 * Added js features on admin tabs
 * Cleaned code

0.7 20090714:
 * Fixed ''image path'' on multiblog
 * Added ''url prefix'' option

0.6 20090710:
 * Fixed post_type bugs
 * Added "enable" options for posts on some templates
 * Added "limit to one category" for posts

0.5 20090709:
 * Fixed postgresql compatibility

0.4 20090703:
 * changed database rateit_id to allow text id
 * Fixed some js bugs
 * Fixed translation
 * Added option to edit thank msg
 * Added cookie and/or ip option
 * Added images choice
 * Added some options to widget
 * Added extension ability
 * Added import/export support

0.3 20090625:
 * Fixed jquery rating system

0.2 20090621:
 * First public release
