<?php

/*
Plugin Name: Lead-Tracking-System	
Description: This is a lead Tracking plugin.Saves Data from Contact form to Database & sends mail to admin. Also Tracks Referral link & saves in database as well as mail.Please read usage.txt on how to use this plugin.
Plugin URI: http://wisdmlabs.com
Version: 1.0
License: GPLv2
Author: WisdmLabs
Author URI: http://wisdmlabs.com
Network: true
Text Domain: WPCF7


"Lead-Tracking-System"
Copyright (C) 2012  Wisdmlabs

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define( 'WPCF7_VERSION', '1.0' );

define( 'WPCF7_REQUIRED_WP_VERSION', '3.3.1' );

if ( ! defined( 'WPCF7_PLUGIN_BASENAME' ) )
	define( 'WPCF7_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'WPCF7_PLUGIN_NAME' ) )
	define( 'WPCF7_PLUGIN_NAME', trim( dirname( WPCF7_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'WPCF7_PLUGIN_DIR' ) )
	define( 'WPCF7_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . WPCF7_PLUGIN_NAME );

if ( ! defined( 'WPCF7_PLUGIN_URL' ) )
	define( 'WPCF7_PLUGIN_URL', WP_PLUGIN_URL . '/' . WPCF7_PLUGIN_NAME );

if ( ! defined( 'WPCF7_PLUGIN_MODULES_DIR' ) )
	define( 'WPCF7_PLUGIN_MODULES_DIR', WPCF7_PLUGIN_DIR . '/modules' );

if ( ! defined( 'WPCF7_LOAD_JS' ) )
	define( 'WPCF7_LOAD_JS', true );

if ( ! defined( 'WPCF7_LOAD_CSS' ) )
	define( 'WPCF7_LOAD_CSS', true );

if ( ! defined( 'WPCF7_AUTOP' ) )
	define( 'WPCF7_AUTOP', true );

if ( ! defined( 'WPCF7_USE_PIPE' ) )
	define( 'WPCF7_USE_PIPE', true );

/* If you or your client hate to see about donation, set this value false. */
if ( ! defined( 'WPCF7_SHOW_DONATION_LINK' ) )
	define( 'WPCF7_SHOW_DONATION_LINK', false );

if ( ! defined( 'WPCF7_ADMIN_READ_CAPABILITY' ) )
	define( 'WPCF7_ADMIN_READ_CAPABILITY', 'edit_posts' );

if ( ! defined( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY' ) )
	define( 'WPCF7_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );

if ( ! defined( 'WPCF7_VERIFY_NONCE' ) )
	define( 'WPCF7_VERIFY_NONCE', false);

/*require_once WPCF7_PLUGIN_DIR . '/settings.php';*/
require_once  WP_PLUGIN_DIR. '/lead-tracking-system/settings.php';

require_once WP_PLUGIN_DIR . '/lead-tracking-system/Lead-Tracking-database-extension/Lead-Tracking-database-extension.php';

require_once WP_PLUGIN_DIR . '/lead-tracking-system/custom-functions/extension.php';
?>
