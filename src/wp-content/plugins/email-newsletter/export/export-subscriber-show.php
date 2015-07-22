<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/export/export-setting.js"></script>
<?php
if (!session_id())
{
    session_start();
}

$_SESSION['exportcsv'] = "YES"; 

$cnt_subscriber = 0;
$cnt_users = 0;
$cnt_comment_author = 0;
$cnt_subscriber = $wpdb->get_var("select count(DISTINCT eemail_email_sub) from " . WP_eemail_TABLE_SUB);
$cnt_users = $wpdb->get_var("select count(DISTINCT user_email) from ". $wpdb->prefix . "users");
$cnt_comment_author = $wpdb->get_var("SELECT count(DISTINCT comment_author_email) from ". $wpdb->prefix . "comments WHERE comment_author_email <> ''");
if(strtoupper($wpdb->get_var("show tables like '". WP_eemail_TABLE_SCF . "'")) == strtoupper(WP_eemail_TABLE_SCF))  
{
	$cnt_contact_form = $wpdb->get_var("select count(DISTINCT gCF_email) from " . WP_eemail_TABLE_SCF);
}
else
{
	$cnt_contact_form = "NA";
}
?>

<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div id="icon-plugins" class="icon32"></div>
  <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
  <h3><?php _e('Export email address in csv format', 'email-newsletter'); ?></h3>
  <div class="tool-box">
  <form name="frm_emailnewsletter" method="post">
  <table width="100%" class="widefat" id="straymanage">
    <thead>
      <tr>
        <th width="3%" class="check-column" scope="col"><input type="checkbox" name="eemail_group_item[]" /></th>
        <th width="5%" scope="col"><?php _e('Sno', 'email-newsletter'); ?></th>
        <th scope="col"><?php _e('Export option', 'email-newsletter'); ?></th>
		<th width="10%" scope="col"><?php _e('Total email', 'email-newsletter'); ?></th>
        <th width="10%" scope="col"><?php _e('Action', 'email-newsletter'); ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th width="3%" class="check-column" scope="col"><input type="checkbox" name="eemail_group_item[]" /></th>
        <th width="5%" scope="col"><?php _e('Sno', 'email-newsletter'); ?></th>
        <th scope="col"><?php _e('Export option', 'email-newsletter'); ?></th>
		<th width="10%" scope="col"><?php _e('Total email', 'email-newsletter'); ?></th>
        <th width="10%" scope="col"><?php _e('Action', 'email-newsletter'); ?></th>
      </tr>
    </tfoot>
    <tbody>
      <tr>
        <td><input type="checkbox" value="" name="eemail_group_item[]"></td>
        <td>1</td>
        <td><?php _e('Subscriber email address', 'email-newsletter'); ?></td>
		<td><?php echo $cnt_subscriber; ?></td>
        <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('export/export-setting.php'); ?>', 'view_subscriber')" href="javascript:void(0);"><?php _e('Click to export csv', 'email-newsletter'); ?></a> </td>
      </tr>
      <tr class="alternate">
        <td><input type="checkbox" value="" name="eemail_group_item[]"></td>
        <td>2</td>
        <td><?php _e('Registered email address', 'email-newsletter'); ?></td>
		<td><?php echo $cnt_users; ?></td>
        <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('export/export-setting.php'); ?>', 'registered_user')" href="javascript:void(0);"><?php _e('Click to export csv', 'email-newsletter'); ?></a> </td>
      </tr>
      <tr>
        <td><input type="checkbox" value="" name="eemail_group_item[]"></td>
        <td>3</td>
        <td><?php _e('Comments author email address', 'email-newsletter'); ?></td>
		<td><?php echo $cnt_comment_author; ?></td>
        <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('export/export-setting.php'); ?>', 'commentposed_user')" href="javascript:void(0);"><?php _e('Click to export csv', 'email-newsletter'); ?></a> </td>
      </tr>
      <tr class="alternate">
        <td><input type="checkbox" value="" name="eemail_group_item[]"></td>
        <td>4</td>
        <td><?php _e('Contact form email address', 'email-newsletter'); ?></td>
		<td><?php echo $cnt_contact_form; ?></td>
        <td>
		<?php if($cnt_contact_form <> 'NA') { ?>
		<a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('export/export-setting.php'); ?>', 'contact_user')" href="javascript:void(0);"><?php _e('Click to export csv', 'email-newsletter'); ?></a> 
		<?php } else { echo $cnt_contact_form; } ?>
		</td>
      </tr>
    </tbody>
  </table>
  </form>
  <div class="tablenav">
	  <h2>
		<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Add Email', 'email-newsletter'); ?></a> 
		<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Import Email', 'email-newsletter'); ?></a> 
		<a class="button add-new-h2" target="_blank" href="<?php echo WP_eemail_FAV; ?>"><?php _e('Help', 'email-newsletter'); ?></a>
	  </h2>
  </div>
  <div style="height:10px;"></div>
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
  </div>
</div>
