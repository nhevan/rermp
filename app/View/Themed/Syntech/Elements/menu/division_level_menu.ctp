<div class="nav-collapse" id="nav-container">
	<ul class="nav nav-pills nav-justified" style="margin-bottom:20px;">
	  
		<li>
			<?php echo $this->Html->link(__('Dashboard'), array('controller' => 'users', 'action' => 'dashboard',$this->Session->read('Auth.User.id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('Division Overview'), array('controller' => 'divisions', 'action' => 'overview',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('List All Employees'), array('controller' => 'employees', 'action' => 'divisionEmployees',$this->Session->read('Auth.User.ref_id'))); ?>
		</li>
		
		<li>
			<?php echo $this->Html->link(__('Add District'), array('controller' => 'districts', 'action' => 'add',$this->Session->read('Auth.User.ref_id'))); ?>
		</li>
		<li class="dropdown">
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      MORE ACTIONS<span class="caret"></span>
		    </a>
		    <ul class="dropdown-menu" role="menu">
			    <li><?php echo $this->Html->link(__('View Base Account'), array('controller' => 'Accounts', 'action' => 'detail',1)); ?> </li>
			    <li><?php echo $this->Html->link(__('Add District Account'), array('controller' => 'accounts', 'action' => 'addDistrictAccount',$this->Session->read('Auth.User.ref_id'))); ?></li>
	 	    </ul>
	  </li>

	  
	</ul>
</div>