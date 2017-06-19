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
		<title>Master</title>
		<link rel="stylesheet" type="text/css" href="../style.css">
		<script type="text/javascript">
			function redirect() {
				window.location = "salesman.php";
			}
		</script>
	</head>
	<body onLoad="setTimeout('redirect()',1000)">
		<?php include('../menu.php'); ?><br>
		<center>
		<?php if($_SESSION['groups'] == 'root') 
		{?>	
			<a class=smalllink href='divisions.php' >Product</a> | 
			<b class="big">Salesman</b> |  
			<a class=smalllink href='commission.php'>Commission</a>	
		<?} elseif ($_SESSION['groups'] == 'root')  {?>
			<a class=smalllink href='divisions.php' >Product</a> | 
			<b class="big">Salesman</b> |  
			<a class=smalllink href='commission.php'>Commission</a>	
		<? } else { ?>
		<b class="big">Salesman</b>   
		<? } ?><br><br><br><br>
		<font color=red><b>Sorry ........ Salesman Have an Invoice</b></font>
		</center><br>
		<br>
</body>
</html>