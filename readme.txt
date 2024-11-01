=== WP Similar Basic Auth ===
Contributors: 256hax
Tags: password, login, security, auth, basic auth, .htaccess, kusanagi
Donate link: none
Requires at least: 5.0
Tested up to: 5.7.2
Requires PHP: 5.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Protect WordPress admin page on similar Basic Auth without .htaccess.

== Description ==

Attackers trying to breakthrough WordPress admin page. Basic Authentication helps to prevent attacks.
But some case it can't modify .htaccess or ssl.conf.

This plugin is useful for servers where prohibition modify Apache conf(.htaccess) or Nginx conf(ssl.conf).
Protect WordPress admin page on similar Basic Auth. It doesn't need .htaccess or ssl.conf.

= Features =

* Auth log in with User Name and Password.
* Customization title and message in Login page.

= Note =

This plugin doesn't replace Basic Authentication. If you can modify .htaccess or ssl.conf, I recommend using that. See differences running layer.

= Running Layer =

Fronts-end (ex: CSS, Javascript)
Application Plugin **<- This plugin**
Application (ex: WordPress)
Programming language (ex: PHP)
Middleware Web (ex: Apache, Nginx) **<- .htaccess Basic Authentication**
Middleware DB / OS

== Installation ==

1. **Visit** Plugins > Add New in your WordPress admin page.
2. **Search** for "WP Similar Basic Auth".
3. Click **Install Now** then **Activate** plugin.
4. **Configure** User Name and Password in Settings > WP Similar Basic Auth.

= Note =

* Staring WP Similar Basic Auth in WordPress admin page after settings User Name and Password.
* Enable cookie and JavaScript on your computer.

== Frequently Asked Questions ==

= I forgot User Name and Password. =
Try to following steps.

1. Delete wp-similar-basic-auth in your WordPress plugins directory.
(ex: [WordPress Installation Path]/wp-content/plugins/wp-similar-basic-auth)
2. Log in to your WordPress admin page.
3. Reinstall WP Similar Basic Auth. (See Installation instructions)
4. Configure new User Name and Password in the "WP Similar Basic Auth" settings.

= Can I read password? =
No. WP Similar Basic Auth plugin use hash function.

= Why doesn't ask log in again? =
"Remember logging in" for several weeks if you successful WP Similar Basic Auth log in page.
Ask log in again after log out WordPress admin page or several weeks.

= How can I log out WP Similar Basic Auth? =
Log out WordPress admin page.

= How can I stop? =
Deactivate in the WordPress plugins menu.

= Can I change the WP Similar Basic Auth log in page theme? =
No. WP Similar Basic Auth plugin automatically load your WordPress theme.
But you can customize style by CSS. Use 'div id="wsba"'.

== Screenshots ==

1. Log in page.
2. Log in page wtih title and message.
3. Settings.

== Changelog ==

See the [release history](https://github.com/256hax/wp-similar-basic-auth/releases).
