
<h2>Not Authorized</h2>
You are not authorized access that page.<br />Go to:
<?php

	if (AuthComponent::user('id')){ echo $this->Html->link('My Account',array('controller'=>'users','action'=>'account')); 
	} 
	else {echo $this->Html->link("Login", array("controller"=>"users","action"=>"login"));
		echo "  <br />  " .  $this->Html->link("Register", array("controller"=>"users","action"=>"register"));
	}
	
				
?>
