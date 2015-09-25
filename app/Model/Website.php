<?php
	class Website extends AppModel{
		
		public $belongsTo = array("Plan","Type","Engine","Template");
		
		public $hasMany = array("WebsiteAddon");
		
		public $validate = array(
			'name'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Domain name is required')
				,'isUnique'=>array('rule'=>'isUnique','message'=>'That domain is already in use')
				,'isWebsite'=>array('rule' => 'url','message'=>'Please enter a complete domain name (Must have extension e.g .com)')
			),
			'engine_id'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Engine is required')
			),
			'type_id'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Type is required')
			),
			'template_id'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Template is required')
			),
			'plan_id'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Plan is required')
			)
		);
		
		public function isOwnedBy($website, $user) {
		    return $this->field('id', array('id' => $website, 'user_id' => $user)) !== false;			
		}
		
	}
?>
		