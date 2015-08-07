<?php
/*
Plugin Name: Contact Form
Plugin URI: http://bestwebsoft.com/products/
Description: Plugin for Contact Form.
Author: BestWebSoft
Version: 3.89
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*  @ Copyright 2015  BestWebSoft  ( http://support.bestwebsoft.com )

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

/**
* Add Wordpress page 'bws_plugins' and sub-page of this plugin to admin-panel.
* @return void
*/
if ( ! function_exists( 'cntctfrm_admin_menu' ) ) {
	function cntctfrm_admin_menu() {
		bws_add_general_menu( plugin_basename( __FILE__ ) );
		add_submenu_page( 'bws_plugins', __( 'Contact Form Settings', 'contact_form' ), __( 'Contact Form', 'contact_form' ), 'manage_options', 'contact_form.php', 'cntctfrm_settings_page' );
	}
}

if ( ! function_exists ( 'cntctfrm_init' ) ) {
	function cntctfrm_init() {
		global $bws_plugin_info, $cntctfrm_plugin_info;
		/* Internationalization, first(!) */
		if ( ! session_id() )
			@session_start();

		load_plugin_textdomain( 'contact_form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_functions.php' );
		
		if ( empty( $cntctfrm_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$cntctfrm_plugin_info = get_plugin_data( __FILE__ );
		}
		/* Function check if plugin is compatible with current WP version  */
		bws_wp_version_check( plugin_basename( __FILE__ ), $cntctfrm_plugin_info, "3.1" );

		if ( ! is_admin() )
			cntctfrm_check_and_send();
	}
}

if ( ! function_exists ( 'cntctfrm_admin_init' ) ) {
	function cntctfrm_admin_init() {
		global $bws_plugin_info, $cntctfrm_plugin_info;
		/* Add variable for bws_menu */

		if ( ! isset( $bws_plugin_info ) || empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '77', 'version' => $cntctfrm_plugin_info["Version"] );

		/* Call register settings function */
		if ( isset( $_REQUEST['page'] ) && ( 'contact_form.php' == $_REQUEST['page'] ) )
			cntctfrm_settings();
	}
}

/* Register settings for plugin */
if ( ! function_exists( 'cntctfrm_settings' ) ) {
	function cntctfrm_settings() {
		global $cntctfrm_options, $cntctfrm_option_defaults, $cntctfrm_plugin_info;
		$cntctfrm_db_version = "1.0";

		if ( ! $cntctfrm_plugin_info )
			$cntctfrm_plugin_info = get_plugin_data( __FILE__ );

		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		$from_email = 'wordpress@' . $sitename;

		$cntctfrm_option_defaults = array(
			'plugin_option_version' 			=> $cntctfrm_plugin_info["Version"],
			'plugin_db_version' 				=> $cntctfrm_db_version,
			'cntctfrm_user_email'				=> 'admin',
			'cntctfrm_custom_email'				=> '',
			'cntctfrm_select_email'				=> 'user',
			'cntctfrm_from_email'				=> 'custom',
			'cntctfrm_custom_from_email'		=> $from_email,
			'cntctfrm_attachment'				=> 0,
			'cntctfrm_attachment_explanations'	=> 1,
			'cntctfrm_send_copy'				=> 0,
			'cntctfrm_from_field'				=> get_bloginfo( 'name' ),
			'cntctfrm_select_from_field'		=> 'custom',
			'cntctfrm_display_name_field'		=> 1,
			'cntctfrm_display_address_field' 	=> 0,
			'cntctfrm_display_phone_field' 		=> 0,
			'cntctfrm_required_name_field' 		=> 1,
			'cntctfrm_required_address_field' 	=> 0,
			'cntctfrm_required_email_field' 	=> 1,
			'cntctfrm_required_phone_field' 	=> 0,
			'cntctfrm_required_subject_field' 	=> 1,
			'cntctfrm_required_message_field' 	=> 1,
			'cntctfrm_required_symbol'			=> '*',
			'cntctfrm_display_add_info' 		=> 1,
			'cntctfrm_display_sent_from' 		=> 1,
			'cntctfrm_display_date_time' 		=> 1,
			'cntctfrm_mail_method' 				=> 'wp-mail',
			'cntctfrm_display_coming_from' 		=> 1,
			'cntctfrm_display_user_agent' 		=> 1,
			'cntctfrm_language'					=> array(),
			'cntctfrm_change_label'				=> 0,
			'cntctfrm_name_label' 				=> array( 'en' => __( "Name:", 'contact_form' ) ),
			'cntctfrm_address_label' 			=> array( 'en' => __( "Address:", 'contact_form' ) ),
			'cntctfrm_email_label' 				=> array( 'en' => __( "Email Address:", 'contact_form' ) ),
			'cntctfrm_phone_label' 				=> array( 'en' => __( "Phone number:", 'contact_form' ) ),
			'cntctfrm_subject_label' 			=> array( 'en' => __( "Subject:", 'contact_form' ) ),
			'cntctfrm_message_label' 			=> array( 'en' => __( "Message:", 'contact_form' ) ),
			'cntctfrm_attachment_label'			=> array( 'en' => __( "Attachment:", 'contact_form' ) ),
			'cntctfrm_attachment_tooltip'		=> array( 'en' => __( "Supported file types: HTML, TXT, CSS, GIF, PNG, JPEG, JPG, TIFF, BMP, AI, EPS, PS, RTF, PDF, DOC, DOCX, XLS, ZIP, RAR, WAV, MP3, PPT. Max file size: 2MB", 'contact_form' ) ),
			'cntctfrm_send_copy_label'			=> array( 'en' => __( "Send me a copy", 'contact_form' ) ),
			'cntctfrm_submit_label'				=> array( 'en' => __( "Submit", 'contact_form' ) ),
			'cntctfrm_name_error' 				=> array( 'en' => __( "Your name is required.", 'contact_form' ) ),
			'cntctfrm_address_error' 			=> array( 'en' => __( "Address is required.", 'contact_form' ) ),
			'cntctfrm_email_error' 				=> array( 'en' => __( "A valid email address is required.", 'contact_form' ) ),
			'cntctfrm_phone_error' 				=> array( 'en' => __( "Phone number is required.", 'contact_form' ) ),
			'cntctfrm_subject_error' 			=> array( 'en' => __( "Subject is required.", 'contact_form' ) ),
			'cntctfrm_message_error' 			=> array( 'en' => __( "Message text is required.", 'contact_form' ) ),
			'cntctfrm_attachment_error' 		=> array( 'en' => __( "File format is not valid.", 'contact_form' ) ),
			'cntctfrm_attachment_upload_error'	=> array( 'en' => __( "File upload error.", 'contact_form' ) ),
			'cntctfrm_attachment_move_error' 	=> array( 'en' => __( "The file could not be uploaded.", 'contact_form' ) ),
			'cntctfrm_attachment_size_error' 	=> array( 'en' => __( "This file is too large.", 'contact_form' ) ),
			'cntctfrm_captcha_error' 			=> array( 'en' => __( "Please fill out the CAPTCHA.", 'contact_form' ) ),
			'cntctfrm_form_error'				=> array( 'en' => __( "Please make corrections below and try again.", 'contact_form' ) ),
			'cntctfrm_action_after_send' 		=> 1,
			'cntctfrm_thank_text' 				=> array( 'en' => __( "Thank you for contacting us.", 'contact_form' ) ),
			'cntctfrm_redirect_url'				=> '',
			'cntctfrm_delete_attached_file'		=> '0',
			'cntctfrm_html_email'				=> 1,
			'cntctfrm_site_name_parameter'		=> 'SERVER_NAME',
			'cntctfrm_change_label_in_email'	=> 0,
		);

		/* Check contact-form-multi plugin */
		if ( is_plugin_active( 'contact-form-multi/contact-form-multi.php' ) )
			$contact_form_multi_active = true;
		if ( is_plugin_active( 'contact-form-multi-pro/contact-form-multi-pro.php' ) )
			$contact_form_multi_pro_active = true;

		/* Install the option defaults */
		if ( ! get_option( 'cntctfrm_options' ) )
			add_option( 'cntctfrm_options', $cntctfrm_option_defaults );

		/* Get options from the database for default options */
		if ( isset( $contact_form_multi_active ) || isset( $contact_form_multi_pro_active ) ) {
			if ( ! get_option( 'cntctfrmmlt_options' ) )
				add_option( 'cntctfrmmlt_options', $cntctfrm_option_defaults );

			$cntctfrmmlt_options = get_option( 'cntctfrmmlt_options' );

			if ( ! isset( $cntctfrmmlt_options['plugin_option_version'] ) || $cntctfrmmlt_options['plugin_option_version'] != $cntctfrm_plugin_info["Version"] ) {
				$cntctfrmmlt_options = array_merge( $cntctfrm_option_defaults, $cntctfrmmlt_options );
				$cntctfrmmlt_options['plugin_option_version'] = $cntctfrm_plugin_info["Version"];
				update_option( 'cntctfrmmlt_options', $cntctfrmmlt_options );
			}

			/* Get options from the database */
			if ( isset( $_SESSION['cntctfrmmlt_id_form'] ) ) {
				if ( get_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] ) )
					$cntctfrm_options = get_option( 'cntctfrmmlt_options_'. $_SESSION['cntctfrmmlt_id_form'] );
				else {
					if ( isset( $contact_form_multi_pro_active ) )
						$cntctfrmmlt_options_main = get_site_option( 'cntctfrmmltpr_options_main' );
					elseif ( isset( $contact_form_multi_active ) )
						$cntctfrmmlt_options_main = get_site_option( 'cntctfrmmlt_options_main' );

					if (  1 == $_SESSION['cntctfrmmlt_id_form'] && 1 == count( $cntctfrmmlt_options_main['name_id_form'] ) ) {
						add_option( 'cntctfrmmlt_options_1' , get_option( 'cntctfrm_options' ) );
						$cntctfrm_options = get_option( 'cntctfrmmlt_options_1' );
					} else
						$cntctfrm_options = get_option( 'cntctfrmmlt_options' );
				}
			} else {
				$cntctfrm_options = get_option( 'cntctfrmmlt_options' );
			}
		} else {
			/* Get options from the database */
			$cntctfrm_options = get_option( 'cntctfrm_options' );
		}

		if ( empty( $cntctfrm_options['cntctfrm_language'] ) && ! is_array( $cntctfrm_options['cntctfrm_name_label'] ) ) {
			$cntctfrm_options['cntctfrm_name_label']				= array( 'en' => $cntctfrm_options['cntctfrm_name_label'] );
			$cntctfrm_options['cntctfrm_address_label']				= array( 'en' => $cntctfrm_options['cntctfrm_address_label'] );
			$cntctfrm_options['cntctfrm_email_label']				= array( 'en' => $cntctfrm_options['cntctfrm_email_label'] );
			$cntctfrm_options['cntctfrm_phone_label']				= array( 'en' => $cntctfrm_options['cntctfrm_phone_label'] );
			$cntctfrm_options['cntctfrm_subject_label']				= array( 'en' => $cntctfrm_options['cntctfrm_subject_label'] );
			$cntctfrm_options['cntctfrm_message_label']				= array( 'en' => $cntctfrm_options['cntctfrm_message_label'] );
			$cntctfrm_options['cntctfrm_attachment_label']			= array( 'en' => $cntctfrm_options['cntctfrm_attachment_label'] );
			$cntctfrm_options['cntctfrm_attachment_tooltip']		= array( 'en' => $cntctfrm_options['cntctfrm_attachment_tooltip'] );
			$cntctfrm_options['cntctfrm_send_copy_label']			= array( 'en' => $cntctfrm_options['cntctfrm_send_copy_label'] );
			$cntctfrm_options['cntctfrm_thank_text']				= array( 'en' => $cntctfrm_options['cntctfrm_thank_text'] );
			$cntctfrm_options['cntctfrm_submit_label']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_submit_label']['en'] );
			$cntctfrm_options['cntctfrm_name_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_name_error']['en'] );
			$cntctfrm_options['cntctfrm_address_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_address_error']['en'] );
			$cntctfrm_options['cntctfrm_email_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_email_error']['en'] );
			$cntctfrm_options['cntctfrm_phone_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_phone_error']['en'] );
			$cntctfrm_options['cntctfrm_subject_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_subject_error']['en'] );
			$cntctfrm_options['cntctfrm_message_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_message_error']['en'] );
			$cntctfrm_options['cntctfrm_attachment_error']			= array( 'en' => $cntctfrm_option_defaults['cntctfrm_attachment_error']['en'] );
			$cntctfrm_options['cntctfrm_attachment_upload_error']	= array( 'en' => $cntctfrm_option_defaults['cntctfrm_attachment_upload_error']['en'] );
			$cntctfrm_options['cntctfrm_attachment_move_error']		= array( 'en' => $cntctfrm_option_defaults['cntctfrm_attachment_move_error']['en'] );
			$cntctfrm_options['cntctfrm_attachment_size_error']		= array( 'en' => $cntctfrm_option_defaults['cntctfrm_attachment_size_error']['en'] );
			$cntctfrm_options['cntctfrm_captcha_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_captcha_error']['en'] );
			$cntctfrm_options['cntctfrm_form_error']				= array( 'en' => $cntctfrm_option_defaults['cntctfrm_form_error']['en'] );
		}

		if ( ! isset( $cntctfrm_options['plugin_option_version'] ) || $cntctfrm_options['plugin_option_version'] != $cntctfrm_plugin_info["Version"] ) {
			$cntctfrm_options = array_merge( $cntctfrm_option_defaults, $cntctfrm_options );
			$cntctfrm_options['plugin_option_version'] = $cntctfrm_plugin_info["Version"];

			if ( isset( $cntctfrm_options['cntctfrm_required_symbol'] ) && '1' == $cntctfrm_options['cntctfrm_required_symbol'] )
				$cntctfrm_options['cntctfrm_required_symbol'] = '*';
			elseif ( isset( $cntctfrm_options['cntctfrm_required_symbol'] ) && '0' == $cntctfrm_options['cntctfrm_required_symbol'] )
				$cntctfrm_options['cntctfrm_required_symbol'] = '';

			if ( isset( $contact_form_multi_active ) || isset( $contact_form_multi_pro_active ) ) {
				if ( get_site_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] ) )
					update_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] , $cntctfrm_options,  '', 'yes' );
				else
					update_option( 'cntctfrmmlt_options', $cntctfrm_options );
			} else
				update_option( 'cntctfrm_options', $cntctfrm_options );
		}

		/* Create db table of fields list */
		if ( ! isset( $cntctfrm_options['plugin_db_version'] ) || $cntctfrm_options['plugin_db_version'] != $cntctfrm_db_version ) {
			cntctfrm_db_create();
			$cntctfrm_options['plugin_db_version'] = $cntctfrm_db_version;
			if ( isset( $contact_form_multi_active ) || isset( $contact_form_multi_pro_active ) ) {
				update_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] , $cntctfrm_options );
			} else {
				update_option( 'cntctfrm_options', $cntctfrm_options );
			}
		}
	}
}

/* Function check if plugin is compatible with current WP version  */
if ( ! function_exists ( 'cntctfrm_db_create' ) ) {
	function cntctfrm_db_create() {
		global $wpdb;
		$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "cntctfrm_field" );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "cntctfrm_field` (
			id int NOT NULL AUTO_INCREMENT,
			name CHAR(100) NOT NULL,
			UNIQUE KEY id (id)
		);";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		$fields = array(
			'name',
			'email',
			'subject',
			'message',
			'address',
			'phone',
			'attachment',
			'attachment_explanations',
			'send_copy',
			'sent_from',
			'date_time',
			'coming_from',
			'user_agent'
		);
		foreach ( $fields as $key => $value ) {
			$db_row = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "cntctfrm_field WHERE `name` = '" . $value . "'", ARRAY_A );
			if ( !isset( $db_row ) || empty( $db_row ) ) {
				$wpdb->insert(  $wpdb->prefix . "cntctfrm_field", array( 'name' => $value ), array( '%s' ) );
			}
		}
	}
}

if ( ! function_exists ( 'cntctfrm_activation' ) ) {
	function cntctfrm_activation( $networkwide ) {
		global $wpdb;
		if ( function_exists( 'is_multisite' ) && is_multisite() && $networkwide ) {
			$cntctfrm_blog_id = $wpdb->blogid;
			$cntctfrm_get_blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $cntctfrm_get_blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				cntctfrm_settings();
				cntctfrm_db_create();
			}
			switch_to_blog( $cntctfrm_blog_id );
			return;
		} else {
			cntctfrm_settings();
			cntctfrm_db_create();
		}
	}
}

/* Add settings page in admin area */
if ( ! function_exists( 'cntctfrm_settings_page' ) ) {
	function cntctfrm_settings_page() {
		global $cntctfrm_options, $wpdb, $cntctfrm_option_defaults, $wp_version, $cntctfrm_plugin_info;
		$error = $message = $notice = '';
		$plugin_basename = plugin_basename( __FILE__ );

		if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active_for_network' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( ! isset( $_GET['action'] ) || 'appearance' != $_GET['action'] ) {
			$all_plugins = get_plugins();
		}

		/* Check contact-form-multi plugin */
		if ( is_plugin_active( 'contact-form-multi/contact-form-multi.php' ) )
			$contact_form_multi_active = true;
		if ( is_plugin_active( 'contact-form-multi-pro/contact-form-multi-pro.php' ) )
			$contact_form_multi_pro_active = true;

		/* Get Captcha options */
		if ( get_option( 'cptch_options' ) )
			$cptch_options = get_option( 'cptch_options' );
		if ( get_option( 'cptchpls_options' ) )
			$cptchpls_options = get_option( 'cptchpls_options' );
		if ( get_option( 'cptchpr_options' ) )
			$cptchpr_options = get_option( 'cptchpr_options' );
		/* Get Contact Form to DB options */
		if ( get_option( 'cntctfrmtdb_options' ) )
			$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );
		if ( get_option( 'cntctfrmtdbpr_options' ) )
			$cntctfrmtdbpr_options = get_option( 'cntctfrmtdbpr_options' );

		$userslogin = get_users( 'blog_id=' . $GLOBALS['blog_id'] . '&role=administrator' );

		/* Save data for settings page */
		if ( isset( $_POST['cntctfrm_form_submit'] ) && check_admin_referer( $plugin_basename, 'cntctfrm_nonce_name' ) ) {

			$cntctfrm_options_submit['cntctfrm_user_email'] = $_POST['cntctfrm_user_email'];
			$cntctfrm_options_submit['cntctfrm_custom_email'] = trim( stripslashes( esc_html( $_POST['cntctfrm_custom_email'] ) ), " ," );
			$cntctfrm_options_submit['cntctfrm_select_email'] = $_POST['cntctfrm_select_email'];
			$cntctfrm_options_submit['cntctfrm_from_email'] = $_POST['cntctfrm_from_email'];
			$cntctfrm_options_submit['cntctfrm_custom_from_email'] = stripslashes( esc_html( $_POST['cntctfrm_custom_from_email'] ) );

			$cntctfrm_options_submit['cntctfrm_mail_method']				= $_POST['cntctfrm_mail_method'];
			$cntctfrm_options_submit['cntctfrm_from_field']					= stripslashes( esc_html( $_POST['cntctfrm_from_field'] ) );
			$cntctfrm_options_submit['cntctfrm_select_from_field']			= $_POST['cntctfrm_select_from_field'];
			$cntctfrm_options_submit['cntctfrm_display_name_field']			= isset( $_POST['cntctfrm_display_name_field']) ? 1 : 0;
			$cntctfrm_options_submit['cntctfrm_display_address_field']		= isset( $_POST['cntctfrm_display_address_field']) ? 1 : 0;
			$cntctfrm_options_submit['cntctfrm_display_phone_field']		= isset( $_POST['cntctfrm_display_phone_field']) ? 1 : 0;
			$cntctfrm_options_submit['cntctfrm_attachment']					= isset( $_POST['cntctfrm_attachment']) ? $_POST['cntctfrm_attachment'] : 0;
			$cntctfrm_options_submit['cntctfrm_attachment_explanations']	= isset( $_POST['cntctfrm_attachment_explanations']) ? $_POST['cntctfrm_attachment_explanations'] : 0;
			$cntctfrm_options_submit['cntctfrm_send_copy']					= isset( $_POST['cntctfrm_send_copy']) ? $_POST['cntctfrm_send_copy'] : 0;

			$cntctfrm_options_submit['cntctfrm_delete_attached_file'] = isset( $_POST['cntctfrm_delete_attached_file']) ? $_POST['cntctfrm_delete_attached_file'] : 0;

			if ( isset( $_POST['cntctfrm_display_captcha'] ) ) {
				if ( get_option( 'cptch_options' ) ) {
					$cptch_options['cptch_contact_form'] = 1;
					update_option( 'cptch_options', $cptch_options );
				}
				if ( get_option( 'cptchpls_options' ) ) {
					$cptchpls_options['cptchpls_contact_form'] = 1;
					update_option( 'cptchpls_options', $cptchpls_options );
				}
				if ( get_option( 'cptchpr_options' ) ) {
					$cptchpr_options['cptchpr_contact_form'] = 1;
					update_option( 'cptchpr_options', $cptchpr_options );
				}
			} else {
				if ( get_option( 'cptch_options' ) ) {
					$cptch_options['cptch_contact_form'] = 0;
					update_option( 'cptch_options', $cptch_options );
				}
				if ( get_option( 'cptchpls_options' ) ) {
					$cptchpls_options['cptchpls_contact_form'] = 0;
					update_option( 'cptchpls_options', $cptchpls_options );
				}
				if ( get_option( 'cptchpr_options' ) ) {
					$cptchpr_options['cptchpr_contact_form'] = 0;
					update_option( 'cptchpr_options', $cptchpr_options );
				}
			}

			if ( isset( $_POST['cntctfrm_save_email_to_db'] ) ) {
				if ( get_option( 'cntctfrmtdb_options' ) ) {
					$cntctfrmtdb_options['cntctfrmtdb_save_messages_to_db'] = 1;
					update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
				}
				if ( get_option( 'cntctfrmtdbpr_options' ) ) {
					$cntctfrmtdbpr_options['save_messages_to_db'] = 1;
					update_option( 'cntctfrmtdbpr_options', $cntctfrmtdbpr_options );
				}
			} else {
				if ( get_option( 'cntctfrmtdb_options' ) ) {
					$cntctfrmtdb_options['cntctfrmtdb_save_messages_to_db'] = 0;
					update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
				}
				if ( get_option( 'cntctfrmtdbpr_options' ) ) {
					$cntctfrmtdbpr_options['save_messages_to_db'] = 0;
					update_option( 'cntctfrmtdbpr_options', $cntctfrmtdbpr_options );
				}
			}

			if ( 0 == $cntctfrm_options_submit['cntctfrm_display_name_field'] ) {
				$cntctfrm_options_submit['cntctfrm_required_name_field'] = 0;
			} else {
				$cntctfrm_options_submit['cntctfrm_required_name_field'] = isset( $_POST['cntctfrm_required_name_field']) ? 1 : 0;
			}
			if ( 0 == $cntctfrm_options_submit['cntctfrm_display_address_field'] ) {
				$cntctfrm_options_submit['cntctfrm_required_address_field']	= 0;
			} else {
				$cntctfrm_options_submit['cntctfrm_required_address_field']	= isset( $_POST['cntctfrm_required_address_field']) ? 1 : 0;
			}
			$cntctfrm_options_submit['cntctfrm_required_email_field'] = isset( $_POST['cntctfrm_required_email_field']) ? 1 : 0;
			if ( 0 == $cntctfrm_options_submit['cntctfrm_display_phone_field'] ) {
				$cntctfrm_options_submit['cntctfrm_required_phone_field']	= 0;
			} else {
				$cntctfrm_options_submit['cntctfrm_required_phone_field']	= isset( $_POST['cntctfrm_required_phone_field']) ? 1 : 0;
			}
			$cntctfrm_options_submit['cntctfrm_required_subject_field']		= isset( $_POST['cntctfrm_required_subject_field']) ? 1 : 0;
			$cntctfrm_options_submit['cntctfrm_required_message_field']		= isset( $_POST['cntctfrm_required_message_field']) ? 1 : 0;

			$cntctfrm_options_submit['cntctfrm_required_symbol']			= isset( $_POST['cntctfrm_required_symbol']) ? stripslashes( esc_html( $_POST['cntctfrm_required_symbol'] ) ) : '*';
			$cntctfrm_options_submit['cntctfrm_html_email'] 				= isset( $_POST['cntctfrm_html_email']) ? 1 : 0;
			$cntctfrm_options_submit['cntctfrm_site_name_parameter'] 		= $_POST['cntctfrm_site_name_parameter'];
			$cntctfrm_options_submit['cntctfrm_display_add_info']			= isset( $_POST['cntctfrm_display_add_info']) ? 1 : 0;

			if ( 1 == $cntctfrm_options_submit['cntctfrm_display_add_info'] ) {
				$cntctfrm_options_submit['cntctfrm_display_sent_from']		= isset( $_POST['cntctfrm_display_sent_from']) ? 1 : 0;
				$cntctfrm_options_submit['cntctfrm_display_date_time']		= isset( $_POST['cntctfrm_display_date_time']) ? 1 : 0;
				$cntctfrm_options_submit['cntctfrm_display_coming_from']	= isset( $_POST['cntctfrm_display_coming_from']) ? 1 : 0;
				$cntctfrm_options_submit['cntctfrm_display_user_agent']		= isset( $_POST['cntctfrm_display_user_agent']) ? 1 : 0;
			} else {
				$cntctfrm_options_submit['cntctfrm_display_sent_from']		= 1;
				$cntctfrm_options_submit['cntctfrm_display_date_time']		= 1;
				$cntctfrm_options_submit['cntctfrm_display_coming_from']	= 1;
				$cntctfrm_options_submit['cntctfrm_display_user_agent']		= 1;
			}

			$cntctfrm_options_submit['cntctfrm_change_label']				= isset( $_POST['cntctfrm_change_label']) ? 1 : 0;
			$cntctfrm_options_submit['cntctfrm_change_label_in_email']		= isset( $_POST['cntctfrm_change_label_in_email']) ? 1 : 0;

			if ( 1 == $cntctfrm_options_submit['cntctfrm_change_label'] ) {
				foreach ( $_POST['cntctfrm_name_label'] as $key => $val ) {
					$cntctfrm_options_submit['cntctfrm_name_label'][ $key ]					= stripcslashes( htmlspecialchars( $_POST['cntctfrm_name_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_address_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_address_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_email_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_email_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_phone_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_phone_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_subject_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_subject_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_message_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_message_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_attachment_label'][ $key ]			= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_attachment_tooltip'][ $key ]			= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_tooltip'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_send_copy_label'][ $key ]			= stripcslashes( htmlspecialchars( $_POST['cntctfrm_send_copy_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_thank_text'][ $key ]					= stripcslashes( htmlspecialchars( $_POST['cntctfrm_thank_text'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_submit_label'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_submit_label'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_name_error'][ $key ]					= stripcslashes( htmlspecialchars( $_POST['cntctfrm_name_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_address_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_address_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_email_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_email_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_phone_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_phone_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_subject_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_subject_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_message_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_message_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_attachment_error'][ $key ]			= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_attachment_upload_error'][ $key ]	= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_upload_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_attachment_move_error'][ $key ]		= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_move_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_attachment_size_error'][ $key ]		= stripcslashes( htmlspecialchars( $_POST['cntctfrm_attachment_size_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_captcha_error'][ $key ]				= stripcslashes( htmlspecialchars( $_POST['cntctfrm_captcha_error'][ $key ] ) );
					$cntctfrm_options_submit['cntctfrm_form_error'][ $key ]					= stripcslashes( htmlspecialchars( $_POST['cntctfrm_form_error'][ $key ] ) );
				}
			} else {
				if ( empty( $cntctfrm_options['cntctfrm_language'] ) ) {
					$cntctfrm_options_submit['cntctfrm_name_label']					= $cntctfrm_option_defaults['cntctfrm_name_label'];
					$cntctfrm_options_submit['cntctfrm_address_label']				= $cntctfrm_option_defaults['cntctfrm_address_label'];
					$cntctfrm_options_submit['cntctfrm_email_label']				= $cntctfrm_option_defaults['cntctfrm_email_label'];
					$cntctfrm_options_submit['cntctfrm_phone_label']				= $cntctfrm_option_defaults['cntctfrm_phone_label'];
					$cntctfrm_options_submit['cntctfrm_subject_label']				= $cntctfrm_option_defaults['cntctfrm_subject_label'];
					$cntctfrm_options_submit['cntctfrm_message_label']				= $cntctfrm_option_defaults['cntctfrm_message_label'];
					$cntctfrm_options_submit['cntctfrm_attachment_label']			= $cntctfrm_option_defaults['cntctfrm_attachment_label'];
					$cntctfrm_options_submit['cntctfrm_attachment_tooltip']			= $cntctfrm_option_defaults['cntctfrm_attachment_tooltip'];
					$cntctfrm_options_submit['cntctfrm_send_copy_label']			= $cntctfrm_option_defaults['cntctfrm_send_copy_label'];
					$cntctfrm_options_submit['cntctfrm_thank_text']					= $_POST['cntctfrm_thank_text'];
					$cntctfrm_options_submit['cntctfrm_submit_label']				= $cntctfrm_option_defaults['cntctfrm_submit_label'];
					$cntctfrm_options_submit['cntctfrm_name_error']					= $cntctfrm_option_defaults['cntctfrm_name_error'];
					$cntctfrm_options_submit['cntctfrm_address_error']				= $cntctfrm_option_defaults['cntctfrm_address_error'];
					$cntctfrm_options_submit['cntctfrm_email_error']				= $cntctfrm_option_defaults['cntctfrm_email_error'];
					$cntctfrm_options_submit['cntctfrm_phone_error']				= $cntctfrm_option_defaults['cntctfrm_phone_error'];
					$cntctfrm_options_submit['cntctfrm_subject_error']				= $cntctfrm_option_defaults['cntctfrm_subject_error'];
					$cntctfrm_options_submit['cntctfrm_message_error']				= $cntctfrm_option_defaults['cntctfrm_message_error'];
					$cntctfrm_options_submit['cntctfrm_attachment_error']			= $cntctfrm_option_defaults['cntctfrm_attachment_error'];
					$cntctfrm_options_submit['cntctfrm_attachment_upload_error']	= $cntctfrm_option_defaults['cntctfrm_attachment_upload_error'];
					$cntctfrm_options_submit['cntctfrm_attachment_move_error']		= $cntctfrm_option_defaults['cntctfrm_attachment_move_error'];
					$cntctfrm_options_submit['cntctfrm_attachment_size_error']		= $cntctfrm_option_defaults['cntctfrm_attachment_size_error'];
					$cntctfrm_options_submit['cntctfrm_captcha_error']				= $cntctfrm_option_defaults['cntctfrm_captcha_error'];
					$cntctfrm_options_submit['cntctfrm_form_error']					= $cntctfrm_option_defaults['cntctfrm_form_error'];
					foreach ( $cntctfrm_options_submit['cntctfrm_thank_text'] as $key => $val ) {
						$cntctfrm_options_submit['cntctfrm_thank_text'][ $key ] = stripcslashes( htmlspecialchars( $val ) );
					}
				} else {
					$cntctfrm_options_submit['cntctfrm_name_label']['en']				= $cntctfrm_option_defaults['cntctfrm_name_label']['en'];
					$cntctfrm_options_submit['cntctfrm_address_label']['en']			= $cntctfrm_option_defaults['cntctfrm_address_label']['en'];
					$cntctfrm_options_submit['cntctfrm_email_label']['en']				= $cntctfrm_option_defaults['cntctfrm_email_label']['en'];
					$cntctfrm_options_submit['cntctfrm_phone_label']['en']				= $cntctfrm_option_defaults['cntctfrm_phone_label']['en'];
					$cntctfrm_options_submit['cntctfrm_subject_label']['en']			= $cntctfrm_option_defaults['cntctfrm_subject_label']['en'];
					$cntctfrm_options_submit['cntctfrm_message_label']['en']			= $cntctfrm_option_defaults['cntctfrm_message_label']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_label']['en']			= $cntctfrm_option_defaults['cntctfrm_attachment_label']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_tooltip']['en']		= $cntctfrm_option_defaults['cntctfrm_attachment_tooltip']['en'];
					$cntctfrm_options_submit['cntctfrm_send_copy_label']['en']			= $cntctfrm_option_defaults['cntctfrm_send_copy_label']['en'];
					$cntctfrm_options_submit['cntctfrm_submit_label']['en']				= $cntctfrm_option_defaults['cntctfrm_submit_label']['en'];
					$cntctfrm_options_submit['cntctfrm_name_error']['en']				= $cntctfrm_option_defaults['cntctfrm_name_error']['en'];
					$cntctfrm_options_submit['cntctfrm_address_error']['en']			= $cntctfrm_option_defaults['cntctfrm_address_error']['en'];
					$cntctfrm_options_submit['cntctfrm_email_error']['en']				= $cntctfrm_option_defaults['cntctfrm_email_error']['en'];
					$cntctfrm_options_submit['cntctfrm_phone_error']['en']				= $cntctfrm_option_defaults['cntctfrm_phone_error']['en'];
					$cntctfrm_options_submit['cntctfrm_subject_error']['en']			= $cntctfrm_option_defaults['cntctfrm_subject_error']['en'];
					$cntctfrm_options_submit['cntctfrm_message_error']['en']			= $cntctfrm_option_defaults['cntctfrm_message_error']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_error']['en']			= $cntctfrm_option_defaults['cntctfrm_attachment_error']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_upload_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_upload_error']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_move_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_move_error']['en'];
					$cntctfrm_options_submit['cntctfrm_attachment_size_error']['en']	= $cntctfrm_option_defaults['cntctfrm_attachment_size_error']['en'];
					$cntctfrm_options_submit['cntctfrm_captcha_error']['en']			= $cntctfrm_option_defaults['cntctfrm_captcha_error']['en'];
					$cntctfrm_options_submit['cntctfrm_form_error']['en']				= $cntctfrm_option_defaults['cntctfrm_form_error']['en'];

					foreach ( $_POST['cntctfrm_thank_text'] as $key => $val ) {
						$cntctfrm_options_submit['cntctfrm_thank_text'][ $key ] = stripcslashes( htmlspecialchars( $_POST['cntctfrm_thank_text'][ $key ] ) );
					}
				}
			}
			/* if 'FROM' field was changed */
			if ( ( 'custom' == $cntctfrm_options['cntctfrm_from_email'] && 'custom' != $cntctfrm_options_submit['cntctfrm_from_email'] ) ||
				( 'custom' == $cntctfrm_options_submit['cntctfrm_from_email'] && $cntctfrm_options['cntctfrm_custom_from_email'] != $cntctfrm_options_submit['cntctfrm_custom_from_email'] ) ) {
				$notice = __( "Email 'FROM' field option was changed, which may cause email messages being moved to the spam folder or email delivery failures.", 'contact_form' );
			}

			$cntctfrm_options_submit['cntctfrm_action_after_send']	= $_POST['cntctfrm_action_after_send'];
			$cntctfrm_options_submit['cntctfrm_redirect_url']	= esc_url( $_POST['cntctfrm_redirect_url'] );
			$cntctfrm_options = array_merge( $cntctfrm_options, $cntctfrm_options_submit  );

			if ( 0 == $cntctfrm_options_submit['cntctfrm_action_after_send']
				&& ( "" == trim( $cntctfrm_options_submit['cntctfrm_redirect_url'] )
				|| ! filter_var( $cntctfrm_options_submit['cntctfrm_redirect_url'], FILTER_VALIDATE_URL) ) ) {
					$error .=__(  "If the 'Redirect to page' option is selected then the URL field should be in the following format", 'contact_form' )." <code>http://your_site/your_page</code>";
					$cntctfrm_options['cntctfrm_action_after_send'] = 1;
			}
			if ( 'user' == $cntctfrm_options_submit['cntctfrm_select_email'] ) {
				if ( '3.3' > $wp_version && function_exists( 'get_userdatabylogin' ) && false !== get_userdatabylogin( $cntctfrm_options_submit['cntctfrm_user_email'] ) ) {
					//
				} else if ( false !== get_user_by( 'login', $cntctfrm_options_submit['cntctfrm_user_email'] ) ) {
					//
				} else {
					$error .= __(  "Such user does not exist.", 'contact_form' );
				}
			} else {
				if ( preg_match( '|,|', $cntctfrm_options_submit['cntctfrm_custom_email'] ) ) {
					$cntctfrm_custom_emails = explode( ',', $cntctfrm_options_submit['cntctfrm_custom_email'] );
				} else {
					$cntctfrm_custom_emails[0] = $cntctfrm_options_submit['cntctfrm_custom_email'];
				}
				foreach ( $cntctfrm_custom_emails as $cntctfrm_custom_email ) {
					if ( $cntctfrm_custom_email == "" || ! is_email( trim( $cntctfrm_custom_email ) ) ) {
						$error .= __( "Please enter a valid email address in the 'Use this email address' field.", 'contact_form' );
						break;
					}
				}
			}
			if ( 'custom' == $cntctfrm_options_submit['cntctfrm_from_email'] ) {
				if ( "" == $cntctfrm_options_submit['cntctfrm_custom_from_email']
					|| ! is_email( trim( $cntctfrm_options_submit['cntctfrm_custom_from_email'] ) ) ) {
					$error .= __( "Please enter a valid email address in the 'FROM' field.", 'contact_form' );
				}
			}

			if ( '' == $error ) {
				if ( isset( $contact_form_multi_active ) ) {

					$cntctfrmmlt_options_main = get_option( 'cntctfrmmlt_options_main' );

					if ( $cntctfrmmlt_options_main['id_form'] !== $_SESSION['cntctfrmmlt_id_form'] )
						add_option( 'cntctfrmmlt_options_' . $cntctfrmmlt_options_main['id_form'] , $cntctfrm_options );
					else if ( $cntctfrmmlt_options_main['id_form'] == $_SESSION['cntctfrmmlt_id_form'] )
						update_option( 'cntctfrmmlt_options_' . $cntctfrmmlt_options_main['id_form'] , $cntctfrm_options,  '', 'yes' );
				} elseif ( isset( $contact_form_multi_pro_active ) ) {
					$cntctfrmmltpr_options_main = get_option( 'cntctfrmmltpr_options_main' );

					if ( $cntctfrmmltpr_options_main['id_form'] !== $_SESSION['cntctfrmmlt_id_form'] )
						add_option( 'cntctfrmmlt_options_' . $cntctfrmmltpr_options_main['id_form'] , $cntctfrm_options );
					else if ( $cntctfrmmltpr_options_main['id_form'] == $_SESSION['cntctfrmmlt_id_form'] )
						update_option( 'cntctfrmmlt_options_' . $cntctfrmmltpr_options_main['id_form'] , $cntctfrm_options,  '', 'yes' );
				} else {
					update_option( 'cntctfrm_options', $cntctfrm_options );
				}
				$message = __( "Settings saved.", 'contact_form' );
			} else {
				$error .=  ' ' . __( "Settings are not saved.", 'contact_form' );
			}
		}

		/* Display form on the setting page */
		$lang_codes = array(
			'aa' => 'Afar', 'ab' => 'Abkhazian', 'af' => 'Afrikaans', 'ak' => 'Akan', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic', 'an' => 'Aragonese', 'hy' => 'Armenian', 'as' => 'Assamese', 'av' => 'Avaric', 'ae' => 'Avestan', 'ay' => 'Aymara', 'az' => 'Azerbaijani', 'ba' => 'Bashkir', 'bm' => 'Bambara', 'eu' => 'Basque', 'be' => 'Belarusian', 'bn' => 'Bengali',
			'bh' => 'Bihari', 'bi' => 'Bislama', 'bs' => 'Bosnian', 'br' => 'Breton', 'bg' => 'Bulgarian', 'my' => 'Burmese', 'ca' => 'Catalan; Valencian', 'ch' => 'Chamorro', 'ce' => 'Chechen', 'zh' => 'Chinese', 'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic', 'cv' => 'Chuvash', 'kw' => 'Cornish', 'co' => 'Corsican', 'cr' => 'Cree',
			'cs' => 'Czech', 'da' => 'Danish', 'dv' => 'Divehi; Dhivehi; Maldivian', 'nl' => 'Dutch; Flemish', 'dz' => 'Dzongkha', 'eo' => 'Esperanto', 'et' => 'Estonian', 'ee' => 'Ewe', 'fo' => 'Faroese', 'fj' => 'Fijjian', 'fi' => 'Finnish', 'fr' => 'French', 'fy' => 'Western Frisian', 'ff' => 'Fulah', 'ka' => 'Georgian', 'de' => 'German', 'gd' => 'Gaelic; Scottish Gaelic',
			'ga' => 'Irish', 'gl' => 'Galician', 'gv' => 'Manx', 'el' => 'Greek, Modern', 'gn' => 'Guarani', 'gu' => 'Gujarati', 'ht' => 'Haitian; Haitian Creole', 'ha' => 'Hausa', 'he' => 'Hebrew', 'hz' => 'Herero', 'hi' => 'Hindi', 'ho' => 'Hiri Motu', 'hu' => 'Hungarian', 'ig' => 'Igbo', 'is' => 'Icelandic', 'io' => 'Ido', 'ii' => 'Sichuan Yi', 'iu' => 'Inuktitut', 'ie' => 'Interlingue',
			'ia' => 'Interlingua (International Auxiliary Language Association)', 'id' => 'Indonesian', 'ik' => 'Inupiaq', 'it' => 'Italian', 'jv' => 'Javanese', 'ja' => 'Japanese', 'kl' => 'Kalaallisut; Greenlandic', 'kn' => 'Kannada', 'ks' => 'Kashmiri', 'kr' => 'Kanuri', 'kk' => 'Kazakh', 'km' => 'Central Khmer', 'ki' => 'Kikuyu; Gikuyu', 'rw' => 'Kinyarwanda', 'ky' => 'Kirghiz; Kyrgyz',
			'kv' => 'Komi', 'kg' => 'Kongo', 'ko' => 'Korean', 'kj' => 'Kuanyama; Kwanyama', 'ku' => 'Kurdish', 'lo' => 'Lao', 'la' => 'Latin', 'lv' => 'Latvian', 'li' => 'Limburgan; Limburger; Limburgish', 'ln' => 'Lingala', 'lt' => 'Lithuanian', 'lb' => 'Luxembourgish; Letzeburgesch', 'lu' => 'Luba-Katanga', 'lg' => 'Ganda', 'mk' => 'Macedonian', 'mh' => 'Marshallese', 'ml' => 'Malayalam',
			'mi' => 'Maori', 'mr' => 'Marathi', 'ms' => 'Malay', 'mg' => 'Malagasy', 'mt' => 'Maltese', 'mo' => 'Moldavian', 'mn' => 'Mongolian', 'na' => 'Nauru', 'nv' => 'Navajo; Navaho', 'nr' => 'Ndebele, South; South Ndebele', 'nd' => 'Ndebele, North; North Ndebele', 'ng' => 'Ndonga', 'ne' => 'Nepali', 'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian', 'nb' => 'Bokmål, Norwegian, Norwegian Bokmål',
			'no' => 'Norwegian', 'ny' => 'Chichewa; Chewa; Nyanja', 'oc' => 'Occitan, Provençal', 'oj' => 'Ojibwa', 'or' => 'Oriya', 'om' => 'Oromo', 'os' => 'Ossetian; Ossetic', 'pa' => 'Panjabi; Punjabi', 'fa' => 'Persian', 'pi' => 'Pali', 'pl' => 'Polish', 'pt' => 'Portuguese', 'ps' => 'Pushto', 'qu' => 'Quechua', 'rm' => 'Romansh', 'ro' => 'Romanian', 'rn' => 'Rundi', 'ru' => 'Russian',
			'sg' => 'Sango', 'sa' => 'Sanskrit', 'sr' => 'Serbian', 'hr' => 'Croatian', 'si' => 'Sinhala; Sinhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'se' => 'Northern Sami', 'sm' => 'Samoan', 'sn' => 'Shona', 'sd' => 'Sindhi', 'so' => 'Somali', 'st' => 'Sotho, Southern', 'es' => 'Spanish; Castilian', 'sc' => 'Sardinian', 'ss' => 'Swati', 'su' => 'Sundanese', 'sw' => 'Swahili',
			'sv' => 'Swedish', 'ty' => 'Tahitian', 'ta' => 'Tamil', 'tt' => 'Tatar', 'te' => 'Telugu', 'tg' => 'Tajik', 'tl' => 'Tagalog', 'th' => 'Thai', 'bo' => 'Tibetan', 'ti' => 'Tigrinya', 'to' => 'Tonga (Tonga Islands)', 'tn' => 'Tswana', 'ts' => 'Tsonga', 'tk' => 'Turkmen', 'tr' => 'Turkish', 'tw' => 'Twi', 'ug' => 'Uighur; Uyghur', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek',
			've' => 'Venda', 'vi' => 'Vietnamese', 'vo' => 'Volapük', 'cy' => 'Welsh','wa' => 'Walloon','wo' => 'Wolof', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'yo' => 'Yoruba', 'za' => 'Zhuang; Chuang', 'zu' => 'Zulu' );

		/* GO PRO */
		if ( isset( $_GET['action'] ) && 'go_pro' == $_GET['action'] ) {
			$go_pro_result = bws_go_pro_tab_check( $plugin_basename );
			if ( ! empty( $go_pro_result['error'] ) )
				$error = $go_pro_result['error'];
		} ?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php _e( "Contact Form Settings", 'contact_form' ); ?></h2>
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab<?php if ( ! isset( $_GET['action'] ) ) echo ' nav-tab-active'; ?>"  href="admin.php?page=contact_form.php"><?php _e( 'Settings', 'contact_form' ); ?></a>
				<a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'additional' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=contact_form.php&amp;action=additional"><?php _e( 'Additional settings', 'contact_form' ); ?></a>
				<a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'appearance' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=contact_form.php&amp;action=appearance"><?php _e( 'Appearance', 'contact_form' ); ?></a>
				<a class="nav-tab" href="http://bestwebsoft.com/products/contact-form/faq" target="_blank"><?php _e( 'FAQ', 'contact_form' ); ?></a>
				<a class="nav-tab bws_go_pro_tab<?php if ( isset( $_GET['action'] ) && 'go_pro' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=contact_form.php&amp;action=go_pro"><?php _e( 'Go PRO', 'contact_form' ); ?></a>
			</h2>
			<div class="updated fade" <?php if ( ! isset( $_POST['cntctfrm_form_submit'] ) || "" != $error ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
			<div id="cntctfrm_settings_notice" class="updated fade" style="display:none"><p><strong><?php _e( "Notice:", 'contact_form' ); ?></strong> <?php _e( "The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'contact_form' ); ?></p></div>
			<?php if ( ! empty( $notice ) ) { ?>
				<div class="error"><p><strong><?php _e( 'Notice:', 'contact_form' ); ?></strong> <?php echo $notice; ?></p></div>			
			<?php } ?>
			<div class="error" <?php if ( "" == $error ) echo 'style="display:none"'; ?>><p><strong><?php echo $error; ?></strong></p></div>
			<?php if ( ! isset( $_GET['action'] ) || 'additional' == $_GET['action'] ) {
				/* main 'settings' or 'additional' settings page */
				if ( ! isset( $contact_form_multi_active ) && ! isset( $contact_form_multi_pro_active ) ) { ?>
					<h2 class="nav-tab-wrapper">
						<li class="nav-tab  nav-tab-active">NEW_FORM</li>
						<a id="cntctfrm_show_multi_notice" class="nav-tab" target="_new" href="http://bestwebsoft.com/products/contact-form-multi/?k=747ca825fb44711e2d24e40697747bc6&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" title="<?php _e( "If you want to create multiple contact forms, please install the Contact Form Multi plugin.", 'contact_form' ); ?>">+</a>
					</h2>
				<?php }				
				$form_action = ( ! isset( $_GET['action'] ) ) ? 'admin.php?page=contact_form.php' : 'admin.php?page=contact_form.php&amp;action=' . $_GET['action']; ?>
				<form id="cntctfrm_settings_form" method="post" action="<?php echo $form_action ?>">
					<span style="margin-bottom:15px;">
						<?php if ( ! isset( $contact_form_multi_active ) && ! isset( $contact_form_multi_pro_active ) ) { ?>
							<p><?php _e( "If you would like to add the Contact Form to your website, just copy and paste this shortcode to your post or page or widget:", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[contact_form]</span> <?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[contact_form lang=en]</span><br />							
							<?php _e( "If have any problems with the standard shortcode [contact_form], you should use the shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bws_contact_form]</span> (<?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bws_contact_form lang=en]</span>) <?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form]</span> (<?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=en]</span>). <?php _e( "They work the same way.", 'contact_form' ); ?></p>
						<?php } else { ?>
							<p><?php _e( "If you would like to add the Contact Form to your website, just copy and paste this shortcode to your post or page or widget:", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[contact_form id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[contact_form lang=en id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span><br />
							<?php _e( "If have any problems with the standard shortcode [contact_form], you should use the shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bws_contact_form id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> (<?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bws_contact_form lang=en id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span>) <?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> (<?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=en id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span>). <?php _e( "They work the same way.", 'contact_form' ); ?></p>
						<?php } ?>
					</span>
					<div <?php if ( isset( $_GET['action'] ) ) echo 'style="display: none;"'; ?> >
						<p><?php _e( "If you leave the fields empty, the messages will be sent to the email address specified during registration.", 'contact_form' ); ?></p>
						<table class="form-table" style="width:auto;">
							<tr valign="top">
								<th scope="row"><?php _e( "The user's email address:", 'contact_form' ); ?> </th>
								<td colspan="2">
									<input type="radio" id="cntctfrm_select_email_user" name="cntctfrm_select_email" value="user" <?php if ( $cntctfrm_options['cntctfrm_select_email'] == 'user' ) echo 'checked="checked" '; ?>/>
									<select name="cntctfrm_user_email">
										<option disabled><?php _e( "Create a username", 'contact_form' ); ?></option>
											<?php foreach ( $userslogin as $key => $value ) {
												if ( isset( $value->data ) ) {
													if ( $value->data->user_email != '' ) { ?>
														<option value="<?php echo $value->data->user_login; ?>" <?php if ( $cntctfrm_options['cntctfrm_user_email'] == $value->data->user_login ) echo 'selected="selected" '; ?>><?php echo $value->data->user_login; ?></option>
													<?php }
												} else {
													if ( $value->user_email != '' ) { ?>
														<option value="<?php echo $value->user_login; ?>" <?php if ( $cntctfrm_options['cntctfrm_user_email'] == $value->user_login ) echo 'selected="selected" '; ?>><?php echo $value->user_login; ?></option>
													<?php }
												}
											} ?>
									</select>
									<span class="cntctfrm_info"><?php _e( "Enter a username of the person who should get the messages from the contact form.", 'contact_form' ); ?></span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( "Use this email address:", 'contact_form' ); ?> </th>
								<td colspan="2">
									<input type="radio" id="cntctfrm_select_email_custom" name="cntctfrm_select_email" value="custom" <?php if ( 'custom' == $cntctfrm_options['cntctfrm_select_email'] ) echo 'checked="checked" '; ?>/> <input type="text" name="cntctfrm_custom_email" value="<?php echo $cntctfrm_options['cntctfrm_custom_email']; ?>" onfocus="document.getElementById('cntctfrm_select_email_custom').checked = true;" />
									<span class="cntctfrm_info"><?php _e( "Enter the email address you want the messages forwarded to.", 'contact_form' ); ?></span>
								</td>
							</tr>
						</table>
						<div class="bws_pro_version_bloc">
							<div class="bws_pro_version_table_bloc">
								<div class="bws_table_bg"></div>
								<table class="form-table bws_pro_version">
									<tr valign="top">
										<th scope="row"><?php _e( "Add department selectbox to the contact form:", 'contact_form' ); ?></th>
										<td colspan="2">
											<input type="radio" id="cntctfrmpr_select_email_department" name="cntctfrmpr_select_email" value="departments" disabled="disabled" />
											<div class="cntctfrmpr_department_table"><img src="<?php echo plugins_url( 'images/pro_screen_1.png', __FILE__ ); ?>" alt="" /></div>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row" colspan="2">
											* <?php _e( 'If you upgrade to Pro version all your settings will be saved.', 'contact_form' ); ?>
										</th>
									</tr>
								</table>
							</div>
							<div class="bws_pro_version_tooltip">
								<div class="bws_info">
									<?php _e( 'Unlock premium options by upgrading to a PRO version.', 'contact_form' ); ?>
									<a href="http://bestwebsoft.com/products/contact-form/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro"><?php _e( 'Learn More', 'contact_form' ); ?></a>
								</div>
								<a class="bws_button" href="http://bestwebsoft.com/products/contact-form/buy/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro">
									<?php _e( 'Go', 'contact_form' ); ?> <strong>PRO</strong>
								</a>
								<div class="clear"></div>
							</div>
						</div>
						<table class="form-table" style="width:auto;">
							<tr valign="top">
								<th scope="row"><?php _e( "Save emails to the database", 'contact_form' ); ?> </th>
								<td colspan="2">
									<?php if ( array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) || array_key_exists( 'contact-form-to-db-pro/contact_form_to_db_pro.php', $all_plugins ) ) {
										if ( is_plugin_active( 'contact-form-to-db/contact_form_to_db.php' ) || is_plugin_active( 'contact-form-to-db-pro/contact_form_to_db_pro.php' ) ) { ?>
											<input type="checkbox" name="cntctfrm_save_email_to_db" value="1" <?php if ( ( isset( $cntctfrmtdb_options ) && 1 == $cntctfrmtdb_options["cntctfrmtdb_save_messages_to_db"] ) || ( isset( $cntctfrmtdbpr_options ) && 1 == $cntctfrmtdbpr_options["save_messages_to_db"] ) ) echo 'checked="checked"'; ?> />
											<span style="color: #888888;font-size: 10px;"> (<?php _e( 'Using', 'contact_form' ); ?> <a href="admin.php?page=cntctfrmtdb_manager">Contact Form to DB</a> <?php _e( 'powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/products/">bestwebsoft.com</a>)</span>
										<?php } else { ?>
											<input disabled="disabled" type="checkbox" name="cntctfrm_save_email_to_db" value="1" <?php if ( ( isset( $cntctfrmtdb_options ) && 1 == $cntctfrmtdb_options["cntctfrmtdb_save_messages_to_db"] ) || ( isset( $cntctfrmtdbpr_options ) && 1 == $cntctfrmtdbpr_options["save_messages_to_db"] ) ) echo 'checked="checked"'; ?> />
											<span style="color: #888888;font-size: 10px;">(<?php _e( 'Using Contact Form to DB powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/products/">bestwebsoft.com</a>) <a href="<?php echo bloginfo("url"); ?>/wp-admin/plugins.php"><?php _e( 'Activate Contact Form to DB', 'contact_form' ); ?></a></span>
										<?php }
									} else { ?>
										<input disabled="disabled" type="checkbox" name="cntctfrm_save_email_to_db" value="1" />
										<span style="color: #888888;font-size: 10px;">(<?php _e( 'Using Contact Form to DB powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/products/">bestwebsoft.com</a>) <a href="http://bestwebsoft.com/products/contact-form-to-db/?k=19d806f45d866e70545de83169b274f2&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>"><?php _e( 'Download Contact Form to DB', 'contact_form' ); ?></a></span>
									<?php } ?>
								</td>
							</tr>
						</table>
					</div>
					<!-- end of main 'settings' div -->
					<div <?php if ( ! isset( $_GET['action'] ) ) echo 'style="display: none;"'; ?> >
						<table class="form-table" style="width:auto;">
							<tr>
								<th scope="row"><?php _e( 'What to use?', 'contact_form' ); ?></th>
								<td colspan="2">
									<label><input type='radio' name='cntctfrm_mail_method' value='wp-mail' <?php if ( 'wp-mail' == $cntctfrm_options['cntctfrm_mail_method'] ) echo 'checked="checked" '; ?>/>
									<?php _e( 'Wp-mail', 'contact_form' ); ?></label> <span class="cntctfrm_info">(<?php _e( 'You can use the wp_mail function for mailing', 'contact_form' ); ?>)</span><br />
									<label><input type='radio' name='cntctfrm_mail_method' value='mail' <?php if ( 'mail' == $cntctfrm_options['cntctfrm_mail_method'] ) echo 'checked="checked" '; ?>/>
									<?php _e( 'Mail', 'contact_form' ); ?> </label> <span class="cntctfrm_info">(<?php _e( 'To send mail you can use the php mail function', 'contact_form' ); ?>)</span><br />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" style="width:200px;"><?php _e( "'FROM' field", 'contact_form' ); ?></th>
								<td style="width: 200px; vertical-align: top;">
									<div><?php _e( "Name", 'contact_form' ); ?></div>
									<div>
										<input type="radio" id="cntctfrm_select_from_custom_field" name="cntctfrm_select_from_field" value="custom" <?php if ( 'custom' == $cntctfrm_options['cntctfrm_select_from_field'] ) echo 'checked="checked" '; ?>/>
										<input type="text" name="cntctfrm_from_field" value="<?php echo stripslashes( $cntctfrm_options['cntctfrm_from_field'] ); ?>" onfocus="document.getElementById('cntctfrm_select_from_custom_field').checked = true;" size="18"/><br/>
										<label style="float: left"><input type="radio" id="cntctfrm_select_from_field" name="cntctfrm_select_from_field" value="user_name" <?php if ( 'user_name' == $cntctfrm_options['cntctfrm_select_from_field'] ) echo 'checked="checked" '; ?>/> <?php _e( "User name", 'contact_form' ); ?></label>
										<div class="cntctfrm_help_box" style="margin: -3px 0 0 10px;">
											<div class="cntctfrm_hidden_help_text" style="display: none;"><?php echo __( "The name of the user who fills the form will be used in the field 'From'.", 'contact_form' ); ?></div>
										</div>
									</div>
								</td>
								<td>
									<div><?php _e( "Email", 'contact_form' ); ?></div>
									<div>
										<div style="clear: both;">
											<input type="radio" id="cntctfrm_from_custom_email" name="cntctfrm_from_email" value="custom" <?php if ( 'custom' == $cntctfrm_options['cntctfrm_from_email'] ) echo 'checked="checked" '; ?>/>
											<input type="text" name="cntctfrm_custom_from_email" value="<?php echo $cntctfrm_options['cntctfrm_custom_from_email']; ?>" onfocus="document.getElementById('cntctfrm_from_custom_email').checked = true;" />
										</div>
										<div style="clear: both;">
											<label style="float: left"><input type="radio" id="cntctfrm_from_email" name="cntctfrm_from_email" value="user" <?php if ( 'user' == $cntctfrm_options['cntctfrm_from_email'] ) echo 'checked="checked" '; ?>/> <?php _e( "User email", 'contact_form' ); ?></label>
											<div class="cntctfrm_help_box" style="margin: -3px 0 0 10px;">
												<div class="cntctfrm_hidden_help_text" style="display: none;"><?php echo __( "The email address of the user who fills the form will be used in the field 'From'.", 'contact_form' ); ?></div>
											</div>
										</div>
										<div style="clear: both;">
											<span class="cntctfrm_info">(<?php _e( "If this option is changed, email messages may be moved to the spam folder or email delivery failures may occur.", 'contact_form' ); ?>)</span>
										</div>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( "Required symbol", 'contact_form' ); ?></th>
								<td colspan="2">
									<input type="text" id="cntctfrm_required_symbol" name="cntctfrm_required_symbol" value="<?php echo $cntctfrm_options['cntctfrm_required_symbol']; ?>"/>
								</td>
							</tr>
						</table>
						<br />
						<table class="cntctfrm_settings_table" style="width:auto;">
							<thead>
								<tr valign="top">
									<th scope="row" style="width: 210px;"><?php _e( "Fields", 'contact_form' ); ?></th>
									<th><?php _e( "Used", 'contact_form' ); ?></th>
									<th><?php _e( "Required", 'contact_form' ); ?></th>
									<th><?php _e( "Visible", 'contact_form' ); ?></th>
									<th><?php _e( "Disabled for editing", 'contact_form' ); ?></th>
									<th scope="row" ><?php _e( "Field's default value", 'contact_form' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr valign="top">
									<td><?php _e( "Name", 'contact_form' ); ?></td>
									<td><input type="checkbox" name="cntctfrm_display_name_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_display_name_field'] ) echo 'checked="checked" '; ?>/></td>
									<td><input type="checkbox" id="cntctfrm_required_name_field" name="cntctfrm_required_name_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_required_name_field'] ) echo 'checked="checked" '; ?>/></td>
									<td class="bws_pro_version"><input disabled="disabled" type="checkbox" name="cntctfrmpr_visible_name" value="1" checked="checked" /></td>
									<td class="bws_pro_version"><input disabled="disabled" type="checkbox" name="cntctfrmpr_disabled_name" value="1" /></td>
									<td class="bws_pro_version">
										<input disabled="disabled" type="checkbox" name="cntctfrmpr_default_name" value="1" />
										<?php _e( "Use User's name as a default value if the user is logged in.", 'contact_form' ); ?><br />
										<span class="cntctfrm_info">(<?php _e( "'Visible' and 'Disabled for editing' options will be applied only to logged-in users.", 'contact_form' ); ?>)</span>
									</td>
								</tr>
								<tr valign="top">
									<td><?php _e( "Location selectbox", 'contact_form' ); ?></td>
									<td class="bws_pro_version"><input disabled="disabled" type="checkbox" name="cntctfrmpr_display_selectbox" value="1" /></td>
									<td class="bws_pro_version"><input disabled="disabled" type="checkbox" name="cntctfrmpr_required_selectbox" value="1" /></td>
									<td class="bws_pro_version"></td>
									<td class="bws_pro_version"></td>
									<td class="bws_pro_version"><input disabled="disabled" type="file" name="cntctfrmpr_default_location"></td>
								</tr>
								<tr valign="top">
									<td><?php _e( "Address", 'contact_form' ); ?></td>
									<td><input type="checkbox" id="cntctfrm_display_address_field" name="cntctfrm_display_address_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_display_address_field'] ) echo 'checked="checked" '; ?>/></td>
									<td><input type="checkbox" id="cntctfrm_required_address_field" name="cntctfrm_required_address_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_required_address_field'] ) echo 'checked="checked" '; ?>/></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr valign="top">
									<td><?php _e( "Email Address", 'contact_form' ); ?></td>
									<td></td>
									<td><input type="checkbox" id="cntctfrm_required_email_field" name="cntctfrm_required_email_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_required_email_field'] ) echo 'checked="checked" '; ?>/></td>
									<td class="bws_pro_version"><input disabled="disabled" type="checkbox" name="cntctfrmpr_visible_email" value="1" checked="checked" /></td>
									<td class="bws_pro_version"><input disabled="disabled" type="checkbox" name="cntctfrmpr_disabled_email" value="1" /></td>
									<td class="bws_pro_version">
										<input disabled="disabled" type="checkbox" name="cntctfrmpr_default_email" value="1" />
											<?php _e( "Use User's email as a default value if the user is logged in.", 'contact_form' ); ?><br />
										<span class="cntctfrm_info">(<?php _e( "'Visible' and 'Disabled for editing' options will be applied only to logged-in users.", 'contact_form' ); ?>)</span>
									</td>
								</tr>
								<tr valign="top">
									<td><?php _e( "Phone number", 'contact_form' ); ?></td>
									<td><input type="checkbox" id="cntctfrm_display_phone_field" name="cntctfrm_display_phone_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_display_phone_field'] ) echo 'checked="checked" '; ?>/></td>
									<td><input type="checkbox" id="cntctfrm_required_phone_field" name="cntctfrm_required_phone_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_required_phone_field'] ) echo 'checked="checked" '; ?>/></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr valign="top">
									<td><?php _e( "Subject", 'contact_form' ); ?></td>
									<td></td>
									<td><input type="checkbox" id="cntctfrm_required_subject_field" name="cntctfrm_required_subject_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_required_subject_field'] ) echo 'checked="checked" '; ?>/></td>
									<td class="bws_pro_version"><input class="subject" disabled="disabled" type="checkbox" name="cntctfrmpr_visible_subject" value="1" checked="checked" /></td>
									<td class="bws_pro_version"><input class="subject" disabled="disabled" type="checkbox" name="cntctfrmpr_disabled_subject" value="1" /></td>
									<td class="bws_pro_version"><input class="subject" disabled="disabled" type="text" name="cntctfrmpr_default_subject" value="" /></td>
								</tr>
								<tr valign="top">
									<td><?php _e( "Message", 'contact_form' ); ?></td>
									<td></td>
									<td><input type="checkbox" id="cntctfrm_required_message_field" name="cntctfrm_required_message_field" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_required_message_field'] ) echo 'checked="checked" '; ?>/></td>
									<td class="bws_pro_version"><input class="message" disabled="disabled" type="checkbox" name="cntctfrmpr_visible_message" value="1" checked="checked" /></td>
									<td class="bws_pro_version"><input class="message" disabled="disabled" disabled="disabled" type="checkbox" name="cntctfrmpr_disabled_message" value="1" /></td>
									<td class="bws_pro_version"><input class="message" disabled="disabled" type="text" name="cntctfrmpr_default_message" value="" /></td>
								</tr>
								<tr valign="top">
									<td></td>
									<td></td>
									<td></td>
									<td colspan="3" class="bws_pro_version_tooltip">
										<div class="bws_info">
											<?php _e( 'Unlock premium options by upgrading to a PRO version.', 'contact_form' ); ?>
											<a href="http://bestwebsoft.com/products/contact-form/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro"><?php _e( 'Learn More', 'contact_form' ); ?></a>
										</div>
										<a class="bws_button" href="http://bestwebsoft.com/products/contact-form/buy/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro">
											<?php _e( 'Go', 'contact_form' ); ?> <strong>PRO</strong>
										</a>
										<div class="clear"></div>
									</td>
								</tr>
								<tr valign="top">
									<td>
										<?php _e( "Attachment block", 'contact_form' ); ?>
										<div class="cntctfrm_help_box" style="margin: -3px 0 0; float:right;">
											<div class="cntctfrm_hidden_help_text" style="display: none;"><?php echo __( "Users can attach the following file formats", 'contact_form' ) . ": html, txt, css, gif, png, jpeg, jpg, tiff, bmp, ai, eps, ps, rtf, pdf, doc, docx, xls, zip, rar, wav, mp3, ppt, aar, sce"; ?></div>
										</div>
									</td>
									<td><input type="checkbox" id="cntctfrm_attachment" name="cntctfrm_attachment" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_attachment'] ) echo 'checked="checked" '; ?>/></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
						<table class="form-table" style="width:auto;">
							<tr valign="top">
								<th scope="row"><?php _e( "Add to the form", 'contact_form' ); ?></th>
								<td style="width:750px;" colspan="3">
									<div style="clear: both;">
										<label style="float: left">
											<input type="checkbox" id="cntctfrm_attachment_explanations" name="cntctfrm_attachment_explanations" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_attachment_explanations'] && '1' == $cntctfrm_options['cntctfrm_attachment'] ) echo 'checked="checked" '; ?>/>
											<?php _e( "Tips below the Attachment", 'contact_form' ); ?>
										</label>
										<div class="cntctfrm_help_box" style="margin: -3px 0 0 10px;">
											<div class="cntctfrm_hidden_help_text" style="display: none;width: auto;"><img title="" src="<?php echo plugins_url( 'images/tooltip_attachment_tips.png', __FILE__ ); ?>" alt=""/></div>
										</div>
									</div>
									<div style="clear: both;">
										<label style="float: left">
											<input type="checkbox" id="cntctfrm_send_copy" name="cntctfrm_send_copy" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_send_copy'] ) echo 'checked="checked" '; ?>/>
											<?php _e( "'Send me a copy' block", 'contact_form' ); ?>
										</label>
										<div class="cntctfrm_help_box" style="margin: -3px 0 0 10px;">
											<div class="cntctfrm_hidden_help_text" style="display: none;width: auto;"><img title="" src="<?php echo plugins_url( 'images/tooltip_sendme_block.png', __FILE__ ); ?>" alt=""/></div>
										</div>
									</div>
									<div style="clear: both;">
										<?php if ( array_key_exists( 'captcha/captcha.php', $all_plugins ) || array_key_exists( 'captcha-plus/captcha-plus.php', $all_plugins ) || array_key_exists( 'captcha-pro/captcha_pro.php', $all_plugins ) ) {
											if ( is_plugin_active( 'captcha/captcha.php' ) || is_plugin_active( 'captcha-plus/captcha-plus.php' ) || is_plugin_active( 'captcha-pro/captcha_pro.php' ) ) { ?>
												<label><input type="checkbox" name="cntctfrm_display_captcha" value="1" <?php if ( ( isset( $cptch_options ) && 1 == $cptch_options["cptch_contact_form"] ) ||( isset( $cptchpls_options ) && 1 == $cptchpls_options["cptchpls_contact_form"] ) || ( isset( $cptchpr_options ) && 1 == $cptchpr_options["cptchpr_contact_form"] ) ) echo 'checked="checked"'; ?> />
												<?php _e( "Captcha", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/products/">bestwebsoft.com</a>)</span>
											<?php } else { ?>
												<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_captcha" value="1" <?php if ( ( isset( $cptch_options ) && 1 == $cptch_options["cptch_contact_form"] ) || ( isset( $cptchpls_options ) && 1 == $cptchpls_options["cptchpls_contact_form"] ) || ( isset( $cptchpr_options ) && 1 == $cptchpr_options["cptchpr_contact_form"] ) ) echo 'checked="checked"'; ?> />
												<?php _e( 'Captcha', 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'powered by', 'contact_form' ); ?> <a href="http://bestwebsoft.com/products/">bestwebsoft.com</a>) <a href="<?php echo bloginfo("url"); ?>/wp-admin/plugins.php"><?php _e( 'Activate captcha', 'contact_form' ); ?></a></span>
											<?php }
										} else { ?>
											<label><input disabled="disabled" type="checkbox" name="cntctfrm_display_captcha" value="1" />
											<?php _e( 'Captcha', 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'powered by', 'contact_form' ); ?> <a href="http://http://bestwebsoft.com/products/">bestwebsoft.com</a>) <a href="http://bestwebsoft.com/products/captcha/?k=19ac1e9b23bea947cfc4a9b8e3326c03&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>"><?php _e( 'Download captcha', 'contact_form' ); ?></a></span>
										<?php } ?>
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"></th>
								<td colspan="3" class="bws_pro_version">
									<label><input disabled="disabled" type="checkbox" value="1" name="cntctfrmpr_display_privacy_check"> <?php _e( 'Agreement checkbox', 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'Required checkbox for submitting the form', 'contact_form' ); ?>)</span><br />
									<label><input disabled="disabled" type="checkbox" value="1" name="cntctfrmpr_display_optional_check"> <?php _e( 'Optional checkbox', 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;">(<?php _e( 'Optional checkbox, the results of which will be displayed in email', 'contact_form' ); ?>)</span><br />
								</td>
							</tr>
							<tr valign="top">
								<th></th>
								<td colspan="3" class="bws_pro_version_tooltip">
									<div class="bws_info">
										<?php _e( 'Unlock premium options by upgrading to a PRO version.', 'contact_form' ); ?>
										<a href="http://bestwebsoft.com/products/contact-form/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro"><?php _e( 'Learn More', 'contact_form' ); ?></a>
									</div>
									<a class="bws_button" href="http://bestwebsoft.com/products/contact-form/buy/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro">
										<?php _e( 'Go', 'contact_form' ); ?> <strong>PRO</strong>
									</a>
									<div class="clear"></div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( "Delete an attachment file from the server after the email is sent", 'contact_form' ); ?> </th>
								<td colspan="3">
									<input type="checkbox" id="cntctfrm_delete_attached_file" name="cntctfrm_delete_attached_file" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_delete_attached_file'] ) echo 'checked="checked" '; ?>/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( "Email in HTML format sending", 'contact_form' ); ?></th>
								<td colspan="2"><input type="checkbox" name="cntctfrm_html_email" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_html_email'] ) echo 'checked="checked" '; ?>/></td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( "Display additional info in the email", 'contact_form' ); ?></th>
								<td style="width:15px;" class="cntctfrm_td_top_align">
									<input type="checkbox" id="cntctfrm_display_add_info" name="cntctfrm_display_add_info" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_display_add_info'] ) echo 'checked="checked" '; ?>/>
								</td>
								<td style="max-width:150px;" class="cntctfrm_display_add_info_block <?php if ( '0' == $cntctfrm_options['cntctfrm_display_add_info'] ) echo "cntctfrm_hidden"; ?>">
									<label><input type="checkbox" id="cntctfrm_display_sent_from" name="cntctfrm_display_sent_from" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_display_sent_from'] ) echo 'checked="checked" '; ?>/> <?php _e( "Sent from (ip address)", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;"><?php _e( "Example: Sent from (IP address):	127.0.0.1", 'contact_form' ); ?></span><br />
									<label><input type="checkbox" id="cntctfrm_display_date_time" name="cntctfrm_display_date_time" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_display_date_time'] ) echo 'checked="checked" '; ?>/> <?php _e( "Date/Time", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;"><?php _e( "Example: Date/Time:	August 19, 2013 8:50 pm", 'contact_form' ); ?></span><br />
									<label><input type="checkbox" id="cntctfrm_display_coming_from" name="cntctfrm_display_coming_from" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_display_coming_from'] ) echo 'checked="checked" '; ?>/> <?php _e( "Sent from (referer)", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;"><?php _e( "Example: Sent from (referer):	http://bestwebsoft.com/contacts/contact-us/", 'contact_form' ); ?></span><br />
									<label><input type="checkbox" id="cntctfrm_display_user_agent" name="cntctfrm_display_user_agent" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_display_user_agent'] ) echo 'checked="checked" '; ?>/> <?php _e( "Using (user agent)", 'contact_form' ); ?></label> <span style="color: #888888;font-size: 10px;"><?php _e( "Example: Using (user agent):	Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36", 'contact_form' ); ?></span><br />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( "Language settings for the field names in the form", 'contact_form' ); ?></th>
								<td colspan="2">
									<select name="cntctfrm_languages" id="cntctfrm_languages" style="width:300px;">
									<?php foreach ( $lang_codes as $key => $val ) {
										if ( in_array( $key, $cntctfrm_options['cntctfrm_language'] ) )
											continue;
										echo '<option value="' . esc_attr( $key ) . '"> ' . esc_html( $val ) . '</option>';
									} ?>
									</select>
									<input type="button" class="button-primary" id="cntctfrm_add_language_button" value="<?php _e( 'Add a language', 'contact_form' ); ?>" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( "Change the names of the contact form fields and error messages", 'contact_form' ); ?></th>
								<td style="width:15px;" class="cntctfrm_td_top_align">
									<input type="checkbox" id="cntctfrm_change_label" name="cntctfrm_change_label" value="1" <?php if ( $cntctfrm_options['cntctfrm_change_label'] == '1' ) echo 'checked="checked" '; ?>/>
								</td>
								<td class="cntctfrm_change_label_block <?php if ( '0' == $cntctfrm_options['cntctfrm_change_label'] ) echo "cntctfrm_hidden"; ?>">
									<div class="cntctfrm_label_language_tab cntctfrm_active" id="cntctfrm_label_en"><?php _e( 'English', 'contact_form' ); ?></div>
									<?php if ( ! empty( $cntctfrm_options['cntctfrm_language'] ) ) {
										foreach ( $cntctfrm_options['cntctfrm_language'] as $val ) {
											echo '<div class="cntctfrm_label_language_tab" id="cntctfrm_label_' . $val . '">' . $lang_codes[ $val ] . ' <span class="cntctfrm_delete" rel="' . $val . '">X</span></div>';
										}
									} ?>
									<div class="clear"></div>
									<div class="cntctfrm_language_tab cntctfrm_tab_en">
										<div class="cntctfrm_language_tab_block_mini" style="display:none;"><?php _e( "click to expand/hide the list", 'contact_form' ); ?></div>
										<div class="cntctfrm_language_tab_block">
											<input type="text" name="cntctfrm_name_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_name_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Name:", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_address_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_address_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Address:", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_email_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_email_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Email Address:", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_phone_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_phone_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Phone number:", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_subject_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_subject_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Subject:", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_message_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_message_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Message:", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_attachment_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Attachment:", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_attachment_tooltip[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_tooltip']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Tips below the Attachment block", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_send_copy_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_send_copy_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Send me a copy", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_submit_label[en]" value="<?php echo $cntctfrm_options['cntctfrm_submit_label']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Submit", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_name_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_name_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Name field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_address_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_address_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Address field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_email_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_email_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Email field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_phone_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_phone_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Phone field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_subject_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_subject_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Subject field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_message_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_message_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Message field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_attachment_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message about the file type for the Attachment field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_attachment_upload_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_upload_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message while uploading a file for the Attachment field to the server", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_attachment_move_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_move_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message while moving the file for the Attachment field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_attachment_size_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_attachment_size_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message when file size limit for the Attachment field is exceeded", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_captcha_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_captcha_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Captcha field", 'contact_form' ); ?></span><br />
											<input type="text" name="cntctfrm_form_error[en]" value="<?php echo $cntctfrm_options['cntctfrm_form_error']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the whole form", 'contact_form' ); ?></span><br />
										</div>
										<?php if ( ! isset( $contact_form_multi_active ) && ! isset( $contact_form_multi_pro_active ) ) { ?>
											<span class="cntctfrm_info" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=en]</span> <?php _e( "or", 'contact_form' ) ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form]</span> <?php _e( "for this language", 'contact_form' ); ?></span>
										<?php } else { ?>
											<span class="cntctfrm_info" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=en id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "for this language", 'contact_form' ); ?></span>
										<?php } ?>
									</div>
									<?php if ( ! empty( $cntctfrm_options['cntctfrm_language'] ) ) {
										foreach ( $cntctfrm_options['cntctfrm_language'] as $val ) { ?>
											<div class="cntctfrm_language_tab hidden cntctfrm_tab_<?php echo $val; ?>">
												<div class="cntctfrm_language_tab_block_mini" style="display:none;"><?php _e( "click to expand/hide the list", 'contact_form' ); ?></div>
												<div class="cntctfrm_language_tab_block">
													<input type="text" name="cntctfrm_name_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_name_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_name_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Name:", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_address_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_address_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_address_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Address:", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_email_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_email_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_email_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Email Address:", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_phone_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_phone_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_phone_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Phone number:", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_subject_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_subject_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_subject_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Subject:", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_message_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_message_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_message_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Message:", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_attachment_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Attachment:", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_attachment_tooltip[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_tooltip'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_tooltip'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Tips below the Attachment block", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_send_copy_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_send_copy_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_send_copy_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Send me a copy", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_submit_label[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_submit_label'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_submit_label'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Submit", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_name_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_name_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_name_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Name field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_address_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_address_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_address_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Address field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_email_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_email_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_email_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Email field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_phone_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_phone_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_phone_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Phone field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_subject_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_subject_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_subject_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Subject field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_message_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_message_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_message_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Message field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_attachment_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message about the file type for the Attachment field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_attachment_upload_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_upload_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_upload_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message while uploading a file for the Attachment field to the server", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_attachment_move_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_move_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_move_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message while moving the file for the Attachment field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_attachment_size_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_attachment_size_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_attachment_size_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message when file size limit for the Attachment field is exceeded", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_captcha_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_captcha_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_captcha_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the Captcha field", 'contact_form' ); ?></span><br />
													<input type="text" name="cntctfrm_form_error[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_form_error'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_form_error'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Error message for the whole form", 'contact_form' ); ?></span><br />
												</div>
												<?php if ( ! isset( $contact_form_multi_active ) && ! isset( $contact_form_multi_pro_active ) ) { ?>
													<span class="cntctfrm_info" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=<?php echo $val; ?>]</span> <?php _e( "for this language", 'contact_form' ); ?></span>
												<?php } else { ?>
													<span class="cntctfrm_info" style="margin-left: 5px;"><?php _e( "Use shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=<?php $val . ' id=' . $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "for this language", 'contact_form' ); ?></span>
												<?php } ?>
											</div>
										<?php }
									} ?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( 'Use the changed names of the contact form fields in the email', 'contact_form' ); ?></th>
								<td colspan="2">
									<input type="checkbox" name="cntctfrm_change_label_in_email" value="1" <?php if ( $cntctfrm_options['cntctfrm_change_label_in_email'] == '1' ) echo 'checked="checked" '; ?>/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( "Action after email is sent", 'contact_form' ); ?></th>
								<td colspan="2" class="cntctfrm_action_after_send_block">
									<label><input type="radio" id="cntctfrm_action_after_send" name="cntctfrm_action_after_send" value="1" <?php if ( '1' == $cntctfrm_options['cntctfrm_action_after_send'] ) echo 'checked="checked" '; ?>/> <?php _e( "Display text", 'contact_form' ); ?></label><br />
									<div class="cntctfrm_label_language_tab cntctfrm_active" id="cntctfrm_text_en"><?php _e( 'English', 'contact_form' ); ?></div>
									<?php if ( ! empty( $cntctfrm_options['cntctfrm_language'] ) ) {
										foreach ( $cntctfrm_options['cntctfrm_language'] as $val ) {
											echo '<div class="cntctfrm_label_language_tab" id="cntctfrm_text_' . $val . '">' . $lang_codes[ $val ] . ' <span class="cntctfrm_delete" rel="' . $val . '">X</span></div>';
										}
									} ?>
									<div class="clear"></div>
									<div class="cntctfrm_language_tab cntctfrm_tab_en" style=" padding: 5px 10px 5px 5px;">
										<input type="text" name="cntctfrm_thank_text[en]" value="<?php echo $cntctfrm_options['cntctfrm_thank_text']['en']; ?>" /> <span class="cntctfrm_info"><?php _e( "Text", 'contact_form' ); ?></span><br />
										<?php if ( ! isset( $contact_form_multi_active ) && ! isset( $contact_form_multi_pro_active ) ) { ?>
											<span class="cntctfrm_info"><?php _e( "Use shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=en]</span> <?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form]</span> <?php _e( "for this language", 'contact_form' ); ?></span>
										<?php } else { ?>
											<span class="cntctfrm_info"><?php _e( "Use shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=en id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "or", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form id=<?php echo $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "for this language", 'contact_form' ); ?></span>
										<?php } ?>
									</div>
									<?php if ( ! empty( $cntctfrm_options['cntctfrm_language'] ) ) {
										foreach ( $cntctfrm_options['cntctfrm_language'] as $val ) { ?>
											<div class="cntctfrm_language_tab hidden cntctfrm_tab_<?php echo $val; ?>" style=" padding: 5px 10px 5px 5px;">
												<input type="text" name="cntctfrm_thank_text[<?php echo $val; ?>]" value="<?php if ( isset( $cntctfrm_options['cntctfrm_thank_text'][ $val ] ) ) echo $cntctfrm_options['cntctfrm_thank_text'][ $val ]; ?>" /> <span class="cntctfrm_info"><?php _e( "Text", 'contact_form' ); ?></span><br />
												<?php if ( ! isset( $contact_form_multi_active ) && ! isset( $contact_form_multi_pro_active ) ) { ?>
													<span class="cntctfrm_info"><?php _e( "Use shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=<?php echo $val; ?>]</span> <?php _e( "for this language", 'contact_form' ); ?></span>
												<?php } else { ?>
													<span class="cntctfrm_info"><?php _e( "Use shortcode", 'contact_form' ); ?> <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=<?php echo $val . ' id=' . $_SESSION['cntctfrmmlt_id_form']; ?>]</span> <?php _e( "for this language", 'contact_form' ); ?></span>
												<?php } ?>
											</div>
										<?php }
									} ?>
									<div id="cntctfrm_before"></div>
									<br />
									<input type="radio" id="cntctfrm_action_after_send_url" name="cntctfrm_action_after_send" value="0" <?php if ( '0' == $cntctfrm_options['cntctfrm_action_after_send'] ) echo 'checked="checked" '; ?>/> <?php _e( "Redirect to the page", 'contact_form' ); ?><br />
									<input type="text" name="cntctfrm_redirect_url" value="<?php echo $cntctfrm_options['cntctfrm_redirect_url']; ?>" onfocus="document.getElementById('cntctfrm_action_after_send_url').checked = true;" /> <span class="cntctfrm_info"><?php _e( "Url", 'contact_form' ); ?></span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e( 'The $_SERVER variable that is used to build a URL of the form', 'contact_form' ); ?></th>
								<td colspan="2">
									<label><input type="radio" name="cntctfrm_site_name_parameter" value="SERVER_NAME" <?php if ( 'SERVER_NAME' == $cntctfrm_options['cntctfrm_site_name_parameter'] ) echo 'checked="checked" '; ?>/> SERVER_NAME</label><br />
									<label><input type="radio" name="cntctfrm_site_name_parameter" value="HTTP_HOST" <?php if ( 'HTTP_HOST' == $cntctfrm_options['cntctfrm_site_name_parameter'] ) echo 'checked="checked" '; ?>/> HTTP_HOST</label><br />
									<span class="cntctfrm_info"><?php _e( "If you are not sure whether to change this setting or not, please do not do that.", 'contact_form' ); ?></span>
								</td>
							</tr>
						</table>
						<div class="bws_pro_version_bloc">
							<div class="bws_pro_version_table_bloc">
								<div class="bws_table_bg"></div>
								<table class="form-table bws_pro_version">
									<tr valign="top">
										<th scope="row"><?php _e( 'Auto Response', 'contact_form' ); ?></th>
										<td colspan="2">
											<input disabled="disabled" type="checkbox" value="1" name="cntctfrm_auto_response" checked="checked"/>
											<textarea name="cntctfrm_auto_response_message" style="position: relative; margin-left: 20px; z-index: -1;">Dear %%NAME%%, Thank you for contacting us. We have received your message and will reply to it shortly. Regards, %%SITENAME%% Team.</textarea><br/>
											<span class="cntctfrm_info" style="margin-left: 45px"><?php _e( "You can use %%NAME%% to display data from the email field and %%MESSAGE%% to display data from the Message field, as well as %%SITENAME%% to display blog name.", 'contact_form' ); ?></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row" colspan="2">
											* <?php _e( 'If you upgrade to Pro version all your settings will be saved.', 'contact_form' ); ?>
										</th>
									</tr>
								</table>
							</div>
							<div class="bws_pro_version_tooltip">
								<div class="bws_info">
									<?php _e( 'Unlock premium options by upgrading to a PRO version.', 'contact_form' ); ?>
									<a href="http://bestwebsoft.com/products/contact-form/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro"><?php _e( 'Learn More', 'contact_form' ); ?></a>
								</div>
								<a class="bws_button" href="http://bestwebsoft.com/products/contact-form/buy/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro">
									<?php _e( 'Go', 'contact_form' ); ?> <strong>PRO</strong>
								</a>
								<div class="clear"></div>
							</div>
						</div>
					</div>
					<!-- end of 'Additional' settings -->
					<input type="hidden" name="cntctfrm_form_submit" value="submit" />
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'contact_form' ); ?>" />
					</p>
					<?php wp_nonce_field( $plugin_basename, 'cntctfrm_nonce_name' ); ?>
				</form>
				<?php bws_plugin_reviews_block( $cntctfrm_plugin_info['Name'], 'contact-form-plugin' );
			} elseif ( 'appearance' == $_GET['action'] ) { ?>
				<div id="cntctfrmpr_left_table">
					<div class="bws_pro_version_bloc">
						<div class="bws_pro_version_table_bloc">
							<div class="bws_table_bg"></div>
							<table class="form-table bws_pro_version">
								<tr valign="top">
									<th scope="row"><?php _e( "Errors output", 'contact_form' ); ?></th>
									<td colspan="2">
										<select name="cntctfrmpr_error_displaying">
											<option value="labels"><?php _e( "Display error messages", 'contact_form' ); ?></option>
											<option value="input_colors"><?php _e( "Color of the input field errors.", 'contact_form' ); ?></option>
											<option value="both" selected="selected"><?php _e( "Display error messages & color of the input field errors", 'contact_form' ); ?></option>
										</select>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( "Add placeholder to the input blocks", 'contact_form' ); ?></th>
									<td colspan="2">
										<input disabled='disabled' type="checkbox" name="cntctfrmpr_placeholder" value="1" checked="checked"/>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row"><?php _e( "Add tooltips", 'contact_form' ); ?></th>
									<td colspan="2">
										<div>
											<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_name" value="1" checked="checked"/>
											<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_name"><?php _e( "Name", 'contact_form' ); ?></label>
										</div>
										<?php if ( '1' == $cntctfrm_options['cntctfrm_display_address_field'] ) { ?>
											<div>
												<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_address" value="1" checked="checked"/>
												<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_address"><?php _e( "Address", 'contact_form' ); ?></label>
											</div>
										<?php } ?>
										<div>
											<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_email" value="1" checked="checked"/>
											<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_email"><?php _e( "Email address", 'contact_form' ); ?></label>
										</div>
										<?php if ( '1' == $cntctfrm_options['cntctfrm_display_phone_field'] ) { ?>
											<div>
												<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_phone" value="1" checked="checked"/>
												<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_phone"><?php _e( "Phone Number", 'contact_form' ); ?></label>
											</div>
										<?php } ?>
										<div>
											<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_subject" value="1" checked="checked"/>
											<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_subject"><?php _e( "Subject", 'contact_form' ); ?></label>
										</div>
										<div>
											<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_message" value="1" checked="checked"/>
											<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_message"><?php _e( "Message", 'contact_form' ); ?></label>
										</div>
										<?php if ( '1' == $cntctfrm_options['cntctfrm_attachment_explanations'] ) { ?>
											<div>
												<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_attachment" value="1" checked="checked"/>
												<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_attachment"><?php _e( "Attachment", 'contact_form' ); ?></label>
											</div>
										<?php } ?>
										<div>
											<input disabled='disabled' type="checkbox" name="cntctfrmpr_tooltip_display_captcha" value="1" />
											<label class="cntctfrmpr_tooltip_label" for="cntctfrmpr_tooltip_display_captcha"><?php _e( "Captcha", 'contact_form' ); ?> </label><span style="color: #888888;font-size: 10px;"><?php _e( '(powered by bestwebsoft.com)', 'contact_form' ); ?></span>
										</div>
									</td>
								</tr>
								<tr valign="top">
									<th colspan="3" scope="row"><input disabled='disabled' type="checkbox" id="cntctfrmpr_style_options" name="cntctfrmpr_style_options" value="1" checked="checked" /> <?php _e( "Style options", 'contact_form' ); ?></th>
								</tr>
								<tr valign="top" class="cntctfrmpr_style_block">
									<th scope="row"><?php _e( "Text color", 'contact_form' ); ?></th>
									<td colspan="2">
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_label_color" value="" class="cntctfrmpr_colorPicker" />
											<?php _e( 'Label text color', 'contact_form' ); ?>
										</div>
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_input_placeholder_color" value="" class="cntctfrmpr_colorPicker" />
											<?php _e( "Placeholder color", 'contact_form' ); ?>
										</div>
									</td>
								</tr>
								<tr valign="top" class="cntctfrmpr_style_block">
									<th scope="row"><?php _e( "Errors color", 'contact_form' ); ?></th>
									<td colspan="2">
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_error_color" value="" class="cntctfrmpr_colorPicker" />
											<?php _e( 'Error text color', 'contact_form' ); ?>
										</div>
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_error_input_color" value="" class="cntctfrmpr_colorPicker" />
											<?php _e( 'Background color of the input field errors', 'contact_form' ); ?>
										</div>
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_error_input_border_color" value="" class="cntctfrmpr_colorPicker" />
											<?php _e( 'Border color of the input field errors', 'contact_form' ); ?>
										</div>
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" id="" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_input_placeholder_error_color" value="" class="cntctfrmpr_colorPicker " />
											<?php _e( "Placeholder color of the input field errors", 'contact_form' ); ?>
										</div>
									</td>
								</tr>
								<tr valign="top" class="cntctfrmpr_style_block">
									<th scope="row"><?php _e( "Input fields", 'contact_form' ); ?></th>
									<td colspan="2">
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" id="" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_input_background" value="" class="cntctfrmpr_colorPicker" />
											<?php _e( "Input fields background color", 'contact_form' ); ?>
										</div>
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_input_color" value="" class="cntctfrmpr_colorPicker" />
											<?php _e( "Text fields color", 'contact_form' ); ?>
										</div>
										<input disabled='disabled' style="margin-left: 66px;" size="8" type="text" value="" name="cntctfrmpr_border_input_width" /> <?php _e( 'Border width in px, numbers only', 'contact_form' ); ?><br />
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_border_input_color" value="" class="cntctfrmpr_colorPicker" />
											 <?php _e( 'Border color', 'contact_form' ); ?>
										</div>
									</td>
								</tr>
								<tr valign="top" class="cntctfrmpr_style_block">
									<th scope="row"><?php _e( "Submit button", 'contact_form' ); ?></th>
									<td colspan="2">
										<input disabled='disabled' style="margin-left: 66px;" size="8" type="text" value="" name="cntctfrmpr_button_width" /> <?php _e( 'Width in px, numbers only', 'contact_form' ); ?><br />
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_button_backgroud" value="" class="cntctfrmpr_colorPicker" />
											 <?php _e( 'Button color', 'contact_form' ); ?>
										</div>
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_button_color" value="" class="cntctfrmpr_colorPicker" />
											<?php _e( "Button text color", 'contact_form' ); ?>
										</div>
										<div>
											<input disabled='disabled' type="button" class="cntctfrmpr_default button-small button" value="<?php _e('Default', 'contact_form'); ?>" />
											<input disabled='disabled' type="text" name="cntctfrmpr_border_button_color" value="" class="cntctfrmpr_colorPicker" />
											 <?php _e( 'Border color', 'contact_form' ); ?>
										</div>
									</td>
								</tr>
								<tr valign="top">
									<th scope="row" colspan="2">
										* <?php _e( 'If you upgrade to Pro version all your settings will be saved.', 'contact_form' ); ?>
									</th>
								</tr>
							</table>
						</div>
						<div class="bws_pro_version_tooltip">
							<div class="bws_info">
								<?php _e( 'Unlock premium options by upgrading to a PRO version.', 'contact_form' ); ?>
								<a href="http://bestwebsoft.com/products/contact-form/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro"><?php _e( 'Learn More', 'contact_form' ); ?></a>
							</div>
							<a class="bws_button" href="http://bestwebsoft.com/products/contact-form/buy/?k=697c5e74f39779ce77850e11dbe21962&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form Pro">
								<?php _e( 'Go', 'contact_form' ); ?> <strong>PRO</strong>
							</a>
							<div class="clear"></div>
						</div>
					</div>
					<input type="hidden" name="cntctfrmpr_form_submit" value="submit" />
					<p class="submit">
						<input disabled='disabled' type="button" class="button-primary" value="<?php _e( 'Save Changes', 'contact_form' ); ?>" />
					</p>
				</div>
				<div id="cntctfrmpr_right_table">
					<h3><?php _e( "Contact Form Pro | Preview", 'contact_form' ); ?></h3>
					<div id="cntctfrmpr_contact_form" class="cntctfrm_contact_form">
						<div id="cntctfrmpr_show_errors_block">
							<input disabled="" type="checkbox" id="cntctfrmpr_show_errors" name="cntctfrmpr_show_errors" /> <?php _e( "Show with errors", 'contact_form' ); ?>
						</div>
						<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_form_error']['en']; ?></div>
						<div class="cntctfrm_label cntctfrm_label_name">
							<label for="cntctfrmpr_contact_name"><?php echo $cntctfrm_options['cntctfrm_name_label']['en']; if ( 1 == $cntctfrm_options['cntctfrm_required_name_field'] ) echo '<span class="required"> *</span>'; ?></label>
						</div>
						<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_name_error']['en']; ?></div>
						<div class="cntctfrm_input cntctfrm_input_name">
							<input placeholder="<?php _e( "Please enter your full name...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_name" id="cntctfrmpr_contact_name"/>
							<div class="cntctfrmpr_help_box">
								<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your full name...", 'contact_form' ); ?></div>
							</div>
						</div>
						<?php if ( 1 == $cntctfrm_options['cntctfrm_display_address_field'] ) { ?>
							<div class="cntctfrm_label cntctfrm_label_address">
								<label for="cntctfrmpr_contact_address"><?php echo $cntctfrm_options['cntctfrm_address_label']['en']; if ( 1 == $cntctfrm_options['cntctfrm_required_address_field'] ) echo '<span class="required"> *</span>'; ?></label>
							</div>
							<?php if ( 1 == $cntctfrm_options['cntctfrm_required_address_field'] ) { ?>
								<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_address_error']['en']; ?></div>
							<?php } ?>
							<div class="cntctfrm_input cntctfrm_input_address">
								<input placeholder="<?php _e( "Please enter your address...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_address" id="cntctfrmpr_contact_address" />
								<div class="cntctfrmpr_help_box">
									<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your address...", 'contact_form' ); ?></div>
								</div>
							</div>
						<?php } ?>
						<div class="cntctfrm_label cntctfrm_label_email">
							<label for="cntctfrmpr_contact_email"><?php echo $cntctfrm_options['cntctfrm_email_label']['en']; if ( 1 == $cntctfrm_options['cntctfrm_required_email_field'] ) echo '<span class="required"> *</span>'; ?></label>
						</div>
						<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_email_error']['en']; ?></div>
						<div class="cntctfrm_input cntctfrm_input_email">
							<input placeholder="<?php _e( "Please enter your email address...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_email" id="cntctfrmpr_contact_email" />
							<div class="cntctfrmpr_help_box">
								<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your email address...", 'contact_form' ); ?></div>
							</div>
						</div>
						<?php if ( 1 == $cntctfrm_options['cntctfrm_display_phone_field'] ) { ?>
							<div class="cntctfrm_label cntctfrm_label_phone">
								<label for="cntctfrmpr_contact_phone"><?php echo $cntctfrm_options['cntctfrm_phone_label']['en']; if ( 1 == $cntctfrm_options['cntctfrm_required_phone_field'] ) echo '<span class="required"> *</span>'; ?></label>
							</div>
							<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['phone_error']['en']; ?></div>
							<div class="cntctfrm_input cntctfrm_input_phone">
								<input placeholder="<?php _e( "Please enter your phone number...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_phone" id="cntctfrmpr_contact_phone" />
								<div class="cntctfrmpr_help_box">
									<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your phone number...", 'contact_form' ); ?></div>
								</div>
							</div>
						<?php } ?>
						<div class="cntctfrm_label cntctfrm_label_subject">
							<label for="cntctfrmpr_contact_subject"><?php echo $cntctfrm_options['cntctfrm_subject_label']['en']; if ( 1 == $cntctfrm_options['cntctfrm_required_subject_field'] ) echo '<span class="required"> *</span>'; ?></label>
						</div>
						<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_subject_error']['en']; ?></div>
						<div class="cntctfrm_input cntctfrm_input_subject">
							<input placeholder="<?php _e( "Please enter subject...", 'contact_form' ); ?>" class="text" type="text" size="40" value="" name="cntctfrmpr_contact_subject" id="cntctfrmpr_contact_subject" />
							<div class="cntctfrmpr_help_box">
								<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter subject...", 'contact_form' ); ?></div>
							</div>
						</div>
						<div class="cntctfrm_label cntctfrm_label_message">
							<label for="cntctfrmpr_contact_message"><?php echo $cntctfrm_options['cntctfrm_message_label']['en']; if ( 1 == $cntctfrm_options['cntctfrm_required_message_field'] ) echo '<span class="required"> *</span>'; ?></label>
						</div>
						<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_message_error']['en']; ?></div>
						<div class="cntctfrm_input cntctfrm_input_message">
							<textarea placeholder="<?php _e( "Please enter your message...", 'contact_form' ); ?>" rows="5" cols="30" name="cntctfrmpr_contact_message" id="cntctfrmpr_contact_message"></textarea>
							<div class="cntctfrmpr_help_box">
								<div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php _e( "Please enter your message...", 'contact_form' ); ?></div>
							</div>
						</div>
						<?php if ( 1 == $cntctfrm_options['cntctfrm_attachment'] ) { ?>
							<div class="cntctfrm_label cntctfrm_label_attachment">
								<label for="cntctfrmpr_contact_attachment"><?php echo $cntctfrm_options['cntctfrm_attachment_label']['en']; ?></label>
							</div>
							<div class="cntctfrmpr_error_text hidden" style="text-align: left;"><?php echo $cntctfrm_options['cntctfrm_attachment_error']['en']; ?></div>
							<div class="cntctfrm_input cntctfrm_input_attachment">
							<input type="file" name="cntctfrmpr_contact_attachment" id="cntctfrmpr_contact_attachment" style="float:left;" />
							<?php if ( 1 == $cntctfrm_options['cntctfrm_attachment_explanations'] ) { ?>
								<div class="cntctfrmpr_help_box cntctfrmpr_hidden_help_text_attach"><div class="cntctfrmpr_hidden_help_text" style="font-size: 12px; display: none;"><?php echo $cntctfrm_options['cntctfrm_attachment_tooltip']['en']; ?></div></div>
							<?php } ?>
							</div>
						<?php } ?>
						<?php if ( 1 == $cntctfrm_options['cntctfrm_send_copy'] ) { ?>
							<div class="cntctfrm_checkbox cntctfrm_checkbox_send_copy">
								<input type="checkbox" value="1" name="cntctfrmpr_contact_send_copy" id="cntctfrmpr_contact_send_copy" style="text-align: left; margin: 0;" />
								<label for="cntctfrmpr_contact_send_copy"><?php echo $cntctfrm_options['cntctfrm_send_copy_label']['en']; ?></label>
							</div>
						<?php } ?>
						<div class="cntctfrm_input cntctfrm_input_submit">
							<input type="submit" value="<?php echo $cntctfrm_options['cntctfrm_submit_label']['en']; ?>" style="cursor: pointer; margin: 0pt; text-align: center;margin-bottom:10px;" />
						</div>
					</div>
					<div id="cntctfrmpr_shortcode">
						<?php _e( "If you would like to add the Contact Form to your website, just copy and paste this shortcode to your post or page or widget:", 'contact_form' ); ?><br/>
						<div>
							<div id="cntctfrmpr_shortcode_code">
								<span class="cntctfrm_shortcode">[bestwebsoft_contact_form]</span>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			<?php } elseif ( 'go_pro' == $_GET['action'] ) {
				bws_go_pro_tab( $cntctfrm_plugin_info, $plugin_basename, 'contact_form.php', 'contact_form_pro.php', 'contact-form-pro/contact_form_pro.php', 'contact-form', '697c5e74f39779ce77850e11dbe21962', '77', isset( $go_pro_result['pro_plugin_is_activated'] ) );
			}?>
		</div>
	<?php }
}

/* Display contact form in front end - page or post */
if ( ! function_exists( 'cntctfrm_display_form' ) ) {
	function cntctfrm_display_form( $atts = array( 'lang' => 'en' ) ) {
		global $error_message, $cntctfrm_options, $cntctfrm_result, $cntctfrmmlt_ide, $cntctfrmmlt_active_plugin, $cntctfrm_form_count;

		$cntctfrm_form_count = empty( $cntctfrm_form_count ) ? 1 : ++$cntctfrm_form_count;
		$cntctfrm_form_countid = ( $cntctfrm_form_count == 1 ? '' : '_' . $cntctfrm_form_count );

		$content = "";

		/* Get options for the form with a definite identifier */
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'contact-form-multi/contact-form-multi.php' ) || is_plugin_active( 'contact-form-multi-pro/contact-form-multi-pro.php' ) ) {

			extract( shortcode_atts( array( 'id' => $cntctfrmmlt_ide, 'lang' => 'en' ), $atts ) );
			if ( isset( $atts['id'] ) )
				$cntctfrm_options = get_option( 'cntctfrmmlt_options_' . $atts['id'] );
			else
				$cntctfrm_options = get_option( 'cntctfrmmlt_options' );
		} else {
			$cntctfrm_options = get_option( 'cntctfrm_options' );
			extract( shortcode_atts( array( 'lang' => 'en' ), $atts ) );
		}
		/* check lang and replace with en default if need */
		foreach ( $cntctfrm_options as $key => $value ) {
			if ( is_array( $value ) && array_key_exists( 'en', $value ) && ( ! array_key_exists( $lang, $value ) || ( isset( $cntctfrm_options[ $key ][ $lang ] ) && $cntctfrm_options[ $key ][ $lang ] == '' ) ) ) {
				$cntctfrm_options[ $key ][ $lang ] = $cntctfrm_options[ $key ]['en'];
			}
		}

		if ( '80' != $_SERVER["SERVER_PORT"] || (  isset( $_SERVER["HTTPS"] ) && '443' != $_SERVER["SERVER_PORT"] && strtolower( $_SERVER["HTTPS"] ) == "on" ) )
			$page_url = ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ? "https://" : "http://" ) . $_SERVER[ $cntctfrm_options['cntctfrm_site_name_parameter'] ] . ':' . $_SERVER["SERVER_PORT"] . strip_tags( $_SERVER["REQUEST_URI"] ) . '#cntctfrm_contact_form';
		else
			$page_url = ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ? "https://" : "http://" ) . $_SERVER[ $cntctfrm_options['cntctfrm_site_name_parameter'] ] . strip_tags( $_SERVER["REQUEST_URI"] ) . '#cntctfrm_contact_form';

		/* If contact form submited */

		$cntctfrm_form_submited = isset( $_POST['cntctfrm_form_submited'] ) ? $_POST['cntctfrm_form_submited'] : 0;

		$name = ( isset( $_POST['cntctfrm_contact_name'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_name'] ) ) : "";
		$address = ( isset( $_POST['cntctfrm_contact_address'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_address'] ) ) : "";
		$email = ( isset( $_POST['cntctfrm_contact_email'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_email'] ) ) : "";
		$subject = ( isset( $_POST['cntctfrm_contact_subject'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_subject'] ) ) : "";
		$message = ( isset( $_POST['cntctfrm_contact_message'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_message'] ) ) : "";
		$phone = ( isset( $_POST['cntctfrm_contact_phone'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_phone'] ) ) : "";

		$name = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $name ) ) );
		$address = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $address ) ) );
		$email = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $email ) ) );
		$subject = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $subject ) ) );
		$message = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $message ) ) );
		$phone = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $phone ) ) );

		$send_copy = ( isset( $_POST['cntctfrm_contact_send_copy'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) ? $_POST['cntctfrm_contact_send_copy'] : "";
		/* If it is good */

		if ( true === $cntctfrm_result && $cntctfrm_form_count == $cntctfrm_form_submited ) {
			$_SESSION['cntctfrm_send_mail'] = true;

			if ( 1 == $cntctfrm_options['cntctfrm_action_after_send'] )
				$content .= '<div id="cntctfrm_contact_form' . $cntctfrm_form_countid . '"><div id="cntctfrm_thanks">' . $cntctfrm_options['cntctfrm_thank_text'][ $lang ] . '</div></div>';
			else
				$content .= "<script type='text/javascript'>window.location.href = '" . $cntctfrm_options['cntctfrm_redirect_url'] . "';</script>";

		} elseif ( false === $cntctfrm_result && $cntctfrm_form_count == $cntctfrm_form_submited ) {
			/* If email not be delivered */
			$error_message['error_form'] = __( "Sorry, email message could not be delivered.", 'contact_form' );
		}

		if ( true !== $cntctfrm_result || $cntctfrm_form_count != $cntctfrm_form_submited ) {
			$_SESSION['cntctfrm_send_mail'] = false;
			/* Output form */
			$content .= '<form method="post" id="cntctfrm_contact_form' . $cntctfrm_form_countid . '" class="cntctfrm_contact_form"';
			$content .= ' action="' . $page_url .  $cntctfrm_form_countid . '" enctype="multipart/form-data">';
			if ( isset( $error_message['error_form'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) {
				$content .= '<div class="cntctfrm_error_text">' . $error_message['error_form'].'</div>';
			}

			if ( 1 == $cntctfrm_options['cntctfrm_display_name_field'] ) {
				$content .= '<div class="cntctfrm_label cntctfrm_label_name">
					<label for="cntctfrm_contact_name' . $cntctfrm_form_countid . '">' . $cntctfrm_options['cntctfrm_name_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_name_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' );
				$content .= '</div>';
				if ( isset( $error_message['error_name'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) {
					$content .= '<div class="cntctfrm_error_text">' . $error_message['error_name'] . '</div>';
				}
				$content .= '<div class="cntctfrm_input cntctfrm_input_name">
					<input class="text" type="text" size="40" value="' . $name . '" name="cntctfrm_contact_name" id="cntctfrm_contact_name' . $cntctfrm_form_countid . '" />';
				$content .= '</div>';
			}

			if ( 1 == $cntctfrm_options['cntctfrm_display_address_field'] ) {
				$content .= '<div class="cntctfrm_label cntctfrm_label_address">
						<label for="cntctfrm_contact_address' . $cntctfrm_form_countid . '">' . $cntctfrm_options['cntctfrm_address_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_address_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '</div>';
				if ( isset( $error_message['error_address'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) {
					$content .= '<div class="cntctfrm_error_text">' . $error_message['error_address'] . '</div>';
				}
				$content .= '<div class="cntctfrm_input cntctfrm_input_address">
						<input class="text" type="text" size="40" value="' . $address . '" name="cntctfrm_contact_address" id="cntctfrm_contact_address' . $cntctfrm_form_countid . '" />
					</div>
					';
			}

			$content .= '<div class="cntctfrm_label cntctfrm_label_email">
					<label for="cntctfrm_contact_email' . $cntctfrm_form_countid . '">' . $cntctfrm_options['cntctfrm_email_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_email_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
				</div>';
			if ( isset( $error_message['error_email'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) {
				$content .= '<div class="cntctfrm_error_text">' . $error_message['error_email'] . '</div>';
			}
			$content .= '<div class="cntctfrm_input cntctfrm_input_email">
					<input class="text" type="text" size="40" value="' . $email . '" name="cntctfrm_contact_email" id="cntctfrm_contact_email' . $cntctfrm_form_countid . '" />
				</div>
			';

			if ( 1 == $cntctfrm_options['cntctfrm_display_phone_field'] ) {
				$content .= '<div class="cntctfrm_label cntctfrm_label_phone">
						<label for="cntctfrm_contact_phone' . $cntctfrm_form_countid . '">' . $cntctfrm_options['cntctfrm_phone_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_phone_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
					</div>';
				if ( isset( $error_message['error_phone'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) {
					$content .= '<div class="cntctfrm_error_text">' . $error_message['error_phone'] . '</div>';
				}
				$content .= '<div class="cntctfrm_input cntctfrm_input_phone">
						<input class="text" type="text" size="40" value="' . $phone . '" name="cntctfrm_contact_phone" id="cntctfrm_contact_phone' . $cntctfrm_form_countid . '" />
					</div>
					';
			}
			$content .= '<div class="cntctfrm_label cntctfrm_label_subject">
					<label for="cntctfrm_contact_subject' . $cntctfrm_form_countid . '">' . $cntctfrm_options['cntctfrm_subject_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_subject_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
				</div>';
			if ( isset( $error_message['error_subject'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) {
				$content .= '<div class="cntctfrm_error_text">' . $error_message['error_subject'] . '</div>';
			}
			$content .= '<div class="cntctfrm_input cntctfrm_input_subject">
					<input class="text" type="text" size="40" value="' . $subject . '" name="cntctfrm_contact_subject" id="cntctfrm_contact_subject' . $cntctfrm_form_countid . '" />
				</div>

				<div class="cntctfrm_label cntctfrm_label_message">
					<label for="cntctfrm_contact_message' . $cntctfrm_form_countid . '">' . $cntctfrm_options['cntctfrm_message_label'][ $lang ] . ( $cntctfrm_options['cntctfrm_required_message_field'] == 1 ? ' <span class="required">' . $cntctfrm_options['cntctfrm_required_symbol'] . '</span></label>' : '</label>' ) . '
				</div>';
			if ( isset( $error_message['error_message'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) {
				$content .= '<div class="cntctfrm_error_text">' . $error_message['error_message'] . '</div>';
			}
			$content .= '<div class="cntctfrm_input cntctfrm_input_message">
					<textarea rows="5" cols="30" name="cntctfrm_contact_message" id="cntctfrm_contact_message' . $cntctfrm_form_countid . '">' . $message . '</textarea>
				</div>';
			if ( 1 == $cntctfrm_options['cntctfrm_attachment'] ) {
				$content .= '<div class="cntctfrm_label cntctfrm_label_attachment">
						<label for="cntctfrm_contact_attachment' . $cntctfrm_form_countid . '">' . $cntctfrm_options['cntctfrm_attachment_label'][ $lang ] . '</label>
					</div>';
				if ( isset( $error_message['error_attachment'] ) && $cntctfrm_form_count == $cntctfrm_form_submited ) {
					$content .= '<div class="cntctfrm_error_text">' . $error_message['error_attachment'] . '</div>';
				}
				$content .= '<div class="cntctfrm_input cntctfrm_input_attachment">
						<input type="file" name="cntctfrm_contact_attachment" id="cntctfrm_contact_attachment' . $cntctfrm_form_countid . '"' . ( isset( $error_message['error_attachment'] ) ? "class='error'": "" ) . ' />';
				if ( 1 == $cntctfrm_options['cntctfrm_attachment_explanations'] ) {
						$content .= '<label class="cntctfrm_contact_attachment_extensions"><br />' . $cntctfrm_options['cntctfrm_attachment_tooltip'][ $lang ] . '</label>';
				}
				$content .= '
				</div>';
			}
			if ( 1 == $cntctfrm_options['cntctfrm_send_copy'] ) {
				$content .= '<div class="cntctfrm_checkbox cntctfrm_checkbox_send_copy">
						<input type="checkbox" value="1" name="cntctfrm_contact_send_copy" id="cntctfrm_contact_send_copy"' . ( $send_copy == '1' ? ' checked="checked" ' : "" ) . ' />
						<label for="cntctfrm_contact_send_copy">' . $cntctfrm_options['cntctfrm_send_copy_label'][ $lang ] . '</label>
					</div>';
			}

			if ( has_filter( 'cntctfrm_display_captcha' ) ) {
				$content .= '<div class="cntctfrm_input cntctfrm_input_captcha">';
				$content .= apply_filters( 'cntctfrm_display_captcha' , ( $cntctfrm_form_count == $cntctfrm_form_submited ) ? $error_message : false );
				$content .= '</div>';
			}

			$content .= '<div class="cntctfrm_input cntctfrm_input_submit">';
			if ( isset( $atts['id'] ) )
				$content .= '<input type="hidden" value="' . esc_attr( $atts['id'] ) . '" name="cntctfrmmlt_shortcode_id">';
			$content .= '<input type="hidden" value="send" name="cntctfrm_contact_action"><input type="hidden" value="Version: 3.30" />
					<input type="hidden" value="' . esc_attr( $lang ) . '" name="cntctfrm_language">
					<input type="hidden" value="' . $cntctfrm_form_count . '" name="cntctfrm_form_submited">
					<input type="submit" value="'. $cntctfrm_options['cntctfrm_submit_label'][ $lang ] . '" class="cntctfrm_contact_submit" />
				</div>
				</form>';
		}
		return $content ;
	}
}

if ( ! function_exists( 'cntctfrm_check_and_send' ) ) {
	function cntctfrm_check_and_send() {
		global $cntctfrm_result, $cntctfrm_options;
		if ( ( isset( $_POST['cntctfrm_contact_action'] ) && isset( $_POST['cntctfrm_language'] ) ) || true === $cntctfrm_result ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( 'contact-form-multi/contact-form-multi.php' ) || is_plugin_active( 'contact-form-multi-pro/contact-form-multi-pro.php' ) ) {

				if ( ! isset( $_POST['cntctfrmmlt_shortcode_id'] ) )
					$cntctfrm_options = get_option( 'cntctfrmmlt_options' );
				else
					$cntctfrm_options = get_option( 'cntctfrmmlt_options_' . $_POST['cntctfrmmlt_shortcode_id'] );

			} else {
				$cntctfrm_options = get_option( 'cntctfrm_options' );
			}

			if ( isset( $_POST['cntctfrm_contact_action'] ) ) {
				/* Check all input data */
				$cntctfrm_result = cntctfrm_check_form();
			}
			/* If it is good */
			if ( true === $cntctfrm_result ) {
				$_SESSION['cntctfrm_send_mail'] = true;
				if ( 0 == $cntctfrm_options['cntctfrm_action_after_send'] ) {
					wp_redirect( $cntctfrm_options['cntctfrm_redirect_url'] );
					exit;
				}
			}
		}
	}
}

/* Check all input data */
if ( ! function_exists( 'cntctfrm_check_form' ) ) {
	function cntctfrm_check_form() {
		global $error_message, $cntctfrm_options;
		$language = isset( $_POST['cntctfrm_language'] ) ? $_POST['cntctfrm_language'] : 'en';
		$path_of_uploaded_file = $cntctfrm_result = "";
		/* Error messages array */
		$error_message = array();

		$name = isset( $_POST['cntctfrm_contact_name'] ) ?  htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_name'] ) ) : "";
		$address = isset( $_POST['cntctfrm_contact_address'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_address'] ) ) : "";
		$email = isset( $_POST['cntctfrm_contact_email'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_email'] ) ) : "";
		$subject = isset( $_POST['cntctfrm_contact_subject'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_subject'] ) ) : "";
		$message = isset( $_POST['cntctfrm_contact_message'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_message'] ) ) : "";
		$phone = isset( $_POST['cntctfrm_contact_phone'] ) ? htmlspecialchars( stripslashes( $_POST['cntctfrm_contact_phone'] ) ) : "";

		$name = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $name ) ) );
		$address = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $address ) ) );
		$email = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $email ) ) );
		$subject = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $subject ) ) );
		$message = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $message ) ) );
		$phone = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $phone ) ) );

		/* check language and replace with en default if need */
		if ( ! in_array( $language, $cntctfrm_options['cntctfrm_language'] ) ) {
			foreach ( $cntctfrm_options as $key => $value ) {
				if ( is_array( $value ) && array_key_exists( 'en', $value ) && ( ! array_key_exists( $language, $value ) || ( isset( $cntctfrm_options[ $key ][ $language ] ) && $cntctfrm_options[ $key ][ $language ] == '' ) ) ) {
					$cntctfrm_options[ $key ][ $language ] = $cntctfrm_options[ $key ]['en'];
				}
			}
		}

		if ( 1 == $cntctfrm_options['cntctfrm_required_name_field'] && 1 == $cntctfrm_options['cntctfrm_display_name_field'] )
			$error_message['error_name'] = $cntctfrm_options['cntctfrm_name_error'][ $language ];
		if ( 1 == $cntctfrm_options['cntctfrm_required_address_field'] && 1 == $cntctfrm_options['cntctfrm_display_address_field'] )
			$error_message['error_address'] = $cntctfrm_options['cntctfrm_address_error'][ $language ];
		if ( 1 == $cntctfrm_options['cntctfrm_required_email_field'] )
			$error_message['error_email'] = $cntctfrm_options['cntctfrm_email_error'][ $language ];
		if ( 1 == $cntctfrm_options['cntctfrm_required_subject_field'] )
			$error_message['error_subject'] = $cntctfrm_options['cntctfrm_subject_error'][ $language ];
		if ( 1 == $cntctfrm_options['cntctfrm_required_message_field'] )
			$error_message['error_message'] = $cntctfrm_options['cntctfrm_message_error'][ $language ];
		if ( 1 == $cntctfrm_options['cntctfrm_required_phone_field'] && 1 == $cntctfrm_options['cntctfrm_display_phone_field'] )
			$error_message['error_phone'] = $cntctfrm_options['cntctfrm_phone_error'][ $language ];
		$error_message['error_form'] = $cntctfrm_options['cntctfrm_form_error'][ $language ];
		if ( 1 == $cntctfrm_options['cntctfrm_attachment'] ) {
			global $path_of_uploaded_file, $mime_type;
			$mime_type= array(
				'html'=>'text/html',
				'htm'=>'text/html',
				'txt'=>'text/plain',
				'css'=>'text/css',
				'gif'=>'image/gif',
				'png'=>'image/x-png',
				'jpeg'=>'image/jpeg',
				'jpg'=>'image/jpeg',
				'JPG'=>'image/jpeg',
				'jpe'=>'image/jpeg',
				'TIFF'=>'image/tiff',
				'tiff'=>'image/tiff',
				'tif'=>'image/tiff',
				'TIF'=>'image/tiff',
				'bmp'=>'image/x-ms-bmp',
				'BMP'=>'image/x-ms-bmp',
				'ai'=>'application/postscript',
				'eps'=>'application/postscript',
				'ps'=>'application/postscript',
				'rtf'=>'application/rtf',
				'pdf'=>'application/pdf',
				'doc'=>'application/msword',
				'docx'=>'application/msword',
				'xls'=>'application/vnd.ms-excel',
				'xlsx'=>'application/vnd.ms-excel',
				'zip'=>'application/zip',
				'rar'=>'application/rar',
				'wav'=>'audio/wav',
				'mp3'=>'audio/mp3',
				'ppt'=>'application/vnd.ms-powerpoint',
				'aar'=>'application/sb-replay',
				'sce'=>'application/sb-scenario' );
			$error_message['error_attachment'] = $cntctfrm_options['cntctfrm_attachment_error'][ $language ];
		}
		/* Check information wich was input in fields */
		if ( 1 == $cntctfrm_options['cntctfrm_display_name_field'] && 1 == $cntctfrm_options['cntctfrm_required_name_field'] && "" != $name )
			unset( $error_message['error_name'] );
		if ( 1 == $cntctfrm_options['cntctfrm_display_address_field'] && 1 == $cntctfrm_options['cntctfrm_required_address_field'] && "" != $address )
			unset( $error_message['error_address'] );
		if ( 1 == $cntctfrm_options['cntctfrm_required_email_field'] && "" != $email &&
			is_email( trim( stripslashes( $email ) ) ) )
			unset( $error_message['error_email'] );
		if ( 1 == $cntctfrm_options['cntctfrm_display_phone_field'] && 1 == $cntctfrm_options['cntctfrm_required_phone_field'] && "" != $phone )
			unset( $error_message['error_phone'] );
		if ( 1 == $cntctfrm_options['cntctfrm_required_subject_field'] && "" != $subject )
			unset( $error_message['error_subject'] );
		if ( 1 == $cntctfrm_options['cntctfrm_required_message_field'] && "" != $message )
			unset( $error_message['error_message'] );
		/* If captcha plugin exists */
		if ( ! apply_filters( 'cntctfrm_check_form', $_POST ) )
			$error_message['error_captcha'] = $cntctfrm_options['cntctfrm_captcha_error'][ $language ];
		if ( isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) {
			if ( is_multisite() ) {
				if ( defined('UPLOADS') ) {
					if ( ! is_dir( ABSPATH . UPLOADS ) ) {
						wp_mkdir_p( ABSPATH . UPLOADS );
					}
					$path_of_uploaded_file = ABSPATH . UPLOADS . 'cntctfrm_' . md5( $_FILES["cntctfrm_contact_attachment"]["name"] . time() . $email ) . '_' . $_FILES["cntctfrm_contact_attachment"]["name"];
				} else if ( defined( 'BLOGUPLOADDIR' ) ) {
					if ( ! is_dir( BLOGUPLOADDIR ) ) {
						wp_mkdir_p( BLOGUPLOADDIR );
					}
					$path_of_uploaded_file = BLOGUPLOADDIR . 'cntctfrm_' . md5( $_FILES["cntctfrm_contact_attachment"]["name"] . time() . $email ) . '_' . $_FILES["cntctfrm_contact_attachment"]["name"];
				} else {
					$uploads = wp_upload_dir();
					if ( ! isset( $uploads['path'] ) && isset( $uploads['error'] ) )
						$error_message['error_attachment'] = $uploads['error'];
					else
						$path_of_uploaded_file = $uploads['path'] . "/" . 'cntctfrm_' . md5( $_FILES["cntctfrm_contact_attachment"]["name"] . time() . $email ) . '_' . $_FILES["cntctfrm_contact_attachment"]["name"];
				}
			} else {
				$uploads = wp_upload_dir();
				if ( ! isset( $uploads['path'] ) && isset ( $uploads['error'] ) )
					$error_message['error_attachment'] = $uploads['error'];
				else
					$path_of_uploaded_file = $uploads['path'] . "/" . 'cntctfrm_' . md5( $_FILES["cntctfrm_contact_attachment"]["name"] . time() . $email ) . '_' . $_FILES["cntctfrm_contact_attachment"]["name"];
			}
			$tmp_path = $_FILES["cntctfrm_contact_attachment"]["tmp_name"];
			$path_info = pathinfo( $path_of_uploaded_file );

			if ( array_key_exists ( $path_info['extension'], $mime_type ) ) {
				if ( is_uploaded_file( $tmp_path ) ) {
					if ( move_uploaded_file( $tmp_path, $path_of_uploaded_file ) ) {
						do_action( 'cntctfrm_get_attachment_data', $path_of_uploaded_file );
						unset( $error_message['error_attachment'] );
					} else {
						$letter_upload_max_size = substr( ini_get( 'upload_max_filesize' ), -1 );
						// $upload_max_size = substr( ini_get('upload_max_filesize'), 0, -1 );
						$upload_max_size = '1';
						switch( strtoupper( $letter_upload_max_size ) ) {
							case 'P':
								$upload_max_size *= 1024;
							case 'T':
								$upload_max_size *= 1024;
							case 'G':
								$upload_max_size *= 1024;
							case 'M':
								$upload_max_size *= 1024;
							case 'K':
								$upload_max_size *= 1024;
								break;
						}
						if ( isset( $upload_max_size ) && isset( $_FILES["cntctfrm_contact_attachment"]["size"] ) &&
							 $_FILES["cntctfrm_contact_attachment"]["size"] <= $upload_max_size ) {
							$error_message['error_attachment'] = $cntctfrm_options['cntctfrm_attachment_move_error'][ $language ];
						} else {
							$error_message['error_attachment'] = $cntctfrm_options['cntctfrm_attachment_size_error'][ $language ];
						}
					}
				} else {
					$error_message['error_attachment'] = $cntctfrm_options['cntctfrm_attachment_upload_error'][ $language ];
				}
			}
		} else {
			unset( $error_message['error_attachment'] );
		}
		if ( 1 == count( $error_message ) ) {
			unset( $error_message['error_form'] );
			/* If all is good - send mail */
			$cntctfrm_result = cntctfrm_send_mail();
			do_action( 'cntctfrm_check_dispatch', $cntctfrm_result );
		}
		return $cntctfrm_result;
	}
}

/* Send mail function */
if( ! function_exists( 'cntctfrm_send_mail' ) ) {
	function cntctfrm_send_mail() {
		global $cntctfrm_options, $path_of_uploaded_file, $wp_version, $wpdb;
		$to = $headers  = "";

		$lang = isset( $_POST['cntctfrm_language'] ) ? $_POST['cntctfrm_language'] : 'en';

		$name = isset( $_POST['cntctfrm_contact_name'] ) ? $_POST['cntctfrm_contact_name'] : "";
		$address = isset( $_POST['cntctfrm_contact_address'] ) ? $_POST['cntctfrm_contact_address'] : "";
		$email = isset( $_POST['cntctfrm_contact_email'] ) ? stripslashes( $_POST['cntctfrm_contact_email'] ) : "";
		$subject = isset( $_POST['cntctfrm_contact_subject'] ) ? $_POST['cntctfrm_contact_subject'] : "";
		$message = isset( $_POST['cntctfrm_contact_message'] ) ? $_POST['cntctfrm_contact_message'] : "";
		$phone = isset( $_POST['cntctfrm_contact_phone'] ) ? $_POST['cntctfrm_contact_phone'] : "";
		$user_agent = cntctfrm_clean_input( $_SERVER['HTTP_USER_AGENT'] );

		$name = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $name ) ) ) );
		$address = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $address ) ) ) );
		$email = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $email ) ) ) );
		$subject = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $subject ) ) ) );
		$message = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $message ) ) ) );
		$phone = stripslashes( strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', $phone ) ) ) );

		if ( isset( $_SESSION['cntctfrm_send_mail'] ) && true == $_SESSION['cntctfrm_send_mail'] )
			return true;

		if ( 'user' == $cntctfrm_options['cntctfrm_select_email'] ) {
			if ( '3.3' > $wp_version && function_exists('get_userdatabylogin') && false !== $user = get_userdatabylogin( $cntctfrm_options['cntctfrm_user_email'] ) ) {
				$to = $user->user_email;
			} elseif ( false !== $user = get_user_by( 'login', $cntctfrm_options['cntctfrm_user_email'] ) )
				$to = $user->user_email;
		} else {
			$to = $cntctfrm_options['cntctfrm_custom_email'];
		}

		if ( "" == $to ) {
			/* If email options are not certain choose admin email */
			$to = get_option("admin_email");
		}
		if ( "" != $to ) {
			$user_info_string = $userdomain = $form_action_url = '';
			$attachments = array();

			if ( 'on' == strtolower( getenv('HTTPS') ) ) {
				$form_action_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			} else {
				$form_action_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			}

			if ( 1 == $cntctfrm_options['cntctfrm_display_add_info']) {
				$userdomain = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
				if ( 1 == $cntctfrm_options['cntctfrm_display_add_info'] ||
						1 == $cntctfrm_options['cntctfrm_display_sent_from'] ||
						1 == $cntctfrm_options['cntctfrm_display_coming_from'] ||
						1 == $cntctfrm_options['cntctfrm_display_user_agent'] ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td><br /></td><td><br /></td></tr>';
				}
				if ( 1 == $cntctfrm_options['cntctfrm_display_sent_from'] ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td>' . __( 'Sent from (ip address)', 'contact_form' ) . ':</td><td>' . $_SERVER['REMOTE_ADDR'] . " ( " . $userdomain . " )" . '</td></tr>';
					else
						$user_info_string .= __( 'Sent from (ip address)', 'contact_form' ) . ': ' . $_SERVER['REMOTE_ADDR'] . " ( " . $userdomain . " )" . "\n";
				}
				if ( 1 == $cntctfrm_options['cntctfrm_display_date_time'] ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td>' . __('Date/Time', 'contact_form') . ':</td><td>' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ) . '</td></tr>';
					else
						$user_info_string .= __( 'Date/Time', 'contact_form' ) . ': ' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ) . "\n";
				}
				if ( 1 == $cntctfrm_options['cntctfrm_display_coming_from'] ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td>' . __( 'Sent from (referer)', 'contact_form' ) . ':</td><td>' . $form_action_url . '</td></tr>';
					else
						$user_info_string .= __( 'Sent from (referer)', 'contact_form' ) . ': ' . $form_action_url . "\n";
				}
				if ( 1 == $cntctfrm_options['cntctfrm_display_user_agent'] ) {
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$user_info_string .= '<tr><td>' . __( 'Using (user agent)', 'contact_form' ) . ':</td><td>' . $user_agent . '</td></tr>';
					else
						$user_info_string .= __( 'Using (user agent)', 'contact_form' ) . ': ' . $user_agent . "\n";
				}
			}
			/* Message */
			if ( 1 == $cntctfrm_options['cntctfrm_html_email'] ) {
				$message_text = '<html>
				<head>
					<title>' . __( "Contact from", 'contact_form' ) . ' ' . get_bloginfo('name') . '</title>
				</head>
				<body>
					<table>';
				if ( 1 == $cntctfrm_options['cntctfrm_display_name_field'] ) {
					$message_text .= '<tr><td width="160">';
					$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_name_label'][ $lang ] : __( "Name", 'contact_form' );
					$message_text .= '</td><td>' . $name . '</td></tr>';
				}

				if ( 1 == $cntctfrm_options['cntctfrm_display_address_field'] ) {
					$message_text .= '<tr><td>';
					$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_address_label'][ $lang ] : __( "Address", 'contact_form' );
					$message_text .= '</td><td>' . $address . '</td></tr>';
				}

				$message_text .= '<tr><td>';
				$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_email_label'][ $lang ] : __( "Email", 'contact_form' );
				$message_text .= '</td><td>' . $email . '</td></tr>';

				if ( 1 == $cntctfrm_options['cntctfrm_display_phone_field'] ) {
					$message_text .= '<tr><td>';
					$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_phone_label'][ $lang ] : __( "Phone", 'contact_form' );
					$message_text .= '</td><td>' . $phone . '</td></tr>';
				}

				$message_text .= '<tr><td>';
				$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_subject_label'][ $lang ] : __( "Subject", 'contact_form' );
				$message_text .= '</td><td>' . $subject . '</td></tr>
						<tr><td>';
				$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_message_label'][ $lang ] : __( "Message", 'contact_form' );
				$message_text .= '</td><td>' . $message . '</td>
						</tr>
						<tr><td>' . __( "Site", 'contact_form' ) . '</td><td>' . get_bloginfo("url") . '</td></tr>
						<tr>
							<td><br /></td><td><br /></td>
						</tr>';
				$message_text_for_user = $message_text . '</table></body></html>';
				$message_text .= $user_info_string . '</table></body></html>';
			} else {
				$message_text = '';
				if ( 1 == $cntctfrm_options['cntctfrm_display_name_field'] ) {
					$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_name_label'][ $lang ] : __( "Name", 'contact_form' );
					$message_text .= ': ' . $name . "\n";
				}
				if ( 1 == $cntctfrm_options['cntctfrm_display_address_field'] ) {
					$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_address_label'][ $lang ] : __( "Address", 'contact_form' );
					$message_text .= ': ' . $address . "\n";
				}
				$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_email_label'][ $lang ] : __( "Email", 'contact_form' );
				$message_text .= ': ' . $email . "\n";
				if ( 1 == $cntctfrm_options['cntctfrm_display_phone_field'] ) {
					$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_phone_label'][ $lang ] : __( "Phone", 'contact_form' );
					$message_text .= ': ' . $phone . "\n";
				}
				$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_subject_label'][ $lang ] : __( "Subject", 'contact_form' );
				$message_text .= ': ' . $subject . "\n";
				$message_text .= ( 1 == $cntctfrm_options['cntctfrm_change_label_in_email'] ) ? $cntctfrm_options['cntctfrm_message_label'][ $lang ] : __( "Message", 'contact_form' );
				$message_text .= ': ' . $message . "\n" .
						__( "Site", 'contact_form' ) . ': ' . get_bloginfo("url") . "\n"
						 . "\n";
				$message_text_for_user = $message_text;
				$message_text .= $user_info_string;
			}

			do_action( 'cntctfrm_get_mail_data', $to, $name, $email, $address, $phone, $subject, $message, $form_action_url, $user_agent, $userdomain );

			if ( ! function_exists( 'is_plugin_active' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			/* 'from' name */
			$from_field_name = ( 'custom' == $cntctfrm_options['cntctfrm_select_from_field'] ) ? stripslashes( $cntctfrm_options['cntctfrm_from_field'] ) : $name;
			/* 'from' email */
			$from_email = ( 'custom' == $cntctfrm_options['cntctfrm_from_email'] ) ? stripslashes( $cntctfrm_options['cntctfrm_custom_from_email'] ) : stripslashes( $email );

			if ( ( is_plugin_active( 'email-queue/email-queue.php' ) || is_plugin_active( 'email-queue-pro/email-queue-pro.php' ) ) && function_exists( 'mlq_if_mail_plugin_is_in_queue' ) && mlq_if_mail_plugin_is_in_queue( plugin_basename( __FILE__ ) ) ) {
				/* if email-queue plugin is active and this plugin's "in_queue" status is 'ON' */
				/* attachment path */
				$attachment_file = ( 1 == $cntctfrm_options['cntctfrm_attachment'] && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) ? $path_of_uploaded_file : '';
				/* headers */
				/* content type */
				$headers .= ( 1 == $cntctfrm_options['cntctfrm_html_email'] ) ? 'Content-type: text/html; charset=utf-8' . "\n" : 'Content-type: text/plain; charset=utf-8' . "\n" ;
				/* 'from' name & email */
				$headers .= 'From: ' . $from_field_name . ' <' . $from_email . '>';
				/* send copy */
				if ( isset( $_POST['cntctfrm_contact_send_copy'] ) && 1 == $_POST['cntctfrm_contact_send_copy'] ) {
					do_action( 'cntctfrm_get_mail_data_for_mlq', plugin_basename( __FILE__ ), $email, $subject, $message_text_for_user, $attachment_file, $headers );
				}
				global $mlq_mail_result;
				do_action( 'cntctfrm_get_mail_data_for_mlq', plugin_basename( __FILE__ ), $to, $subject, $message_text, $attachment_file, $headers );
				/* return $mail_result = true if email-queue has successfully inserted mail in its DB; in other case - return false */
				return $mail_result = $mlq_mail_result;
			} else {
				if ( 'wp-mail' == $cntctfrm_options['cntctfrm_mail_method'] ) {
					/* To send HTML mail, the Content-type header must be set */
					if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
						$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
					else
						$headers .= 'Content-type: text/plain; charset=utf-8' . "\n";

					/* Additional headers */
					$headers .= 'From: ' . $from_field_name . ' <' . $from_email . '>';

					if ( 1 == $cntctfrm_options['cntctfrm_attachment'] && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) {
						$path_parts = pathinfo( $path_of_uploaded_file );
						$path_of_uploaded_file_changed = $path_parts['dirname'] . '/' . preg_replace( '/^cntctfrm_[A-Z,a-z,0-9]{32}_/i', '', $path_parts['basename'] );

						if ( ! @copy( $path_of_uploaded_file, $path_of_uploaded_file_changed ) )
							$path_of_uploaded_file_changed = $path_of_uploaded_file;

						$attachments = array( $path_of_uploaded_file_changed );
					}

					if ( isset( $_POST['cntctfrm_contact_send_copy'] ) && 1 == $_POST['cntctfrm_contact_send_copy'] )
						wp_mail( $email, $subject, $message_text_for_user, $headers, $attachments );

					/* Mail it */
					$mail_result = wp_mail( $to, $subject, $message_text, $headers, $attachments );
					/* Delete attachment */
					if ( 1 == $cntctfrm_options['cntctfrm_attachment'] && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"]
						&& $path_of_uploaded_file_changed != $path_of_uploaded_file ) {
						@unlink( $path_of_uploaded_file_changed );
					}
					if ( 1 == $cntctfrm_options['cntctfrm_attachment'] && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"] && '1' == $cntctfrm_options['cntctfrm_delete_attached_file'] ) {
						@unlink( $path_of_uploaded_file );
					}
					return $mail_result;
				} else {
					/* Set headers */
					$headers  .= 'MIME-Version: 1.0' . "\n";

					if ( 1 == $cntctfrm_options['cntctfrm_attachment'] && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) {
						$message_block = $message_text;

						/* Additional headers */
						$headers .= 'From: ' . $from_field_name . ' <' . $from_email . '>' . "\n";

						$bound_text = "jimmyP123";

						$bound = "--" . $bound_text . "";

						$bound_last = "--" . $bound_text . "--";

						$headers .= "Content-Type: multipart/mixed; boundary=\"$bound_text\"";

						$message_text = __( "If you can see this MIME, it means that the MIME type is not supported by your email client!", "contact_form" ) . "\n";

						if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
							$message_text .= $bound . "\n" . "Content-Type: text/html; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block . "\n\n";
						else
							$message_text .= $bound . "\n" . "Content-Type: text/plain; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message_block . "\n\n";


						$file = file_get_contents( $path_of_uploaded_file );
						$message_text .= $bound . "\n";

						$message_text .= "Content-Type: application/octet-stream; name=\"" . $_FILES["cntctfrm_contact_attachment"]["name"] . "\"\n" .
						"Content-Description: " . basename( $path_of_uploaded_file ) . "\n" .
						"Content-Disposition: attachment;\n" . " filename=\"" . $_FILES["cntctfrm_contact_attachment"]["name"] ."\"; size=" . filesize( $path_of_uploaded_file ) . ";\n" .
						"Content-Transfer-Encoding: base64\n\n" . chunk_split( base64_encode( $file ) ) . "\n\n";
							$message_text .= $bound_last;
					} else {
						/* To send HTML mail, header must be set */
						if ( 1 == $cntctfrm_options['cntctfrm_html_email'] )
							$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
						else
							$headers .= 'Content-type: text/plain; charset=utf-8' . "\n";

						/* Additional headers */
						$headers .= 'From: ' . $from_field_name . ' <' . $from_email . '>' . "\n";
					}
					if ( isset( $_POST['cntctfrm_contact_send_copy'] ) && 1 == $_POST['cntctfrm_contact_send_copy'] )
						@mail( $email, $subject, $message_text_for_user, $headers );

					$mail_result = @mail( $to, $subject , $message_text, $headers );
					/* Delete attachment */
					if ( 1 == $cntctfrm_options['cntctfrm_attachment'] && isset( $_FILES["cntctfrm_contact_attachment"]["tmp_name"] ) && "" != $_FILES["cntctfrm_contact_attachment"]["tmp_name"] && '1' == $cntctfrm_options['cntctfrm_delete_attached_file'] ) {
						@unlink( $path_of_uploaded_file );
					}
					return $mail_result;
				}
			}
		}
		return false;
	}
}

/**
 * Function that is used by email-queue to check for compatibility
 * @return void
 */
if ( ! function_exists( 'cntctfrm_check_for_compatibility_with_mlq' ) ) {
	function cntctfrm_check_for_compatibility_with_mlq() {
		return false;
	}
}

if ( ! function_exists ( 'cntctfrm_plugin_action_links' ) ) {
	function cntctfrm_plugin_action_links( $links, $file ) {
		if ( ! is_network_admin() ) {
			/* Static so we don't call plugin_basename on every plugin row. */
			static $this_plugin;
			if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);

			if ( $file == $this_plugin ) {
				$settings_link = '<a href="admin.php?page=contact_form.php">' . __( 'Settings', 'contact_form' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
}
/* End function cntctfrm_plugin_action_links */

if ( ! function_exists ( 'cntctfrm_register_plugin_links' ) ) {
	function cntctfrm_register_plugin_links( $links, $file ) {
		$base = plugin_basename(__FILE__);
		if ( $file == $base ) {
			if ( ! is_network_admin() )
				$links[] = '<a href="admin.php?page=contact_form.php">' . __( 'Settings','contact_form' ) . '</a>';
			$links[] = '<a href="http://wordpress.org/plugins/contact-form-plugin/faq/" target="_blank">' . __( 'FAQ','contact_form' ) . '</a>';
			$links[] = '<a href="http://support.bestwebsoft.com">' . __( 'Support','contact_form' ) . '</a>';
		}
		return $links;
	}
}

if ( ! function_exists ( 'cntctfrm_clean_input' ) ) {
	function cntctfrm_clean_input( $string, $preserve_space = 0 ) {
		if ( is_string( $string ) ) {
			if ( $preserve_space ) {
				return cntctfrm_sanitize_string( strip_tags( stripslashes( $string ) ), $preserve_space );
			}
			return trim( cntctfrm_sanitize_string( strip_tags( stripslashes( $string ) ) ) );
		} else if ( is_array( $string ) ) {
			reset( $string );
			while ( list($key, $value ) = each( $string ) ) {
				$string[ $key ] = cntctfrm_clean_input( $value,$preserve_space );
			}
			return $string;
		} else {
			return $string;
		}
	}
}
/* End function ctf_clean_input */

/* Functions for protecting and validating form vars */
if ( ! function_exists ( 'cntctfrm_sanitize_string' ) ) {
	function cntctfrm_sanitize_string( $string, $preserve_space = 0 ) {
		if ( ! $preserve_space )
			$string = preg_replace("/ +/", ' ', trim( $string ) );

		return preg_replace( "/[<>]/", '_', $string );
	}
}

if ( ! function_exists ( 'cntctfrm_admin_head' ) ) {
	function cntctfrm_admin_head() {
		if ( isset( $_REQUEST['page'] ) && ( 'contact_form.php' == $_REQUEST['page'] ) ) {
			global $wp_version, $cntctfrm_plugin_info;

			wp_enqueue_style( 'cntctfrm_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );

			if ( isset( $_GET['action'] ) && 'appearance' == $_GET['action'] ) {
				wp_enqueue_style( 'cntctfrm_form_style', plugins_url( 'css/form_style.css', __FILE__ ) );
			}

			$script_vars = array(
				'cntctfrm_nonce' 		=> wp_create_nonce( plugin_basename( __FILE__ ), 'cntctfrm_ajax_nonce_field' ),
				'cntctfrm_confirm_text'  => __( 'Are you sure that you want to delete this language data?', 'contact_form' ) 
			);

			if ( 3.5 > $wp_version ) {
				wp_enqueue_script( 'cntctfrm_script', plugins_url( 'js/script_wp_before_3.5.js', __FILE__ ) );
				$script_vars['cntctfrm_delete_multi_link'] = ( 3.3 > $wp_version ) ? false : true;
			} else {
				wp_enqueue_script( 'cntctfrm_script', plugins_url( 'js/script.js', __FILE__ ) );
			}

			wp_localize_script( 'cntctfrm_script', 'cntctfrm_ajax', $script_vars );

			if ( ! ( 3.3 > $wp_version ) ) {
				require_once( dirname( __FILE__ ) . '/bws_menu/bws_functions.php' );
				$tooltip_args = array(
					'tooltip_id'	=> 'cntctfrm_install_multi_tooltip',
					'css_selector' 	=> '#cntctfrm_show_multi_notice',
					'actions' 		=> array(
						'click' 	=> true,
						'onload' 	=> true,
					), 
					'content' 			=> '<h3>' . __( 'Add multiple forms', 'contact_form' ) . '</h3>' .'<p>' . __( 'Install Contact Form Multi plugin to create unlimited number of contact forms.', 'contact_form' ) . '</p>',
					'buttons'			=> array(
						array(
							'type' => 'link',
							'link' => 'http://bestwebsoft.com/products/contact-form-multi/?k=747ca825fb44711e2d24e40697747bc6&pn=77&v=' . $cntctfrm_plugin_info["Version"] . '&wp_v=' . $wp_version,
							'text' => __( 'Learn more', 'contact_form' ),
						),
						'close' => array(
							'type' => 'dismiss',
							'text' => __( 'Close', 'contact_form' ),
						),
					),
					'position' => array( 
						'edge' 		=> 'top',
						'align' 	=> 'left',
					),
				);
				bws_add_tooltip_admin( $tooltip_args );
			}
		}
	}
}

if ( ! function_exists ( 'cntctfrm_wp_head' ) ) {
	function cntctfrm_wp_head() {
		wp_enqueue_style( 'cntctfrm_form_style', plugins_url( 'css/form_style.css', __FILE__ ) );
	}
}

if ( ! function_exists ( 'cntctfrm_add_language' ) ) {
	function cntctfrm_add_language() {
		check_ajax_referer( plugin_basename( __FILE__ ), 'cntctfrm_ajax_nonce_field' );

		$lang = strip_tags( preg_replace( '/<[^>]*>/', '', preg_replace( '/<script.*<\/[^>]*>/', '', htmlspecialchars( $_REQUEST['lang'] ) ) ) );

		/* Check contact-form-multi plugin */
		if ( is_plugin_active( 'contact-form-multi/contact-form-multi.php' ) )
			$contact_form_multi_active = true;
		if ( is_plugin_active( 'contact-form-multi-pro/contact-form-multi-pro.php' ) )
			$contact_form_multi_pro_active = true;

		if ( isset( $contact_form_multi_active ) || isset( $contact_form_multi_pro_active ) ) {
			$cntctfrm_options = get_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] );
		} else {
			$cntctfrm_options = get_option( 'cntctfrm_options' );
		}

		$cntctfrm_options['cntctfrm_language'][] = $lang;

		if ( isset ( $contact_form_multi_active ) ) {
			$cntctfrmmlt_options_main = get_option( 'cntctfrmmlt_options_main' );
			update_option( 'cntctfrmmlt_options_' . $cntctfrmmlt_options_main['id_form'], $cntctfrm_options );
		} elseif ( isset( $contact_form_multi_pro_active ) ) {
			$cntctfrmmltpr_options_main = get_option( 'cntctfrmmltpr_options_main' );
			update_option( 'cntctfrmmlt_options_' . $cntctfrmmltpr_options_main['id_form'], $cntctfrm_options );
		} else {
			update_option( 'cntctfrm_options', $cntctfrm_options );
		}

		if ( ! isset( $contact_form_multi_active ) && ! isset( $contact_form_multi_pro_active ) ) {
			$result = __( "Use shortcode", 'contact_form' ) . ' <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=' . $lang . ']</span> ' . __( "for this language", 'contact_form' );
		} else {
			$result = __( "Use shortcode", 'contact_form' ) . ' <span class="cntctfrm_shortcode">[bestwebsoft_contact_form lang=' . $lang . ' id=' . $_SESSION['cntctfrmmlt_id_form'] . ']</span> ' . __( "for this language", 'contact_form' );
		}

		echo json_encode( $result );
		die();
	}
}

if ( ! function_exists ( 'cntctfrm_remove_language' ) ) {
	function cntctfrm_remove_language() {
		check_ajax_referer( plugin_basename( __FILE__ ), 'cntctfrm_ajax_nonce_field' );
		/* Check contact-form-multi plugin */
		if ( is_plugin_active( 'contact-form-multi/contact-form-multi.php' ) )
			$contact_form_multi_active = true;
		if ( is_plugin_active( 'contact-form-multi-pro/contact-form-multi-pro.php' ) )
			$contact_form_multi_pro_active = true;

		if ( isset( $contact_form_multi_active ) || isset( $contact_form_multi_pro_active ) ) {
			$cntctfrm_options = get_option( 'cntctfrmmlt_options_' . $_SESSION['cntctfrmmlt_id_form'] );
		} else {
			$cntctfrm_options = get_option( 'cntctfrm_options' );
		}

		if ( $key = array_search( $_REQUEST['lang'], $cntctfrm_options['cntctfrm_language'] ) !== false )
			$cntctfrm_options['cntctfrm_language'] = array_diff( $cntctfrm_options['cntctfrm_language'], array( $_REQUEST['lang'] ) );
		if ( isset( $cntctfrm_options['cntctfrm_name_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_name_label'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_address_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_address_label'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_email_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_email_label'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_phone_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_phone_label'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_subject_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_subject_label'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_message_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_message_label'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_attachment_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_label'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_attachment_tooltip'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_tooltip'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_send_copy_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_send_copy_label'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_thank_text'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_thank_text'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_submit_label'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_submit_label'][ $_REQUEST['lang'] ]);
		if ( isset( $cntctfrm_options['cntctfrm_name_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_name_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_address_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_address_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_email_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_email_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_phone_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_phone_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_subject_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_subject_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_message_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_message_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_attachment_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_attachment_upload_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_upload_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_attachment_move_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_move_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_attachment_size_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_attachment_size_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_captcha_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_captcha_error'][ $_REQUEST['lang'] ] );
		if ( isset( $cntctfrm_options['cntctfrm_form_error'][ $_REQUEST['lang'] ] ) )
			unset( $cntctfrm_options['cntctfrm_form_error'][ $_REQUEST['lang'] ] );

		if ( isset( $contact_form_multi_active ) ) {
			$cntctfrmmlt_options_main = get_option( 'cntctfrmmlt_options_main' );
			update_option( 'cntctfrmmlt_options_' . $cntctfrmmlt_options_main['id_form'], $cntctfrm_options );
		} elseif ( isset( $contact_form_multi_pro_active ) ) {
			$cntctfrmmltpr_options_main = get_option( 'cntctfrmmltpr_options_main' );
			update_option( 'cntctfrmmlt_options_' . $cntctfrmmltpr_options_main['id_form'], $cntctfrm_options );
		} else {
			update_option( 'cntctfrm_options', $cntctfrm_options );
		}
		die();
	}
}

if ( ! function_exists ( 'cntctfrm_plugin_banner' ) ) {
	function cntctfrm_plugin_banner() {
		global $hook_suffix;
		if ( 'plugins.php' == $hook_suffix ) {
			global $cntctfrm_plugin_info, $wp_version, $bstwbsftwppdtplgns_cookie_add, $bstwbsftwppdtplgns_banner_array;
			bws_plugin_banner( $cntctfrm_plugin_info, 'cntctfrm', 'contact-form', 'f575dc39cba54a9de88df346eed52101', '77', plugins_url( 'images/banner.png', __FILE__ ) ); 

			if ( empty( $bstwbsftwppdtplgns_banner_array ) )
				bws_get_banner_array();

			if ( ! function_exists( 'is_plugin_active' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$all_plugins = get_plugins();
			$this_banner_prefix = 'cntctfrm_for_ctfrmtdb';
			$this_banner = 'cntctfrm_for_ctfrmtdb_hide_banner_on_plugin_page';
			foreach ( $bstwbsftwppdtplgns_banner_array as $key => $value ) {
				if ( $this_banner == $value[0] ) {
					if ( ! isset( $bstwbsftwppdtplgns_cookie_add ) ) {
						echo '<script type="text/javascript" src="' . plugins_url( '/bws_menu/js/c_o_o_k_i_e.js', __FILE__ ) . '"></script>';
						$bstwbsftwppdtplgns_cookie_add = true;
					} ?>
					<script type="text/javascript">
						(function($) {
							$(document).ready( function() {
								var hide_message = $.cookie( '<?php echo $this_banner_prefix; ?>_hide_banner_on_plugin_page' );
								if ( hide_message == "true" ) {
									$( ".<?php echo $this_banner_prefix; ?>_message" ).css( "display", "none" );
								} else {
									$( ".<?php echo $this_banner_prefix; ?>_message" ).css( "display", "block" );
								};
								$( ".<?php echo $this_banner_prefix; ?>_close_icon" ).click( function() {
									$( ".<?php echo $this_banner_prefix; ?>_message" ).css( "display", "none" );
									$.cookie( "<?php echo $this_banner_prefix; ?>_hide_banner_on_plugin_page", "true", { expires: 32 } );
								});
							});
						})(jQuery);
					</script>
					<?php if ( ! array_key_exists( 'contact-form-to-db/contact_form_to_db.php', $all_plugins ) && ! array_key_exists( 'contact-form-to-db-pro/contact_form_to_db_pro.php', $all_plugins ) ) { ?>
						<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
							<div class="cntctfrm_for_ctfrmtdb_message bws_banner_on_plugin_page" style="display: none;">
								<img class="<?php echo $this_banner_prefix; ?>_close_icon close_icon" title="" src="<?php echo plugins_url( 'images/close_banner.png', __FILE__ ); ?>" alt=""/>
								<div class="button_div">
									<a class="button" target="_blank" href="http://bestwebsoft.com/products/contact-form-to-db/?k=6ebf0743736411607343ad391dc3b436&pn=77&v=<?php echo $cntctfrm_plugin_info["Version"]; ?>&amp;wp_v=<?php echo $wp_version; ?>"><?php _e( 'Learn More', 'contact_form' ); ?></a>
								</div>
								<div class="text">
									<?php _e( "<strong>Contact Form to DB</strong> allows to store your messages to the database.", 'contact_form' ); ?><br />
									<span><?php _e( "Manage messages that have been sent from your website.", 'contact_form' ); ?></span>
								</div>
								<div class="icon">
									<img title="" src="<?php echo plugins_url( 'images/banner_for_ctfrmtdb.png', __FILE__ ); ?>" alt=""/>
								</div>
							</div>
						</div>
					<?php }
					break;
				}
				if ( isset( $all_plugins[ $value[1] ] ) && $all_plugins[ $value[1] ]["Version"] >= $value[2] && is_plugin_active( $value[1] ) && ! isset( $_COOKIE[ $value[0] ] ) ) {
					break;
				}
			}
		}
	}
}

/* Function for delete options */
if ( ! function_exists ( 'cntctfrm_delete_options' ) ) {
	function cntctfrm_delete_options() {
		delete_option( 'cntctfrm_options' );
	}
}

register_activation_hook( __FILE__, 'cntctfrm_activation' );

add_action( 'admin_menu', 'cntctfrm_admin_menu' );

add_action( 'init', 'cntctfrm_init' );
add_action( 'admin_init', 'cntctfrm_admin_init' );

/* Additional links on the plugin page */
add_filter( 'plugin_action_links', 'cntctfrm_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'cntctfrm_register_plugin_links', 10, 2 );

add_action( 'admin_enqueue_scripts', 'cntctfrm_admin_head' );
add_action( 'wp_enqueue_scripts', 'cntctfrm_wp_head' );

add_shortcode( 'contact_form', 'cntctfrm_display_form' );
add_shortcode( 'bws_contact_form', 'cntctfrm_display_form' );
add_shortcode( 'bestwebsoft_contact_form', 'cntctfrm_display_form' );
add_filter( 'widget_text', 'do_shortcode' );

add_action( 'wp_ajax_cntctfrm_add_language', 'cntctfrm_add_language' );
add_action( 'wp_ajax_cntctfrm_remove_language', 'cntctfrm_remove_language' );

add_action( 'admin_notices', 'cntctfrm_plugin_banner');

register_uninstall_hook( __FILE__, 'cntctfrm_delete_options' );