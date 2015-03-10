<div class="transactions form">
<?php echo $this->Form->create('Transaction'); ?>
	<fieldset>
		<? $districtName = $districts['District']['name']; ?>
		<legend><?php echo __("Transfer Money To $districtName"); ?></legend>
	<?php
	
		$months = array(
			1=>'January',
			2=>'February',
			3=>'March',
			4=>'April',
			5=>'May',
			6=>'June',
			7=>'July',
			8=>'August',
			9=>'September',
			10=>'October',
			11=>'November',
			12=>'December');
		$years = array('2014'=>2014,'2015'=>2015,'2016'=>2016,'2017'=>2017,'2018'=>2018,'2019'=>2019,'2020'=>2020);
		echo $this->Form->input('year',array('options'=>$years));
		echo $this->Form->input('month',array('options'=>$months));
		echo $this->Form->input('reference');
		echo $this->Form->input('amount',array('required'=>'true'));
		echo $this->Form->input('district_id',array('type'=>'hidden','value'=>$districts['District']['id']));
	?>
	</fieldset>
<?php echo $this->Form->submit('Submit',array('class'=>'btn btn-primary')); ?>
<?php echo $this->Form->end(); ?>
</div>
