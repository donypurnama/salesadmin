<?php 
include('../constant.php'); 
include('../database.php'); 
	if ($_SESSION['user'] == '')
	{
	//	Header('Location: '.DOMAIN_NAME.'index.php');
	Header('Location: ../index.php');
	}

?>
<html>
	<head>
		<title>Master</title>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language=javascript>
			function delsales(salescode) {
				if (confirm("Are you sure to delete this salesman?")) {			
				location.href='editsales.php?act=3&update=1&salescode='+salescode; 
				
				}
			}
		</script>
	</head>
	<body >
		<?php include('../menu.php'); ?>
		
		<br>
		<table border="0" cellspacing=0 cellpadding=3 width=60%>
		<tr><td colspan=7><div class="header icon-48-trash"><font class="big"><b>Salesman</b></font></td></tr>
		<tr valign=top bgcolor="#e5ebf9" class=header>
			<td align="center"><b>Sales Code</b></td>
			<td ><b>Sales Name</b></td>
			<td  align="center"><b>Alias</b></td>
			<td align="center"><b>Position</b></td>
			<td align="center"><b>Trainer</b></td>
			<td align="center"><b>Supervisor</b></td>
			<td align="center"><b>Active</b></td>
		</tr>
	<?php
		$res = read_write("select *	from tb_salesman  where salescode like '".$branch."%' order by salesname");
		
		
while ($row = mysql_fetch_array($res)) {
	if ($row['salescode'] <> $branch.'.0000') {
		echo "<tr class=small>";
		echo "<td align='center'>".$row['salescode']."</td>";
		echo "<td><a class=smalllink href='editsales.php?act=2&salescode=".$row['salescode']."'>".$row['salesname']."</a></td>";
		echo "<td align='center'>".$row['alias']."</td>";
		echo "<td align='center'>".$constant_salespos[$row['position']]."</td>";
		
		
		
		
		$rs_train = read_write("select salesname from tb_salesman where salescode='".$row['trainer']."'");
		$irow = mysql_fetch_array($rs_train);
		$isalesname = $irow['salesname'];
				
		if ($isalesname <>'') {
			echo "<td align='center'>".$isalesname."</td>";
		} else { 
			echo "<td align='center'>-</td>"; 
		}
		
		
		
		mysql_free_result($rs_train);
		$rs_train = read_write("select salesname from tb_salesman where salescode='".$row['district_supervisor']."'");
		$irow = mysql_fetch_array($rs_train);
		$isalesname = $irow['salesname'];
		
			
		if ($isalesname<>'') {
			
			echo "<td align='center'>".$isalesname."</td>";
		} else { 
			echo "<td align='center'>-</td>"; 
		}
				
		if ($row['active']==1) {
				echo "<td align='center'><img src='../templates/images/checklist.gif'></td>";
			} else {
				echo "<td></td>";
			}
		
		mysql_free_result($rs_train);
		if($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator') { 
		
		
		} else {
		echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
	}
}

	?>
	<tr><td height=10></td></tr>
	<tr class=small>
	<td colspan=7><?//if ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator' || $_SESSION['groups'] == 'personnel') { ?>
					<a class=smalllink href='editsales.php?act=1'>Add New Salesman</a> &nbsp;|&nbsp;  <? //} ?>
	
	<a class=smalllink href='overriding.php' >Overriding Scheme</a></td></tr>
	</table>
	</body>
</html>