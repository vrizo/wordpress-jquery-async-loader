# Wordpress jQuery & plugins Async Loader
This plugin asynchronously load jQuery and plugins without "jQuery is undefined" errors.

Loading of JavaScript resources should be done asynchronously, in a non-blocking manner, so the load time of your webpage will not be affected. But using of deferring or async loading of jQuery causes lots of problems with jQuery plugins. This plugin replaces default Wordpress's JS loader with a special jQuery loader (jQl), that's why there are no any errors with jQuery plugins.
Maintained by [me](https://www.upwork.com/freelancers/~019842b9db9697a094).

It's highly recommended to use any concatenate plugin, e.g., Fast Velocity Minify or WP Fastest Cache. Please, do not add jQuery to the ignore list, because this plugin fixes “undefined jQuery” errors on the console log. Also, it is allowed to use defer or async parsing of JS files.

It requires Wordpress 4.1 and newer.

It is available in English :gb:/:us: only for now.


## Wordpress Plugin Directory
* Not yet.

## How to use
1. Install the plugin using FTP or Wordpress Dashboard.
2. Activate it.
3. Optionally you can disable jQuery CDN replacing on Settings &rarr; jQuery Async Loader page.

## How it works?
1. The plugin loads small JavaScript code in the header. This is [jQl developed by Cédric Morin](https://github.com/Cerdic/jQl) (jQuery async loader);
2. Then it loads minified jQuery from Google CDN (optional, you can disable it on settings page);
3. And finally it replaces all <script> tags with a special function.

## Screenshot
<img src="/assets/screenshot-1.png" align="center" height="582" width="897" alt="jQuery Async Loader Settings Page" >

## Having any troubles or ideas?
Please contact [me by email](mailto:kb@kernel-it.ru).

## Changelog
v. 1.0
* Initial release