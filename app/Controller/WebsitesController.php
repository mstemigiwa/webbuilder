<?php
	
	class WebsitesController extends AppController{
		
		public function beforeFilter(){			
					parent::beforeFilter();
					$this->Auth->allow('create');
		}
		
		public function isAuthorized($user) {			
			$websiteID = (int) $this->request->params['pass'][0]; 
			if ($this->Website->isOwnedBy($websiteID, $user['id'])) {
	            return true;
	        }
		     return false;
		}
		
		
		public function manage($id = null){
			$this->Website->recursive = 2;	
			$website = $this->Website->findById($id);
			if(!$website) {throw new NotFoundException("Website not Found");}
			else{
				$this->set("website",$website);
			}
			//$addons = $this->Website->WebsiteAddon->Addon->find("all"); TODO V2 Add back in to create up-sell opportunities
			//$this->set("addons",$addons);
		}
		
		public function create(){ 
			if($this->request->is("post")){ 
				$this->Website->set($this->request->data);
				if($this->Website->validates()) {
					$this->Session->write('Cart',$this->request->data);	
					return $this->redirect(array('controller'=>'templates', 'action'=>'select'));
				}
						
			}
			$vars['DomainsBot'] = Configure::read('DomainsBot');
			$vars['types'] = $this->Website->Type->find('all');
			$vars['x'] = $this->Website->Type->find('list');
			$vars['engines'] = $this->Website->Engine->find('all');
			$this->set('vars',$vars);
		}
		
		
		
	}
?>