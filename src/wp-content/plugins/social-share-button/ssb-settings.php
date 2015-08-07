<?php	
/**
 * Social Share Button main setting page.
 *
 * @author 		ParaTheme
 * @version     1.0
 */
	if ( ! defined('ABSPATH')) exit;

	if(empty($_POST['ssb_hidden']))
		{
			$ssb_share_content_display = get_option( 'ssb_share_content_display' );	
			$ssb_share_content_themes = get_option( 'ssb_share_content_themes' );
			$ssb_share_content_position = get_option( 'ssb_share_content_position' );
			$ssb_share_total_count_display = get_option( 'ssb_share_total_count_display' );			
			$ssb_share_content_icon_margin = get_option( 'ssb_share_content_icon_margin' );
			$ssb_share_filter_posttype = get_option( 'ssb_share_filter_posttype' );			
			$ssb_share_target_tab = get_option( 'ssb_share_target_tab' );
			$ssb_social_sites = get_option( 'ssb_social_sites' );
			$ssb_social_sites_domain = get_option( 'ssb_social_sites_domain' );			
			$ssb_social_sites_domain_url = get_option( 'ssb_social_sites_domain_url' );
			$ssb_social_sites_domain_icon = get_option( 'ssb_social_sites_domain_icon' );						
			
			
			$ssb_share_archive_display = get_option( 'ssb_share_archive_display' );	
			$ssb_share_home_display = get_option( 'ssb_share_home_display' );									
			$ssb_share_excerpt_display = get_option( 'ssb_share_excerpt_display' );			
			
		}
	else
		{
	
		if($_POST['ssb_hidden'] == 'Y') {
			//Form data sent

			
			$ssb_share_content_display = $_POST['ssb_share_content_display'];
			update_option('ssb_share_content_display', $ssb_share_content_display);			

			$ssb_share_content_themes = $_POST['ssb_share_content_themes'];
			update_option('ssb_share_content_themes', $ssb_share_content_themes);
			
			$ssb_share_content_position = $_POST['ssb_share_content_position'];
			update_option('ssb_share_content_position', $ssb_share_content_position);
			
			$ssb_share_total_count_display = $_POST['ssb_share_total_count_display'];
			update_option('ssb_share_total_count_display', $ssb_share_total_count_display);			
			
			$ssb_share_content_icon_margin = $_POST['ssb_share_content_icon_margin'];
			update_option('ssb_share_content_icon_margin', $ssb_share_content_icon_margin);
			

			if(!empty($_POST['ssb_share_filter_posttype']))
				{
					$ssb_share_filter_posttype = $_POST['ssb_share_filter_posttype'];
				}
			else
				{
					$ssb_share_filter_posttype = "";
				}
				
			update_option('ssb_share_filter_posttype', $ssb_share_filter_posttype);			
			
			$ssb_share_target_tab = $_POST['ssb_share_target_tab'];
			update_option('ssb_share_target_tab', $ssb_share_target_tab);	
			
			$ssb_social_sites = stripslashes_deep($_POST['ssb_social_sites']);
			update_option('ssb_social_sites', $ssb_social_sites);			
			
			$ssb_social_sites_domain = stripslashes_deep($_POST['ssb_social_sites_domain']);
			update_option('ssb_social_sites_domain', $ssb_social_sites_domain);				
			
			$ssb_social_sites_domain_url = stripslashes_deep($_POST['ssb_social_sites_domain_url']);
			update_option('ssb_social_sites_domain_url', $ssb_social_sites_domain_url);				
			
			$ssb_social_sites_domain_icon = stripslashes_deep($_POST['ssb_social_sites_domain_icon']);
			update_option('ssb_social_sites_domain_icon', $ssb_social_sites_domain_icon);				
			
			
			
			
			
			$ssb_share_archive_display = $_POST['ssb_share_archive_display'];
			update_option('ssb_share_archive_display', $ssb_share_archive_display);
			
			$ssb_share_home_display = $_POST['ssb_share_home_display'];
			update_option('ssb_share_home_display', $ssb_share_home_display);
			
			$ssb_share_excerpt_display = $_POST['ssb_share_excerpt_display'];
			update_option('ssb_share_excerpt_display', $ssb_share_excerpt_display);			
					
			
							

			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p></div>

			<?php
		} 
	}
	
	
	

    $ssb_customer_type = get_option('ssb_customer_type');
    $ssb_version = get_option('ssb_version');	










            if(empty($ssb_social_sites_domain))
                {
                    $ssb_social_sites_domain = array(
														"reddit"=>"reddit",						
														"email"=>"email",					
														"fb"=>"fb",
														"twitter"=>"twitter",
														"gplus"=>"gplus",
														"pinterest"=>"pinterest");
                    
                }
				
            if(empty($ssb_social_sites_domain_url))
                {
                    $ssb_social_sites_domain_url = array(
														"reddit"=>"http://www.reddit.com/submit?title={title}&url={url}",						
														"email"=>"mailto:?subject={title}&body={url}",					
														"fb"=>"https://www.facebook.com/sharer/sharer.php?u={url}",
														"twitter"=>"https://twitter.com/intent/tweet?url={url}&text={title}",
														"gplus"=>"https://plus.google.com/share?url={url}",
														"pinterest"=>"https://pinterest.com/pin/create/button/?url={url}&media={thumb_url}");
                    
                }






	
?>

<div class="wrap">

	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".ssb_plugin_name." Settings</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="ssb_hidden" value="Y">
        <?php settings_fields( 'ssb_plugin_options' );
				do_settings_sections( 'ssb_plugin_options' );
			
		?>
       
	<br />
	<div class="para-settings ssb-settings">
        <ul class="tab-nav">
            <li nav="1" class="nav1 active">SSB Options</li>
            <li nav="2" class="nav2">SSB Style</li>
            <li nav="3" class="nav3">SSB Content</li>
            <li nav="5" class="nav5"><i>new</i>Social Sites</li>            
            <li nav="4" class="nav4">Help & Upgrade</li>
        
        </ul>

        <ul class="box">
            <li style="display: block;" class="box1 tab-box active">
                <div class="option-box">
                    <p class="option-title">Display On Content. (Enable/Disable)</p>
                    <p class="option-info">Choose 'yes' to display share buttons on content and choose 'No' to hide share buttons on post.</p>
                    
                    <select name="ssb_share_content_display">
                        <option value="yes" <?php  if($ssb_share_content_display=='yes') echo "selected"; ?>>Yes</option>
                        <option value="no" <?php  if($ssb_share_content_display=='no') echo "selected"; ?>>No</option>
                       
                    </select>
                </div>
                
               
                
                
				
				<div class="option-box">
                    <p class="option-title">Open new tab when click share buttons.</p>
                    <p class="option-info">When someone try to click share buttons it will open new tab to share link on social media sites.</p>
					<select name="ssb_share_target_tab">
                    	<option value="new" <?php  if($ssb_share_target_tab=='new') echo "selected"; ?>>New</option>
                        <option value="same" <?php  if($ssb_share_target_tab=='same') echo "selected"; ?>>Same</option>
					</select>
                </div>          
				  





				


				<div class="option-box">
                    <p class="option-title">Need Help ?</p>
                    <p class="option-info">Feel free to Contact with any issue for this plugin, Ask any question via forum <a href="<?php echo ssb_qa_url;?>"><?php echo ssb_qa_url;?></a> <strong style="color:#139b50;">(free)</strong>
                    
                    </p>
                </div>



				<div class="option-box">
                    <p class="option-title">Display only these buttons.</p>
                    <p class="option-info"></p>
                    
                    
                    
                    <?php
                    
					foreach($ssb_social_sites_domain as $domain)
						{
							if(empty($ssb_social_sites[$domain]))
								{
									$ssb_social_sites[$domain] = '';
								}
							
							
							echo '<label><input type="checkbox" name="ssb_social_sites['.$domain.']" value="'.$domain.'"';
							if($ssb_social_sites[$domain] == $domain) echo "checked";
							echo '  />'.$domain.'</label><br/>';
						}
					
					?>

                </div>





            </li>
            <li class="box2 tab-box">
				<div class="option-box">
                    <p class="option-title">Select Buttons Themes.</p>
                    <p class="option-info">Select different style themes to display different style buttons on post.</p>
<style type="text/css">

.ssb_share_content_themes input[type="radio"] {
  display: none;
}

.ssb_share_content_themes label {
  cursor: pointer;
  display: inline-block;
  margin: 10px;
  
}

.ssb_share_content_themes span {
  border: 3px solid rgb(199, 199, 199);
}

.ssb_share_content_themes span.selected {
  border: 3px solid #FF511C;
}



span.ssb_share_content_themes_defualt 
	{
  background: url("<?php echo ssb_plugin_url; ?>/css/admin-defualt.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	
	}
	

span.ssb_share_content_themes_defualt_box
	{
  background: url("<?php echo ssb_plugin_url; ?>/css/admin-defualt-box.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	
	}	
	
	
span.ssb_share_content_themes_flat {
  background: url("<?php echo ssb_plugin_url; ?>/css/admin-flat.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
  
}
	
span.ssb_share_content_themes_round 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-round.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}
	
	
span.ssb_share_content_themes_wide 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-wide.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
	
span.ssb_share_content_themes_bodyname 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-bodyname.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}
span.ssb_share_content_themes_packslide 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-packslide.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
span.ssb_share_content_themes_hexa
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-hexa.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
span.ssb_share_content_themes_hover_left
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-hover-left.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
	
span.ssb_share_content_themes_hover_right 
	{
  background: url("<?php echo ssb_plugin_url; ?>css/admin-hover-right.png") repeat scroll 0 0 rgba(0, 0, 0, 0);
  display: block;
  height: 100px;
  width: 100px;
	}	
	
</style>


<script>
jQuery(document).ready(function(jQuery)
	{
					jQuery(".ssb_share_content_themes span").click(function(){
						jQuery('.selected').removeClass('selected');
						jQuery(this).addClass('selected');
						
						})

					
	})

</script>







        <div class="ssb_share_content_themes">
        	<label ><input type="radio" name="ssb_share_content_themes"  value="defualt" <?php  if($ssb_share_content_themes=='defualt') echo "checked"; ?>/><span title="Defualt" class="ssb_share_content_themes_defualt <?php  if($ssb_share_content_themes=='defualt') echo "selected"; ?>"></span></label>
            
        	<label ><input type="radio" name="ssb_share_content_themes"  value="defualt_box" <?php  if($ssb_share_content_themes=='defualt_box') echo "checked"; ?>/><span title="Defualt Box" class="ssb_share_content_themes_defualt_box <?php  if($ssb_share_content_themes=='defualt_box') echo "selected"; ?>"></span></label>            
            
            
            
        	<label ><input type="radio" name="ssb_share_content_themes"  value="flat" <?php  if($ssb_share_content_themes=='flat') echo "checked"; ?>/><span title="Flat" class="ssb_share_content_themes_flat <?php  if($ssb_share_content_themes=='flat') echo "selected"; ?>"></span></label>
            
           <label ><input type="radio" name="ssb_share_content_themes"  value="round" <?php  if($ssb_share_content_themes=='round') echo "checked"; ?>/><span title="Round" class="ssb_share_content_themes_round <?php  if($ssb_share_content_themes=='round') echo "selected"; ?>" ></span></label>
           
           <label ><input type="radio" name="ssb_share_content_themes"  value="wide" <?php  if($ssb_share_content_themes=='wide') echo "checked"; ?>/><span title="Wide" class="ssb_share_content_themes_wide <?php  if($ssb_share_content_themes=='wide') echo "selected"; ?>"></span></label>
            
           <label > <input type="radio" name="ssb_share_content_themes"  value="bodyname" <?php  if($ssb_share_content_themes=='bodyname') echo "checked"; ?>/><span title="Body Name" class="ssb_share_content_themes_bodyname <?php  if($ssb_share_content_themes=='bodyname') echo "selected"; ?>"></span></label>
            
             <label ><input type="radio" name="ssb_share_content_themes"  value="packslide" <?php  if($ssb_share_content_themes=='packslide') echo "checked"; ?>><span title="Pack Slide"  class="ssb_share_content_themes_packslide <?php  if($ssb_share_content_themes=='packslide') echo "selected"; ?>"></span></label>
             
            <label ><input type="radio" name="ssb_share_content_themes"  value="hexa" <?php  if($ssb_share_content_themes=='hexa') echo "checked"; ?>/><span title="Hexa" class="ssb_share_content_themes_hexa <?php  if($ssb_share_content_themes=='hexa') echo "selected"; ?>"></span></label>
            
            <label > <input type="radio" name="ssb_share_content_themes"  value="hover-left" <?php  if($ssb_share_content_themes=='hover-left') echo "checked"; ?>><span title="Hover Left" class="ssb_share_content_themes_hover_left <?php  if($ssb_share_content_themes=='hover-left') echo "selected"; ?>"></span></label>
                 
             <label ><input type="radio" name="ssb_share_content_themes"   value="hover-right" <?php  if($ssb_share_content_themes=='hover-right') echo "checked"; ?>/><span title="Hover Right" class="ssb_share_content_themes_hover_right <?php  if($ssb_share_content_themes=='hover-right') echo "selected"; ?>"></span></label>                   
           			</div>
                </div>
                
                
				<div class="option-box">
                    <p class="option-title">Buttons Margin.</p>
                    <p class="option-info">Margin for buttons.</p>
					<input name="ssb_share_content_icon_margin" size="5" type="text" value="<?php  if(!empty($ssb_share_content_icon_margin)) echo $ssb_share_content_icon_margin; else echo "0"; ?>" />px 
                </div>
                
                
				<div class="option-box">
                    <p class="option-title">Social Share Position.</p>
                    <p class="option-info">Display share button top or bottom of content.</p>
                    <select name="ssb_share_content_position">
                        <option value="top" <?php  if($ssb_share_content_position=='top') echo "selected"; ?>>Top</option>
                        <option value="bottom" <?php  if($ssb_share_content_position=='bottom') echo "selected"; ?>>Bottom</option>
                    </select>
                </div>
                
				<div class="option-box">
                    <p class="option-title">Total Share Count Display.</p>
                    <p class="option-info">Display total share count.(for some theme)</p>
                    <select name="ssb_share_total_count_display">
                    	<option value="no" <?php  if($ssb_share_total_count_display=='no') echo "selected"; ?>>No</option>
                        <option value="yes" <?php  if($ssb_share_total_count_display=='yes') echo "selected"; ?>>Yes</option>
                        
                    </select>
                </div>                
                
                
                
                
                
            </li>

            <li class="box3 tab-box">
            
            
            
                <div class="option-box">
                    <p class="option-title">Display On Archive.</p>
                    <p class="option-info">Choose 'yes' to display share buttons on archive page's and choose 'No' to hide share buttons on archive.</p>
                    <select name="ssb_share_archive_display">
                    	<option value="no" <?php  if($ssb_share_archive_display=='no') echo "selected"; ?>>No</option>
                        <option value="yes" <?php  if($ssb_share_archive_display=='yes') echo "selected"; ?>>Yes</option>  
                    </select>
                </div> 
                
                <div class="option-box">
                    <p class="option-title">Display On Home.</p>
                    <p class="option-info">Choose 'yes' to display share buttons on Home page and choose 'No' to hide share buttons on Home.</p>
                    <select name="ssb_share_home_display">
                    	<option value="no" <?php  if($ssb_share_home_display=='no') echo "selected"; ?>>No</option>
                        <option value="yes" <?php  if($ssb_share_home_display=='yes') echo "selected"; ?>>Yes</option>
                    </select>
                </div>
                
                <div class="option-box">
                    <p class="option-title">Display On Excerpt.</p>
                    <p class="option-info">Choose 'yes' to display share buttons on excerpt and choose 'No' to hide share buttons on excerpt.</p>
                    <select name="ssb_share_excerpt_display">
                        <option value="no" <?php  if($ssb_share_excerpt_display=='no') echo "selected"; ?>>No</option>
                        <option value="yes" <?php  if($ssb_share_excerpt_display=='yes') echo "selected"; ?>>Yes</option>
                    </select>
                </div>                
                
                
                               
            
            
            
				<div class="option-box">
                    <p class="option-title">Display share button On These Post Type Only.</p>
                    <p class="option-info">By choosing post type you can filter display share button only these post type.</p>
                    
                    <?php

					$post_types = get_post_types( '', 'names' ); 
					
					foreach ( $post_types as $post_type ) {
					
					   echo '<label for="ssb_share_filter_posttype['.$post_type.']"><input type="checkbox" name="ssb_share_filter_posttype['.$post_type.']" id="ssb_share_filter_posttype['.$post_type.']"  value ="1" '; 
					   if ( isset( $ssb_share_filter_posttype[$post_type] ) ) echo "checked"; 
					   echo' >'. $post_type.'</label><br />' ;
					}
					
					?>
                </div>
            </li>
            <li class="box5 tab-box">
				<div class="option-box">
                    <p class="option-title">Social sites domain</p>
                    <p class="option-info">Now you can add custom social site which is not added, default sites are reddit, email, fb, twitter, gplus, pinterest.
                    <br />
                    String {url}, {title}, {thumb_url} will replace by post url, title and thumbnail url.
                    </p>
                
			<table class="ssb_social_sites_domain" id="ssb_social_sites_domain">
				<tr>
                <th></th>
                <th>Domain</th> 
                <th>Share URL</th>                 
                <th>Icon</th> 
                <th>Delete</th>                
                                               
                </tr>
              
            <?php 

				
				
				
				
				
				
				
				

            foreach ($ssb_social_sites_domain as $value) {
                if(!empty($value))
                    {
                        ?>
                    <tr>
                    	<td>*</td>
                        <td>
                       		<input type="text" name="ssb_social_sites_domain[<?php echo $value; ?>]" value="<?php if(isset($ssb_social_sites_domain[$value])) echo $ssb_social_sites_domain[$value]; else echo $value; ?>"  /><br />
                        </td>
                        
                        <td width="450">
                       		<input type="text" style="width:100%" name="ssb_social_sites_domain_url[<?php echo $value; ?>]" value="<?php if(isset($ssb_social_sites_domain_url[$value])) echo $ssb_social_sites_domain_url[$value]; ?>"  /><br />
                        </td> 
                        
                        <td>                    
                            <span style=" <?php if(!empty($ssb_social_sites_domain_icon[$value])) echo 'background:url('.$ssb_social_sites_domain_icon[$value].') no-repeat scroll 0 0 rgba(0, 0, 0, 0);';  ?>" title="Icon for this field." class="ssb_social_sites_domain_icon <?php if(empty($ssb_social_sites_domain_icon[$value])) echo 'empty_icon';?>" icon-name="<?php echo $value; ?>"> </span>
                            
                            
                            <input class="ssb_social_sites_domain_icon_<?php echo $value; ?>" name="ssb_social_sites_domain_icon[<?php echo $value; ?>]" type="hidden" value="<?php if(isset($ssb_social_sites_domain_icon[$value])) echo $ssb_social_sites_domain_icon[$value]; ?>" />
                        </td>                        
                                               
                        <td>                    
                        <span class="ssb_social_sites_domain_remove">X</span>
                        </td>
                    </tr>
                        <?php
						
						
                    
                    }
            }
            
            ?>

                    
                    </table> 
                    
        
        
 <script>

	jQuery(document).ready(function() {

		// Initialise the table
		jQuery("#ssb_social_sites_domain").tableDnD();

	});

</script>

<div class="button ssb_social_sites_domain_add"><?php _e('Add New','ssb'); ?></div>


                </div>
            </li>         
            <li class="box4 tab-box">
				<div class="option-box">
                    <p class="option-title">Need Help ?</p>
                    <p class="option-info">Feel free to contact with any issue for this plugin, Ask any question via forum <a href="<?php echo ssb_qa_url; ?>"><?php echo ssb_qa_url; ?></a> <strong style="color:#139b50;">(free)</strong><br />

    <?php

    if($ssb_customer_type=="free")
        {
    
            echo 'You are using <strong> '.$ssb_customer_type.' version  '.$ssb_version.'</strong> of <strong>'.ssb_plugin_name.'</strong>, To get more feature you could try our premium version. ';
            
            echo '<br /><a href="'.ssb_pro_url.'">'.ssb_pro_url.'</a>';
            
        }
    else
        {
    
            echo 'Thanks for using <strong> premium version  '.$ssb_version.'</strong> of <strong>'.ssb_plugin_name.'</strong> ';	
            
            
        }
    
     ?>       

                    
                    </p>

                </div>
                
                
				<div class="option-box">
                    <p class="option-title">Please Share</p>
                    <p class="option-info">If you like this plugin please share with your social share network.</p>
                    <?php
                    
						echo ssb_share_plugin();
					?>
                </div>
                
                
                
                
                
                
            </li>

</ul>







<p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes' ) ?>" />
                </p>
		</form>
	</div>

</div>
