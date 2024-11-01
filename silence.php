<?php 
/*
Plugin Name: Silence!
Plugin URI: http://silence.alandawi.com.ar
Description: This plugin disables the WordPress update notifications, as well as update notifications of different plugins. It is ideal to deliver a complete WordPress and not bother the user with alerts. Silence!
Version: 1.5
Author: Alan Gabriel Dawidowicz
Author URI: http://www.alandawi.com.ar
	
	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/


if (get_option('check1') == true) {
	add_action( 'init', create_function( '$silence_core', "remove_action( 'init', 'wp_version_check' );" ), 2 );
	add_filter( 'pre_option_update_core', create_function( '$silence_core', "return null;" ) );
	remove_action( 'wp_version_check', 'wp_version_check' );
	remove_action( 'admin_init', '_maybe_update_core' );
	add_filter( 'pre_transient_update_core', create_function( '$silence_core', "return null;" ) );
	add_filter( 'pre_site_transient_update_core', create_function( '$silence_core', "return null;" ) );
}


if (get_option('check2') == true) {
	add_action( 'admin_menu', create_function( '$silence_plugin', "remove_action( 'load-plugins.php', 'wp_update_plugins' );") );
	add_action( 'admin_init', create_function( '$silence_plugin', "remove_action( 'admin_init', 'wp_update_plugins' );"), 2 );
	add_action( 'init', create_function( '$silence_plugin', "remove_action( 'init', 'wp_update_plugins' );"), 2 );
	add_filter( 'pre_option_update_plugins', create_function( '$silence_plugin', "return null;" ) );

	remove_action( 'load-plugins.php', 'wp_update_plugins' );
	remove_action( 'load-update.php', 'wp_update_plugins' );
	remove_action( 'admin_init', '_maybe_update_plugins' );
	remove_action( 'wp_update_plugins', 'wp_update_plugins' );
	add_filter( 'pre_transient_update_plugins', create_function( '$silence_plugin', "return null;" ) );

	remove_action( 'load-update-core.php', 'wp_update_plugins' );
	add_filter( 'pre_site_transient_update_plugins', create_function( '$silence_plugin', "return null;" ) );
}
?>
<?php

add_action('admin_menu', 'silence_create_menu');

function silence_create_menu() {
	add_menu_page('Silence Options', 'Silence Options', 'administrator', __FILE__, 'silence_settings_page',plugins_url('/img/generic.png', __FILE__));

	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	register_setting( 'silence-settings', 'check1' );
	register_setting( 'silence-settings', 'check2' );
}

function silence_settings_page() {
?>
<div class="wrap">
<h2>Silence! Options</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'silence-settings' ); ?>
    <?php do_settings_sections( 'silence-settings' ); ?>
    <table class="form-table">

        <tr valign="top">
        <th scope="row" style="width: 250px;">Enable / Disable notifications WordPress</th>
        <td>
        	<input type="checkbox" name="check1" value="false"<?php if (get_option('check1')==true) echo 'checked="checked" '; ?> />
		</td>
        </tr>


        <tr valign="top">
        <th scope="row" style="width: 250px;">Enable / Disable notifications Plugins</th>
        <td>
        	<input type="checkbox" name="check2" value="false"<?php if (get_option('check2')==true) echo 'checked="checked" '; ?> />
		</td>
        </tr>

    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="Save Changes" />
    </p>

</form>
</div>
<?php } ?>