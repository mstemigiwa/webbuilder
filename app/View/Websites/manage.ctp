<?php //debug($website); ?>
<?php //debug($addons); ?>
<h1><?php echo $website['Website']['name']; ?></h1>
<table>
	<tbody>
		<?php 
			echo "<tr><td>Domain</td><td>" . $website['Website']['name'] . "</td><td>Expires:</td><td>" . $this->Time->nice($website['Website']['created'] . "+1 year") . "</td></tr>"; //Add logic for past due websites
			echo "<tr><td>Type</td><td>" . $website['Type']['name'] . "</td><td>Engine:</td><td>" . $website['Engine']['name']  . "</td></tr>"; //TODO Add logic for icons
			echo "<tr><td>Views</td><td>" . $website['Website']['views'] . "/" . $website['Plan']['view'] . "</td><td>Resets:</td><td>" . $this->Time->nice($website['Website']['duedate']) . "</td></tr>";
			echo "<tr><td>Storage</td><td>" . $website['Website']['storage'] . "/" . $website['Plan']['storage']  . "</td></tr>";
		?>
	</tbody>
</table>
<h2>Add Ons</h2>
<table>
	<thead>
		<td>Name</td>
		<td>Description</td>
		<td>Recurring</td>
		<td>Purchase Date</td>
	</thead>
	<tbody>
		<?php
			foreach ($addons as $addon) {
				?>
				<tr>
				<td><?php echo $addon['Addon']['name']; ?></td>
				<td><?php echo $addon['Addon']['description']; ?></td>
				<td>
					<?php 
						if($addon['Addon']['recurring']) {echo "Yes";}
						else { echo "No"; }  
					?>
				</td>
				<td>
					<?php

					foreach ($website['WebsiteAddon'] as $purchased) {
						if($addon['Addon']['id'] == $purchased['addon_id']) {echo $this->Time->nice($purchased['created']);}
						else {echo "Add to Cart"; } //TODO Implement Cart Logic for past due add ons
					}
					?>
				</td>
				</tr>
				<?php
			}
		?>
	</tbody>
</table>