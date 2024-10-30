<?php
/*
Plugin Name: IP Dependent Cookies
Plugin URI: http://v-media.cz/ip-dependent-cookies/
Description: Plugin IP Dependent Cookies makes your Wordpress installation more secure adding your IP to salt. Even if someone steals your cookies he (or she) won't be able to use them.
Version: 1.2.1
Author: Artprima
Author URI: http://artprima.cz/
License: This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
*/

if ( ! defined( 'ABSPATH' ) )
    die();

if ( ! defined( 'WP_CONTENT_URL' ) )
    define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
    define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
    define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

require_once( WP_PLUGIN_DIR . '/ip-dependent-cookies/ip-dependent-cookies.class.php');

if ( ! defined( 'IPDC_PLUGIN_FULL_PATH' ) )
    define( 'IPDC_PLUGIN_FULL_PATH', __FILE__ );

if ( ! defined( 'IPDC_PLUGIN_BASENAME' ) )
    define( 'IPDC_PLUGIN_BASENAME', plugin_basename( IPDC_PLUGIN_FULL_PATH ) );

if ( ! defined( 'IPDC_PLUGIN_MENU_PARENT' ) )
    define( 'IPDC_PLUGIN_MENU_PARENT', 'options-general.php' );

if ( ! defined( 'IPDC_PLUGIN_SETTINGS_URL' ) )
    define( 'IPDC_PLUGIN_SETTINGS_URL', admin_url(IPDC_PLUGIN_MENU_PARENT . '?page=' . IPDC_PLUGIN_BASENAME) );

$ip_dependent_cookies = new IPDependentCookies();
