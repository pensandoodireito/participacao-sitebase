<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';
$search = isset($_GET['search']) ? $_GET['search'] : 'A,B,C';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE_SUB."
	WHERE `eemail_id_sub` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'email-newsletter'); ?></strong></p></div><?php
}
else
{
	$eemail_errors = array();
	$eemail_success = '';
	$eemail_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".WP_eemail_TABLE_SUB."`
		WHERE `eemail_id_sub` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'eemail_name_sub' => $data['eemail_name_sub'],
		'eemail_email_sub' => $data['eemail_email_sub'],
		'eemail_status_sub' => $data['eemail_status_sub'],
		'eemail_date_sub' => $data['eemail_date_sub']
	);
}
// Form submitted, check the data
if (isset($_POST['eemail_form_submit']) && $_POST['eemail_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('eemail_form_edit');
	
	$form['eemail_email_sub'] = isset($_POST['eemail_email_sub']) ? $_POST['eemail_email_sub'] : '';
	if ($form['eemail_email_sub'] == '')
	{
		$eemail_errors[] = __('Please enter email address.', 'email-newsletter');
		$eemail_error_found = TRUE;
	}

	$form['eemail_name_sub'] = isset($_POST['eemail_name_sub']) ? $_POST['eemail_name_sub'] : '';
	$form['eemail_status_sub'] = isset($_POST['eemail_status_sub']) ? $_POST['eemail_status_sub'] : '';

	//	No errors found, we can add this Group to the table
	if ($eemail_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".WP_eemail_TABLE_SUB."`
				SET `eemail_email_sub` = %s,
				`eemail_name_sub` = %s,
				`eemail_status_sub` = %s
				WHERE eemail_id_sub = %d
				LIMIT 1",
				array($form['eemail_email_sub'], $form['eemail_name_sub'], $form['eemail_status_sub'], $did)
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
    <p><strong><?php echo $eemail_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&search=<?php echo $search; ?>"><?php _e('Click here', 'email-newsletter'); ?></a> <?php _e(' to view the details', 'email-newsletter'); ?></strong></p>
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
	<form name="eemail_form" method="post" action="#" onsubmit="return _eemail_submit()"  >
      <h3><?php _e('Edit email', 'email-newsletter'); ?></h3>
	  <label for="tag-image"><?php _e('Enter email address.', 'email-newsletter'); ?></label>
      <input name="eemail_email_sub" type="text" id="eemail_email_sub" value="<?php echo esc_html(stripslashes($form['eemail_email_sub'])); ?>" size="50" />
      <p><?php _e('Please enter email address.', 'email-newsletter'); ?></p>
	  <label for="tag-image"><?php _e('Enter name.', 'email-newsletter'); ?></label>
      <input name="eemail_name_sub" type="text" id="eemail_name_sub" value="<?php echo esc_html(stripslashes($form['eemail_name_sub'])); ?>" size="50" />
      <p><?php _e('Please enter email name.', 'email-newsletter'); ?></p>
      <label for="tag-display-status"><?php _e('Status', 'email-newsletter'); ?></label>
      <select name="eemail_status_sub" id="eemail_status_sub">
        <option value=''><?php _e('Select', 'email-newsletter'); ?></option>
		<option value='YES' <?php if(strtoupper($form['eemail_status_sub'])=='YES') { echo 'selected="selected"' ; } ?>>Old Email</option>
        <option value='SIG' <?php if(strtoupper($form['eemail_status_sub'])=='NO') { echo 'selected="selected"' ; } ?>>Single Opt In</option>
		<option value='PEN' <?php if(strtoupper($form['eemail_status_sub'])=='PEN') { echo 'selected="selected"' ; } ?>>Not confirmed</option>
		<option value='CON' <?php if(strtoupper($form['eemail_status_sub'])=='CON') { echo 'selected="selected"' ; } ?>>Confirmed</option>
		<option value='UNS' <?php if(strtoupper($form['eemail_status_sub'])=='UNS') { echo 'selected="selected"' ; } ?>>Unsubscribed</option>
      </select>
      <p><?php _e('Unsubscribed, Not confirmed emails not display in send mail page.', 'email-newsletter'); ?></p>
      <input name="eemail_id_sub" id="eemail_id_sub" type="hidden" value="">
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