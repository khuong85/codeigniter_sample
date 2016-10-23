<?php if (!empty($evaluations)): ?>   
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
         <td class="title"><?php echo date('d-m-Y',strtotime($value['evaluated_date'])); ?></td>
          <?php if($isAccess === 1 || $isAccess === 4 || $isAccess === 5):?>      
        	 <td class="action-project"><a href="#" value="<?php echo $value['evaluate_id']."_".$value['rank_name']."_".$value['evaluated_date']."_".$this->session->userdata('name');?>" id="more_evaluation">
        	 <img class="action" src="<?php echo base_url();?>/common/images/info.png" alt="delete" height="25" width="25" />
        	 </a></td>
         <?php else:?>
         	<td></td>
        	 <!-- <td><a href="#" id="need_more_role" class="transparent_class">More</a></td> -->
         <?php endif;?>
      </tr>
      <?php endforeach; ?>
   </table>
   <div id="ajax_evaluations">
      <?php echo $create_links_evaluations;?>
   </div>
</div>
<?php else:?>
   <table>
      <tr>You have no evaluations.</tr>
   </table>
<?php endif;?>