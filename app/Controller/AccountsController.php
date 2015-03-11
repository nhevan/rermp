<?php
App::uses('AppController', 'Controller');
/**
 * Accounts Controller
 *
 * @property Account $Account
 * @property PaginatorComponent $Paginator
 */
class AccountsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Account->recursive = 0;
		$this->set('accounts', $this->Paginator->paginate());
	}
//making primary	
	public function makePrimary($account_id) {
		
		
		$this->Account->recursive = -1;
		$tmp = $this->Account->findById($account_id);
		$accType = $tmp['Account']['accounttype_id'];
		$accRefId = $tmp['Account']['refId'];
		$accounts =  $this->Paginator->paginate(array('accounttype_id'=>$accType,'refId'=>$accRefId));
		foreach($accounts as $account){
			$this->Account->id = $account['Account']['id'];
			if($account['Account']['id']==$account_id){
				$this->Account->saveField('isPrimary',1);


			}else{

				$this->Account->saveField('isPrimary',0);
			}
		}
		if($accType==2)
			return $this->redirect(array('controller' => 'divisions', 'action' => 'overview',$accRefId));
		if($accType==3)
			return $this->redirect(array('controller' => 'districts', 'action' => 'overview',$accRefId));
		if($accType==4)
			return $this->redirect(array('controller' => 'upazillas', 'action' => 'overview',$accRefId));
		if($accType==5)
			return $this->redirect(array('controller' => 'unions', 'action' => 'overview',$accRefId));
		if($accType==6)
			return $this->redirect(array('controller' => 'employees', 'action' => 'info',$accRefId));	
		$this->set('accounts',$accounts);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
		$this->set('account', $this->Account->find('first', $options));
	}
	
/**
 * detail method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function detail($id = null) {
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
		$account = $this->Account->find('first', $options);

		
		//authentication begins
		$user_id = (int)$this->Session->read('Auth.User.id');
		$uType = $this->Session->read('Auth.User.usertype_id');
		$ref_id = $this->Session->read('Auth.User.ref_id');
		$account_type = $account['Accounttype']['id'];
		$account_ref_id = $account['Account']['refId'];
		if($uType == 6){ //for DISTRICT level users
			if($account_type == 6){ //check if employee type account
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'Employee' => array(
				            'className' => 'Employee',
				            'foreignKey' => 'refId'
				        )
				    )
					)
				);
				$this->Account->Behaviors->load('Containable');
				$this->Account->contain('Transaction','Accounttype','Employee.Union.Upazilla.District.id');
				$account = $this->Account->find('first', $options);
				//$emp_id = $account['Employee']['id'];
				$emp_district = $account['Employee']['Union']['Upazilla']['District']['id'];
				if($emp_district!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details Employees that do no belong under your District.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'users', 'action' => 'dashboard',$user_id));
	            }
			}elseif($account_type == 3){ //check if district type account
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'District' => array(
				            'className' => 'District',
				            'foreignKey' => 'refId'
				        )
				    )
				));
				$this->Account->Behaviors->load('Containable');
				$this->Account->contain('Transaction','Accounttype','District');
				$account = $this->Account->find('first', $options);
				$district_id = $account['District']['id'];
				if($district_id!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details from other Districts.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'districts', 'action' => 'overview',$ref_id));
	            }

			}elseif($account_type == 4){ //check if upazilla type account
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'Upazilla' => array(
				            'className' => 'Upazilla',
				            'foreignKey' => 'refId'
				        )
				    )
				));
				$this->Account->Behaviors->load('Containable');
				$this->Account->contain('Transaction','Accounttype','Upazilla','Upazilla.District.id');
				$account = $this->Account->find('first', $options);
				$district_id = $account['Upazilla']['District']['id'];
				if($district_id!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details from other Districts.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'districts', 'action' => 'overview',$ref_id));
	            }

			}
			elseif($account_type == 5){ //check if union type account under specific Upazilla
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'Union' => array(
				            'className' => 'Union',
				            'foreignKey' => 'refId'
				        )
				    )
				));
				$this->Account->Behaviors->load('Containable');
				$this->Account->contain('Transaction','Accounttype','Union.Upazilla.District');
				$account = $this->Account->find('first', $options);
				$district_id = $account['Union']['Upazilla']['District']['id'];
				if($district_id!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details from other union or District Account.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'districts', 'action' => 'overview',$ref_id));
	            }

			}

		}
		if($uType == 7){ //for upazilla level users
			if($account_type == 6){ //check if employee type account
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'Employee' => array(
				            'className' => 'Employee',
				            'foreignKey' => 'refId'
				        )
				    )
					)
				);
				$this->Account->Behaviors->load('Containable');
				$this->Account->contain('Transaction','Accounttype','Employee.Union.Upazilla.id');
				$account = $this->Account->find('first', $options);
				//$emp_id = $account['Employee']['id'];
				$emp_upazilla = $account['Employee']['Union']['Upazilla']['id'];
				if($emp_upazilla!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details Employees that do no belong under your Upazilla.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'users', 'action' => 'dashboard',$user_id));
	            }
			}
			elseif($account_type == 4){ //check if upazilla type account
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'Upazilla' => array(
				            'className' => 'Upazilla',
				            'foreignKey' => 'refId'
				        )
				    )
				));
				$this->Account->Behaviors->load('Containable');
				$this->Account->contain('Transaction','Accounttype','Upazilla','Upazilla.Union.id');
				$account = $this->Account->find('first', $options);
				$upazilla_id = $account['Upazilla']['id'];
				if($upazilla_id!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details from other upazillas.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'upazillas', 'action' => 'overview',$ref_id));
	            }

			}
			elseif($account_type == 5){ //check if union type account under specific Upazilla
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'Union' => array(
				            'className' => 'Union',
				            'foreignKey' => 'refId'
				        )
				    )
				));
				$this->Account->Behaviors->load('Containable');
				$this->Account->contain('Transaction','Accounttype','Union.Upazilla');
				$account = $this->Account->find('first', $options);
				$upazilla_id = $account['Union']['Upazilla']['id'];
				if($upazilla_id!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details from other union or Upazilla Account.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'upazillas', 'action' => 'overview',$ref_id));
	            }

			}
		}
		if($uType == 8){ //for union level users
			if($account_type == 6){ //check if employee type account
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'Employee' => array(
				            'className' => 'Employee',
				            'foreignKey' => 'refId'
				        )
				    )
				));
				$account = $this->Account->find('first', $options);
				$emp_id = $account['Employee']['id'];
				$emp_union = $account['Employee']['union_id'];
				if($emp_union!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details Employees that do no belong under your Union.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'users', 'action' => 'dashboard',$user_id));
	            }
			}
			elseif($account_type == 5){ //check if union type account
				$this->Account->BindModel(
				    array('belongsTo' => array(
				        'Union' => array(
				            'className' => 'Union',
				            'foreignKey' => 'refId'
				        )
				    )
				));
				$account = $this->Account->find('first', $options);
				$union_id = $account['Union']['id'];
				if($union_id!=$ref_id){
					$this->Session->setFlash(
		                __('You are not authorized to view account details from other unions.'),
		                'default',
		                array(),
		                'auth'
		            );
		            return $this->redirect(array('controller' => 'accounts', 'action' => 'detail',$ref_id));
	            }
			}
		}
		
		
		$this->set('account', $account);

	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Account->create();
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		}
		$accounttypes = $this->Account->Accounttype->find('list');
		$this->set(compact('accounttypes'));
	}

/**
 * add Division account method
 *
 * @return void
 */
	public function addDivisionAccount() {
		if ($this->request->is('post')) {
			$this->request->data['Account']['accounttype_id'] = 2;
			$this->request->data['Account']['created_on'] = date('Y-m-d H:m:s');
			$this->Account->create();
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		}
		$this->loadModel('Division');
		$Divisions = $this->Division->recursive = -1;
		$Divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		$accounttypes = $this->Account->Accounttype->find('list');
		$this->set(compact('accounttypes','Divisions'));
	}
	
/**
 * add District account method
 *
 * @return void
 */
	public function addDistrictAccount() {
		if ($this->request->is('post')) {
			$this->request->data['Account']['accounttype_id'] = 3;
			$this->request->data['Account']['created_on'] = date('Y-m-d H:m:s');
			$this->Account->create();
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved.'));
				return $this->redirect(array('action' => 'detail',$this->Account->getLastInsertID()));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		}
		$this->loadModel('Division');
		$Divisions = $this->Division->recursive = -1;
		$Divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		$accounttypes = $this->Account->Accounttype->find('list');
		$this->set(compact('accounttypes','Divisions'));
	}
	
/**
 * add Upazilla account method
 *
 * @return void
 */
	public function addUpazillaAccount() {
		if ($this->request->is('post')) {
			$this->request->data['Account']['accounttype_id'] = 4;
			$this->request->data['Account']['created_on'] = date('Y-m-d H:m:s');
			$this->Account->create();
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved.'));
				return $this->redirect(array('controller'=>'accounts','action' => 'detail',$this->Account->getLastInsertID()));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		}
		$this->loadModel('Division');
		$Divisions = $this->Division->recursive = -1;
		$Divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		$accounttypes = $this->Account->Accounttype->find('list');
		$this->set(compact('accounttypes','Divisions'));
	}


/**
 * add Union account method
 *
 * @return void
 */
	public function addUnionAccount() {
		if ($this->request->is('post')) {
			$this->request->data['Account']['accounttype_id'] = 5;
			$this->request->data['Account']['created_on'] = date('Y-m-d H:m:s');
			$this->Account->create();
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved.'));
				return $this->redirect(array('action' => 'detail',$this->Account->getLastInsertID()));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		}
		$this->loadModel('Division');
		$Divisions = $this->Division->recursive = -1;
		$Divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		$accounttypes = $this->Account->Accounttype->find('list');
		$this->set(compact('accounttypes','Divisions'));
	}


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Account->exists($id)) {
			throw new NotFoundException(__('Invalid account'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Account->save($this->request->data)) {
				$this->Session->setFlash(__('The account has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The account could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Account.' . $this->Account->primaryKey => $id));
			$this->request->data = $this->Account->find('first', $options);
		}
		$accounttypes = $this->Account->Accounttype->find('list');
		$this->set(compact('accounttypes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Account->id = $id;
		if (!$this->Account->exists()) {
			throw new NotFoundException(__('Invalid account'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Account->delete()) {
			$this->Session->setFlash(__('The account has been deleted.'));
		} else {
			$this->Session->setFlash(__('The account could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
