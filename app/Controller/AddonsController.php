<?php
	
	class AddonsController extends AppController{
		
		public $components = array('Paginator');
		
	
		public function beforeFilter(){			
					parent::beforeFilter();
					$this->Auth->allow('select');
		}
		
				
		
		public function select(){
			
			$cart = $this->Session->read('Cart'); debug($cart);
			if(!$cart && !in_array($cart['Website']['name'],$cart) 
					&& !in_array($cart['Website']['type'],$cart) 
					&& !in_array($cart['Website']['engine'],$cart) 
					&& !in_array($cart['Website']['template'],$cart)
					&& !in_array($cart['Website']['plan'],$cart)
				) { return $this->redirect(array('controller'=>'websites','action'=>'create')); } 
			
			
			if($this->request->is("post")){
				$cart['WebsiteAddon'] = $this->request->data['WebsiteAddon'];
				$this->Session->write('Cart',$cart);	
				return $this->redirect(array('controller'=>'users', 'action'=>'checkout'));		
			}
			$addons = $this->Addon->find("all",array('order'=>array("RAND()")));
			$vars['addons']= $addons;
			$vars['cart']=$cart;
			$this->set('vars',$vars); 
		}
		
		
		
	}
?>