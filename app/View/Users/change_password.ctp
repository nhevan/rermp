<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Change your password'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username', array('disabled' => 'disabled'));
		echo $this->Form->input('name');
		echo $this->Form->input('password');

	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>