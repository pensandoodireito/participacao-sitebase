<?php

function ssb_share_body()
	{
		global $post;
		
		$ssb_share_content_themes = get_option( 'ssb_share_content_themes' );
		$ssb_share_target_tab = get_option( 'ssb_share_target_tab' );
		$ssb_social_sites = get_option( 'ssb_social_sites' );
		
		$ssb_share_total_count_display = get_option( 'ssb_share_total_count_display' );		
		
		$ssb_social_sites_domain = get_option( 'ssb_social_sites_domain' );				
		$ssb_social_sites_domain_url = get_option( 'ssb_social_sites_domain_url' );			
		$ssb_social_sites_domain_icon = get_option( 'ssb_social_sites_domain_icon' );		
		
		
		
		
		
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
		
		
		
            if(empty($ssb_social_sites_domain_icon))
                {
                    $ssb_social_sites_domain_icon = array(
														"reddit"=>"",						
														"email"=>"",					
														"fb"=>"",
														"twitter"=>"",
														"gplus"=>"",
														"pinterest"=>"");
                    
                }		
		
		
		
		
		if($ssb_share_target_tab=='new')
			{$ssb_share_target_tab = "target='_blank'";}
		elseif($ssb_share_target_tab=='same')
			{$ssb_share_target_tab = "target='_parent'";}
		
		$ssb_post_sites = get_post_meta( $post->ID, 'ssb_post_sites', true );

		foreach($ssb_social_sites as $icon)
			{
								
			if(empty($ssb_post_sites[$icon]))
				{
					$ssb_count[$icon] = 0;
				}
			else
				{
					$ssb_count[$icon] = $ssb_post_sites[$icon];
				}
				
				
			}





		
		$ssb_share_icons = '';
		
		if($ssb_share_content_themes=='defualt')
			{
				
		foreach($ssb_social_sites as $icon)
			{
				
				if(!empty($ssb_social_sites[$icon]))
					{
			
						if($ssb_social_sites[$icon]=="fb")				
						$ssb_share_icons.= '<div class="defualt-button-fb">
		<iframe src="//www.facebook.com/plugins/like.php?href='.ssb_share_get_url().'&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=743541755673761" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
						</div>';
		
						if($ssb_social_sites[$icon]=="twitter")
						$ssb_share_icons.= '<div class="defualt-button-twitter">
						<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.ssb_share_get_url().'">Tweet</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>
							</div>';		
									
						if($ssb_social_sites[$icon]=="gplus")
						$ssb_share_icons.= '<div class="defualt-button-gplus">
						<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
		<div class="g-plusone" data-size="medium" data-href="'.ssb_share_get_url().'"></div>
							</div>';				
					
						if($ssb_social_sites[$icon]=="linkedin")
						$ssb_share_icons.= '<div class="defualt-button-linkedin">
						<script src="//platform.linkedin.com/in.js" type="text/javascript">
		  lang: en_US
		</script>
		<script type="IN/Share" data-url="'.ssb_share_get_url().'" data-counter="right"></script>
							</div>';			
					
					
						if($ssb_social_sites[$icon]=="pinterest")
						$ssb_share_icons.= '<div class="defualt-button-pinterest">
		<a href="//www.pinterest.com/pin/create/button/?url='.ssb_share_get_url().'&media='.ssb_share_get_image().'&description='.ssb_share_get_title().'" data-pin-do="buttonPin" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
		<!-- Please call pinit.js only once per page -->
		<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
							</div>';			
					
						if($ssb_social_sites[$icon]=="reddit")
						$ssb_share_icons.= '<div class="defualt-button-reddit">
							<script type="text/javascript" src="http://www.reddit.com/static/button/button1.js?i=1&styled=off&url='.ssb_share_get_url().'&newwindow=1&reddit_title='.ssb_share_get_title().'"></script>
							</div>';
			
						if($ssb_social_sites[$icon]=="email")
						$ssb_share_icons.= '<div class="defualt-button-email">
		<a '.$ssb_share_target_tab.' class="email" href="mailto:?subject='.ssb_share_get_title().'&body='.ssb_share_get_url().'" ><span class="icon"></span>Email</a>
							</div>';
					}
	
				
				
				
				
				
				
					
			}
				
				
				
				
				
				
								

	
			}
		if($ssb_share_content_themes=='defualt_box')
			{
			$ssb_share_icons.= '<div class="ssb-share" >';
		foreach($ssb_social_sites as $icon)
			{
				
				if(!empty($ssb_social_sites[$icon]))
					{
						
						if($ssb_social_sites[$icon]=="fb")				
						$ssb_share_icons.= '<div class="defualt-box-fb">
		<iframe src="//www.facebook.com/plugins/like.php?href='.ssb_share_get_url().'&amp;width&amp;layout=box_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=65&amp;appId=743541755673761" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:65px;" allowTransparency="true"></iframe>
						</div>';
					
		
						if($ssb_social_sites[$icon]=="gplus")
						$ssb_share_icons.= '<div class="defualt-box-gplus">
						<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
		<div class="g-plusone" data-size="tall" data-href="'.ssb_share_get_url().'"></div>
							</div>';
		
		
						if($ssb_social_sites[$icon]=="twitter")
						$ssb_share_icons.= '<div class="defualt-box-twitter" >
						<a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical" data-url="'.ssb_share_get_url().'">Tweet</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>
							</div>';	
		
					
						if($ssb_social_sites[$icon]=="linkedin")
						$ssb_share_icons.= '<div class="defualt-box-linkedin">
						<script src="//platform.linkedin.com/in.js" type="text/javascript">
		  lang: en_US
		</script>
		<script type="IN/Share" data-url="'.ssb_share_get_url().'" data-counter="top"></script>
							</div>';
							
						if($ssb_social_sites[$icon]=="pinterest")
						$ssb_share_icons.= '<div class="defualt-box-pinterest">
		<a href="//www.pinterest.com/pin/create/button/?url='.ssb_share_get_url().'&media='.ssb_share_get_image().'&description='.ssb_share_get_title().'" count-layout="vertical" data-pin-do="buttonPin" data-pin-config="beside"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
		<!-- Please call pinit.js only once per page -->
		<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
							</div>';	
							
					
						if($ssb_social_sites[$icon]=="reddit")
						$ssb_share_icons.= '<div class="defualt-box-reddit">
							<script type="text/javascript" src="http://www.reddit.com/static/button/button2.js?i=1&styled=off&url='.ssb_share_get_url().'&newwindow=1&reddit_title='.ssb_share_get_title().'"></script>
							</div>';
		
		
						if($ssb_social_sites[$icon]=="email")
						$ssb_share_icons.= '<div class="defualt-box-email">
		<a '.$ssb_share_target_tab.' class="email" href="mailto:?subject='.ssb_share_get_title().'&body='.ssb_share_get_url().'" ><span class="icon"></span>Email</a>
							</div>';

					
					}
			}
			
			$ssb_share_icons.= '</div>';
			
			
			
			
			

			
				
			
			
			
			
			}
			
		else if($ssb_share_content_themes=='flat' || $ssb_share_content_themes=='round'|| $ssb_share_content_themes=='wide')
			{
				if($ssb_share_total_count_display == 'yes')
					{
						$ssb_share_icons.= "<a class='total-count' href='#' >Total Share: ".ssb_total_share_count_by_postid()."</a>";
					}
				
				
				
				foreach($ssb_social_sites as $icon)
					{
						if(!empty($ssb_social_sites[$icon]))
							{
								if($ssb_social_sites[$icon] == $icon)
									{
										$url = $ssb_social_sites_domain_url[$icon];
										
										$url = str_replace("{title}",ssb_share_get_title(), $url);
										$url = str_replace("{url}",ssb_share_get_url(), $url);
										$url = str_replace("{thumb_url}",ssb_share_get_image(), $url);																				
										
										
										$icon_bg = $ssb_social_sites_domain_icon[$icon];
										if(!empty($icon_bg))
											{
												$custom_style = 'style=background-image:url('.$icon_bg.')';
											}
										else
											{
												$custom_style = '';
											}
										
										$ssb_share_icons.= "<a ".$custom_style."  ".$ssb_share_target_tab." class='".$icon."' href='".$url."' ><span class='icon'></span><span class='count'>".$ssb_count[$icon]."</span></a>";
									}
							}

					}
				

					
					
			}
		else if($ssb_share_content_themes=='bodyname')
			{
				if($ssb_share_total_count_display == 'yes')
					{
				$ssb_share_icons.= "<a class='total-count' href='#' >Total Share: ".ssb_total_share_count_by_postid()."</a>";
					}
				foreach($ssb_social_sites as $icon)
					{
						if(!empty($ssb_social_sites[$icon]))
							{
								if($ssb_social_sites[$icon] == $icon)
									{
										$url = $ssb_social_sites_domain_url[$icon];
										
										$url = str_replace("{title}",ssb_share_get_title(), $url);
										$url = str_replace("{url}",ssb_share_get_url(), $url);
										$url = str_replace("{thumb_url}",ssb_share_get_image(), $url);	
																													
										$icon_bg = $ssb_social_sites_domain_icon[$icon];
										if(!empty($icon_bg))
											{
												$custom_style = 'style=background-image:url('.$icon_bg.')';
											}
										else
											{
												$custom_style = '';
											}
										
										$ssb_share_icons.= "<a  ".$ssb_share_target_tab." class='".$icon."' href='".$url."' ><span ".$custom_style." class='icon'></span><span class='body'>Share</span><span class='count'>".$ssb_count[$icon]."</span></a>";

									}
							}

					}



					
					
			}
			
		else if($ssb_share_content_themes=='packslide' || $ssb_share_content_themes=='hexa')
			{
				if($ssb_share_total_count_display == 'yes')
					{
						$ssb_share_icons.= "<a class='total-count' href='#' >Total Share: ".ssb_total_share_count_by_postid()."</a>";
					}
				foreach($ssb_social_sites as $icon)
					{
						if(!empty($ssb_social_sites[$icon]))
							{
								if($ssb_social_sites[$icon] == $icon)
									{
										$url = $ssb_social_sites_domain_url[$icon];
										
										$url = str_replace("{title}",ssb_share_get_title(), $url);
										$url = str_replace("{url}",ssb_share_get_url(), $url);
										$url = str_replace("{thumb_url}",ssb_share_get_image(), $url);																				

										$icon_bg = $ssb_social_sites_domain_icon[$icon];
										if(!empty($icon_bg))
											{
												$custom_style = 'style=background-image:url('.$icon_bg.')';
											}
										else
											{
												$custom_style = '';
											}






										$ssb_share_icons.= "<a ".$custom_style." ".$ssb_share_target_tab." class='".$icon."' href='".$url."' ><span  class='icon'></span><span class='count'>".$ssb_count[$icon]."</span></a>";
										
										
										
									}
							}

					}







			}	

			
			
		else if($ssb_share_content_themes=='hover-left' || $ssb_share_content_themes=='hover-right')
			{
				if($ssb_share_total_count_display == 'yes')
					{
				$ssb_share_icons.= "<a title='Total Share' class='total-count' href='#' >".ssb_total_share_count_by_postid()."</a><br>";
					}
				
				foreach($ssb_social_sites as $icon)
					{
						if(!empty($ssb_social_sites[$icon]))
							{
								if($ssb_social_sites[$icon] == $icon)
									{
										$url = $ssb_social_sites_domain_url[$icon];
										
										$url = str_replace("{title}",ssb_share_get_title(), $url);
										$url = str_replace("{url}",ssb_share_get_url(), $url);
										$url = str_replace("{thumb_url}",ssb_share_get_image(), $url);																				
										
										$icon_bg = $ssb_social_sites_domain_icon[$icon];
										if(!empty($icon_bg))
											{
												$custom_style = 'style=background-image:url('.$icon_bg.')';
											}
										else
											{
												$custom_style = '';
											}
										
										
										$ssb_share_icons.= "<a  ".$ssb_share_target_tab." class='".$icon."' href='".$url."' ><span ".$custom_style." class='icon'></span><span class='count'>".$ssb_count[$icon]."</span></a><br />";
										
									}
							}

					}
				
				
				
				
			}
			
	
			
			

		return $ssb_share_icons;
	}
?>