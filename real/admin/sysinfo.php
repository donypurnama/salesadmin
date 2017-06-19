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
<title>System Information</title>
<link rel='stylesheet' type='text/css' href='../templates/css/style.css'>
 
   <script type="text/javascript" src="../templates/css/joomla.javascript.js"></script>
  <script type="text/javascript" src="../templates/css/mootools.js"></script>
  <script type="text/javascript" src="../templates/css/switcher.js"></script>
 
 
<link rel="stylesheet" href="../templates/css/system.css" type="text/css" />
<link href="../templates/css/template.css" rel="stylesheet" type="text/css" />
<link href="templates/khepri/css/ie7.css" rel="stylesheet" type="text/css" />
<link href="templates/khepri/css/ie6.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../templates/css/rounded.css" />
<script type="text/javascript" src="../templates/css/menu.js"></script>
<script type="text/javascript" src="../templates/css/index.js"></script>


</head>
<body>
<?php include ('../menu.php'); ?><br>
<div class="header icon-48-systeminfo">Information</div>
<div class="clr"></div>
	<div id="submenu-box">
		<div class="t">
			<div class="t">
				<div class="t"></div>
	 		</div>
 		</div>
		<div class="m">
			<div class="submenu-box">
		<div class="submenu-pad">asasS
		<ul id="submenu" class="information">
			sasas
		</ul>		
		
	</div>
</div>
<div class="clr"></div>
</div>
<div class="b">
	<div class="b">
		<div class="b"></div>
	</div>
</div>
</div>
</body>
</html>
 