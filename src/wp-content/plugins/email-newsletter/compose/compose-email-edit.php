<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE."
	WHERE `eemail_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist (1).', 'email-newsletter'); ?></strong></p></div><?php
}
else
{
	$eemail_errors = array();
	$eemail_success = '';
	$eemail_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_eemail_TABLE."`
		WHERE `eemail_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'eemail_subject' => $data['eemail_subject'],
		'eemail_content' => $data['eemail_content'],
		'eemail_status' => $data['eemail_status'],
		'eemail_date' => $data['eemail_date']
	);
}
// Form submitted, check the data
if (isset($_POST['eemail_form_submit']) && $_POST['eemail_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_form_edit');
	
	$form['eemail_subject'] = isset($_POST['eemail_subject']) ? $_POST['eemail_subject'] : '';
	if ($form['eemail_subject'] == '')
	{
		$eemail_errors[] = __('Please enter email subject.', 'email-newsletter');
		$eemail_error_found = TRUE;
	}

	$form['eemail_content'] = isset($_POST['eemail_content']) ? $_POST['eemail_content'] : '';
	$form['eemail_status'] = isset($_POST['eemail_status']) ? $_POST['eemail_status'] : '';

	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_eemail_TABLE."`
				SET `eemail_subject` = %s,
				`eemail_content` = %s,
				`eemail_status` = %s
				WHERE eemail_id = %d
				LIMIT 1",
				array($form['eemail_subject'], $form['eemail_content'], $form['eemail_status'], $did)
			);
		$wpdb->query($sSql);
		$eemail_success = __('Email was successfully updated.', 'email-newsletter');
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
if ($eemail_error_found == FALSE && strlen($eemail_success) > 0)
{
	?>
	<div class="updated fade">
		<p>
			<strong>
				<?php echo $eemail_success; ?> 
				<a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=compose-email"><?php _e('Click here', 'email-newsletter'); ?></a>
				<?php _e(' to view the details', 'email-newsletter'); ?>
			</strong>
		</p>
	</div>
	<?php
}
?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/compose/compose-email-setting.js"></script>
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
<div class="form-wrap">
	<div id="icon-plugins" class="icon32"></div>
	<h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
	<form name="eemail_form" method="post" action="#" onsubmit="return _eemail_submit()"  >
      <h3><?php _e('Edit email', 'email-newsletter'); ?></h3>
	  <label for="tag-image"><?php _e('Enter email subject.', 'email-newsletter'); ?></label>
      <input name="eemail_subject" type="text" id="eemail_subject" value="<?php echo esc_html(stripslashes($form['eemail_subject'])); ?>" size="90" />
      <p><?php _e('Please enter your email subject.', 'email-newsletter'); ?></p>
	  <label for="tag-link"><?php _e('Enter email content', 'email-newsletter'); ?></label>
      <textarea name="eemail_content" cols="140" rows="25" id="eemail_content"><?php echo esc_html(stripslashes($form['eemail_content'])); ?></textarea>
      <p><?php _e('This page is where you write, save your email messages. We can add HTML content.', 'email-newsletter'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'email-newsletter'); ?></label>
      <select name="eemail_status" id="eemail_status">
        <option value=''><?php _e('Select', 'email-newsletter'); ?></option>
		<option value='YES' <?php if($form['eemail_status']=='YES') { echo 'selected="selected"' ; } ?>>Yes</option>
        <option value='NO' <?php if($form['eemail_status']=='NO') { echo 'selected="selected"' ; } ?>>No</option>
      </select>
      <p><?php _e('Do you want to show this email in Send Mail admin pages?.', 'email-newsletter'); ?></p>
      <input name="eemail_id" id="eemail_id" type="hidden" value="">
      <input type="hidden" name="eemail_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button add-new-h2" value="<?php _e('Update Details', 'email-newsletter'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="<?php _e('Cancel', 'email-newsletter'); ?>" type="button" />
        <input name="Help" lang="publish" class="button add-new-h2" onclick="eemail_help()" value="<?php _e('Help', 'email-newsletter'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('eemail_form_edit'); ?>
    </form>
</div>
<p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>