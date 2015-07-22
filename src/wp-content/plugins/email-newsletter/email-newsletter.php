<?php
/*
Plugin Name: Email newsletter
Plugin URI: http://www.gopiplus.com/work/2010/09/25/email-newsletter/
Description: This easy-to-use plugin provides a simple way for Wordpress users to email registered users, commenters and subscribers. To place widget click <a href="widgets.php">here</a>.
Author: Gopi.R, tanaylakhani
Version: 20.12
Author URI: http://www.gopiplus.com
Donate link: http://www.gopiplus.com/work/2010/09/25/email-newsletter/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("WP_eemail_TABLE", $wpdb->prefix . "eemail_newsletter");
define("WP_eemail_TABLE_SUB", $wpdb->prefix . "eemail_newsletter_sub");
define("WP_eemail_TABLE_SCF", $wpdb->prefix . "gCF");
define("WP_eemail_TABLE_APP", $wpdb->prefix . "eemail_newsletter_app");

if ( ! defined( 'EMAIL_PLUGIN_BASENAME' ) )
    define( 'EMAIL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'EMAIL_PLUGIN_NAME' ) )
    define( 'EMAIL_PLUGIN_NAME', trim( dirname( EMAIL_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'EMAIL_PLUGIN_DIR' ) )
    define( 'EMAIL_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . EMAIL_PLUGIN_NAME );

if ( ! defined( 'EMAIL_PLUGIN_URL' ) )
    define( 'EMAIL_PLUGIN_URL', WP_PLUGIN_URL . '/' . EMAIL_PLUGIN_NAME );

function emailnews_plugin_path( $path = '' ) {
    return path_join( FIFO_PLUGIN_DIR, trim( $path, '/' ) );
}

function emailnews_plugin_url( $path = '' ) {
    return plugins_url( $path, EMAIL_PLUGIN_BASENAME );
}

define("WP_eemail_UNIQUE_NAME", "email-newsletter");
define("WP_eemail_TITLE", "Email Newsletter");
define('WP_eemail_LINK', 'Check official website for more information <a target="_blank" href="http://www.gopiplus.com/work/2010/09/25/email-newsletter/">click here</a>');
define('WP_eemail_FAV', 'http://www.gopiplus.com/work/2010/09/25/email-newsletter/');

if (!session_id()) { session_start(); }

function eemail_install() 
{
    global $wpdb, $wp_version;
    
    $admin_email = get_option('admin_email');
    
    add_option('eemail_title', "Email Newsletter");
    add_option('eemail_bcc', "0");
    add_option('eemail_widget_cap', "Sign up for our email newsletters");
    add_option('eemail_widget_txt_cap', "Enter email");
    add_option('eemail_widget_but_cap', "Submit");
    
    add_option('eemail_on_homepage', "YES");
    add_option('eemail_on_posts', "YES");
    add_option('eemail_on_pages', "YES");
    add_option('eemail_on_search', "NO");
    add_option('eemail_on_archives', "NO");
    
    add_option('my_plugin_do_activation_redirect', true);  

    add_option('eemail_from_name', "noreply");
    add_option('eemail_from_email', "noreply@mysitename.com");
    
    add_option('eemail_admin_email_option', "YES");
    add_option('eemail_admin_email_address', $admin_email);
    add_option('eemail_admin_email_subject', "New email subscription");
    add_option('eemail_admin_email_content', "Hi Admin, We have received a request to subscribe new email address (##USEREMAIL##) to receive emails from our website. Thank you.");
    add_option('eemail_user_email_option', "YES");
    add_option('eemail_user_email_subject', "Confirm subscription");
    add_option('eemail_user_email_content', "Hi User, We have received a request to subscribe this email address to receive newsletter from our website. Thank you.");
    add_option('eemail_email_type', "HTML");
    
    if(strtoupper($wpdb->get_var("show tables like '". WP_eemail_TABLE . "'")) != strtoupper(WP_eemail_TABLE))  
    {
        $wpdb->query("
            CREATE TABLE IF NOT EXISTS `". WP_eemail_TABLE . "` (
              `eemail_id` int(11) NOT NULL auto_increment,
              `eemail_subject` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
              `eemail_content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
              `eemail_status` char(3) NOT NULL default 'YES',
              `eemail_date` datetime NOT NULL default '0000-00-00 00:00:00',
              PRIMARY KEY  (`eemail_id`) )
            ");
        
        $sql = "insert into ".WP_eemail_TABLE.""
                    . " set `eemail_subject` = '" . 'Sample Subject'
                    . "', `eemail_content` = '" . 'This is sample mail content, Can add HTML content here.'
                    . "', `eemail_status` = '" . 'YES'
                    . "', `eemail_date` = CURDATE()";
                    
        $wpdb->get_results($sql);
        
        $Sample = '<strong style="color: #990000"> Email newsletter</strong><p>Email newsletter plugin have option to send HTML Mails/Newsletters to registered user,'; 
        $Sample .= ' Comment author, Subscriber and Users who contacted you. Sending email is much cheaper than most other forms of communication. Email marketing has proven very';
        $Sample .= ' successful for those who do it right. This plugin is very useful those who need to send Newsletters to users who subscribed to your blogs.</p>';
        $Sample .= ' <strong style="color: #990000">Advantage of this plugin</strong><ol>';
        $Sample .= ' <li>No coding knowledge required to setup this plugin.</li>';
        $Sample .= ' <li>Very easy installation and setup.</li><li>Option to send email newsletter to registered user.</li>';
        $Sample .= ' <li>Option to send email newsletter to commenter (Comment author).</li>';
        $Sample .= ' <li>Option to send email newsletter to users who contacted you.</li>';
        $Sample .= ' <li>Option to setup email subscription box and option to send email newsletter to subscriber.</li>';
        $Sample .= ' <li>Option to setup unsubscribe link in newsletter.</li><li>Option to Export and Import email address.</li>';
        $Sample .= ' <li>Automatic welcome email to new subscriber.</li><li>Admin email notification for every new subscriber.</li>';
        $Sample .= ' </ol><strong style="color: #990000">Thanks & Regards</strong><br>www.gopiplus.com';

        
        $sql = "insert into ".WP_eemail_TABLE.""
                    . " set `eemail_subject` = '" . 'Sample HTML Mail'
                    . "', `eemail_content` = '" . $Sample
                    . "', `eemail_status` = '" . 'YES'
                    . "', `eemail_date` = CURDATE()";
                    
        $wpdb->get_results($sql);
    }
    
    if(strtoupper($wpdb->get_var("show tables like '". WP_eemail_TABLE_SUB . "'")) != strtoupper(WP_eemail_TABLE_SUB))  
    {
        $wpdb->query("
            CREATE TABLE `". WP_eemail_TABLE_SUB . "` (
                `eemail_id_sub` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `eemail_name_sub` VARCHAR( 250 ) NOT NULL ,
                `eemail_email_sub` VARCHAR( 250 ) NOT NULL ,
                `eemail_status_sub` VARCHAR( 3 ) NOT NULL ,
                `eemail_date_sub` DATE NOT NULL )
            ");
    }
    
    $unsubscribelink = get_option('siteurl') . "/wp-content/plugins/email-newsletter/unsubscribe/unsubscribe.php?rand=##rand##&reff=##reff##&user=##user##";
    add_option('eemail_un_option', "Yes");
    add_option('eemail_un_text', "If you do not want to receive any more newsletters, Please <a href='##LINK##'>click here</a>");
    add_option('eemail_un_link', $unsubscribelink);
    
    $eemail_msgdis_1 = '<html><head><title>Email Newsletter</title></head><body style="background:#F2F2F2;font-family:Verdana, Arial, Helvetica, sans-serif;padding-top:65px;text-align:center;"><div style="background:#FFF;border:1px solid #ddd;border-radius:6px;max-width:580px;margin:0 auto;padding:34px 0 24px;width:580px"><div class="title"><h2>Thank You</h2><p>You have been successfully subscribed to our newsletter.</p></div></div></body></html>';
    $eemail_msgdis_3 = '<html><head><title>Email Newsletter</title></head><body style="background:#F2F2F2;font-family:Verdana, Arial, Helvetica, sans-serif;padding-top:65px;text-align:center;"><div style="background:#FFF;border:1px solid #ddd;border-radius:6px;max-width:580px;margin:0 auto;padding:34px 0 24px;width:580px"><div class="title"><h2>Thank You</h2><p>You have been successfully unsubscribed. You will no longer hear from us.</p></div></div></body></html>';
    
    $doubleoptinlink = get_option('siteurl') . "/wp-content/plugins/email-newsletter/double-optin/double-optin.php?rand=##rand##&guid=##guid##&user=##user##";
    add_option('eemail_opt_option', "double-optin");
    add_option('eemail_opt_subject', "Please confirm subscription (Email Newsletter)");
    add_option('eemail_opt_content', "A newsletter subscription request for this email address was received. Please confirm it by <a href='##LINK##'>clicking here</a>. If you cannot click the link, please use the following link. <br /><br /> ##LINK## <br /><br /> Thanks.");
    add_option('eemail_opt_link', $doubleoptinlink);
    
    add_option('eemail_msgdis_1', $eemail_msgdis_1);
    add_option('eemail_msgdis_2', "Oops.. This subscription cant be completed, sorry. The email address is blocked or already subscribed. Thank you.");
    add_option('eemail_msgdis_3', $eemail_msgdis_3);
    add_option('eemail_msgdis_4', "Oops.. We are getting some technical error. Please try again or contact admin.");
    add_option('eemail_msgdis_5', "Oops.. Unexpected error occurred. Please try again.");
    add_option('eemail_msgdis_6', "Oops.. Unexpected error occurred. Please try again.");
}

function eemail_admin_option() 
{
    global $wpdb;
    include_once('pages/welcome-page.php');
}

function add_admin_menu_email_general()
{
    global $wpdb;
    include_once('pages/welcome-page.php');

}

function eemail_deactivation() 
{
    // No action required.
}


function eemail_get_emailid() 
{
    global $wpdb;
    $arrData = array();
    $i=0;
    
    $sSql = "select eemail_id_sub, eemail_email_sub from " . WP_eemail_TABLE_SUB . " order by eemail_id_sub";
    $myData = $wpdb->get_results($sSql, ARRAY_A);
    if ( ! empty($myData) ) 
    {
        if( count($myData) > 0 )
        {
            foreach ($myData as $data)
            {
                $arrData[$i]["eemail_id_sub"] = $data["eemail_id_sub"];
                $arrData[$i]["eemail_email_sub"] = $data["eemail_email_sub"];
                $i=$i+1;
            }
        }
    }
    return $arrData;
}

function eemail_get_emails( $eemail_id ) 
{
    global $wpdb;
    $arrData = array();
    $sSql = "select eemail_subject, eemail_content from " . WP_eemail_TABLE . " where";
    $sSql = $sSql . " eemail_id = " . trim($eemail_id);
    $sSql = $sSql . " order by eemail_id limit 0, 1";
    $data = $wpdb->get_results($sSql);
    if ( ! empty($data) )
    {
        $data = $data[0];
        $arrData[0]["eemail_subject"] = stripslashes($data->eemail_subject);
        $arrData[0]["eemail_content"] = stripslashes($data->eemail_content);
    }
    else
    {
        $arrData[0]["eemail_subject"] = "NA";
        $arrData[0]["eemail_content"] = "NA";
    }
    return $arrData;
}

function eemail_send_mail($recipients = array(), $eemail_id = 0, $source = "") 
{
    global $wpdb;
    global $user_login , $user_email;
    
    $arrSubscriber = array();
    $num_sent = 0;
    $sender_name = "";
    $sender_name = "";
    $eemail_email_type = "";
    $eemail_un_text = "";
    $eemail_un_link = "";
    $eemail_un_option = "NO";
    $eemail_errors = array();
    $eemail_error_found =  FALSE;
    
    $sender_name = get_option('eemail_from_name');
    $sender_email = get_option('eemail_from_email');
    $eemail_email_type = get_option('eemail_email_type');
    $eemail_un_option = get_option('eemail_un_option');
    
    if($eemail_email_type == "")
    {
        $eemail_email_type = "HTML";
    }
    
    // Check emails from address and from name.
    if(trim($sender_name) == "" || trim($sender_email) == '')
    {
        get_currentuserinfo();
        $sender_name = $user_login;
        $sender_email = $user_email;
    }
    
    // Check recipients count.
    if(empty($recipients))
    {
        return $num_sent; 
    }
    
    // Check email content valid or not.
    if($eemail_id == 0)
    {
        return false;
    }

    $headers  = "From: \"$sender_name\" <$sender_email>\n";
    $headers .= "Return-Path: <" . $sender_email . ">\n";
    $headers .= "Reply-To: \"" . $sender_name . "\" <" . $sender_email . ">\n";
    $headers .= "X-Mailer: PHP" . phpversion() . "\n";
    
    // Load email subject and email newsletter details.
    $arrEmails = eemail_get_emails($eemail_id);
    if(count($arrEmails) > 0)
    {
        $form = array(
            'eemail_subject' => $arrEmails[0]['eemail_subject'],
            'eemail_content' => $arrEmails[0]['eemail_content']
        );
        
        $subject = $form['eemail_subject'];
        $message = $form['eemail_content'];
    }
    
    if($subject == "")
    {
        return false;
    }
    
    // Check unsubscribe option
    if( strtoupper($eemail_un_option) == "YES" )
    {
        $eemail_un_text = get_option('eemail_un_text');
        $eemail_un_link = get_option('eemail_un_link');
    }

    if( strtoupper($eemail_email_type) == "HTML" )  
    {
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: " . get_bloginfo('html_type') . "; charset=\"". get_bloginfo('charset') . "\"\n";
        $headers .= "Content-type: text/html\r\n"; 
        $mailtext = "<html><head><title>" . $subject . "</title></head><body>" . $message . "</body></html>";
    } 
    else 
    {
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: text/plain; charset=\"". get_bloginfo('charset') . "\"\n";
        $message = preg_replace('|&[^a][^m][^p].{0,3};|', '', $message);
        $message = preg_replace('|&amp;|', '&', $message);
        $mailtext = wordwrap(strip_tags($message), 80, "\n");
    }
    
    $mailtext = str_replace("\r\n", "<br />", $mailtext);
    
    if(count($recipients) > 0)
    {
        $emailaddress = "";
        $emaildbid = "";
        
        for ( $i = 0; $i < count($recipients); $i++) 
        {
            if($source == "subscriber")
            {
                $recipientsvalue = explode("<||>", $recipients[$i]);
                $recipientsvaluecount = count($recipientsvalue);
                if($recipientsvaluecount == 2)
                {
                    $emailaddress = $recipientsvalue[0];
                    $emaildbid = $recipientsvalue[1];
                }
                else
                {
                    $eemail_errors[] = __($emailaddress, 'email-newsletter');
                    $eemail_error_found = TRUE;
                }
            }
            else
            {
                $emailaddress = $recipients[$i];
                $emaildbid = "";
            }

            if ( !eemail_valid_email($emailaddress) ) 
            { 
                $eemail_errors[] = __($emailaddress, 'email-newsletter');
                $eemail_error_found = TRUE;
            }
            else
            {
                $unsubscribe = "";
                if($source == "subscriber")
                {
                    if( strtoupper($eemail_un_option) == "YES" )
                    {
                        $unsubscribemyguid = myguid();
                        if($emaildbid <> "0" && $emaildbid <> "")
                        {
                            $unsubscriberand = str_replace("##rand##", $emaildbid, $eemail_un_link);
                            $unsubscribeuser = str_replace("##user##", $emailaddress, $unsubscriberand);
                            $unsubscribelink = str_replace("##reff##", $unsubscribemyguid, $unsubscribeuser);
                            $unsubscribe = str_replace('##LINK##', $unsubscribelink, $eemail_un_text);
                        }
                        else
                        {
                            $unsubscribe = "";
                        }
                    }
                    else
                    {
                        $unsubscribe = "";
                    }
                    
                    if ( strtoupper($eemail_email_type) == "HTML" )
                    {
                        $unsubscribe = '<br>' . $unsubscribe;
                    }
                    else
                    {
                        $unsubscribe = '\n' . $unsubscribe;
                    }
                }

                @wp_mail($emailaddress, $subject, $mailtext . $unsubscribe, $headers);
                $num_sent = $num_sent + 1;
            }
        }
    }

    if($num_sent > 0) 
    { 
        ?>
        <?php _e('<div class="updated fade"><strong><p>Email has been sent successfully.</p></strong></div>', 'email-newsletter'); ?>
        <?php
    }

    if ($eemail_error_found == TRUE && isset($eemail_errors[0]) == TRUE)
    {
        $eemail_value = "";
        $value = "";
        $j = 0;
        foreach($eemail_errors as $value) 
        {
            if ($j % 4 == 0 && $j <> 0)
            {
                $eemail_value = $eemail_value . "<br>";
            }
            $eemail_value = $eemail_value . $value . ", ";

            $j = $j + 1;
        }
        ?>
        <div class="error fade"><p><strong><?php _e('Some invalid email address found.', 'email-newsletter'); ?></strong><br /><?php echo $eemail_value; ?></p></div>
        <?php
    }
    
    return $num_sent;
}

function myguid() 
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

function eemail_valid_email($email) 
{
   $regex = '/^[A-z0-9][\w.+-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
   return (preg_match($regex, $email));
}

function eemail_get_max_bcc_recipients() 
{
    return get_option( 'eemail_bcc' );
}

function eemail_get_email_content($eemail_id) 
{
    global $wpdb;
    $emailrecord = array();
    $data = $wpdb->get_results("select eemail_subject,eemail_content from ".WP_eemail_TABLE." where eemail_id=$eemail_id limit 1");
    if ( !empty($data) ) 
    {
        $data = $data[0];
        $emailrecord["eemail_subject"] = $data->eemail_subject;
        $emailrecord["eemail_content"] = $data->eemail_content;
    }
    return $emailrecord;
}

function eemail_show() 
{
    global $wpdb, $wp_version;
    include_once("widget/widget.php");
}

function eemail_widget($args) 
{
    if(is_home() && get_option('eemail_on_homepage') == 'YES') { $display = "show";    }
    if(is_single() && get_option('eemail_on_posts') == 'YES') {    $display = "show"; }
    if(is_page() && get_option('eemail_on_pages') == 'YES') { $display = "show"; }
    if(is_archive() && get_option('eemail_on_search') == 'YES') { $display = "show"; }
    if(is_search() && get_option('eemail_on_archives') == 'YES') { $display = "show"; }
    if($display == "show")
    {
        extract($args);
        echo $before_widget;
        echo $before_title;
        echo get_option('eemail_title');
        echo $after_title;
        eemail_show();
        echo $after_widget;
    }
}
add_shortcode( 'email-newsletter-plugin', 'eemail_form_shortcode' );

function eemail_form_shortcode( $atts ) 
{
    $ccf = "";
    //[email-newsletter-plugin]
    return eemail_show();
}
    
function eemail_control() 
{
    _e('Email Newsletter', 'email-newsletter');
}

function eemai_widget_init()
{
    if(function_exists('wp_register_sidebar_widget')) 
    {
        wp_register_sidebar_widget( __('Email Newsletter', 'email-newsletter'), __('Email Newsletter', 'email-newsletter'), 'eemail_widget');
    }
    
    if(function_exists('wp_register_widget_control')) 
    {
        wp_register_widget_control( __('Email Newsletter', 'email-newsletter') , array( __('Email Newsletter', 'email-newsletter') , 'widgets'), 'eemail_control');
    } 
}


function add_app_register_page(){
    global $wpdb;
    include_once('pages/app-page.php');
}

function add_admin_menu_email_compose() 
{
    global $wpdb;
    $current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
    switch($current_page)
    {
        case 'add':
            include('compose/compose-email-add.php');
            break;
        case 'edit':
            include('compose/compose-email-edit.php');
            break;
        default:
            include('compose/compose-email-show.php');
            break;
    }
}

function add_admin_menu_email_to_registered_user() 
{
    global $wpdb;
    include('sendemail/sendmail-registereduser.php');
}

function add_admin_menu_email_to_comment_posed_user() 
{
    global $wpdb;
    include('sendemail/sendmail-commenter.php');
}

function add_admin_menu_email_to_subscriber() 
{
    global $wpdb;
    include('sendemail/sendmail-subscriber.php');
}

function add_admin_menu_view_subscriber() 
{
    global $wpdb;
    $current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
    switch($current_page)
    {
        case 'add':
            include('subscriber/view-subscriber-add.php');
            break;
        case 'edit':
            include('subscriber/view-subscriber-edit.php');
            break;
        default:
            include('subscriber/view-subscriber-show.php');
            break;
    }
}

function add_admin_menu_widget_option() 
{
    global $wpdb;
    include('pages/widget-setting.php');
}

function add_admin_menu_email_option() 
{
    global $wpdb;
    include('pages/email-setting.php');
}

function add_admin_menu_email_to_simple_contact_form() 
{
    global $wpdb;
    include_once("sendemail/sendmail-contactform.php");
}

function add_admin_menu_export_csv() 
{
    global $wpdb;
    include('export/export-subscriber-show.php');
}

function add_admin_menu_import_emails() 
{
    global $wpdb;
    include('subscriber/view-subscriber-add.php');
}

function add_unsubscribe_option() 
{
    global $wpdb;
    include('pages/unsubscribe-setting.php');
}

function add_admin_menu_email_testemail() 
{
    global $wpdb;
    include('sendemail/sendmail-testing.php');
}

function add_admin_menu_opt_in() 
{
    global $wpdb;
    include('pages/optin-setting.php');
}
function add_readygraph_premium(){
	include('extension/readygraph/go-premium.php');
}

function ViewSubscriberResendEmail($did) 
{
    global $wpdb;
    $sSql = $wpdb->prepare("SELECT * FROM ".WP_eemail_TABLE_SUB." WHERE eemail_id_sub = %d LIMIT 1", array($did));
    $data = array();
    $data = $wpdb->get_row($sSql, ARRAY_A);
    if(count($data) > 0)
    {
        $arrFromEmail = array();
        $arrCheck = GetFromEmail();
        $eemail_from_name = $arrCheck[0]['eemail_from_name'];
        $eemail_from_email = $arrCheck[0]['eemail_from_email'];
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
        $headers .= "From: \"$eemail_from_name\" <$eemail_from_email>\n";
        
        $to_dbid = $data['eemail_id_sub'];
        $to_email = $data['eemail_email_sub'];
        
        $to_guid = myguid();
        $to_subject = get_option('eemail_opt_subject');
        $to_message = get_option('eemail_opt_content');
        $eemail_opt_link = get_option('eemail_opt_link');
    
        $eemail_opt_link = str_replace("##rand##", $to_dbid, $eemail_opt_link);
        $eemail_opt_link = str_replace("##user##", $to_email, $eemail_opt_link);
        $eemail_opt_link = str_replace("##guid##", $to_guid, $eemail_opt_link);
        $to_message = str_replace('##LINK##', $eemail_opt_link, $to_message);        
        $to_message = str_replace("\r\n", "<br />", $to_message);
        
        @wp_mail($to_email, $to_subject, $to_message, $headers);
        
        $sSql = $wpdb->prepare("UPDATE ".WP_eemail_TABLE_SUB." SET eemail_status_sub = 'PEN' WHERE eemail_id_sub = %d LIMIT 1",    array($to_dbid));
        $wpdb->query($sSql);
    }
}

function GetFromEmail() 
{
    global $wpdb;
    $arrData = array();
    $eemail_from_name = get_option('eemail_from_name');
    $eemail_from_email = get_option('eemail_from_email');
    if($eemail_from_name == "" || $eemail_from_email == "")
    {
        get_currentuserinfo();
        $eemail_from_name = $user_login;
        $eemail_from_email = $user_email;
    }
    $arrData[0]["eemail_from_name"] = $eemail_from_name;
    $arrData[0]["eemail_from_email"] = $eemail_from_email;
    return $arrData;
}

function add_admin_menu_option() 
{
    add_menu_page( __( 'Email Newsletter', 'email-newsletter' ), __( 'Email Newsletter', 'email-newsletter' ), 'admin_dashboard', 'email-newsletter', 'eemail_admin_option' );
    /*add_submenu_page('email-newsletter', 'Readygraph App', __( 'Readygraph App', 'email-newsletter' ), 'administrator', 'register-app', 'add_app_register_page');*/
    add_submenu_page('email-newsletter', 'General Information', __( 'General Information', 'email-newsletter' ), 'administrator', 'general-information', 'add_admin_menu_email_general');
    add_submenu_page('email-newsletter', 'Compose Mail', __( 'Compose Mail', 'email-newsletter' ), 'administrator', 'compose-email', 'add_admin_menu_email_compose');
    add_submenu_page('email-newsletter', 'Send Mail to a Registered User', __( 'Mail to Registered User', 'email-newsletter' ), 'administrator', 'sendmail-registereduser', 'add_admin_menu_email_to_registered_user');
    add_submenu_page('email-newsletter', 'Send Mail to Commenters', __( 'Mail to Commenter', 'email-newsletter' ), 'administrator', 'sendmail-commenter', 'add_admin_menu_email_to_comment_posed_user');
    add_submenu_page('email-newsletter', 'Send Mail to Subscribed Users', __( 'Mail to Subscriber', 'email-newsletter' ), 'administrator', 'sendmail-subscriber', 'add_admin_menu_email_to_subscriber');
    add_submenu_page('email-newsletter', 'Send Mail to Users who Contacted You', __( 'Mail to Contact Form User', 'email-newsletter' ), 'administrator', 'sendmail-contactform', 'add_admin_menu_email_to_simple_contact_form');
    add_submenu_page('email-newsletter', 'View subscribed user', __( 'View Subscriber', 'email-newsletter' ), 'administrator', 'view-subscriber', 'add_admin_menu_view_subscriber');
    add_submenu_page('email-newsletter', 'Widget setting', __( 'Setup Widget', 'email-newsletter' ), 'administrator', 'widget-setting', 'add_admin_menu_widget_option');
    add_submenu_page('email-newsletter', 'Email setting', __( 'Setup Email', 'email-newsletter' ), 'administrator', 'email-setting', 'add_admin_menu_email_option');
    add_submenu_page('email-newsletter', 'Unsubscribe link option', __( 'Setup Unsubscribe', 'email-newsletter' ), 'administrator', 'unsubscribe-setting', 'add_unsubscribe_option');
    add_submenu_page('email-newsletter', 'Opt In Setting', __( 'Opt In Setting', 'email-newsletter' ), 'administrator', 'opt-in', 'add_admin_menu_opt_in');
    add_submenu_page('email-newsletter', 'Export CSV', __( 'Export Users to CSV', 'email-newsletter' ), 'administrator', 'export-subscriber', 'add_admin_menu_export_csv');
    add_submenu_page('email-newsletter', 'Import emails', __( 'Import Mails', 'email-newsletter' ), 'administrator', 'import-subscriber', 'add_admin_menu_import_emails');
    add_submenu_page('email-newsletter', 'Send Test Mail', __( 'Send Test Mail', 'email-newsletter' ), 'administrator', 'test-email', 'add_admin_menu_email_testemail');
	add_submenu_page('email-newsletter', 'Go Premium', __( 'Go Premium', 'email-newsletter' ), 'administrator', 'readygraph-go-premium', 'add_readygraph_premium');
}

function eemail_textdomain() 
{
      load_plugin_textdomain( 'email-newsletter' , false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/*function on_plugin_activated_redirect(){
    $setting_url="admin.php?page=register-app";    
    if (get_option('my_plugin_do_activation_redirect', false)) {  
        delete_option('my_plugin_do_activation_redirect'); 
        wp_redirect(admin_url($setting_url)); 
    }  
}
*/
add_action('plugins_loaded', 'eemail_textdomain');
add_action('admin_menu', 'add_admin_menu_option');
register_activation_hook(__FILE__, 'eemail_install');
register_deactivation_hook(__FILE__, 'eemail_deactivation');
//add_action('admin_init', 'on_plugin_activated_redirect');  
add_action("plugins_loaded", "eemai_widget_init");
add_action('init', 'eemai_widget_init');
if( file_exists(plugin_dir_path( __FILE__ ).'/readygraph-extension.php' )) {
include "readygraph-extension.php";
}
else{
}
function ee_rrmdir($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") 
           ee_rrmdir($dir."/".$object); 
        else unlink   ($dir."/".$object);
      }
    }
    reset($objects);
    rmdir($dir);
  }
  $del_url = plugin_dir_path( __FILE__ );
  unlink($del_url.'/readygraph-extension.php');
 $setting_url="admin.php?page=general-information";
  echo'<script> window.location="'.admin_url($setting_url).'"; </script> ';
}
function ee_delete_rg_options() {
delete_option('readygraph_access_token');
delete_option('readygraph_application_id');
delete_option('readygraph_refresh_token');
delete_option('readygraph_email');
delete_option('readygraph_settings');
delete_option('readygraph_delay');
delete_option('readygraph_enable_sidebar');
delete_option('readygraph_auto_select_all');
delete_option('readygraph_enable_notification');
delete_option('readygraph_enable_branding');
delete_option('readygraph_send_blog_updates');
delete_option('readygraph_send_real_time_post_updates');
delete_option('readygraph_popup_template');
}
?>