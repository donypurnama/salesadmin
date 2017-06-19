<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}

?>
<html>
	<head>
		<title>Master</title>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
	</head>
<body >
<?php include('../menu.php'); ?><br>
	<table border="0" cellspacing="0" cellpadding="7" align="center" width="60%" bgcolor="#e5ebf9">
			<tr bgcolor='white'>
				<td class="small"><div class="header icon-48-article"><b class='big'>Product: </b>	 </div>
				</td>
			</tr>
			<tr >
				<td class=header >&nbsp;</td>			
			</tr>
			<tr>
				<table border="0" cellspacing="2" cellpadding="2" width="60%" bgcolor="white" align=center>
				<?php 
				
				$res = read_write("select * from tb_division where divisioninv is not null");
				while ($row = mysql_fetch_array($res)) {			
					echo "<tr class=small>";
					echo "<td>&nbsp;</td><td><a href='product.php?divisionid=".$row['divisionid']."&x=1' class=smalllink>".$row['divisionname']."</a></td></tr>";
				} ?>
			</tr>
			
				</table>
			
		</table><br><br>
	</body>
</html> 

	
