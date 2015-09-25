
<div class="addons form">
<?php echo $this->Form->create('Website'); ?>
    <fieldset>
        <legend>
            <?php echo __('Additional Services'); ?>
        </legend>
	        <div>
	        <?php
	        foreach ($vars['addons'] as $key => $addon) { 
				//$price = ($addon['Addon']['price'] >0)?  "NGN " . $addon['Addon']['price'] : "FREE!!!";
				echo $this->Form->input('WebsiteAddon.' . $key . '.addon_id'
											,array(
												'type'=>'checkbox'
												,'options'=>array( $addon['Addon']['id']=>$addon['Addon']['name'] )
												,'after'=>"Price: " . $addon['Addon']['description'] . "<br />"  . $this->Number->currency($addon['Addon']['price'],'NGNFree') //$price
												,'hiddenField'=>false
												,'div'=>'box'
												,'multiple'=>true
												,'value'=>$addon['Addon']['id']
												,'label'=>$addon['Addon']['name']
											)
										);
										//TODO Format Add on boxes
			}
	         
			?>
	        </div>
    </fieldset>
<?php echo $this->Form->end(__('Next: Review & Checkout')); ?>
