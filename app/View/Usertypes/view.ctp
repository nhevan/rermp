<div class="usertypes view">
<h2><?php echo __('Usertype Info'); ?></h2>
	<dl>
		<dt class="orange"><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($usertype['Usertype']['name']); ?>
			&nbsp;
		</dd>
		<dt class="orange"><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($usertype['Usertype']['description']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="related">
	<h3><?php echo __('Related Users'); ?></h3>
	<?php if (!empty($usertype['User'])): ?>
	<table class="table table-bordered" style="max-width:300px;">
	<!--<tr>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('CreationTime'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>-->
	<tr>
		<td>
		#
		<? $userCount = 1; ?>
		</td>
		<td>
		User Name
		</td>
		<td><?php echo __('CreationTime'); ?></td>
	</tr>
	<?php foreach ($usertype['User'] as $user): ?>
		<tr>
			<td>
			<? echo $userCount++; ?>
			</td>
			<td><?php
			echo $this->Html->link($this->element('userinfo',array('what'=>'name','id'=>(int)$user['id'])), array('controller' => 'users', 'action' => 'info', $user['id']));
			?>
</td>
			<!--<td><?php echo $user['user_id']; ?></td>-->
			<td><?php echo $user['creationTime']; ?></td>
		<!--	<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users', 'action' => 'delete', $user['id']), null, __('Are you sure you want to delete # %s?', $user['id'])); ?>
			</td>-->
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
