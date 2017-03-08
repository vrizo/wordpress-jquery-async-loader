=== jQuery & Plugins Asynchronous Loader ===
Contributors: killbill-sbor
Tags: jquery, loader, above the fold, optimization, pagespeed, insights, minify, jquery is undefined, render blocking
Requires at least: 4.1
Tested up to: 4.7.2
Stable tag: trunk
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin asynchronously loads jQuery and its plugins without "jQuery is undefined" errors.

== Description ==

Loading of JavaScript resources should be done asynchronously, in a non-blocking manner, so the load time of your webpage will not be affected (it would not be delayed by render-blocking JavaScript). But using of deferring or async loading of jQuery causes lots of problems with jQuery plugins. This plugin replaces default Wordpress's JS loader with a special jQuery loader (jQl), that's why there are no any errors with jQuery plugins.

It's highly recommended to use any concatenate plugin, e.g., Fast Velocity Minify or WP Fastest Cache. Please, do not add jQuery to the ignore list, because this plugin fixes “undefined jQuery” errors on the console log. Also, it is allowed to use defer or async parsing of JS files.

It requires Wordpress 4.1 and newer.

It is available in English and Russian.


== Installation ==

1. Install the plugin using FTP or Wordpress Dashboard.
1. Activate it.
1. Optionally you can disable jQuery CDN replacing on Settings &rarr; jQuery Async Loader page.

It's highly recommended to use any concatenate plugin, e.g., Fast Velocity Minify or WP Fastest Cache. Please, do not add jQuery to the ignore list, because this plugin fixes “undefined jQuery” errors on the console log. Also, it is allowed to use defer or async parsing of JS files.

== Frequently Asked Questions ==

= How it works? =
1. The plugin loads small JavaScript code in the header. This is jQl developed by Cédric Morin (jQuery async loader);
1. Then it loads minified jQuery from Google CDN (optional, you can disable it on settings page);
1. And finally, it replaces all <script> tags with a special function.

= I see scripts loaded through <script> tag, is it OK? =
Yes, that's okay for Google Analytics, Facebook pixel, Twitter tag and many other external scripts.
But please make sure that all your theme and plugins scripts are included correctly through wp_enqueue_script or similar functions

== Screenshots ==

1. jQuery & Plugins Async Loader Settings page

== Changelog ==

= 1.1 =
* Added admin pages processing option
* Russian translation

= 1.0 =
* Initial release