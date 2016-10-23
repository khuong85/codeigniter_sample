<!-- content -->
<div id="content">
	<!-- table -->
	<div class="box">
		<!-- box / title -->
		<div class="title">
			<h5><?php echo $heading; ?> </h5>
		</div>
		<!-- end box / title -->
			<?php 
			$EM_path = 'employee';
			if(isset($error_message) && $error_message['type'] == 'image')
				echo '<div class="update-top-error">' . $error_message['message'] . '</div>';
			if( $success_flag == TRUE)
				echo '<div class="negative-top-success">Update profile successfully.</div>';
			?>
			<div class="form">
			<?php if( isset($permission) && $permission):
				$hire_date = $profile['hire_date'];
				$left_date = $profile['left_date'];
				$date_of_birth = $profile['date_of_birth'];
			?>
				<div class="fields">
					<?php echo form_open_multipart('employee/do_upload/' . $profile['employee_id']);?>
					<div class="edit-upload">
						<div>
							<?php
								echo (!empty($profile['photo']) ? img('common/uploads/' . $profile['photo']) : img('common/uploads/noimage2.png'));
								echo form_error('photo');
							?>
						</div>
						<div>	
							<input type="file" name="photo" size="20" />
							<input class="ui-button ui-widget ui-state-default ui-corner-all" type="submit" value="Upload" />
						</div>
					</div>
					<?php echo form_close();?>
					<!-- end edit-upload -->
				<?php echo form_open($EM_path . '/save_profile');?>
					<div class="field  field-first">
						<div class="label">
							<label for="input-small">Name:</label>
						</div>
						<div class="input">
							<?php 
								echo form_hidden('employee_id', $profile['employee_id']);
								echo form_hidden('photo', $profile['photo']);
								echo form_input('name', $profile['name']);
								echo form_error('name');
							?>
						</div>
					</div>
					<!-- end field-first -->
					<div class="field">
						<div class="label">
							<label for="input-small">Origin:</label>
						</div>
						<div class="input">
							<?php 
								echo form_input('origin', $profile['origin']);
								echo form_error('origin');
							?>
						</div>
					</div>
					<!-- end field -->
					<div class="field">
						<div class="label">
							<label for="input-small">Date of birth:</label>
						</div>
						<div class="input">
							<?php 
								if ( $date_of_birth == '0000-00-00')
									$date_of_birth = NULL;
								echo form_input('date_of_birth', $date_of_birth, 'class="datetime"') ;
								echo form_error('date_of_birth');
							?>
						</div>
					</div>
					<!-- end field -->
					<div class="field">
						<div class="label">
							<label for="input-large">Address:</label>
						</div>
						<div class="input">
							<?php
								$add_attributes = array('id' => 'input-large','class' => 'large', 'name' => 'address');
								echo form_input($add_attributes,$profile['address']);
								echo form_error('address');
							?>
						</div>
					</div>
					<!-- end field -->
					<div class="field">
						<div class="label">
							<label for="input-small">Phone:</label>
						</div>
						<div class="input">
							<?php
								echo form_input('phone', $profile['phone']);
								echo form_error('phone');
							?>
						</div>
					</div>
					<!-- end field -->
					<div class="field">
						<div class="label">
							<label for="input-small">Hire date:</label>
						</div>
						<div class="input">
							<?php
								if ( $hire_date == '0000-00-00')
									$hire_date = NULL;
								echo form_input('hire_date', $hire_date, 'class="datetime"');
							?>
							<?php echo form_error('hire_date');?>
						</div>
					</div>
					<!-- end field -->
					<div class="field">
						<div class="label">
							<label for="input-small">Left date:</label>
						</div>
						<div class="input">
							<?php
								if ( $left_date == '0000-00-00')
									$left_date = NULL;
								echo form_input('left_date', $left_date, 'class="datetime"');
								echo form_error('left_date');
							?>
						</div>
					</div>
					<!-- end field -->
					<div class="field">
						<div class="label">
							<label for="input-small">Title:</label>
						</div>
						<div class="input">
							<?php
								echo form_input('title', $profile['title']);
								echo form_error('title');
							?>
						</div>
					</div>
					<!-- end field -->
					<div class="email-field">
						<div class="label">
							<label for="input-small">Email:</label>
						</div>
						<div class="input">
							<?php
								echo form_label($profile['email']);
							?>
						</div>
					</div>
					<!-- end field -->
					<?php if( $this->emp_auth->is_themeself( $employee_id)):?>
					<div class="field">
						<div class="label">
							<label for="input-small">New password:</label>
						</div>
						<div class="input">
							<?php
								echo form_password('password', NULL);
								echo form_error('password');
							?>
						</div>
					</div>
					<?php endif;?>
					<!-- end field -->
					<div class="field">
						<div class="label">
							<label for="input-small">Skills:</label>
						</div>
						<div class="input">
							<?php
								$sk_attributes = array('name' => 'skill', 'style' => 'resize: none');
								echo form_textarea($sk_attributes, $profile['skill']);
								echo form_error('skill');
							?>
						</div>
					</div>
					<!-- end field -->
					<div class="field">
						<div class="label">
							<label for="input-small">Self-Introduction:</label>
						</div>
						<div class="input">
							<?php
								$intro_attributes = array('name' => 'self-introduction', 'style' => 'resize: none');
								echo form_textarea($intro_attributes, $profile['self-introduction']);
								echo form_error('self-introduction');
							?>
						</div>
					</div>
					<!-- end field -->
				<?php if( $this->emp_auth->is_root() && !$this->emp_auth->is_themeself()):?>
				<div class="email-field">
					<div class="label">
						<label for="input-small">Role:</label>
					</div>
					<div class="input">
						<?php
						$intro_attributes = array('name' => 'is-admin', 'style' => 'resize: none', 'checked' => $profile['is-admin'], 'value'=>TRUE);
						//echo form_checkbox($intro_attributes);
						$select_attr1 = array( 'name' => 'is-admin', 'id' => 'is-admin', 'value' => 1, 'checked' => ($profile['is-admin']==1));
						$select_attr2 = array( 'name' => 'is-admin', 'id' => 'is-member', 'value' => 0, 'checked' => ($profile['is-admin']==0));
						echo form_radio( $select_attr1) . '&nbsp;' . form_label('Admin', 'is-admin') . '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo form_radio( $select_attr2) . '&nbsp;' . form_label('Member', 'is-member');
						?>
					</div>
				</div>
				<!-- end field -->
				<?php endif; ?>
					<div class="buttons">
						<?php
							$last_url = $this->session->userdata('last_url');
							$edit_attributes = array('class' => 'ui-button ui-widget ui-state-default ui-corner-all');
							$url = base_url( $last_url?$last_url:'employee/management_panel/');
							$cancel_attr = array(
									'class'=>'ui-button ui-widget ui-state-default ui-corner-all cancel_button',
									'name'=>'cancel',
									'value'=>'Cancel',
									'onclick' => "window.location='" . $url . "'");
							echo form_submit($edit_attributes, 'Update');
							echo form_submit($cancel_attr);
							
							echo form_close();
						?>
					</div>
					<!-- end buttons -->
				</div>
				<!-- end fields -->
				<?php else:
					echo "You don't have permission to access.";
				endif;?>
			</div>
			<!-- end forms -->
	</div>	
	<!-- end table -->
</div>
<!-- end content -->