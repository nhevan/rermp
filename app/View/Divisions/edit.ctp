<div class="divisions form">
<?php echo $this->Form->create('Division'); ?>
	<fieldset>
		<legend><?php echo __('Edit Division'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
