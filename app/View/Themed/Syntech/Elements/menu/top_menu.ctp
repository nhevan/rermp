<div class="navbar">
	<div class="navbar-inner">
		<div class="row-fluid">
			<div class="span9">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<!--<a href="/"><? echo $this->Html->image('rermp2-logo.png', array('alt' => 'rermp2-lged.com','id'=>'logo')); ?></a>-->
				<? echo $this->Html->image('Bangladesh-Government.png',array('width'=>'100px','class'=>'left pad-20')); ?>
				<h3 style="border-bottom:0px;color:lightblue;font-size:21px;">Rural Employment and Road Maintenance Programme-2</h3>
				<h5 style="color:white;">An initiative of Local Government Engineering Department</h5>
				
			</div>
			<div class="span3">
				<?
					$userName = $this->session->read('Auth.User.name');
					$userId = $this->session->read('Auth.User.id');
					$Authx = $this->session->read('Auth');
				?>
				<div class="user-block ">
	                
	                
	                <? 
	                if(!empty($userId)){
	                	echo "<div class='user-lbl left '>Welcome</div>";
	                	echo "<div class='user-txt left blue bold'>";
	                	echo $userName; 
	                	echo "&nbsp;";
	                	echo "&nbsp;";
	                	echo "&nbsp;";
		                echo $this->Html->link(__('Logout'), array('controller'=>'users','action' => 'logout'), array('class' => '')); 
		            }else{
		            	echo "<div class='user-lbl left '>Please</div>";
	                	echo "<div class='user-txt left blue bold'>";
		            	echo $this->Html->link(__('Login'), array('controller'=>'users','action' => 'login'), array('class' => '')); 
		            }
	                ?>
	                </div>
	                
	                <div class="clear"></div>
	            </div>
			</div>
		</div><!-- first row ends -->
		<div class="row-fluid">
			<div class="span10 offset1">
				<?php 
				if((int)$this->Session->read('Auth.User.usertype_id')==8) {//Union Level User
					echo $this->element('menu/union_level_menu');
				}elseif((int)$this->Session->read('Auth.User.usertype_id')==7) {//Upazilla Level User
					echo $this->element('menu/upazilla_level_menu');
				}elseif((int)$this->Session->read('Auth.User.usertype_id')==6) {//District Level User
					echo $this->element('menu/district_level_menu');
				}elseif((int)$this->Session->read('Auth.User.usertype_id')==5) {//Division Level User
					echo $this->element('menu/division_level_menu');
				}elseif((int)$this->Session->read('Auth.User.usertype_id')==2) {//HQ User Menu
					echo $this->element('menu/hq_user_menu');
				}else{
					echo $this->element('menu/test_menu');
				}
				
				?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#notis').load('Notifications/notiview/<? echo $userId; ?>').fadeIn("slow");
	var auto_refresh = setInterval(
	function ()
	{
	$('#notis').load('Notifications/notiview/<? echo $userId; ?>').fadeIn("slow");
	}, 10000); // refresh every 10000 milliseconds 
	
	$('#notis').hover(
	  function () {
	    clearInterval(auto_refresh)
	  },
	  function () {
	  	$('#notis').load('Notifications/notiview/<? echo $userId; ?>').fadeIn("slow");
	    auto_refresh = setInterval(
		function ()
		{
			$('#notis').load('Notifications/notiview/<? echo $userId; ?>').fadeIn("slow");
		}, 10000);
		$('.popover').fadeOut("slow");
	  }
	);
	
	</script>
	
	<style>
    	#notis-notUsed{
    		position: relative;
    		left:10px !important;
    		
    	}
    </style>