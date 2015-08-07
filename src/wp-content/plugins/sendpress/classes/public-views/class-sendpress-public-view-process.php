<?php

// Prevent loading this file directly
if ( !defined('SENDPRESS_VERSION') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}

class SendPress_Public_View_Process extends SendPress_Public_View {
	
	function page_start(){}

	function page_end(){}

	function html() {
		
		$info = $this->data();
		if(isset($_GET['id'])){
			$id = SPNL()->validate->int( $_GET['id'] );
		} else{
			$id =$info->id;
		}

		$email = SendPress_Data::process_with_iron( $id );

		$data = array("send"=>$email);

		echo json_encode($data);

		
	}

}