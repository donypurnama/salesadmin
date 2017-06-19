<?php include('constant.php') ?>
<?php include('database.php') ?>
<?php
	session_start();
	if ($_SESSION['user'] == '')
	{
		Header('Location: '.DOMAIN_NAME.'index.php');
	}
	if ($_GET['change'] == '1')
	{
		$error = '';
		$success = false;
		$old = $_POST['currentpass'];
		$new = $_POST['newpass'];
		$repnew = $_POST['repeatpass'];
		$res = read_write("select * from tb_user where usercode = '".$_SESSION['user']."' and password = '".$old."'");
		
		if (mysql_num_rows($res) == 0)
		{
			$error = "Invalid password";	
		}
		else
		{
			if ($new != $repnew)
			{
				$error = "New passwords do not match";
			}
			else
			{
				if (strlen($new) < 6)
				{
					$error = "Password must be at least 6 characters";
				}
				else
				{
					$res = read_write("update tb_user set password = '".$new."' where usercode = '".$_SESSION['user']."'");
					$success = true;
				}
			}
		}
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<?php include('../menu.php'); ?>
		<?php 
			if($success) 
			{
		?>
			<center><font class="small">Password changed. </font></center>
		<?php 
			}
			else
			{
		?>
		<center><font class="big"><b>Change Password</b></font></center>
		<form action="pass.php?change=1" method="POST">
			<table border=0 cellpadding=1 cellspacing=1 width="100%">
				<tr>
					<th colspan=2 class="small"><font color=#ff0000><?php echo $error ?></font></th>
				</tr>
				<tr>
					<td width="50%" class="small" align="right"><font color=#ff0000>Current Password</font></td>
					<td width="50%"><input type="password" name="currentpass" class="forms" size=20 maxlength=20></td>
				</tr>
				<tr>
					<td class="small" align="right"><font color=#ff0000>New Password</font></td>
					<td><input type="password" name="newpass" class="forms" size=20 maxlength=20></td>
				</tr>
				<tr>
					<td class="small" align="right"><font color=#ff0000>Repeat New Password</font></td>
					<td><input type="password" name="repeatpass" class="forms" size=20 maxlength=20></td>
				</tr>
				<tr>
					<td colspan=2 align="center">
						<input type="submit" value="Submit" class="forms">
						<input type="reset" value="Reset" class="forms">
					</td>
				</tr>
			</table>
		</form>
		<?php
			}
		?>
	</body>
</html>