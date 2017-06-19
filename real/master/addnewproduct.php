<?php include('../constant.php');?>
<?php include('../database.php');?>
<?php
if ($_SESSION['user'] == '')
{
	//Header('Location: '.DOMAIN_NAME.'index.php');
	Header('Location: ../index.php');
}
$divisionid 	= $_GET['divisionid'];
?>
<html>
<head>
	<title>Add New Product - Salesadmin</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
</head>
<body>
<form method="POST" action="insertproduct.php?divisionid=<? echo $divisionid;?>">
<table border="0" cellpadding="5" cellspacing="0" bgcolor="#e5ebf9">
<tr >
	<td class="small"><b class='big'>Division:</b></td>
	<td class="small" colspan="2">
	<? 
		$res = read_write("SELECT * FROM tb_division WHERE divisionid='".$divisionid."'");
		$row = mysql_fetch_array($res);
		$_SESSION ['divisionid'] = $divisionid;
		echo "<b class='big'>".$row['divisionname']."</b>";
	?>
	</td>
	
</tr>
<tr bgcolor='white'>
	<td class="small">Number of fields product:</td>
	<td><input type="text" name="jumproduct" size="3"></td>
	<td><input type="submit" value="Go"></td>
</tr>

	
	

</table>
</form>
</body>