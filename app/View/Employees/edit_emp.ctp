<div class="employees form">
<?php echo $this->Form->create('Employee'); ?>
	<fieldset>
		<legend><?php echo __('Edit Employee'); ?></legend>
	<?php
		echo $this->Form->input('Employee.id');
		echo $this->Form->input('Employee.name');
		
		//employeeMetum fields starts here
		echo $this->Form->input('Employeemetum.name',array('label'=>'Full Name :','required'=>'true'));
		echo $this->Form->input('Employeemetum.fatherHusband',array('label'=>'Father or Husband Name :'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
