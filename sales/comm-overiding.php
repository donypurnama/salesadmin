<?php 
include('../constant.php'); 
include('../database.php'); 
$overgroupcode = $_GET['overgroupcode'];
$divisionid = $_GET['divisionid'];
$othercomm = $_GET['othercomm'];
//$jumcomm = $_GET['jumcomm'];
if ($divisionid <> '0') { $seldivision = $divisionid; }
$search = $_GET['search'];
$choose = $_GET['choose'];

if ($choose == 1) {
	$rdocomm = $_POST['rdocomm'];
	$seldivision = $_POST['seldivision'];
	
	$rsdiv = read_write("select 
							divisionname
						from 
							tb_division 
						where 
							divisionid='".$seldivision."' and divisioninv is not null");
							
	$rowdiv = mysql_fetch_array($rsdiv);

	$commstr = $rowdiv['divisionname'];
	$res = read_write("select * from tb_overrules where overgroupcode = '".$rdocomm."'");
	while ($rowcomm = mysql_fetch_array($res)) {
		$startdate = $rowcomm['startdate'];
		$enddate = $rowcomm['enddate'];
		$commstr = $commstr."<br>".$startdate." - ".$enddate.": ".$rowcomm['percent_comm']." %";
	}
	$commstr = $commstr."<br>> ".$enddate.": 0<br>";
	
	echo "<script language=javascript>";
	echo "window.opener.document.forms[0].overgroupcode.value='".$rdocomm."';";
	echo "window.opener.document.getElementById('overtype').innerHTML='".$commstr."';";
	echo "window.close();";
	echo "</script>";
}

?>
<html>
<head><title>Overriding</title>
<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
<script language=javascript>
	function choosediv() {
		var division = document.forms[0].seldivision.value;		
		location.href='comm-overiding.php?divisionid='+division+'&search=1';
	}
</script>
<link href="../templates/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<script type="text/javascript" src="../templates/js/joomla.javascript.js"></script>
<script type="text/javascript" src="../templates/css/mootools.js"></script>
<link href="../templates/css/system.css" rel="stylesheet"  type="text/css" />
<link href="../templates/css/template.css" rel="stylesheet" type="text/css" />
 
<link href="../templates/css/ie7.css" rel="stylesheet" type="text/css" />
<link href="../templates/css/ie6.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../templates/css/rounded.css" /></head>
<body>
<form name=frmadd method=post action="comm-overiding.php?choose=1">
<table border='0' cellspacing='1' cellpadding='1' align='center'>
	<tr class='header'><th class=small colspan=2 >Choose Division</th></tr>
	<tr class=small>
	<td><select name="seldivision" onChange="choosediv()" class="inputbox">
<option value="">--
<?php
	$res = read_write("select divisionid,divisionname from tb_division where divisioninv is not null");
	while ($row = mysql_fetch_array($res)) {
		echo "<option value='".$row['divisionid']."'";
		if ($row['divisionid']==$seldivision) { echo " selected"; }	echo ">".$row['divisionname'];
	}
?></select></td></tr>
</table><br>
<?

/*---------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
if ($search == 1 && $divisionid <> '' && $divisionid <> '0') {

	/*COUNT LIMIT */
	$res = read_write("select overgroupcode, implement_date, commgroupname from tb_commgroup where divisionid='".$divisionid."' order by rank desc");
			$data = mysql_fetch_array($res);
			$jumcomm=mysql_num_rows($res);
	/*COUNT LIMIT-*/
	
	
	
	if ($othercomm == 'all' || $overgroupcode <> '') {
		/* ALL COMMISSION SHOW */	
		$x = $jumcomm;	
		
	
		
		
		$res = read_write("select * from tb_commgroup where divisionid='".$divisionid."' order by rank desc limit ".$x."");
		while ($row = mysql_fetch_array($res)) {
			echo "<table border=1  cellspacing=0 cellpadding=1 align=center width='150'>";
			echo "<tr class=small bgcolor='#e5ebf9' class=header>
				<td colspan=2 >
					<input type=radio name=rdocomm value=".$row['overgroupcode']; 
						if ($overgroupcode == $row['overgroupcode']) { echo " checked"; }
					echo "> &nbsp;".$row['commgroupname']." </td>";
			echo "</tr>";
			
			$rs = read_write("select * from tb_overrules where overgroupcode='".$row['overgroupcode']."' order by startdate");
			while ($rw = mysql_fetch_array($rs)) {
				$startdate = $rw['startdate'];
				$enddate = $rw['enddate'];
				echo "<tr class=small>";
				if ($enddate <> 999) {
					echo "<td align='center'>".$startdate." - ".$enddate."</td>";
				} else {
					echo "<td align='center'>>= ".$startdate."</td>";
				}
				echo "<td align='center'>".$rw['percent_comm']."</td></tr>";
			}
				if ($enddate<>999 && mysql_num_rows($rs) > 0) {
				echo "<tr class=small>";
				echo "<td>> ".$enddate."</td>";
				echo "<td>0</td></tr>";
				}
				echo "</table><br>";
		}
			echo "<center>
			<input type=submit value=Choose class='tBox'> &nbsp;
			<input type=button value=Cancel class='tBox' onclick='window.close()'></center>";
		
	/* ALL COMMISSION SHOW */		
	} else {
		/* COMMISSION SHOW  ONLY ONE */	
		$x = 1;
		
		$def = read_write("SELECT * from tb_defaultoverrules where divisionid = '".$divisionid."'");
		$row_a = mysql_fetch_array($def);
		
		$commgroupcode_default = $row_a['overgroupcode'];
		
		$res_b = read_write("select overgroupcode, 
									implement_date, 
									commgroupname  
							from 
								tb_commgroup 
							where 
								overgroupcode='".$commgroupcode_default."' 
							order by 
								rank desc 
							limit ".$x."");
		$row_b = mysql_fetch_array($res_b);
		
			
		
	
		$res = read_write("select 
								overgroupcode, 
								implement_date, 
								commgroupname 
							from 
								tb_commgroup 
							where 
								overgroupcode='".$commgroupcode_default."' 
							order by 
								rank DESC 
							limit ".$x."");
							
		while ($row = mysql_fetch_array($res)) {
			echo "<table border=1  cellspacing=0 cellpadding=1 align=center width='150'>";
			echo "<tr class=small bgcolor='#e5ebf9' class=header>
				<td colspan=2 >
					<input type=radio name=rdocomm value=".$row['overgroupcode']; 
						if ($overgroupcode == $row['overgroupcode']) { 
							echo " checked"; 
						}
					echo " checked> &nbsp;".$row['commgroupname']." </td>";
			echo "</tr>";
			
		$rs = read_write("select * from tb_overrules where overgroupcode='".$row['overgroupcode']."' order by startdate");
		while ($rw = mysql_fetch_array($rs)) {
			$startdate = $rw['startdate'];
			$enddate = $rw['enddate'];
			echo "<tr class=small>";
			if ($enddate <> 999) {
				echo "<td align='center'>".$startdate." - ".$enddate."</td>";
			} else {
				echo "<td align='center'>>= ".$startdate."</td>";
			}
			echo "<td align='center'>".$rw['percent_comm']."</td></tr>";
		}
		if ($enddate<>999 && mysql_num_rows($rs) > 0) {
			echo "<tr class=small>";
			echo "<td>> ".$enddate."</td>";
			echo "<td>0</td></tr>";
			}
			echo "</table><br>";
	}
			echo "<center>
			<input type=submit value=Choose class='tBox'> &nbsp;
			<input type=button value=Cancel class='tBox' onclick='window.close()'></center>";
		
		echo "<br><div align=center><a href='comm-overiding.php?divisionid=".$seldivision."&search=1&othercomm=all'>Other Commission Type

		</a></div>";	
		
	}

	
} /* END ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

?>

</form>
</body>

</html>
