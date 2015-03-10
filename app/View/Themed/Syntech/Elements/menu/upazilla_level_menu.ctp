<div class="nav-collapse" id="nav-container">
	<ul class="nav nav-pills nav-justified" style="margin-bottom:20px;">
	  
		<li>
			<?php echo $this->Html->link(__('Dashboard'), array('controller' => 'users', 'action' => 'dashboard',$this->Session->read('Auth.User.id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('Upazilla Overview'), array('controller' => 'upazillas', 'action' => 'overview',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('List All Employees'), array('controller' => 'employees', 'action' => 'upazillaEmployees',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('Add Union'), array('controller' => 'unions', 'action' => 'add',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
		
		<li>
			<?php echo $this->Html->link(__('Add Union Account'), array('controller' => 'accounts', 'action' => 'addUnionAccount',$this->Session->read('Auth.User.ref_id'))); ?>		
		</li>
	  
	</ul>
</div>