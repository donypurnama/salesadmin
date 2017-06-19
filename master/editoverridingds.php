<?php 
include('../constant.php');
include('../database.php');
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$commruleid = $_GET['overdsruleid'];
	$commgroupcode = $_GET['commgroupcode'];
	$divisionid = $_GET['divisionid'];

	$act = $_GET['act'];
	$update = $_GET['update'];
	if ($update <> 1) {
		if ($act == 2) {
			$res = read_write("select * from 
									tb_overdsrules 
								where 
									overdsruleid=".$commruleid);
			$row = mysql_fetch_array($res);
			$txtstart 	= $row['startdate'];
			$txtend 	= $row['enddate'];
			$txtpercent = $row['percent_comm'];
		} elseif ($act == 3) {
			read_write("delete from 
							tb_overdsrules 
						where 
							overdsruleid=".$commruleid);
			
			Header("Location: viewoverridingds.php?overdsgroupcode=".$commgroupcode."");
		}
	} else {
		$txtstart 	= $_POST['txtstart'];
		$txtend 	= $_POST['txtend'];
		$txtpercent = $_POST['txtpercent'];
		
		if ($act == 1) {
			read_write("insert into tb_overdsrules (
							startdate, 
							enddate, 
							percent_comm, 
							overgroupcode) 
						values (
							".$txtstart.",
							".$txtend.",
							".$txtpercent.",
							'".$commgroupcode."')");

		} elseif ($act == 2) {
			read_write("update tb_overdsrules set 
							startdate=".$txtstart.", 
							enddate=".$txtend.", 
							percent_comm=".$txtpercent." 
						where 
							overdsruleid=".$commruleid);
			
		} elseif ($act == 4) {
			$implement_date = $_POST['implement_date'];
			$commgroupname 	= stripslashes($_POST['commgroupname']);
			$commgroupcode  = stripslashes($_POST['overdsgroupcode']); 

		
			read_write("insert into tb_commgroup (
							divisionid, 
							implement_date, 
							commgroupname,
							overdsgroupcode) 
						values (
							'".$divisionid."',
							'".$implement_date."',
							'".$commgroupname."',
							'".$commgroupcode."'
							)");
			//$commgroupid = mysql_insert_id();

			read_write("insert into tb_overdsrules (
							startdate, 
							enddate, 
							percent_comm, 
							overgroupcode) 
						values (
							".$txtstart.",
							".$txtend.",
							".$txtpercent.",
							'".$commgroupcode."'
							)");
		
		
	
		
		}

		Header("Location: viewoverridingds.php?overdsgroupcode=".$commgroupcode."");
	}

	$res = read_write("select 
							divisionname 
						from 
							tb_commgroup, 
							tb_division 
						where 
							tb_commgroup.divisionid=tb_division.divisionid 
						and 
							tb_commgroup.divisionid='".$divisionid."'");
	$row = mysql_fetch_array($res);
	$divisionname = $row['divisionname'];
			
?>

<html>
	<head>
		<title>Master</title>
			<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
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
	<center> <br>
<?php
if ($act == 1 || $act == 2 || $act == 4) { ?>

<form name=frmcomm action="editoverridingds.php?update=1&act=<?php echo $act;?>&overdsruleid=<?php echo $overdsruleid;?>&overdsgroupcode=<?php echo $commgroupcode; ?>&divisionid=<?php echo $divisionid;?>" method=post>
	<?php if ($act==4) { ?>
	<table border='0' cellspacing='1' cellpadding='1' align='center' width='300'>
	<tr class='header'><th colspan='2'>Division : <?php echo $divisionname; ?></th></tr>
	<tr><td colspan='2' height='10'></td></tr>
		<tr class=small>
			<td>Commission Code: </td>
			<td><input type=text class=tBox size=10 name='commgroupcode'></td>
		</tr>
		<tr class=small>
			<td>Implement Date: </td>
			<td><input type=text class=tBox size=10 name='implement_date'> &nbsp;<a href="javascript:showCal('Calendar1')" class=smalllink>select date</a></td>
		</tr>
		<tr class=small>
			<td>Commission Name: </td>
			<td><input type=text class=tBox size=22 name='commgroupname'> </td>
		</tr>
		<tr class=small>
			<td>Division Id: </td>
			<td><input type=text class=tBox size=2 name='divisionid' value=<? echo $divisionid; ?> disabled> </td>
		</tr>
	</table><br>
<?php } ?>


<table border='0' cellspacing='5' cellpadding='1' align='center' width='300'>
<? if ($act==1) { ?>
<tr class='header'><th colspan='2' align='left'>Division : <?php echo $divisionname; ?></th></tr>
<?} ?>
<tr class='header'><th colspan='2'>Commission Rules</th></tr>
	<tr class=small>
		<td align='right'>Start</td>
		<td><input type=text class=tBox name=txtstart size=2 value='<?php echo $txtstart; ?>'></td>
	</tr>
	<tr class=small>
		<td align='right'>End</td><td>
		<input type=text class=tBox name=txtend size=2 value='<?php echo $txtend; ?>'></td>
	</tr>
	<tr class=small>
		<td align='right'>Percent</td>
		<td><input type=text class=tBox name=txtpercent size=5 value='<?php echo $txtpercent; ?>'></td>
	</tr>
	<tr><td colspan='2'></td></tr>
	<tr>
		<td colspan=2 align='center'> <input type=submit value='Save' class=tBox> &nbsp;

<input type=button value='Cancel' class=tBox onClick="location.href='comm-overidingds.php?overdsgroupcode=<?php echo $commgroupcode;?>'">
&nbsp;<input type=button value='Back' class=tBox onclick='javascript:location.href="comm-overidingds.php?divisionid=<?echo $divisionid;?>&search=1"'>&nbsp;
</td></tr>
</table>
</form>
<?php
	}
?>
</body>
</html>