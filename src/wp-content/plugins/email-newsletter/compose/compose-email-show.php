<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_eemail_display']) && $_POST['frm_eemail_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$eemail_success = '';
	$eemail_success_msg = FALSE;
	
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
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('eemail_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_eemail_TABLE."`
					WHERE `eemail_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$eemail_success_msg = TRUE;
			$eemail_success = __('Selected record was successfully deleted.', 'email-newsletter');
		}
	}
	
	if ($eemail_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $eemail_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div id="icon-plugins" class="icon32"></div>
    <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
	<h3><?php _e('Compose email', 'email-newsletter'); ?>  <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=compose-email&amp;ac=add"><?php _e('Add New', 'email-newsletter'); ?></a></h3>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_eemail_TABLE."` order by eemail_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/compose/compose-email-setting.js"></script>
		<form name="frm_eemail_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="3%" class="check-column" scope="col"><input type="checkbox" name="eemail_group_item[]" /></th>
			<th scope="col"><?php _e('Email subject', 'email-newsletter'); ?></th>
            <th scope="col"><?php _e('Status', 'email-newsletter'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="eemail_group_item[]" /></th>
			<th scope="col"><?php _e('Email subject', 'email-newsletter'); ?></th>
            <th scope="col"><?php _e('Status', 'email-newsletter'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			$displayisthere = FALSE;
			if(count($myData) > 0)
			{
				$i = 1;
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['eemail_id']; ?>" name="eemail_group_item[]"></td>
					  <td><?php echo esc_html(stripslashes($data['eemail_subject'])); ?>
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=compose-email&amp;ac=edit&amp;did=<?php echo $data['eemail_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:_eemail_delete('<?php echo $data['eemail_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
						</div>
					  </td>
						<td><?php echo $data['eemail_status']; ?></td>
					</tr>
					<?php
					$i = $i+1;
				}
			}
			else
			{
				?><tr><td colspan="3" align="center"><?php _e('No records available.', 'email-newsletter'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('eemail_form_show'); ?>
		<input type="hidden" name="frm_eemail_display" value="yes"/>
      </form>	
	  <div class="tablenav">
		  <h2>
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=compose-email&amp;ac=add"><?php _e('Compose New Email', 'email-newsletter'); ?></a>
			<a class="button add-new-h2" target="_blank" href="<?php echo WP_eemail_FAV; ?>"><?php _e('Help', 'email-newsletter'); ?></a>
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Add Email', 'email-newsletter'); ?></a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Import Email', 'email-newsletter'); ?></a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=export-subscriber"><?php _e('Export Email (CSV)', 'email-newsletter'); ?></a> 
		  </h2>
	  </div>
	  <div style="height:10px;"></div>
	  <p class="description"><?php echo WP_eemail_LINK; ?></p>
	</div>
</div>