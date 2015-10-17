<?php
	class Website extends AppModel{
		
		public $belongsTo = array("Plan","Type","Engine","Template","User");
		
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
		
		
		public function afterFind($results, $primary = false) {
		    foreach ($results as $key => $val) {
		        if (isset($val['Website']['duedate']) && isset($val['Website']['status'])) {
		            $pastdue = 
		            	(strtotime($val['Website']['duedate'])<time() && 
		            		!($val['Website']['status'] == 'unpaid' || $val['Website']['status'] == 'pending'));
					$results[$key]['Website']['pastdue'] = $pastdue;
					
					$msg  = "";
					switch ($val['Website']['status']) { 
						case 'pending':
							$msg  = "<b>Pending Setup</b>: Your website will be fully operational within 24-48hours, if it has been longer than 48hrs please contact support <br />"; //TODO CONTENT Add support email
							break;
						case 'unpaid':
							$msg  = "<b>Pending Payment</b>Don't let " . $val['Website']['name'] . "get away!"; //. $this->Html->link("Get it Now",array('controller'=>'users','action'=>'checkout')); //TODO DESINGN Button
							break;
						case 'active':
							if($pastdue){
								$msg  =  "<b>Past Due</b>: " . $this->Html->link("Pay Now",array('controller'=>'users','action'=>'checkout')); //TODO DESIGN Button
							}
							else { $msg  =  "Active"; }
							break;
						case 'disabled':
							$msg  =  "<b>Disabled</b>:Your account has been suspended due to non-payment. ";
							break;
					}
					$results[$key]['Website']['msg'] = $msg;
		        }
		    }
		    return $results;
		}
	}
?>
		