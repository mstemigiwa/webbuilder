<?php
	App::uses('CakeTime', 'Utility');
	class OrdersController extends AppController{
		
		public $components = array('Stripe.Stripe');
		
		//public $uses = array('Website','WebsiteAddon','Order');
		
		public function isAuthorized($user) {
			return true;
			/*	TODO necessarry?
			$orderID = (int) $this->request->params['pass'][0]; 
			if ($this->Order->isOwnedBy($orderID, $user['id'])) {
	            return true;
	        }
		     return false;
			 * 
			 */
		}
		
		public function pay(){
			if(!$this->request->is("post")){
				 return $this->redirect(array('controller'=>'users','action'=>'checkout'));
			}
			
			
			$cart = $this->request->data['Cart'];
			$recurring = 0; $oneTime = 0;
			foreach ($cart['Items'] as $key => $item_json) {
				$item = json_decode($item_json,true);
				if($item['recurring']){$recurring+=$item['price'];}
				else{$oneTime+=$item['price'];}
				
			}
			$cart['Recurrring'] = $recurring;
			$cart['OneTime'] = $oneTime;
			
			$vars['VoguePay'] = Configure::read('VoguePay');
			$vars['Cart'] = $cart;
			$this->set('vars',$vars);
		
		} 
		
		public function stripe(){
			
			if(!$this->request->is("post")){
				 return $this->redirect(array('controller'=>'users','action'=>'checkout'));
			}
			
			debug($this->request->data);
			debug($this->Auth->user('id'));
			
			$user = $this->Order->User->findById($this->Auth->user('id',array("recursive"=>-1)));
			$this->Order->User->id = $this->Auth->user('id');
			$user_info = $user['User']['firstname'] . " " . $user['User']['lastname'] . " - "  . $user['User']['username']; 

			
			
			$stripeToken = $this->request->data['Order']['stripeToken'];
			$cart = json_decode($this->request->data['Order']['items'],true);
			debug($cart);
			
			$data = array(
			    'stripeToken' => $stripeToken,
			    'description' =>  $user_info
			);
			
			$stripe_id = $user['User']['stripeCustomer'];
			
			// If not already a stripe customer
			//if(!$stripe_id){ We should always update so we can charge the last card they used
				$customer = $this->Stripe->customerCreate($data);
				debug($customer);
				
				if(!is_array($customer)) {
					debug("Cant create customer"); //TODO Handle error in creating customer
					die;
				}
				$stripe_id = $customer['stripe_id'];
				
				$this->Order->User->saveField('stripeCustomer',$stripe_id);
			//}
			
			
			
			$data = array(
			    'amount' => $cart['Recurrring']+$cart['OneTime'],
			    'stripeCustomer' => $stripe_id,
			    //'stripeToken'=>$stripeToken,
			    'description' => $user_info
			    
			);
			
			$charge = $this->Stripe->charge($data);
			debug($charge);
			
			if(!is_array($charge)){
				 $this->Session->setFlash(__('We were unable to charge your card, please try again.<br />Error: ' . $charge)); //TODO CONTENT Come up with better language
				$this->redirect(array('controller'=>'users','action'=>'checkout'));
			}
			//TODO If the amounts don't match
			
			// Save the order to the db
			$order = array(
					'user_id'=>$this->Auth->user('id')
					,'stripe_token'=>$charge['stripe_id']
					,'items'=>$this->request->data['Order']['items']
					,'amount'=>$charge['stripe_amount']
					,'status'=>'paid'
					,'paid'=>CakeTime::format("now","%Y-%m-%d %H:%M:%S")
					);
			$this->Order->create();
			if(!$this->Order->save($order)){
				debug($this->Order->validationErrors); //TODO Handle errors
			}
			
			$processed = $this->_process($this->Order->id);
			if(!is_array($processed)){
				//TODO Send an email to admin and let user know it will be updated in 24hrs
				
				// Everything looks to be successful rediect 
				$this->Session->setFlash(__('Your order has been successfully processed.')); //TODO CONTENT Come up with better language
				$this->redirect(array('controller'=>'users','action'=>'account'));
			}
			else {
				$msg = (isset($processed['display_error'])) ? $processed['display_error'] : 'We were unable to process your transaction, please try again or contact an admin.';
				$this->Session->setFlash(__($msg)); //TODO CONTENT Come up with better language and add message about support
				$this->redirect(array('controller'=>'users','action'=>'checkout'));
			} 
			
			debug($processed);
			
		}

		protected function _process($id){ // Update the customers account and all items appropriately
			
			$this->Order->recursive = -1;
			$order = $this->Order->read(null, $id);
			
			if($order['Order']['status'] != 'paid'){
				$errors['display_error'] =	'Order is not in a state that is ready for processing';		
				debug( $errors);
				//return $errors;
			}
			
			
			debug($order);
			
			$this->loadModel('Website');
			$this->loadModel('WebsiteAddon');
			$this->Websiterecursive = -1;
			$this->WebsiteAddonrecursive = -1;
			
			$dataSource = $this->Order->getDataSource();
			$dataSource->begin();
			$saved = true;
			$errors = array();
			
			$duedate = CakeTime::format("+ 30days","%Y-%m-%d %H:%M:%S");
			
			debug($duedate);
			try {			
				$items = json_decode($order['Order']['items'] , true);
				foreach ($items['Items'] as $key => $item_json) {
					$item = json_decode($item_json,true);
					debug($item);
			
					switch ($item['type']) {
						case 'website':
							$this->Website->id = $item['id'];
							$this->Website->set(array('duedate'=>$duedate,'status'=>'pending'));	
							if(!$this->Website->save()) {//TODO V2 change to the right date
								array_push($errors,$this->Website->validationErrors);
								$saved = false;							
							}
							$this->Website->clear();
							break;
						case 'template':
							//TODO How do we handle templates: Update the website_id with the template_id
							break;
						case 'addon':
							$this->WebsiteAddon->id = $item['id'];
							$addon_date = ($item['recurring'])? $duedate : '2999-12-31 00:00:00' ;
							if(!$this->WebsiteAddon->saveField('duedate',$addon_date)){ //TODO V2 change to the right date
								array_push($errors,$this->WebsiteAddon->validationErrors);
								$saved = false;							
							}
							$this->WebsiteAddon->clear();
							break;
					}
				}
				
				//throw new Exception('How did this happen');
				
				$order_data = array('status'=>'processed','processed'=>date("Y-m-d H:i:s"));
				if(!$this->Order->save($order_data)) {
					array_push($errors,$this->Order->validationErrors);
					$saved = false;
				}
			}
			catch(Exception $e){
				$errors['display_error'] = "Error processing order";
				$errors['error_data']= $e;
				$saved = false;
			}
			
			if($saved){
				$dataSource->commit();
				debug($saved);
				 return $saved; 
			}
			else {
				
				$dataSource->rollback();
				$errors['display_error'] = "Unable to process order"; //TODO JSON Encode Errors and save to table with order id and date occurred
				debug($errors);
				return $errors;
			}
			
		}

		
		
		
		protected function notify_admin(){
			//TODO EMAIL
		}

		protected function notify_user(){
			//TODO EMAIL
		}
		
		
	}
		
?>