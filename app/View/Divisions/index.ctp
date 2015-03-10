<div class="divisions index">
	<h2><?php echo __('List of Divisions under RERMP2'); ?>
	<?php echo $this->Html->link(__('Add New Division'), array('action' => 'add'),array('class'=>'btn btn-large btn-success pull-right')); ?>
	</h2>
	<table class="table table-bordered">
	<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th>Additional Info.</th>
			<th><?php echo $this->Paginator->sort('createdby','Created By'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($divisions as $division): ?>
	<tr>
		<td><?php echo h($division['Division']['name']); ?>&nbsp;</td>
		<td><?php echo h($division['Division']['description']); ?>&nbsp;</td>
		<td>
			<?php
				if($division['Division']['createdby']==1){
					echo "Created By System";
				}else{
				echo $this->Html->link($this->element('userinfo',array('what'=>'name','id'=>(int)$division['Division']['createdby'])), array('controller' => 'users', 'action' => 'info',$division['Division']['createdby']));
				}
			?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Overview'), array('action' => 'overview', $division['Division']['id']),array('class'=>'btn btn-info')); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $division['Division']['id']),array('class'=>'btn btn-warning')); ?>
			<!--<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $division['Division']['id']), null, __('Are you sure you want to delete # %s?', $division['Division']['id'])); ?>-->
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
