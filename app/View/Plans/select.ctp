
<div class="templates form">
<?php echo $this->Form->create('Website'); ?>
    <fieldset>
        <legend>
            <?php echo __('Select your Plan'); ?>
        </legend>
        <?php 
	        
	        //Displays Pricing Table
	        ?>
	        <div>
	        <?php
	        echo $this->Form->hidden('Website.plan_id',array('id'=>'WebsitePlan_'));
	        foreach ($vars['plans'] as $plan) {	    //TODO Set up pricing table   
	        //$price = ($plan['Plan']['monthly'] >0)?  "NGN " . $plan['Plan']['monthly'] : "FREE!!!"; 	
	        	echo $this->Form->input('plan_id'
										,array(
											'type'=>'radio'
											,'options'=>array( $plan['Plan']['id']=>$plan['Plan']['name'] )
											,'after'=>"Price: " . $plan['Plan']['description'] . "<br />"  . $this->Number->currency($plan['Plan']['monthly'],'NGNFree') //$price
											,'hiddenField'=>false
											,'div'=>'box'
										)
									);

				//TODO Add icon for each type
			}
			?>
	        </div>
    </fieldset>
<?php echo $this->Form->end(__('Next: Add-ons & Upgrades')); ?>
