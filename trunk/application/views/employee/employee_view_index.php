<!-- View for dialog add-evaluation -->
<div id="dialog-add-evaluation" title="Add new Evaluation" style="display: none;">
   <?php
$attributes = array('id' => 'form-add-evaluation');
echo form_open('employee/insert_evaluation_member', $attributes);
      ?>
   <p class="validateTips_add_evaluation">All form fields are required.</p>
   <fieldset>
      <label for="dropdown_rank_id">Rank</label>
      <select id="dropdown_rank_id" name="dropdown_rank_id">
      </select>
      <br />
      <label for="date_evaluation">Date</label>
      <input type="text" name="date_evaluation" id="date_evaluation" class="text ui-widget-content ui-corner-all" />
      <label for="details">Detail</label><br />
      <?php
	$area_attr = array('name' => 'details', 'rows' => 10, 'cols' => 78, 'id' => 'details', 'class' => 'text ui-widget-content ui-corner-all');
	echo form_textarea($area_attr);
      ?>
   </fieldset>
   <?php echo form_close(); ?>
</div>
<!-- End view for dialog add-evalution -->

<!-- More for dialog add-evaluation -->
	<div id="dialog-more-evaluation" title="More evaluation" style="display: none;">
		<?php
			$attributes = array('id' => 'form-more-evaluation');
			echo form_open('employee/more_evaluation', $attributes);
		?>
		<fieldset>
		
			<div class="field-item">
				<div class="label">
					<label for="rank">Rank</label>
				</div>
				<div id="rank" class="input"> </div>
			</div>
			<div class="field-item">
				<div class="label">
					<label for="evaluted_date">Evaluated Date</label>
				</div>
				<div id="evaluted_date" class="input"></div>
			</div>
			<div class="field-lastitem">
				<div class="label">
					<label for="detail_evaluation">Detail</label>
				</div>
				<div id="detail_evaluation" class="input"></div>
			</div>
			
		</fieldset>
		<?php echo form_close(); ?>
	</div>
<!-- End View for more evaluation -->

<!-- View for edit evaluation -->
<div id="dialog-edit-evaluation" title="Edit Evaluation" style="display: none;">
   <?php
$attributes = array('id' => 'form-edit-evaluation');
echo form_open('employee/edit_evaluation_member', $attributes);
      ?>
   <fieldset>
      <label for="dropdown_rank_id_edit">Rank</label>
      <select id="dropdown_rank_id_edit" name="dropdown_rank_id_edit">
      </select>
      <br />
      <label for="date_evaluation_edit">Date</label>
      <input type="text" name="date_evaluation_edit" id="date_evaluation_edit" class="text ui-widget-content ui-corner-all" />
      <label for="details_edit">Detail</label>
      <?php
	$area_attr = array('name' => 'details_edit', 'rows' => 10, 'cols' => 78, 'id' => 'details_edit', 'class' => 'text ui-widget-content ui-corner-all');
	echo form_textarea($area_attr);
      ?>
   </fieldset>
   <?php echo form_close(); ?>
</div>
<!-- End View for edit evaluation -->

<!-- end content / left -->
<div id="left">
	<div class="box">
		<div class="title">
			<h5><?php
			switch ($user_info['role']) {
				case '1' :
					$str = ' (admin)';
					break;

				case '2' :
					$str = ' (super admin)';
					break;

				default :
					$str = '';
					break;
			}
 ?>
				Profile: <?php echo $user_info['name'] . $str; ?>
			</h5>
		</div>
	      <div id="prof">
	         <img alt="image user" width="80px" height="80px" src="<?php echo(empty($user_info['photo']) ? base_url('/common/uploads/noimage2.png') : base_url('/common/uploads/' . $user_info['photo'])); ?>" />
	         
	         <?php if($isAccess === 1 || $isAccess === 2 || $isAccess === 4 || $isAccess === 5):?>      
	         <a href="<?php echo base_url()."employee/update_employee/".$user_info['employee_id'] ?>">Update</a>
	         <?php endif; ?>
	         <?php if(($this->emp_auth->is_admin() || $this->emp_auth->is_root()) && $user_info['role'] == 0 ):?>
	         <a href="#" id="delete_profile" value="<?php echo $user_info['employee_id']; ?>">Delete</a>
	         <?php endif; ?>
	         <?php
			$intro = $user_info['self-introduction'];
			$x = 100;
			$intro_len = strlen($intro);

			if ($intro_len > $x) {
				$intro_content = substr($intro, 0, $x);
				$intro = '<span id="intro_less">' . $intro_content . '</span><span id="intro_more" class="intro_textfull">' . $intro . '</span>
	         		<br><span class="intro_more">Show More</span>';
			}
			echo '<div class="intro_content">' . $intro . '</div><br>';
	         ?>
	      </div>
	      <ul>
	         <li>
	            <label>Origin: </label>
	            <?php echo $user_info['origin']; ?>
	         </li>
	         <li>
	            <label>Skill: </label>
	            <?php
				$skill = $user_info['skill'];
				$skill_len = strlen($skill);
				if ($skill_len > $x) {
					$skill_content = substr($skill, 0, $x);
					$skill = '<span id="skill_less">' . $skill_content . '</span><span id="skill_more" class="skill_textfull">' . $skill . '</span>
	         		<br><span class="skill_more">Show More</span>';
				}
				echo '<div class="skill_content">' . $skill . '</div><br>';
	            ?>
	         </li>
	         <li>
	            <label>Phone: </label>
	            <?php echo $user_info['phone']; ?>
	         </li>
	         <li>
	            <label>Email: </label>
	            <?php echo $user_info['email']; ?>
	         </li>
	         <li>
	            <label>Title: </label>
	            <?php echo $user_info['title']; ?>
	         </li>
	         <li>
	            <label>Birthday: </label>
	            <?php echo(!empty($user_info['date_of_birth']) ? date('d-m-Y', strtotime($user_info['date_of_birth'])) : ''); ?>
	         </li>
	         <li>
	            <label>Address: </label>
	            <?php echo $user_info['address']; ?>
	         </li>
	         <li>
	            <label>Hire date: </label>
	            <?php echo(!empty($user_info['hire_date']) ? date('d-m-Y', strtotime($user_info['hire_date'])) : ''); ?>
	         </li>
	      </ul>
	</div> 
</div>
<!-- end content / left -->

<!-- content / right -->
<div id="right">
   <!-- table -->
   <div class="box">
      <!-- box / title -->
      <div class="title">
         <h5>Project Assignment</h5>
         <div class="search">
            <div class="button">
               <?php if($isAccess === 1 || $isAccess === 2 || $isAccess === 4 || $isAccess === 5):?>  
               <input class="ui-button ui-widget ui-state-default ui-corner-all" type="button" id="assign_project" onclick="window.location='<?php echo base_url() . "employee/assign_project/" . $user_info['employee_id']; ?>'" value="Assign project" />
               <?php endif; ?>
            </div>
         </div>
      </div>
      <!-- end box / title -->
      <!-- table -->
      <?php if(!empty($list_assignments)):?>
      <div id="content_project_assignments" class="table">
         <table>
            <thead>
               <tr>
                  <th>Project Name</th>
                  <th>Num People</th>
                  <th>Assigned Date</th>
                  <th class="selected last">Action</th>
               </tr>
            </thead>
            <?php foreach ($list_assignments as $value): ?>	
            <tr>
               <td class="title"><?php echo $value['name']; ?></td>
               <td class="price"><?php echo $value['SL']; ?></td>
               <td class="category"><?php echo date('d-m-Y', strtotime($value['start_date'])); ?></td>
               <?php if($isAccess === 1 || $isAccess === 4 || $isAccess === 5):?>      
               <td class="action-project"><a href="#" id="delete_project_assignment" value="<?php echo $value['assignment_id']; ?>">
               		<img class="action" src="<?php echo base_url(); ?>/common/images/delete.png" alt="delete" height="23" width="23" />
               		</a></td>
               <?php else: ?>
               <td></td>
               <!-- <td><a href="#" id="need_more_role" class="transparent_class">Delete</a></td> -->
               <?php endif; ?>
            </tr>
            <?php endforeach; ?>
         </table>
         <div id="ajax_project_assignments">
            <?php echo $create_links_project_assignments; ?>
         </div>
      </div>
      <?php else: ?>	
      <div id="content_project_assignments" class="table">
         <table>
            <tr>There is no project assignments.</tr>
         </table>
      </div>
      <?php endif; ?>
   </div>
   <!-- end table -->
   <?php if( $this->emp_auth->is_root() || $this->emp_auth->is_admin() || $this->emp_auth->is_themeself($user_info['employee_id'])): ?>
   <div class="box">
      <!-- box / title -->
      <div class="title">
         <h5>Evaluation List</h5>
         <div class="search">
            <div class="button">
               <?php if($isAccess ===1 || $isAccess ===4 || $isAccess===5):?>
               <!-- <a style="float:right" id="add_evaluation" value="<?php echo $this->session->userdata('name');?>" href="#">Evaluate Member</a>  -->
               <input class="ui-button ui-widget ui-state-default ui-corner-all" type="button" id="add_evaluation" name="<?php echo $this -> session -> userdata('name'); ?>" value="Evaluate member" />
               <?php endif; ?>
            </div>
         </div>
      </div>
      <!-- end box / title -->
	      <?php if (!empty($evaluations)): ?>   
	      <div id="content_evaluations" class="table">
	         <table>
	            <thead>
	               <tr>
	                  <th>Rank</th>
	                  <th>Evaluated Date</th>
	                  <th class="selected last">Action</th>
	               </tr>
	            </thead>
	            <?php foreach ($evaluations as $value): ?>
	            <tr>
	               <td class="price"><?php echo $value['rank_name']; ?></td>
	               <td class="title"><?php echo date('d-m-Y', strtotime($value['evaluated_date'])); ?></td>
	               <?php if($isAccess ===1 || $isAccess===4 || $isAccess ===5):?>    
	               <td class="action-project"><a href="#" value="<?php echo $value['evaluate_id'] . "_" . $value['rank_name'] . "_" . $value['evaluated_date'] . "_" . $this -> session -> userdata('name'); ?>" id="more_evaluation">
	               <img class="action" src="<?php echo base_url(); ?>/common/images/info.png" alt="delete" height="25" width="25" />
	               </a></td>
	               <?php else: ?>
	               <!-- <td><a href="#" id="need_more_role" class="transparent_class">More</a></td> -->
	               <td></td>
	               <?php endif; ?>
	            </tr>
	            <?php endforeach; ?>
	         </table>
	         <div id="ajax_evaluations">
	            <?php echo $create_links_evaluations; ?>
	         </div>
	      </div>
	      <?php else: ?>
	      <div id="content_evaluations" class="table">
	         <table>
	            <tr>There is no evaluations.</tr>
	         </table>
	      </div>
	      <?php endif; ?>
      <!-- end table -->
   </div>
	<?php endif; ?>
</div>
<!-- end content / right -->