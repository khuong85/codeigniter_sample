<?php if(!empty($list_assignments)):?>
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
         <td class="category"><?php echo date('d-m-Y',strtotime($value['start_date'])); ?></td>
          <?php if($isAccess === 1 || $isAccess === 4 || $isAccess === 5):?>      
	         <td class="action-project"><a href="#" id="delete_project_assignment" value="<?php echo $value['assignment_id'];?>">
	         <img class="action" src="<?php echo base_url();?>/common/images/delete.png" alt="delete" height="23" width="23" />
	         </a></td>
          <?php else:?>
          	 <td></td>
              <!--  <td><a href="#" id="need_more_role" class="transparent_class">Delete</a></td>-->         		 
          <?php endif;?>
         
      </tr>
      <?php endforeach; ?>
   </table>
   <div id="ajax_project_assignments">
      <?php echo $create_links_project_assignments;?>
   </div>
</div>
<?php else:?>	
   <table>
      <tr>You have no project assignments.</tr>
   </table>
</div>
<?php endif;?>