<?php
	class Order extends AppModel{
		
		public $belongsTo = array("User");
		
		
		public $validate = array(
			'stripe_token'=> array(
				'required'=> array('rule'=> array('notEmpty'),'message'=> 'Stripe Token is required')
				,'isUnique'=>array('rule'=>'isUnique','message'=>'Order has already been saved')
			)
		);
		
		public function isOwnedBy($order, $user) {
		    return $this->field('id', array('id' => $order, 'user_id' => $user)) !== false;			
		}
		
	}
?>
		