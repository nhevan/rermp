<?php
App::uses('AuthComponent', 'AppModel', 'Model');
/**
 * User Model
 *
 * @property User $User
 * @property Usertype $Usertype
 * @property Ref $Ref
 * @property District $District
 * @property Transaction $Transaction
 * @property Upazilla $Upazilla
 * @property User $User
 */
class User extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	/*public $validate = array(
		'ref_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);*/

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Usertype' => array(
			'className' => 'Usertype',
			'foreignKey' => 'usertype_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
public $actsAs = array('Acl' => array('type' => 'requester'));
/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'District' => array(
			'className' => 'District',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Transaction' => array(
			'className' => 'Transaction',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Upazilla' => array(
			'className' => 'Upazilla',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	//for ACL implementation
	public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['usertype_id'])) {
            $usertypeId = $this->data['User']['usertype_id'];
        } else {
            $usertypeId = $this->field('usertype_id');
        }
        if (!$usertypeId) {
            return null;
        } else {
            return array('Usertype' => array('id' => $usertypeId));
        }
    }
    
    public function beforeSave($options = array()) {
        $this->data['User']['password'] = AuthComponent::password(
          $this->data['User']['password']
        );
        return true;
    }

}
