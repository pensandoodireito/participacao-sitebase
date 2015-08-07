<?php

// Prevent loading this file directly
if ( !defined('SENDPRESS_VERSION') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}

class SendPress_Public_View_Tracker extends SendPress_Public_View {
	
	function page_start(){  }

	function page_end(){}

	function html() {
		//$ip = $_SERVER['REMOTE_ADDR'];

		$info = $this->data();
		//$hash = wp_hash( $info->url , 'sendpress' );


		$dk = SendPress_Data::devicetypes( $this->_device_type );

		$url = $info->url;
		
		switch($info->url){
			case '{sp-browser-url}':
				$url = SPNL()->template_tags->do_subscriber_tags( SendPress_Tag_Browser_Url::external( $info->url, $info->report , $info->id, false ), $info->report, $info->report, $info->id, false );
			break;
			case '{sp-unsubscribe-url}':
				$url = SPNL()->template_tags->do_subscriber_tags( SendPress_Tag_Unsubscribe::external( $info->url, $info->report, $info->id, false ), $info->report, $info->report, $info->id, false );
			break;
			case '{sp-manage-subscription-url}':
				$url = SPNL()->template_tags->do_subscriber_tags( SendPress_Tag_Manage_Subscriptions::external( $info->url, $info->report , $info->id, false ), $info->report, $info->report, $info->id, false );
			break;
		}

		$args = array(
		 'timeout' => 0.1,
		 'blocking' => false,
		 'headers' => array(),
		 'sslverify' => false,
		);

		$request = home_url('/spnl-api/tracker');
		// Parameters as separate arguments
		$request =  add_query_arg( array( 'email' =>$info->report , 'url' => urlencode( $info->url ) , 'id' => $info->id ), $request );
 
		wp_remote_get($request, $args);	

		if(strrpos( $url, "mailto" ) !== false){
			header("Location: " . esc_url_raw( $url ) );
		} else {
			
			if(defined("SENDPRESS_PRO_VERSION")){
				$url = add_query_arg( 'utm_medium' , 'email' , $url );
				$url = add_query_arg( 'utm_source' , 'sendpress' , $url );
				$sub = get_post_meta( $info->report , '_sendpress_subject' , true );
				$alt = get_post_meta( $info->report , 'google-campaign-name', true);
				if( $alt !== false ) {
					$sub = $alt;
				}
				$url = add_query_arg( 'utm_campaign' , $sub , $url );
			}
			
			wp_redirect( esc_url_raw( $url ) );
		}

		exit;
		//
		//$link = SendPress_Data::get_url_by_id( $info->urlID );
		//SendPress_Data::track_click( $info->id , $info->report, $info->urlID , $ip , $this->_device_type, $this->_device );
		//header("Location: " . $link->url);
		//wp_redirect( $link->url ); 
		
		//exit;
	}

}