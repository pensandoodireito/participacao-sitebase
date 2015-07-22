<?php
$Email = "";
$Email = isset($_POST['txt_email_newsletter']) ? $_POST['txt_email_newsletter'] : '';
$Email = trim($Email);

if($Email <> "")
{
	$regex = '/^[A-z0-9][\w.+-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
	$eemail_valid_email = preg_match($regex, $Email);
	if($eemail_valid_email)
	{
		$eemail_abspath = dirname(__FILE__);
		$eemail_abspath_1 = str_replace('wp-content/plugins/email-newsletter/widget', '', $eemail_abspath);
		$eemail_abspath_1 = str_replace('wp-content\plugins\email-newsletter\widget', '', $eemail_abspath_1);
		require_once($eemail_abspath_1 .'wp-config.php');
		global $wpdb, $wp_version;
		global $user_login , $user_email;
		
		$result = '0';
		$sSql = $wpdb->prepare(
			"SELECT COUNT(*) AS `count` FROM ".WP_eemail_TABLE_SUB."
			WHERE `eemail_email_sub` = %s", $Email);
		$result = $wpdb->get_var($sSql);
		
		if ($result == '0')
		{
			$eemail_opt_option = get_option('eemail_opt_option');
			if($eemail_opt_option == "double-optin")
			{
				$doubleoptin = "PEN";
			}
			else
			{
				$doubleoptin = "SIG";
			}
			
			$CurrentDate = date('Y-m-d G:i:s'); 
			$sql = $wpdb->prepare(
				"INSERT INTO `". WP_eemail_TABLE_SUB ."`
				(`eemail_name_sub`,`eemail_email_sub`, `eemail_status_sub`, `eemail_date_sub`)
				VALUES(%s, %s, %s, %s)",
				array('NA', $Email, $doubleoptin, $CurrentDate)
			);
			$wpdb->query($sql);
			
			$eemail_admin_email_option =  strtoupper(get_option('eemail_admin_email_option'));
			$eemail_user_email_option = strtoupper(get_option('eemail_user_email_option'));
			$eemail_admin_email_address = get_option('eemail_admin_email_address');
			$eemail_from_name = get_option('eemail_from_name');
			$eemail_from_email = get_option('eemail_from_email');
			
			if($eemail_admin_email_address == "")
			{
				get_currentuserinfo();
				$eemail_admin_email_address = $user_email;
			}
				
			if($eemail_from_name == "" || $eemail_from_email == "")
			{
				get_currentuserinfo();
				$eemail_from_name = $user_login;
				$eemail_from_email = $user_email;
			}
			
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
			$headers .= "From: \"$eemail_from_name\" <$eemail_from_email>\n";
			
			if(trim($eemail_admin_email_option) == "YES")
			{
				$to_email = $eemail_admin_email_address;
				$to_subject = get_option('eemail_admin_email_subject');
				$to_message = get_option('eemail_admin_email_content');
				$to_message = str_replace("\r\n", "<br />", $to_message);
				$to_message = str_replace("##USEREMAIL##", $Email, $to_message);
				@wp_mail($to_email, $to_subject, $to_message, $headers);
			}
			if($doubleoptin == "PEN")
			{
				$to_email = $Email;
				$eemail_opt_guid = eemail_opt_guid();
				$to_subject = get_option('eemail_opt_subject');
				$to_message = get_option('eemail_opt_content');
				$eemail_opt_link = get_option('eemail_opt_link');
				
				$sSql = $wpdb->prepare("SELECT * FROM ".WP_eemail_TABLE_SUB." WHERE eemail_email_sub = '%s' LIMIT 1	", array($to_email));
				$data = array();
				$data = $wpdb->get_row($sSql, ARRAY_A);
				$emaildbid = 0;
				if(count($data) > 0)
				{
					$emaildbid = $data['eemail_id_sub'];
				}
				
				$eemail_opt_rand = str_replace("##rand##", $emaildbid, $eemail_opt_link);
				$eemail_opt_user = str_replace("##user##", $to_email, $eemail_opt_rand);
				$eemail_opt_link = str_replace("##guid##", $eemail_opt_guid, $eemail_opt_user);
				$to_message = str_replace('##LINK##', $eemail_opt_link, $to_message);		
				$to_message = str_replace("\r\n", "<br />", $to_message);
				
				@wp_mail($to_email, $to_subject, $to_message, $headers);
				echo "subscribed-pending-doubleoptin";
			}
			else
			{
				if(trim($eemail_user_email_option) == "YES")
				{
					$to_email = $Email;
					$to_subject = get_option('eemail_user_email_subject');
					$to_message = get_option('eemail_user_email_content');
					$to_message = str_replace("\r\n", "<br />", $to_message);
					@wp_mail($to_email, $to_subject, $to_message, $headers);
				}
				echo "subscribed-successfully";
			}
			
		}
		else
		{
			echo "already-exist";
		}
	}
	else
	{
		echo "invalid-email";
	}
}
else
{
	echo "unexpected-error";
}

function eemail_opt_guid() 
{
	$random_id_length = 60; 
	$rnd_id = crypt(uniqid(rand(),1)); 
	$rnd_id = strip_tags(stripslashes($rnd_id)); 
	$rnd_id = str_replace(".","",$rnd_id); 
	$rnd_id = strrev(str_replace("/","",$rnd_id)); 
	$rnd_id = strrev(str_replace("$","",$rnd_id)); 
	$rnd_id = strrev(str_replace("#","",$rnd_id)); 
	$rnd_id = strrev(str_replace("@","",$rnd_id)); 
	$rnd_id = substr($rnd_id,0,$random_id_length); 
	$rnd_id = strtolower($rnd_id);
	return $rnd_id;
}	
?>