<?php include('database.php'); ?>
<?php
	session_start();
	
	$login = $_GET['login'];
	$error = '';
	$res = NULL;
	if ($login == '1')
	{
		$username = $_POST['usr'];
		$password = $_POST['passwd'];
		if (strpos($username," ") > 0 || strpos($password," ") > 0) {
			echo 'Invalid User Name or Password';
			exit();
		}
		
		
		$res = read_write("SELECT * FROM tb_user WHERE user_name = '".$username."'");
		$row = mysql_fetch_array($res);
		
		if ($password == $row['password'])
		{
			
			
			$_SESSION['groups'] = $row['groups'];
			$_SESSION['user'] = $row['usercode'];
			$_SESSION['realname'] = $row['realname'];
			switch ($row['homepage']) {
				case "sales":
				case "finance":
				case "admin":
					Header('Location: '.$row['homepage'].'/index.php');
					break;
				case "master":
					Header('Location: master/divisions.php');
					break;				
				default:
					Header('Location: sales/index.php');break;
			}			
		}
		else
		{
			$error = 'Invalid User Name or Password';
		}
	}
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<table border=0 width="100%">
			<tr>
				<td align="center">
					<b class="big">Sales Administration Facility</b>
					<br>
					<font color=#ff0000 class="small">You are required to log in to be able to use this facility.</font><br>
					<form method="POST" action="index.php?login=1">
						<table border=0 width="50%">
							<tr>
								<th colspan=2 class="small"><font color=#ff0000><?php echo $error; ?></font></th>
							</tr>
							<tr>
								<td width="50%" align="right" class="small">User Name</td>
								<td width="50%"><input type="text" name="usr" class="forms" size=20 maxlength=20></td>
							</tr>
							<tr>
								<td align="right" class="small">Password</td>
								<td><input type="password" name="passwd" class="forms" size=20 maxlength=20></td>
							</tr>
							<tr>
								<td colspan=2 align="center">
									<input type="submit" value="Log In" class="forms">
									<input type="reset" value="Reset" class="forms">
								</td>
							</tr>
						</table>
					</form>
				<td>
		</table>
	</body>
</html>