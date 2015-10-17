<style type="text/css">
	.pastdue {
		background-color:#FFE6E6;
	}
	.note {
		font-style:italic;
		font-size: .85em;
	}
	
	.spacer{ margin: 25px;}
</style>
<?php //debug($website['Website']); ?>
<?php //debug($addons); ?>
<?php
	$due_class = "";
	if($website['Website']['pastdue']) 
		{
			echo $this->Html->link("View Site","http://" . $website['Website']['name'],array('target'=>'_blank')); //TODO DESIGN Button and float right
			$due_class = "class='pastdue'";
		}
?>
<h1><?php echo $website['Website']['name']; ?></h1>
<table>
	<tbody>
		<?php 
			echo "<tr><td>Domain</td><td>" . $website['Website']['name'] . "</td><td>Due Date:</td><td $due_class>" . $this->Time->nice($website['Website']['duedate']) . "</td></tr>"; //Add logic for past due websites
			echo "<tr><td>Type</td><td>" . $website['Type']['name'] . "</td><td>Engine:</td><td>" . $website['Engine']['name']  . "</td></tr>"; //TODO DESIGN Add logic for icons
			echo "<tr><td>Views</td><td>" . $website['Website']['views'] . "/" . $website['Plan']['view'] ."</td>";
			echo "<tr><td>Storage</td><td>" . $website['Website']['storage'] . "/" . $website['Plan']['storage']  . "</td>";
			echo "</tr><tr><td>Status</td><td colspan='100'>";
			echo $website['Website']['msg'];//TODO DESIGN Format properly
			echo "</td></tr>";
		?>
	</tbody>
</table>
<?php if(count($website['WebsiteAddon'])):?>
<h2>Add Ons</h2>
<table>
	<thead>
		<td>Name</td>
		<td>Description</td>
		<td>Due Date</td>
	</thead>
	<tbody>
	<?php
		foreach ($website['WebsiteAddon'] as $addon) {
			$due_class = "";
			$due=($addon['Addon']['recurring'])?$this->Time->nice($addon['duedate']):"-";
			if(!$this->Time->isFuture($addon['duedate']) && $addon['Addon']['recurring'] == true){
				$due = "Past Due: " . $this->Html->link("Pay Now",array('controller'=>'users','action'=>'checkout')); //TODO DESIGN Button
				$due_class = "class='pastdue'";
			}
	?>
			<tr <?php echo $due_class; ?>>
			<td><?php echo $addon['Addon']['name']; ?></td>
			<td><?php echo $addon['Addon']['description']; ?></td>
			<?php echo "<td $due_class>$due</td>" ?>
	<?php } ?>
	</tbody>
</table>
<?php endif; ?>