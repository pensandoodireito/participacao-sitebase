<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/pages/pages-setting.js"></script>
<div class="wrap">
	<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
    <h3><?php _e('Email setting', 'email-newsletter'); ?></h3>
	<?php
	$eemail_from_name = get_option('eemail_from_name');
	$eemail_from_email = get_option('eemail_from_email');
	
	$eemail_admin_email_option = get_option('eemail_admin_email_option');
	$eemail_admin_email_address = get_option('eemail_admin_email_address');
	$eemail_admin_email_content = get_option('eemail_admin_email_content');
	$eemail_user_email_option = get_option('eemail_user_email_option');
	$eemail_user_email_content = get_option('eemail_user_email_content');
	$eemail_email_type = get_option('eemail_email_type');
	
	$eemail_admin_email_subject = get_option('eemail_admin_email_subject');
	$eemail_user_email_subject = get_option('eemail_user_email_subject');
	
	if (@$_POST['eemail_submit']) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('eemail_form_email');
		
		$eemail_from_name = stripslashes($_POST['eemail_from_name']);
		$eemail_from_email = stripslashes($_POST['eemail_from_email']);
		
		$eemail_admin_email_option = stripslashes($_POST['eemail_admin_email_option']);
		$eemail_admin_email_address = stripslashes($_POST['eemail_admin_email_address']);
		$eemail_admin_email_content = stripslashes($_POST['eemail_admin_email_content']);
		$eemail_user_email_option = stripslashes($_POST['eemail_user_email_option']);
		$eemail_user_email_content = stripslashes($_POST['eemail_user_email_content']);
		$eemail_email_type = stripslashes($_POST['eemail_email_type']);
		
		$eemail_admin_email_subject = stripslashes($_POST['eemail_admin_email_subject']);
		$eemail_user_email_subject = stripslashes($_POST['eemail_user_email_subject']);
		
		update_option('eemail_from_name', $eemail_from_name );
		update_option('eemail_from_email', $eemail_from_email );
		
		update_option('eemail_admin_email_option', $eemail_admin_email_option );
		update_option('eemail_admin_email_address', $eemail_admin_email_address );
		update_option('eemail_admin_email_content', $eemail_admin_email_content );
		update_option('eemail_user_email_option', $eemail_user_email_option );
		update_option('eemail_user_email_content', $eemail_user_email_content );
		update_option('eemail_email_type', $eemail_email_type );
		
		update_option('eemail_admin_email_subject', $eemail_admin_email_subject );
		update_option('eemail_user_email_subject', $eemail_user_email_subject );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'email-newsletter'); ?></strong></p>
		</div>
		<?php
	}
	?>

	<form name="eemail_form" method="post" action="" onsubmit="return _email_setting()" >
	<label for="tag-title"><?php _e('From email name', 'email-newsletter'); ?></label>
	<input name="eemail_from_name" id="eemail_from_name" type="text" value="<?php echo $eemail_from_name; ?>" maxlength="150" size="50" />
	<p><?php _e('Please enter your from email name.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('From email address', 'email-newsletter'); ?></label>
	<input name="eemail_from_email" id="eemail_from_email" type="text" value="<?php echo $eemail_from_email; ?>" maxlength="150" size="50" />
	<p><?php _e('Please enter your from email address.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Send auto email to admin', 'email-newsletter'); ?></label>
	<select name="eemail_admin_email_option" id="eemail_admin_email_option">
		<option value=''><?php _e('Select', 'email-newsletter'); ?></option>
		<option value='YES' <?php if($eemail_admin_email_option == 'YES') { echo 'selected' ; } ?>>Yes</option>
		<option value='NO' <?php if($eemail_admin_email_option == 'NO') { echo 'selected' ; } ?>>No</option>
	</select>
	<p><?php _e('Send email to admin when new user subscribed.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Admin email address', 'email-newsletter'); ?></label>
	<input name="eemail_admin_email_address" id="eemail_admin_email_address" type="text" value="<?php echo $eemail_admin_email_address; ?>" maxlength="150" size="50" />
	<p><?php _e('Please enter admin email address to received email.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Admin email subject', 'email-newsletter'); ?></label>
	<input name="eemail_admin_email_subject" id="eemail_admin_email_subject" type="text" value="<?php echo $eemail_admin_email_subject; ?>" maxlength="150" size="50" />
	<p><?php _e('Please enter admin email subject.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Admin email content', 'email-newsletter'); ?></label>
	<textarea name="eemail_admin_email_content" id="eemail_admin_email_content" cols="100" rows="6"><?php echo esc_html(stripslashes($eemail_admin_email_content)); ?></textarea>
	<p><?php _e('Please enter admin email content. (Keyword: ##USEREMAIL##)', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Send auto email to subscriber', 'email-newsletter'); ?></label>
	<select name="eemail_user_email_option" id="eemail_user_email_option">
		<option value=''><?php _e('Select', 'email-newsletter'); ?></option>
		<option value='YES' <?php if($eemail_user_email_option == 'YES') { echo 'selected' ; } ?>>Yes</option>
		<option value='NO' <?php if($eemail_user_email_option == 'NO') { echo 'selected' ; } ?>>No</option>
	</select>
	<p><?php _e('Send welcome email to subscriber.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Subscriber email subject', 'email-newsletter'); ?></label>
	<input name="eemail_user_email_subject" id="eemail_user_email_subject" type="text" value="<?php echo $eemail_user_email_subject; ?>" maxlength="150" size="50" />
	<p><?php _e('Please enter Subscriber email subject.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Subscriber email content', 'email-newsletter'); ?></label>
	<textarea name="eemail_user_email_content" id="eemail_user_email_content" cols="100" rows="6"><?php echo esc_html(stripslashes($eemail_user_email_content)); ?></textarea>
	<p><?php _e('Please enter subscriber welcome email content.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Email type', 'email-newsletter'); ?></label>
	<select name="eemail_email_type" id="eemail_email_type">
		<option value='HTML' <?php if($eemail_email_type == 'HTML') { echo 'selected' ; } ?>>HTML Email</option>
		<option value='PLAINTEXT' <?php if($eemail_email_type == 'PLAINTEXT') { echo 'selected' ; } ?>>Plain Text</option>
	</select>
	<p><?php _e('Please enter subscriber welcome email content.', 'email-newsletter'); ?></p>
	
	<p style="padding-top:10px;">
		<input type="submit" id="eemail_submit" name="eemail_submit" lang="publish" class="button add-new-h2" value="<?php _e('Update Settings', 'email-newsletter'); ?>" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="<?php _e('Cancel', 'email-newsletter'); ?>" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="<?php _e('Help', 'email-newsletter'); ?>" type="button" />
	</p>
	<?php wp_nonce_field('eemail_form_email'); ?>
	</form>
	</div><br />
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>