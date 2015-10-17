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
<h1>Your Account</h1>
<?php echo $this->Html->link("Create Site",array('controller'=>'websites','action'=>'create')); //TODO DESIGN Format button ?>
<h2>Profile</h2>
<table>
	<tbody>
		<tr>
			<td>First Name</td><td><?php echo $user['User']['firstname']; ?></td>
			<td>Last Name</td><td><?php echo $user['User']['lastname']; ?></td>
		</tr>
		<tr>
			<td>Company</td><td><?php echo $user['User']['company']; ?></td>
			<td>Address</td><td><?php echo $user['User']['address']; ?></td>
		</tr>
		<tr>
			<td>Email</td><td><?php echo $user['User']['username']; ?></td>
			<td>Phone Number</td><td><?php echo $user['User']['phone']; ?></td>
		</tr>

	</tbody>
	
</table>

<?php
	$unpaid = "";
	$websites_table = "";
	$unpaid_count = 0;
	
	foreach ($user['Website'] as $website ) {
		if($website['status'] == "unpaid"){ $unpaid .= "<li>" . $website['name'] . "</li>"; $unpaid_count++;}
		else {
			$row_class = ""; $due = $this->Time->nice($website['duedate']);
			$view = ($website['status'] == "active")? $this->Html->link("View Site","http://" . $website['name'],array('target'=>'_blank')) : $website['msg']; //TODO DESIGN Button for Pay Now
			
			if ($website['pastdue']){
				$row_class = "class='pastdue'";
				$due = "Past Due: " . $this->Html->link("Pay Now",array('controller'=>'users','action'=>'checkout')); //TODO DESIGN Button
				$view="";
			}
			$websites_table .= 
			"<tr $row_class>
				<td>" . $website['name'] . "</td>
				<td>" . $website['Type']['name'] . "</td>
				<td>" . $website['Engine']['name'] . "</td>  
				<td>" . $website['views'] . "/" . $website['Plan']['view'] . "</td> 
				<td>" . $website['storage'] . "/" . $website['Plan']['storage']  . "</td>
				<td>$due</td>
				<td>" . $this->Html->link("Manage",array('controller'=>'websites','action'=>'manage',$website['id'])) . "</td>
				<td>$view</td>
			</tr>";
		}	
		//TODO DESIGN Implement Icon Logic
		//TODO DESIGN Change to Progress Bars 
		//TODO DESIGN Change to button
	}
?>
<?php if($unpaid): ?>
	<div class='spacer'> <!--TODO DESIGN Make an alert box-->
		<b>It's not too late!</b>
		You could still get: 
		<ul>
			<?php echo $unpaid; ?>
		</ul>
		<?php 
			$it_them = ($unpaid_count > 1)? "them":"it";
			echo $this->Html->link("Get $it_them Now",array('controller'=>'users','action'=>'checkout')); //TODO DESIGN Button TODO NICE See if it can be change to say get it/them now
		?>
	</div>
<?php endif; ?>
<?php if($websites_table != ""): ?>
<h2>Websites</h2>
<table>
	<thead>
		<td>Name</td>
		<td>Type</td>
		<td>Engine</td>
		<td>Views</td>
		<td>Storage</td>
		<td>Due Date</td>
	</thead>
	<tbody>
		<?php echo $websites_table; ?>
	</tbody>
</table>
<div class="note spacer">
	*Websites take approximately 24-48 hours to set-up, if it has been longer than 48hours, please contact support. <!--TODO CONTENT Add Support Email-->
</div>
<?php
	elseif($websites_table == "" && $unpaid == ""):
		echo "<div class='spacer'><h2>You don't have a website yet!</h2>"; 
		echo $this->Html->link("Create a Website",array('controller'=>'websites','action'=>'create')); //TODO DESIGN Change to button
		echo "</div>";
	endif;
?>


