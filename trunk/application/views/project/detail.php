<!-- content -->
<div id="content">
<!-- table -->
	<div class="box">
		<!-- box / title -->
		<div class="title">
			<h5>Project Detail</h5>
		</div>
		<!-- end box / title -->
		<div class="table">
		
		<div class="project-detail">
    	<table>
    		<thead>
    			<tr>
            <th>Project name:</th>
            <th>Start date</th>
            <th>End date</th>
            <th>No of members</th>
    			</tr>
    		</thead>
    		<tbody>
    			<tr>
            <td class="project-detail-name"><?php echo $project['name']; ?></td>
            <td class="project-detail-date" id="project_detail_start_date"><?php echo date('d-m-Y',strtotime($project['start_date'])); ?></td>
            <td class="project-detail-date" id="project_detail_end_date"><?php echo date('d-m-Y',strtotime($project['end_date'])); ?></td>
            <td class="project-detail-number"><?php echo $project['numPeople']; ?></td>
    			</tr>
    		</tbody>
    	</table>
    </div>
    <div class="table">
    
      <!--  Start of page assign employee -->
      <div id="assign-dialog-form" title="Assign your self for this project" style="display: none;">
        <?php
          $attributes = array('id' => 'assignForm');
          echo form_open('project/assignEmployee', $attributes);
          $strEmployeeId = $this->session->userdata('user_id');
        ?>
        <p class="validateTips">All form fields are required.</p>

          <fieldset>
            <label for="assign_start_date">Name</label>
            <input type="hidden" readonly="readonly" name="employeeId[]" id="employeeId" value="<?php echo $strEmployeeId; ?>" />
            <input type="hidden" name="project_id" value="<?php echo $project['project_id'];?>"/>
            <input type="hidden" name="typeUser" value="assign"/>
            <input readonly="readonly" type="text" name="assign_name" id="assign_name" value="<?php echo $this->session->userdata('name'); ?>"  class="text ui-widget-content ui-corner-all" />
            <label for="assign_start_date">Start Date</label>
            <input readonly="readonly" type="text" name="assign_start_date" id="assign_start_date" class="text ui-widget-content ui-corner-all" />
            <label for="assign_end_date">End Date</label>
            <input readonly="readonly" type="text" name="assign_end_date" id="assign_end_date" class="text ui-widget-content ui-corner-all" />
          </fieldset>
        <?php echo form_close();?>
       </div>
      <!--  End of page assign employee -->
      
      <?php
      $showAssignButton = true;
      if(count($project_assignment)){
        foreach ($project_assignment as $assignment_key => $assignment_value){
          if($strEmployeeId == $assignment_value['employee_id']){
            $showAssignButton = false;
          }
        }
      }
      ?>
      <?php if($showAssignButton){?>
      <div class="add-new" <?php echo $role_assign;?>>
         <input onclick="assignEmployee();" class="ui-button ui-widget ui-state-default ui-corner-all" id="create-project" type="button" value="Assign employee"/>
      </div>
      <?php }?>

      <div class="add-new" <?php echo $role; ?>>
      <?php echo form_open('project/listEmployee');?>
      <?php 
          if(count($project_assignment) > 0) {
            foreach ($project_assignment as $k => $val){
              $arrEmployeeId[] = $val['employee_id'];
            }
            $strEmployeeId = implode(',', $arrEmployeeId);
          }else{
            $strEmployeeId = null;
          }
      ?>
         <input type="hidden" name="strEmployeeId" id="strEmployeeId" value="<?php echo $strEmployeeId; ?>" />
         <input type="hidden" name="project_id" value="<?php echo $project['project_id'];?>"/>
         <input class="ui-button ui-widget ui-state-default ui-corner-all" id="create-project" type="submit" value="Assign employee"/>
      <?php echo form_close();?>
      </div>
      <div class="negative-top-success">
      <?php 
      if(isset($message)){
         echo $message;
      }
      ?>
      </div>
      <table>
      	<thead>
          <tr>
          	<th class="left">Picture</th>
          	<th>Employee name</th>
          	<th>Start date</th>
          	<th>End date</th>
          	<th <?php echo $role;?> class="selected last">Action</th>
          </tr>
      	</thead>
      	<?php foreach ($project_assignment as $key => $value) {
      	    $assignment_id = $value['assignment_id'];
      	    $employee_photo = 'noimage2.png';
      	    if(($value['photo']) != null){
      	      $employee_photo = $value['photo'];
      	    }
            $employee_name = $value['name'];
            $start_date = $value['start_date'];
            $end_date = $value['end_date'];
        ?>
      	<tbody>
      		<tr>
            <td class="photo"><img width="80" height="60" src="<?php echo base_url('/common/uploads/'. $employee_photo); ?>" alt="Employee Image"/></td>
            <td class="title"><?php echo $employee_name; ?></td>
            <td class="category"><?php echo (!empty($start_date) ? date('d-m-Y',strtotime($start_date)) : ''); ?></td>
            <td class="category"><?php echo (!empty($end_date) ? date('d-m-Y',strtotime($end_date)) : ''); ?></td>
            <td <?php echo $role; ?> class="action-project-detail">
            	  <a onclick="return confirm('Do you want to delete this item?');" href="<?php echo site_url(array('project','deleteProjectAssignment', $project['project_id'].'/'.$assignment_id)); ?>">
            	    <img class="action" src="<?php echo base_url();?>/common/images/delete.png" alt="delete" height="23" width="23" />
            	  </a>
            </td>
      		</tr>
      	</tbody>
      	<?php } ?> 
      </table>
			<!-- pagination -->
	      <div class="pagination pagination-left">
	      	<ul class="pager">
	      		<?php echo $this->pagination->create_links(); ?>
	      	</ul>
	      </div>
		  <!-- end pagination -->
	    </div>
		<!-- end table -->
	</div>
	</div>
</div>
<!-- end content -->
