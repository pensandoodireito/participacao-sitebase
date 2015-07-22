<?php
$eemail_abspath = dirname(__FILE__);
$eemail_abspath_1 = str_replace('wp-content/plugins/email-newsletter/unsubscribe', '', $eemail_abspath);
$eemail_abspath_1 = str_replace('wp-content\plugins\email-newsletter\unsubscribe', '', $eemail_abspath_1);
require_once($eemail_abspath_1 .'wp-config.php');
$blogname = get_option('blogname');
?>
<html>
<head>
<title><?php echo $blogname; ?></title>
</head>
<body>
<?php
$form['rand'] = isset($_GET['rand']) ? $_GET['rand'] : '';
$form['user'] = isset($_GET['user']) ? $_GET['user'] : '';
$form['reff'] = isset($_GET['reff']) ? $_GET['reff'] : '';

if ($form['rand'] == '' || $form['user'] == '' || $form['reff'] == '')
{
	$message = get_option('eemail_msgdis_6');
	$message = str_replace("\r\n", "<br />", $message);
	if($message == "")
	{
		$message = __('Oops.. Unexpected error occurred. Please try again.', 'email-newsletter');
	}
	echo $message;
	die;
}
else
{
	global $wpdb;
	$result = '0';
	$sSql = $wpdb->prepare("SELECT COUNT(*) AS count FROM ".WP_eemail_TABLE_SUB." WHERE eemail_id_sub = %d and eemail_email_sub = '%s' and eemail_status_sub = 'CON'",
		$form['rand'], $form['user']);
	$result = $wpdb->get_var($sSql);

	if ($result != '1')
	{
		$message = get_option('eemail_msgdis_4');
		$message = str_replace("\r\n", "<br />", $message);
		if($message == "")
		{
			$message = __('Oops.. We are getting some technical error. Please try again or contact admin.', 'email-newsletter');
		}
		echo $message;
	}
	else
	{
		  $sSql = $wpdb->prepare("UPDATE ".WP_eemail_TABLE_SUB."
				SET eemail_status_sub = 'UNS' WHERE eemail_id_sub = %d and eemail_email_sub = '%s' LIMIT 1",array($form['rand'], $form['user']));
			$wpdb->query($sSql);
			
			$message = get_option('eemail_msgdis_3');
			$message = str_replace("\r\n", "<br />", $message);
			if($message == "")
			{
				$message = __('You have been successfully unsubscribed.', 'email-newsletter');
			}
			echo $message;
	}
}
?>
</body>
</html>