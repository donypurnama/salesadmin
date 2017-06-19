	
<table width="100%" border=0 cellpadding=2 cellspacing=0>
<tr>
	<td class="small" align="right">
	<?php
	$username = $_SESSION['realname'];
	$groups = $_SESSION['groups'];
	echo "Welcome <b>".$groups."</b><br>";
	
	?></td>
</tr>
	<tr bgcolor=#3300ff>
		<th width="100%" class="small">
			<?php 
			if ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator') {
				?>
				<a href="<?php echo DOMAIN_NAME; ?>sales/" class="menu">Sales</a> |
				<a href="<?php echo DOMAIN_NAME; ?>finance/" class="menu">Finance</a> |
				<a href="<?php echo DOMAIN_NAME; ?>master/divisions.php" class="menu">Master</a> |
				<a href="<?php echo DOMAIN_NAME; ?>admin/index.php" class="menu">Users</a> | 
				<a href="<?php echo DOMAIN_NAME; ?>pass.php" class="menu">Change Password</a> |
				<a href="<?php echo DOMAIN_NAME; ?>backup/index.php" class="menu">Backup</a> |
				<a href="<?php echo DOMAIN_NAME; ?>logout.php" class="menu">Log Out</a>
				<?php
			} else if ($_SESSION['groups'] == 'finance') { 
			
			 ?>
				<a href="<?php echo DOMAIN_NAME; ?>sales/" class="menu">Sales</a> |
				<a href="<?php echo DOMAIN_NAME; ?>finance/" class="menu">Finance</a> |
				<a href="<?php echo DOMAIN_NAME; ?>master/salesman.php" class="menu">Master</a> |
				<a href="<?php echo DOMAIN_NAME; ?>pass.php" class="menu">Change Password</a> |
				<a href="<?php echo DOMAIN_NAME; ?>backup/index.php" class="menu">Backup</a> |
				<a href="<?php echo DOMAIN_NAME; ?>logout.php" class="menu">Log Out</a>
			<? 
			
			} else  { ?>
				<a href="<?php echo DOMAIN_NAME; ?>sales/" class="menu">Sales</a> |				
				<a href="<?php echo DOMAIN_NAME; ?>master/salesman.php" class="menu">Master</a> |
				<a href="<?php echo DOMAIN_NAME; ?>pass.php" class="menu">Change Password</a> |
				<a href="<?php echo DOMAIN_NAME; ?>backup/index.php" class="menu">Backup</a> |
				<a href="<?php echo DOMAIN_NAME; ?>logout.php" class="menu">Log Out</a>
			<? } ?>
		</th>
	</tr>
</table>