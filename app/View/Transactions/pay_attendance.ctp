<div class="transactions form">
<?php echo $this->Form->create('Transaction'); ?>
	<fieldset>
		<legend><?php echo __('Transfer Money To Employee Account'); ?></legend>
		<?

		?>
		<?php
			echo $this->Form->input('reference');
			echo $this->Form->input('attendance_id',array('type'=>'hidden','value'=>$attendanceId));
		?>
	
	</fieldset>
<?php echo $this->Form->submit('Submit',array('class'=>'btn btn-primary')); ?>
<?php echo $this->Form->end(); ?>
</div>
