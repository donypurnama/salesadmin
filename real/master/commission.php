<?php 
include('../constant.php'); 
include('../database.php');

	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$search = $_GET['search'];
	$divisionid = $_GET['divisionid'];
	$op 		= $_GET['op'];

	
	
	$res_a = read_write("SELECT 
							*
						FROM 
							tb_commgroup 
						WHERE 
							divisionid='".$divisionid."'
						ORDER BY 
							rank DESC");
	
	$row = mysql_fetch_array($res_a);
	$def = read_write("SELECT * from tb_defaultcommrules where divisionid = '".$divisionid."'");
	$resdef = mysql_fetch_array($def);
	
	/*---------------------------------------------------------------------------------------------------------*/
	
	
	

?>
<html>
	<head>
		<title>Master</title>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language=javascript>
	function choosediv() {
		var division = document.forms[0].seldivision.value;		
		location.href='commission.php?divisionid='+division+'&search=1';
	}

	function delcommgroup(commgroupcode,divisionid) {
		if (confirm('Are you sure to delete this commission type?')) {
		location.href='viewcommission.php?del=1&commgroupcode=' + commgroupcode + '&divisionid=' + divisionid;
		}
	}
</script>
</head>
<body >
<?php include('../menu.php'); ?><br>
<form name=frmadd method=post action="commission.php?choose=1">
<table border='0' cellspacing=1 cellpadding=1 width='10%' align='center' >
<tr><td ><div class="header icon-48-sections">Commision</td></tr>
<tr>
	<td>
	<table border="0" cellspacing=1 cellpadding=1 width=100% >
	<tr class='header'><th class=small colspan=2 align="center">Choose Division</th></tr>
	<tr class=small>
	<td align="center">
	<select name="seldivision" class="inputbox" onchange="choosediv()">
	<option value="">--
	<?php
		$res = read_write("select divisionid,divisionname from tb_division where divisioninv is not null");
		while ($row = mysql_fetch_array($res)) {
			
			echo "<option value='".$row['divisionid']."'";
			if ($row['divisionid']==$divisionid) { echo " selected"; }	
			echo ">".$row['divisionname'];
		}
	?></select>
	</td></tr>
</table>
	</table>
<br>

<?
if ($search == 1 && $divisionid <> '') {
	

	
	$res = read_write("SELECT 
							*
						FROM 
							tb_commgroup 
						WHERE 
							divisionid='".$divisionid."'
						ORDER BY 
							rank DESC");
	
	
	
	while ($row = mysql_fetch_array($res)) {
		
	echo "<table border='0'  cellspacing=1 cellpadding='5' align=center width=220>";
	echo "<tr class=small bgcolor='#e5ebf9' class=header>";
	echo "<td colspan=3 align='center' width=><b>".$row['commgroupcode']."</b></td>";
	echo "</tr>";
	echo "<tr class=small>";
	echo "<td colspan=2 align=center ><font size='1' color='#666666'><b>";
	
	if ($row['commgroupname']  <>'' || $row['implement_date']<>'') {
						echo $row['commgroupname'];
						if( $row['implement_date'] <> '' ) {
						echo "<br>Per ".date("d M Y", strtotime($row['implement_date']));
						}
						echo "<br>";
					}
	
	echo "</b></font></td>";
    echo "<td align='center' width='80'><font size='1' color='#666666'><b>Action</b></td>";
	echo "</tr>";
	echo "<tr>";
    echo "<td colspan='2' align='center'><font size='1' color='#999999'><b>Commission rules</b></font></td>";
    echo "<td rowspan='2' valign='top' align='center' >";
	
	if($row['commgroupcode'] == $resdef['commgroupcode']) {
		echo "<img src='../templates/images/b_primary_active.png'>";
	} else {
		echo "<a href='defaultcommission.php?divisionid=".$divisionid."&op=delete&commgroupcode=".$row['commgroupcode']."'>
		<img src='../templates/images/b_primary.png'></a>";
	}
	
	
	
	if ($_SESSION['groups'] == 'root' ) { 	  
		echo "<a href='viewcommission.php?commgroupcode=".$row['commgroupcode']."' class=smalllink>
		<img src='../templates/images/b_edit.png'>&nbsp;</a>
		<a href='javascript:delcommgroup(\"".$row['commgroupcode']."\",\"".$divisionid."\")' class=smalllink><img src='../templates/images/b_drop.png' name='drop'></a>";
					  
	} 
	
	
	
	
	echo "</td>";
	echo "</tr>";
	
	$rs = read_write("select * from tb_commrules where commgroupcode='".$row['commgroupcode']."' order by startdate");
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
		if ($enddate<>999) {
		echo "<tr class=small>";
		echo "<td>> ".$enddate."</td>";
		echo "<td>0</td>
		
		</tr>";
		}
	echo "</table><br>";
	
	
	
		/*----------------------------------------------------------------------------------------------------------------------------------------------------*/
	
	} //end while $res
	
	if ($_SESSION['groups'] == 'root' ) { 	
	echo "<center><a href='editcommission.php?act=4&divisionid=".$divisionid."' class=smalllink>Add New Commission Type</a></center>";
	} 
	
} ?>
</form></body></html>
