<?php //debug($cart);
	$cartTotal = 0;
	$checked = ($vars['isPost'])?"":"checked";
?>
<?php echo $this->Form->create('Cart'); ?>
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
					echo $this->Form->input('Cart.Website.' . $websiteKey . '.id',array('type'=>'checkbox',$checked,'value'=>$website['Website']['id'],'label'=>$website['Website']['name'],'hiddenField'=>false));
					//echo $website['Website']['name']; 
				?>
			</td>
			<td><?php echo $website['Type']['name']; ?></td>
			<td><?php echo $website['Engine']['name']; ?></td>
			<td><?php echo $website['Plan']['name']; ?></td>
			<td><?php echo $this->Number->currency($website['Plan']['monthly'],"NGN"); ?></td>
		</tr>
		<tr>
			<td>Template</td>
			<td colspan="3"><?php echo $website['Template']['name']; ?></td>
			<td><?php echo $this->Number->currency($website['Template']['price'],"NGN"); ?></td>
		</tr>
		<tr>
			<td  style="text-align: right;" rowspan="<?php echo count($website['WebsiteAddon']) + 1; ?>">Add Ons</td>
		</tr>
		<?php //TODO Add in Template Price ?>
			<?php
				foreach ($website['WebsiteAddon'] as $addonKey=>$addon) {
					$websiteTotal +=$addon['Addon']['price'] ; 
			?>
						<tr>
						<td colspan="3">
							<?php
								echo $this->Form->input('Cart.Website.' . $websiteKey . '.Addon.' . $addonKey . '.id'  ,array('type'=>'checkbox',$checked,'value'=>$addon['Addon']['id'],'label'=>$addon['Addon']['name'],'hiddenField'=>false)); 
								//echo $addon['Addon']['name']; 
							?>
						</td>
						<td><?php echo $this->Number->currency($addon['Addon']['price'],"NGN"); ?></td>
						</tr>
			<?php	}	?>
		<tr>
			<td colspan="3"></td>
			<th>Total</th>
			<th><?php echo $this->Number->currency($websiteTotal,"NGN"); ?></th>
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
						echo $this->Form->input('Cart.Addon.' . $addonKey . '.id'  ,array('type'=>'checkbox',$checked,'value'=>$addon['Addon']['id'],'label'=>$addon['Addon']['name'],'hiddenField'=>false)); 
								//echo $addon['Addon']['name'] . "(". $addon['Website']['name'] . ")"; 
					?>
				</td>
				<td><?php echo $this->Number->currency($addon['Addon']['price'],"NGN"); ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>

<div>
	<h2 style="text-align: right;">Cart Total: <?php echo $this->Number->currency($cartTotal,"NGN"); ?></h2>
</div>
<?php echo $this->Form->end(__('Checkout')); ?>