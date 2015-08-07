
jQuery(document).ready(function($)
	{
		
		
		
		$(document).on('click', '.ssb_social_sites_domain_icon', function()
			{	
			var social_icon_url = prompt("Please insert icon url","");
			if(social_icon_url != null)
				{
					var icon_name = $(this).attr("icon-name");	
							
					$(this).css("background-image",'url('+social_icon_url+')');
						
					$(".ssb_social_sites_domain_icon_"+icon_name).val(social_icon_url);
				}



			})
		
		
		
		

		$(document).on('click', '.ssb_social_sites_domain_remove', function()
			{	
				if (confirm('Do you really want to delete this field ?')) {
					
					$(this).parent().parent().remove();
				}
			})

		$(document).on('click', '.ssb_social_sites_domain_add', function()
			{
				var social_domain = prompt("Please add new social site","");
				
				if(social_domain != null && social_domain != '')
					{
						$(".ssb_social_sites_domain").append('<tr><td>*</td><td><input type="text" name="ssb_social_sites_domain['+social_domain+']" value="'+social_domain+'"  /></td><td width="450"><input  style="width:100%" type="text" name="ssb_social_sites_domain_url['+social_domain+']" value=""  /></td><td><span class="ssb_social_sites_domain_remove">X</span></td></tr>');
					}

		
			})















		// will be using for trace stats
		
		$(document).on('click', '.ssb-share a', function()
			{
				
				var ssb_site = $(this).attr('class');
				var post_id = $(this).parent().attr("post_id");
				
				var count = parseInt($(this).children('.count').text());
				
				


				$.ajax(
					{
					type:"POST",
					url:ssb_ajax.ssb_ajaxurl,
					data:{action:"ssb_ajax_form",ssb_site:ssb_site,post_id:post_id},
					success:function(data)
						{
							$('.ssb-share-'+post_id+' a.'+ssb_site).children('.count').text(count+1);
							$('.ssb-share-'+post_id+' a.'+ssb_site).prop('disabled',true);
							$('.ssb-share-'+post_id+' a.'+ssb_site).css('cursor','not-allowed');
						}
					})
		});
		
		

		
	
 		

	});	







