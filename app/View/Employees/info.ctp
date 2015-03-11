<div class="employees view">
<h2><?php echo __('Employee Information'); ?>

<? 
$uType = $this->Session->read('Auth.User.usertype_id');
if($uType==2 || $uType==6){
	echo $this->HTML->link('Edit',array('controller'=>'Employees','action'=>'editEmp',$employee['Employee']['id']),array('class'=>'btn btn-info')); 
}

?>
</h2>
	<table class="table table-bordered genericTable" style="max-width:60%;">
		<tr>
			<td><span class="infoLabel"><?php echo __('Nick Name'); ?> </span>: </td>
			<td><?php echo h($employee['Employee']['name']); ?></td>
		</tr>

		<tr>
			<td><?php echo __('Full Name :'); ?></td>
			<td><?php echo $employee['Employeemetum']['name']; ?></td>
		</tr>
		
		<tr>
			<td><?php echo __('Status :'); ?></td>
			<td>
				<?php
					$status = $employee['Employee']['isActive'];
					if($status==0) $status="Inactive";
					if($status==1) $status="Active";
					echo h($status); 
				?>
			</td>
		</tr>
		<tr>
			<td><?php echo __('Created By :'); ?></td>
			<td>
			<?php
				echo $this->element('userinfo',array('what'=>'name','id'=>(int)$employee['Employee']['created_by']));
			?>
			</td>
		</tr>
		<tr>
			<td><?php echo __('Created on :'); ?></td>
			<td><?php echo h($employee['Employee']['creationTime']); ?></td>
		</tr>
		<tr>
			<td><?php echo __('Last Modified :'); ?></td>
			<td><?php echo h($employee['Employee']['updatedOn']); ?></td>
		</tr>
		<tr>
			<td><?php echo __('Union :'); ?></td>
			<td>
				<? echo $employee['Union']['name']; ?>
			</td>
		</tr>
		
		<tr>
			<td><?php echo __('Father/Husband Name :'); ?></td>
			<td><?php echo $employee['Employeemetum']['fatherHusband']; ?></td>
		</tr>
		
		<tr>
			<td><?php echo __('NID No. :'); ?></td>
			<td><?php echo $employee['Employeemetum']['NID']; ?></td>
		</tr>
		
		<tr>
			<td><?php echo __('Birth Registration No. :'); ?></td>
			<td><?php echo $employee['Employeemetum']['birthRegNo']; ?></td>
		</tr>
		
		<tr>
			<td><?php echo __('Bate Of Birth :'); ?></td>
			<td><?php echo $employee['Employeemetum']['DOB']; ?></td>
		</tr>
		<tr>
			<td><?php echo __('Village Name :'); ?></td>
			<td><?php echo $employee['Employeemetum']['village']; ?></td>
		</tr>
		
		<tr>
			<td><?php echo __('Loan Amount :' ); ?></td>
			<td><?php echo $employee['Employeemetum']['loanAmount']; ?> BDT</td>
		</tr>
		
		<tr>
			<td><?php echo __('Loan Purpose :'); ?></td>
			<td><?php echo $employee['Employeemetum']['loanPurpose']; ?></td>
		</tr>
		
		<tr>
			<td><?php echo __('Paid Till Today :'); ?></td>
			<td><?php echo $employee['Employeemetum']['paidTillDate']; ?> BDT</td>
		</tr>
		<tr>
			<td><?php echo __('Savings Till Today :'); ?></td>
			<td><?php echo $employee['Employeemetum']['savingsTillDate']; ?> BDT</td>
		</tr>
		
		<tr>
			<td><?php echo __('Savings Till Today Per Bank :'); ?></td>
			<td><?php echo $employee['Employeemetum']['savingTillDatePerBank']; ?> BDT</td>
		</tr>
		
	</table>
</div>

<div class="related">
	<h3><?php echo __('Related Salarystatuses'); ?></h3>
	<?php if (!empty($employee['Salarystatus'])): ?>
	<table cellpadding = "0" cellspacing = "0" class="table table-bordered genericTable" style="width:50%;">
	<tr>
		<th style="text-align:center;"><?php echo __('Total Cash'); ?></th>
		<th style="text-align:center;"><?php echo __('Total Saving'); ?></th>
	</tr>
	<?php foreach ($employee['Salarystatus'] as $salarystatus): ?>
		<tr>
			<td style="text-align:center;"><?php echo $salarystatus['cash']; ?> BDT</td>
			<td style="text-align:center;"><?php echo $salarystatus['saving']; ?> BDT</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

</div>

<div class="related">
	<h3><?php echo __('Related Accounts'); ?></h3>
	<?php if (!empty($accountinfo)): ?>
	<table cellpadding = "0" cellspacing = "0" class="table table-bordered genericTable">
	<tr>
		<th><?php echo __('AccountNo'); ?></th>
		<th><?php echo __('BankName'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Balance'); ?></th>

	</tr>
	<? foreach($accountinfo as $accounts){ 
		$acType = "default";
		if($accounts['Account']['isPrimary']) $acType = "Primary Account";
	?>
		<tr>
			<td style="text-align:left";><?php echo $accounts['Account']['accountNo']; ?></td>
			<td><?php echo $accounts['Account']['bankName']; ?></td>
			<td><? 
			echo $acType; 
			if($acType == "default"){
			$uType = $this->Session->read('Auth.User.usertype_id');
				if($uType==2 || $uType==6){
				echo $this->HTML->link('Make Primary',array('controller'=>'accounts','action'=>'makePrimary',$accounts['Account']['id']),array('class'=>'btn btn-warning btn-small')); 
				} 
			 }
			 ?>
			</td>
			<td>
				<?php echo $accounts['Account']['balance']; ?> BDT
				
				<? 
				$uType = $this->Session->read('Auth.User.usertype_id');
				if($uType==2 || $uType==6){
					echo $this->HTML->link('View Detail',array('controller'=>'accounts','action'=>'detail',$accounts['Account']['id']),array('class'=>'btn btn-info'));  
				}
				
				?>
			</td>
		</tr>
	<? } ?>
	</table>
<?php endif; ?>

</div>
<div class="related">
	<h3><?php echo __('Attendances Record'); ?></h3>
	<?php if (!empty($employee['Attendance'])): ?>
	<table cellpadding = "0" cellspacing = "0" class="table table-bordered">
	<tr>
		<th><?php echo __('Recorded On'); ?></th>
		<th><?php echo __('Working Year'); ?></th>
		<th><?php echo __('Working Month'); ?></th>
		<th><?php echo __('Days Present'); ?></th>
		<th><?php echo __('Salary Status'); ?></th>
		<th><?php echo __('Pay Date'); ?></th>
	
	</tr>
	<?php foreach ($employee['Attendance'] as $attendance): ?>
		<?
			if($attendance['isPaid']==0) {
				$attendance['isPaid'] ="Not Paid yet";
				$attendance['paidOnDate'] = "N/A";
			}elseif($attendance['isPaid']==1) {
				$attendance['isPaid'] ="Paid";
			}
		?>
		<tr>
			<td><?php echo $attendance['datetime']; ?></td>
			<td><?php echo $attendance['year']; ?></td>
			<td><?php echo $attendance['month']; ?></td>
			<td><?php echo $attendance['days_present']; ?></td>
			<td><?php echo $attendance['isPaid']; ?>
			<?php 
			if($attendance['isPaid'] =="Not Paid yet" && ($this->Session->read('Auth.User.usertype_id')==2 ||$this->Session->read('Auth.User.usertype_id')==6 || $this->Session->read('Auth.User.usertype_id')==1) ){ //8 for allowing only UNION and SUPER ADMIN users to pay
				echo $this->Html->link(__('Pay'), array('controller' => 'transactions', 'action' => 'payAttendance',$attendance['id']),array('class'=>'btn btn-warning')); 
			}	
			?>
			</td>
			<td><?php echo $attendance['paidOnDate']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
<? if($this->Session->read('Auth.User.usertype_id')==2 || $this->Session->read('Auth.User.usertype_id')==6 ||$this->Session->read('Auth.User.usertype_id')==1): ?>
	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('Add New Attendance for this Employee'), array('controller' => 'attendances', 'action' => 'assign',$employee['Employee']['id'])); ?> </li>
		</ul>
	</div>
<? endif; ?>
</div>
<!--<div class="related">
	<h3><?php echo __('Related Baselinedata'); ?></h3>
	<?php if (!empty($employee['Baselinedatum'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Baseline Id'); ?></th>
		<th><?php echo __('NGO'); ?></th>
		<th><?php echo __('Employee Id'); ?></th>
		<th><?php echo __('Education'); ?></th>
		<th><?php echo __('Occupation'); ?></th>
		<th><?php echo __('Age'); ?></th>
		<th><?php echo __('FamilyHeadName'); ?></th>
		<th><?php echo __('FamilyHeadOccupation'); ?></th>
		<th><?php echo __('FamilyHeadEducation'); ?></th>
		<th><?php echo __('FamilyHeadAge'); ?></th>
		<th><?php echo __('FamilyHeadDailyIncome'); ?></th>
		<th><?php echo __('FamilyHeadLandOwned'); ?></th>
		<th><?php echo __('TotalFamilyMember'); ?></th>
		<th><?php echo __('MaleFamilyMember'); ?></th>
		<th><?php echo __('TotalChildren'); ?></th>
		<th><?php echo __('ChildrenGoingToSchool'); ?></th>
		<th><?php echo __('ChildrenTask'); ?></th>
		<th><?php echo __('DoesDayLabour'); ?></th>
		<th><?php echo __('HasBussiness'); ?></th>
		<th><?php echo __('HasAgriculture'); ?></th>
		<th><?php echo __('IsBegging'); ?></th>
		<th><?php echo __('HasOthersIncome'); ?></th>
		<th><?php echo __('IncomeType'); ?></th>
		<th><?php echo __('HaveOtherRegularIncomeSource'); ?></th>
		<th><?php echo __('OwnRegularIncome'); ?></th>
		<th><?php echo __('HusbandRegularIncome'); ?></th>
		<th><?php echo __('ParentsRegularincome'); ?></th>
		<th><?php echo __('ChildrensRegularIncome'); ?></th>
		<th><?php echo __('ResourcesLeftByHusband'); ?></th>
		<th><?php echo __('OthersRegularIncome'); ?></th>
		<th><?php echo __('LandQuantityOwned'); ?></th>
		<th><?php echo __('LandHomestead'); ?></th>
		<th><?php echo __('LandCultivable'); ?></th>
		<th><?php echo __('LandKhasInPossion'); ?></th>
		<th><?php echo __('Hen'); ?></th>
		<th><?php echo __('Chicken'); ?></th>
		<th><?php echo __('Duck'); ?></th>
		<th><?php echo __('Swan'); ?></th>
		<th><?php echo __('Cow'); ?></th>
		<th><?php echo __('Goat'); ?></th>
		<th><?php echo __('HomesteadGarden'); ?></th>
		<th><?php echo __('Tree'); ?></th>
		<th><?php echo __('Pond'); ?></th>
		<th><?php echo __('Others'); ?></th>
		<th><?php echo __('IsVGDmember'); ?></th>
		<th><?php echo __('IsNGOmember'); ?></th>
		<th><?php echo __('NameOfOrganization'); ?></th>
		<th><?php echo __('YearsInvolvedInOrganization'); ?></th>
		<th><?php echo __('LoanAmountFromNGOorGrameenBank'); ?></th>
		<th><?php echo __('LoanAmount'); ?></th>
		<th><?php echo __('LoanYear'); ?></th>
		<th><?php echo __('LoanRepaid'); ?></th>
		<th><?php echo __('MonthlyExpenditureFood'); ?></th>
		<th><?php echo __('MonthlyExpenditureChildrenSchool'); ?></th>
		<th><?php echo __('MonthlyExpenditureClothes'); ?></th>
		<th><?php echo __('MonthlyExpenditureHaealth'); ?></th>
		<th><?php echo __('MonthlyExpenditureTransportation'); ?></th>
		<th><?php echo __('MonthlyExpenditureMobilephone'); ?></th>
		<th><?php echo __('MonthlyExpenditureLoanRepayment'); ?></th>
		<th><?php echo __('MonthlyExpenditureOthers'); ?></th>
		<th><?php echo __('IsInterestedInRuralRoadmaintenance'); ?></th>
		<th><?php echo __('IsInterestedAsAnNGOworkers'); ?></th>
		<th><?php echo __('Relationshipstatuses Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($employee['Baselinedatum'] as $baselinedatum): ?>
		<tr>
			<td><?php echo $baselinedatum['id']; ?></td>
			<td><?php echo $baselinedatum['baseline_id']; ?></td>
			<td><?php echo $baselinedatum['NGO']; ?></td>
			<td><?php echo $baselinedatum['employee_id']; ?></td>
			<td><?php echo $baselinedatum['education']; ?></td>
			<td><?php echo $baselinedatum['occupation']; ?></td>
			<td><?php echo $baselinedatum['age']; ?></td>
			<td><?php echo $baselinedatum['familyHeadName']; ?></td>
			<td><?php echo $baselinedatum['familyHeadOccupation']; ?></td>
			<td><?php echo $baselinedatum['familyHeadEducation']; ?></td>
			<td><?php echo $baselinedatum['familyHeadAge']; ?></td>
			<td><?php echo $baselinedatum['familyHeadDailyIncome']; ?></td>
			<td><?php echo $baselinedatum['familyHeadLandOwned']; ?></td>
			<td><?php echo $baselinedatum['totalFamilyMember']; ?></td>
			<td><?php echo $baselinedatum['maleFamilyMember']; ?></td>
			<td><?php echo $baselinedatum['totalChildren']; ?></td>
			<td><?php echo $baselinedatum['childrenGoingToSchool']; ?></td>
			<td><?php echo $baselinedatum['childrenTask']; ?></td>
			<td><?php echo $baselinedatum['doesDayLabour']; ?></td>
			<td><?php echo $baselinedatum['hasBussiness']; ?></td>
			<td><?php echo $baselinedatum['hasAgriculture']; ?></td>
			<td><?php echo $baselinedatum['isBegging']; ?></td>
			<td><?php echo $baselinedatum['hasOthersIncome']; ?></td>
			<td><?php echo $baselinedatum['IncomeType']; ?></td>
			<td><?php echo $baselinedatum['haveOtherRegularIncomeSource']; ?></td>
			<td><?php echo $baselinedatum['ownRegularIncome']; ?></td>
			<td><?php echo $baselinedatum['husbandRegularIncome']; ?></td>
			<td><?php echo $baselinedatum['parentsRegularincome']; ?></td>
			<td><?php echo $baselinedatum['childrensRegularIncome']; ?></td>
			<td><?php echo $baselinedatum['resourcesLeftByHusband']; ?></td>
			<td><?php echo $baselinedatum['othersRegularIncome']; ?></td>
			<td><?php echo $baselinedatum['landQuantityOwned']; ?></td>
			<td><?php echo $baselinedatum['landHomestead']; ?></td>
			<td><?php echo $baselinedatum['landCultivable']; ?></td>
			<td><?php echo $baselinedatum['landKhasInPossion']; ?></td>
			<td><?php echo $baselinedatum['hen']; ?></td>
			<td><?php echo $baselinedatum['chicken']; ?></td>
			<td><?php echo $baselinedatum['duck']; ?></td>
			<td><?php echo $baselinedatum['swan']; ?></td>
			<td><?php echo $baselinedatum['cow']; ?></td>
			<td><?php echo $baselinedatum['goat']; ?></td>
			<td><?php echo $baselinedatum['homesteadGarden']; ?></td>
			<td><?php echo $baselinedatum['tree']; ?></td>
			<td><?php echo $baselinedatum['pond']; ?></td>
			<td><?php echo $baselinedatum['others']; ?></td>
			<td><?php echo $baselinedatum['isVGDmember']; ?></td>
			<td><?php echo $baselinedatum['isNGOmember']; ?></td>
			<td><?php echo $baselinedatum['nameOfOrganization']; ?></td>
			<td><?php echo $baselinedatum['yearsInvolvedInOrganization']; ?></td>
			<td><?php echo $baselinedatum['loanAmountFromNGOorGrameenBank']; ?></td>
			<td><?php echo $baselinedatum['loanAmount']; ?></td>
			<td><?php echo $baselinedatum['loanYear']; ?></td>
			<td><?php echo $baselinedatum['loanRepaid']; ?></td>
			<td><?php echo $baselinedatum['monthlyExpenditureFood']; ?></td>
			<td><?php echo $baselinedatum['monthlyExpenditureChildrenSchool']; ?></td>
			<td><?php echo $baselinedatum['monthlyExpenditureClothes']; ?></td>
			<td><?php echo $baselinedatum['monthlyExpenditureHaealth']; ?></td>
			<td><?php echo $baselinedatum['monthlyExpenditureTransportation']; ?></td>
			<td><?php echo $baselinedatum['monthlyExpenditureMobilephone']; ?></td>
			<td><?php echo $baselinedatum['monthlyExpenditureLoanRepayment']; ?></td>
			<td><?php echo $baselinedatum['monthlyExpenditureOthers']; ?></td>
			<td><?php echo $baselinedatum['isInterestedInRuralRoadmaintenance']; ?></td>
			<td><?php echo $baselinedatum['isInterestedAsAnNGOworkers']; ?></td>
			<td><?php echo $baselinedatum['relationshipstatuses_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'baselinedata', 'action' => 'view', $baselinedatum['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'baselinedata', 'action' => 'edit', $baselinedatum['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'baselinedata', 'action' => 'delete', $baselinedatum['id']), null, __('Are you sure you want to delete # %s?', $baselinedatum['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Baselinedatum'), array('controller' => 'baselinedata', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
-->

