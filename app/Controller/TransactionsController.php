<?php
App::uses('AppController', 'Controller');
/**
 * Transactions Controller
 *
 * @property Transaction $Transaction
 * @property PaginatorComponent $Paginator
 */
class TransactionsController extends AppController {

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
		$this->Transaction->recursive = 0;
		$this->set('transactions', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Transaction->exists($id)) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		$options = array('conditions' => array('Transaction.' . $this->Transaction->primaryKey => $id));
		$this->set('transaction', $this->Transaction->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Transaction->create();
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$users = $this->Transaction->User->find('list');
		//$employees = $this->Transaction->Employee->find('list');
		$accounts = $this->Transaction->Account->find('list');
		$this->set(compact('users', 'accounts'));
	}
	
/**
 * transfer Money to Division method
 *
 * @return void
 */
	public function transferMoneyToDivision() {
		if ($this->request->is('post')) {
			$currentUser = $this->Session->read('Auth.User');
			$currentUserId = (int)$currentUser['id'];
			$this->request->data['Transaction']['datetime'] = date('Y-m-d H:m:s'); //TIME OF TRANSACTION
			$this->request->data['Transaction']['user_id'] = 1; //CURRENT USER ID
			$this->request->data['Transaction']['account_from'] = 9; //set the account id according to current USER level, like district levl account or watevr
			$tmp = $this->request->data['Transaction']['account_id'];
			$targetAccount = $this->Transaction->Account->findById($tmp);
			$sourceAccount = $this->Transaction->Account->findById($this->request->data['Transaction']['account_from']);
			$availableBalance = $sourceAccount['Account']['balance'];
			if((int)$availableBalance < (int)$this->request->data['Transaction']['amount']){
				$this->Session->setFlash(__('The transaction could not be initiated because of INSUFFICIENT fund.'));
				return $this->redirect(array('action' => 'transferMoneyToDivision'));
			}
			$newBalance = (int)$targetAccount['Account']['balance'] + (int)$this->request->data['Transaction']['amount'];
			$this->request->data['Transaction']['previous_balance'] = $targetAccount['Account']['balance'];
			$this->request->data['Transaction']['current_balance'] = $newBalance;
			
			$this->Transaction->Account->id = $this->request->data['Transaction']['account_from'];
			$this->Transaction->Account->saveField('balance',$availableBalance - (int)$this->request->data['Transaction']['amount']);
			
			$this->Transaction->Account->id = $tmp;
			$this->Transaction->Account->saveField('balance',$newBalance);
			
			$this->Transaction->create(); //creating Income transaction
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
			
			$this->Transaction->create(); //creating Expenditure transaction
			$this->request->data['Transaction']['account_from'] = $this->request->data['Transaction']['account_id'];
			$this->request->data['Transaction']['account_id'] = 9;
			$this->request->data['Transaction']['type'] = 2;
			$this->request->data['Transaction']['previous_balance'] = $availableBalance;
			$this->request->data['Transaction']['current_balance'] = $availableBalance - (int)$this->request->data['Transaction']['amount'];

			
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The expenditure transaction has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$users = $this->Transaction->User->find('list');
		//$employees = $this->Transaction->Employee->find('list');
		$this->Transaction->Account->recursive = -1;
		$accounts = $this->Transaction->Account->findAllByAccounttypeId(2); // looking for division type account
		
		$this->loadModel('Division');
		$this->Division->recursive = -1;
		$divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('users', 'accounts','divisions'));
	}
	
/**
 * transfer Money to District method
 *
 * @return void
 */
	public function transferMoneyToDistrict() {
		if ($this->request->is('post')) {
			$currentUser = $this->Session->read('Auth.User');
			$currentUserId = (int)$currentUser['id'];
			$uType =  (int)$currentUser['usertype_id'];
			$this->request->data['Transaction']['datetime'] = date('Y-m-d H:m:s'); //TIME OF TRANSACTION
			$this->request->data['Transaction']['user_id'] = $currentUserId; //CURRENT USER ID
//			if($uType)
			$this->request->data['Transaction']['account_from'] = 1; //BASE ACCOUNT
			
			$tmp = $this->request->data['Transaction']['account_id'];
			$targetAccount = $this->Transaction->Account->findById($tmp);

			$sourceAccount = $this->Transaction->Account->findById($this->request->data['Transaction']['account_from']);
			$availableBalance = $sourceAccount['Account']['balance'];
			if((int)$availableBalance < (int)$this->request->data['Transaction']['amount']){
				$this->Session->setFlash(__('The transaction could not be initiated because of INSUFFICIENT fund.'));
				return $this->redirect(array('action' => 'transferMoneyToDivision'));
			}
			$newBalance = (int)$targetAccount['Account']['balance'] + (int)$this->request->data['Transaction']['amount'];
			$this->request->data['Transaction']['previous_balance'] = $targetAccount['Account']['balance'];
			$this->request->data['Transaction']['current_balance'] = $newBalance;
			
			$this->Transaction->Account->id = $this->request->data['Transaction']['account_from'];
			$this->Transaction->Account->saveField('balance',$availableBalance - (int)$this->request->data['Transaction']['amount']);
			
			$this->Transaction->Account->id = $tmp;
			$this->Transaction->Account->saveField('balance',$newBalance);
			
			$this->Transaction->create(); //creating Income transaction
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
			
			$this->Transaction->create(); //creating Expenditure transaction
			$this->request->data['Transaction']['account_from'] = $this->request->data['Transaction']['account_id'];
			$this->request->data['Transaction']['account_id'] = 1; //BASE ACCOUNT
			$this->request->data['Transaction']['type'] = 2;
			$this->request->data['Transaction']['previous_balance'] = $availableBalance;
			$this->request->data['Transaction']['current_balance'] = $availableBalance - (int)$this->request->data['Transaction']['amount'];

			
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The expenditure transaction has been saved.'));
				return $this->redirect(array('controller'=>'Accounts','action' => 'detail',$this->request->data['Transaction']['account_from']));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$users = $this->Transaction->User->find('list');
		
		
		$this->loadModel('Division');
		$this->Division->recursive = -1;
		$divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('users','divisions'));
	}
	
/**
 * transfer Money to Upazilla method
 *
 * @return void
 */
	public function transferMoneyToUpazilla() {
		if ($this->request->is('post')) {
			$currentUser = $this->Session->read('Auth.User');
			$currentUserId = (int)$currentUser['id'];
			$this->request->data['Transaction']['datetime'] = date('Y-m-d H:m:s'); //TIME OF TRANSACTION
			$this->request->data['Transaction']['user_id'] = $currentUserId; //CURRENT USER ID
			$this->request->data['Transaction']['account_from'] = 9; //set the account id according to current USER level, like district levl account or watevr
			$tmp = $this->request->data['Transaction']['account_id'];
			$targetAccount = $this->Transaction->Account->findById($tmp);
			$sourceAccount = $this->Transaction->Account->findById($this->request->data['Transaction']['account_from']);
			$availableBalance = $sourceAccount['Account']['balance'];
			if((int)$availableBalance < (int)$this->request->data['Transaction']['amount']){
				$this->Session->setFlash(__('The transaction could not be initiated because of INSUFFICIENT fund.'));
				return $this->redirect(array('action' => 'transferMoneyToDivision'));
			}
			$newBalance = (int)$targetAccount['Account']['balance'] + (int)$this->request->data['Transaction']['amount'];
			$this->request->data['Transaction']['previous_balance'] = $targetAccount['Account']['balance'];
			$this->request->data['Transaction']['current_balance'] = $newBalance;
			
			$this->Transaction->Account->id = $this->request->data['Transaction']['account_from'];
			$this->Transaction->Account->saveField('balance',$availableBalance - (int)$this->request->data['Transaction']['amount']);
			
			$this->Transaction->Account->id = $tmp;
			$this->Transaction->Account->saveField('balance',$newBalance);
			
			$this->Transaction->create(); //creating Income transaction
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
			
			$this->Transaction->create(); //creating Expenditure transaction
			$this->request->data['Transaction']['account_from'] = $this->request->data['Transaction']['account_id'];
			$this->request->data['Transaction']['account_id'] = 9;
			$this->request->data['Transaction']['type'] = 2;
			$this->request->data['Transaction']['previous_balance'] = $availableBalance;
			$this->request->data['Transaction']['current_balance'] = $availableBalance - (int)$this->request->data['Transaction']['amount'];

			
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The expenditure transaction has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$users = $this->Transaction->User->find('list');
		
		
		$this->loadModel('Division');
		$this->Division->recursive = -1;
		$divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('users','divisions'));
	}
	
	private function transfer($account_from, $account_to, $formData){
		$ref_id = $this->Session->read('Auth.User.ref_id');
	//	return true;//if successfull
		//if UNsuccessfull
		$amounttoPay = (int)$formData['Transaction']['amount'];
		$targetAccount = $this->Transaction->Account->findById($account_to);
		$sourceAccount = $this->Transaction->Account->findById($account_from);
		
		$availableBalance = $sourceAccount['Account']['balance'];
		$targetPreviousBalance = $targetAccount['Account']['balance'];
		$newBalance = (int)$targetAccount['Account']['balance'] + (int)$amounttoPay;
		if((int)$availableBalance < (int)$amounttoPay){
			$this->Session->setFlash(__('The transaction could not be initiated because of INSUFFICIENT fund ON the Paying Account.'));
			return false;
		}
		
		//populating formData starts
		$currentUser = $this->Session->read('Auth.User');
		$currentUserId = (int)$currentUser['id'];
		$upazillaId = (int)$currentUser['ref_id'];
		$formData['Transaction']['datetime'] = date('Y-m-d H:m:s'); //TIME OF TRANSACTION
		$formData['Transaction']['user_id'] = $currentUserId; //CURRENT USER ID
		$formData['Transaction']['account_id'] = $account_to;
		$formData['Transaction']['account_from'] = $account_from;
		$formData['Transaction']['type'] = 1; //represents Income
		$formData['Transaction']['previous_balance'] = $targetPreviousBalance;
		$formData['Transaction']['current_balance'] = $targetPreviousBalance + $amounttoPay;
		//populating formData ends
		//Recording Income
		$this->Transaction->create();
		if ($this->Transaction->save($formData)) {
			$this->Transaction->Account->id = $account_to;
			$this->Transaction->Account->saveField('balance',$newBalance);
			$this->Session->setFlash(__('The expenditure transaction has been saved.'));

		} else {
			$this->Session->setFlash(__('The Income transaction could not be saved. Please try again.'));
			return false;
		}
		//editing $formData to save as Expenditure
		$formData['Transaction']['account_id'] = $account_from;
		$formData['Transaction']['account_from'] = $account_to;
		$formData['Transaction']['type'] = 2; //represents Expenditure
		$formData['Transaction']['previous_balance'] = $availableBalance;
		$formData['Transaction']['current_balance'] = $availableBalance - (int)$amounttoPay;
		//editing $formData to save as Expenditure ends
		//Recording Expenditure 
		$this->Transaction->create();
		if ($this->Transaction->save($formData)) {
			$this->Transaction->Account->id = $account_from;
			$this->Transaction->Account->saveField('balance',$availableBalance - (int)$amounttoPay);
			$this->Session->setFlash(__('The expenditure transaction has been saved.'));
			return true;
		} else {
			$this->Session->setFlash(__('The expenditure could not be saved. Please try again.'));
			return false;
		}
	}
	
/**
 * transfer Money to District method
 *
 * @return void
 */
	public function DivUserFundDistrict($districtId = null) {
		$ref_id = $this->Session->read('Auth.User.ref_id');
		if($districtId == null){
			$this->Session->setFlash(__('Please do not try anything abnormal. Your account will be permanently blocked.'));
			
			if($ref_id!=null)
				return $this->redirect(array('controller'=>'districts','action' => 'overview',$ref_id));
			else
				return $this->redirect(array('controller'=>'users','action' => 'logout'));
		}
		if ($this->request->is('post')) {
			$referer = $this->referer();
			$chips = explode("/",$referer);
			$chipCount = sizeof($chips);

			$districtIdFromForm = $this->request->data['Transaction']['district_id'];
			$districtIdFromRef = $chips[$chipCount-1];

			if($districtIdFromForm!=$districtIdFromRef || $districtIdFromForm!=$districtId || $districtId!=$districtIdFromRef){
				//stop the transaction, something is not right
				//throw error
				return;
			}
		$this->loadModel('District');
		$this->District->Behaviors->load('Containable');
		$this->District->BindModel(
	        array('hasMany' => array(
                'Account' => array(
                    'className' => 'Account',
                    'foreignKey' => 'refId',
                    'conditions' => array(
					    'AND'=>array(
						    array('Account.accounttype_id' => 3),
						    array('Account.isPrimary' => 1)
						)
					)
                )
            )
        ));

		$this->District->Division->BindModel(
	        array('hasMany' => array(
                'Account' => array(
                    'className' => 'Account',
                    'foreignKey' => 'refId',
                    'conditions' => array(
					    'AND'=>array(
						    array('Account.accounttype_id' => 2),
						    array('Account.isPrimary' => 1)
						)
					)
                )
            )
        ));
		$this->District->contain('Account','Division.Account');
		$Data = $this->District->findById($districtId);
		foreach($Data['Account'] as $account){
			$districtAcc = $account['id'];
		}
		foreach($Data['Division']['Account'] as $account){
			$divisionAcc = $account['id'];
		}
		$formData = $this->request->data;
		$isSent = $this->transfer($divisionAcc, $districtAcc,$formData);
		if($isSent){
			$this->Session->setFlash(__('The transaction has been saved.'));
			return $this->redirect(array('action' => 'detail','controller'=>'accounts',$districtAcc));
		}
		$this->loadModel('District');
		$this->District->Behaviors->load('Containable');
		$this->District->BindModel(
	        array('hasMany' => array(
                'Account' => array(
                    'className' => 'Account',
                    'foreignKey' => 'refId',
                    'conditions' => array('AND'=>array(
						    array('Account.accounttype_id' => 3),
						    array('Account.isPrimary' => 1)
					    )
					)
                )
            )
        ));
        $this->District->contain('Account');
		$districts = $this->District->findById($districtId,array('id','name'));
		if(empty($districts['Account'])){
			$this->Session->setFlash(__('This District does not have any Primary Bank Account associated with it. Please add an Account First.'));
			return $this->redirect(array('action' => 'overview','controller'=>'divisions',$ref_id));
		}
		$this->set(compact('unions'));
			return;
		}
		//$users = $this->Transaction->User->find('list');
		
		$this->loadModel('District');
		$this->District->Behaviors->load('Containable');
		$this->District->BindModel(
	        array('hasMany' => array(
                'Account' => array(
                    'className' => 'Account',
                    'foreignKey' => 'refId',
                    'conditions' => array('AND'=>array(
						    array('Account.accounttype_id' => 3),
						    array('Account.isPrimary' => 1)
					    )
					)
                )
            )
        ));
        $this->District->contain('Account');
        
		$districts = $this->District->findById($districtId,array('id','name'));
		/*echo "<pre>";
		var_dump($upazillas);
		echo "</pre>";*/
		if(empty($districts['Account'])){
			$this->Session->setFlash(__('This District does not have any Primary Bank Account associated with it. Please add an Account First.'));
			return $this->redirect(array('action' => 'overview','controller'=>'divisions',$ref_id));
		}
		$this->set(compact('districts'));
	}
/**
 * transfer Money to Upazilla method
 *
 * @return void
 */
	public function DisUserFundUpazilla($upazillaId = null) {
		$ref_id = $this->Session->read('Auth.User.ref_id');
		if($upazillaId == null){
			$this->Session->setFlash(__('Please do not try anything abnormal. Your account will be permanently blocked.'));
			
			if($ref_id!=null)
				return $this->redirect(array('controller'=>'upazillas','action' => 'overview',$ref_id));
			else
				return $this->redirect(array('controller'=>'users','action' => 'logout'));
		}
		if ($this->request->is('post')) {
			$referer = $this->referer();
			$chips = explode("/",$referer);
			$chipCount = sizeof($chips);

			$upazillaIdFromForm = $this->request->data['Transaction']['upazilla_id'];
			$upazillaIdFromRef = $chips[$chipCount-1];

			if($upazillaIdFromForm!=$upazillaIdFromRef || $upazillaIdFromForm!=$upazillaId || $upazillaId!=$upazillaIdFromRef){
				//stop the transaction, something is not right
				//throw error
				return;
			}
			$this->loadModel('Upazilla');
			$this->Upazilla->Behaviors->load('Containable');
			$this->Upazilla->BindModel(
		        array('hasMany' => array(
	                'Account' => array(
	                    'className' => 'Account',
	                    'foreignKey' => 'refId',
	                    'conditions' => array(
						    'AND'=>array(
							    array('Account.accounttype_id' => 4),
							    array('Account.isPrimary' => 1)
							)
						)
	                )
	            )
	        ));
			$this->Upazilla->District->BindModel(
		        array('hasMany' => array(
	                'Account' => array(
	                    'className' => 'Account',
	                    'foreignKey' => 'refId',
	                    'conditions' => array(
						    'AND'=>array(
							    array('Account.accounttype_id' => 3),
							    array('Account.isPrimary' => 1)
							)
						)
	                )
	            )
	        ));
			$this->Upazilla->contain('Account','District.Account');
			$Data = $this->Upazilla->findById($upazillaId);
			foreach($Data['Account'] as $account){
				$upazillaAcc = $account['id'];
			}
			foreach($Data['District']['Account'] as $account){
				$districtAcc = $account['id'];
			}
			$formData = $this->request->data;
			$isSent = $this->transfer($districtAcc, $upazillaAcc,$formData);
			if($isSent){
				$this->Session->setFlash(__('The transaction has been saved.'));
				return $this->redirect(array('action' => 'detail','controller'=>'accounts',$upazillaAcc));
			}
			$this->loadModel('Upazilla');
		$this->Upazilla->Behaviors->load('Containable');
		$this->Upazilla->BindModel(
	        array('hasMany' => array(
                'Account' => array(
                    'className' => 'Account',
                    'foreignKey' => 'refId',
                    'conditions' => array('AND'=>array(
						    array('Account.accounttype_id' => 4),
						    array('Account.isPrimary' => 1)
					    )
					)
                )
            )
        ));
        $this->Upazilla->contain('Account');
		$upazillas = $this->Upazilla->findById($upazillaId,array('id','name'));
		if(empty($upazillas['Account'])){
			$this->Session->setFlash(__('This Upazilla does not have any Primary Bank Account associated with it. Please add an Account First.'));
			return $this->redirect(array('action' => 'overview','controller'=>'districts',$ref_id));
		}
		$this->set(compact('unions'));
			return;
		}
		//$users = $this->Transaction->User->find('list');
		
		
		$this->loadModel('Upazilla');
		$this->Upazilla->Behaviors->load('Containable');
		$this->Upazilla->BindModel(
	        array('hasMany' => array(
                'Account' => array(
                    'className' => 'Account',
                    'foreignKey' => 'refId',
                    'conditions' => array('AND'=>array(
						    array('Account.accounttype_id' => 4),
						    array('Account.isPrimary' => 1)
					    )
					)
                )
            )
        ));
        $this->Upazilla->contain('Account');

		$upazillas = $this->Upazilla->findById($upazillaId,array('id','name'));
		/*echo "<pre>";
		var_dump($upazillas);
		echo "</pre>";*/
		if(empty($upazillas['Account'])){
			$this->Session->setFlash(__('This Upazilla does not have any Primary Bank Account associated with it. Please add an Account First.'));
			return $this->redirect(array('action' => 'overview','controller'=>'districts',$ref_id));
		}
		$this->set(compact('upazillas'));
	}	
	
/**
 * transfer Money to Union method
 *
 * @return void
 */
	public function UpUserFundUnion($unionId = null) {
		$ref_id = $this->Session->read('Auth.User.ref_id');
		if($unionId == null){
			$this->Session->setFlash(__('Please do not try anything abnormal. Your account will be permanently blocked.'));
			
			if($ref_id!=null)
				return $this->redirect(array('controller'=>'upazillas','action' => 'overview',$ref_id));
			else
				return $this->redirect(array('controller'=>'users','action' => 'logout'));
		}
		if ($this->request->is('post')) {
			$referer = $this->referer();
			$chips = explode("/",$referer);
			$chipCount = sizeof($chips);

			$unionIdFromForm = $this->request->data['Transaction']['union_id'];
			$unionIdFromRef = $chips[$chipCount-1];

			if($unionIdFromForm!=$unionIdFromRef || $unionIdFromForm!=$unionId || $unionId!=$unionIdFromRef){
				//stop the transaction, something is not right
				//throw error
				return;
			}
			$this->loadModel('Union');
			$this->Union->Behaviors->load('Containable');
			$this->Union->BindModel(
		        array('hasMany' => array(
	                'Account' => array(
	                    'className' => 'Account',
	                    'foreignKey' => 'refId',
	                    'conditions' => array(
						    'AND'=>array(
							    array('Account.accounttype_id' => 5),
							    array('Account.isPrimary' => 1)
							)
						)
	                )
	            )
	        ));
			$this->Union->Upazilla->BindModel(
		        array('hasMany' => array(
	                'Account' => array(
	                    'className' => 'Account',
	                    'foreignKey' => 'refId',
	                    'conditions' => array(
						    'AND'=>array(
							    array('Account.accounttype_id' => 4),
							    array('Account.isPrimary' => 1)
							)
						)
	                )
	            )
	        ));
			$this->Union->contain('Account','Upazilla.Account');
			$Data = $this->Union->findById($unionId);
			foreach($Data['Account'] as $account){
				$unionAcc = $account['id'];
			}
			foreach($Data['Upazilla']['Account'] as $account){
				$upazillaAcc = $account['id'];
			}
			$formData = $this->request->data;
			$isSent = $this->transfer($upazillaAcc, $unionAcc,$formData);
			if($isSent){
				$this->Session->setFlash(__('The transaction has been saved.'));
				return $this->redirect(array('action' => 'detail','controller'=>'accounts',$unionAcc));
			}
			$this->loadModel('Union');
		$this->Union->Behaviors->load('Containable');
		$this->Union->BindModel(
	        array('hasMany' => array(
                'Account' => array(
                    'className' => 'Account',
                    'foreignKey' => 'refId',
                    'conditions' => array('AND'=>array(
						    array('Account.accounttype_id' => 5),
						    array('Account.isPrimary' => 1)
					    )
					)
                )
            )
        ));
        $this->Union->contain('Account');
		$unions = $this->Union->findById($unionId,array('id','name'));
		if(empty($unions['Account'])){
			$this->Session->setFlash(__('This Union does not have any Primary Bank Account associated with it. Please add an Account First.'));
			return $this->redirect(array('action' => 'overview','controller'=>'upazillas',$ref_id));
		}
		$this->set(compact('unions'));
			return;
		}
		//$users = $this->Transaction->User->find('list');
		
		
		$this->loadModel('Union');
		$this->Union->Behaviors->load('Containable');
		$this->Union->BindModel(
	        array('hasMany' => array(
                'Account' => array(
                    'className' => 'Account',
                    'foreignKey' => 'refId',
                    'conditions' => array('AND'=>array(
						    array('Account.accounttype_id' => 5),
						    array('Account.isPrimary' => 1)
					    )
					)
                )
            )
        ));
        $this->Union->contain('Account');
		$unions = $this->Union->findById($unionId,array('id','name'));
		if(empty($unions['Account'])){
			$this->Session->setFlash(__('This Union does not have any Primary Bank Account associated with it. Please add an Account First.'));
			return $this->redirect(array('action' => 'overview','controller'=>'upazillas',$ref_id));
		}
		$this->set(compact('unions'));
	}	
/**
 * transfer Money to Union method
 *
 * @return void
 */
	public function fundRma() {
		if ($this->request->is('post')) {
			$currentUser = $this->Session->read('Auth.User');
			$currentUserId = (int)$currentUser['id'];
			$this->request->data['Transaction']['datetime'] = date('Y-m-d H:m:s'); //TIME OF TRANSACTION
			$this->request->data['Transaction']['user_id'] = $currentUserId; //CURRENT USER ID
			$this->request->data['Transaction']['account_from'] = 1; //Base Account
			$tmp = $this->request->data['Transaction']['account_id'];
			$targetAccount = $this->Transaction->Account->findById($tmp);
			$sourceAccount = $this->Transaction->Account->findById($this->request->data['Transaction']['account_from']);
			$availableBalance = $sourceAccount['Account']['balance'];
			if((int)$availableBalance < (int)$this->request->data['Transaction']['amount']){
				$this->Session->setFlash(__('The transaction could not be initiated because of INSUFFICIENT fund.'));
				return $this->redirect(array('action' => 'transferMoneyToDivision'));
			}
			$newBalance = (int)$targetAccount['Account']['balance'] + (int)$this->request->data['Transaction']['amount'];
			$this->request->data['Transaction']['previous_balance'] = $targetAccount['Account']['balance'];
			$this->request->data['Transaction']['current_balance'] = $newBalance;
			
			$this->Transaction->Account->id = $this->request->data['Transaction']['account_from'];
			$this->Transaction->Account->saveField('balance',$availableBalance - (int)$this->request->data['Transaction']['amount']);
			
			$this->Transaction->Account->id = $tmp;
			$this->Transaction->Account->saveField('balance',$newBalance);
			
			$this->Transaction->create(); //creating Income transaction
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
			
			$this->Transaction->create(); //creating Expenditure transaction
			$this->request->data['Transaction']['account_from'] = $this->request->data['Transaction']['account_id'];
			$this->request->data['Transaction']['account_id'] = 1; 
			$this->request->data['Transaction']['type'] = 2;
			$this->request->data['Transaction']['previous_balance'] = $availableBalance;
			$this->request->data['Transaction']['current_balance'] = $availableBalance - (int)$this->request->data['Transaction']['amount'];

			
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The expenditure transaction has been saved.'));
				return $this->redirect(array('controller'=>'Accounts','action' => 'detail',$this->request->data['Transaction']['account_from']));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$users = $this->Transaction->User->find('list');
		
		
		$this->loadModel('Division');
		$this->Division->recursive = -1;
		$divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('users','divisions'));
	}
	
/**
 * transfer Money to Union method
 *
 * @return void
 */
	public function transferMoneyToUnion() {
		if ($this->request->is('post')) {
			$currentUser = $this->Session->read('Auth.User');
			$currentUserId = (int)$currentUser['id'];
			$this->request->data['Transaction']['datetime'] = date('Y-m-d H:m:s'); //TIME OF TRANSACTION
			$this->request->data['Transaction']['user_id'] = $currentUserId; //CURRENT USER ID
			$this->request->data['Transaction']['account_from'] = 9; //set the account id according to current USER level, like district levl account or watevr
			$tmp = $this->request->data['Transaction']['account_id'];
			$targetAccount = $this->Transaction->Account->findById($tmp);
			$sourceAccount = $this->Transaction->Account->findById($this->request->data['Transaction']['account_from']);
			$availableBalance = $sourceAccount['Account']['balance'];
			if((int)$availableBalance < (int)$this->request->data['Transaction']['amount']){
				$this->Session->setFlash(__('The transaction could not be initiated because of INSUFFICIENT fund.'));
				return $this->redirect(array('action' => 'transferMoneyToDivision'));
			}
			$newBalance = (int)$targetAccount['Account']['balance'] + (int)$this->request->data['Transaction']['amount'];
			$this->request->data['Transaction']['previous_balance'] = $targetAccount['Account']['balance'];
			$this->request->data['Transaction']['current_balance'] = $newBalance;
			
			$this->Transaction->Account->id = $this->request->data['Transaction']['account_from'];
			$this->Transaction->Account->saveField('balance',$availableBalance - (int)$this->request->data['Transaction']['amount']);
			
			$this->Transaction->Account->id = $tmp;
			$this->Transaction->Account->saveField('balance',$newBalance);
			
			$this->Transaction->create(); //creating Income transaction
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
			
			$this->Transaction->create(); //creating Expenditure transaction
			$this->request->data['Transaction']['account_from'] = $this->request->data['Transaction']['account_id'];
			$this->request->data['Transaction']['account_id'] = 9;
			$this->request->data['Transaction']['type'] = 2;
			$this->request->data['Transaction']['previous_balance'] = $availableBalance;
			$this->request->data['Transaction']['current_balance'] = $availableBalance - (int)$this->request->data['Transaction']['amount'];

			
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The expenditure transaction has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$users = $this->Transaction->User->find('list');
		
		
		$this->loadModel('Division');
		$this->Division->recursive = -1;
		$divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('users','divisions'));
	}
	
/**
 * transfer Money to Employee method
 *
 * @return void
 */
	public function transferMoneyToEmployee() {
		if ($this->request->is('post')) {
			$currentUser = $this->Session->read('Auth.User');
			$currentUserId = (int)$currentUser['id'];
			$this->request->data['Transaction']['datetime'] = date('Y-m-d H:m:s'); //TIME OF TRANSACTION
			$this->request->data['Transaction']['user_id'] = $currentUserId; //CURRENT USER ID
			$this->request->data['Transaction']['account_from'] = 9; //set the account id according to current USER level, like district levl account or watevr
			$tmp = $this->request->data['Transaction']['account_id'];
			$targetAccount = $this->Transaction->Account->findById($tmp);
			$sourceAccount = $this->Transaction->Account->findById($this->request->data['Transaction']['account_from']);
			$availableBalance = $sourceAccount['Account']['balance'];
			if((int)$availableBalance < (int)$this->request->data['Transaction']['amount']){
				$this->Session->setFlash(__('The transaction could not be initiated because of INSUFFICIENT fund.'));
				return $this->redirect(array('action' => 'transferMoneyToDivision'));
			}
			$newBalance = (int)$targetAccount['Account']['balance'] + (int)$this->request->data['Transaction']['amount'];
			$this->request->data['Transaction']['previous_balance'] = $targetAccount['Account']['balance'];
			$this->request->data['Transaction']['current_balance'] = $newBalance;
			
			$this->Transaction->Account->id = $this->request->data['Transaction']['account_from'];
			$this->Transaction->Account->saveField('balance',$availableBalance - (int)$this->request->data['Transaction']['amount']);
			
			$this->Transaction->Account->id = $tmp;
			$this->Transaction->Account->saveField('balance',$newBalance);
			
			$this->Transaction->create(); //creating Income transaction
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
			
			$this->Transaction->create(); //creating Expenditure transaction
			$this->request->data['Transaction']['account_from'] = $this->request->data['Transaction']['account_id'];
			$this->request->data['Transaction']['account_id'] = 9;
			$this->request->data['Transaction']['type'] = 2;
			$this->request->data['Transaction']['previous_balance'] = $availableBalance;
			$this->request->data['Transaction']['current_balance'] = $availableBalance - (int)$this->request->data['Transaction']['amount'];

			
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The expenditure transaction has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}
		$users = $this->Transaction->User->find('list');
		
		
		$this->loadModel('Division');
		$this->Division->recursive = -1;
		$divisions = $this->Division->find('all',array('fields'=>array('id','name')));
		
		$this->set(compact('users','divisions'));
	}

/**
 * transfer Money to Employee method
 *
 * @return void
 */
	public function payAttendance($attendanceId) {
	$this->set('attendanceId',$attendanceId);
		if ($this->request->is('post')) {

			$referer = $this->referer();
			$chips = explode("/",$referer);
			$chipCount = sizeof($chips);

			$attendanceIdFromForm = $this->request->data['Transaction']['attendance_id'];
			$attendanceIdFromRef = $chips[$chipCount-1];

			if($attendanceIdFromForm!=$attendanceIdFromRef || $attendanceIdFromForm!=$attendanceId || $attendanceId!=$attendanceIdFromRef){
				//stop the transaction, something is not right
				//throw error
				return;
			}

			$currentUser = $this->Session->read('Auth.User');
			$currentUserId = (int)$currentUser['id'];
			$this->request->data['Transaction']['datetime'] = date('Y-m-d H:m:s'); //TIME OF TRANSACTION
			$this->request->data['Transaction']['user_id'] = $currentUserId; //CURRENT USER ID
			//***
			$this->loadModel('Attendance');
			$this->Attendance->Employee->BindModel(
		        array('hasOne' => array(
	                'Account' => array(
	                    'className' => 'Account',
	                    'foreignKey' => 'refId'
	                )
	            )
	        ));
			$this->Attendance->Employee->Union->BindModel(
		        array('hasOne' => array(
	                'Account' => array(
	                    'className' => 'Account',
	                    'foreignKey' => 'refId'
	                )
	            )
	        ));

			$this->Attendance->Behaviors->load('Containable');
			$this->Attendance->contain(
				array(
					'Employee'=>array('fields'=>array('id')),
					'Employee.Account'=>array('conditions'=>array('Account.isPrimary' => '1')),
					'Employee.Union',
					'Employee.Union.Account'=>array('conditions'=>array('Account.isPrimary' => '1'))
				)
			);
			$AttnData = $this->Attendance->findById($attendanceId);
			if(!empty($AttnData['Employee']['Account']['id'])){
				$account_to = $AttnData['Employee']['Account']['id'];
				//throw error if no primary account is found for EMPLOYEES
			}else{
				$this->Session->setFlash(__('Please make your the Employees has ONE and ONLY ONE primary account.'));
				return $this->redirect(array('controller'=>'employees','action' => 'info', (int)$AttnData['Employee']['id']));
			}
			if(!empty($AttnData['Employee']['Union']['Account']['id'])){
				$account_from = $AttnData['Employee']['Union']['Account']['id'];
				//throw error if no primary account is found for UNIONS
			}else{
				$this->Session->setFlash(__('Please make your the Parent Account has ONE and ONLY ONE primary account.'));
				return $this->redirect(array('controller'=>'employees','action' => 'info', (int)$AttnData['Employee']['id']));
			}
			
			$year = $AttnData['Attendance']['year'];
			$month = $AttnData['Attendance']['month'];
			$ref = $this->request->data['Transaction']['reference'];
			$type = 1; // 1 stands for income
			$daysPresent = $AttnData['Attendance']['days_present'];
			$rate = 150;
			$amountEarned = $rate * $daysPresent;
			$prevBalance = (int)$AttnData['Employee']['Account']['balance'];
			$newBalance = $prevBalance + $amountEarned;
			/*echo $account_to;
			echo "-";
			echo $account_from;
			echo "-";
			echo $year;
			echo "-";
			echo $month;
			echo "-";
			echo $ref;
			echo "-";
			echo $type;
			echo "-";
			echo $amountEarned;
			echo "-";
			echo $prevBalance;
			echo "-";
			echo $newBalance;
			echo "-";
			echo $attendanceId;
			echo "<pre>";
			var_dump($AttnData);
			echo "</pre>";*/
			$this->request->data['Transaction']['year'] = $year;
			$this->request->data['Transaction']['month'] = $month;
			$this->request->data['Transaction']['account_from'] = $account_from; //money taken from the Employee Union Account
			$this->request->data['Transaction']['account_id'] = $account_to;

			//****
			$tmp = $account_to;
			$targetAccount = $this->Transaction->Account->findById($account_to);
			$sourceAccount = $this->Transaction->Account->findById($account_from);
			$availableBalance = $sourceAccount['Account']['balance'];
			if((int)$availableBalance < (int)$amountEarned){
				$this->Session->setFlash(__('The transaction could not be initiated because of INSUFFICIENT fund ON the Paying Account.'));
				return $this->redirect(array('controller'=>'employees','action' => 'info',$AttnData['Employee']['id']));
			}
			$newBalance = (int)$targetAccount['Account']['balance'] + (int)$amountEarned;
			$this->request->data['Transaction']['amount'] = (int)$amountEarned;
			$this->request->data['Transaction']['previous_balance'] = $targetAccount['Account']['balance'];
			$this->request->data['Transaction']['current_balance'] = $newBalance;
			$this->request->data['Transaction']['attendance_id'] = $attendanceId;

			$this->Transaction->Account->id = $account_from;
			//***
			$this->Transaction->Account->saveField('balance',$availableBalance - (int)$amountEarned);
			
			$this->Transaction->Account->id = $account_to;
			// ***
			$this->Transaction->Account->saveField('balance',$newBalance);
			$this->Attendance->id = $attendanceId;
			$this->Attendance->saveField('isPaid',1);
			$this->Attendance->saveField('paidOnDate',date('Y-m-d H:m:s'));
			//creating Income transaction
			$this->Transaction->create(); 
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				//return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
			
			$this->Transaction->create(); //creating Expenditure transaction
			$this->request->data['Transaction']['account_from'] = $account_to;
			$this->request->data['Transaction']['account_id'] = $account_from;
			$this->request->data['Transaction']['type'] = 2; //2 stands for expenditure
			$this->request->data['Transaction']['previous_balance'] = $availableBalance;
			$this->request->data['Transaction']['current_balance'] = $availableBalance - (int)$amountEarned;

			
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The expenditure transaction has been saved.'));
				return $this->redirect(array('controller'=>'employees','action' => 'info', (int)$AttnData['Employee']['id']));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		}//posting ends */
		$users = $this->Transaction->User->find('list');
		
		$this->set(compact('users','attendanceId'));
	}
	

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Transaction->exists($id)) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Transaction->save($this->request->data)) {
				$this->Session->setFlash(__('The transaction has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The transaction could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Transaction.' . $this->Transaction->primaryKey => $id));
			$this->request->data = $this->Transaction->find('first', $options);
		}
		$users = $this->Transaction->User->find('list');
		//$employees = $this->Transaction->Employee->find('list');
		$accounts = $this->Transaction->Account->find('list');
		$this->set(compact('users','accounts'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Transaction->id = $id;
		if (!$this->Transaction->exists()) {
			throw new NotFoundException(__('Invalid transaction'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Transaction->delete()) {
			$this->Session->setFlash(__('The transaction has been deleted.'));
		} else {
			$this->Session->setFlash(__('The transaction could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
