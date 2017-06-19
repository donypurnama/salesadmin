<?php
	session_start();
	$_SESSION['user'] = '';
	session_destroy();
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<font class="small" align="center">
			You have logged out of the system. You need to <a href="index.php">log in</a> to be able to use this system.
		</font>
	</body>
</html>