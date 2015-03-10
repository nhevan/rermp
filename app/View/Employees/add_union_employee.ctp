<div class="employees form">
<?php echo $this->Form->create('Employee', array('type' => 'file')); ?>
	<fieldset>
		<legend><?php echo __('Add Employee'); ?></legend>
		<div id="EmpForm" style="display:nonex;">
	<?php
		//echo $this->Form->input('union_id');
		echo $this->Form->input('name',array('label'=>'Nick Name : '));
		?>
		<span>Upload a profile Picture :</span>
		<? echo $this->Form->file('image',array('required'=>'true')); ?>
		<br/><br/>
		<?
		echo $this->Form->input('Employeemetum.name',array('label'=>'Full Name :','required'=>'true'));
		echo $this->Form->input('Employeemetum.fatherHusband',array('label'=>'Father or Husband Name :'));
		echo $this->Form->input('Employeemetum.NID',array('required'=>'true'));
		//echo $this->Form->input('employee_id');
		echo $this->Form->input('Employeemetum.birthRegNo',array('label'=>'Birth Registration No.:','rows'=>1,'type'=>'text'));
		echo $this->Form->input('Employeemetum.DOB',array(
								'label' => 'Date of Birth :',
								'minYear' => date('Y') - 70,
    							'maxYear' => date('Y') - 18,));
		echo $this->Form->input('Employeemetum.village',array('required'=>'true'));
		echo $this->Form->input('Employeemetum.loanAmount');
		echo $this->Form->input('Employeemetum.loanPurpose');
		echo $this->Form->input('Employeemetum.paidTillDate');
		echo $this->Form->input('Employeemetum.savingsTillDate');
		echo $this->Form->input('Employeemetum.savingTillDatePerBank');
	?>
		<legend><?php echo __('Account Information'); ?></legend>
	<?	
		echo $this->Form->input('Account.accountNo',array('required'=>'true'));
		echo $this->Form->input('Account.bankName',array('required'=>'true'));
	?>
		</div><!-- EmpForm ends -->
	</fieldset>
<?php 
	echo $this->Form->submit('Submit',array('class'=>'btn btn-primary')); 
	echo $this->Form->end();
?>
</div>
