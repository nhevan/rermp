<?
$currentPage =  $this->Paginator->counter('{:page}');
$count = ($currentPage*$limit); 
$count = $count - ($limit-1);
if(!empty($employees)){
	$unionName = $employees[0]['Union']['name'];
}

?>

<div class="employees index">
	<h2><?php echo 'Employees under '.$DistrictName; ?></h2>
	<table cellpadding="0" cellspacing="0" class="table table-bordered">
	<tr>
			<th>#</th>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name','Name'); ?></th>
			<th>Full Name</th>
			<th><?php echo $this->Paginator->sort('union_id','Union Name'); ?></th>
			<th><?php echo $this->Paginator->sort('Union.upazilla_id','Upazilla Name'); ?></th>

			<th><?php echo $this->Paginator->sort('isActive'); ?></th>
			<th>Actions</th>
	</tr>

	<?php foreach ($employees as $employee): ?>

	<? 
	
	if(isset($employee['Employeemetum'])) $fName = $employee['Employeemetum']['name'];
	else $fName = "--Not Available--";
 	?>

	<tr>
		<td><?  echo $count++; ?> </td>
		<td><?php echo h($employee['Employee']['id']); ?>&nbsp;</td>
		<td><?php echo h($employee['Employee']['name']); ?>&nbsp;</td>
		<td><?php echo h($fName); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($employee['Union']['name'], array('controller' => 'unions', 'action' => 'overview', $employee['Union']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($employee['Union']['Upazilla']['name'], array('controller' => 'upazillas', 'action' => 'overview', $employee['Union']['Upazilla']['id'])); ?>
		</td>
		
		<?  
		$status = (int)$employee['Employee']['isActive'];

		if($status==1) $status = "Active";
		elseif($status==0) $status = "Inactive";
		?>
		<td><?php echo h($status); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link("Detail", array('controller' => 'employees', 'action' => 'info', $employee['Employee']['id']),array('class'=>'btn btn-info','type'=>'button')); ?>

		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo $this->Paginator->next(__(' next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>