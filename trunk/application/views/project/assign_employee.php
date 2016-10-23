<!-- content -->
<div id="content">
<input type="hidden" name="project_detail_start_date" id="project_detail_start_date" value="<?php echo $project['start_date'];?>"/>
<input type="hidden" name="project_detail_end_date" id="project_detail_end_date" value="<?php echo $project['end_date'];?>"/>
<!-- table -->
	<div class="box">
	<!-- box / title -->
    <div class="title">
    	<h5>Assign Employees</h5>
    	<div class="search">
    		<?php // echo $message; ?>
    		<?php echo form_open('project/listEmployee'); ?>
        <?php 
           $keyword_employees = null;
           if(isset($keyword_employee)){
             $keyword_employees = $keyword_employee;
           }
         ?>
        <div class="input">
        	<input type="text" id="keyword_employee" name="keyword_employee" value="<?php echo $keyword_employees; ?>"/>
        </div>
        <div class="button">
        		<input class="ui-button ui-widget ui-state-default ui-corner-all" type="submit" name="submit" value="Search" />
        </div>
       <?php echo form_close(); ?>
    	</div>
    </div>
    
    <div class="table">
	    <div class="project-assign-employee">
			<table>
				<thead>
					<tr>
						<th>Project name</th>
						<th>Start date</th>
						<th>End date</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $project['name'];?></td>
						<td><?php echo date('d-m-Y',strtotime($project['start_date']));?></td>
						<td><?php echo date('d-m-Y',strtotime($project['end_date']));?></td>
					</tr>
				</tbody>
			</table>
		</div>
     <?php $attr = array('name' => 'assignmentForm');?>
     <?php echo form_open('project/assignEmployee', $attr); ?>
		  <div class="assign-employee-date">
	    	<label>Start date:</label> <input readonly="readonly" type="text" name="employee_start_date" id="employee_start_date"/>
	    	<label>End date: </label><input readonly="readonly" type="text" name="employee_end_date" id="employee_end_date"/>
	    </div>
	    <!-- div table class -->
	    <div class="table">
	      <table>
	      	<thead>
	          <tr>
	            <th></th>
	            <th>Employee name</th>
	            <th>Skills</th>
	            <th>Rank</th>
	          </tr>
	      	</thead>
	      	<?php foreach ($Items as $key => $value) {
	              $employee_id = $value['employee_id'];
	              $employee_name = $value['name'];
	              $skill = $value['skill'];
	              $rankstr = '';
	              foreach ($rank as $rank_key => $rank_value){
	                if($rank_value['rank_id'] == $value['rank']){
	                  $rankstr = $rank_value['name'];
	                }
	              }
	        ?>
	      	<tbody>
	      		<tr>
	            <td align="center"><input type="checkbox" name="employeeId[]" class="employeeId" value="<?php echo $employee_id; ?>"/></td>
	            <td align="center"><?php echo $employee_name; ?></td>
	            <td width="40%"><?php echo $skill; ?></td>
	            <td align="center"><?php echo $rankstr; ?></td>
	      		</tr>
	      	</tbody>
	      	<?php } ?>
	      </table>
		  <!-- end div table class -->
      </div>
	<!-- pagination -->
      <div class="pagination pagination-left">
      	<ul class="pager">
      		<?php echo $this->pagination->create_links(); ?>
      	</ul>
      </div>
			<!-- end pagination -->
     <div>
       <input type="hidden" name="typeUser" value="employee"/>
       <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>" />
       <div id="assign-buttons">
         <a onclick="//javascript:assignEmployee();" href="javascript:void(0);" id='assignEmployee'><input class="ui-button ui-widget ui-state-default ui-corner-all" type="button" value="Assign"></a>
         <a href="<?php echo site_url(array('project', 'detail', $project['project_id'])); ?>"><input class="ui-button ui-widget ui-state-default ui-corner-all" type="button" value="Cancel"></a>
       </div>
     </div>
    </div>
    <?php echo form_close(); ?>
	<!-- end table -->
</div>
</div>
<!-- end content -->
