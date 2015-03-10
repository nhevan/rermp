<div class="unions form">
<?php echo $this->Form->create('Union'); ?>
	<fieldset>
		<legend><?php echo __('Edit Union'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('decsription');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>