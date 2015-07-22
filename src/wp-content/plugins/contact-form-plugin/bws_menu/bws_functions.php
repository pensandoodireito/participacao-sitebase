<?php
/*
* General functions for BestWebSoft plugins
* Version: 1.0.2
*/
if ( ! function_exists ( 'bws_add_general_menu' ) ) {
	function bws_add_general_menu( $base ) {
		global $bstwbsftwppdtplgns_options, $bstwbsftwppdtplgns_added_menu;
		$bws_menu_info = get_plugin_data( dirname( dirname( plugin_dir_path( __FILE__ ) ) ) . '/' . dirname( $base ) . '/bws_menu/bws_menu.php' );
		$bws_menu_version = $bws_menu_info["Version"];

		if ( ! isset( $bstwbsftwppdtplgns_options ) ) {
			if ( is_multisite() ) {
				if ( ! get_site_option( 'bstwbsftwppdtplgns_options' ) )
					add_site_option( 'bstwbsftwppdtplgns_options', array() );
				$bstwbsftwppdtplgns_options = get_site_option( 'bstwbsftwppdtplgns_options' );
			} else {
				if ( ! get_option( 'bstwbsftwppdtplgns_options' ) )
					add_option( 'bstwbsftwppdtplgns_options', array() );
				$bstwbsftwppdtplgns_options = get_option( 'bstwbsftwppdtplgns_options' );
			}
		}

		if ( isset( $bstwbsftwppdtplgns_options['bws_menu_version'] ) ) {
			$bstwbsftwppdtplgns_options['bws_menu']['version'][ $base ] = $bws_menu_version;
			unset( $bstwbsftwppdtplgns_options['bws_menu_version'] );
			if ( is_multisite() )
				update_site_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
			else
				update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
			require_once( dirname( __FILE__ ) . '/bws_menu.php' );
		} else if ( ! isset( $bstwbsftwppdtplgns_options['bws_menu']['version'][ $base ] ) || $bstwbsftwppdtplgns_options['bws_menu']['version'][ $base ] < $bws_menu_version ) {
			$bstwbsftwppdtplgns_options['bws_menu']['version'][ $base ] = $bws_menu_version;
			if ( is_multisite() )
				update_site_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
			else
				update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
			require_once( dirname( __FILE__ ) . '/bws_menu.php' );
		} else if ( ! isset( $bstwbsftwppdtplgns_added_menu ) ) {
			$all_plugins = get_plugins();
			$plugin_with_newer_menu = $base;
			foreach ( $bstwbsftwppdtplgns_options['bws_menu']['version'] as $key => $value ) {
				if ( array_key_exists( $key, $all_plugins ) ) {
					if ( $bws_menu_version < $value && is_plugin_active( $base ) ) {
						$plugin_with_newer_menu = $key;
					}
				} else {
					unset( $bstwbsftwppdtplgns_options['bws_menu']['version'][ $key ] );
					if ( is_multisite() )
						update_site_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
					else
						update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
				}
			}
			$plugin_with_newer_menu = explode( '/', $plugin_with_newer_menu );
			$wp_content_dir = defined( 'WP_CONTENT_DIR' ) ? basename( WP_CONTENT_DIR ) : 'wp-content';

			if ( file_exists( ABSPATH . $wp_content_dir . '/plugins/' . $plugin_with_newer_menu[0] . '/bws_menu/bws_menu.php' ) )
				require_once( ABSPATH . $wp_content_dir . '/plugins/' . $plugin_with_newer_menu[0] . '/bws_menu/bws_menu.php' );
			else
				require_once( dirname( __FILE__ ) . '/bws_menu.php' );
			$bstwbsftwppdtplgns_added_menu = true;
		}
		add_menu_page( 'BWS Plugins', 'BWS Plugins', 'manage_options', 'bws_plugins', 'bws_add_menu_render', plugins_url( 'images/px.png', __FILE__ ), 1001 );
	}
}


/**
* Function check if plugin is compatible with current WP version
* @return void
*/
if ( ! function_exists ( 'bws_wp_version_check' ) ) {
	function bws_wp_version_check( $plugin_basename, $plugin_info, $require_wp ) {
		global $wp_version;
		if ( version_compare( $wp_version, $require_wp, "<" ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( $plugin_basename ) ) {
				deactivate_plugins( $plugin_basename );
				$admin_url = ( function_exists( 'get_admin_url' ) ) ? get_admin_url( null, 'plugins.php' ) : esc_url( '/wp-admin/plugins.php' );
				wp_die( 
					printf(
						"<strong>" . $plugin_info['Name'] . "</strong> %1$s <strong>WordPress " . $require_wp . "</strong> %2$s <br /><br />%3$s <a href='" . $admin_url . "'>%4$s</a>.",
						__( 'requires', 'bestwebsoft' ),
						__( 'or higher, that is why it has been deactivated! Please upgrade WordPress and try again.', 'bestwebsoft' ),
						__( 'Back to the WordPress', 'bestwebsoft' ),
						__( 'Plugins page', 'bestwebsoft' )
					)
				);
			}
		}
	}
}

if ( ! function_exists( 'bws_plugin_banner' ) ) {
	function bws_plugin_banner( $plugin_info, $this_banner_prefix, $link_slug, $link_key, $link_pn, $banner_url ) {
		global $wp_version, $bstwbsftwppdtplgns_cookie_add, $bstwbsftwppdtplgns_banner_array;
		
		if ( empty( $bstwbsftwppdtplgns_banner_array ) ) {
			if ( ! function_exists( 'bws_get_banner_array' ) )
				require_once( dirname( __FILE__ ) . '/bws_menu.php' );
			bws_get_banner_array();
		}
		
		if ( ! function_exists( 'is_plugin_active' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$all_plugins = get_plugins();

		$this_banner = $this_banner_prefix . '_hide_banner_on_plugin_page';
		foreach ( $bstwbsftwppdtplgns_banner_array as $key => $value ) {
			if ( $this_banner == $value[0] ) {
				if ( ! isset( $bstwbsftwppdtplgns_cookie_add ) ) {
					echo '<script type="text/javascript" src="' . plugins_url( 'js/c_o_o_k_i_e.js', __FILE__ ) . '"></script>';
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
				<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
					<div class="<?php echo $this_banner_prefix; ?>_message bws_banner_on_plugin_page" style="display: none;">
						<img class="<?php echo $this_banner_prefix; ?>_close_icon close_icon" title="" src="<?php echo plugins_url( 'images/close_banner.png', __FILE__ ); ?>" alt=""/>
						<div class="button_div">
							<a class="button" target="_blank" href="http://bestwebsoft.com/products/<?php echo $link_slug; ?>/?k=<?php echo $link_key; ?>&amp;pn=<?php echo $link_pn; ?>&amp;v=<?php echo $plugin_info["Version"]; ?>&amp;wp_v=<?php echo $wp_version; ?>"><?php _e( 'Learn More', 'bestwebsoft' ); ?></a>
						</div>
						<div class="text"><?php
							_e( 'It’s time to upgrade your', 'bestwebsoft' ); ?> <strong><?php echo $plugin_info['Name']; ?> plugin</strong> <?php _e( 'to', 'bestwebsoft' ); ?> <strong>PRO</strong> <?php _e( 'version!', 'bestwebsoft' ); ?><br />
							<span><?php _e( 'Extend standard plugin functionality with new great options.', 'bestwebsoft' ); ?></span>
						</div>
						<div class="icon">
							<img title="" src="<?php echo $banner_url; ?>" alt="" />
						</div>
					</div>
				</div>
				<?php break;
			}
			if ( isset( $all_plugins[ $value[1] ] ) && $all_plugins[ $value[1] ]["Version"] >= $value[2] && is_plugin_active( $value[1] ) && ! isset( $_COOKIE[ $value[0] ] ) ) {
				break;
			}
		}
	}
}

if ( ! function_exists( 'bws_plugin_reviews_block' ) ) {
	function bws_plugin_reviews_block( $plugin_name, $plugin_slug ) { ?>
		<div class="bws-plugin-reviews">
			<div class="bws-plugin-reviews-rate">
				<?php _e( 'If you enjoy our plugin, please give it 5 stars on WordPress', 'bestwebsoft' ); ?>:
				<a href="http://wordpress.org/support/view/plugin-reviews/<?php echo $plugin_slug; ?>" target="_blank" title="<?php echo $plugin_name; ?> reviews"><?php _e( 'Rate the plugin', 'bestwebsoft' ); ?></a>
			</div>
			<div class="bws-plugin-reviews-support">
				<?php _e( 'If there is something wrong about it, please contact us', 'bestwebsoft' ); ?>:
				<a href="http://support.bestwebsoft.com">http://support.bestwebsoft.com</a>
			</div>
		</div>
	<?php }
}

if ( ! function_exists( 'bws_go_pro_tab_check' ) ) {
	function bws_go_pro_tab_check( $plugin_basename ) {
		global $wp_version, $bstwbsftwppdtplgns_options;
		$result = array();

		$bws_license_key = ( isset( $_POST['bws_license_key'] ) ) ? stripslashes( esc_html( trim( $_POST['bws_license_key'] ) ) ) : "";

		if ( isset( $_POST['bws_license_submit'] ) && check_admin_referer( $plugin_basename, 'bws_license_nonce_name' ) ) {
			if ( '' != $bws_license_key ) { 
				if ( strlen( $bws_license_key ) != 18 ) {
					$result['error'] = __( "Wrong license key", 'bestwebsoft' );
				} else {
					$bws_license_plugin = stripslashes( esc_html( $_POST['bws_license_plugin'] ) );	
					if ( isset( $bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['count'] ) && $bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['time'] > ( time() - (24 * 60 * 60) ) ) {
						$bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['count'] = $bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['count'] + 1;
					} else {
						$bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['count'] = 1;
						$bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['time'] = time();
					}

					/* download Pro */
					if ( ! function_exists( 'get_plugins' ) )
						require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

					$all_plugins = get_plugins();
					
					if ( ! array_key_exists( $bws_license_plugin, $all_plugins ) ) {
						$current = get_site_transient( 'update_plugins' );
						if ( is_array( $all_plugins ) && !empty( $all_plugins ) && isset( $current ) && is_array( $current->response ) ) {
							$to_send = array();
							$to_send["plugins"][ $bws_license_plugin ] = array();
							$to_send["plugins"][ $bws_license_plugin ]["bws_license_key"] = $bws_license_key;
							$to_send["plugins"][ $bws_license_plugin ]["bws_illegal_client"] = true;
							$options = array(
								'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3 ),
								'body' => array( 'plugins' => serialize( $to_send ) ),
								'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ) );
							$raw_response = wp_remote_post( 'http://bestwebsoft.com/wp-content/plugins/paid-products/plugins/update-check/1.0/', $options );

							if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
								$result['error'] = __( "Something went wrong. Please try again later. If the error appears again, please contact us", 'bestwebsoft' ) . ' <a href="http://support.bestwebsoft.com">BestWebSoft</a>. ' . __( "We are sorry for inconvenience.", 'bestwebsoft' );
							} else {
								$response = maybe_unserialize( wp_remote_retrieve_body( $raw_response ) );
								if ( is_array( $response ) && !empty( $response ) ) {
									foreach ( $response as $key => $value ) {
										if ( "wrong_license_key" == $value->package ) {
											$result['error'] = __( "Wrong license key", 'bestwebsoft' ); 
										} elseif ( "wrong_domain" == $value->package ) {
											$result['error'] = __( "This license key is bind to another site", 'bestwebsoft' );
										} elseif ( "you_are_banned" == $value->package ) {
											$result['error'] = __( "Unfortunately, you have exceeded the number of available tries per day. Please, upload the plugin manually.", 'bestwebsoft' );
										} elseif ( "time_out" == $value->package ) {
											$result['error'] = __( "Unfortunately, Your license has expired. To continue getting top-priority support and plugin updates you should extend it in your", 'bestwebsoft' ) . ' <a href="http://bestwebsoft.com/wp-admin/admin.php?page=bws_plugins_client_area">Client area</a>';
										} elseif ( "duplicate_domen_for_trial" == $value->package ) {
											$result['error'] = __( "Unfortunately, the PRO licence was already installed to this domain. The PRO Trial license can be installed only once.", 'bestwebsoft' );
										}
									}
									if ( empty( $result['error'] ) ) {
										$bstwbsftwppdtplgns_options[ $bws_license_plugin ] = $bws_license_key;

										$url = 'http://bestwebsoft.com/wp-content/plugins/paid-products/plugins/downloads/?bws_first_download=' . $bws_license_plugin . '&bws_license_key=' . $bws_license_key . '&download_from=5';
										$uploadDir = wp_upload_dir();
											$zip_name = explode( '/', $bws_license_plugin );
											$received_content = file_get_contents( $url );
											if ( ! $received_content ) {
												$result['error'] = __( "Failed to download the zip archive. Please, upload the plugin manually", 'bestwebsoft' );
											} else {
												if ( is_writable( $uploadDir["path"] ) ) {
													$file_put_contents = $uploadDir["path"] . "/" . $zip_name[0] . ".zip";
													if ( file_put_contents( $file_put_contents, $received_content ) ) {
														@chmod( $file_put_contents, octdec( 755 ) );
														if ( class_exists( 'ZipArchive' ) ) {
															$zip = new ZipArchive();
															if ( $zip->open( $file_put_contents ) === TRUE ) {
																$zip->extractTo( WP_PLUGIN_DIR );
																$zip->close();
															} else {
																$result['error'] = __( "Failed to open the zip archive. Please, upload the plugin manually", 'bestwebsoft' );
															}
														} elseif ( class_exists( 'Phar' ) ) {
															$phar = new PharData( $file_put_contents );
															$phar->extractTo( WP_PLUGIN_DIR );
														} else {
															$result['error'] = __( "Your server does not support either ZipArchive or Phar. Please, upload the plugin manually", 'bestwebsoft' );
														}
														@unlink( $file_put_contents );
													} else {
														$result['error'] = __( "Failed to download the zip archive. Please, upload the plugin manually", 'bestwebsoft' );
													}
												} else {
													$result['error'] = __( "UploadDir is not writable. Please, upload the plugin manually", 'bestwebsoft' );
												}
											}

										/* activate Pro */
										if ( file_exists( WP_PLUGIN_DIR . '/' . $zip_name[0] ) ) {
											if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
												/* if multisite and free plugin is network activated */
												$active_plugins = get_site_option( 'active_sitewide_plugins' );
												$active_plugins[ $bws_license_plugin ] = time();
												update_site_option( 'active_sitewide_plugins', $active_plugins );
											} else {
												/* activate on a single blog */
												$active_plugins = get_option( 'active_plugins' );
												array_push( $active_plugins, $bws_license_plugin );
												update_option( 'active_plugins', $active_plugins );
											}
											$result['pro_plugin_is_activated'] = true;
										} elseif ( empty( $result['error'] ) ) {
											$result['error'] = __( "Failed to download the zip archive. Please, upload the plugin manually", 'bestwebsoft' );
										}
									}
								} else {
									$result['error'] = __( "Something went wrong. Try again later or upload the plugin manually. We are sorry for inconvenience.", 'bestwebsoft' ); 
								}
							}
						}
					} else {
						$bstwbsftwppdtplgns_options[ $bws_license_plugin ] = $bws_license_key;
						/* activate Pro */
						if ( ! is_plugin_active( $bws_license_plugin ) ) {
							if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
								/* if multisite and free plugin is network activated */
								$network_wide = true;
							} else {
								/* activate on a single blog */
								$network_wide = false;
							}
							activate_plugin( $bws_license_plugin, NULL, $network_wide );
							$result['pro_plugin_is_activated'] = true;
						}
					}
					if ( is_multisite() )
						update_site_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
					else
						update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
				}
			} else {
				$result['error'] = __( "Please, enter Your license key", 'bestwebsoft' );
			}
		}
		return $result;
	}
}

if ( ! function_exists( 'bws_go_pro_tab' ) ) {
	function bws_go_pro_tab( $plugin_info, $plugin_basename, $page, $pro_page, $bws_license_plugin, $link_slug, $link_key, $link_pn, $pro_plugin_is_activated = false ) {
		global $wp_version, $bstwbsftwppdtplgns_options;
		$bws_license_key = ( isset( $_POST['bws_license_key'] ) ) ? stripslashes( esc_html( trim( $_POST['bws_license_key'] ) ) ) : "";
		if ( $pro_plugin_is_activated ) { ?>
			<script type="text/javascript">
				window.setTimeout( function() {
					window.location.href = 'admin.php?page=<?php echo $pro_page; ?>';
				}, 5000 );
			</script>
			<p><?php _e( "Congratulations! The PRO version of the plugin is successfully download and activated.", 'bestwebsoft' ); ?></p>
			<p>
				<?php _e( "Please, go to", 'bestwebsoft' ); ?> <a href="admin.php?page=<?php echo $pro_page; ?>"><?php _e( 'the setting page', 'bestwebsoft' ); ?></a> 
				(<?php _e( "You will be redirected automatically in 5 seconds.", 'bestwebsoft' ); ?>)
			</p>
		<?php } else { ?>
			<form method="post" action="admin.php?page=<?php echo $page; ?>&amp;action=go_pro">
				<p>
					<?php _e( 'You can download and activate', 'bestwebsoft' ); ?> 
					<a href="http://bestwebsoft.com/products/<?php echo $link_slug; ?>/?k=<?php echo $link_key; ?>&amp;pn=<?php echo $link_pn; ?>&amp;v=<?php echo $plugin_info["Version"]; ?>&amp;wp_v=<?php echo $wp_version; ?>" target="_blank" title="<?php echo $plugin_info["Name"]; ?> Pro">PRO</a> 
					<?php _e( 'version of this plugin by entering Your license key.', 'bestwebsoft' ); ?><br />
					<span class="bws_info">
						<?php _e( 'You can find your license key on your personal page Client area, by clicking on the link', 'bestwebsoft' ); ?> 
						<a href="http://bestwebsoft.com/wp-login.php">http://bestwebsoft.com/wp-login.php</a> 
						<?php _e( '(your username is the email you specify when purchasing the product).', 'bestwebsoft' ); ?>
					</span>
				</p>
				<?php if ( isset( $bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['count'] ) &&
					'5' < $bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['count'] &&
					$bstwbsftwppdtplgns_options['go_pro'][ $bws_license_plugin ]['time'] > ( time() - ( 24 * 60 * 60 ) ) ) { ?>
					<p>
						<input disabled="disabled" type="text" name="bws_license_key" value="<?php echo $bws_license_key; ?>" />
						<input disabled="disabled" type="submit" class="button-primary" value="<?php _e( 'Activate', 'bestwebsoft' ); ?>" />
					</p>
					<p><?php _e( "Unfortunately, you have exceeded the number of available tries per day. Please, upload the plugin manually.", 'bestwebsoft' ); ?></p>
				<?php } else { ?>
					<p>
						<input type="text" name="bws_license_key" value="<?php echo $bws_license_key; ?>" />
						<input type="hidden" name="bws_license_plugin" value="<?php echo $bws_license_plugin; ?>" />
						<input type="hidden" name="bws_license_submit" value="submit" />
						<input type="submit" class="button-primary" value="<?php _e( 'Activate', 'bestwebsoft' ); ?>" />
						<?php wp_nonce_field( $plugin_basename, 'bws_license_nonce_name' ); ?>
					</p>
				<?php } ?>
			</form>
		<?php }
	}
}

if ( ! function_exists( 'bws_check_pro_license' ) ) {
	function bws_check_pro_license( $plugin_basename ) {
		global $wp_version, $bstwbsftwppdtplgns_options;
		$result = array();

		if ( isset( $_POST['bws_license_submit'] ) && check_admin_referer( $plugin_basename, 'bws_license_nonce_name' ) ) {
			$license_key = isset( $_POST['bws_license_key'] ) ? stripslashes( esc_html( trim( $_POST['bws_license_key'] ) ) ) : '';
			
			if ( '' != $license_key ) {
				if ( ! function_exists( 'get_plugins' ) )
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				$plugins_all = get_plugins();
				$current = get_site_transient( 'update_plugins' );

				if ( is_array( $plugins_all ) && !empty( $plugins_all ) && isset( $current ) && is_array( $current->response ) ) {
					$to_send = array();
					$to_send["plugins"][ $plugin_basename ] = $plugins_all[ $plugin_basename ];
					$to_send["plugins"][ $plugin_basename ]["bws_license_key"] = $license_key;
					$to_send["plugins"][ $plugin_basename ]["bws_illegal_client"] = true;
					$options = array(
							'timeout' => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 3),
							'body' => array( 'plugins' => serialize( $to_send ) ),
							'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' )
						);
					$raw_response = wp_remote_post( 'http://bestwebsoft.com/wp-content/plugins/paid-products/plugins/update-check/1.0/', $options );
					if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
						$result['error'] = __( 'Something went wrong. Please try again later. If the error appears again, please contact us', 'bestwebsoft' ) . ' <a href=http://support.bestwebsoft.com>BestWebSoft</a>. ' . __( 'We are sorry for inconvenience.', 'bestwebsoft' );
					} else {
						$response = maybe_unserialize( wp_remote_retrieve_body( $raw_response ) );
						if ( is_array( $response ) && !empty( $response ) ) {
							foreach ( $response as $key => $value ) {
								if ( "wrong_license_key" == $value->package ) {
									$result['error'] = __( 'Wrong license key', 'bestwebsoft' ); 
								} else if ( "wrong_domain" == $value->package ) {
									$result['error'] = __( 'This license key is bind to another site', 'bestwebsoft' );
								} else if ( "time_out" == $value->package ) {
									$result['message'] = __( 'This license key is valid, but Your license has expired. If you want to update our plugin in future, you should extend the license.', 'bestwebsoft' );
								} elseif ( "you_are_banned" == $value->package ) {
									$result['error'] = __( "Unfortunately, you have exceeded the number of available tries.", 'bestwebsoft' );
								}
								if ( empty( $result['message'] ) && empty( $result['error'] ) ) {
									if ( isset( $value->time_out ) && '' != $value->time_out )
										$result['message'] = __( 'The license key is valid. Your license will expire on', 'bestwebsoft' ) . ' ' . $value->time_out;
									else
										$result['message'] = __( 'The license key is valid.', 'bestwebsoft' );
								}
								if ( empty( $result['error'] ) ) {
									if ( $bstwbsftwppdtplgns_options[ $plugin_basename ] != $license_key ) {
										$bstwbsftwppdtplgns_options[ $plugin_basename ] = $license_key;
										$bstwbsftwppdtplgns_options['time_out'][ $plugin_basename ] = $value->time_out;
										if ( is_multisite() )
											update_site_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
										else
											update_option( 'bstwbsftwppdtplgns_options', $bstwbsftwppdtplgns_options );
										$file = @fopen( dirname( __FILE__ ) . "/license_key.txt" , "w+" );
										if ( $file ) {
											@fwrite( $file, $license_key );
											@fclose( $file );
										}
									}
								}
							}
						} else {
							$result['error'] = __( 'Something went wrong. Please try again later. If the error appears again, please contact us', 'bestwebsoft' ) . ' <a href=http://support.bestwebsoft.com>BestWebSoft</a>. ' . __( 'We are sorry for inconvenience.', 'bestwebsoft' );
						}
					}
				}
			} else {
				$result['error'] = __( 'Please, enter your license key', 'bestwebsoft' );
			}
		}
		return $result;
	}
}

if ( ! function_exists ( 'bws_check_pro_license_form' ) ) {
	function bws_check_pro_license_form( $plugin_basename, $page ) {
		global $bstwbsftwppdtplgns_options;
		$license_key = ( isset( $bstwbsftwppdtplgns_options[ $plugin_basename ] ) ) ? $bstwbsftwppdtplgns_options[ $plugin_basename ] : ''; ?>
		<div class="clear"></div>
		<form method="post" action="admin.php?page=<?php echo $page; ?>">
			<p><?php echo _e( 'If needed you can check if the license key is correct or reenter it in the field below. You can find your license key on your personal page - Client area - on our website', 'bestwebsoft' ) . ' <a href="http://bestwebsoft.com/wp-login.php">http://bestwebsoft.com/wp-login.php</a> ' . __( '(your username is the email you specify when purchasing the product). If necessary, please submit "Lost your password?" request.', 'bestwebsoft' ); ?></p>
			<p>
				<input type="text" name="bws_license_key" value="<?php echo $license_key; ?>" />
				<input type="hidden" name="bws_license_submit" value="submit" />
				<input type="submit" class="button-primary" value="<?php _e( 'Check license key', 'bestwebsoft' ) ?>" />
				<?php wp_nonce_field( $plugin_basename, 'bws_license_nonce_name' ); ?>
			</p>
		</form>
	<?php }
}

if ( ! function_exists ( 'bws_plugin_update_row' ) ) {
	function bws_plugin_update_row( $plugin_key ) {
		global $bstwbsftwppdtplgns_options;
		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
		if ( isset( $bstwbsftwppdtplgns_options['wrong_license_key'][ $plugin_key ] ) ) {
			echo '<tr class="plugin-update-tr">
					<td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange">
						<div class="update-message" style="background-color: #FFEBE8; border-color: #CC0000;">' . __( 'WARNING: We have noticed illegal use of our plugin by you. We strongly recommend you to solve the problem within 24 hours, otherwise the plugin will be deactivated. Please go to your personal', 'bestwebsoft' ) . ' <a href="http://bestwebsoft.com/wp-admin/admin.php?page=bws_plugins_client_area">Client area</a> ' . __( '(your username is the email you specify when purchasing the product), where you can make the necessary changes.', 'bestwebsoft' ) . '</div>
					</td>
				</tr>';
		} elseif ( isset( $bstwbsftwppdtplgns_options['time_out'][ $plugin_key ] ) && strtotime( $bstwbsftwppdtplgns_options['time_out'][ $plugin_key ] ) < strtotime( date("m/d/Y") ) ) {
			echo '<tr class="plugin-update-tr">
					<td colspan="' . $wp_list_table->get_column_count() . '" class="plugin-update colspanchange">
						<div class="update-message" style="color: #8C0000;">' . __( 'Your license has expired. To continue getting top-priority support and plugin updates you should extend it.', 'bestwebsoft' ) . ' <a target="_new" href="http://support.bestwebsoft.com/entries/53487136-I-purchased-a-Pro-plugin-with-a-recurring-billing-I-cancelled-the-subscription-The-license-has-expir">' . __( "Learn more", 'bestwebsoft' ) . '</div>
					</td>
				</tr>';
		}
	}
}

if ( ! function_exists ( 'bws_plugin_banner_timeout' ) ) {
	function bws_plugin_banner_timeout( $plugin_key, $plugin_prefix, $plugin_name ) {
		global $bstwbsftwppdtplgns_options, $bstwbsftwppdtplgns_cookie_add;
		if ( isset( $bstwbsftwppdtplgns_options['time_out'][ $plugin_key ] ) && strtotime( $bstwbsftwppdtplgns_options['time_out'][ $plugin_key ] ) < strtotime( date("m/d/Y") . '+1 month' ) && strtotime( $bstwbsftwppdtplgns_options['time_out'][ $plugin_key ] ) > strtotime( date("m/d/Y") ) ) {
			if ( ! isset( $bstwbsftwppdtplgns_cookie_add ) ) {
				echo '<script type="text/javascript" src="' . plugins_url( 'bws_menu/js/c_o_o_k_i_e.js', __FILE__ ) . '"></script>';
				$bstwbsftwppdtplgns_cookie_add = true;
			} ?>
			<script type="text/javascript">
				(function($) {
					$(document).ready( function() {
						var hide_message = $.cookie( "<?php echo $plugin_prefix; ?>_timeout_hide_banner_on_plugin_page" );
						if ( hide_message == "true" ) {
							$( ".<?php echo $plugin_prefix; ?>_message" ).css( "display", "none" );
						} else {
							$( ".<?php echo $plugin_prefix; ?>_message" ).css( "display", "block" );
						}
						$( ".<?php echo $plugin_prefix; ?>_close_icon" ).click( function() {
							$( ".<?php echo $plugin_prefix; ?>_message" ).css( "display", "none" );
							$.cookie( "<?php echo $plugin_prefix; ?>_timeout_hide_banner_on_plugin_page", "true", { expires: 30 } );
						});
					});
				})(jQuery);
			</script>
			<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
				<div class="<?php echo $plugin_prefix; ?>_message bws_banner_on_plugin_page" style="display:none;">
					<img class="<?php echo $plugin_prefix; ?>_close_icon close_icon" title="" src="<?php echo  plugins_url( 'images/close_banner.png', __FILE__ ); ?>" alt=""/>
					<div class="text"><?php _e( "You license for", 'bestwebsoft' ); ?> <strong><?php echo $plugin_name; ?> PRO</strong> <?php echo __( "expires on", 'bestwebsoft' ) . ' ' . $bstwbsftwppdtplgns_options['time_out'][ $plugin_key ] . ' ' . __( "and you won't be granted TOP-PRIORITY SUPPORT or UPDATES.", 'bestwebsoft' ); ?> <a target="_new" href="http://support.bestwebsoft.com/entries/53487136"><?php _e( "Learn more", 'bestwebsoft' ); ?></a></div>
					<div class="icon">
						<img title="" src="<?php echo plugins_url( 'images/banner.png', __FILE__ ); ?>" alt=""/>
					</div>
				</div>  
			</div>
		<?php }
	}
}

if ( ! function_exists ( 'bws_plugin_init' ) ) {
	function bws_plugin_init() {
		/* Internationalization, first(!) */
		load_plugin_textdomain( 'bestwebsoft', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

if ( ! function_exists ( 'bws_admin_enqueue_scripts' ) ) {
	function bws_admin_enqueue_scripts() {
		global $wp_version;
		if ( $wp_version < 3.8 )
			wp_enqueue_style( 'bws-admin-stylesheet', plugins_url( 'css/general_style_wp_before_3.8.css', __FILE__ ) );
		else
			wp_enqueue_style( 'bws-admin-stylesheet', plugins_url( 'css/general_style.css', __FILE__ ) );

		if ( isset( $_GET['page'] ) && $_GET['page'] == "bws_plugins" ) {
			wp_enqueue_style( 'bws_menu_style', plugins_url( 'css/style.css', __FILE__ ) );
			wp_enqueue_script( 'bws_menu_script', plugins_url( 'js/bws_menu.js' , __FILE__ ) );
			if ( $wp_version >= '3.8' )
				wp_enqueue_script( 'theme-install' );
			elseif ( $wp_version >= '3.4' )
				wp_enqueue_script( 'theme' );
		}
	}
}

if ( ! function_exists ( 'bws_admin_head' ) ) {
	function bws_admin_head() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == "bws_plugins" ) { ?>
			<noscript>
				<style type="text/css">
					.bws_product_button {
						display: inline-block;
					}
				</style>
			</noscript>
		<?php }
	}
}

/**
 * Tooltip block
 */
if ( ! function_exists( 'bws_add_tooltip_admin' ) ) {
	function bws_add_tooltip_admin( $tooltip_args = array() ) {
		new BWS_add_admin_tooltip( $tooltip_args );
	}
}

if ( ! class_exists( 'BWS_add_admin_tooltip' ) ) {
	class BWS_add_admin_tooltip {
		private $tooltip_args;

		public function __construct( $tooltip_args ) {
			global $wp_version;
			if ( 3.3 > $wp_version )
				return;
			/* Default arguments */
			$tooltip_args_default = array( 
				'tooltip_id'	=> false,
				'css_selector' 	=> false, 
				'actions' 		=> array(
					'click' 	=> true,
					'onload' 	=> false,
				), 
				'buttons'		=> array(
					'close' 	=> array(
						'type' => 'dismiss',
						'text' => __( 'Close', 'bestwebsoft' ),
					),
				),
				'position' => array(
					'edge'  	=> 'top', 
					'align' 	=> 'center',
					'pos-left'	=> 0, 
					'pos-top'	=> 0, 
					'zindex' 	=> 10000 
				), 
			);
			$tooltip_args = array_merge( $tooltip_args_default, $tooltip_args );
			/* Check that our merged array has default values */
			foreach ( $tooltip_args_default as $arg_key => $arg_value ) {
				if ( is_array( $arg_value ) ) {
					foreach ( $arg_value as $key => $value) {
						if ( ! isset( $tooltip_args[ $arg_key ][ $key ] ) ) {
							$tooltip_args[ $arg_key ][ $key ] = $tooltip_args_default[ $arg_key ][ $key ];
						}
					}
				}
			}
			/* Check if tooltip is dismissed */
			if ( true === $tooltip_args['actions']['onload'] ) {
				if ( in_array( $tooltip_args['tooltip_id'], array_filter( explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) ) ) ) ) {
					$tooltip_args['actions']['onload'] = false;
				}
			}
			/* Check entered data */
			if ( false === $tooltip_args['tooltip_id'] || false === $tooltip_args['css_selector'] || ( false === $tooltip_args['actions']['click'] && false === $tooltip_args['actions']['onload'] ) ) {
				/* if not enough data to output a tooltip */
				return;
			} else {
				/* check position */
				if ( ! in_array( $tooltip_args['position']['edge'], array( 'left', 'right', 'top', 'bottom' ) )  ) {
					$tooltip_args['position']['edge'] = 'top';
				}
				if ( ! in_array( $tooltip_args['position']['align'], array( 'top', 'bottom', 'left', 'right', 'center', ) ) ) {
					$tooltip_args['position']['align'] = 'center';
				}
			}
			/* fix position */
			switch ( $tooltip_args['position']['edge'] ) {
				case 'left':
				case 'right':
					switch ( $tooltip_args['position']['align'] ) {
						case 'top':
						case 'bottom':
							$tooltip_args['position']['align'] = 'center';
							break;
					}
					break;
				case 'top':
				case 'bottom':
					if ( $tooltip_args['position']['align'] == 'left' ) {
						$tooltip_args['position']['pos-left'] -= 65;
					}
					break;
			}
			$this->tooltip_args = $tooltip_args;
			/* add styles and scripts */
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
			/* add script that displays our tooltip */
			add_action( 'admin_print_footer_scripts', array( $this, 'add_scripts' ) );
		}

		/**
		 * Display tooltip
		 */
		public function add_scripts() {
			$tooltip_args = $this->tooltip_args; ?>
			<script type="text/javascript">
				(function($) {
					var pointer_options = <?php echo json_encode( $tooltip_args ); ?>;
					pointer_buttons = pointer_options['buttons'];
					/* extend pointer options - add close button */
					pointer_options = $.extend(pointer_options, {
						buttons: function(event, t) {
							var button, i = '';
							/* check and add dismiss-type buttons */
							for( var but in pointer_buttons ) {
								if ( typeof pointer_buttons[ but ]['type'] != 'undefined' && pointer_buttons[ but ]['type'] == 'dismiss' && typeof pointer_buttons[ but ]['text'] != 'undefined' && pointer_buttons[ but ]['text'] != '' ) {
									button += '<a id="pointer-close' + i + '" style="margin:0px 5px 2px;" class="button-secondary">' + pointer_buttons[ but ]['text'] + '</a>';
									i++;
								}
							}
							button = jQuery( button );
							button.bind('click.pointer', function () {
								t.element.pointer('close');
							});
							return button;
						},
						/* add ajax dismiss functionality */
						close : $.proxy(function () {
							if ( pointer_options['actions']['onload'] == true ) {
								$.post( ajaxurl, this );
							}
						}, {
							pointer: pointer_options['tooltip_id'],
							action: 'dismiss-wp-pointer'
						})
					});
					/* function to display pointer */
					function displayPointer( cssSelector ) {
						cssSelector.pointer(pointer_options).pointer({
							pointerClass: 'wp-pointer ' + pointer_options["tooltip_id"],
							content: pointer_options['content'],
							position: 
							{
								edge: pointer_options['position']['edge'],
								align: pointer_options['position']['align'],
							},
						}).pointer('open');
						/* display buttons that are not type of dismiss */
						for ( var but in pointer_buttons ) {
							if ( typeof pointer_buttons[ but ]['type'] != 'undefined' && pointer_buttons[ but ]['type'] != 'dismiss' && typeof pointer_buttons[ but ]['text'] != 'undefined' && pointer_buttons[ but ]['text'] != '' ) {
								$('#pointer-close').after( '<a class="button-primary" style="margin-right: 5px;" ' +
								( ( pointer_buttons[ but ]['type'] == 'link' && typeof pointer_buttons[ but ]['link'] != 'undefined' && pointer_buttons[ but ]['link'] != '') ? 'target="_blank" href="' + pointer_buttons[ but ]['link'] + '"' : '' )
								+ '>' + pointer_buttons[ but ]['text'] + '</a>' );
							};
						}
						/* adjust position of pointer */
						topPos = parseInt( $( "." + pointer_options["tooltip_id"] ).css("top") ) + parseInt( pointer_options['position']['pos-top'] );
						leftPos = parseInt( $( "." + pointer_options["tooltip_id"] ).css("left") ) + parseInt( pointer_options['position']['pos-left'] );
						if ( pointer_options['position']['align'] == 'left' ) {
							leftPos += cssSelector.outerWidth()/2;
						};
						$( "." + pointer_options["tooltip_id"] ).css({ "top": topPos + "px", "left": leftPos + "px" });
						/* adjust z-index if need */
						pointerZindex = parseInt( $( "." + pointer_options["tooltip_id"] ).css("z-index") );
						if ( pointerZindex != pointer_options['position']['zindex'] ) {
							$( "." + pointer_options["tooltip_id"] ).css({ "z-index": pointer_options['position']['zindex'] });
						}
					}
					/* display pointer for the first time */
					if ( pointer_options['actions']['onload'] ) {
						displayPointer( $( pointer_options['css_selector'] ) );
					}
					/* display pointer when clicked on selector */
					if ( pointer_options['actions']['click'] ) {
						$( pointer_options['css_selector'] ).click( function () {
							displayPointer( $( this ) );
						});
					}
				})(jQuery);
			</script>
		<?php }
	}
}

add_action( 'admin_init', 'bws_plugin_init' );
add_action( 'admin_enqueue_scripts', 'bws_admin_enqueue_scripts' );
add_action( 'admin_head', 'bws_admin_head' );