<script language="javascript" type="text/javascript" src="<?php echo emailnews_plugin_url('widget/widget.js'); ?>"></script>
<link rel="stylesheet" media="screen" type="text/css" href="<?php echo emailnews_plugin_url('widget/widget.css'); ?>" />
<div>
  <div class="eemail_caption">
    <?php echo get_option('eemail_widget_cap'); ?>
  </div>
  <div class="eemail_msg">
    <span id="eemail_msg"></span>
  </div>
  <div class="eemail_textbox">
    <input class="eemail_textbox_class" name="eemail_txt_email" id="eemail_txt_email" onkeypress="if(event.keyCode==13) eemail_submit_ajax('<?php echo emailnews_plugin_url('widget'); ?>')" onblur="if(this.value=='') this.value='<?php echo get_option('eemail_widget_txt_cap'); ?>';" onfocus="if(this.value=='<?php echo get_option('eemail_widget_txt_cap'); ?>') this.value='';" value="<?php echo get_option('eemail_widget_txt_cap'); ?>" maxlength="150" type="text">
  </div>
  <?php 
  if(get_option('readygraph_application_id') && strlen(get_option('readygraph_application_id')) > 0){?>
  <p style="max-width:180px;font-size: 10px;margin-bottom:10px;">By signing up, you agree to our <a href="http://www.readygraph.com/tos">Terms of Service</a> and <a href='http://readygraph.com/privacy/'>Privacy Policy</a>.</p>
  <?php } ?>
  <div class="eemail_button">
    <input class="eemail_textbox_button" name="eemail_txt_Button" id="eemail_txt_Button" onClick="return eemail_submit_ajax('<?php echo emailnews_plugin_url('widget'); ?>','<?php echo get_option('readygraph_application_id', ''); ?>')" value="<?php echo get_option('eemail_widget_but_cap'); ?>" type="button">
  </div>
</div>