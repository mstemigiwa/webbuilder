<?php
	
	class TemplatesController extends AppController{
		
		public $components = array('Paginator');
		
	
		public function beforeFilter(){			
					parent::beforeFilter();
					$this->Auth->allow('select');
		}
		
				
		
		public function select(){
			
			$cart = $this->Session->read('Cart'); debug($cart);
			
			if(!$cart && !in_array($cart['Website']['name'],$cart) && !in_array($cart['Website']['type'],$cart) && !in_array($cart['Website']['engine'],$cart)) 
			{ return $this->redirect(array('controller'=>'websites','action'=>'create')); } 
			
			if($this->request->is("post")){
				$cart['Website']['template_id'] = $this->request->data['Website']['template_id'];
				$this->Session->write('Cart',$cart);	
				return $this->redirect(array('controller'=>'plans', 'action'=>'select'));		
			}
			$templates = $this->Template->find("all", 
												array(
													'conditions'=>array('Template.engine_id'=>$cart['Website']['engine_id'])
													,'order' => 'RAND()'
													)
												);
			$vars['templates']=$templates;
			
			$categories = $this->Template->Category->find("all");
			$vars['categories'] = $categories;
			
			
			$this->set('vars',$vars); 
		}
		
		
		
	}
?>