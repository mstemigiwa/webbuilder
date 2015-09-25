
<div class="templates form">
<?php echo $this->Form->create('Website'); ?>
    <fieldset>
        <legend>
            <?php echo __('Select your Template'); ?>
        </legend>
        <div id="categories">
        	<ul>
        	<?php
        		foreach ($vars['categories'] as $category) {
					echo "<li>" . $category['Category']['name'] . "</li>";
				}
        	?>
        	</ul>
        </div>
        <?php 
	        
	        //Displays templates
	        ?>
	        <div>
	        <?php
	        echo $this->Form->hidden('Website.template_id',array('id'=>'WebsiteTemplate_'));
	        foreach ($vars['templates'] as $key=>$template) {
	        	//$price = ($template['Template']['price'] >0)?  "NGN " . $template['Template']['price'] : "FREE!!!";
	        	echo $this->Form->input('template_id'
										,array(
											'type'=>'radio'
											,'options'=>array( $template['Template']['id']=>$template['Template']['name'] )
											,'after'=>"Price: " . $template['Template']['description'] . "<br />"  . $this->Number->currency($template['Template']['price'],'NGNFree') //$price
											,'hiddenField'=>false
											,'div'=>'box'
										)
									);

				//TODO Add icon for each type
				//TODO Add link for demo
			}
			//TODO Add code for "Custom Template"
			?>
	        </div>
    </fieldset>
<?php echo $this->Form->end(__('Next: Pick your Your Plan')); ?>
