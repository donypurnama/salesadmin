<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	
	
	$sid = $_GET['sid'];
	$ds = $_GET['ds'];
	$t = $_GET['t'];
	if($sid=='')
	{
	echo"<body onLoad='javascript:window.close()'>";
	}
	//echo '<a href="javascript:window.close();">CLOSE WINDOW</a>'; 
	$update = $_GET['update'];
	
	if ($update==1) {
		$trainer = $_POST['trainer'];
		if ($trainer == '') { $trainer=0; }
		$district_supervisor = $_POST['district_supervisor'];
		if ($district_supervisor == '') { $district_supervisor=0; }
		$commgroupcode = $_POST['commgroupcode'];
		$oldcommgroupcode = $_POST['oldcommgroupcode'];

		read_write("update 
						tb_invoice 
					set 
						trainer='".$trainer."', 
						district_supervisor='".$district_supervisor."', 
						commgroupcode='".$commgroupcode."',
						validate=0
					where 
						invoiceno='".$sid."'");

		if ($commgroupcode <> $oldcommgroupcode) {
			changecommission($sid, $commgroupcode, $percent_trainer, $percent_ds);
		}
		$msg = "<font color=red><b>Update success..</b></font>";
	} elseif ($update==2) {
		$res = read_write("update tb_invoice set validate=1 where invoiceno='".$sid."'");
		$msg = "<font color=red><b>Validate success..</b></font>";
	} elseif ($update==3) {
		$res = read_write("update tb_invoice set validate=0 where invoiceno='".$sid."'");
		$msg = "<font color=red><b>Un-Validate success..</b></font>";
	}

	
	$rsinv = read_write("select 
							totalreturn, ppnreturn,
							tb_invoice.invoiceno, 
							tb_invoice.invoicedate,
							tb_deliveryaddr.deliverycode, 
							tb_company.companyname, 
							tb_company.companycode, 
							tb_buyer.personname, 
							tb_buyer.buyercode, 
							tb_deliveryaddr.street as c_street, 
							tb_deliveryaddr.building as c_building, 
							tb_deliveryaddr.city as c_city, 
							tb_buyer.street as p_street, 
						
							tb_buyer.city as p_city, 
							tax+totalsales as total_value, 
							totalsalesusd,
							currency, kurs, invtax, discount, salesname, 
							tb_invoice.trainer, 
							tb_invoice.district_supervisor, 
							tb_invoice.commgroupcode, 
							tb_invoice.validate,
							tb_commgroup.divisionid 
						from 
						tb_invoice, 
						tb_company, 
						tb_buyer, 
						tb_deliveryaddr, 
						tb_salesman, 
						tb_commgroup 
						where 
							tb_invoice.buyercode=tb_buyer.buyercode
						and 
							tb_company.companycode=tb_deliveryaddr.companycode 
						and 
							tb_deliveryaddr.deliverycode=tb_buyer.deliverycode 
						and 
							tb_invoice.salescode=tb_salesman.salescode 
						and 
							tb_commgroup.commgroupcode=tb_invoice.commgroupcode 
						and 
							tb_invoice.invoiceno='".$sid."'");

	$row = mysql_fetch_array($rsinv);
	
	if ($row['deliverycode'] > $branch.".0000000") {
		$address = trim($row['c_street']);
		if (trim($row['c_building'])<>"") {
			$address = $address.", ".trim($row['c_building']);
		}
		if (trim($row['c_city'])<>"") {
			$address = $address.", ".trim($row['c_city']);
		}
		$b_company=1;
	} else {
		$address = trim($row['p_street']);
		if (trim($row['p_building'])<>"") {
			$address = $address.", ".trim($row['p_building']);
		}
		if (trim($row['p_city'])<>"") {
			$address = $address.", ".trim($row['p_city']);
		}
		$b_company=0;
	}

	$sid = $row['invoiceno'];
	
	if ($b_company==1) {
		$customer=stripslashes($row['companyname']);
	} else {
		$customer=stripslashes($row['personname']);
	}
	$totalvalue = $row['total_value'];
	$currency = $row['currency'];
	$kurs = $row['kurs'];
	$totalsalesusd = $row['totalsalesusd'];
	$invtax = $row['invtax'];
	$discount = $row['discount'];
	$totalreturn = $row['totalreturn'];
	$ppnreturn = $row['ppnreturn'];
	$salesman = $row['salesname'];
	$trainer = $row['trainer'];
	$district_supervisor = $row['district_supervisor'];
	$commgroupcode = $row['commgroupcode'];
	$divisionid = $row['divisionid'];
	$validate = $row['validate'];
	$invoicedate = $row['invoicedate'];
	
	if ($commgroupcode <> "") {
		$rsdiv = read_write("select divisionname from tb_division where divisionid='".$divisionid."'");
		$rowdiv = mysql_fetch_array($rsdiv);

		$commstr = $rowdiv['divisionname'];
		$rscomm = read_write("select * from tb_commrules where commgroupcode='".$commgroupcode."'");
		while ($rowcomm = mysql_fetch_array($rscomm)) {
			$startdate = $rowcomm['startdate'];
			$enddate = $rowcomm['enddate'];
			$commstr = $commstr."<br>".$startdate." - ".$enddate.": ".$rowcomm['percent_comm']." %";
		}
		$commstr = $commstr."<br>> ".$enddate.": 0<br>";
	}
?>
<html>
	<head>
	<title>Invoice's TTU</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language=javascript>
		function opencommission() {
			var scommgroupcode = document.forms[0].commgroupcode.value;
			var sdivisionid = document.forms[0].divisionid.value;
			window.open('commissiontype.php?commgroupcode='+scommgroupcode+'&divisionid='+sdivisionid+'&search=1','mywin','scrollbars=1,width=550,height=450,resizable=1');			
			}
		
		function chkcommission() {
			var commgroupcode = document.forms[0].commgroupcode.value;
			var oldcommgroupcode = document.forms[0].oldcommgroupcode.value;

			if (commgroupcode !== oldcommgroupcode) {
				if (confirm('You have been changed commission type. Are you sure to proceed?')) {
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		}
		
			function dounvalidate(sid) {
				if (confirm("Are you sure to Un-validate this data?")) {
					location.href='invoice.php?update=3&sid='+sid;
				}
			}
		</script>
		</head>
<body>
<?php
if(!$pop)
{
 include('../menu.php'); 
} ?><br>



<center><b class="big">Invoice's TTU Information</b></center>
<?php
echo "<font class=small><b>".$sid." </b> <br>";
if ($b_company==1) {
	echo "<a href='customer.php?cid=".$row['companycode']."' class=smalllink>".$customer."</a>";
	//echo "<a href='customer.php?cid=".$row['companycode']."' class=smalllink>".$row['companyname']."</a></td>";
} else {
	echo "<a href='customer.php?bid=".$row['buyercode']."' class=smalllink>".$customer."</a>";
}
echo "<br>";
echo $address."</font>";

$rssales = read_write("select * from tb_salesman where salescode > ".$branch.".00000 order by salesname"); 
	$i = 0;
	$num_sales = mysql_num_rows($rssales);
	while ($rwsales = mysql_fetch_array($rssales)) {
		$arrsalesname[$i]=$rwsales['salesname'];
		$arrsalesid[$i] = $rwsales['salescode'];
		$i++;
	}
?>
<form name=frmoverriding method=post action="invoice.php?update=1&sid=<?php echo $sid;?>" onsubmit="return chkcommission()">
<input type=hidden name=oldcommgroupcode value=<?php echo $commgroupcode?>>
<table cellspacing=0 border=0>
<tr class="small"><td colspan=2><?php echo $msg;  ?></td></tr>
<tr class="small">
			<td align="right" >Salesman:</td>
			<td><?php echo $salesman; ?></td>
		</tr>
		<tr class="small">
			<td align="right" >Trainer:</td>
			<td><select name='trainer' class=tBox><option value=''>
	<?php	
	for ($i=0;$i<$num_sales;$i++) {
			echo "<option value=".$arrsalesid[$i];
			if ($trainer == $arrsalesid[$i]) { echo " selected"; }	
			echo ">".$arrsalesname[$i];
		}
	?>
			</td>					
		</tr>
		<tr class="small">
			<td align="right" >District Supervisor:</td>
			<td><select name='district_supervisor' class=tBox><option value=>
	<?php	
		for ($i=0;$i<$num_sales;$i++) {
			echo "<option value=".$arrsalesid[$i];
			if ($district_supervisor == $arrsalesid[$i]) { echo " selected"; }	
			echo ">".$arrsalesname[$i];
		}
	?>
			</td>					
		</tr>
		<tr class='small'>
			<td align='right'>Invoice Date:</td>
			<td><?php echo $invoicedate;?></td>
		</tr>
		<tr valign=top class="small">
			<td align="right" >Commission Type:</td>
			<td><div id=commtype><?php echo $commstr; ?></div><a href='javascript:opencommission()' class=smalllink>Select type</a>
			<input type=hidden name=commgroupcode value=<?php echo $commgroupcode;?>><input type=hidden name=divisionid value=<?php echo $divisionid;?>></td>					
		</tr>
		<tr><td height=6></td></tr>
		<tr><td colspan=2 align=center>
			<input type=submit value='Save' class=tBox <?php if ($validate=='1') { echo "disabled"; }?>>
			<input type=button class=tBox value="Un-Validate" onclick="dounvalidate('<?php echo $sid;?>')" <?php if ($validate=='' || $validate=='0') { echo "disabled"; }?>>
			<?if($ds){ 
			echo "<input type=button class='tBox' value='Back To Overriding Ds' onclick=\"location.href='commission.php?search=1&ver=2&pos=3&ds=$ds'\">"; 
			}elseif($t){
			echo "<input type=button class='tBox' value='Back To Overriding T' onclick=\"location.href='commission.php?search=1&ver=2&pos=2&t=$t'\">"; 
			}
			?></td>
		</tr>
</table><br>
</form>
		<table border="0" cellspacing=0 cellpadding=2 width='40%'>
		<tr bgcolor=#e5ebf9 class=header>
			<td><b>TTU No.</b></td>
			<td align=right><b>Payment (RP)</b></td>
			<td align=center><b>TTU Date</b></td>
		</tr>
<?php
	$res = read_write("select 
						ttuid, 
						ttuno, 
						ppn_payment+payment 
					as 
						total_pay, 
						ttudate 
					from 
						tb_ttu 
					where 
						invoiceno='".$sid."' 
					and 
						ttuno like 'TU".$branch."%' order by ttuno desc");
					
	$total_pay = 0;
	if (mysql_num_rows($res) == 0) {
		echo "<tr class=small><td colspan=3>== No Payment Yet ==</td></tr>";
	} else {
		while ($row = mysql_fetch_array($res)) {
		$total_pay = $total_pay + $row['total_pay'];
		echo "<tr class=small>";
		echo "<td><a href='ttu.php?ttuid=".$row['ttuid']."' class=smalllink>".$row['ttuno']."</a></td>";
		echo "<td align=right>";
		echo number_format($row['total_pay'],0);		
		echo " &nbsp;</td>";
			
		echo "<td align=center>".date("j M Y",strtotime($row['ttudate']))."</td>";
		echo "</tr>"; }
	}
	
	/*-----------------------------------------------------------------*/

	$real_value = $totalvalue-$totalreturn-$ppnreturn; 
	
	/*-----------------------------------------------------------------*/
		$rsinv = read_write("select tb_invoice.invoicedate	from tb_invoice	where tb_invoice.invoiceno='".$sid."'");
		$row = mysql_fetch_array($rsinv);
		
		$rsinv = read_write("select sum(payment) + sum(ppn_payment) as sum_ttu from tb_ttu where invoiceno='".$sid."'");
		$rowinv = mysql_fetch_array($rsinv);
		$sum_ttu = $rowinv['sum_ttu'];
	
		$outstanding = $real_value - $sum_ttu;

		
?>	
	<tr><td height=5></td></tr>
	<tr><td colspan=4 align=right>
	<input <?php if($outstanding <= 0) { echo "disabled"; }  ?> type=button value="Tambah TTU" class=tBox onclick="javascript:location.href='ttu.php?sid=<?php echo $sid;?>'">	</td>
	</tr>
</table><br>

<table border=0 cellspacing=0 cellpadding=2 >
<tr class="small">
		<td colspan=2 ><b>Invoice Summary</b></td><td>&nbsp;</td>
						
	</tr>
<tr class="small">
		<td align="right" >Invoice Value:</td>
<?php
		
		echo "<td>Rp. ".number_format($totalvalue,0); 
		if ($currency <> "Rp.") {
			echo "<br>(USD ".number_format($totalsalesusd,0)." with kurs 1 USD=Rp ".$kurs.")";
		}
		echo "</td><td>&nbsp;</td>"; ?>
</tr>
<?php 
	if ($totalreturn <> "" && $totalreturn > 0) {
		//$real_value = $real_value - $totalreturn;
		echo "<tr class=small><td align=right>Nota Credit:</td><td>Rp. ".number_format($totalreturn,0)."</td></tr>";
		echo "<tr class=small><td align=right>Current Invoice:</td><td>Rp. ".number_format($real_value,0)."</td></tr>";
	}
?>
<tr class="small">
			<td align="right" >Total TTU:</td>
			<td><?php echo "Rp. ".number_format($sum_ttu,0); ?></td>					
</tr>
<tr class="small">
		<td align="right" >Outstanding:</td>
		<?php //$outstanding = $real_value - $total_pay; ?>
		<td><?php echo 'Rp. '.number_format($outstanding,0);?></td><td>&nbsp;</td>
</tr>
</table><br>
<input type=button value='Office Correction' class='tBox' onclick="javascript:location.href='tsp.php?sid=<?php echo $sid; ?>' " ><br><br>
<!-- list ttu yg bukan kode TU branch -->
<table border="0" cellspacing=0 cellpadding=2 width='40%'>
		
<?php
	
	$res = read_write("select 
						ttuid, 
						ttuno, 
						ppn_payment+payment 
					as 
						total_pay, 
						ttudate 
					from 
						tb_ttu 
					where 
						invoiceno='".$sid."' 
					and 
						ttuno not like 'TU".$branch."%' order by ttuno desc");
					
	$total_pay = 0;
	if (mysql_num_rows($res) == 0) {
		echo "<tr class=small><td colspan=3>&nbsp;</td></tr>";
	} else {
		echo "<tr bgcolor='#e5ebf9' class='header'>
				<td><b>TTU No. </b></td>
				<td align='right'><b>Payment <RP) </b></td>
				<td align='right'><b>TTU Date</b></td></tr>
		";
		
		
		while ($row = mysql_fetch_array($res)) {
		$total_pay = $total_pay + $row['total_pay'];
		echo "<tr class=small>";
		echo "<td><a href='tsp.php?ttuid=".$row['ttuid']."' class=smalllink>".$row['ttuno']."</a></td>";
		echo "<td align=right>";
		echo number_format($row['total_pay'],0);		
		echo " &nbsp;</td>";			
		echo "<td align=center>".date("j M Y",strtotime($row['ttudate']))."</td>";
		echo "</tr>"; }
	}
	
	/*-----------------------------------------------------------------*/
	$real_value = $totalvalue; 
	
	$outstanding = $real_value - $total_pay;
	/*-----------------------------------------------------------------*/
		$rsinv = read_write("select tb_invoice.invoicedate	from tb_invoice	where tb_invoice.invoiceno='".$sid."'");
		$row = mysql_fetch_array($rsinv);
		
		$rsinv = read_write("select sum(payment) + sum(ppn_payment) as sum_ttu from tb_ttu where invoiceno='".$sid."'");
		$rowinv = mysql_fetch_array($rsinv);
		$sum_ttu = $rowinv['sum_ttu'];
?>		
</table>
</body>
</html>
<?php
function changecommission($sid, $commgroupcode, $percent_trainer, $percent_ds) {
	$res = read_write("select 
							ttuid, 
							ttudate, 
							payment, 
							ppn_payment, 
							invoicedate 
						from 
							tb_ttu, 
							tb_invoice 
						where 
							tb_ttu.invoiceno=tb_invoice.invoiceno 
						and 
							tb_ttu.invoiceno='".$sid."'");
	
	while ($row = mysql_fetch_array($res)) {
		$ttuid = $row['ttuid'];
		$ttudate = $row['ttudate'];
		$payment = $row['payment'];
		$ppn_payment = $row['ppn_payment'];
		$invoicedate = $row['invoicedate'];

		$yrls = date("Y",strtotime($invoicedate));
		$mthls = date("n",strtotime($invoicedate));
		$ddls = date("j",strtotime($invoicedate));
		$ts_invoicedate = mktime(0,0,0,$mthls,$ddls,$yrls);
		
		$yrls = date("Y",strtotime($ttudate));
		$mthls = date("n",strtotime($ttudate));
		$ddls = date("j",strtotime($ttudate));
		$ts_ttudate = mktime(0,0,0,$mthls,$ddls,$yrls);
		$datediff = ((($ts_ttudate - $ts_invoicedate) / 60) / 60) / 24;	

		$rsrules = read_write("select 
									percent_comm 
								from 
									tb_commrules 
								where 
									commgroupcode='".$commgroupcode."' 
								and 
									(".$datediff. "-startdate)>=0 
								and 
									(enddate-".$datediff.")>=0");
		if (mysql_num_rows($rsrules) > 0) {
			$rspc = mysql_fetch_array($rsrules);
			$percent_comm = $rspc['percent_comm'];
			$commission = $payment * $percent_comm/100;

			$comm_trainer = $percent_trainer * $payment/100; //$percent_trainer & $percent_ds in constant.php
			$comm_ds = $percent_ds * $payment/100;

		} else {
			$percent_comm = 0;
			$commission = 0;
			$percent_trainer = 0;
			$percent_ds = 0;
			$comm_trainer = 0;
			$comm_ds = 0;
		}
				
		read_write("update 
						tb_ttu 
					set 
						commission=".$commission.", 
						percent_comm=".$percent_comm.", 
						percent_trainer=".$percent_trainer.", 
						comm_trainer=".$comm_trainer.", 
						percent_ds=".$percent_ds.", 
						comm_ds=".$comm_ds." 
					where ttuid=".$ttuid);
		
		
	}
}

?>