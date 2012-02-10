<?php
/*
Plugin Name: Gravity Forms Authenticate User Registration
Plugin URI: 
Description: This is a secondary layer to "force" a user registration to verify their account which will move them from one account role to another. Dependancies: Gravity Forms & Gravity Forms User Registration Add-On.
Version: 1.0
Author: Timothy Wood (@codearachnid)
Author URI: http://www.codearachnid.com	
Author Email: tim@imaginesimplicity.com
License:

  Copyright 2011 Imagine Simplicity (tim@imaginesimplicity.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/
function gfauthenticateuser_init(){

	// Build virtual page
	add_rewrite_rule( 'validate-user/([^/]+)/?$', 'index.php?pagename=validate-user&public_key=$matches[1]', 'top' );
	add_action( 'wp_loaded', 'gfauthenticateuser_flush_rules' );
	add_filter( 'query_vars', 'gfauthenticateuser_set_query_vars' );
	add_action( 'parse_request', 'gfauthenticateuser_parse_request' );

	// Setup Gravity Forms hidden field value	
	add_action( 'gform_field_value_public_key', 'gfauthenticateuser_set_public_key' );
	
	// Parse shortcode on validation page
	add_shortcode( 'gfauthenticateuser', 'gfauthenticateuser_shortcode' );
}
add_action( 'init', 'gfauthenticateuser_init' ); 

function gfauthenticateuser_flush_rules() {
	$rules = get_option( 'rewrite_rules' );
	if ( ! isset( $rules[ 'validate-user/([^/]+)/?$' ] ) ) {
		if ( function_exists( 'flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
		} else {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}
}

function gfauthenticateuser_set_query_vars( $vars ) {
	array_push( $vars, 'public_key' );
	return $vars;
}

function gfauthenticateuser_parse_request( $wp ) {
	if ( array_key_exists( 'public_key', $wp->query_vars ) )
		DEFINE( 'GFAUTHUSER_PK', $wp->query_vars['public_key'] );
}

function gfauthenticateuser_set_public_key( $value ) {
	return sha1( AUTH_KEY . md5( uniqid( rand(), true ) ) );
}

function gfauthenticateuser_shortcode( $atts ){
   extract( shortcode_atts( array(
	  'fail' => 'Please try again.',
	  'success' => 'Your account is now validated!',
	  'setrole' => 'subscriber',
	  'currentrole' => 'subscriber',
	  ), $atts ) );
	$pk = GFAUTHUSER_PK;
	if($pk != 'GFAUTHUSER_PK') {
		global $wpdb;
		$UID = $wpdb->get_var( $wpdb->prepare( "SELECT um.user_id FROM {$wpdb->prefix}usermeta um WHERE um.meta_key = 'entry_id' AND um.meta_value = (SELECT gf.lead_id FROM {$wpdb->prefix}rg_lead_detail gf WHERE value = '{$pk}');" ) );
		if( $UID ) {
			$user = new WP_User( $UID );
			$user->remove_role( $currentrole );
			$user->add_role( $setrole );
			return $success;
		} else {
			return $fail;
		}
	} else {
		return $fail;
	}
}