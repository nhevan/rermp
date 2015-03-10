<div class="nav-collapse" id="nav-container">
	<ul class="nav nav-pills nav-justified" style="margin-bottom:20px;">
	  
		<li>
			<?php echo $this->Html->link(__('Dashboard'), array('controller' => 'users', 'action' => 'dashboard',$this->Session->read('Auth.User.id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('District Overview'), array('controller' => 'districts', 'action' => 'overview',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('List All Employees'), array('controller' => 'employees', 'action' => 'districtEmployees',$this->Session->read('Auth.User.ref_id'))); ?>
		</li>
		
		<li class="dropdown">
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      CREATE<span class="caret"></span>
		    </a>
		    <ul class="dropdown-menu" role="menu">
				<li>
					<?php echo $this->Html->link(__('Add Upazilla'), array('controller' => 'upazillas', 'action' => 'add')); ?>
				</li>
				<li>
					<?php echo $this->Html->link(__('Add Union'), array('controller' => 'Unions', 'action' => 'add')); ?>
				</li>
				<li><?php echo $this->Html->link(__('Add Upazilla Account'), array('controller' => 'Accounts', 'action' => 'addUpazillaAccount')); ?> </li>
		    	<li><?php echo $this->Html->link(__('Add Union Account'), array('controller' => 'Accounts', 'action' => 'addUnionAccount')); ?> </li>
	 	    </ul>
		</li>
		<li class="dropdown">
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      MORE ACTIONS<span class="caret"></span>
		    </a>
		    <ul class="dropdown-menu" role="menu">
			    <li><?php echo $this->Html->link(__('Change Account Password'), array('controller' => 'Users', 'action' => 'changePassword')); ?> </li>
	 	    </ul>
		</li>

	  
	</ul>
</div>