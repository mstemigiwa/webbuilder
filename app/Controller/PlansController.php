<?php
	
	class PlansController extends AppController{
		
		public $components = array('Paginator');
		
	
		public function beforeFilter(){			
					parent::beforeFilter();
					$this->Auth->allow('select');
		}
		
				
		
		public function select(){
			
			$cart = $this->Session->read('Cart'); debug($cart);
			
			if(!$cart && !in_array($cart['Website']['name'],$cart) && !in_array($cart['Website']['type'],$cart) && !in_array($cart['Website']['engine'],$cart) && !in_array($cart['Website']['template'],$cart)) 
			{ return $this->redirect(array('controller'=>'websites','action'=>'create')); } 
			
			if($this->request->is("post")){
				$cart['Website']['plan_id'] = $this->request->data['Website']['plan_id'];
				$this->Session->write('Cart',$cart);	
				return $this->redirect(array('controller'=>'addons', 'action'=>'select'));		
			}
			$plans = $this->Plan->find("all",array('order'=>array('Plan.monthly')));
			$vars['plans']= $plans;
			
			$this->set('vars',$vars); 
		}
		
		
		
	}
?>