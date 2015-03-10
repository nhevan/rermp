<?php
App::uses('AuthComponent', 'AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	
public function beforeFilter() {
    parent::beforeFilter();
    //$this->Auth->allow(); //for allowing everything to everyone
    $this->Auth->allow('initDB');
	$this->Auth->allow('logout');
	$this->Auth->allow('login');
	$this->Auth->allow('getUserInfo');
}

public function initDB() {
    $usertype = $this->User->Usertype;

    // Allow super admins to everything (Custom CRUD + default)
	$usertype->id = 1;
	$this->Acl->allow($usertype, 'controllers');

	// Allow HQ Users to create,view and edit everything
    $usertype->id = 2;
    $this->Acl->deny($usertype, 'controllers');
	$this->Acl->deny($usertype, 'controllers/Divisions');
	$this->Acl->allow($usertype, 'controllers/Pages');
	
	$this->Acl->allow($usertype, 'controllers/Users/dashboard');
	$this->Acl->allow($usertype, 'controllers/Users/info');
	$this->Acl->allow($usertype, 'controllers/Users/index');
	$this->Acl->allow($usertype, 'controllers/Users/edit');
	$this->Acl->allow($usertype, 'controllers/Users/delete');
	$this->Acl->allow($usertype, 'controllers/Users/addHqUsers');
	$this->Acl->allow($usertype, 'controllers/Users/addDivisionUser');
	$this->Acl->allow($usertype, 'controllers/Users/addDistrictUser');
	$this->Acl->allow($usertype, 'controllers/Users/addUpazillaUser');
	$this->Acl->allow($usertype, 'controllers/Users/addUnionUser');
	$this->Acl->allow($usertype, 'controllers/Usertypes/view');

	$this->Acl->allow($usertype, 'controllers/Divisions/add');
	$this->Acl->allow($usertype, 'controllers/Divisions/edit');
	$this->Acl->allow($usertype, 'controllers/Divisions/index');
	$this->Acl->allow($usertype, 'controllers/Divisions/overview');
	$this->Acl->allow($usertype, 'controllers/Divisions/projectOverview');
	
	$this->Acl->allow($usertype, 'controllers/Accounts/makePrimary');
	$this->Acl->allow($usertype, 'controllers/Accounts/detail');
	$this->Acl->allow($usertype, 'controllers/Accounts/addDistrictAccount');
	$this->Acl->allow($usertype, 'controllers/Accounts/addUnionAccount');
	$this->Acl->allow($usertype, 'controllers/Accounts/addUpazillaAccount');
	
	$this->Acl->allow($usertype, 'controllers/Districts/overview');
	$this->Acl->allow($usertype, 'controllers/Districts/add');
	$this->Acl->allow($usertype, 'controllers/Districts/edit');
	$this->Acl->allow($usertype, 'controllers/Districts/fetchDistrictsWithAccounts');

	$this->Acl->allow($usertype, 'controllers/Upazillas/overview');
	$this->Acl->allow($usertype, 'controllers/Upazillas/add');
	$this->Acl->allow($usertype, 'controllers/Upazillas/edit');
		
	$this->Acl->allow($usertype, 'controllers/Unions/overview');
	$this->Acl->allow($usertype, 'controllers/Unions/add');
	$this->Acl->allow($usertype, 'controllers/Unions/edit');

	$this->Acl->allow($usertype, 'controllers/Employees/addEmployee');	
	$this->Acl->allow($usertype, 'controllers/Employees/info');	
	$this->Acl->deny($usertype, 'controllers/Employees/edit');
	$this->Acl->allow($usertype, 'controllers/Employees/editEmp');	
	$this->Acl->allow($usertype, 'controllers/Employees/divisionEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/districtEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/upazillaEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/unionEmployees');
	
	$this->Acl->allow($usertype, 'controllers/Attendances/assign');
	
	$this->Acl->allow($usertype, 'controllers/Transactions/payAttendance');
	$this->Acl->allow($usertype, 'controllers/Transactions/transferMoneyToDistrict');
	$this->Acl->deny($usertype, 'controllers/Transactions/transferMoneyToUnion');
	$this->Acl->allow($usertype, 'controllers/Transactions/fundRma');
	

	// Allow Admins to most of the custom CRUD function - default
	$usertype->id = 3;
	$this->Acl->deny($usertype, 'controllers');
	$this->Acl->allow($usertype, 'controllers/Divisions');
	$this->Acl->allow($usertype, 'controllers/Districts');
	
	//define DIVISION level user accesibility
	$usertype->id = 5;
	$this->Acl->deny($usertype, 'controllers');
	$this->Acl->deny($usertype, 'controllers/Users/info');
	//all the functions require deep level authentication
	$this->Acl->allow($usertype, 'controllers/Users/dashboard');
	$this->Acl->allow($usertype, 'controllers/Divisions/overview');
	$this->Acl->allow($usertype, 'controllers/Districts/overview');
	$this->Acl->allow($usertype, 'controllers/Upazillas/overview');
	$this->Acl->allow($usertype, 'controllers/Unions/overview');
	$this->Acl->allow($usertype, 'controllers/Accounts/detail');
	$this->Acl->allow($usertype, 'controllers/Employees/info');
	$this->Acl->allow($usertype, 'controllers/Employees/divisionEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/districtEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/upazillaEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/unionEmployees');	
	$this->Acl->deny($usertype, 'controllers/Accounts/makePrimary');
	$this->Acl->deny($usertype, 'controllers/Districts/add');
	$this->Acl->deny($usertype, 'controllers/Accounts/addDistrictAccount');
	$this->Acl->deny($usertype, 'controllers/Transactions/DivUserFundDistrict');
	
	//define DISTRICT level user accesibility
	$usertype->id = 6;
	$this->Acl->deny($usertype, 'controllers');
	$this->Acl->deny($usertype, 'controllers/Users/info');
	$this->Acl->allow($usertype, 'controllers/Users/changePassword');
	$this->Acl->allow($usertype, 'controllers/Users/dashboard');

	$this->Acl->allow($usertype, 'controllers/Accounts/detail');
	$this->Acl->allow($usertype, 'controllers/Accounts/addUpazillaAccount');
	$this->Acl->allow($usertype, 'controllers/Accounts/addUnionAccount');
	$this->Acl->allow($usertype, 'controllers/Accounts/makePrimary');
	
	$this->Acl->allow($usertype, 'controllers/Districts/overview');
	
	$this->Acl->allow($usertype, 'controllers/Upazillas/overview');
	$this->Acl->allow($usertype, 'controllers/Upazillas/add');
	$this->Acl->allow($usertype, 'controllers/Upazillas/edit');
	
	$this->Acl->allow($usertype, 'controllers/Unions/overview');
	$this->Acl->allow($usertype, 'controllers/Unions/add');
	$this->Acl->allow($usertype, 'controllers/Unions/edit');

	$this->Acl->allow($usertype, 'controllers/Employees/addEmployee');	
	$this->Acl->allow($usertype, 'controllers/Employees/info');
	$this->Acl->allow($usertype, 'controllers/Employees/districtEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/upazillaEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/unionEmployees');	
	$this->Acl->allow($usertype, 'controllers/Employees/addUnionEmployee');
	$this->Acl->allow($usertype, 'controllers/Employees/editEmp');
	
	$this->Acl->deny($usertype, 'controllers/Transactions/DisUserFundUpazilla');
	$this->Acl->allow($usertype, 'controllers/Transactions/payAttendance');
	$this->Acl->allow($usertype, 'controllers/Transactions/fundRma');
	
	$this->Acl->allow($usertype, 'controllers/Attendances/assign');

//	$this->Acl->allow($usertype, 'controllers/Unions/overview');

	//define upazilla level user accesibility
	$usertype->id = 7;
	$this->Acl->deny($usertype, 'controllers');
	$this->Acl->deny($usertype, 'controllers/Users/info');
	$this->Acl->allow($usertype, 'controllers/Users/dashboard');
	$this->Acl->allow($usertype, 'controllers/Upazillas/overview');
	$this->Acl->allow($usertype, 'controllers/Employees/upazillaEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/unionEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/info');
	$this->Acl->allow($usertype, 'controllers/Unions/overview');
	$this->Acl->allow($usertype, 'controllers/Unions/add');
	$this->Acl->allow($usertype, 'controllers/Accounts/detail');
	$this->Acl->allow($usertype, 'controllers/Accounts/addUnionAccount');
	$this->Acl->allow($usertype, 'controllers/Accounts/makePrimary');
	$this->Acl->allow($usertype, 'controllers/Transactions/UpUserFundUnion');
	
		
	//define union level user accesibility
	$usertype->id = 8;
	$this->Acl->deny($usertype, 'controllers');
	$this->Acl->deny($usertype, 'controllers/Users/info');
	$this->Acl->allow($usertype, 'controllers/Users/dashboard');
	$this->Acl->allow($usertype, 'controllers/Unions/overview');
	$this->Acl->allow($usertype, 'controllers/Employees/unionEmployees');
	$this->Acl->allow($usertype, 'controllers/Employees/info');
	$this->Acl->allow($usertype, 'controllers/Accounts/detail');
	$this->Acl->allow($usertype, 'controllers/Attendances/assign');
	$this->Acl->allow($usertype, 'controllers/Transactions/payAttendance');
	$this->Acl->allow($usertype, 'controllers/Employees/addUnionEmployee');
	$this->Acl->allow($usertype, 'controllers/Accounts/makePrimary');
	
    // allow basic users to log out
	$this->Acl->allow($usertype, 'controllers/Users/logout');
	$this->Acl->allow($usertype, 'controllers/Pages/display');
	
    // we add an exit to avoid an ugly "missing views" error message
    echo "all done";
    exit;
}

/****login and logout*****/
public function login() {
    if ($this->request->is('post')) {
        if ($this->Auth->login()) {
            return $this->redirect($this->Auth->redirect());
        }
        $this->Session->setFlash(__('Your username or password was incorrect.'));
    }
    if ($this->Session->read('Auth.User')) {
        $this->Session->setFlash('You are logged in!');
        return $this->redirect('/');
    }
}

public function logout() {
	$this->Session->setFlash('Good-Bye');
	$this->redirect($this->Auth->logout());
}
/*****functions for using inside elements*****/
public function getUserInfo($id){
	if ($this->User->exists($id)) {
		$user = $this->User->findById($id);
		return $user;
	}else {
		$user = null;
		return $user;
	}
	
		
}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->Paginator->settings = array(
	        'conditions' => array('User.usertype_id <>' => 1),
	        'limit' => 10
	    );
		$this->set('users', $this->Paginator->paginate());
	}
/**
 * dashboard method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function dashboard($id = null) {
			$uData = $this->Session->read('Auth.User');
			$userType = (int)$uData['Usertype']['id'];
			$user_id = (int)$uData['id'];
			$ref_id = (int)$uData['ref_id'];
			$data = 0;
		
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($id!=$user_id) {
            $this->Session->setFlash(
                __('You are not authorized to access another users dashboard.'),
                'default',
                array(),
                'auth'
            );
            return $this->redirect($this->Auth->redirectUrl());
        }
        
        $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
        $ref_type = "HeadQuarter Level";
        $ref_name = "HQ";
        if($userType == 8){	//union level user
	        $this->User->BindModel(
		        array('belongsTo' => array(
	                'Union' => array(
	                    'className' => 'Union',
	                    'foreignKey' => 'ref_id'
	                )
	            )
	        ));
	        
	        $data = $this->User->find('first', $options);
	        $ref_name = $data['Union']['name'];
	        $ref_type = "Union";
		}else if($userType == 7){	//upazilla level user
	        $this->User->BindModel(
		        array('belongsTo' => array(
	                'Upazilla' => array(
	                    'className' => 'Upazilla',
	                    'foreignKey' => 'ref_id'
	                )
	            )
	        ));
	        
	        $data = $this->User->find('first', $options);
	        $ref_name = $data['Upazilla']['name'];
	        $ref_type = "Upazilla";
		}else if($userType == 6){	//District level user
	        $this->User->BindModel(
		        array('belongsTo' => array(
	                'District' => array(
	                    'className' => 'District',
	                    'foreignKey' => 'ref_id'
	                )
	            )
	        ));
	        
	        $data = $this->User->find('first', $options);
	        $ref_name = $data['District']['name'];
	        $ref_type = "District";
		}else if($userType == 5){	//Division level user
	        $this->User->BindModel(
		        array('belongsTo' => array(
	                'Division' => array(
	                    'className' => 'Division',
	                    'foreignKey' => 'ref_id'
	                )
	            )
	        ));
	        
	        $data = $this->User->find('first', $options);
	        $ref_name = $data['Division']['name'];
	        $ref_type = "Division";
		}else{
			$data = $this->User->find('first', $options);
				
		}
		
		$this->set('user', $data);
		$this->set('ref_name', $ref_name);
		$this->set('ref_type', $ref_type);
	}
/**
 * info method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function info($id = null) {
		$data = 0;
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
	    $ref_type = "HeadQuarter Level";
        $ref_name = "HQ";
        $data = $this->User->find('first', $options);
        $userType = $data['Usertype']['id'];
        if($userType == 8){	//union level user
	        $this->User->BindModel(
		        array('belongsTo' => array(
	                'Union' => array(
	                    'className' => 'Union',
	                    'foreignKey' => 'ref_id'
	                )
	            )
	        ));
	        
	        $data = $this->User->find('first', $options);
	        $ref_name = $data['Union']['name'];
	        $ref_type = "Union";
		}else if($userType == 7){	//upazilla level user
	        $this->User->BindModel(
		        array('belongsTo' => array(
	                'Upazilla' => array(
	                    'className' => 'Upazilla',
	                    'foreignKey' => 'ref_id'
	                )
	            )
	        ));
	        
	        $data = $this->User->find('first', $options);
	        $ref_name = $data['Upazilla']['name'];
	        $ref_type = "Upazilla";
		}else if($userType == 6){	//District level user
	        $this->User->BindModel(
		        array('belongsTo' => array(
	                'District' => array(
	                    'className' => 'District',
	                    'foreignKey' => 'ref_id'
	                )
	            )
	        ));
	        
	        $data = $this->User->find('first', $options);
	        $ref_name = $data['District']['name'];
	        $ref_type = "District";
		}else if($userType == 5){	//Division level user
	        $this->User->BindModel(
		        array('belongsTo' => array(
	                'Division' => array(
	                    'className' => 'Division',
	                    'foreignKey' => 'ref_id'
	                )
	            )
	        ));
	        
	        $data = $this->User->find('first', $options);
	        $ref_name = $data['Division']['name'];
	        $ref_type = "Division";
		}else{
			$data = $this->User->find('first', $options);
				
		}		
//		$data = $this->User->find('first', $options);
		
		$this->set('user', $data);
		$this->set('ref_name', $ref_name);
		$this->set('ref_type', $ref_type);
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add HQ Users method
 *
 * @return void
 */
	public function addHqUsers() {
		$currentUser = $this->Session->read('Auth.User');
		$currentUserId = (int)$currentUser['id'];
		if ($this->request->is('post')) {
			$this->request->data['User']['user_id'] = $currentUserId;
			$this->request->data['User']['ref_id'] = null;
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'info',$this->User->getLastInsertId()));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$usertypes = $this->User->Usertype->find('list',array('conditions' => array('AND'=>array('Usertype.id >=' => 2, 'Usertype.id <=' => 4))));
		$this->set(compact('usertypes'));
	}
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$usertypes = $this->User->Usertype->find('list');
		$this->set(compact('usertypes'));
	}
/**
 * add Division user method
 *
 * @return void
 */
	public function addDivisionUser() {
		$currentUser = $this->Session->read('Auth.User');
		$currentUserId = (int)$currentUser['id'];
		if ($this->request->is('post')) {
			$this->request->data['User']['user_id'] = $currentUserId;
			$this->request->data['User']['usertype_id'] = 5;	 //MUST CHANGE
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The Division level user has been saved.'));
				return $this->redirect(array('action' => 'info',$this->User->getLastInsertId()));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$usertypes = $this->User->Usertype->find('list');
		$this->loadModel('Division');
		$Divisions = $this->Division->recursive = -1;
		$Divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('usertypes','Divisions'));
		
	}


/**
 * add District user method
 *
 * @return void
 */
	public function addDistrictUser() {
		$currentUser = $this->Session->read('Auth.User');
		$currentUserId = (int)$currentUser['id'];
		if ($this->request->is('post')) {
			$this->request->data['User']['user_id'] = $currentUserId;
			$this->request->data['User']['usertype_id'] = 6;	 //MUST CHANGE
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The District level user has been saved.'));
				return $this->redirect(array('action' => 'info',$this->User->getLastInsertId()));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$usertypes = $this->User->Usertype->find('list');
		$this->loadModel('Division');
		$Divisions = $this->Division->recursive = -1;
		$Divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('usertypes','Divisions'));
	}

/**
 * add Upazilla user method
 *
 * @return void
 */
	public function addUpazillaUser() {
		$currentUser = $this->Session->read('Auth.User');
		$currentUserId = (int)$currentUser['id'];
		if ($this->request->is('post')) {
			$this->request->data['User']['user_id'] = $currentUserId;
			$this->request->data['User']['usertype_id'] = 7;	 //MUST CHANGE
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The Upazilla level user has been saved.'));
				return $this->redirect(array('action' => 'info',$this->User->getLastInsertId()));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$usertypes = $this->User->Usertype->find('list');
		$this->loadModel('Division');
		$Divisions = $this->Division->recursive = -1;
		$Divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('usertypes','Divisions'));
	}
	
/**
 * add Union user method
 *
 * @return void
 */
	public function addUnionUser() {
		$currentUser = $this->Session->read('Auth.User');
		$currentUserId = (int)$currentUser['id'];
		if ($this->request->is('post')) {
			$this->request->data['User']['user_id'] = $currentUserId;
			$this->request->data['User']['usertype_id'] = 8;	 //MUST CHANGE
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The Union level user has been saved.'));
				return $this->redirect(array('action' => 'info',$this->User->getLastInsertId()));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$usertypes = $this->User->Usertype->find('list');
		$this->loadModel('Division');
		$Divisions = $this->Division->recursive = -1;
		$Divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('usertypes','Divisions'));
	}


/**
 * change password method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function changePassword() {
		$currentUser = $this->Session->read('Auth.User');
		$currentUserId = (int)$currentUser['id'];
		$id = $currentUserId;
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['User']['user_id'] = $currentUserId;
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'info',$id));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$usertypes = $this->User->Usertype->find('list',array('conditions' => array('AND'=>array('Usertype.id >=' => 2, 'Usertype.id <=' => 10))));
		$this->set(compact('usertypes'));
	}



/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$currentUser = $this->Session->read('Auth.User');
		$currentUserId = (int)$currentUser['id'];
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['User']['user_id'] = $currentUserId;
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'info',$id));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$usertypes = $this->User->Usertype->find('list',array('conditions' => array('AND'=>array('Usertype.id >=' => 2, 'Usertype.id <=' => 10))));
		$this->set(compact('usertypes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function beforeSave($options = array()) {
        $this->data['User']['password'] = AuthComponent::password(
          $this->data['User']['password']
        );
        return true;
    }
	
	
	}
