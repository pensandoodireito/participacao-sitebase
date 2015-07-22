<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
if(strtoupper($wpdb->get_var("show tables like '". WP_eemail_TABLE_SCF . "'")) != strtoupper(WP_eemail_TABLE_SCF))  
{
	?>
	<div class="wrap">
		<div class="form-wrap">
		<div id="icon-plugins" class="icon32"></div>
		<h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?> <?php _e('(Send email to users who contacted you)', 'email-newsletter'); ?></h2>
			<br />
			This plugin now comes with a 'Simple Contact Form' wordpress plugin. I thought it would be useful to combine this feature with the newsletter plugin. After installing the contact form, your site visitors may contact you using this form. Those users then are visible to you on this page, and you can email them by using the same procedure as outlined before.<br /><br />
			<div class="error fade">
				<p>Install the plugin 'Simple Contact Form' on your site. If it is not useful to you, please omit this menu. <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/simple-contact-form/">click here</a> </p>
			</div>
		</div>
		<p class="description"><?php echo WP_eemail_LINK; ?></p>
	</div>
	<?php
}
else
{
$eemail_errors = array();
$eemail_success = '';
$eemail_error_found = FALSE;

$search = isset($_GET['search']) ? $_GET['search'] : 'A,B,C';
if (isset($_POST['eemail_sendmail_contactform']) && $_POST['eemail_sendmail_contactform'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_sendmail_contactform');

	$form['eemail_subject_drop'] = isset($_POST['eemail_subject_drop']) ? $_POST['eemail_subject_drop'] : '';
	if ($form['eemail_subject_drop'] == '')
	{
		$eemail_errors[] = __('Please select email subject.', 'email-newsletter');
		$eemail_error_found = TRUE;
	}
	$form['eemail_checked'] = isset($_POST['eemail_checked']) ? $_POST['eemail_checked'] : '';
	if ($form['eemail_checked'] == '')
	{
		$eemail_errors[] = __('Please select email address.', 'email-newsletter');
		$eemail_error_found = TRUE;
	}
	$recipients = $_POST['eemail_checked'];
	
	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{
		$sSql = $wpdb->prepare(
				"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE."
				WHERE `eemail_id` = %d",
				array($form['eemail_subject_drop'])
			);
			$result = '0';
			$result = $wpdb->get_var($sSql);
			
			if ($result != '1')
			{
				?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'email-newsletter'); ?></strong></p></div><?php
			}
			else
			{
				$num_sent = 0;
				$recipients = $form['eemail_checked'];
				$num_sent = eemail_send_mail($form['eemail_checked'], $form['eemail_subject_drop'], "contactform" );
				?>
				<div class="updated fade">
				<p>Email has been sent to <?php echo $num_sent; ?> user(s), and <?php echo count($recipients);?> recipient(s) were originally found.</p>
				</div>
				<?php
			}
	}
}
if ($eemail_error_found == TRUE && isset($eemail_errors[0]) == TRUE)
{
	?><div class="error fade"><p><strong><?php echo $eemail_errors[0]; ?></strong></p></div><?php
}
?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/sendemail/sendmail-setting.js"></script>
<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?> <?php _e('(Send email to simple contact form users)', 'email-newsletter'); ?></h2>
	<h3><?php _e('Select email address from simple contact form users list:', 'email-newsletter'); ?></h3>
	<div style="padding-bottom:14px;padding-top:5px;">
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=A,B,C">A, B, C</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=D,E,F">D, E, F</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=G,H,I">G, H, I</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=J,K,L">J, K, L</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=M,N,O">M, N, O</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=P,Q,R">P, Q, R</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=S,T,U">S, T, U</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=V,W,X,Y,Z">V, W, X, Y, Z</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=0,1,2,3,4,5,6,7,8,9">0 - 9</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-contactform&search=ALL">ALL</a>
	</div>
	<form name="form_eemail" method="post" action="#" onsubmit="return _send_email_submit()"  >
	<?php
	$sSql = "select distinct gCF_email, gCF_name from ".WP_eemail_TABLE_SCF." where 1=1"; 
	if($search <> "")
	{
		if($search <> "ALL")
		{
			$array = explode(',', $search);
			$length = count($array);
			for ($i = 0; $i < $length; $i++) 
			{
				if(@$i == 0)
				{
					$sSql = $sSql . " and";
				}
				else
				{
					$sSql = $sSql . " or";
				}
				$sSql = $sSql . " gCF_email LIKE '" . $array[$i]. "%'";
			}
		}
	}
	$sSql = $sSql . " ORDER BY gCF_email";
	$data = $wpdb->get_results($sSql);
	$count = 0;
	if ( !empty($data) ) 
	{
		echo "<table border='0' cellspacing='0'><tr>";
		$col=3;
		foreach ( $data as $data )
		{
			$to = $data->gCF_email;
			if($to <> "")
			{
				echo "<td style='padding-top:4px;padding-bottom:4px;padding-right:10px;'>";
				?>
				<input class="radio" type="checkbox" checked="checked" value='<?php echo $to; ?>' name="eemail_checked[]">
				&nbsp;<?php echo $to; ?>
				<?php
				if($col > 1) 
				{
					$col=$col-1;
					echo "</td><td>"; 
				}
				elseif($col = 1)
				{
					$col=$col-1;
					echo "</td></tr><tr>";;
					$col=3;
				}
				$count = $count + 1;
			}
		}
		echo "</tr></table>";
	}
	else
	{
		$searchdisplay = "";
		if($search == "0,1,2,3,4,5,6,7,8,9")
		{
			$searchdisplay = "0 - 9";
		}
		else
		{
			$searchdisplay = $search;
		}
		_e($searchdisplay . ' - No email address available for this search result. Please click above buttons to search.', 'email-newsletter');
	}
	?>
	<div style="padding-top:14px;">
		<?php _e('Total emails:', 'email-newsletter'); ?> <?php echo $count; ?>
	</div>
	<div style="padding-top:14px;">
		<input class="button add-new-h2" type="hidden" name="send" value="true" />
		<input class="button add-new-h2" type="button" name="CheckAll" value="Check All" onClick="SetAllCheckBoxes('form_eemail', 'eemail_checked[]', true);">
		<input class="button add-new-h2" type="button" name="UnCheckAll" value="Uncheck All" onClick="SetAllCheckBoxes('form_eemail', 'eemail_checked[]', false);">
	</div>
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
	<h3><?php _e('Select email subject', 'email-newsletter'); ?></h3>
	<div>
		<select name="eemail_subject_drop" id="eemail_subject_drop">
			<option value=""><?php _e(' == Select Email Subject == ', 'email-newsletter'); ?></option>
			<?php echo $eemail_subject_drop_val; ?>
		</select>
	</div>
	<div style="padding-top:20px;">
	<input type="submit" name="Submit" class="button add-new-h2" value="<?php _e('Send Email', 'email-newsletter'); ?>" style="width:160px;" />&nbsp;&nbsp;
	<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="<?php _e('Cancel', 'email-newsletter'); ?>" type="button" />&nbsp;&nbsp;
    <input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="<?php _e('Help', 'email-newsletter'); ?>" type="button" />
	</div>
	<?php wp_nonce_field('eemail_sendmail_contactform'); ?>
	<input type="hidden" name="eemail_sendmail_contactform" id="eemail_sendmail_contactform" value="yes"/>
	</form>
	</div>
	<?php include_once("steps.php"); ?>
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>
<?php
}
?>