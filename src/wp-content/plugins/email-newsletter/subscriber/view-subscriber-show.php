<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
$search = isset($_GET['search']) ? $_GET['search'] : 'A,B,C';
if (isset($_POST['frm_eemail_display']) && $_POST['frm_eemail_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$eemail_success = '';
	$eemail_success_msg = FALSE;
	if (isset($_POST['frm_eemail_bulkaction']) && $_POST['frm_eemail_bulkaction'] != 'delete' && $_POST['frm_eemail_bulkaction'] != 'resend')
	{
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
			?>
			<div class="error fade">
			  <p><strong><?php _e('Oops, selected details doesnt exist (1).', 'email-newsletter'); ?></strong></p>
			</div>
			<?php
		}
		else
		{
			// Form submitted, check the action
			if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
			{
				//	Just security thingy that wordpress offers us
				check_admin_referer('eemail_form_show');
				
				//	Delete selected record from the table
				$sSql = $wpdb->prepare("DELETE FROM `".WP_eemail_TABLE_SUB."`
						WHERE `eemail_id_sub` = %d
						LIMIT 1", $did);
				$wpdb->query($sSql);
				
				//	Set success message
				$eemail_success_msg = TRUE;
				$eemail_success = __('Selected record was successfully deleted.', 'email-newsletter');
			}
			
			if (isset($_GET['ac']) && $_GET['ac'] == 'resend' && isset($_GET['did']) && $_GET['did'] != '')
			{
				$did = isset($_GET['did']) ? $_GET['did'] : '0';
				ViewSubscriberResendEmail($did);
				$eemail_success_msg = TRUE;
				$eemail_success  = __('Confirmation email resent successfully.', 'email-newsletter');
			}
		}
	}
	else
	{
		check_admin_referer('eemail_form_show');
		
		if (isset($_POST['frm_eemail_bulkaction']) && $_POST['frm_eemail_bulkaction'] == 'delete')
		{
			$chk_delete = $_POST['chk_delete'];
			if(!empty($chk_delete))
			{			
				$count = count($chk_delete);
				for($i=0; $i<$count; $i++)
				{
					$del_id = $chk_delete[$i];
					$sql = "delete FROM ".WP_eemail_TABLE_SUB." WHERE eemail_id_sub=".$del_id." Limit 1";
					$wpdb->get_results($sql);
				}
				
				//	Set success message
				$eemail_success_msg = TRUE;
				$eemail_success = __($count . ' Selected record was successfully deleted.', 'email-newsletter');
			}
			else
			{
				?>
				<div class="error fade">
				  <p><strong><?php _e('Oops, No record was selected.', 'email-newsletter'); ?></strong></p>
				</div>
				<?php
			}
		}
		elseif (isset($_POST['frm_eemail_bulkaction']) && $_POST['frm_eemail_bulkaction'] == 'resend')
		{
			$chk_delete = $_POST['chk_delete'];
			if(!empty($chk_delete))
			{			
				$count = count($chk_delete);
				for($i=0; $i<$count; $i++)
				{
					$del_id = $chk_delete[$i];
					ViewSubscriberResendEmail($del_id);
					$eemail_success  = __('Confirmation email resent successfully.', 'email-newsletter');
				}
				
				//	Set success message
				$eemail_success_msg = TRUE;
				$eemail_success = __($count . ' Confirmation emails resent successfully.', 'email-newsletter');
			}
			else
			{
				?>
				<div class="error fade">
				  <p><strong><?php _e('Oops, No record was selected.', 'email-newsletter'); ?></strong></p>
				</div>
				<?php
			}
		}
	}
	
	if ($eemail_success_msg == TRUE)
	{
		?>
		<div class="updated fade">
		  <p><strong><?php echo $eemail_success; ?></strong></p>
		</div>
		<?php
	}
}
?>
<script language="javaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/subscriber/subscriber-setting.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" />
<div class="wrap">
<?php wp_enqueue_style('ee_rg_admin_template', plugins_url() ."/email-newsletter/extension/readygraph/assets/css/upgrade.css");

echo '<div class="rg_info rg_message"><img src="'.plugins_url() .'/email-newsletter/extension/readygraph/assets/Sign-Alert-icon.png" style="float: left;height: 50px;padding-right: 10px;"><a href="admin.php?page=readygraph-app"><button class="button-warning pure-button" style="float: right; margin-right: 15px;">Connect ReadyGraph</button></a><h3 style="color:white">Grow your site traffic faster: Activate Email Newsletter\'s User Growth Engine (ReadyGraph)</h3><p style="color: whitesmoke">Promotion to New Users | Viral Signup Form | Site Update emails | Import Existing Users</p></div>'; ?>
  <div id="icon-plugins" class="icon32"></div>
  <h2><?php _e(WP_eemail_TITLE, 'email-newsletter'); ?></h2>
  <h3><?php _e('View subscriber', 'email-newsletter'); ?> <a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Add New', 'email-newsletter'); ?></a></h3>
  <div class="tool-box">
    <?php
		$sSql = "SELECT * FROM `".WP_eemail_TABLE_SUB."` where 1=1";
		if($search <> "")
		{
			$array = explode(',', $search);
			$length = count($array);
			for ($i = 0; $i < $length; $i++) 
			{
				if(@$i == 0)
				{
					$sSql = $sSql . " and";
				}
				else
				{
					$sSql = $sSql . " or";
				}
				$sSql = $sSql . " eemail_email_sub LIKE '" . $array[$i]. "%'";
			}
		}
		$sSql = $sSql . " ORDER BY eemail_email_sub";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
	<div class="tablenav">
		<span style="text-align:left;">
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=A,B,C">A,B,C</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=D,E,F">D,E,F</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=G,H,I">G,H,I</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=J,K,L">J,K,L</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=M,N,O">M,N,O</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=P,Q,R">P,Q,R</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=S,T,U">S,T,U</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=V,W,X,Y,Z">V,W,X,Y,Z</a>&nbsp;&nbsp; 
			<a class="button add-new-h2" href="admin.php?page=view-subscriber&search=0,1,2,3,4,5,6,7,8,9">0-9</a> 
		<span>
		<span style="float:right;">
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Add Email', 'email-newsletter'); ?></a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Import Email', 'email-newsletter'); ?></a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=export-subscriber"><?php _e('Export Email (CSV)', 'email-newsletter'); ?></a> 
			<a class="button add-new-h2" target="_blank" href="<?php echo WP_eemail_FAV; ?>"><?php _e('Help', 'email-newsletter'); ?></a> 
		</span>
    </div>
    <form name="frm_eemail_display" method="post" onsubmit="return _subscribermultipledelete()">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="chk_delete[]" id="chk_delete[]" /></th>
            <th scope="col"><?php _e('Sno', 'email-newsletter'); ?></th>
            <th scope="col"><?php _e('Email address', 'email-newsletter'); ?></th>
			<th scope="col"><?php _e('Status', 'email-newsletter'); ?></th>
            <th scope="col"><?php _e('DB id', 'email-newsletter'); ?></th>
			<th scope="col"><?php _e('Action', 'email-newsletter'); ?></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="chk_delete[]" id="chk_delete[]" /></th>
            <th scope="col"><?php _e('Sno', 'email-newsletter'); ?></th>
            <th scope="col"><?php _e('Email address', 'email-newsletter'); ?></th>
			<th scope="col"><?php _e('Status', 'email-newsletter'); ?></th>
            <th scope="col"><?php _e('DB id', 'email-newsletter'); ?></th>
			<th scope="col"><?php _e('Action', 'email-newsletter'); ?></th>
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
            <td align="left"><input name="chk_delete[]" id="chk_delete[]" type="checkbox" value="<?php echo $data['eemail_id_sub'] ?>" /></td>
            <td><?php echo $i; ?></td>
            <td><?php echo $data['eemail_email_sub']; ?></td>        
            <td>
			<?php
			if($data['eemail_status_sub'] == "YES")
			{
				?>Old Email<?php
			}
			elseif($data['eemail_status_sub'] == "SIG")
			{
				?>Single Opt In<?php
			}
			elseif($data['eemail_status_sub'] == "PEN")
			{
				?>Not confirmed<?php
			}
			elseif($data['eemail_status_sub'] == "CON")
			{
				?>Confirmed<?php
			}
			elseif($data['eemail_status_sub'] == "UNS")
			{
				?>Unsubscribed<?php
			}
			else
			{
				?>Old Email<?php
			}
			?>
			</td>
			<td><?php echo $data['eemail_id_sub']; ?></td>
			<td><div> 
			<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=edit&search=<?php echo $search; ?>&amp;did=<?php echo $data['eemail_id_sub']; ?>"><?php _e('Edit', 'email-newsletter'); ?></a> | </span> 
			<span class="trash"><a onClick="javascript:_eemail_delete('<?php echo $data['eemail_id_sub']; ?>','<?php echo $search; ?>')" href="javascript:void(0);"><?php _e('Delete', 'email-newsletter'); ?></a></span>
			<?php
			if($data['eemail_status_sub'] != "CON")
			{
				?>
					<span class="edit"> | <a onClick="javascript:_eemail_resend('<?php echo $data['eemail_id_sub']; ?>','<?php echo $search; ?>')" href="javascript:void(0);"><?php _e('Resend Confirmation', 'email-newsletter'); ?></a></span> 
				<?php
			}
			?>
			</div>
			</td>
          </tr>
          <?php
					$i = $i+1;
				} 
			}
			else
			{
				?>
				<tr>
					<td colspan="6" align="center"><?php _e('No records available.', 'email-newsletter'); ?></td>
				</tr>
				<?php 
			}
			?>
        </tbody>
      </table>
      <?php wp_nonce_field('eemail_form_show'); ?>
      <input type="hidden" name="frm_eemail_display" value="yes"/>
	  <input type="hidden" name="frm_eemail_bulkaction" value=""/>
	  <input name="searchquery" id="searchquery" type="hidden" value="<?php echo $search; ?>" />
	<div style="padding-top:10px;"></div>
    <div class="tablenav">
		<div class="alignleft">
			<select name="action" id="action">
				<option value=""><?php _e('Bulk Actions', 'email-newsletter'); ?></option>
				<option value="delete"><?php _e('Delete', 'email-newsletter'); ?></option>
				<option value="resend"><?php _e('Resend Confirmation', 'email-newsletter'); ?></option>
			</select>
			<input type="submit" value="Apply" class="button action" id="doaction" name="">
		</div>
		<div class="alignright">
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Add Email', 'email-newsletter'); ?></a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=view-subscriber&amp;ac=add"><?php _e('Import Email', 'email-newsletter'); ?></a> 
			<a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=export-subscriber"><?php _e('Export Email (CSV)', 'email-newsletter'); ?></a> 
			<a class="button add-new-h2" target="_blank" href="<?php echo WP_eemail_FAV; ?>"><?php _e('Help', 'email-newsletter'); ?></a> 
		</div>
    </div>
	</form>
    <br />
    <p class="description"><?php echo WP_eemail_LINK; ?></p>
  </div>
</div>
