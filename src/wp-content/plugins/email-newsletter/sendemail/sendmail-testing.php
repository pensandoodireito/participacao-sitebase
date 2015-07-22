<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/sendemail/sendmail-setting.js"></script>
<?php

$eemail_errors = array();
$eemail_success = '';
$eemail_error_found = FALSE;

function eemail_valid_testemail($email) 
{
   $regex = '/^[A-z0-9][\w.+-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
   return (preg_match($regex, $email));
}

if (isset($_POST['eemail_sendmail_testing']) && $_POST['eemail_sendmail_testing'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_sendmail_testing');
	
	$arrEmail = array();
	
	$form['eemail_subject_drop'] = isset($_POST['eemail_subject_drop']) ? $_POST['eemail_subject_drop'] : '';
	if ($form['eemail_subject_drop'] == '')
	{
		$eemail_errors[] = __('Please select email subject.', 'email-newsletter');
		$eemail_error_found = TRUE;
	}
	
	$form['eemail_email_1'] = isset($_POST['eemail_email_1']) ? $_POST['eemail_email_1'] : '';
	$form['eemail_email_2'] = isset($_POST['eemail_email_2']) ? $_POST['eemail_email_2'] : '';
	$form['eemail_email_3'] = isset($_POST['eemail_email_3']) ? $_POST['eemail_email_3'] : '';
	
	if ($form['eemail_email_1'] == '')
	{
		$eemail_errors[] = __('Please provide a valid email address.', 'email-newsletter');
		$eemail_error_found = TRUE;
	}
	
	if($form['eemail_email_1'] <> "")
	{
		if ( !eemail_valid_testemail($form['eemail_email_1']) ) 
		{ 
			$eemail_errors[] = __('Please provide a valid email address (1).', 'email-newsletter');
			$eemail_error_found = TRUE;
		}
		else
		{
			$arrEmail[0] = $form['eemail_email_1'];
		}
	}
	
	if($form['eemail_email_2'] <> "")
	{
		if ( !eemail_valid_testemail($form['eemail_email_2']) ) 
		{ 
			$eemail_errors[] = __('Please provide a valid email address (2).', 'email-newsletter');
			$eemail_error_found = TRUE;
		}
		else
		{
			$arrEmail[1] = $form['eemail_email_2'];
		}
	}
	
	if($form['eemail_email_3'] <> "")
	{
		if ( !eemail_valid_testemail($form['eemail_email_3']) ) 
		{ 
			$eemail_errors[] = __('Please provide a valid email address (3).', 'email-newsletter');
			$eemail_error_found = TRUE;
		}
		else
		{
			$arrEmail[2] = $form['eemail_email_3'];
		}
	}
	
	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{
		$num_sent = 0;	
		$num_sent = eemail_send_mail($arrEmail, $form['eemail_subject_drop'], "testing" );
		?>
		<div class="updated fade">
		<strong><p>Email has been sent to <?php echo $num_sent; ?> user(s), and <?php echo count($arrEmail);?> recipient(s) were originally found.</p></strong>
		</div>
		<?php
	}
			
}
if ($eemail_error_found == TRUE && isset($eemail_errors[0]) == TRUE)
{
	?><div class="error fade"><p><strong><?php echo $eemail_errors[0]; ?></strong></p></div><?php
}
?>
<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?> <?php _e('(Send Test Mail)', 'email-newsletter'); ?></h2>
    <h3></h3>
	<?php
	$data = $wpdb->get_results("select eemail_id, eemail_subject  from ".WP_eemail_TABLE." where 1=1 and eemail_status='YES' order by eemail_id desc");
	if ( !empty($data) ) 
	{
		foreach ( $data as $data )
		{
			if($data->eemail_subject <> "")
			{
				@$eemail_subject_drop_val = @$eemail_subject_drop_val . '<option value="'.$data->eemail_id.'">' . stripcslashes($data->eemail_subject) . '</option>';
			}
		}
	}
	?>
	<form name="form_eemail" method="post" action="#" onsubmit="return _send_email_testing()"  >
	<h3><?php _e('Select email subject', 'email-newsletter'); ?></h3>
	<div>
		<select name="eemail_subject_drop" id="eemail_subject_drop">
			<option value=""><?php _e(' == Select Email Subject == ', 'email-newsletter'); ?></option>
			<?php echo $eemail_subject_drop_val; ?>
		</select>
	</div>
	<h3><?php _e('Enter email address', 'email-newsletter'); ?></h3>
	
	<label for="tag-title"><?php _e('Email address 1', 'email-newsletter'); ?></label>
	<input name="eemail_email_1" id="eemail_email_1" type="text" value="" maxlength="150" size="50" />
	<p></p>
	
	<label for="tag-title"><?php _e('Email address 2', 'email-newsletter'); ?></label>
	<input name="eemail_email_2" id="eemail_email_2" type="text" value="" maxlength="150" size="50" />
	<p></p>
	
	<label for="tag-title"><?php _e('Email address 3', 'email-newsletter'); ?></label>
	<input name="eemail_email_3" id="eemail_email_3" type="text" value="" maxlength="150" size="50" />
	<p></p>
	
	<div style="padding-top:20px;">
	<input type="submit" name="Submit" class="button add-new-h2" value="<?php _e('Send Email', 'email-newsletter'); ?>" style="width:160px;" />&nbsp;&nbsp;
	<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="<?php _e('Cancel', 'email-newsletter'); ?>" type="button" />&nbsp;&nbsp;
    <input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="<?php _e('Help', 'email-newsletter'); ?>" type="button" />
	</div>
	<?php wp_nonce_field('eemail_sendmail_testing'); ?>
	<input type="hidden" name="eemail_sendmail_testing" id="eemail_sendmail_testing" value="yes"/>
	</form>
	
	</div><br />
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>