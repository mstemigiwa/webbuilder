<?php
	class User extends AppModel{
		
    	public $hasMany = array('Website'=>array('order'=>'Website.duedate'));
		
					
		public $validate = array(
			'username'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'A username is required')
				,'valid_email'=>array('rule'=>'email','required'=>true, 'allowEmpty'=>false,'message'=>'A valid email is required')
				,'isUnique'=>array('rule'=>'isUnique','message'=>'That email is already in use')
			),
			'firstname'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'First Name is required')
			),
			'lastname'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Last Name is required')
			),
			'address'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Address is required')
			),
			'phone'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Phone Number is required') //TODO require a valid phonenumber
			),
			
			'password'=>array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'A password is required')
			),
			'password2'=>array(
				'match'=>array('rule'=>'matchPasswords','message'=>'Your passwords must match')
			),
		);
		
	
		public function beforeSave($options = array()) {
				if (isset($this->data[$this->alias]['password'])) {
					$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
				}
				return true;
			}
		public function matchPasswords() { 
	        return $this->data[$this->name]['password'] === $this->data[$this->name]['password2']; 
	    }
		
		
		
	}
?>
