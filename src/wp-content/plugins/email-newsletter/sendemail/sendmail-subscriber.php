<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
$eemail_errors = array();
$eemail_success = '';
$eemail_error_found = FALSE;

$search = isset($_GET['search']) ? $_GET['search'] : 'A,B,C';
if (isset($_POST['eemail_sendmail_subscriber']) && $_POST['eemail_sendmail_subscriber'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_sendmail_subscriber');

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
				$num_sent = eemail_send_mail($form['eemail_checked'], $form['eemail_subject_drop'], "subscriber" );
				?>
				<div class="updated fade">
				<strong><p>Email has been sent to <?php echo $num_sent; ?> user(s), and <?php echo count($recipients);?> recipient(s) were originally found.</p></strong>
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
    <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?> <?php _e('(Send email to subscribed users)', 'email-newsletter'); ?></h2>
	<h3><?php _e('Select email address from subscribed users list:', 'email-newsletter'); ?></h3>
	<div style="padding-bottom:14px;padding-top:5px;">
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=A,B,C">A, B, C</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=D,E,F">D, E, F</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=G,H,I">G, H, I</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=J,K,L">J, K, L</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=M,N,O">M, N, O</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=P,Q,R">P, Q, R</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=S,T,U">S, T, U</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=V,W,X,Y,Z">V, W, X, Y, Z</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=0,1,2,3,4,5,6,7,8,9">0 - 9</a>&nbsp;&nbsp;
		<a class="button add-new-h2" href="admin.php?page=sendmail-subscriber&search=ALL">ALL</a>
	</div>
	<form name="form_eemail" method="post" action="#" onsubmit="return _send_email_submit()"  >
	<?php
	$sSql = "select distinct eemail_email_sub, eemail_id_sub from ".WP_eemail_TABLE_SUB." where 1=1"; 
	$sSql = $sSql . " and (eemail_status_sub ='YES' OR eemail_status_sub ='SIG' OR eemail_status_sub ='CON')";
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
				$sSql = $sSql . " eemail_email_sub LIKE '" . $array[$i]. "%'";
			}
		}
	}
	$sSql = $sSql . " ORDER BY eemail_email_sub";
	$data = $wpdb->get_results($sSql);
	$count = 0;
	if ( !empty($data) ) 
	{
		echo "<table border='0' cellspacing='0'><tr>";
		$col=3;
		foreach ( $data as $data )
		{
			$to = $data->eemail_email_sub;
			$eemail_id_sub = $data->eemail_id_sub;
			$ToAddress = trim($to) . '<||>' . trim($eemail_id_sub);
			if($to <> "")
			{
				echo "<td style='padding-top:4px;padding-bottom:4px;padding-right:10px;'>";
				?>
				<input class="radio" type="checkbox" checked="checked" value='<?php echo $ToAddress; ?>' id="eemail_checked[]" name="eemail_checked[]">
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
	<?php wp_nonce_field('eemail_sendmail_subscriber'); ?>
	<input type="hidden" name="eemail_sendmail_subscriber" id="eemail_sendmail_subscriber" value="yes"/>
	</form>
	</div>
	<?php include_once("steps.php"); ?>
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>