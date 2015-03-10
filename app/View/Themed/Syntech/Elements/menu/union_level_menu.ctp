<div class="nav-collapse" id="nav-container">
	<ul class="nav nav-pills nav-justified" style="margin-bottom:20px;">
	  
		<li>
			<?php echo $this->Html->link(__('Dashboard'), array('controller' => 'users', 'action' => 'dashboard',$this->Session->read('Auth.User.id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('Union Overview'), array('controller' => 'unions', 'action' => 'overview',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('List All Employees'), array('controller' => 'employees', 'action' => 'unionEmployees',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'addUnionEmployee',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
	  
	</ul>
</div>