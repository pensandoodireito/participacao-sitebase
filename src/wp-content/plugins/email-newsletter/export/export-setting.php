<?php
if (!session_id())
{
    session_start();
}

$eemail_abspath = dirname(__FILE__);
$eemail_abspath_1 = str_replace('wp-content/plugins/email-newsletter/export', '', $eemail_abspath);
$eemail_abspath_1 = str_replace('wp-content\plugins\email-newsletter\export', '', $eemail_abspath_1);
require_once($eemail_abspath_1 .'wp-config.php');
		
if ($_SESSION['exportcsv'] == "YES")
{
	if($_SERVER['REQUEST_METHOD'] == "POST") 
	{
		if (strpos($_SERVER['HTTP_REFERER'], get_option('siteurl')) !== false) 
		{
			$option = isset($_REQUEST['option']) ? $_REQUEST['option'] : '';
			switch ($option) 
			{
				case "view_subscriber":
					$data = $wpdb->get_results("select eemail_email_sub as 'Subscriber Email', eemail_date_sub as 'Date' from ".WP_eemail_TABLE_SUB." where 1=1 ORDER BY eemail_date_sub");
					download($data, 's', '');
					break;
				case "registered_user":
					$data = $wpdb->get_results("select user_nicename as 'Name', user_email as 'Email' from ". $wpdb->prefix . "users ORDER BY user_nicename");
					download($data, 'r', '');
					break;
				case "commentposed_user":
					$data = $wpdb->get_results("SELECT DISTINCT(comment_author_email) as Email, comment_author as 'Comment Author'  FROM ". $wpdb->prefix . "comments WHERE comment_author_email <> '' ORDER BY comment_author_email");
					download($data, 'c', '');
					break;
				case "contact_user":
					$data = $wpdb->get_results("select distinct gCF_email as Email, gCF_name as Name from ".WP_eemail_TABLE_SCF." ORDER BY gCF_email");
					download($data, 'cc', '');
					break;
				default:
					_e('Unexpected url submit has been detected 4', 'email-newsletter');
					break;
			}
		}
		else
		{
			_e('Unexpected url submit has been detected 1', 'email-newsletter');
		}
	}
	else
	{
		_e('Unexpected url submit has been detected 2', 'email-newsletter');
	}
}
else
{
	_e('Unexpected url submit has been detected 3', 'email-newsletter');
}

function download($arrays, $filename = 'output.csv', $option) 
{
	$string = '';
	$c=0;
	$filename = 'EmailNewsletter_'.$option.'_'.date('Ymd_His').".csv";
	foreach($arrays AS $array) 
	{
		$val_array = array();
		$key_array = array();
		foreach($array AS $key => $val) 
		{
			$key_array[] = $key;
			$val = str_replace('"', '""', $val);
			$val_array[] = "\"$val\"";
		}
		if($c == 0) 
		{
			$string .= implode(",", $key_array)."\n";
		}
		$string .= implode(",", $val_array)."\n";
		$c++;
	}
	ob_clean();
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename='.$filename);
    echo $string;
}
?>