<?php 
include('../constant.php');
include('../database.php'); 

session_start();
include('bck_functions.php');

if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	
	$iptfill = $_POST['iptfill'];
	$_SESSION['chkbackup'] = "0";
	$done = $_GET['done'];

	if ($iptfill == 1) {
		$last_update = $_POST['last_update'];
		$lastsent = getlastsent();
		$yrls = date("Y");
		$mthls = date("n");
		$ddls = date("j");
		$ts_today = mktime(0,0,0,$mthls,$ddls,$yrls);
		
		$yrls = date("Y",strtotime($last_update));
		$mthls = date("n",strtotime($last_update));
		$ddls = date("j",strtotime($last_update));
		$ts_last_update = mktime(0,0,0,$mthls,$ddls,$yrls);

		$datediff = ((($ts_today - $ts_last_update) / 60) / 60) / 24;
		
		if ($datediff >= 0) {
			if ($last_update > $lastsent) {
				$chkluls = check_lu_between_ls($last_update, $lastsent);
				if ($chkluls) {
					$warning = "<br>Some data have not been sent yet. Please correct your last update";
				}
			} else {		
				echo "<html><body>";
				$host = $_SERVER['HTTP_HOST'];
				$self = $_SERVER['PHP_SELF'];				
				$fineurl = "$host";
				
				//echo "local path ". $localpath;
				
				//$path = "http://".$localipserver."/salesadmin/cobacoba/";
				
				echo "<form name='frmbu' id='frmbu' action='http://".$remoteipserver."/salesadmin/real/restore/restore.php?ip=".$fineurl."' method=post>";
				echo "<input type=hidden name=lpath value='".$localpath."'><br>";
				echo "<input type=hidden name=path value='".$path."'>";
				echo "<input type=hidden name=last_update value='".$last_update."'>";
				echo "<input type=hidden name=xmlcompany value=\"".createxmlcompany($last_update)."\">";
				echo "<input type=hidden name=xmlbuyer value=\"".createxmlbuyer($last_update)."\">";
				echo "<input type=hidden name=xmldelivery value=\"".createxmldelivery($last_update)."\">";
				echo "<input type=hidden name=xmlinvoice value=\"".createxmlinvoice($last_update)."\">";
				
				echo "<input type=hidden name=xmlsalesman value=\"".createxmlsalesman($last_update)."\">";
				echo "<input type=hidden name=xmlttu value=\"".createxmlttu($last_update)."\">";
				echo "<input type=hidden name=xmluser value=\"".createxmluser($last_update)."\">";
				echo "<input type=hidden name=xmldelcompany value=\"".createxmldel_company($last_update)."\">";
				echo "<input type=hidden name=xmldelbuyer value=\"".createxmldel_buyer($last_update)."\">";
				echo "<input type=hidden name=xmldeldeliveryaddr value=\"".createxmldel_deliveryaddr($last_update)."\">";
				echo "<input type=hidden name=xmldelinvoice value=\"".createxmldel_invoice($last_update)."\">";
				echo "<input type=hidden name=xmldelsalesman value=\"".createxmldel_salesman($last_update)."\">";
				echo "<input type=hidden name=xmldelttu value=\"".createxmldel_ttu($last_update)."\">";
				echo "<input type=hidden name=xmldeluser value=\"".createxmldel_user($last_update)."\">";
				echo "<input type=hidden name=localpath value=\"http://".$localipserver."/salesadmin/real/backup/index.php\">";
				echo "<center>Data collection process success. Please click Continue button to proceed<br>";
				echo "<input type=submit value='Continue'></center>";
				
				
				
				echo "</form>";
				$_SESSION['chkbackup'] = "1";
				//echo "<script language=javascript>";
				//echo "window.frmbu.submit();";
				//echo "</script>";
				echo "</body></html>";
				exit();
				
			}
		} else {
			$warning = "<br>Tanggal tidak boleh melebihi hari ini";
				
		}
	}
	
	
	
	if ($done=="1" ) { //&& $_SESSION['chkbackup'] == "1"
		$lu_done = $_GET['last_update'];
		read_write("update tb_company set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_buyer set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_deliveryaddr set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_invoice set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_salesman set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_ttu set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_user set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_delcompany set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_delbuyer set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_deldeliveryaddr set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_delinvoice set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_delsalesman set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_delttu set sentstatus=1 where last_update='".$lu_done."'");
		read_write("update tb_deluser set sentstatus=1 where last_update='".$lu_done."'");

		$ls = read_write("select count(*) from tb_sentdata");
		if (mysql_num_rows($ls)==0) {
			read_write("insert into tb_sentdata (lastsent) value ('".$lu_done."')");
		} else {
			read_write("Update tb_sentdata set lastsent='".$lu_done."'");
		}
		
		$warning = "<br>Data successfully sent to head office";
	} elseif ($done=="2") {
		$sdate = date("Ymd");
		$warning = "<br>Backup Data success (File name: <a href='dlzip.php?filename=bck_".$branch."_".$sdate."&bcklocation=".$_GET['bcklocation']."' class=smalllink>bck_".$branch."_".$sdate.".zip</a>)";
	}

	if ($failzip=="1") {
		$warning = "<br>Backup Failed";
	}

	$_SESSION['chkbackup'] = "0";
	$lastsent = getlastsent();
	

	
	
?>
<html>
	<head>
	<title>Send Data & Backup</title>
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
<body>
<?php include('../menu.php'); ?><br>

<?php echo $warning; ?> 

<?php
	if (($_SESSION['groups']=='root' || $_SESSION['groups']=='administrator' || $_SESSION['groups']=='sales')  && $branch <> '') {  //&& $branch <> '01'
?> 
<form name=frmbackup method=post action="index.php">
<input type=hidden name=iptfill value=1>
<table cellspacing=0 border=0>

<tr class=small><td colspan=2><b>Send Datas to Head Office</b> &nbsp;<a href="" onclick="javascript:window.open('http://<?php echo $remoteipserver;?>/salesadmin/restore/testconn.php')" class=verysmalllink>[Connection Test]</a></td></tr>
<tr><td height=10></td></tr>

<tr class="small">
	<td>Last Update: &nbsp;
	<input type=text class=forms name='last_update' value='<?php echo $lastsent; ?>' size=10> &nbsp;<a href="javascript:showCal('Calendar1')" class=smalllink>Select Date</a> &nbsp;
	<input type=submit value='Send' class=forms> </td>
</tr>
</table>
</form>
<?php } ?>
<form name=frmbackup2 method=post action="create_zip.php">
<table cellspacing=0 border=0 align=center width="20%">
<tr><td colspan=2><div class="header icon-48-massemail"><font class="big"><b>Back Up</b></font></div></td></tr>
<tr class=small>
	<td align=right><b>Backup
	<td>
		<input type=submit value=' Go ' class=tBox></td>
</tr>

</table>
</form>
<?php
if ($done=="2") {
	
	echo "<iframe id=frmexcel name=frmexcel style='visibility:hidden;width:10px;height:10px'></iframe>";
	echo "<script language=javascript>";
	echo "window.frmexcel.location.href='dlzip.php?filename=bck_".$branch."_".$sdate."&bcklocation=".$_GET['bcklocation']."';";
	echo "</script>";
}
?>

</body>
</html>

