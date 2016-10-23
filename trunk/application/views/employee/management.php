<!-- content -->
<div id="content">
	<!-- table -->
	<div class="box">
		<!-- box / title -->
		<div class="title">
		<h5><?php echo $heading; ?> </h5>
			<div class="search">
				<?php
				if( isset($mess)) echo $mess;
				echo form_open('employee/search_employee');
				?>
				<div class="input">
					<?php 
					$search_string = isset($search_string)?$search_string:NULL;
					$attr = array(
						'name'=>'search_string',
						'id'=>'search_string',
						'value'=> $search_string);
					?>
					<?php echo form_input($attr); ?>
				</div>	
				<div class="button">
					<?php
					$attributes = array('class' => 'ui-button ui-widget ui-state-default ui-corner-all');
					echo form_submit($attributes, 'Search');
					?>
				</div>
				<?php
				echo form_close();
				?>
			</div>
		</div>
		<!-- end box / title -->	
		<div class="table">
			<?php if(isset($not_found) && $not_found):?>
				<h3>Search not found!</h3>
			<?php else: ?>
			<table>
				<thead>
					<tr>
						<th>Photo</th>
						<th>Name</th>
						<th>Email</th>
						<th>Hire Date</th>
						<th>Left date</th>
						<th>Action</th>
					</tr>
				</thead>	
				<?php foreach ( $vals as $val):
					?>
				<tbody>
					<tr>
						<td class="photo">
							<a href="<?php echo base_url('employee/employee_detail/' . $val['employee_id']); ?>" class="img">
								<img src="<?php echo (!empty($val['photo']) ? base_url( '/common/uploads/'.$val['photo']): base_url( '/common/uploads/noimage2.png')); ?>"/>
							</a>
						</td>
						<td class="name"><?php echo $val['name']?></td>
						<td class="email"><?php echo $val['email']?></td>
						<td class="category"><?php echo $val['hire_date'];?></td>
						<td class="category"><?php echo $val['left_date'];?></td>
						<td class="action-employee">
							<a href="<?php echo base_url('employee/employee_detail/' . $val['employee_id']); ?>" class="bt_detail">
								<img class="action" src="<?php echo base_url(); ?>/common/images/info.png" alt="detail" height="25" width="25" /></a>
<?php if( $this->emp_auth->is_admin() || $this->emp_auth->is_root()):?>
							<a href="<?php echo base_url('employee/update_employee/' . $val['employee_id']); ?>" class="bt_update">
								<img class="action" src="<?php echo base_url(); ?>/common/images/update.png" alt="edit" height="23" width="23" /></a>
							<?php if($this->emp_auth->is_root() || ($this->emp_auth->is_admin() && $val['role'] == 0)):?>
							<a href="<?php echo base_url('employee/delete_profile/' . $val['employee_id']); ?>" class="bt_delete">
								<img class="action" src="<?php echo base_url(); ?>/common/images/delete.png" alt="delete" height="23" width="23" /></a>
							<?php endif;?>
<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div class="pagination pagination-left">
				<ul class="pager">
					<?php echo $pagitation; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
		<!-- end table -->
	</div>	
</div>
<!-- end content -->