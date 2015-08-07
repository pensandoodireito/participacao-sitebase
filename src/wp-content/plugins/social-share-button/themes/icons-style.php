<?php

add_filter('wp_head','ssb_share_icons_style');
function ssb_share_icons_style()
	{	
		$ssb_share_content_themes = get_option( 'ssb_share_content_themes' );
		$ssb_share_content_icon_margin = get_option( 'ssb_share_content_icon_margin' );
		
		echo "<style type='text/css'>
		.ssb-share.".$ssb_share_content_themes." a{margin-right:".$ssb_share_content_icon_margin."px;}
		
		</style>";

	}
?>