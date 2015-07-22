<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   ReadyGraph
 * @author    dan@readygraph.com
 * @license   GPL-2.0+
 * @link      http://www.readygraph.com
 * @copyright 2014 Your Name or Company Name
 */
 
function ee_disconnectReadyGraph(){
$app_id = get_option('readygraph_application_id');
wp_remote_get( "http://readygraph.com/api/v1/tracking?event=disconnect_readygraph&app_id=$app_id" );
ee_delete_rg_options();
}
function ee_deleteReadyGraph(){
$app_id = get_option('readygraph_application_id');
wp_remote_get( "http://readygraph.com/api/v1/tracking?event=uninstall_readygraph&app_id=$app_id" );
ee_delete_rg_options();
$dir = plugin_dir_path( __FILE__ );
ee_rrmdir($dir);
}
function readygraph_monetize_update(){
	$app_id = get_option('readygraph_application_id');
	$email = get_option('readygraph_monetize_email');
	$monetize = get_option('readygraph_enable_monetize');
	$related_tags = get_option('readygraph_related_tags');
	$url = 'https://readygraph.com/api/v1/wp-monetize/';
	$response = wp_remote_post($url, array( 'body' => array('app_id' => $app_id, 'monetize_email' => $email, 'monetize' => $monetize, 'related_tags' => $related_tags)));
	if ( is_wp_error( $response ) ) {
	} else {
   $json_decoded = json_decode($response['body'],true);
   if (array_key_exists('adsoptimal_id', $json_decoded['data'])) {
   update_option('readygraph_adsoptimal_id', $json_decoded['data']['adsoptimal_id']);
   }   if (array_key_exists('adsoptimal_secret', $json_decoded['data'])) {
   update_option('readygraph_adsoptimal_secret', $json_decoded['data']['adsoptimal_secret']);
   }
}
}
	if(isset($_GET["action"]) && base64_decode($_GET["action"]) == "changeaccount")ee_disconnectReadyGraph();
	if(isset($_GET["action"]) && base64_decode($_GET["action"]) == "deleteaccount")ee_deleteReadyGraph();
	global $main_plugin_title;
	if (!get_option('readygraph_access_token') || strlen(get_option('readygraph_access_token')) <= 0) {
	}
	else {
	if (isset($_POST["readygraph_access_token"])) update_option('readygraph_access_token', $_POST["readygraph_access_token"]);
	if (isset($_POST["readygraph_refresh_token"])) update_option('readygraph_refresh_token', $_POST["readygraph_refresh_token"]);
	if (isset($_POST["readygraph_email"])) update_option('readygraph_email', $_POST["readygraph_email"]);
	if (isset($_POST["readygraph_application_id"])) update_option('readygraph_application_id', $_POST["readygraph_application_id"]);
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST["readygraph_monetize"]) && $_POST["readygraph_monetize"] == "1") update_option('readygraph_enable_monetize', "true");
		else update_option('readygraph_enable_monetize', "false");
		if (isset($_POST["readygraph_related_tags"]) && $_POST["readygraph_related_tags"] == "1") update_option('readygraph_related_tags', "true");
		else update_option('readygraph_related_tags', "false");
		if (isset($_POST["readygraph_monetize_email"])) update_option('readygraph_monetize_email', $_POST["readygraph_monetize_email"]);
		readygraph_monetize_update();
	}
	if (get_option('readygraph_enable_branding', '') == 'false') {
	?>
<style>
/* FOR INLINE WIDGET */
.rgw-text {
    display: none !important;
}
</style>
<?php } ?>	

<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'assets/css/admin.css', __FILE__ ) ?>">
<script type="text/javascript" src="<?php echo plugins_url( 'assets/js/admin.js', __FILE__ ) ?>"></script>
<form method="post" id="myForm">
<input type="hidden" name="readygraph_access_token" value="<?php echo get_option('readygraph_access_token', '') ?>">
<input type="hidden" name="readygraph_refresh_token" value="<?php echo get_option('readygraph_refresh_token', '') ?>">
<input type="hidden" name="readygraph_email" value="<?php echo get_option('readygraph_email', '') ?>">
<input type="hidden" name="readygraph_application_id" value="<?php echo get_option('readygraph_application_id', '') ?>">
<input type="hidden" name="readygraph_settings" value="<?php echo htmlentities(str_replace("\\\"", "\"", get_option('readygraph_settings', '{}'))) ?>">
<input type="hidden" name="readygraph_delay" value="<?php echo get_option('readygraph_delay', '5000') ?>">
<input type="hidden" name="readygraph_enable_sidebar" value="<?php echo get_option('readygraph_enable_sidebar', 'false') ?>">
<input type="hidden" name="readygraph_enable_notification" value="<?php echo get_option('readygraph_enable_notification', 'true') ?>">
<input type="hidden" name="readygraph_auto_select_all" value="<?php echo get_option('readygraph_auto_select_all', 'true') ?>">
<input type="hidden" name="readygraph_enable_branding" value="<?php echo get_option('readygraph_enable_branding', 'false') ?>">
<input type="hidden" name="readygraph_send_blog_updates" value="<?php echo get_option('readygraph_send_blog_updates', 'true') ?>">
<input type="hidden" name="readygraph_send_real_time_post_updates" value="<?php echo get_option('readygraph_send_real_time_post_updates', 'false') ?>">
<input type="hidden" name="readygraph_popup_template" value="<?php echo get_option('readygraph_popup_template', 'default-template') ?>">


<div class="authenticate" style="display: none;">
	    <div class="wrap1" style="min-height: 600px;">

      <div id="icon-plugins" class="icon32"></div>
      <h2>Earn Revenue with <?php echo $main_plugin_title ?>'s with ReadyGraph Growth Engine</h2>
      
      <p style="display:none;color:red;" id="error"></p>
      <div class="register-left">
	<div class="alert" style="margin: 0px auto; padding: 15px; text-align: center;">
			<h3>Activate ReadyGraph to get more traffic to your site</h3>
<!--		<h3 style="margin-top: 0px; font-weight: 300;"><?php //echo $main_plugin_title ?>, Now with ReadyGraph</h3> -->
		<p style="padding: 50px 0px 30px 0px;"><a class="btn btn-primary connect" href="javascript:void(0);" style="font-size: 15px; line-height: 40px; padding: 0 30px;">Start Earning Revenue<br><span style="font-size: 10px;">Connect ReadyGraph</span></a></p>
		<!--<p style="padding: 0px 0px;"><a class="btn btn-default skip" href="javascript:void(0);" style="font-size: 10px; line-height: 20px; padding: 0 30px;">Skip ReadyGraph</a></p>-->
		<p>Readygraph maximizes your Growth and Revenue</p>
		<p style="text-align: left; padding: 0 20px;">
			- Monetize mobile and web traffic with optimized, non-intrusive ad units<br> 
			- Get more traffic<br>
			- Send automatic email digests of all your site posts<br>
			- Get better deliverablility<br>
			- Track performace and user activity
		</p>
	</div>
          
      </div>

        <div class="register-right">
          <div class="form-wrap alert" style="font-size:12px;">
          <p><h3>ReadyGraph grows your site</h3></p>
<p>ReadyGraph delivers audience growth and motivates users to come back.</p><br /><p><span class="rg-signup-icon"><img src="<?php echo plugin_dir_url( __FILE__ );?>assets/icon_fb.png"></span><b>Optimized Signup Form –</b> ReadyGraph’s signup form has one click signup and integration with Facebook so you can get quick and easy signups from your users.<br /><br /><span class="rg-signup-icon"><img src="<?php echo plugin_dir_url( __FILE__ );?>assets/icon_heart.png"></span>
<b>Viral Friend Invites –</b>Loyal site visitors who love your site can easily invite all their friends. Readygraph encourages your visitors' friends to come and signup for your site too.<br /><br /><b><span class="rg-signup-icon"><img src="<?php echo plugin_dir_url( __FILE__ );?>assets/icon_mail.png"></span>Automated Re-engagement Emails –</b> ReadyGraph’s automated emails keep visitors coming back. Send a daily or weekly digest of all your new posts and keep them informed about site activity, events, etc.<br /><br /><b><span class="rg-signup-icon"><img src="<?php echo plugin_dir_url( __FILE__ );?>assets/icon_chart.png"></span>Analytics -</b> Track new subscribers, invites, traffic, and other key metrics that quantify growth and user engagement.  ReadyGraph safely stores user data on the cloud so you can access from anywhere.<br /><br />
If you have questions or concerns contact us anytime at <a href="mailto:info@readygraph.com" target="_blank">info@readygraph.com</a></p>
          </div>
      </div>
	  </div>
</div>
<div class="authenticating" style="display: none;">
	<div style="color: #ffffff; width: 350px; margin: 100px auto 0px; padding: 15px; border: solid 1px #2a388f; text-align: center; background-color: #2961cb; -webkit-border-radius: 7px; -moz-border-radius: 7px; border-radius: 7px;">
		<h3 style="margin-top: 0px; font-weight: 300;"><?php echo $main_plugin_title ?>, Now with ReadyGraph</h3>
		<h4 style="padding: 50px 0; line-height: 42px;">Retrieving Your Account..</h4>
		<p>Activate Readygraph features to optimize <?php echo $main_plugin_title ?> functionality. Signup For These Benefits:</p>
		<p style="text-align: left; padding: 0 20px;">
			- Grow your subscribers faster<br>
			- Engage users with automated email updates<br>
			- Enhanced email deliverablility<br>
			- Track performace with user-activity analytics
		</p>
	</div>
</div>
<style>a.help-tooltip {outline:none; }a.help-tooltip strong {line-height:30px;}a.help-tooltip:hover {text-decoration:none;} a.help-tooltip span {    z-index:10;display:none; padding:14px 20px;    margin-top:40px; margin-left:-150px;    width:300px; line-height:16px;}a.help-tooltip:hover span{    display:inline; position:absolute;     border:2px solid #FFF;    background:#fff;	text-align: justify;	z-index:1000000000;}.callout {z-index:1000000000;position:absolute;border:0;top:-14px;left:120px;}    /*CSS3 extras*/a.help-tooltip span{    border-radius:2px;    -moz-border-radius: 2px;    -webkit-border-radius: 2px;            -moz-box-shadow: 0px 0px 8px 4px #666;    -webkit-box-shadow: 0px 0px 8px 4px #666;    box-shadow: 0px 0px 8px 4px #666;}</style>
<div class="authenticated" style="display: none;">
	<div style="background-color: #2961cb; min-width: 90%; height: 50px;margin-right: 1%;">
		<img src="<?php echo plugin_dir_url( __FILE__ );?>assets/white-logo.png" style="width: 138px; height: 30px; margin: 10px 0 0 15px; float: left;">
		<div class="btn-group pull-right" style="margin: 8px 10px 0 0;">
			<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" style="background: transparent; border-color: #ffffff; color: #ffffff; ">
				<span class="email-address" style="text-shadow: none;"></span> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a class="change-account" href="#">Change Account</a></li>
				<li><a class="disconnect" href="<?php $current_url = explode("&", $_SERVER['REQUEST_URI']); echo $current_url[0];?>&action=<?php echo base64_encode("changeaccount");?>">Disconnect</a></li>
				<li><a class="delete" href="<?php $current_url = explode("&", $_SERVER['REQUEST_URI']); echo $current_url[0];?>&action=<?php echo base64_encode("deleteaccount");?>">Delete ReadyGraph</a></li>
			</ul>
		</div>
		<div class="btn-group pull-right" style="margin: 8px 10px 0 0;">
			<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" style="background: transparent; border-color: #ffffff; color: #ffffff; ">
				<span class="result" style="text-shadow: none;">...</span> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="http://readygraph.com/application/insights/" target="_blank">Insights</a></li>
			</ul>
		</div>
		<div style="clear: both;"></div>
	</div>
		<!-- write menu code-->

	<div class="readygraph-nav-menu">
	<ul><li>Grow Users
	  <ul>
		<li><a href="<?php $current_url = explode("?", $_SERVER['REQUEST_URI']); echo $current_url[0];?>?page=readygraph-app&ac=signup-popup">Signup Popup</a></li>
		<li><a href="https://readygraph.com/application/insights/" target="_blank">User Statistics</a></li>
		<li><a href="#"></a></li>
	  </ul>
	</li>
  <li>Email Users
	<ul>
		<li><a href="https://readygraph.com/application/customize/settings/email/welcome/" target="_blank">Retention Email</a></li>
		<li><a href="https://readygraph.com/application/customize/settings/email/invitation/" target="_blank">Invitation Email</a></li>
		<li><a href="http://readygraph.com/application/insights/" target="_blank">Custom Email</a></li>
    </ul>
  </li>
  <li>
    Engage Users
    <ul>
		<li><a href="<?php $current_url = explode("?", $_SERVER['REQUEST_URI']); echo $current_url[0];?>?page=readygraph-app&ac=social-feed">Social Feed</a></li>
		<li><a href="#">Social Followers</a></li>
		<li><a href="#">Feedback Survey</a></li>
    </ul>
  </li>
  <li>Basic Settings
    <ul>
		<li><a href="<?php $current_url = explode("&", $_SERVER['REQUEST_URI']); echo $current_url[0];?>&ac=site-profile">Site Profile</a></li>
		<li><a href="<?php $current_url = explode("?", $_SERVER['REQUEST_URI']); echo $current_url[0];?>?page=readygraph-app&ac=feature-settings">Feature Settings</a></li>		<li><a href="<?php $current_url = explode("&", $_SERVER['REQUEST_URI']); echo $current_url[0];?>&ac=monetization-settings">Monetization Settings</a></li>
	</ul>
  </li>
</ul>
	<div class="btn-group" style="margin: 8px 10px 0 10px;">
		<p><a href="mailto:info@readygraph.com" style="color: #b1c1ca" >Help <img src="<?php echo plugin_dir_url( __FILE__ );?>assets/9.png"/></a></p>
	</div>
	<div class="btn-group" style="margin: 8px 10px 0 10px;">
		<p>
		<a href="<?php $current_url = explode("?", $_SERVER['REQUEST_URI']); echo $current_url[0];?>?page=readygraph-app&ac=faq" style="color: #b1c1ca" >FAQ  <img src="<?php echo plugin_dir_url( __FILE__ );?>assets/10.png" /></a></p>
	</div>
	<div class="btn-group" style="">
		<p><a href="https://readygraph.com/accounts/payment/?email=<?php echo get_option('readygraph_email', '') ?>" target="_blank" style="color: #b1c1ca" ><img src="<?php echo plugin_dir_url( __FILE__ );?>assets/go-premium.png" height="40px" style="margin:5px" /></a></p>
	</div>
	</div>
	<div style="margin: 3% 5%">
		<?php if(get_option('readygraph_enable_monetize') && get_option('readygraph_enable_monetize') == "true") { ?><h3 style="font-weight: normal; text-align: center;"><img src="<?php echo plugin_dir_url( __FILE__ );?>assets/check.png"/>Congratulations! <?php echo $main_plugin_title; ?>'s ReadyGraph monetization engine is now active.</h3><?php } ?>
		<h3><strong>Adjust Revenue Settings</strong></h3>
			
			<div style="width: 60%;">
			<img src="<?php echo plugin_dir_url( __FILE__ );?>assets/round-check.png" style="float: left; height: 20px; vertical-align: middle;"/><h5 class="rg-h4" style="margin-left: 30px;text-align:justify; line-height: 20px;">Note: To view your revenue stats, adjust ad placements, and request payment, please click the button below.  This will take you to your dashboard page powered by our monetization partner AdsOptimal. Please contact us <a href="mailto:info@readygraph.com">info@readygraph.com</a> anytime if you have questions.  If you no longer wish to monetize via our non-intrusive highly optimized ad units, you can turn off monetization below.  Remember to save your changes!</h5>
			
			<br>
			<div style="display: block; margin: 10px;"><label for="readygraph_monetize_email">Email:</label><input type="text" name="readygraph_monetize_email" id="readygraph_monetize_email" value="<?php echo get_option('readygraph_monetize_email');?>" style="display: inline; margin: 0 0 0 20px" /></div>
				<p><input type="checkbox" name="readygraph_monetize" value="1" style="margin: 0 10px;" <?php if(get_option('readygraph_enable_monetize') && get_option('readygraph_enable_monetize') == "true") echo "checked"; ?> >Enable Monetization</span>
					<a href="#" class="help-tooltip"><img src="<?php echo plugin_dir_url( __FILE__ );?>assets/Help-icon.png" width="15px" style="margin-left:10px;"/><span><img class="callout" src="<?php echo plugin_dir_url( __FILE__ );?>assets/callout_black.gif" /><strong>ReadyGraph Monetization Settings</strong><br />You can check/uncheck this box to enable/disable the monetization settings for ReadyGraph<br /></span></a>
				</p>
				<p><input type="checkbox" name="readygraph_related_tags" value="1" style="margin: 0 10px;" <?php if(get_option('readygraph_related_tags') && get_option('readygraph_related_tags') == "true") echo "checked"; ?> >Enable Related Tags (Powered by Infolinks)</span>
					<a href="#" class="help-tooltip"><img src="<?php echo plugin_dir_url( __FILE__ );?>assets/Help-icon.png" width="15px" style="margin-left:10px;"/><span><img class="callout" src="<?php echo plugin_dir_url( __FILE__ );?>assets/callout_black.gif" /><strong>ReadyGraph Monetization Settings</strong><br />You can check/uncheck this box to enable/disable the related tags displaying below your post<br /></span></a>
				</p>
				<div class="save-changes">
			<a type="button" class="btn btn-large btn-warning" href="https://www.adsoptimal.com/api/v4/redirect/dashboard?adoid=<?php echo get_option('readygraph_adsoptimal_id', ''); ?>&secret=<?php echo get_option('readygraph_adsoptimal_secret', ''); ?>;" target="_blank" style="margin: 15px">View Revenue Dashboard</a><button type="submit" class="btn btn-large btn-warning save" formaction="<?php $current_url = explode("&", $_SERVER['REQUEST_URI']); echo $current_url[0];?>&ac=monetization-settings" style="margin: 15px">Save Changes</button>
			</div>
			
			</div>
			
	</div>
</div>
</form>
<script type="text/javascript" src="https://readygraph.com/scripts/readygraph.js"></script>
<script type="text/javascript" charset="utf-8">
	var $ = jQuery;
	$(function () {
		var settings =
			{
				'host':     "www.readygraph.com"
			, 'clientId': "9838eb84c6da2fc44ab9"
			};

		var authHost     = "https://" + settings.host;
		var resourceHost = "https://" + settings.host;
		
		// OAuth 2.0 Popup
		//
		var popupWindow=null;
		function openPopup(url)
		{
			if(popupWindow && !popupWindow.closed) popupWindow.focus();
			else popupWindow = window.open(url,"_blank","directories=no, status=no, menubar=no, scrollbars=yes, resizable=no,width=515, height=330,top=" + (screen.height - 330)/2 + ",left=" + (screen.width - 515)/2);
		}
		function parent_disable() {
			if(popupWindow && !popupWindow.closed) popupWindow.focus();
		}
		
		$("a.connect").click(function() {
			var url = authHost + '/oauth/authenticate?client_id=' + settings.clientId + '&redirect_uri=' + encodeURIComponent(location.href.replace('#' + location.hash,"")) + '&response_type=token';
			openPopup(url);
			$(document.body).bind('focus', parent_disable);
			$(document.body).bind('click', parent_disable);
		});
		$(".change-account").click(function() {
			var url = authHost + '/oauth/authenticate?client_id=' + settings.clientId + '&redirect_uri=' + encodeURIComponent(location.href.replace('#' + location.hash,"")) + '&response_type=token';
			var logout = authHost + '/oauth/logout?redirect=' + encodeURIComponent(url);
			openPopup(logout);
			$(document.body).bind('focus', parent_disable);
			$(document.body).bind('click', parent_disable);
		});
		
		// User Interface
		//
		$('.template').click(function() {
			$('#preview').attr('src', $(this).find('img').attr('src'));
		});
		
		// Manage OAuth 2.0 Redirect
		//
		var extractCode = function(hash) {
			var match = hash.match(/code=(\w+)/);
			return !!match && match[1];
		};
		var extractToken = function(hash) {
			var match = hash.match(/access_token=(\w+)/);
			return !!match && match[1];
		};
		var extractError = function(hash) {
			var match = hash.match(/error=(\w+)/);
			return !!match && match[1];
		};
		
		var code = extractCode(window.location.href);
		if (extractError(window.location.href) == 'access_denied') {
			window.close();
		}
		else if(code) {
			try { window.opener.setCode(code); }
			catch(ex) { }
			window.close();
		}
		else {
			$('.rgw-fb-login-button-iframe').hide();
			$('div.authenticated').show();
			
			if ($('[name="readygraph_access_token"]').val()) {
				$('.rgw-fb-login-button-iframe').show();
				$('div.authenticate').hide();
				$('div.authenticating').hide();
				$('div.authenticated').show();
				
				$('.email-address').text($('[name="readygraph_email"]').val());
				
				window.setup_readygraph($('[name="readygraph_application_id"]').val());
				$('.delay').val($('[name="readygraph_delay"]').val());
				$('.sidebar').val($('[name="readygraph_enable_sidebar"]').val());
				$('.notification').val($('[name="readygraph_enable_notification"]').val());
				$('.selectAll').val($('[name="readygraph_auto_select_all"]').val());
				$('.branding').val($('[name="readygraph_enable_branding"]').val());
				$('.blog_updates').val($('[name="readygraph_send_blog_updates"]').val());
				$('.real_time_post_update').val($('[name="readygraph_send_real_time_post_updates"]').val());
				$('.popup_template').val($('[name="readygraph_popup_template"]').val());
				
				//$('[name="readygraph_ad_format"][value="' + $('[name="_readygraph_ad_format"]').val() + '"]').parent().click();
				//$('[name="readygraph_ad_timing"][value="' + $('[name="_readygraph_ad_timing"]').val() + '"]').parent().click();
				
				//$('[name="readygraph_ad_delay"]').val($('[name="_readygraph_ad_delay"]').val());
				//$('[name="readygraph_ad_scroll"]').val($('[name="_readygraph_ad_scroll"]').val());
				
				$('.result').text('...');
				if ($('[name="readygraph_access_token"]').val()) {
					$.ajax({
							url: resourceHost + '/api/v1/insight_info'
						, beforeSend: function (xhr) {
								xhr.setRequestHeader('Authorization', "Bearer " + $('[name="readygraph_access_token"]').val());
								xhr.setRequestHeader('Accept',        "application/json");
							}
						, method: 'POST'
						, success: function (response) {
								if (response.data) {
									$('.result').text(response.data.subscribers + ((response.data.subscribers == 0) ? ' Subscriber' : ' Subscribers'));
								} else {
									$('.result').text('Insight');
								}
							}
						, error: function (response) {
								refresh_access_token();
						}
					});
				}
			}
		}
		
		// Manage OAuth 2.0 Results
		//
		function refresh_access_token() {
			var refresh_token = $('[name="readygraph_refresh_token"]').val();
			if (refresh_token) {
				$('div.authenticate').hide();
				$('div.authenticating').show();
				$('div.authenticated').hide();
				
				$.ajax({
						url: resourceHost + '/oauth/access_token'
					, data: {
						grant_type: 'refresh_token',
            refresh_token: $('[name="readygraph_refresh_token"]').val(),
            redirect_uri: encodeURIComponent(location.href.replace('#' + location.hash,"")),
            client_id: settings.clientId
					}
					, method: 'POST'
					, success: function (response) {
							$('[name="readygraph_access_token"]').val(response.access_token);
							$('[name="readygraph_refresh_token"]').val(response.refresh_token);
              window.setAccessToken(response.access_token);
							$('.result').text(response.data.subscribers + ((response.data.subscribers == 0) ? ' Subscriber' : ' Subscribers'));
						}
					, error: function (response) {
							alert('We couldn\'t authenticate your account. Please check your internet connection.');
							$('div.authenticate').show();
							$('div.authenticating').hide();
							$('div.authenticated').hide();
						}
				});
			}
		}
		window.setCode = function(code) {
			$('.rgw-fb-login-button-iframe').hide();
      $('div.authenticate').hide();
			$('div.authenticating').show();
			$('div.authenticated').hide();
      
      $.ajax({
					url: resourceHost + '/oauth/access_token'
        , data: {
            grant_type: 'authorization_code',
            code: code,
            redirect_uri: encodeURIComponent(location.href.replace('#' + location.hash,"")),
            client_id: settings.clientId
        }
        , method: 'POST'
				, success: function (response) {
						if (response) {
							$('[name="readygraph_access_token"]').val(response.access_token);
							$('[name="readygraph_refresh_token"]').val(response.refresh_token);
              window.setAccessToken(response.access_token);
						} else {
							$('div.authenticating').hide();
							$('div.authenticate').show();
						}
					}
			});
    }
		window.setAccessToken = function(token) {
			$('.rgw-fb-login-button-iframe').hide();
			$('div.authenticate').hide();
			$('div.authenticating').show();
			$('div.authenticated').hide();
			
			$.ajax({
					url: resourceHost + '/api/v1/account_info'
				, beforeSend: function (xhr) {
						xhr.setRequestHeader('Authorization', "Bearer " + token);
						xhr.setRequestHeader('Accept',        "application/json");
					}
        , method: 'POST'
				, success: function (response) {
						if (response.data) {
							$('[name="readygraph_access_token"]').val(token);
							$('[name="readygraph_email"]').val(response.data.email);
							$('[name="readygraph_application_id"]').val(response.data.application_id);
							$('#myForm')[0].submit();
						} else {
							$('div.authenticating').hide();
							$('div.authenticate').show();
							$('.rgw-fb-login-button-iframe').hide();
						}
					}
			});
		}
	});
</script>
<script>
window.setup = false;
window.refresh_readygraph = function() {};
window.setup_readygraph = function(app_id) {
    if (window.setup) {
        window.refresh_readygraph();
        return;
    }
    window.setup = true;
    readygraph.setup({
      applicationId: app_id,
      isPreview: true,
      enableLoginWall: false,
      enableDistraction: false,
      enableAutoLogin: false,
      enableSidebar: false,
      enableNotification: false,
      enableInvite: false,
      enableOpenGraph: false,
      enableRgSeo: false
    });
    readygraph.ready(function() {
      readygraph.framework.require(['compact.sdk', 'facebook.sdk'], function() {
        var $ = readygraph.framework.jQuery;
        $.cookie('RGAuth', null);
        readygraph.framework.facebook.logout(function() {
          readygraph.framework.require(['invite'], function() {
            var VIEW_TYPE = {
              LOADING: 0,
              LOGIN_REQUIRE: 1,
              PERMISSION_REQUIRE: 2,
              DEFAULT: 3,
              LOGIN_WITH_EMAIL: 4,
              SIGNUP_WITH_EMAIL: 5,
              IMPORT_WITH_EMAIL: 6,
              FINISH: 10
            };
        
            var auth = new readygraph.framework.ui.AuthModel({
              dialog: true,
              'inviter_profile_picture': 'https://graph.facebook.com/4/picture?type=normal&width=400&height=400'
            });
            $('.rg-preview-widget').html('');
            $('.rg-preview-widget').append(auth.lightbox.view.$el);
            $('.rgw-content').attr('style', 'position: relative !important;');
            
            var view = VIEW_TYPE.LOGIN_REQUIRE;
            auth.on('switch', function() {
              if (auth.view.currentView != view) { auth.view.switchView(view); }
              else auth.view.render();
              if (view == VIEW_TYPE.DEFAULT) {
                auth.view.$el.find('.rgw-invite-view').showAndAnimate();
                auth.view.$el.find('.rgw-follow-view').hideAndAnimate();
                auth.view.$el.commitTransition();
              }
            });
            auth.view.switchView(view);
            
            $(window).scroll(function() {
              $(window).trigger('rgw-invalidate');
            });
            $('.rg-preview-widget, .content-warp').scroll(function() {
              $(window).trigger('rgw-invalidate');
            });
            $(window).trigger('rgw-invalidate');
            
            $('.rg-vertical-tab').click(function() {
                saveContent(auth, $('.rg-preview-widget-container'), true);
								
                $('.rg-vertical-tab').removeClass('active');
                $(this).addClass('active');
                view = VIEW_TYPE[$(this).attr('tab')];
                if (auth.view.currentView != view) { auth.view.switchView(view); }
                
                $('.rg-preview-widget, .content-warp').scrollTop(10000);
            });
            
            enableContentEditable(auth, $('.rg-preview-widget-container'));
            restoreContent(auth, $('.rg-preview-widget-container'));
            
            $('.save').click(function() {
                $('.save').css('opacity', 0.4);
                saveContent(auth, $('.rg-preview-widget-container'), false);
            });
            
            window.refresh_readygraph = function() {
                restoreContent(auth, $('.rg-preview-widget-container'));
            }
          });
        });
      });
    });
}
function enableContentEditable(model, container) {
    model.view.$el.find('[rgw-data-key]').each(function() {
        var element = $(this);
        if (element.attr('rgw-data-editable') == 'false') return;
        
          if (element.attr('editing') != null) return;
          container.find('.special-button-container button').attr('disabled', 'disabled');
          element.text(readygraph.getSettings().get(element.attr('rgw-data-key')));
          element.attr('editing', '1');
          element.css({
            'border': '2px dashed orange',
            'position': 'relative',
            'top': '-2px',
            'margin-bottom': '-4px',
            'background-color': '#FAFAC5'
          });
          element.attr('contenteditable', true);
          element.bind('paste', function(e) {
            e.preventDefault();
          });
          element.bind('keydown', function() { $('.save').css('opacity', '1.0'); });
      });
}
function saveContent(model, container, fake) {
    var settings = {};
    model.view.$el.find('[rgw-data-key]').each(function() {
        var element = $(this);
        if (element.attr('rgw-data-editable') == 'false') return;
        settings[element.attr('rgw-data-key')] = element.text();
        readygraph.getSettings().set(element.attr('rgw-data-key'), element.text());
    });
    if (!fake) {
				$('input[name="readygraph_settings"]').val(JSON.stringify(settings));
        $('#myForm')[0].submit();
    }
}
function restoreContent(model, container) {
    eval('window._TEMP='+$('input[name="readygraph_settings"]').val());
		var settings = window._TEMP;
    if (settings) {
        model.view.$el.find('[rgw-data-key]').each(function() {
            var element = $(this);
            if (element.attr('rgw-data-editable') == 'false') return;
            element.text(settings[element.attr('rgw-data-key')]);
            readygraph.getSettings().set(element.attr('rgw-data-key'), element.text());
        });
    }
}
</script>
<style>
/* FOR INLINE WIDGET */
.rgw-overlay {
    display: none !important;
}
.rgw-content-frame {
    left: 0 !important;
    top: 0 !important;
    position: relative !important;
    margin: 0 auto !important;
    border: solid 1px #cccccc;
}
.rgw-preview-warning {
    display: none !important;
}
.rgw-content {
    position: relative !important;
}
</style>