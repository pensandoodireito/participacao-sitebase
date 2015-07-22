<?php
$eemail_abspath = dirname(__FILE__);
$eemail_abspath_1 = str_replace('wp-content/plugins/email-newsletter/double-optin', '', $eemail_abspath);
$eemail_abspath_1 = str_replace('wp-content\plugins\email-newsletter\double-optin', '', $eemail_abspath_1);
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
$form['guid'] = isset($_GET['guid']) ? $_GET['guid'] : '';

if ($form['rand'] == '' || $form['user'] == '' || $form['guid'] == '')
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
	$sSql = $wpdb->prepare("SELECT COUNT(*) AS count FROM ".WP_eemail_TABLE_SUB."
		WHERE `eemail_id_sub` = %d and eemail_email_sub = '%s' and eemail_status_sub='PEN'", $form['rand'], $form['user']);
	$result = $wpdb->get_var($sSql);

	if ($result != '1')
	{
		$message = get_option('eemail_msgdis_2');
		$message = str_replace("\r\n", "<br />", $message);
		if($message == "")
		{
			$message = __('Oops.. Your email address is not in our newsletter list. Please try again.', 'email-newsletter');
		}
		echo $message;
	}
	else
	{
		$sSql = $wpdb->prepare(
			"UPDATE ".WP_eemail_TABLE_SUB."
			SET eemail_status_sub = 'CON'
			WHERE eemail_id_sub = %d and eemail_email_sub = '%s'
			LIMIT 1",
			array($form['rand'], $form['user']));
		$wpdb->query($sSql);
		
		if(trim($eemail_user_email_option) == "YES")
		{
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
			$headers .= "From: \"$eemail_from_name\" <$eemail_from_email>\n";
		
			$to_email = $form['user'];
			$to_subject = get_option('eemail_user_email_subject');
			$to_message = get_option('eemail_user_email_content');
			$to_message = str_replace("\r\n", "<br />", $to_message);
			@wp_mail($to_email, $to_subject, $to_message, $headers);
		}
		
		$message = get_option('eemail_msgdis_1');
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