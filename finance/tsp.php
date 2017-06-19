<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	session_start();
	if ($_SESSION['user'] == '')
	{
		Header('Location: ../index.php');
	}	
	$ttuid 	= $_GET['ttuid'];
	$sid	= $_GET['sid'];
	$update = $_GET['update'];
	
	if ($update == 1) {
	
		$ttuno 		= $_POST['ttuno'];
		$ttudate 	= $_POST['ttudate'];
		$strpayment = $_POST['payment']; // it's bruto/gross payment
		$strpayment = str_replace(",","",$strpayment);
		$strpayment = str_replace(".","",$strpayment);
		
		$ppn_payment = $_POST['ppn_payment'];
		$ppn_payment = str_replace(",","",$ppn_payment);
		$ppn_payment = str_replace(".","",$ppn_payment);
		
		if ($ppn_payment=='') { $ppn_payment=0; }
		$payment 		= $strpayment - $ppn_payment;
		$outstanding 	= $_POST['outstanding'];
		
		if ($ttuid<>'') {
			$oldpayment = $_POST['oldpayment'];
			$outstanding = $outstanding + $oldpayment;
		}
		
		
		if ($payment > 0 && ($outstanding-$payment)>=0) {
			
			$lpudate 	= $_POST['ttudate'];
			$depositdate 	= $_POST['ttudate'];
			

			$invoicedate 	= $_POST['invoicedate'];
			$yrls = date("Y",strtotime($invoicedate));
			$mthls = date("n",strtotime($invoicedate));
			$ddls = date("j",strtotime($invoicedate));
			$ts_invoicedate = mktime(0,0,0,$mthls,$ddls,$yrls);
			
			$yrls = date("Y",strtotime($ttudate));
			$mthls = date("n",strtotime($ttudate));
			$ddls = date("j",strtotime($ttudate));
			$ts_ttudate = mktime(0,0,0,$mthls,$ddls,$yrls);
			$datediff = ((($ts_ttudate - $ts_invoicedate) / 60) / 60) / 24;		

			
				$percent_comm = 0;
				$commission = 0;
				$percent_trainer = 0;
				$percent_ds = 0;
				$comm_trainer = 0;
				$comm_ds = 0;
				
			if ($ttuid <> '') {
				$res = read_write("update 
										tb_ttu 
									set 	
										ttuno='".$ttuno."',
										ttudate='".$ttudate."', 
										lpudate='".$lpudate."', 
										depositdate='".$depositdate."', 
										usercode ='".$_SESSION['user']."',
										last_update='".$today."',
										payment=".$payment.", 
										ppn_payment=".$ppn_payment.", 
										commission=".$commission.", 
										percent_comm=".$percent_comm.", 
										percent_trainer=".$percent_trainer.", 
										comm_trainer=".$comm_trainer.", 
										percent_ds=".$percent_ds.", 
										comm_ds=".$comm_ds.",
										sentstatus 		= 0
									where 
										ttuid=".$ttuid);
										
				$message = "TTU Correction has been successfully updated ...";
			} else {
			//AUTO NUMBERIN FOR ADD TTU  //ADD TTU BARU
			//$rstemp = read_write("SELECT replace(ttuno, 'TSP".$branch.date("ym")."-','') as nbr FROM tb_ttu order by ttuno desc limit 1");
			$rstemp = read_write("select substring(ttuno, 11) as nbr from tb_ttu where substring(ttuno, 1, 3) = 'TSP' order by ttuno desc limit 1");
			
			
			$rowtemp = mysql_fetch_array($rstemp);
			$ttutemp = (int) $rowtemp['nbr'];
			
			$ttutemp++;
				if (strlen($ttutemp)==1) {
					$ttutemp = '00'.$ttutemp;
				} elseif (strlen($ttutemp)==2) {
					$ttutemp = '0'.$ttutemp;
				}
				$ttuno = "TSP".$branch.date("ym")."-".$ttutemp;		
										
				 $res = read_write("insert into tb_ttu (invoiceno, ttuno, ttudate, lpudate, depositdate,
									payment, ppn_payment, commission, percent_comm, percent_trainer, 
									comm_trainer, percent_ds, comm_ds,last_update,usercode, sentstatus) 
									values (
									'".$sid."', '".$ttuno."', '".$ttudate."', '".$lpudate."',
									'".$depositdate."', ".$payment."," .$ppn_payment.", 
									".$commission.", ".$percent_comm.", ".$percent_trainer.",
									".$comm_trainer.", ".$percent_ds.", ".$comm_ds.", '".$today."',
									'".$_SESSION['user']."', 0
									)");
									
									
						
									
				$ttuid = mysql_insert_id();		
				$message =  "TTU Correction has been successfully inserted ...";
			}
		} else {
			if ($outstanding-$payment<0) {
				$message = "Pembayaran tidak boleh lebih besar dari outstanding";
			} else {
				$message = "Pembayaran harus lebih besar dari PPn";
			}
		}
	} elseif ($update == 2) {		
		if ($branch <> '01' && $branch <> '00' && $branch <> '') {
			/***** INSERT TTUNO TO TBDELTTU  *******/
				$ttuno = $_GET['ttuno'];
				$res = read_write("INSERT INTO tb_delttu (ttuno, last_update, sentstatus) VALUES ('".$ttuno."','".$today."', 0)");		
			/*----------------------------------------------------*/
		}
		read_write("delete from tb_ttu where ttuid=".$ttuid);
		Header("Location: invoice.php?sid=".$sid);
	}
	if ($ttuid <>'') {
		$res = read_write("select tb_ttu.*,
								tb_invoice.invoiceno,
								tb_invoice.tax+tb_invoice.totalsales 
							as 
								total_pay, 
								kurs, 
								currency, 
								invtax, 
								discount, 
								totalreturn,  
								tb_invoice.commgroupcode, 
								invoicedate, 
								salesname, 
								tb_invoice.trainer, 
								tb_invoice.district_supervisor, 
									
								tb_commgroup.divisionid 
							from 
								tb_ttu, 
								tb_invoice, 
								tb_salesman, 
								tb_commgroup 
							where 
								tb_ttu.invoiceno=tb_invoice.invoiceno 
							and 
								tb_salesman.salescode=tb_invoice.salescode 
							and 
								tb_invoice.commgroupcode=tb_commgroup.commgroupcode 
							and ttuid=".$ttuid);
			$num_rows = mysql_num_rows($res);
		
		if ($num_rows > 0) {
			$row = mysql_fetch_array($res);
			
			$sid = $row['invoiceno'];
			$invno = $row['invoiceno'];
			$ttuno = $row['ttuno'];
			
			$ttudate = $row['ttudate'];
			$payment = $row['payment'];
			
			$ppn_payment = $row['ppn_payment'];
			$strpayment = $payment + $ppn_payment; // it's gross to input
			$lpudate 	= $_POST['ttudate'];
			$depositdate 	= $_POST['ttudate'];
			$total_pay = $row['total_pay'];
			$currency = $row['currency'];
			$kurs = $row['kurs'];
		}
		
	} else {
		$res = read_write("SELECT 
								tb_invoice.invoiceno, 
								tax, 
								tax+totalsales as total_pay, 
								kurs, 
								currency, 
								invtax, 
								discount, 
								totalreturn, 
								tb_invoice.commgroupcode, 
								invoicedate, 
								salesname, 
								tb_invoice.trainer, 
								
								tb_salesman.district_supervisor,
								tb_commgroup.divisionid 
						FROM 
							tb_invoice, 
							tb_salesman,
							tb_commgroup							
						WHERE 
							tb_salesman.salescode = tb_invoice.salescode 
						
						AND 
							tb_invoice.commgroupcode = tb_commgroup.commgroupcode 
						AND 
							invoiceno='".$sid."'");		
			$row = mysql_fetch_array($res);	
		$invno 		= $row['invoiceno'];	
		$total_pay 	= $row['total_pay'];
		$currency 	= $row['currency'];
		$kurs 		= $row['kurs'];
		$tax 		= $row['tax'];
		
	}
	
	$invoicedate = $row['invoicedate'];
	$totalreturn = $row['totalreturn'];
	$invtax = $row['invtax'];
	$discount = $row['discount'];
	$salesman = $row['salesname'];
	
	if ($totalreturn<>'' && $totalreturn>0) {
		$rsret = read_write("select sum(qty_return * price) as sum_return from tb_invoiceitems where invoiceno='".$sid."'");
		$rowret = mysql_fetch_array($rsret);
		$sum_return = $rowret['sum_return'];
		
		if ($discount <> "" && $discount > 0) {
			$sum_return = $sum_return - $discount*$sum_return/100;
		}
		if ($invtax<>0) {
			$ppn_ret = (int) $sum_return * 0.1;
			$sum_return = $sum_return + $ppn_ret;
		} 
		$total_pay = $total_pay - $sum_return;
	}
	if ($ttuid=='') {
		$resttu = read_write("select count(ttuid) as cnt from tb_ttu where invoiceno='".$sid."'");
		$rowttu = mysql_fetch_array($resttu);
		$num_rows = $rowttu['cnt'];
		
		if ($num_rows==0) { 
			
			$ppn_payment = $tax*$kurs;			
			$payment=$total_pay*$kurs; // its gross payment	
		}
	}
	$rsinv = read_write("select sum(payment) + sum(ppn_payment) as sum_ttu from tb_ttu where invoiceno='".$sid."'");
	$rowinv = mysql_fetch_array($rsinv);
	$sum_ttu = $rowinv['sum_ttu'];	
	$outstanding = $total_pay-$sum_ttu;
	
	
?>
<html>
<head><title>Add/Edit TSP</title>
<link rel='stylesheet' type='text/css'  href='../templates/css/style.css'>
<script language="javascript" src="cal2.js">
/*
Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
Script featured on/available at http://www.dynamicdrive.com/
This notice must stay intact for use
*/
</script>
<script language='javascript' src='cal_conf2.js'></script>
<script language="javascript">
function validate() {
	if (document.frmtsp.ttudate.value == "") {
		alert("Please fill up the Transaction Date");
		return false;
	} else if (document.frmtsp.payment.value == "") {
		alert("Please fill up the payment");
		return false;
	} else {
		return true;
	}
}
function deletettu(sid,ttuid,ttuno) {
	if (confirm("Are you sure to delete this TTU?")) {
		location.href = 'tsp.php?sid='+sid+'&ttuid='+ttuid+'&update=2&ttuno='+ttuno;
	}
}
</script>
</head>
<body>
<?php include ('../menu.php');?><br>
<form name='frmtsp' action='tsp.php?update=1&ttuid=<?php echo $ttuid; ?>&sid=<?php echo $sid; ?>' method='POST' onSubmit="return validate()" >
<input type=hidden name=commgroupcode value=<?php echo $commgroupcode;?>>
<input type=hidden name=invoicedate value='<?php echo $invoicedate;?>'>
<table border='0' cellspacing='1' cellpadding='1' width="40%" align='center'>
<tr class="small">
	<td colspan=2 ><font color=red><b><?php echo $message; ?></b></font></td>
</tr>
<tr>
	<td colspan=2 align=center><div><b class="big">Office Correction Information</b></div></td>
</tr>
<tr class="small">	
	<td align="right" >Invoice No:</td>
	<td><a href="invoice.php?sid=<?php echo $sid; ?>" class=smalllink><?php echo $sid; ?></a></td>					
</tr>
<tr class="small">
	<td align="right" >Salesman:</td>
	<td><?php echo $salesman; ?></td>					
</tr>
<?php
if($ttuno <> "") {
echo "<tr><td align='right' class='small'>TSP No:</td>
		<td><input type=text name='ttuno' class=tBox size=20 value='".$ttuno."'></td></tr>";
}?>
<tr>
	<td align="right" class="small">Correction Date:</td>
	<td class="small"><input type="text" name="ttudate" value="<?php echo $ttudate; ?>"  class="tBox" size=10 maxlength=10> &nbsp;<a href="javascript:showCal('Calendar9')" class=smalllink>Select Date</a></td>
</tr>
<tr>
	<td align="right" class="small">Correction:</td>
	<td><input type="text" name="payment" value="<?php echo number_format($strpayment,0); ?>" class="tBox" size=20 maxlength=20>
	<input type=hidden name=oldpayment value="<?php echo $strpayment; ?>">  <!-- to check in outstanding --></td>					
</tr>
<tr>
	<td colspan=2 align=center><input type=hidden name=outstanding value="<?php echo $outstanding; ?>">
	<input type="submit" value="Save" class="tBox" <?php if ($_SESSION['groups'] <> 'root' && $_SESSION['groups'] <> 'administrator' && $_SESSION['groups']<>'finance') { echo "disabled"; }?> >		
	<input type="reset" value="Clear" class="tBox">
	<?
			if(isset($HTTP_REFERER)) {
				echo "&nbsp;";
			} else { 
				//echo "<a href='javascript:history.back()'>Back I</a>";
				echo "<input type='button' value='Back' class='tBox' onclick='javascript:history.back()'>";
	}
if (($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator') && $ttuid <> '') { ?>
	<input type="button" value="Delete" class="tBox" onclick="deletettu('<?php echo $sid; ?>','<?php echo $ttuid; ?>','<?php echo $ttuno; ?>')">
<?php }  ?> </td>
</tr>
<tr><td height=10></td></tr>
<tr class=small><td align=right>Current Information:</td><td>&nbsp;</td></tr>
<tr class="small" valign=top>
	<td align="right" >Invoice Price:</td><td>
<?php 
	echo "Rp. ".number_format($kurs*$total_pay,0);	
	if ($currency<>'Rp.') {				
	echo "<BR>(USD ".number_format($total_pay,0).". 1 USD=Rp ".number_format($kurs,0).")";
 }
?></tr>
<tr class="small">
	<td align="right" >Total TTU:</td>
	<td><?php echo "Rp. ".number_format($sum_ttu,0); ?></td>					
</tr>
<tr class="small">
	<td align="right" >Outstanding:</td>
	<td>
<?php			
if ($outstanding==0) {
	echo "-";
} else {
echo "Rp. ".number_format($outstanding,0); 
} ?></td>
</tr>
</table>
</form>
</body>
</html>