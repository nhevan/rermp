
<div class="unions view">
<h1><?php echo __($union['Union']['name'].' Union Overview'); ?>

<?php 
$uType = $this->Session->read('Auth.User.usertype_id');
if($uType==2||$uType==6){
echo $this->Html->link(__('Edit'), array('controller' => 'Unions', 'action' => 'edit',$union['Union']['id']),array('class'=>'btn btn-inverse pull-right')); 

echo $this->Html->link(__('Send Fund'), array('controller' => 'Transactions', 'action' => 'fundRma',$union['Union']['id']),array('class'=>'btn btn-danger pull-right','style'=>'margin-right:10px;')); 
}
?>
</h1>
	<table class="table table-bordered genericTable" style="max-width:60%;">
		<tr>
			<td>System Id :</td>
			<td><?php echo h($union['Union']['id']); ?></td>
		</tr>
		<tr>
			<td>Union :</td>
			<td><?php echo h($union['Union']['name']); ?></td>
		</tr>
		<tr>
			<td>Upazilla :</td>
			<td><?php echo h($union['Upazilla']['name']); ?></td>
		</tr>
		<tr>
			<td>District :</td>
			<td><?php echo h($district['District']['name']); ?></td>
		</tr>
		<tr>
			<td>Division :</td>
			<td><?php echo h($division['Division']['name']); ?></td>
		</tr>
		<tr>
			<td>Union Description :</td>
			<td><?php echo h($union['Union']['decsription']); ?></td>
		</tr>
		<tr>
			<td>Union Created By :</td>
			<td>
			<?php
				echo $this->element('userinfo',array('what'=>'name','id'=>(int)$union['Union']['createdBy']));
			?>
			</td>
		</tr>
		<tr>
			<td>Union Creation Time :</td>
			<td><?php echo h($union['Union']['creationTime']); ?></td>
		</tr>
		<tr>
			<td>Employees :</td>
			<td><?php 
			
			echo $this->Html->link(__('List Employees'), array('controller' => 'employees', 'action' => 'unionEmployees',$union['Union']['id'],$union['Union']['name'] ),array('class'=>'btn btn-success')); 
			
			echo "<br/>";
			
			echo $this->Html->link(__('Add New Employee '), array('controller' => 'employees', 'action' => 'addEmployee'),array('class'=>'btn btn-inverse','style'=>'margin-top:5px;')); 
			
			?> </td>
		</tr>
		
	</table>
</div>

<div class="related">
	<h3><?php echo __('Account Detail'); ?></h3>
	<?php if (!empty($accountinfo)): ?>
	<table cellpadding = "0" cellspacing = "0" class="table table-bordered genericTable">
	<tr>
		<th><?php echo __('AccountNo'); ?></th>
		<th><?php echo __('BankName'); ?></th>
		<th><?php echo __('Account Type'); ?></th>
		<th><?php echo __('Balance'); ?></th>
	</tr>

		<?  
		$count = 0;
		foreach($accountinfo as $singleAccount): 
		$acType = "default";
		if($singleAccount['Account']['isPrimary']) $acType = "Primary Account";
		?>
		<tr>
			<td style="text-align:left";><?php echo  $singleAccount['Account']['accountNo']; ?></td>
			<td><?php echo $singleAccount['Account']['bankName']; ?></td>
			<td><? echo $acType; ?>
			<? 
			if($acType == "default"){
				$uType = $this->Session->read('Auth.User.usertype_id');
if($uType==2 || $uType==6){
				echo $this->HTML->link('Make Primary',array('controller'=>'accounts','action'=>'makePrimary',$singleAccount['Account']['id']),array('class'=>'btn btn-warning btn-small')); 
				}
			}	
			?>
			
			</td>
			<td>
				<?php echo $singleAccount['Account']['balance']; ?> BDT
				
				<? echo $this->HTML->link('View Detail',array('controller'=>'accounts','action'=>'detail',$singleAccount['Account']['id']),array('class'=>'btn btn-info'));  ?>
			</td>
		</tr>
	<? 
	$count++;
	endforeach; ?>

	</table>
<?php endif; ?>
<?
	if (empty($accountinfo)){
		echo __("<p class='alert alert-warning'>No account found under this union. Please add one now. ");
		echo $this->Html->link(__('Add Union Account'), array('controller' => 'accounts', 'action' => 'addUnionAccount'),array('class'=>'btn btn-warning'));
		echo "</p>";
	}
?>
</div>

<div>
	<h3><?php echo __('Accumulated Employee Overview'); ?></h3>
	<?
	$totalE = 0;
	$activeE = 0;
	$totalSaving = 0;
	$totalCash = 0;
	foreach ($employees as $employee){
		$totalE++;
		if((int)$employee['Employee']['isActive']==1) $activeE++;
		if(!empty($employee['Salarystatus'])){
			$totalCash += (int)$employee['Salarystatus'][0]['cash'];
			$totalSaving += (int)$employee['Salarystatus'][0]['saving'];
		}
	}
	
	?>
	<!--<ul class="nav nav-pills nav-stacked" style="max-width: 400px;">
      <li class="active">
          <span class="badge pull-right"><? echo $totalE; ?></span>
          Total Employees
      </li>
      <li>
          <span class="badge pull-right"><? echo $activeE; ?></span>
          Total Active Employees
      </li>
      <li class="active">
          <span class="badge pull-right"><? echo $totalE-$activeE; ?></span>
          Total Inactive Employees
      </li>
      <li>
          <span class="badge pull-right"><? echo $totalSaving+$totalCash; ?> BDT</span>
          Total Earned by Employees
      </li>
      <li class="active">
          <span class="badge pull-right"><? echo $totalCash; ?></span>
          Total Cash Paid to Employees
      </li>
      <li>
          <span class="badge pull-right"><? echo $totalSaving; ?></span>
          Total Saving by Employees
      </li>
    </ul>-->
	<table class="table table-bordered genericTable" style="width:auto;">
		<tr>
			<td>Total Employees</td>
			<td><span class="badge"><? echo $totalE; ?></span></td>
		</tr>
		<tr>
			<td>Total Active Employees</td>
			<td><span class="badge"><? echo $activeE; ?></span></td>
		</tr>
		<tr>
			<td>Total Inactive Employees</td>
			<td><span class="badge"><? echo $totalE-$activeE; ?></span></td>
		</tr>
		<tr>
			<td>Total Earned by Employees</td>
			<td><span class="badge"><? echo $totalSaving+$totalCash; ?> BDT</span></td>
		</tr>
		<tr>
			<td>Total Cash Paid to Employees</td>
			<td><span class="badge pull-right"><? echo $totalCash; ?> BDT</span>
		</tr>
		<tr>
			<td>Total Saving by Employees</td>
			<td><span class="badge pull-right"><? echo $totalSaving; ?> BDT</span></td>
		</tr>
	</table>
</div><!-- employee overview ends -->

