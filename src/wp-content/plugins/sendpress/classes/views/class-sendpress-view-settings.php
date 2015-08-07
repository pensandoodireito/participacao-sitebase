<?php

// Prevent loading this file directly
if ( !defined('SENDPRESS_VERSION') ) {
	header('HTTP/1.0 403 Forbidden');
	die;
}

class SendPress_View_Settings extends SendPress_View {



	function sub_menu($sp = false){
		 SendPress_Tracking::event('Settings Tab');
	?>

	<div class="navbar navbar-default" >

		<div class="pull-right  top-action-buttons navbar-right btn-group">
			<?php $this->view_buttons(); ?>
		</div>
		<div class="navbar-header">

		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		 <span class="sr-only"><?php _e('Toggle navigation','sendpress'); ?></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>

		</button>
		<a class="navbar-brand" href="#"><?php _e('Settings','sendpress'); ?></a>
		</div>
		 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
			<!--
		  <li <?php if($sp->_current_view == ''){ ?>class="active"<?php } ?> ><a href="<?php echo SendPress_Admin::link('Settings'); ?>"><i class="icon-envelope"></i> <?php _e('Basic Setup','sendpress'); ?></a></li>
		 -->
		  <!--
		   <li <?php if($sp->_current_view == 'shared'){ ?>class="active"<?php } ?> >
		    <a href="<?php echo SendPress_Admin::link('Settings_Shared'); ?>"><i class="icon-pencil "></i> <?php _e('Shared Content','sendpress'); ?></a>
		  </li>
		  -->
		<li <?php if($sp->_current_view == 'account'){ ?>class="active"<?php } ?> ><a href="<?php echo SendPress_Admin::link('Settings_Account'); ?>"><i class="icon-envelope "></i> <?php _e('Sending','sendpress'); ?></a></li>
		<li <?php if($sp->_current_view == 'activation'){ ?>class="active"<?php } ?> ><a <?php if($sp->_current_view == 'activation'){ ?>class="wp-ui-primary"<?php } ?>  href="<?php echo SendPress_Admin::link('Settings_Activation'); ?>"><i class=" icon-bullhorn"></i> <?php _e('Confirmation','sendpress'); ?></a></li>
		<?php if(SendPress_Option::get('beta')){ ?>
			<li <?php if(strpos($sp->_current_view,'systememail') !== false){ ?>class="active"<?php } ?> ><a <?php if(strpos($sp->_current_view,'systememail') !== false){ ?>class="wp-ui-primary"<?php } ?>  href="<?php echo SendPress_Admin::link('Settings_Systememail'); ?>"><i class=" icon-bullhorn"></i> <?php _e('System Email','sendpress'); ?></a></li>
		<?php } ?>
		<li <?php if($sp->_current_view == 'access'){ ?>class="active"<?php } ?> ><a href="<?php echo SendPress_Admin::link('Settings_Access'); ?>"><i class="icon-user "></i> <?php _e('Permissions','sendpress'); ?></a></li>
		<li <?php if($sp->_current_view == 'notifications'){ ?>class="active"<?php } ?> ><a href="<?php echo SendPress_Admin::link('Settings_Notifications'); ?>"><i class="icon-bell"></i> <?php _e('Notifications','sendpress'); ?></a></li>
		<li <?php if($sp->_current_view == 'widgets'){ ?>class="active"<?php } ?> ><a href="<?php echo SendPress_Admin::link('Settings_Widgets'); ?>"><i class=" icon-wrench "></i> <?php _e('Forms','sendpress'); ?></a></li>
		<li <?php if($sp->_current_view == 'advanced'){ ?>class="active"<?php } ?> ><a href="<?php echo SendPress_Admin::link('Settings_Advanced'); ?>"><i class=" icon-wrench "></i> <?php _e('Advanced','sendpress'); ?></a></li>
		<li <?php if($sp->_current_view == 'styles'){ ?>class="active wp-ui-primary"<?php } ?> >
		    <a href="<?php echo SendPress_Admin::link('Settings_Styles'); ?>"><i class="icon-pencil "></i> <?php _e('Styles','sendpress'); ?></a>
		 </li>
		 
		<?php do_action('sendpress_view_settings_menu', $sp->_current_view); ?>

		</ul>

	</div>
</div>
	<?php
	}




	function prerender($sp = false){
		if(  $sp->_current_view == '' ){
			SendPress_Admin::redirect('Settings_Account');
		}
	}
	function html($sp) {
		echo "Parent view needs a child.";
	}

}
SendPress_Admin::add_cap('Settings','sendpress_settings');