<?php 
include('../constant.php'); 
include('../database.php'); 
	if ($_SESSION['user'] == '') {
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
?>
<html>
	<head>
		<title>Master</title>
			<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language=javascript>
			function delsales(salesid) {
				if (confirm("Are you sure to delete this salesman?")) {
				location.href='editsales.php?act=3&update=1&salesid=' + salesid; }
			}
		</script>
	</head>
<body >
<?php include('../menu.php'); ?><br>
<?php
$resds = read_write("select salescode, salesname from tb_salesman where position=3");
echo "<table border=0 cellspacing=0 cellpadding=3 align=center width=450>";
while ($rwds = mysql_fetch_array($resds)) {
	echo "<tr class=small>";
	echo "<td colspan='3' class=header height=50>District Supervisor:  <b>".$rwds['salesname']."</b></td></tr>";
	$resds_dl = read_write("select salesname, position from tb_salesman where district_supervisor=".$rwds['salescode']);
	echo "<tr valign=top bgcolor='#e5ebf9' class=header><td align=center><b>No</b></td><td><b>Trainee Name</b></td><td><b>Position</b></td></tr>";
	$i=1;
	while ($rwds_dl = mysql_fetch_array($resds_dl)) {
		echo "<tr class=small>";
		echo "<td align=center width=5>".$i."</td>";
		echo "<td>".$rwds_dl['salesname']."</td>";
		echo "<td>".$constant_salespos[$rwds_dl['position']]."</td>";
		echo "</tr>";	
		$i++;
	}
	echo "<tr><td height=10></td></tr>";
}
echo "</table>";
?>
<?php
	$resds = read_write("select salescode, salesname from tb_salesman where position=2");
		echo "<table border=0 cellspacing=0 cellpadding=3 align=center width=450>";
while ($rwds = mysql_fetch_array($resds)) {
	echo "<tr class=small>";
	echo "<td colspan='3' class=header height=50>Trainer: <b>".$rwds['salesname']."</b></td></tr>";

	$resds_dl = read_write("select salesname,position from tb_salesman where trainer='".$rwds['salescode']."'");

	echo "<tr valign=top bgcolor='#e5ebf9' class=header><td align=center><b>No</b></td><td><b>Trainee Name</b></td><td><b>Position</b></td></tr>";
	
	$i=1;
	while ($rwds_dl = mysql_fetch_array($resds_dl)) {
		echo "<tr class=small>";
		echo "<td align=center width=5>".$i."</td>";
		echo "<td>".$rwds_dl['salesname']."</td>";
	echo "<td>".$constant_salespos[$rwds_dl['position']]."</td>";
		echo "</tr>";
		
		$i++;
	}
	
	
	echo "<tr><td height=10></td></tr>";
}
echo "</table>";
?>
</body></html>