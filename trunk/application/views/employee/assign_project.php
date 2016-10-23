<script type="text/javascript">
	<!--
	var employee_id = <?php echo $employee_id ? $employee_id : 0; ?>;
	//-->
</script>
	<!-- table -->
	<div class="box">
	<?php if( $permission):?>
		<?php echo form_open('employee/save_assign', array('id' => 'form_assign'));?>
		<!-- box / title -->
		<div class="title">
			<h5>Assign Project</h5>
			<div class="search">
				<div class='input'><?php 
					$search_attr = array( 
						'name'=>'search',
						'class' => 'search_field');
					echo form_input($search_attr);
				?></div>
				<div class='button'><?php 
					$button_attr = array(
						'id' => 'button_search',
						'class' => 'ui-button ui-widget ui-state-default ui-corner-all cancel_button',
						'value' => 'Search');
					echo form_submit( $button_attr);
					echo form_hidden('employee_id', $employee_id);
				?></div>
			</div>
		</div>
		<?php if( $success_flag) echo '<div class="update-success">Assign successful!</div>' ?>
		<div id="assign-date">
			<?php 
			echo form_error('start_date');
			echo form_error('end_date'); ?>
			<label>Start time:</label>
			<?php
			echo form_input('start_date', NULL, 'class="datetime"');
			echo '<span style="color: red;">*</span>';
			?>
			<label>End time:</label>
			<?php echo form_input('end_date', NULL, 'class="datetime"');?>
			<?php echo form_error('project_id'); ?>
		</div>
		
		<div id="popup_assign_project">
			<?php if (isset($arr_project) && $arr_project) : ?>
				<?php $edit_attributes = array('class' => 'ui-button ui-widget ui-state-default ui-corner-all'); ?>
				<div id='assign_project'>
					<div class='line'></div>
					<div id="project_list">
						<?php $this -> load -> view('employee/popup_content'); ?>
					</div>
				</div>
				<div id="apply-buttons"><?php
					$apply_att = array('class' => 'apply-buttons ui-button ui-widget ui-state-default ui-corner-all', 'name' => 'apply', 'value'=>'Apply');
					$cancel_att = array(
						'name' => 'cancel', 
						'class' => 'apply-buttons cancel_button ui-button ui-widget ui-state-default ui-corner-all', 
						'value' => 'Cancel', 
						'onclick' => "window.location='" . base_url('employee/employee_detail/' . $employee_id) . "'");
					echo form_submit($apply_att);
					echo form_submit($cancel_att);
				?></div>

			<?php else : ?>
				<h3>Search not found!</h3>
			<?php endif; ?>
		</div>
		<?php echo form_close(); ?>
	<?php else:
			echo "You don't have permission to access.";
		endif;?>
	</div>
