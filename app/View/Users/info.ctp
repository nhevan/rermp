<div class="users view">
<h2><?php echo __('User Info'); ?></h2>
	<table class="table table-bordered genericTable">
		<tr>
			<td><?php echo __('Id'); ?></td>
			<td>
				<?php echo h($user['User']['id']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Full Name'); ?></td>
			<td>
				<?php echo h($user['User']['name']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Username'); ?></td>
			<td>
				<?php echo h($user['User']['username']); ?>
				&nbsp;
			</td>
		</tr>
		<!--<tr>
			<td><?php echo __('Password'); ?></td>
			<td>
				<?php echo h($user['User']['password']); ?>
				&nbsp;
			</td>
		</tr>-->
		<tr>
			<td><?php echo __('Created By'); ?></td>
			<?
				if(empty($user['User']['user_id'])){
					$user['User']['user_id'] = "Root";
				}
			?>
			<td>
				<?php
				echo $this->element('userinfo',array('what'=>'name','id'=>(int)$user['User']['user_id']));
				?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('CreationTime'); ?></td>
			<td>
				<?php echo h($user['User']['creationTime']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Usertype'); ?></td>
			<td>
				<? echo $user['Usertype']['name']; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo __('Your '.$ref_type); ?></td>
			<?
				if(empty($user['User']['ref_id'])){
					$user['User']['ref_id'] = "Not Applicable";
				}
			?>
			<td>
				<?php echo h($ref_name); ?>
				&nbsp;
			</td>
		</tr>
	</table>
</div>
