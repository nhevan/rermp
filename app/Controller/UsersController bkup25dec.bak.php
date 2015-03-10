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
	$this->Auth->allow('getUserInfo');
}

public function initDB() {
    $usertype = $this->User->Usertype;

    // Allow super admins to everything
	$usertype->id = 1;
	$this->Acl->allow($usertype, 'controllers');

	// Allow HQ Viewrs view to everything
    $usertype->id = 2;
    $this->Acl->deny($usertype, 'controllers');
	$this->Acl->allow($usertype, 'controllers/Divisions');
	$this->Acl->allow($usertype, 'controllers/Districts/overview');
	$this->Acl->allow($usertype, 'controllers/Upazillas/overview');
	$this->Acl->allow($usertype, 'controllers/Unions/overview');

		
	$usertype->id = 3;
	$this->Acl->deny($usertype, 'controllers');
	$this->Acl->allow($usertype, 'controllers/Divisions');
	$this->Acl->allow($usertype, 'controllers/Districts');
	
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
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * info method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function info($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
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
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$usertypes = $this->User->Usertype->find('list');
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
			$this->request->data['User']['usertype_id'] = 4;	 //MUST CHANGE
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The Division level user has been saved.'));
				return $this->redirect(array('action' => 'index'));
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
			$this->request->data['User']['usertype_id'] = 3;	 //MUST CHANGE
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The District level user has been saved.'));
				return $this->redirect(array('action' => 'index'));
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
			$this->request->data['User']['usertype_id'] = 5;	 //MUST CHANGE
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The Upazilla level user has been saved.'));
				return $this->redirect(array('action' => 'index'));
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
			$this->request->data['User']['usertype_id'] = 6;	 //MUST CHANGE
			$this->request->data['User']['creationTime'] = date('Y-m-d H:m:s');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The Union level user has been saved.'));
				return $this->redirect(array('action' => 'index'));
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
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$usertypes = $this->User->Usertype->find('list');
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
