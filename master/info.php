<?php 
include('../constant.php'); 
include('../database.php'); 
if ($_SESSION['user'] == '')
{
	//Header('Location: '.DOMAIN_NAME.'index.php');
	Header('Location: ../index.php');
}

?>
<html>
<head>
	<title>System Info</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
</head>
<body>
	<?php include('../menu.php'); ?><BR><BR>
	<?
	
	phpinfo();
	
	?>
	
	
	</body>
</html>
