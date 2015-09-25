<?php //echo $this->Html->script('https://domainsbot.blob.core.windows.net/javascript/jquery.domainsbot-1.0.min.js',array('inline'=>false)); ?>
<div class="websites form">
<?php echo $this->Form->create('Website'); ?>
    <fieldset>
        <legend>
            <?php echo __('Create your Website'); ?>
        </legend>
        <?php 
	        echo $this->Form->input('name',array('label'=>'Select your domain name (e.g. www.example.com)','id'=>'search_box','onChange'=>'searchDomains(this)'));
	        //Displays the website types
	        ?>
	        <div id="domain_results"></div>
	        <div><legend>Type</legend>
	        <?php
	        echo $this->Form->hidden('Website.type_id',array('id'=>'WebsiteType_'));
	        foreach ($vars['types'] as $key=>$type) {
	        	
	        	echo $this->Form->input('type_id'
										,array(
											'type'=>'radio'
											,'options'=>array( $type['Type']['id']=>$type['Type']['name'] )
											,'after'=>$type['Type']['description']
											,'onclick'=>'showEngines()' //".' . $type['Type']['key'] . '"
											,'hiddenField'=>false
											,'key' => $type['Type']['key']
											,'div'=>'box'
										)
									);

				//TODO Add icon for each type
			}
			?>
	        </div>
	        <div id="engines">
	        	<legend>Select your Engine</legend>
		        <?php
				//Displays the engines
				echo $this->Form->hidden('Website.engine_id',array('id'=>'WebsiteEngine_'));
				foreach ($vars['engines'] as $engine) {
					$class = "box engine ";
					//Adds a class for filtering
					foreach ($vars['types'] as $type) {
						if( $engine['Engine'][$type['Type']['key']]) {$class .= " " . $type['Type']['key'];}
					}
					 
					echo $this->Form->input('engine_id'
													,array(
														'type'=>'radio'
														,'options'=>array($engine['Engine']['id']=>$engine['Engine']['name'] )
														,'after'=>$engine['Engine']['description']
														,'div'=>$class
														,'hiddenField'=>false
														)
											);
					//TODO Add icon for each type
				}
	    	?>
			<?php echo $this->Form->end(__('Next: Pick your Template')); ?>
    	</div>
    </fieldset>

<style type="text/css">

</style>
<script type="text/javascript">
	$(document).ready(function(){
		showEngines();
		
	});
	function showEngines(){
		var key = $('[name="data[Website][type_id]"]').filter(":checked").attr("key");
		if(!key) {$("#engines").hide(); }
		else {
			$(".engine").hide();
			$("."+key).show();
			$("#engines").show();
		}
		
		
	}
	
	function searchDomains(obj){ 

		$.getJSON("http://api.domainsbot.com/v4/spinner?callback=?"
					,{
						'auth-token': "<?php echo $vars['DomainsBot']['api_key'] ?>" //b8509797add451dd6fede1212cb3c386
						,'q':$(obj).val()
						,'pageSize':10
						,'tlds':"ng,com.ng,com,net,biz"
						,'customTldRates':'ng=0.8,com.ng=0.8'
					}
					,function(data){displayDomains(data,$(obj).val())}
					).fail(function( jqxhr, textStatus, error ) {
					    var err = textStatus + ", " + error;
					    $( "#domain_results" ).html( "Request Failed: " + err );
					});
	}
	
	function displayDomains(data,searchTerm){ console.log(data);
		try {
		var found = false;
		var domains_html = '';
		$.each( data['Domains'], function( key, val ) {
			if(searchTerm.indexOf(".") > -1){
				if(searchTerm.toLowerCase() == val['DomainName'].toLowerCase()) {found = true; return false;}
			}
			 domains_html +=  "<tr><td>" + val['DomainName'].toLowerCase() + "</td><td><a onclick='selectDomain(&quot;" + val['DomainName'].toLowerCase() + "&quot;)'>Select</a></td></tr>";

		});
		$( "#domain_results" ).html(""); //TODO Style the text box red if not found and green if it is
		if(found) {
			$( "#domain_results" ).html(searchTerm + " is available!");
		}
		else{
			$( "#domain_results" ).html('We&lsquo;re sorry ' + searchTerm.toLowerCase() +  ' is not available. Please try again or select one of the available options below:<table style="width: 300px">' + domains_html + '</table>');
		}
		}catch(e){$( "#domain_results" ).html("Unable to look up domain name.");} 
		
	}
	
	function selectDomain(domain){
		$("#search_box").val(domain);
	}
</script>