<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/pages/pages-setting.js"></script>
<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
    <h3><?php _e('Widget setting', 'email-newsletter'); ?></h3>
	<?php
	$eemail_title = get_option('eemail_title');
	$eemail_on_homepage = get_option('eemail_on_homepage');
	$eemail_on_posts = get_option('eemail_on_posts');
	$eemail_on_pages = get_option('eemail_on_pages');
	$eemail_on_search = get_option('eemail_on_search');
	$eemail_on_archives = get_option('eemail_on_archives');
	$eemail_widget_cap = get_option('eemail_widget_cap');
	$eemail_widget_txt_cap = get_option('eemail_widget_txt_cap');
	$eemail_widget_but_cap = get_option('eemail_widget_but_cap');
	
	if (@$_POST['eemail_submit']) 
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('eemail_form_widget');
		
		$eemail_title = stripslashes($_POST['eemail_title']);
		$eemail_on_homepage = stripslashes($_POST['eemail_on_homepage']);
		$eemail_on_posts = stripslashes($_POST['eemail_on_posts']);
		$eemail_on_pages = stripslashes($_POST['eemail_on_pages']);
		$eemail_on_search = stripslashes($_POST['eemail_on_search']);
		$eemail_on_archives = stripslashes($_POST['eemail_on_archives']);
		$eemail_widget_cap = stripslashes($_POST['eemail_widget_cap']);
		$eemail_widget_txt_cap = stripslashes($_POST['eemail_widget_txt_cap']);
		$eemail_widget_but_cap = stripslashes($_POST['eemail_widget_but_cap']);
		
		update_option('eemail_title', $eemail_title );
		update_option('eemail_on_homepage', $eemail_on_homepage );
		update_option('eemail_on_posts', $eemail_on_posts );
		update_option('eemail_on_pages', $eemail_on_pages );
		update_option('eemail_on_search', $eemail_on_search );
		update_option('eemail_on_archives', $eemail_on_archives );
		update_option('eemail_widget_cap', $eemail_widget_cap );
		update_option('eemail_widget_txt_cap', $eemail_widget_txt_cap );
		update_option('eemail_widget_but_cap', $eemail_widget_but_cap );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'email-newsletter'); ?></strong></p>
		</div>
		<?php
	}
	
	?>
	<form name="eemail_form" method="post" action="">
	
	<label for="tag-title"><?php _e('Title', 'email-newsletter'); ?></label>
	<input name="eemail_title" id="eemail_title" type="text" value="<?php echo $eemail_title; ?>" maxlength="150" size="50" />
	<p><?php _e('Please enter widget title.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Display option (Home page)', 'email-newsletter'); ?></label>
	<input name="eemail_on_homepage" id="eemail_on_homepage" type="text" value="<?php echo $eemail_on_homepage; ?>" maxlength="3" />
	<p><?php _e('Display widget on website home pages. Enter YES (or) NO', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Display option (Posts)', 'email-newsletter'); ?></label>
	<input name="eemail_on_posts" id="eemail_on_posts" type="text" value="<?php echo $eemail_on_posts; ?>" maxlength="3" />
	<p><?php _e('Display widget on all posts. Enter YES (or) NO', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Display option (Pages)', 'email-newsletter'); ?></label>
	<input name="eemail_on_pages" id="eemail_on_pages" type="text" value="<?php echo $eemail_on_pages; ?>" maxlength="3" />
	<p><?php _e('Display widget on all pages. Enter YES (or) NO', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Display option (Search page)', 'email-newsletter'); ?></label>
	<input name="eemail_on_search" id="eemail_on_search" type="text" value="<?php echo $eemail_on_search; ?>" maxlength="3" />
	<p><?php _e('Display widget on all search result pages. Enter YES (or) NO', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Display option (Archives page)', 'email-newsletter'); ?></label>
	<input name="eemail_on_archives" id="eemail_on_archives" type="text" value="<?php echo $eemail_on_archives; ?>" maxlength="3" />
	<p><?php _e('Display widget on all archive pages. Enter YES (or) NO', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Short description', 'email-newsletter'); ?></label>
	<input name="eemail_widget_cap" id="eemail_widget_cap" type="text" value="<?php echo $eemail_widget_cap; ?>" size="50" />
	<p><?php _e('Please enter short description about your widget.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('TextBox caption', 'email-newsletter'); ?></label>
	<input name="eemail_widget_txt_cap" id="eemail_widget_txt_cap" type="text" value="<?php echo $eemail_widget_txt_cap; ?>" />
	<p><?php _e('Please enter text to show within email text box.', 'email-newsletter'); ?></p>
	
	<label for="tag-title"><?php _e('Button caption', 'email-newsletter'); ?></label>
	<input name="eemail_widget_but_cap" id="eemail_widget_but_cap" type="text" value="<?php echo $eemail_widget_but_cap; ?>" />
	<p><?php _e('Please enter text to shown on the widget submit button.', 'email-newsletter'); ?></p>
	
	<p style="padding-top:10px;">
		<input type="submit" id="eemail_submit" name="eemail_submit" lang="publish" class="button add-new-h2" value="<?php _e('Update Settings', 'email-newsletter'); ?>" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="<?php _e('Cancel', 'email-newsletter'); ?>" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="<?php _e('Help', 'email-newsletter'); ?>" type="button" />
	</p>
	<?php wp_nonce_field('eemail_form_widget'); ?>
	</form>
  </div><br />
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>