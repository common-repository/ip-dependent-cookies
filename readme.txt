=== IP Dependent Cookies ===
Contributors: v-media
Donate link: http://v-media.cz/ip-dependent-cookies/
Tags: cookies, safety, auth, security
Requires at least: 2.9
Tested up to: 4.4.2
Stable tag: trunk

Plugin IP Dependent Cookies makes your Wordpress installation more secure adding your IP to salt (which makes cookies IP-dependent).

== Description ==

Each time you login to your blog WordPress creates a session cookie which is used to authenticate you.
By default if someone somehow gets your cookies he (or she) is able to use them to compromise your blog
(even without having to know your password!). To prevent this you may want to make your auth cookies
ip-dependent so that they could be valid only for that ip which you used during login.

Use this plugin only if you have a static IP or dynamic which doesn't change too often. Otherwise, you'll
have to enter your login and password each time your IP changes.

== Installation ==

To install the plugin follow these steps:

   1. Download the ip-dependent-cookies.zip file to your local machine.
   1. Unzip the file
   1. Upload "ip-dependent-cookies" folder to the "/wp-content/plugins/" directory
   1. Activate the plugin through the 'Plugins' menu in WordPress
   1. Go to plugin settings and enable the plugin.
   1. You will be prompted to log in again. Do so. This is necessary to set the new cookie.

== Changelog ==

= 1.2.1 =
* Still works with the most recent version of WordPress

= 1.2 =
* Changed: code refactoring
* Added: plugin made translatable

= 1.1 =
* Added: quick link to settings from the plugins list
* Added: nag when a plugin is not enabled

= 1.0 =
* First public release

