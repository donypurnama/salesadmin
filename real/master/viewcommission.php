<?php 
include('../constant.php'); 
include('../database.php'); 

	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$commgroupcode = $_GET['commgroupcode'];
	$update = $_GET['update'];
	$divisionid = $_GET['divisionid'];
	
	
	if ($update==1) {
	
	$query = "UPDATE 
						tb_commgroup 
					SET 
						commgroupcode 	='".stripslashes($_POST['commgroupcode'])."',
						commgroupname 	='".stripslashes($_POST['commgroupname'])."'"; 
						
	
	if($_POST['implement_date'] <> '') {
		$query=$query.",implement_date	='".$_POST['implement_date']."',rank = ".$_POST['rank'];
					
	}
	$query=$query." WHERE commgroupcode='".$commgroupcode."'";
		read_write($query);
		
		$message = "Database has been successfully updated ...";
					
	}
	
	$del = $_GET['del'];
	if ($del==1) {
		$divisionid = $_GET['divisionid'];
		read_write("DELETE FROM tb_commgroup where commgroupcode='".$commgroupcode."'");
		Header("Location: commission.php?search=1&divisionid=".$divisionid);
	}
	$res = read_write("select * from tb_commgroup where commgroupcode='".$commgroupcode."'");
	$row = mysql_fetch_array($res);
	
	/*---------------------------------------------------------------------------------------------------------------*/
	$res_a = read_write("select 
							divisionname 
						from 
							tb_commgroup, 
							tb_division 
						where 
							tb_commgroup.divisionid=tb_division.divisionid 
						and 
							tb_commgroup.commgroupcode='".$commgroupcode."'");
	/*---------------------------------------------------------------------------------------------------------------*/							
	$row_a = mysql_fetch_array($res_a);
	
	
?>
<html>
	<head>
		<title>Master</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language=javascript>
		function deletecomm(commruleid, commgroupcode) {
			if (confirm("Are you sure to delete this commission rule?")) {
				location.href='editcommission.php?act=3&commruleid=' + commruleid + '&commgroupcode=' + commgroupcode;
			}
		}
		</script>
		<script language="javascript" src="cal2.js">
			/*
			Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
			Script featured on/available at http://www.dynamicdrive.com/
			This notice must stay intact for use
			*/
	</script>
	<script language="javascript" src="cal_conf2.js"></script>
</head>
<body >
<?php include('../menu.php'); ?><br>	
<form name=frmcomm action="viewcommission.php?update=1&commgroupcode=<?php echo $commgroupcode;?>" method=post>
<table border='0' cellspacing='1' cellpadding='3' align='center' width='300'>
<tr class='header'><th colspan='2'>Division : <?php echo $row_a['divisionname']; ?></th></tr>
<tr>
	<td colspan=2 align=right></td>
</tr>
<tr>
	<td colspan=2 align=center><font color=red size='2pt'><b><?php echo $message; ?></b></font></td>
</tr>
<tr class=small>
	<td>Commission Group</td>
	<td><input type=text class=tBox size=10 name='commgroupcode' value='<?php echo $row['commgroupcode'];?>'> </td>
</tr>
<tr class=small>
	<td>Implement Date</td>
	<td><input type=text class=tBox size=10 name='implement_date' value='<?php echo $row['implement_date'];?>'> &nbsp;<a href="javascript:showCal('Calendar1')" class=smalllink>Select Date</a></td>
</tr>
<tr class=small>
	<td>Commission Name</td>
	<td><input type=text class=tBox size=22 name='commgroupname' value='<?php echo $row['commgroupname'];?>'> </td>
</tr>
<tr class=small>
	<td>Rank</td>
	<td><input type=text class=tBox size=22 name='rank' value='<?php echo $row['rank'];?>'> </td>
</tr>
<tr>
	<td colspan=2 align=right></td>
</tr>
<tr>
	<td colspan=2 align=right><input type=submit value='Save' class=tBox >
	&nbsp;<input type=button value='Back' class=tBox onclick='javascript:location.href="commission.php?divisionid=<?echo $row['divisionid'];?>&search=1"'>&nbsp;
	</td>
</tr>
</table>
</form><br>
<table border='0' cellspacing='0' cellpadding='5' align='center' width='300'>
<?php
	$res = read_write("select 
							* 
						from 
							tb_commgroup, 
							tb_division 
						where 
							tb_commgroup.divisionid=tb_division.divisionid 
						and 
							tb_commgroup.commgroupcode='".$commgroupcode."'");
	$row = mysql_fetch_array($res);
	$divisionname = $row['divisionname'];
	$divisionid = $row['divisionid'];
	
	
?>

	<tr class='header'>
	<th colspan=4 align='left'>Division : <?php echo $divisionname; ?></th>
	</tr>
	<tr valign=top bgcolor=#e5ebf9 class=header>	
	<td align=center><b>Start</b></td>
	<td align=center><b>End</b></td><td width=100 align=center><b>Percent (%)</b></td>
	<td>&nbsp;</td></tr>
<?php	$res = read_write("select * from tb_commrules where commgroupcode='".$commgroupcode."'");
		while ($row = mysql_fetch_array($res)) {
			echo "<tr class=small>";
			echo "<td align=center>".$row['startdate']."</td><td align=center>".$row['enddate']."</td>";
			echo "<td align=right>".$row['percent_comm']." &nbsp;</td>";
			echo "<td align='center'><a href='editcommission.php?act=2&commruleid=".$row['commruleid']." &commgroupcode=".$commgroupcode."'  class=smalllink >Edit</a> 
			
			&nbsp;<a href='javascript:deletecomm(".$row['commruleid'].",\"".$commgroupcode."\")' class=smalllink>Delete</a></td></tr>";

		
		}
?>
	<tr><td height=6></td></tr>
	<tr class=small>
	<td colspan=4><a href='editcommission.php?act=1&commgroupcode=<?php echo $commgroupcode; ?>&divisionid=<? echo $divisionid;?>' class=smalllink>Add New Commission Rule</a></td>
	</tr>
	</table>
</body>
</html>



