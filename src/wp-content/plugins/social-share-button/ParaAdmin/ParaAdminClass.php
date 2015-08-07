<?php

// ParaAdmin Class

class paraAdmin
	{
		var $options = array();
		
		
		function option_output($options_all, $options_tabs)
			{
				$html = '';
				$html .= '<div class="para-settings">';
				
				$html .= '<ul class="tab-nav">';	
				
				$i=1;			
				foreach($options_tabs as $id => $tabs)
					{
						if($i==1)
							{
								$active = 'active';
							}
						else
							{
								$active = '';
							}
							
						$html.= '<li nav="'.$i.'" class="tab'.$i.' '.$active.' ">'.$tabs.'</li>';
						
						$i++;
					}
				$html .= '</ul>';
				
				
								
				$html .= '<ul class="box">';
				
				$j = 1;
				foreach($options_tabs as $id => $tabs)
					{
						if($j==1)
							{
								$active = 'active';
								$display = 'block';								
							}
						else
							{
								$active = '';
								$display = 'none';	
							}
						$html.= '<li style="display: '.$display.';" class="box'.$j.' tab-box '.$active.'">';
							foreach($options_all[$id] as $id => $options)
								{
									foreach($options as $option)
									
									$css_class = $options['css_class'];						
									$title = $options['title'];
									$option_details = $options['option_details'];						
									$input_type = $options['input_type'];						
									$input_values = $options['input_values'];						
									
									$html.= '<div class="option-box">';
									
									$html.= '<p class="option-title">'.$title.'</p>';
									$html.= '<p class="option-info">'.$option_details.'</p>';
									
									$html.= $this->input_type($input_type, $input_values, $id, $css_class);
									
									$html.= '</div>';
									
								}
						$html.= '</li>';						
						
						$j++;
					}
				$html .= '</ul>';
							
							
							
							
										

					
					
				$html .= '</div>';
				
				
				return $html;
				
			}
			
			
		function input_type($input_type, $input_values, $id, $css_class)
			{

					
				
				$html ='';
				if($input_type == 'text')
					{
								
						$option_id_value = get_option( $id );
							if(empty($option_id_value))
								{
									$option_id_value = '';
								}
						
						
						
						$html.= '<input name="'.$id.'" type="text" value="'.$option_id_value.'" id="'.$id.'" class="'.$css_class.'" />';
					}
					
				elseif($input_type == 'textarea')
					{
								
						$option_id_value = get_option( $id );
						
						if(!empty($option_id_value))
							{
								$value = $option_id_value;
							}
						else
							{
								$value = $input_values;
							}
						
						
						
						$html.= '<textarea name="'.$id.'" type="text" id="'.$id.'" class="'.$css_class.'" >'.$value.'</textarea>';
					}					
					
					
					
					
					
				elseif($input_type == 'checkbox')
					{	
					
						foreach($input_values as $key => $value)
							{
								
								
								
							$option_key_value = get_option( $key );
								if(empty($option_key_value))
									{
										$option_key_value = '';
										$checked = '';
									}
								else
									{
										$checked = 'checked';
									}
								
								
								
								$html.= '<label>';
								
								$html.= '<input name="'.$key.'" type="checkbox" '.$checked.' value="1" id="'.$key.'" class="'.$css_class.'" /> '.$value;
								$html.= '</label><br />';
							}
					

					}
					
				elseif($input_type == 'select')
					{
						
						
						$html.= '<select name="'.$id.'" id="'.$id.'" class="'.$css_class.'">';
							foreach($input_values as $key => $value)
								{
									
									
									$option_id_value = get_option( $id );
										if($option_id_value == $key )
											{
												$selected = 'selected';
												
											}
										else
											{
												$selected = '';
											}	
									
									
									
									$html.= '<option '.$selected.'  value="'.$key.'" >'.$value.'</option>';
								}
						$html.= '</select>';
						
					}
					
					
				elseif($input_type == 'radio')
					{

						foreach($input_values as $key => $value)
							{
								$html.= '<label>';

								$option_id_value = get_option( $id );
								if($option_id_value == $key )
									{
										$checked = 'checked';
										
									}
								else
									{
										$checked = '';
									}
								
								
								
								
								$html.= '<input '.$checked.'  class="'.$css_class.'" id="'.$key.'" type="radio" name="'.$id.'" value="'.$key.'" >'.$value.'</option> ';
								$html.= '</label><br />';
							}

						
					}					
					
					
					
									
									
				
				
				return $html;
			}
			
			
			
			
			
			
			
			
		
		
		
	}
	
	
