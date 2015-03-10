<div class="nav-collapse" id="nav-container">
	<ul class="nav nav-pills nav-justified" style="margin-bottom:20px;">
	  
		<li>
			<?php echo $this->Html->link(__('Dashboard'), array('controller' => 'users', 'action' => 'dashboard',$this->Session->read('Auth.User.id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('Project Overview'), array('controller' => 'divisions', 'action' => 'projectOverview',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('List Divisions'), array('controller' => 'Divisions', 'action' => 'index',$this->Session->read('Auth.User.ref_id'))); ?>
		</li>
		
		<li class="dropdown">
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      Users <span class="caret"></span>
		    </a>
		    <ul class="dropdown-menu" role="menu">
		    	<li>
					<?php echo $this->Html->link(__('List All Users'), array('controller' => 'Users', 'action' => 'index')); ?>
				</li>
		    	<li><?php echo $this->Html->link(__('Add Head Quarter Level Users'), array('controller' => 'users', 'action' => 'addHqUsers')); ?> </li>
				<li><?php echo $this->Html->link(__('Add Division User'), array('controller' => 'users', 'action' => 'addDivisionUser')); ?> </li>
				<li><?php echo $this->Html->link(__('Add District User'), array('controller' => 'users', 'action' => 'addDistrictUser')); ?> </li>
				<li><?php echo $this->Html->link(__('Add Upazilla User'), array('controller' => 'users', 'action' => 'addUpazillaUser')); ?> </li>
				<li><?php echo $this->Html->link(__('Add Union User'), array('controller' => 'users', 'action' => 'addUnionUser')); ?> </li>
		    </ul>
		</li>
		<li class="dropdown">
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      Accounts <span class="caret"></span>
		    </a>
		    <ul class="dropdown-menu" role="menu">
		    	<li><?php echo $this->Html->link(__('View Base Account'), array('controller' => 'Accounts', 'action' => 'detail',1)); ?> </li>
		    	<li><?php echo $this->Html->link(__('Add District Account'), array('controller' => 'Accounts', 'action' => 'addDistrictAccount')); ?> </li>
		    	<li><?php echo $this->Html->link(__('Add Upazilla Account'), array('controller' => 'Accounts', 'action' => 'addUpazillaAccount')); ?> </li>
		    	<li><?php echo $this->Html->link(__('Add Union Account'), array('controller' => 'Accounts', 'action' => 'addUnionAccount')); ?> </li>
		    	<li><?php echo $this->Html->link(__('Fund District'), array('controller' => 'Transactions', 'action' => 'transferMoneyToDistrict')); ?> </li>
		    	<li><?php echo $this->Html->link(__('Fund RMA'), array('controller' => 'Transactions', 'action' => 'fundRma')); ?> </li>
		    </ul>
		</li>
		<li class="dropdown">
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      Create <span class="caret"></span>
		    </a>
		    <ul class="dropdown-menu" role="menu">
		    	<li>
					<?php echo $this->Html->link(__('Add New Employee'), array('controller' => 'Employees', 'action' => 'addEmployee')); ?>
				</li>
		    	<li>
					<?php echo $this->Html->link(__('Add New Division'), array('controller' => 'Divisions', 'action' => 'add')); ?>
				</li>
				<li>
					<?php echo $this->Html->link(__('Add New District'), array('controller' => 'Districts', 'action' => 'add')); ?>
				</li>
				<li>
					<?php echo $this->Html->link(__('Add New Upazilla'), array('controller' => 'Upazillas', 'action' => 'add')); ?>
				</li>
				<li>
					<?php echo $this->Html->link(__('Add New Union'), array('controller' => 'Unions', 'action' => 'add')); ?>
				</li>
		    </ul>
		</li>	
	</ul>
</div>