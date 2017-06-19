<?php include('constant.php') ?>
<?php
	session_start();
	if ($_SESSION['user'] == '')
	{
		Header('Location: '.DOMAIN_NAME.'index.php');
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body class="small">
		<?php include('menu.php'); ?>
		<center>
			<b class="big">Home Page</b><br><br><br>
			Welcome <b><?php echo $_SESSION['user']; ?></b>.
		</center>
        

	</body>
</html>
