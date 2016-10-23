<!--  End of page add new project -->
<div id="dialog-form" title="Add new project" style="display: none;">
  <?php
    $attributes = array('id' => 'appForm');
    echo form_open('project/add', $attributes);
  ?>
  <p class="validateTips">All form fields are required.</p>
    <fieldset>
      <label for="name">Project Name</label>
      <input type="text" name="name" id="name"  class="text ui-widget-content ui-corner-all" />
      <label for="start_date">Start Date</label>
      <input type="text" readonly="readonly" name="start_date" id="start_date" class="text ui-widget-content ui-corner-all" />
      <label for="end_date">End Date</label>
      <input type="text" readonly="readonly" name="end_date" id="end_date" class="text ui-widget-content ui-corner-all" />
    </fieldset>
  <?php echo form_close(); ?>
</div>
<!--  End of page add new project -->

<!--  End of page edit project -->
<div id="dialog-form-edit" title="Edit project" style="display: none;">
  <?php
    $attributes = array('id' => 'appFormEdit');
    echo form_open('project/edit', $attributes);
  ?>
  <input type="hidden" name="project_id" id="project_id" />
  <p class="validateTips">All form fields are required.</p>
    <fieldset>
      <label for="name">Project Name</label>
      <input type="text" name="project_name" id="project_name"  class="text ui-widget-content ui-corner-all" />
      <label for="start_date">Start Date</label>
      <input readonly="readonly" type="text" name="project_start_date" id="project_start_date" class="text ui-widget-content ui-corner-all" />
      <label for="end_date">End Date</label>
      <input readonly="readonly" type="text" name="project_end_date" id="project_end_date" class="text ui-widget-content ui-corner-all" />
    </fieldset>
  <?php echo form_close(); ?>
</div>
<!--  End of page edit project -->

<!-- content -->
<div id="content">
<!-- table -->
	<div class="box">
		<!-- box / title -->
    <div class="title">
    	<h5>Project Management</h5>
    	<div class="search">
    		<?php $attr = array('onsubmit' => 'return checkSearch()', 'name' => 'formSearch')?>
    		<?php echo form_open('project/index'); ?>
        <?php 
           $keywords = null;
           if(isset($keyword)){
             $keywords = $keyword;
           }
         ?>
        <div class="input">
        	<input type="text" id="keyword" name="keyword" value="<?php echo $keywords; ?>"/>
        </div>
    			<div class="button">
    				<input class="ui-button ui-widget ui-state-default ui-corner-all" type="submit" name="submit" value="Search" />
    			</div>
    		
    	</div>
    </div>
		<!-- end box / title -->

		<div class="table">
      <div class="add-new" <?php echo $role?>>
      	<input class="ui-button ui-widget ui-state-default ui-corner-all" onclick="addItem();" id="create-project" type="button" value="Add new project"/>
      </div>
		<div class="negative-top-success">
			<?php echo $message; ?>
		</div>
      <?php if(isset($NotFound) && $NotFound == 0):?>
      <h3>Search not found!</h3>
      <?php else:?>
      <table>
      	<thead>
          <tr>
          	<th>Project name</th>
          	<th>No of members</th>
          	<th>Start date</th>
          	<th>End date</th>
          	<th class="selected last">Action</th>
          </tr>
      	</thead>
      	<?php foreach ($Items as $key => $value) {
              $project_id = $value['project_id'];
              $project_name = $value['name'];
              $num_people = $value['numPeople'];
              $start_date = $value['start_date'];
              $end_date = $value['end_date'];
        ?>
      	<tbody>
      		<tr>
            <td class="title" id="name_<?php echo $project_id; ?>"><?php echo $project_name; ?></td>
            <td class="price" id="num_<?php echo $project_id; ?>"><?php echo $num_people; ?></td>
            <td class="category" id="start_<?php echo $project_id; ?>"><?php echo date('d-m-Y', strtotime($start_date)); ?></td>
            <td class="category" id="end_<?php echo $project_id; ?>"><?php echo date('d-m-Y',strtotime($end_date)); ?></td>
      			<td class="action-project">
                <a href="<?php echo site_url(array('project', 'detail', $project_id)); ?>">
                <img class="action" src="<?php echo base_url(); ?>/common/images/info.png" alt="detail" height="25" width="25" />
                  <!-- <input  onclick="" type="button" value="Detail"/> -->
                </a>
                <!-- <input onclick="editItem(<?php echo $project_id; ?>);" type="button" value="Edit"/> -->
                <a <?php echo $role;?> onclick="editItem(<?php echo $project_id; ?>);">
                <img class="action" src="<?php echo base_url();?>/common/images/update.png" alt="edit" height="23" width="23" />
                </a>
      				  <a <?php echo $role;?> onclick="return confirm('Do you want to delete this item?');" href="<?php echo site_url(array('project', 'delete', $project_id)); ?>">
      				    <!-- <input  onclick="return confirm('Do you want to delete this item?');" type="button" value="Delete"/> -->
      				    <img class="action" src="<?php echo base_url();?>/common/images/delete.png" alt="delete" height="23" width="23" />
      				  </a>
      			</td>
      		</tr>
      	</tbody>
      	<?php } ?>
      </table>
      <?php endif;?>
			<!-- pagination -->
      <div class="pagination pagination-left">
      	<ul class="pager">
      		<?php echo $this->pagination->create_links(); ?>
      	</ul>
      </div>
			<!-- end pagination -->
    <?php echo form_close(); ?>
	</div>
	</div>
	<!-- end table -->
</div>
<!-- end content -->
