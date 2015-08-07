<?php 
/* 
 * Used for both multiple and single tickets. $col_count will always be 1 in single ticket mode, and be a unique number for each ticket starting from 1 
 * This form should have $EM_Ticket and $col_count available globally. 
 */
global $col_count, $EM_Ticket;
?>
<div class="em-ticket-form">
	<input type="hidden" name="em_tickets[<?php echo $col_count; ?>][ticket_id]" class="ticket_id" value="<?php echo esc_attr($EM_Ticket->ticket_id) ?>" />
	<div class="em-ticket-form-main">
		<div class="ticket-name">
			<label title="<?php __('Enter a ticket name.','dbem'); ?>"><?php _e('Name','dbem') ?></label>
			<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_name]" value="<?php echo esc_attr($EM_Ticket->ticket_name) ?>" class="ticket_name" />
		</div>
		<div class="ticket-description">
			<label><?php _e('Description','dbem') ?></label>
			<textarea name="em_tickets[<?php echo $col_count; ?>][ticket_description]" class="ticket_description"><?php echo esc_html(stripslashes($EM_Ticket->ticket_description)) ?></textarea>
		</div>
		<div class="ticket-price"><label><?php _e('Price','dbem') ?></label><input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_price]" class="ticket_price" value="<?php echo esc_attr($EM_Ticket->get_price_precise()) ?>" /></div>
		<div class="ticket-spaces">
			<label title="<?php __('Enter a maximum number of spaces (required).','dbem'); ?>"><?php _e('Spaces','dbem') ?></label>
			<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_spaces]" value="<?php echo esc_attr($EM_Ticket->ticket_spaces) ?>" class="ticket_spaces" />
		</div>
	</div>
	<div class="em-ticket-form-advanced" style="display:none;">
		<div class="ticket-spaces ticket-spaces-min">
			<label title="<?php _e('Leave either blank for no upper/lower limit.','dbem'); ?>"><?php _ex('At least','spaces per booking','dbem') ?></label>
			<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_min]" value="<?php echo esc_attr($EM_Ticket->ticket_min) ?>" class="ticket_min" />
			<?php _e('spaces per booking', 'dbem')?>
		</div>
		<div class="ticket-spaces ticket-spaces-max">
			<label title="<?php _e('Leave either blank for no upper/lower limit.','dbem'); ?>"><?php _ex('At most','spaces per booking', 'dbem') ?></label>
			<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_max]" value="<?php echo esc_attr($EM_Ticket->ticket_max) ?>" class="ticket_max" />
			<?php _e('spaces per booking', 'dbem')?>
		</div>
		<div class="ticket-dates em-time-range em-date-range">
			<div class="ticket-dates-from">
				<label title="<?php _e('Add a start or end date (or both) to impose time constraints on ticket availability. Leave either blank for no upper/lower limit.','dbem'); ?>">
					<?php _e('Available from','dbem') ?>
				</label>
				<div class="ticket-dates-from-normal">
					<input type="text" name="ticket_start_pub"  class="em-date-input-loc em-date-start" />
					<input type="hidden" name="em_tickets[<?php echo $col_count; ?>][ticket_start]" class="em-date-input ticket_start" value="<?php echo ( !empty($EM_Ticket->ticket_start) ) ? date("Y-m-d", $EM_Ticket->start_timestamp):''; ?>" />
				</div>
				<div class="ticket-dates-from-recurring">
					<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_start_recurring_days]" size="3" value="<?php if( !empty($EM_Ticket->ticket_meta['recurrences']) && is_numeric($EM_Ticket->ticket_meta['recurrences']['start_days'])) echo absint($EM_Ticket->ticket_meta['recurrences']['start_days']); ?>" />
					<?php _e('day(s)','dbem'); ?>
					<select name="em_tickets[<?php echo $col_count; ?>][ticket_start_recurring_when]" class="ticket-dates-from-recurring-when">
						<option value="before" <?php if( !empty($EM_Ticket->ticket_meta['recurrences']['start_days']) && $EM_Ticket->ticket_meta['recurrences']['start_days'] <= 0) echo 'selected="selected"'; ?>><?php echo sprintf(_x('%s the event starts','before or after','dbem'),__('Before','dbem')); ?></option>
						<option value="after" <?php if( !empty($EM_Ticket->ticket_meta['recurrences']['start_days']) && $EM_Ticket->ticket_meta['recurrences']['start_days'] > 0) echo 'selected="selected"'; ?>><?php echo sprintf(_x('%s the event starts','before or after','dbem'),__('After','dbem')); ?></option>
					</select>
				</div>
				<?php _ex('at', 'time','dbem'); ?>
				<input id="start-time" class="em-time-input em-time-start" type="text" size="8" maxlength="8" name="em_tickets[<?php echo $col_count; ?>][ticket_start_time]" value="<?php echo ( !empty($EM_Ticket->ticket_start) ) ? date( em_get_hour_format(), $EM_Ticket->start_timestamp):''; ?>" />
			</div>
			<div class="ticket-dates-to">
				<label title="<?php _e('Add a start or end date (or both) to impose time constraints on ticket availability. Leave either blank for no upper/lower limit.','dbem'); ?>">
					<?php _e('Available until','dbem') ?>
				</label>
				<div class="ticket-dates-to-normal">
					<input type="text" name="ticket_end_pub" class="em-date-input-loc em-date-end" />
					<input type="hidden" name="em_tickets[<?php echo $col_count; ?>][ticket_end]" class="em-date-input ticket_end" value="<?php echo ( !empty($EM_Ticket->ticket_end) ) ? date("Y-m-d", $EM_Ticket->end_timestamp):''; ?>" />
				</div>
				<div class="ticket-dates-to-recurring">
					<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_end_recurring_days]" size="3" value="<?php if( !empty($EM_Ticket->ticket_meta['recurrences']['end_days']) ) echo absint($EM_Ticket->ticket_meta['recurrences']['end_days']); ?>" />
					<?php _e('day(s)','dbem'); ?>
					<select name="em_tickets[<?php echo $col_count; ?>][ticket_end_recurring_when]" class="ticket-dates-to-recurring-when">
						<option value="before" <?php if( !empty($EM_Ticket->ticket_meta['recurrences']['end_days']) && $EM_Ticket->ticket_meta['recurrences']['end_days'] <= 0) echo 'selected="selected"'; ?>><?php echo sprintf(_x('%s the event starts','before or after','dbem'),__('Before','dbem')); ?></option>
						<option value="after" <?php if( !empty($EM_Ticket->ticket_meta['recurrences']['end_days']) && $EM_Ticket->ticket_meta['recurrences']['end_days'] > 0) echo 'selected="selected"'; ?>><?php echo sprintf(_x('%s the event starts','before or after','dbem'),__('After','dbem')); ?></option>
					</select>
				</div>
				<?php _ex('at', 'time','dbem'); ?>
				<input id="end-time" class="em-time-input em-time-end" type="text" size="8" maxlength="8" name="em_tickets[<?php echo $col_count; ?>][ticket_end_time]" value="<?php echo ( !empty($EM_Ticket->ticket_end) ) ? date( em_get_hour_format(), $EM_Ticket->end_timestamp):''; ?>" />
			</div>
		</div>
		<?php if( !get_option('dbem_bookings_tickets_single') || count($EM_Ticket->get_event()->get_tickets()->tickets) > 1 ): ?>
		<div class="ticket-required">
			<label title="<?php _e('If checked every booking must select one or the minimum number of this ticket.','dbem'); ?>"><?php _e('Required?','dbem') ?></label>
			<input type="checkbox" value="1" name="em_tickets[<?php echo $col_count; ?>][ticket_required]" <?php if($EM_Ticket->ticket_required) echo 'checked="checked"'; ?> class="ticket_required" />
		</div>
		<?php endif; ?>
		<div class="ticket-type">
			<label><?php _e('Available for','dbem') ?></label>
			<select name="em_tickets[<?php echo $col_count; ?>][ticket_type]" class="ticket_type">
				<option value=""><?php _e('Everyone','dbem'); ?></option>
				<option value="members" <?php if($EM_Ticket->ticket_members) echo 'selected="selected"'; ?>><?php _e('Logged In Users','dbem'); ?></option>
				<option value="guests" <?php if($EM_Ticket->ticket_guests) echo 'selected="selected"'; ?>><?php _e('Guest Users','dbem'); ?></option>
			</select>
		</div>
		<div class="ticket-roles" <?php if( !$EM_Ticket->ticket_members ): ?>style="display:none;"<?php endif; ?>>
			<label><?php _e('Restrict to','dbem'); ?></label>
			<div>
				<?php 
				$WP_Roles = new WP_Roles();
				foreach($WP_Roles->roles as $role => $role_data){ /* @var $WP_Role WP_Role */
					?>
					<input type="checkbox" name="em_tickets[<?php echo $col_count; ?>][ticket_members_roles][]" value="<?php echo $role; ?>" <?php if( in_array($role, $EM_Ticket->ticket_members_roles) ) echo 'checked="checked"' ?> class="ticket_members_roles" /> <?php echo $role_data['name']; ?><br />
					<?php
				}
				?>
			</div>
		</div>
		<?php do_action('em_ticket_edit_form_fields', $col_count, $EM_Ticket); //do not delete, add your extra fields this way, remember to save them too! ?>
	</div>
	<div class="ticket-options">
		<a href="#" class="ticket-options-advanced show"><span class="show"><?php _e('Show Advanced Options','dbem'); ?></span><span class="hide" style="display:none;"><?php _e('Hide Advanced Options','dbem'); ?></span></a>
	</div>
</div>	