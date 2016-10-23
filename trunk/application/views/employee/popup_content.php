<!-- table -->
<div class="box">	
	<div class="table">
		<table>
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
					<th>Start date</th>
					<th>End date</th>
					<th>Num people</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($arr_project as $project) : ?>
				<tr>
					<td class="category"><?php echo form_checkbox('project_id[]', $project['project_id'], FALSE, 'class="check_project"'); ?></td>
					<td class="title"><?php echo $project['name'] ?></td>
					<td class="category"><?php echo $project['start_date']; ?></td>
					<td class="category"><?php echo $project['end_date']; ?></td>
					<td class="category"><?php echo $project['num_people'] ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php if (isset($pagination) && $pagination) : ?>
		<?php echo $pagination; ?>
		<?php endif; ?>
	</div>
</div>
	
	

	