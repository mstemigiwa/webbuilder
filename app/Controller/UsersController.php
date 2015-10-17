<?php
	App::uses('CakeEmail', 'Network/Email');
	class UsersController extends AppController{
				
		public function beforeFilter(){
			
					parent::beforeFilter();
					$this->Auth->allow('register','logout','login');
		}
		
		public function isAuthorized($user){
			 return true;
		}
		 
		
		public function register(){
			if($this->Auth->loggedin()) {
				$this->Auth->logout();
			} 

			if($this->request->is('post')){
				$this->User->create();
					    
			    if($this->User->save($this->request->data)){ 
						//TODO Email: Verification
			    		$user = $this->User->findById($this->User->id);
						$this->Auth->login($user['User']);
						debug($this->redirect($this->Auth->redirectUrl()));
						//return $this->redirect(array('/'));		
					}
				
					$this->Session->setFlash(__('There was a problem creating your account.'));
				}
		}
		
		
		public function logout(){
			 return $this->redirect($this->Auth->logout());
		 }
		
		public function login(){
			if ($this->request->is('post')) {
		        if ($this->Auth->login()) {
		            return $this->redirect($this->Auth->redirectUrl());
		        }
		        $this->Session->setFlash(__('Invalid username or password, try again'));
		    }
		}
		
		public function account(){
			$this->User->recursive = 2;
			$user = $this->User->findById($this->Auth->user('id',array("recursive"=>3)));
			//debug($user);
			$this->set("user", $user);
		}
		
		public function checkout(){ 
		 
		 $isPost = false;
		 if($this->request->is("post")){
		 	$isPost = true;
		 }
		 
		 
		 if($this->Session->check('Cart')){ //If there is a cart is session, save it 
		 
			$cart = $this->Session->read('Cart');
			if(!$cart && !in_array($cart['Website']['name'],$cart) 
					&& !in_array($cart['Website']['type'],$cart) 
					&& !in_array($cart['Website']['engine'],$cart) 
					&& !in_array($cart['Website']['template'],$cart)
					&& !in_array($cart['Website']['plan'],$cart)
				) { return $this->redirect(array('controller'=>'websites','action'=>'create')); } 
			
			$cart['Website']['user_id'] = $this->Auth->user('id');
			$this->User->Website->create();
			$saved = $this->User->Website->saveAll($cart);
			
			if(!$saved){
				debug($this->User->Website->validationErrors); //TODO Handle save errors
			} 
			else {
				$this->Session->delete('Cart');
			}

		 }
		 	debug($this->Auth->user('id'));
			
			//Retrieve all website items where the due date has passed "OR"=>array('Website.duedate <= '=> date('c')))
			$this->User->id = $this->Auth->user('id');
			$this->User->Website->recursive = 2;
			$expired_sites = $this->User->Website->find("all"
												,array( 'conditions'=>array('Website.duedate <= NOW()', 'User.id'=>$this->Auth->user('id')))
											);
			$expired_site_list = array();
			foreach ($expired_sites as $expired_site) { array_push($expired_site_list,$expired_site['Website']['id']); }
			
			$expired_addons = $this->User->Website->WebsiteAddon->find("all"
												,array( 'conditions'=>array(
															'WebsiteAddon.duedate <= NOW() '
															,"NOT"=>array('WebsiteAddon.website_id'=>$expired_site_list)
															, 'Website.user_id'=>$this->Auth->user('id')
															)
														)
											);								
			

			 
			 $cart = array('sites'=>$expired_sites,'addons'=>$expired_addons);
			 $vars['cart']=$cart;
			 $vars['isPost']=$isPost;
			 $this->set('vars',$vars);				
			
		} 


		
		
		
	}
	
?>