<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" />
<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div id="icon-plugins" class="icon32"></div>
  <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
  <h3></h3>
  <div class="emailn-left">
    <div class="emailn-left-widgets">
      <div class="emailn-widgetsleft">
        <h3><?php _e('Welcome to Email Newsletter Plugin', 'email-newsletter'); ?></h3>
        <div class="emailn-widgetsleft-desc"> <strong><?php _e('Compose Mail', 'email-newsletter'); ?></strong>
          <p><?php _e('This is the first step to take with this plugin. Before sending mail to a user, you first need to compose the mail message, using this page. Once you have composed your mail, it will display automatically on the send mail page.', 'email-newsletter'); ?></p>
          <strong><?php _e('Send Mail to a Registered User', 'email-newsletter'); ?></strong>
          <p><?php _e('Use this page for sending mails to registered users. On this page you find the email addresses of all registered users listed with a check box option. If you dont want to mail any particular user(s), you can uncheck the email(s) on this list. After you selected the users, click on the email subject you first created and press the Send Mail button.', 'email-newsletter'); ?></p>
          <strong><?php _e('Send Mail to Commenters', 'email-newsletter'); ?></strong>
          <p><?php _e('Use this page for sending mails to commenters. On this page you find the email addresses of all commenters listed with a check box option. If you dont want to mail any particular commenter(s), you can uncheck the email(s) on this list. After you selected the commenters, click on the email subject you first created and press the Send Mail button.', 'email-newsletter'); ?></p>
          <strong><?php _e('Send Mail to Subscribed Users', 'email-newsletter'); ?></strong>
          <p><?php _e('This plugin offers a new option. Its a widget that you can drag to your sidebar. It serves as a subscribe option for your site visitors. Use this page to send mails to visitors who subscribed to your newsletter through the widget. You find them all listed with a check box option. If you dont want to mail any particular subscriber(s), you can uncheck the email(s) on this list. After you selected the subscribers, click on the email subject you first created and press the Send Mail button.', 'email-newsletter'); ?></p>
          <p><?php _e('You can see all subscribers on the View Subscriber page.', 'email-newsletter'); ?></p>
          <strong><?php _e('Send Mail to Users who Contacted You', 'email-newsletter'); ?></strong>
          <p><?php _e('This plugin now comes with a Simple Contact Form. I thought it would be useful to combine this feature with the newsletter plugin. After installing the contact form, your site visitors may contact you using this form. Those users then are visible to you on this page, and you can email them by using the same procedure as outlined before.', 'email-newsletter'); ?></p>
          <p><?php _e('Install the plugin Simple Contact Form on your site. If it is not useful to you, omit this menu.', 'email-newsletter'); ?></p>
          <strong><?php _e('Export Users to CSV', 'email-newsletter'); ?></strong>
          <p><?php _e('This is a new option that allows you to download all users into a formatted text file (CSV) for later import on another site of yours, or for using particular emails of it in your ordinary mail client, or for migrating your Wordpress site to another server.', 'email-newsletter'); ?></p>
          <strong><?php _e('Import Mails', 'email-newsletter'); ?></strong>
          <p><?php _e('This new option allows you to import mail addresses into your subscription list.', 'email-newsletter'); ?></p>
          <strong><?php _e('Setup Unsubscribe Link', 'email-newsletter'); ?></strong>
          <p><?php _e('This option is important for your mail receivers being able to unsubscribe from your newsletter. There will be a link in each newsletter that mail receivers can click for unsubscribing from your mailing list.', 'email-newsletter'); ?></p>
		  <strong><?php _e('Opt In Setting', 'email-newsletter'); ?></strong>
		  <p><?php _e('Opt-in is a term used in email marketing to confirm the email. Double Opt In, means subscribers need to confirm their email address by an activation link sent them on a activation email message. Single Opt In, means subscribers do not need to confirm their email address.', 'email-newsletter'); ?></p>
		  <p class="description"><?php echo WP_eemail_LINK; ?></p>
        </div>
      </div>
    </div>
  </div>
  <div class="emailn-right">
    <div class="emailn-widgets">
      <h3>Do you like this Plugin?</h3>
      <div class="emailn-widgets-desc"> This plugin is primarily developed, maintained, supported and documented by <a class="helplink" target="_blank" href='http://www.gopiplus.com/work/'>www.gopiplus.com</a> with a lot of love & effort. Any kind of contribution would be highly appreciated. Thanks!
        <ul>
          <li><a class="helplink" target="_blank" href='http://www.gopiplus.com/work/donation/'>Donate</a></li>
		  <li><a class="helplink" target="_blank" href='http://www.facebook.com/pages/Gopipluscom/197613796950429?sk=wall'>Facebook Like</a></li>
		  <li><a class="helplink" target="_blank" href='https://plus.google.com/103021440284242065651'>Google Plus</a></li>
        </ul>
      </div>
    </div>
    <div class="emailn-widgets">
      <h3>Ideas and support</h3>
      <div class="emailn-widgets-desc"> 
	  Please share your ideas for extra features and better documentations 
	  <a class="helplink" target="_blank" href='http://www.gopiplus.com/work/2010/09/25/email-newsletter/'>click here</a>
	  </div>
    </div>
    <div class="emailn-widgets">
      <h3>Options</h3>
      <div class="emailn-widgets-desc">
        Please use Widget or Short Code facility to add email subscription option.
      </div>
    </div>
    <div class="emailn-widgets">
      <h3>About Plugin</h3>
      <div class="emailn-widgets-desc"> 
	  <a class="helplink" target="_blank" href='http://www.gopiplus.com/work/'><img src="http://www.gopiplus.com/work/wp-content/themes/GTheme/images/gopiplus-logo.png" /></a>
        <ul>
          <li><a class="helplink" target="_blank" href='http://www.gopiplus.com/work/2010/09/25/email-newsletter/'>Click here</a> to read more help and documentation.</li>
          <li><a class="helplink" target="_blank" href='http://www.gopiplus.com/work/2010/09/25/email-newsletter/'>Click here</a> to download my other useful plugin.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
