<?php 
App::uses('Component', 'Controller');
class AccessComponent extends Component{ 
	var $components = array('Acl', 'Auth'); 
    var $user;
    public function initialize(Controller $controller) {
        $this->user = "test";
    }
    public function test(){
    	echo "testing direct print";
    	return $this->user;
    }
} 
?>