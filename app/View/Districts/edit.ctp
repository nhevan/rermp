<div class="districts form">
<?php echo $this->Form->create('District'); ?>
	<fieldset>
		<legend><?php echo __('Edit District'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
