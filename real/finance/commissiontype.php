<?php 
include('../constant.php'); 
include('../database.php'); 
$commgroupcode = $_GET['commgroupcode'];
$divisionid = $_GET['divisionid'];
if ($divisionid<> '') { $seldivision = $divisionid; }
$search = $_GET['search'];
$choose = $_GET['choose'];

if ($choose == 1) {
	$rdocomm = $_POST['rdocomm'];
	$seldivision = $_POST['seldivision'];
	$rsdiv = read_write("select divisionname from tb_division where divisionid='".$seldivision."'");
	$rowdiv = mysql_fetch_array($rsdiv);

	$commstr = $rowdiv['divisionname'];
	$res = read_write("select * from tb_commrules where commgroupcode='".$rdocomm."'");
	while ($rowcomm = mysql_fetch_array($res)) {
		$startdate = $rowcomm['startdate'];
		$enddate = $rowcomm['enddate'];
		$commstr = $commstr."<br>".$startdate." - ".$enddate.": ".$rowcomm['percent_comm']." %";
	}
	$commstr = $commstr."<br>> ".$enddate.": 0<br>";
	echo "<script language=javascript>";
	echo "window.opener.document.forms[0].commgroupcode.value='".$rdocomm."';";
	echo "window.opener.document.forms[0].divisionid.value='".$seldivision."';";
	echo "window.opener.document.getElementById('commtype').innerHTML='".$commstr."';";
	echo "window.close();";
	echo "</script>";
}

?>
<html>
<head><title>Commission</title>
<link rel="stylesheet" type="text/css" href="../style.css">
<script language=javascript>
	function choosediv() {
		var division = document.forms[0].seldivision.value;		
		location.href='commissiontype.php?divisionid='+division+'&search=1';
	}
</script>
</head>
<body>
<form name=frmadd method=post action="commissiontype.php?choose=1">
<table border=0 cellspacing=1 cellpadding=1 align=center>
	<tr><th class=small colspan=2>Choose Division</th></tr>
	<tr class=small>
	<td><select name="seldivision" class="forms" onchange="choosediv()">
	<option value="">--
<?php
	$res = read_write("select divisionid,divisionname from tb_division");
	while ($row = mysql_fetch_array($res)) {
		
		echo "<option value='".$row['divisionid']."'";
		if ($row['divisionid']==$seldivision) { echo " selected"; }	
		echo ">".$row['divisionname'];
	}
?></select></td></tr>
</table><br>
<?
if ($search == 1 && $divisionid <> '') {
	$res = read_write("select 
							commgroupcode, 
							implement_date, 
							commgroupname 
						from 
							tb_commgroup 
						where 
							divisionid='".$divisionid."' 
						order by 
							rank desc");
	
	
	while ($row = mysql_fetch_array($res)) {
		
		echo "<table border=1  cellspacing=0 align=center>";
		echo "<tr class=small><td colspan=2><input type=radio name=rdocomm value=".$row['commgroupcode'];
		if ($commgroupcode == $row['commgroupcode']) { echo " checked"; }
		echo "> &nbsp;".$row['commgroupname']." </td></tr>";
		$rs = read_write("select * from tb_commrules where commgroupcode='".$row['commgroupcode']."' order by startdate");
		while ($rw = mysql_fetch_array($rs)) {
			$startdate = $rw['startdate'];
			$enddate = $rw['enddate'];
			echo "<tr class=small>";
			if ($enddate <> 999) {
				echo "<td>".$startdate." - ".$enddate."</td>";
			} else {
				echo "<td>>= ".$startdate."</td>";
			}
			echo "<td>".$rw['percent_comm']."</td></tr>";
		}
		if ($enddate<>999) {
		echo "<tr class=small>";
		echo "<td>> ".$enddate."</td>";
		echo "<td>0</td></tr>";
		}
		echo "</table><br>";
	}
	echo "<center><input type=submit value=Choose class=forms> &nbsp;<input type=button value=Cancel class=forms onclick='window.close()'></center>";
} ?>
</form>
</body>

</html>
