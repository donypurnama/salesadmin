<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	session_start();
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
?>
<html>
	<head>
		<title>Administration</title>
		<link rel="stylesheet" type="text/css" href="../style.css">
		<script type="text/javascript">
			function redirect() {
				window.location = "index.php";
			}
		</script>
	</head>
	<body onLoad="setTimeout('redirect()',1000)">
		<?php include('../menu.php'); ?>
		<center><font class="big"><b>Administration </b></font>
		<br><br><br><br>
		<font color=red><b>Sorry ..... User Have an Invoice</b></font>
		</center><br>
		<br>
</body>
</html>