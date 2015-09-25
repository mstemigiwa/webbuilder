<?php
	debug($vars);
	
	echo $this->Form->create(null,array('url'=>$vars['VoguePay']['post_url']));
	
	echo $this->Form->input(null,array('type'=>'hidden','value'=>$vars['VoguePay']['merchant_id'],'name'=>'v_merchant_id'));
	echo $this->Form->input(null,array('type'=>'hidden','value'=>substr( md5(rand()), 0, 7),'name'=>'merchant_ref')); //TODO Generate Unique Transaction ID
	echo $this->Form->input(null,array('type'=>'hidden','value'=>'Web Hosting from Graphitek Ventures','name'=>'memo'));
	
	$total = 0;
	$counter = 0;
	foreach ($vars['Cart']['Items'] as $key => $item_json) {
			
		$item = json_decode($item_json,true);
		echo $this->Form->input(null,array('type'=>'hidden','value'=>$item['name'],'name'=>'item_' . $counter));
		echo $this->Form->input(null,array('type'=>'hidden','value'=>$item['price'],'name'=>'price_' . $counter));
		$counter++;
		$total += $item['price'];
	}
	
	echo $this->Form->input(null,array('type'=>'hidden','value'=>$vars['VoguePay']['store_id'],'name'=>'store_id')); 
	echo $this->Form->input(null,array('type'=>'hidden','value'=>$vars['VoguePay']['developer_id'],'name'=>'developer_code'));
	
	echo $this->Form->input(null,array('type'=>'hidden','value'=>$total,'name'=>'total'));
	echo $this->Form->input(null,array('type'=>'hidden','value'=>$this->Html->url(array('controller'=>'users','action'=>'processPayment'), true),'name'=>'notify_url'));
	echo $this->Form->input(null,array('type'=>'hidden','value'=>$this->Html->url(array('controller'=>'users','action'=>'processPayment'), true),'name'=>'success_url'));
	echo $this->Form->input(null,array('type'=>'hidden','value'=>$this->Html->url(array('controller'=>'users','action'=>'processPayment'), true),'name'=>'fail_url'));
	
	 echo $this->Form->end(__('Pay '));
?>
