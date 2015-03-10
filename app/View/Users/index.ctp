<div class="users index">
	<h2><?php echo __('Users'); ?></h2>
	<table class="table table-bordered">
	<tr>
			<th style="width:150px;"><?php echo $this->Paginator->sort('name'); ?></th>
			<th style="width:130px;"><?php echo $this->Paginator->sort('username'); ?></th>
			<th style="width:100px;"><?php echo $this->Paginator->sort('user_id','Created By'); ?></th>
			<th style="width:100px;"><?php echo $this->Paginator->sort('creationTime','Created on'); ?></th>
			<th style="width:130px;"><?php echo $this->Paginator->sort('usertype_id'); ?></th>
			<!--<th><?php echo $this->Paginator->sort('ref_id'); ?></th>-->
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td>
			<?php
			echo $this->Html->link($this->element('userinfo',array('what'=>'name','id'=>(int)$user['User']['user_id'])), array('controller' => 'users', 'action' => 'info', $user['User']['user_id']));
			?>
			&nbsp;
		</td>
		<td><?php echo h($user['User']['creationTime']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($user['Usertype']['name'], array('controller' => 'usertypes', 'action' => 'view', $user['Usertype']['id'])); ?>
		</td>
		<!--<td><?php echo h($user['User']['ref_id']); ?>&nbsp;</td>-->
		<td class="actions">
			<?php echo $this->Html->link(__('Info'), array('action' => 'info', $user['User']['id']),array('class'=>'btn btn-info')); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id']),array('class'=>'btn btn-warning')); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']),array('class'=>'btn btn-inverse'), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
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
