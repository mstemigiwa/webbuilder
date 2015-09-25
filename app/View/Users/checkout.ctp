<?php 
	$cartTotal = 0;
	$checked = ($vars['isPost'])?"":"checked";
	$cartCount = 0;
?>
<?php echo $this->Form->create('Cart',array('url' => array('controller' => 'users', 'action' => 'pay'))); ?>
<h2>Websites</h2>
<table>
	<thead>
		<th>Name</th>
		<th>Type</th>
		<th>Engine</th>
		<th>Plan</th>
		<th>Price</th>
	</thead>
	<?php 
		foreach ($vars['cart']['sites'] as $websiteKey=> $website) {
		$websiteTotal = $website['Plan']['monthly']; 
	?>
		<tr style="font-weight: bold;">
			<td>
				<?php 
					$websiteDetails = array('id'=>$website['Website']['id'], 'name'=>$website['Website']['name'],'price'=>$website['Plan']['monthly'],'type'=>'website','website_id'=>$website['Website']['id']); 
					$websiteDetails_json = json_encode($websiteDetails);
					echo $this->Form->input('Cart.Items.' . $cartCount ,array('type'=>'checkbox',$checked,'value'=>$websiteDetails_json,'label'=>$website['Website']['name'],'hiddenField'=>false,'class'=>'cartItem website website_'. $website['Website']['id'],'onClick'=>"updateCart(this)"));
					$cartCount++;
				?>
			</td>
			<td><?php echo $website['Type']['name'];  ?></td>
			<td><?php echo $website['Engine']['name']; ?></td>
			<td><?php echo $website['Plan']['name']; ?></td>
			<td><?php echo $this->Number->currency($website['Plan']['monthly'],"NGN"); ?></td>
		</tr>
		<tr>
			<td style="text-align: right; font-weight: bold;">Template</td>
			<td colspan="3"><?php echo $website['Template']['name']; ?></td>
			<td><?php echo $this->Number->currency($website['Template']['price'],"NGN"); ?></td>
		</tr>
		<tr>
			<td  style="text-align: right; font-weight: bold;" rowspan="<?php echo count($website['WebsiteAddon']) + 1; ?>">Add Ons</td>
		</tr>
			<?php
				foreach ($website['WebsiteAddon'] as $addonKey=>$addon) {
					$websiteTotal +=$addon['Addon']['price'] ; 
			?>
						<tr>
						<td colspan="3">
							<?php
								$addOnDetails = array('id'=>$addon['Addon']['id'], 'name'=>$addon['Addon']['name']. "(Site: ". $addon['Website']['name'] . ")",'price'=>$addon['Addon']['price'],'type'=>'addon','website_id'=>$website['Website']['id']); 
								$addOnDetails_json = json_encode($addOnDetails);
								echo $this->Form->input('Cart.Items.' . $cartCount ,array('type'=>'checkbox',$checked,'value'=>$addOnDetails_json,'label'=>$addon['Addon']['name'],'hiddenField'=>false,'class'=>'cartItem website' . '_' . $website['Website']['id'] ,'onClick'=>"updateCart(this)"));
								$cartCount++;
							?>
						</td>
						<td><?php echo $this->Number->currency($addon['Addon']['price'],"NGN"); ?></td>
						</tr>
			<?php	}	?>
		<tr>
			<td colspan="3"></td>
			<td><b>Total</b></td>
			<td style="border-bottom: 3px double;"><b><?php echo $this->Number->currency($websiteTotal,"NGN"); ?></b></td>
		</tr><tr></tr><tr></tr>
	<?php	
		$cartTotal += $websiteTotal;
	} 
	?>
	
	
</table>
<h2>Upgrades & Add Ons</h2>
<table>
	<thead>
		<th>Name</th>
		<th>Price</th>
	</thead>
	<tbody>
		<?php foreach ($vars['cart']['addons'] as $addonKey=>$addon) { $cartTotal +=  $addon['Addon']['price'];   ?>
			<tr>
				<td>
					<?php 
						$addOnDetails = array('id'=>$addon['Addon']['id'], 'name'=>$addon['Addon']['name']. "(Site: ". $addon['Website']['name'] . ")",'price'=>$addon['Addon']['price'],'type'=>'addon','website_id'=>$addon['Website']['id']); 
						$addOnDetails_json = json_encode($addOnDetails);
						echo $this->Form->input('Cart.Items.' . $cartCount ,array('type'=>'checkbox',$checked,'value'=>$addOnDetails_json,'label'=>$addon['Addon']['name'],'hiddenField'=>false,'class'=>'cartItem','onClick'=>"updateCart(this)"));
						$cartCount++;
					?>
				</td>
				<td><?php echo $this->Number->currency($addon['Addon']['price'],"NGN"); ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<div>
	<h2 style="text-align: right;">Cart Total: ₦<span id="cartTotal"></span></h2>
</div>
<?php echo $this->Form->end(__('Checkout & Pay')); ?>

<script type="text/javascript">
	$(document).ready(function(){
		updateTotal();
	});
	function updateCart(obj){
		
			var item = JSON.parse($(obj).val());
			if(item['type'] == 'addon'){
				if($(obj).prop("checked")==true){
					var clss = '.cartItem.website.website_' + item['website_id'];
					$(clss).prop('checked', true);
				}
			}
			else if(item['type'] == 'website'){
				var clss = '.cartItem.website_' + item['id'];
				$(clss).prop('checked', $(obj).prop("checked"));
			}
		updateTotal()
		
	}
	
	function updateTotal(){
		var total = 0;
		$(".cartItem:checked").each(function(index){ //:checked
			var item = JSON.parse($(this).val());				
			total += parseInt(item['price']);			
		});
		$("#cartTotal").text(total); //TODO format the number with commas
	}
</script>