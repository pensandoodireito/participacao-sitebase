<?php


if ( ! defined('ABSPATH')) exit;

function ssb_ajax_form()
	{	
		$ssb_site = $_POST['ssb_site'];
		$post_id = $_POST['post_id'];
		
		$ssb_post_sites = get_post_meta( $post_id, 'ssb_post_sites', true );
		$ssb_social_sites_domain = get_option( 'ssb_social_sites_domain' );

		foreach($ssb_social_sites_domain as $icon)
			{
				if($ssb_site == $icon)
					{
						$ssb_post_sites[$icon] = (int)$ssb_post_sites[$icon];
						$ssb_post_sites[$icon] = $ssb_post_sites[$icon]+1;
					}
				else
					{
						$ssb_post_sites[$icon] = (int)$ssb_post_sites[$icon];
						$ssb_post_sites[$icon] = $ssb_post_sites[$icon];
					}
			}


		// trace stats
		update_post_meta( $post_id, 'ssb_post_sites', $ssb_post_sites );
		die();
	}



add_action('wp_ajax_ssb_ajax_form', 'ssb_ajax_form');
add_action('wp_ajax_nopriv_ssb_ajax_form', 'ssb_ajax_form');




function ssb_display($content)
	{
		$ssb_share_content_position = get_option( 'ssb_share_content_position' );
		$ssb_share_content_display = get_option( 'ssb_share_content_display' );
		$ssb_share_filter_posttype = get_option( 'ssb_share_filter_posttype' );
		
		
		
		

		if($ssb_share_filter_posttype==NULL)
			{
				$type ='none';
			}
		else
			{
				$type = '';
			foreach ( $ssb_share_filter_posttype as  $post_type => $post_type_value )
				{
			
				$type .= $post_type.',';
				}
			}
		
		
		if(is_singular(explode(',',$type)) or ( is_home() && ssb_share_home_display() ) or ( is_archive() && ssb_share_archive_display() ))
			{
				
				$content_new = '';
				if($ssb_share_content_position=='top')
					{
						$content_new.=ssb_share_icons();
						$content_new .=$content;
					}
				elseif($ssb_share_content_position=='bottom')
					{	
						$content_new .=$content;
						$content_new.=ssb_share_icons();
						
					}
			
				if($ssb_share_content_display=='yes')
					{
						return $content_new;
					}
				else
					{
						return $content;
					}
			
			}	
		else
			{
				return $content;
			}
	
	
	}

add_filter('the_content', 'ssb_display');



$ssb_share_excerpt_display = get_option( 'ssb_share_excerpt_display' );	

if($ssb_share_excerpt_display == 'yes')
	{
		add_filter('get_the_excerpt', 'ssb_display');
	}




function ssb_total_share_count_by_postid()
	{
		$post_id = get_the_ID();
		
		$ssb_social_sites_domain = get_option( 'ssb_social_sites_domain' );
		$ssb_post_sites = get_post_meta( $post_id, 'ssb_post_sites', true );
		
		
		foreach($ssb_social_sites_domain as $domain)
			{
				if(!empty($ssb_post_sites[$domain]))
					{
						$ssb_post_sites[$domain] = (int)$ssb_post_sites[$domain];
					}
				else
					{
						$ssb_post_sites[$domain] = 0;
					}
				
				
			}
			
		$total_count = array_sum($ssb_post_sites);
		
		return $total_count;
	}








function ssb_share_icons()
	{	
		$ssb_share_content_themes = get_option( 'ssb_share_content_themes' );
		$ssb_share_content_icon_margin = get_option( 'ssb_share_content_icon_margin' );		

		$ssb_share_icons = '';
		$ssb_share_icons.="<div class='ssb-share ssb-share-".get_the_ID()." ".$ssb_share_content_themes."' post_id='".get_the_ID()."' >";
		$ssb_share_icons.= ssb_share_body();
		$ssb_share_icons.='</div>';	

		return $ssb_share_icons;
	}



function ssb_share_get_title()
	{
		global $post;
		$title = get_the_title( $post->ID );
		
		return $title;	
	}


function ssb_share_get_url()
	{
		global $post;
		$permalink = get_permalink( $post->ID );
		
		return $permalink;
	}



function ssb_share_get_image()
	{	
		global $post;
		if ( has_post_thumbnail())
			{
				$post_thumbnail_id = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ));
				$post_thumbnail_id = $post_thumbnail_id[0];
		 	}
		else
			{
				$post_thumbnail_id ='';
			}
		 
	return $post_thumbnail_id;	
	}


function ssb_share_archive_display()
	{
		$ssb_share_archive_display = get_option( 'ssb_share_archive_display' );
		
		if($ssb_share_archive_display == 'yes')
			{
				return true;
			}
		else
			{
				return false;
			}
	}


function ssb_share_home_display()
	{
		$ssb_share_home_display = get_option( 'ssb_share_home_display' );
		
		if($ssb_share_home_display == 'yes')
			{
				return true;
			}
		else
			{
				return false;
			}
	}


















	
	function ssb_share_plugin()
		{
			
			?>
			<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwordpress.org%2Fplugins%2Fsocial-share-button%2F&amp;width&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=80&amp;appId=652982311485932" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:80px;" allowTransparency="true"></iframe>
            
            <br />
            <!-- Place this tag in your head or just before your close body tag. -->
            <script src="https://apis.google.com/js/platform.js" async defer></script>
            
            <!-- Place this tag where you want the +1 button to render. -->
            <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="300" data-href="<?php echo ssb_share_url; ?>"></div>
            
            <br />
            <br />
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo ssb_share_url; ?>" data-text="<?php echo ssb_plugin_name; ?>" data-via="ParaTheme" data-hashtags="WordPress">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>



            <?php
			
			
			
		
		
		}