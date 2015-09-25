<h1>Your Account</h1>
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

<h2>Websites</h2>
<table>
	<thead>
		<td>Name</td>
		<td>Type</td>
		<td>Engine</td>
		<td>Views</td>
		<td>Storage</td>
		<td>Due Date</td>
		<td><?php echo $this->Html->link("Create Site",array('controller'=>'websites','action'=>'create')); ?></td>
	</thead>
	<tbody>
		<?php
			foreach ($user['Website'] as $website ) {
				echo "<tr>";
				echo "<td>" . $website['name'] . "</td>";
				echo "<td>" . $website['Type']['name'] . "</td>"; 
				echo "<td>" . $website['Engine']['name'] . "</td>"; //TODO Implement Icon Logic
				echo "<td>" . $website['views'] . "/" . $website['Plan']['view'] . "</td>";
				echo "<td>" . $website['storage'] . "/" . $website['Plan']['storage']  . "</td>";
				echo "<td>" . $this->Time->nice($website['duedate']) . "</td>";
				echo "<td>" . $this->Html->link("Manage",array('controller'=>'websites','action'=>'manage',$website['id'])) . "</td>";
				echo "</tr>";
			}
		?>
	</tbody>
</table>
