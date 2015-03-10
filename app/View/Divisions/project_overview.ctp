
<div>
	<h3 style="text-align:center"><?php echo __('Accumulated Employee Overview of RERMP2'); ?></h3>
	<?
	$totalE = 0;
	$activeE = 0;
	$totalSaving = 0;
	$totalCash = 0;
	foreach($all_division as $division){
		foreach($division['District'] as $district){
			foreach($district['Upazilla'] as $upazilla){
				foreach($upazilla['Union'] as $union){
					foreach ($union['Employee'] as $employee){
						$totalE ++;
						if(!empty($employee)){
							if((int)$employee['isActive']==1) $activeE++;
						}
						if(!empty($employee['Salarystatus'])){
							$totalCash += (int)$employee['Salarystatus'][0]['cash'];
							$totalSaving += (int)$employee['Salarystatus'][0]['saving'];
						}
		
					}
				}
			}
		}
	}
	
	?>
	<table class="table table-bordered genericTable" style="width:auto;margin:0px auto;">
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

