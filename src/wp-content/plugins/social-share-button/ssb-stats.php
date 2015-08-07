<?php	
/**
 * Social Share Button main setting page.
 *
 * @author 		ParaTheme
 * @version     1.0
 */
	if ( ! defined('ABSPATH')) exit;




	$ssb_social_sites_domain = get_option( 'ssb_social_sites_domain' );
	$posts_per_page = get_option( 'posts_per_page' );
	$ssb_share_filter_posttype = get_option( 'ssb_share_filter_posttype' );
	
	// get the selected post types as array
	$i = 0;
	foreach($ssb_share_filter_posttype as $key=>$value)
		{
			$post_types[$i] = $key; 
			$i++;
		}
	
	
		
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

	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".ssb_plugin_name." Stats</h2>";?>

       
	<br />
	<div class="para-settings ssb-settings">
        <ul class="tab-nav">
            <li nav="1" class="nav1 active">SSB Stats</li>

        
        </ul>

        <ul class="box">
            <li style="display: block;" class="box1 tab-box active">
                


				<div class="option-box">
                    <p class="option-title">Stats for share by post</p>
                    <p class="option-info"></p>
					<?php

					
					if(isset($_GET['paged']))
						{
							$paged =(int)$_GET['paged'];
						}
					else
						{
						$paged =1;
						}
                  	
					
					
					global $wp_query;
					
					$args = 	array (
							'post_type' => $post_types,
							'post_status' => 'publish',
							'posts_per_page' => $posts_per_page,
							'paged' => $paged,
							
							);
					
					
					$wp_query = new WP_Query($args);
					if ( $wp_query->have_posts() ) :
					echo '<table class=" widefat">';
					echo '<thead><tr>';
					echo '<th ><strong>Title<strong></th>';
					foreach($ssb_social_sites_domain as $site)
						{
							echo '<th ><strong>'.$site.'<strong></th>';
						}
					echo '</tr><thead>';
					
					while ( $wp_query->have_posts() ) : $wp_query->the_post();
					
					$ssb_post_sites = get_post_meta( get_the_ID(), 'ssb_post_sites', true );
					
					
					echo '<tr><td>'.get_the_title().'</td>';

					foreach($ssb_social_sites_domain as $site)
						{
							if(empty($ssb_post_sites[$site]))
								{
									$ssb_post_sites[$site] = 0;
								}
							echo '<td>'.$ssb_post_sites[$site].'</td>';
						}


					
										
					echo '</tr>';					
					
					
					
					
					endwhile;
					echo '</table>';
					
					echo '<div class="tablenav">';					
					echo '<div class="tablenav-pages">';
					
					$big = 999999999; // need an unlikely integer
					echo  paginate_links( array(
						'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format' => '?paged=%#%',
						'current' => max( 1, $paged ),
						'total' => $wp_query->max_num_pages
						) );
				
					echo '</div >';		
					echo '</div >';	
					
					wp_reset_query();

					
					endif;
		


		
		
		
		
					?>
                </div>

				





            </li>
            

            
                     
            

</ul>








	</div>

</div>
