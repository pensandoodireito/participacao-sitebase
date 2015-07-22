<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<h3><?php _e('Steps to Send Email', 'email-newsletter'); ?></h3>
<ol>
  <li><?php _e('Select email address from the list.', 'email-newsletter'); ?></li>
  <li><?php _e('Select available email subject.', 'email-newsletter'); ?></li>
  <li><?php _e('Click send email button.', 'email-newsletter'); ?></li>
</ol>
