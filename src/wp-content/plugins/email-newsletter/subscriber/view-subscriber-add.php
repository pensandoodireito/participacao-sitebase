<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$eemail_errors = array();
$eemail_success = '';
$eemail_error_found = FALSE;

// Preset the form fields
$form = array(
	'eemail_email_sub' => ''
);

// Form submitted, check the data
if (isset($_POST['eemail_form_submit']) && $_POST['eemail_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_form_importemails');
	
	$form['importemails'] = isset($_POST['importemails']) ? $_POST['importemails'] : '';
	$form['importemails_status'] = isset($_POST['importemails_status']) ? $_POST['importemails_status'] : 'CON';
	if ($form['importemails'] == '')
	{
		$eemail_errors[] = __('Please enter email address.', 'email-newsletter');
		$eemail_error_found = TRUE;
	}

	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{
		$ArrayEmail = explode(',', $form['importemails']);
		$Inserted = 0;
		$Duplicate = 0;
		$CurrentDate = date('Y-m-d G:i:s'); 
		for ($i = 0; $i < count($ArrayEmail); $i++)
		{
			$cSql = "select * from ".WP_eemail_TABLE_SUB." where eemail_email_sub='" . trim($ArrayEmail[$i]). "'";
			$data = $wpdb->get_results($cSql);
			if ( empty($data) ) 
			{
				$sql = $wpdb->prepare(
					"INSERT INTO `".WP_eemail_TABLE_SUB."`
					(`eemail_name_sub`,`eemail_email_sub`, `eemail_status_sub`, `eemail_date_sub`)
					VALUES(%s, %s, %s, %s)",
					array('No Name', $ArrayEmail[$i], $form['importemails_status'], $CurrentDate)
				);
				$wpdb->query($sql);
				$Inserted = $Inserted + 1;
			}
			else
			{
				$Duplicate = $Duplicate + 1;
			}
		}
		$eemail_success[] = __($Inserted . ' Email(s) was successfully imported.', 'email-newsletter');
		$eemail_success[] = __($Duplicate . ' Email(s) are already in our database.', 'email-newsletter');
		
		// Reset the form fields
		$form = array(
			'eemail_email_sub' => ''
		);
	}
}

if ($eemail_error_found == TRUE && isset($eemail_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $eemail_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($eemail_error_found == FALSE && isset($eemail_success[0]) == TRUE)
{
	?>
	  <div class="updated fade">
		<p>
		<strong>
		<?php echo $eemail_success[0]; ?> <br />
		<?php echo $eemail_success[1]; ?> <br />
		<a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber"><?php _e('Click here', 'email-newsletter'); ?></a> <?php _e(' to view the details', 'email-newsletter'); ?></strong>
		</p>
	  </div>
	  <?php
	}
?>
<script language="javaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/subscriber/subscriber-setting.js"></script>
<div class="form-wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
	<form name="form_importemails" method="post" action="#" onsubmit="return _eemail_import()"  >
      <h3><?php _e('Add email/Import email', 'email-newsletter'); ?></h3>
      
	  <label for="tag-image"><?php _e('Enter email subject.', 'email-newsletter'); ?></label>
      <textarea name="importemails" cols="120" rows="8"></textarea>
      <p><?php _e('Enter the email address with comma separated (No comma at the end).', 'email-newsletter'); ?></p>
	  
	  <label for="tag-display-status"><?php _e('Status', 'email-newsletter'); ?></label>
      <select name="importemails_status" id="importemails_status">
		<option value='YES'>Old Email</option>
        <option value='SIG'>Single Opt In</option>
		<option value='PEN'>Not confirmed</option>
		<option value='CON' selected="selected">Confirmed</option>
		<option value='UNS'>Unsubscribed</option>
      </select>
      <p><?php _e('Unsubscribed, Not confirmed emails not display in send mail page.', 'email-newsletter'); ?></p>
	  
	  <input name="eemail_id" id="eemail_id" type="hidden" value="">
      <input type="hidden" name="eemail_form_submit" value="yes"/>
	  <div style="padding-top:5px;"></div>
      <p>
        <input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Insert Details', 'email-newsletter'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="<?php _e('Cancel', 'email-newsletter'); ?>" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="<?php _e('Help', 'email-newsletter'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('eemail_form_importemails'); ?>
    </form>
</div>
<h3><?php _e('Note', 'email-newsletter'); ?></h3>
<ol>
	<li><?php _e('Enter your email address with comma separated.', 'email-newsletter'); ?></li>
	<li><?php _e('Enter maximum 25 email address at one time.', 'email-newsletter'); ?></li>
	<li><?php _e('Comma not allowed at the end of the string.', 'email-newsletter'); ?></li>
</ol>
<h3><?php _e('Wrong format', 'email-newsletter'); ?></h3>
<ol>
	<li>admin@gmail.com,admin1@gmail.com, &nbsp;&nbsp;&nbsp;&nbsp;<?php _e('(Comma at the end)', 'email-newsletter'); ?></li>
	<li>admin@gmail.com,,admin1@gmail.com &nbsp;&nbsp;&nbsp;&nbsp;<?php _e('(Two comma)', 'email-newsletter'); ?></li>
</ol>
<h3><?php _e('Correct format', 'email-newsletter'); ?></h3>
<ol>
	<li>admin@gmail.com,admin1@gmail.com</li>
</ol>
<p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>