<?php
include ('../constant.php');
include ('../database.php');
session_start();
if ($_SESSION['user'] == '') {
	Header('Location: '.DOMAIN_NAME.'index.php');
}
?>
<html>
<head>
<title>Sales Administration Help</title>
<link rel='stylesheet' type='text/css' href='../templates/css/style.css'>
<link rel='stylesheet' type='text/css' href='../templates/css/system.css'>
<link rel='stylesheet' type='text/css' href='../templates/css/templates.css'>
 <script type="text/javascript" src="../templates/css/mootools.js"></script>
  <script type="text/javascript" src="../templates/css/switcher.js"></script>
<link href="templates/khepri/css/ie7.css" rel="stylesheet" type="text/css" />
<link href="templates/khepri/css/ie6.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="../templates/css/rounded.css" />
	<script type="text/javascript" src="../templates/css/menu.js"></script>
	<script type="text/javascript" src="../templates/css/index.js"></script>
  
</head>
<body>
<?php include ('../menu.php'); ?><br>
<img src="../../templates/images/help/salesman.png" border="0">
		
		
		
</body>
</html>
 
  
  