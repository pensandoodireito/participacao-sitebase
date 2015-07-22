<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/pages/pages-setting.js"></script>
<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
    <h3><?php _e('Unsubscribe link setting', 'email-newsletter'); ?></h3>
	<?php
	$eemail_un_option = get_option('eemail_un_option');
	$eemail_un_text = get_option('eemail_un_text');
	$eemail_un_link = get_option('eemail_un_link');
	$eemail_msgdis_3 = get_option('eemail_msgdis_3');
	$eemail_msgdis_4 = get_option('eemail_msgdis_4');
	$eemail_msgdis_5 = get_option('eemail_msgdis_5');
	
	if (@$_POST['eemail_submit']) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('eemail_form_unsubscribe');
		
		$eemail_un_option = stripslashes($_POST['eemail_un_option']);
		$eemail_un_text = stripslashes($_POST['eemail_un_text']);
		$eemail_un_link = stripslashes($_POST['eemail_un_link']);
		$eemail_msgdis_3 = stripslashes($_POST['eemail_msgdis_3']);
		$eemail_msgdis_4 = stripslashes($_POST['eemail_msgdis_4']);	
		$eemail_msgdis_5 = stripslashes($_POST['eemail_msgdis_5']);	
		
		update_option('eemail_un_option', $eemail_un_option );
		update_option('eemail_un_text', $eemail_un_text );
		update_option('eemail_un_link', $eemail_un_link );
		update_option('eemail_msgdis_3', $eemail_msgdis_3 );
		update_option('eemail_msgdis_4', $eemail_msgdis_4 );
		update_option('eemail_msgdis_5', $eemail_msgdis_5 );
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'email-newsletter'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<form name="form_eemail" method="post" action="">
	
	<label for="tag-title"><?php _e('Unsubscribe Option', 'email-newsletter'); ?></label>
	<select name="eemail_un_option" id="eemail_un_option">
		<option value="Yes" <?php if($eemail_un_option=='Yes') { echo 'selected' ; } ?>><?php _e('Yes, Add an unsubscribe link in email newletter.', 'email-newsletter'); ?></option>
		<option value="No" <?php if($eemail_un_option=='No') { echo 'selected' ; } ?>><?php _e('No, Dont want unsubscribe link in email newletter.', 'email-newsletter'); ?></option>
	</select>
	<p><?php _e('Please enter your option from the list.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Unsubscribe text', 'email-newsletter'); ?></label>
	<textarea name="eemail_un_text" cols="80" rows="5"><?php echo $eemail_un_text; ?></textarea>
	<p><?php _e('Please enter your unsubscribe text. ##LINK## is a keyword.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Unsubscribe link', 'email-newsletter'); ?></label>
	<input name="eemail_un_link" type="text" size="120" value="<?php echo $eemail_un_link; ?>" />
	<p><?php _e('Please enter your unsubscribe link.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Static message 3', 'email-newsletter'); ?></label>
	<textarea name="eemail_msgdis_3" id="eemail_msgdis_3" cols="100" rows="5"><?php echo $eemail_msgdis_3; ?></textarea>
	<p><?php _e('Static message in unsubscribe page.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Static message 4', 'email-newsletter'); ?></label>
	<textarea name="eemail_msgdis_4" id="eemail_msgdis_4" cols="100" rows="5"><?php echo $eemail_msgdis_4; ?></textarea>
	<p><?php _e('Static message in unsubscribe page, if no email found.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Static message 5', 'email-newsletter'); ?></label>
	<textarea name="eemail_msgdis_5" id="eemail_msgdis_5" cols="100" rows="5"><?php echo $eemail_msgdis_5; ?></textarea>
	<p><?php _e('Static message for unexpected error.', 'email-newsletter'); ?></p>
	
	<p style="padding-top:10px;">
		<input type="submit" id="eemail_submit" name="eemail_submit" lang="publish" class="button add-new-h2" value="<?php _e('Update Settings', 'email-newsletter'); ?>" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="<?php _e('Cancel', 'email-newsletter'); ?>" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="<?php _e('Help', 'email-newsletter'); ?>" type="button" />
	</p>
	<?php wp_nonce_field('eemail_form_unsubscribe'); ?>
	</form>
	</div><br />
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>