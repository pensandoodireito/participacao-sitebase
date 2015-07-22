<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/pages/pages-setting.js"></script>
<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
    <h3><?php _e('Opt In Page Configuration', 'email-newsletter'); ?></h3>
	<?php
	$eemail_opt_option = get_option('eemail_opt_option');
	$eemail_opt_subject = get_option('eemail_opt_subject');
	$eemail_opt_content = get_option('eemail_opt_content');
	$eemail_opt_link = get_option('eemail_opt_link');
	$eemail_msgdis_1 = get_option('eemail_msgdis_1');
	$eemail_msgdis_2 = get_option('eemail_msgdis_2');
	$eemail_msgdis_6 = get_option('eemail_msgdis_6');
	
	if (isset($_POST['eemail_form_submit_opt']) && $_POST['eemail_form_submit_opt'] == 'yes')
	{
		check_admin_referer('eemail_form_opt');
		$eemail_opt_option = stripslashes($_POST['eemail_opt_option']);
		$eemail_opt_subject = stripslashes($_POST['eemail_opt_subject']);
		$eemail_opt_content = stripslashes($_POST['eemail_opt_content']);
		$eemail_opt_link = stripslashes($_POST['eemail_opt_link']);	
		$eemail_msgdis_1 = stripslashes($_POST['eemail_msgdis_1']);	
		$eemail_msgdis_2 = stripslashes($_POST['eemail_msgdis_2']);	
		$eemail_msgdis_6 = stripslashes($_POST['eemail_msgdis_6']);	
		
		update_option('eemail_opt_option', $eemail_opt_option );
		update_option('eemail_opt_subject', $eemail_opt_subject );
		update_option('eemail_opt_content', $eemail_opt_content );
		update_option('eemail_opt_link', $eemail_opt_link );
		update_option('eemail_msgdis_1', $eemail_msgdis_1 );
		update_option('eemail_msgdis_2', $eemail_msgdis_2 );
		update_option('eemail_msgdis_6', $eemail_msgdis_6 );
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'email-newsletter'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<form name="form_eemail" method="post" action="">
	
	<label for="tag-title"><?php _e('Opt-In option', 'email-newsletter'); ?></label>
	<select name="eemail_opt_option" id="eemail_opt_option">
		<option value="double-optin" <?php if($eemail_opt_option=='double-optin') { echo 'selected' ; } ?>>Double Opt In</option>
		<option value="single-optin" <?php if($eemail_opt_option=='single-optin') { echo 'selected' ; } ?>>Single Opt In </option>
	</select>
	<p><?php _e('Double Opt In, means subscribers need to confirm their email address by an activation link sent them on a activation email message. <br />Single Opt In, means subscribers do not need to confirm their email address.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Opt-In email subject', 'email-newsletter'); ?></label>
	<input name="eemail_opt_subject" id="eemail_opt_subject" type="text" size="120" value="<?php echo $eemail_opt_subject; ?>" />
	<p><?php _e('Please enter Opt-In email subject', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Opt-In email content', 'email-newsletter'); ?></label>
	<textarea name="eemail_opt_content" cols="80" rows="5"><?php echo $eemail_opt_content; ?></textarea>
	<p><?php _e('Please enter Opt-In email content. ##LINK## is a key word.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Opt-In link', 'email-newsletter'); ?></label>
	<input name="eemail_opt_link" id="eemail_opt_link" type="text" size="120" value="<?php echo $eemail_opt_link; ?>" />
	<p><?php _e('Please enter your Opt-In link.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Static message 1', 'email-newsletter'); ?></label>
	<textarea name="eemail_msgdis_1" id="eemail_msgdis_1" cols="100" rows="5"><?php echo $eemail_msgdis_1; ?></textarea>
	<p><?php _e('Static message after Double Opt In confirmation.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Static message 2', 'email-newsletter'); ?></label>
	<textarea name="eemail_msgdis_2" id="eemail_msgdis_2" cols="100" rows="5"><?php echo $eemail_msgdis_2; ?></textarea>
	<p><?php _e('Static message in Double Opt In confirmation page, if no email found.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Static message 6', 'email-newsletter'); ?></label>
	<textarea name="eemail_msgdis_6" id="eemail_msgdis_6" cols="100" rows="5"><?php echo $eemail_msgdis_6; ?></textarea>
	<p><?php _e('Static message for unexpected error.', 'email-newsletter'); ?></p>
	
	<p style="padding-top:10px;">
		<input type="submit" id="eemail_submit" name="eemail_submit" lang="publish" class="button add-new-h2" value="<?php _e('Update Settings', 'email-newsletter'); ?>" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="<?php _e('Cancel', 'email-newsletter'); ?>" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="<?php _e('Help', 'email-newsletter'); ?>" type="button" />
	</p>
	<?php wp_nonce_field('eemail_form_opt'); ?>
	<input type="hidden" name="eemail_form_submit_opt" value="yes"/>
	</form>
	</div>
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>