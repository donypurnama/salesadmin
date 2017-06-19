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
	$l   	= $_GET['l'];
	
	if ($update == 1) {
		$ttunoman		= $_POST['ttunoman'];
		$ttuno 			= $_POST['ttuno'];
		$ttudate 		= $_POST['ttudate'];
		$strpayment 	= $_POST['payment']; // it's bruto/gross payment
		$strpayment = str_replace(",","",$strpayment);
		$strpayment = str_replace(".","",$strpayment);

		$ppn_payment 	= $_POST['ppn_payment'];
		$ppn_payment = str_replace(",","",$ppn_payment);
		$ppn_payment = str_replace(".","",$ppn_payment);

		if ($ppn_payment=='') { $ppn_payment=0; }
		$payment = $strpayment - $ppn_payment;
		$outstanding = $_POST['outstanding'];
		
		if ($ttuid<>'') {
			$oldpayment = $_POST['oldpayment'];
			$outstanding = $outstanding + $oldpayment;
		}
		
		
		if ($payment >= 0 && ($outstanding-$payment)>=0) {

			$lpudate 	= $_POST['lpudate'];
			$depositdate 	= $_POST['depositdate'];
			$commgroupcode 	= $_POST['commgroupcode'];

			$commdate 	= $_POST['commdate']; //this is commdate
			$yrls = date("Y",strtotime($commdate));
			$mthls = date("n",strtotime($commdate));
			$ddls = date("j",strtotime($commdate));
			$ts_commdate = mktime(0,0,0,$mthls,$ddls,$yrls);
			
			$yrls = date("Y",strtotime($ttudate));
			$mthls = date("n",strtotime($ttudate));
			$ddls = date("j",strtotime($ttudate));
			$ts_ttudate = mktime(0,0,0,$mthls,$ddls,$yrls);
			$datediff = ((($ts_ttudate - $ts_commdate) / 60) / 60) / 24;		

			$res = read_write("select 
								percent_comm 
							from 
								tb_commrules 
							where 
								commgroupcode='".$commgroupcode."' 
							and 
								(".$datediff. "-startdate)>=0 
							and 
								(enddate-".$datediff.")>=0");
			if (mysql_num_rows($res) > 0) {
				$rspc = mysql_fetch_array($res);
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

				$message = "Database has been successfully updated ...";
			} else {	
			
				if ($l==1) {
					$rstemp = read_write("SELECT replace(ttuno, 'TU".$branch.date("ym")."-','') as nbr FROM tb_ttu order by ttuno desc limit 1");
					$rowtemp = mysql_fetch_array($rstemp);
					$ttutemp = (int) $rowtemp['nbr'];
					$ttutemp++;
					if (strlen($ttutemp)==1) {
						$ttutemp = '00'.$ttutemp;
					} elseif (strlen($ttutemp)==2) {
						$ttutemp = '0'.$ttutemp;
					}
					
					$y = substr($ttudate, 2, 2);
					$m = substr($ttudate, 5, 2);
					$dateTTU = $y.$m;
					$ttuno = "TU".$branch.$dateTTU."-".$ttutemp;				
					$res = read_write("insert into tb_ttu (
										invoiceno, 
										ttuno, 
										ttudate, 
										lpudate, 
										depositdate,
										payment, 
										ppn_payment, 
										commission, 
										percent_comm, 
										percent_trainer, 
										comm_trainer, 
										percent_ds, 
										comm_ds,
										last_update,
										usercode,
										sentstatus) 
									values (
										'".$sid."',
										'".$ttuno."',
										'".$ttudate."',
										'".$lpudate."',
										'".$depositdate."',
										".$payment.","
										.$ppn_payment.",
										".$commission.",
										".$percent_comm.",
										".$percent_trainer.",
										".$comm_trainer.",
										".$percent_ds.",
										".$comm_ds.",
										'".$today."',
										'".$_SESSION['user']."',
										0
										)");
					
					$message =  "Database has been successfully inserted ...";
				} else {
					if($ttunoman <> '') //TTUNO TERPILIH MANUAL
					{ //CHECK NOMOR TTU YG SAMA
						$res = read_write("SELECT count(ttuno) as ttuman FROM tb_ttu where ttuno='".$ttunoman."'");
						$jum = mysql_fetch_row($res);
						$ttuno = $ttunoman;
						if($jum[0] == 0) 
						{
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
								$message =  "New TTU has been successfully inserted ...";
						} 
						else 
						{
							$message =  "Sorry ........ TTU Number already exists"; 
							$ttuno = "";
						}
					} 
					else 
					{ //AUTO NUMBERIN FOR ADD TTU  //ADD TTU BARU
					
						$rstemp = read_write("select 
										max(substring(ttuno, length(ttuno)-2)) as nbr 
									from 
										tb_ttu 
									where
										year(ttudate)=".date('Y')." 
									and 
										month(ttudate)=".date('n')." 
									and 
										ttuno like 'TU".$branch."%'");
						$rowtemp = mysql_fetch_array($rstemp);
						$ttutemp = (int) $rowtemp['nbr'];
						$ttutemp++;
						if (strlen($ttutemp)==1) {
							$ttutemp = '00'.$ttutemp;
						} elseif (strlen($ttutemp)==2) {
							$ttutemp = '0'.$ttutemp;
						}
							$ttuno = "TU".$branch.date("ym")."-".$ttutemp;			
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
								$message =  "Database has been successfully inserted ...";
					
					}	
				
				}
			}
		} else { //if payment > 0 pembayaran lbh besar dr ppn jk ada ppn / outstanding minus
			if ($outstanding-$payment<0) {
				$message = "Pembayaran tidak boleh lebih besar dari outstanding";
			} else {
				$message = "Pembayaran harus lebih besar dari PPn";
			}
		}
	} elseif ($update==2) {
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
								totalsalesusd,
								kurs, 
								currency, 
								invtax, 
								discount, 
								totalreturn, ppnreturn,
								tb_invoice.commgroupcode, 
								invoicedate, tb_invoice.commdate,
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
			$lpudate = $row['lpudate'];
			$depositdate = $row['depositdate'];
			$total_pay = $row['total_pay'];
			$currency = $row['currency'];
			$kurs = $row['kurs'];
			$totalsalesusd = $row['totalsalesusd'];
		}
	} else {
		$res = read_write("SELECT 
								tb_invoice.invoiceno, 
								tax, 
								tax+totalsales as total_pay, totalsalesusd,
								kurs, 
								currency, 
								invtax, 
								discount, 
								totalreturn, ppnreturn,
								tb_invoice.commgroupcode, 
								invoicedate,  tb_invoice.commdate,
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
		$totalsalesusd 	= $row['totalsalesusd'];
		 
		
	}
	
	$commgroupcode = $row['commgroupcode'];
	$invoicedate = $row['invoicedate'];
	$commdate = $row['commdate'];
	$totalreturn = $row['totalreturn'];
	$ppnreturn = $row['ppnreturn'];
	$invtax = $row['invtax'];
	$discount = $row['discount'];
	$salesman = $row['salesname'];

	$trainerid = $row['trainer'];
	$dsid = $row['district_supervisor'];
	
	if ($trainerid <> "") {
		$rssales = read_write("select salesname from tb_salesman where salescode='".$trainerid."'");
		$rwsales = mysql_fetch_array($rssales);
		$trainer = $rwsales['salesname'];
	} else { $trainer = "-"; }

	if ($dsid <> "") {	
		$rssales = read_write("select salesname from tb_salesman where salescode='".$dsid."'");
		$rwsales = mysql_fetch_array($rssales);
		$district_supervisor = $rwsales['salesname'];
	} else { $district_supervisor = "-"; }

	
	$divisionid = $row['divisionid'];
	if ($commgroupcode <>"") {
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

	if ($totalreturn<>'' && $totalreturn>0) {
		
		$sum_return = $totalreturn + $ppnreturn;
		if ($discount <> "" && $discount > 0) {
			$sum_return = $sum_return - $discount*$sum_return/100;
		}
	
		$total_pay = $total_pay - $sum_return;
	}
	if ($ttuid=='') {
		$resttu = read_write("select count(ttuid) as cnt from tb_ttu where invoiceno='".$sid."'");
		$rowttu = mysql_fetch_array($resttu);
		$num_rows = $rowttu['cnt'];
		
		if ($num_rows==0) { 
			
			$ppn_payment = $tax;			
			$payment=$total_pay; // its gross payment	
		}
	}
	$rsinv = read_write("select sum(payment) + sum(ppn_payment) as sum_ttu from tb_ttu where invoiceno='".$sid."'");
	$rowinv = mysql_fetch_array($rsinv);
	$sum_ttu = $rowinv['sum_ttu'];
	
	$outstanding = $total_pay-$sum_ttu;
									
?>

<html>
	<head>
	<title>Add/Edit TTU</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language="javascript" src="cal2.js">
			/*
			Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
			Script featured on/available at http://www.dynamicdrive.com/
			This notice must stay intact for use
			*/
		</script>
		<script language="javascript" src="cal_conf2.js"></script>
		<script language="javascript">
			function validate()
			{
				 if (document.frmttu.ttudate.value == "")
				{
					alert("Please fill up the Transaction Date");
					return false;
				}
				else if (document.frmttu.payment.value == "")
				{
					alert("Please fill up the payment");
					return false;
				}
				else
				{
					return true;
				}
			}

			function deletettu(sid,ttuid,ttuno)
			{
				if (confirm("Are you sure to delete this TTU?")) {
					location.href = 'ttu.php?sid='+sid+'&ttuid='+ttuid+'&update=2&ttuno='+ttuno;
				}
			}
			
	<?php
		if ($ttuno == "") { ?>
			function cekpilih(status)
			{
				a=status.value;
				if(a==1)
				{	
					awal();
				}
				else if(a==2)
				{
					document.getElementById('ttuno').disabled = false;	
					
				}
			}

			
			function awal()
			{
				if (document.getElementById('ttuno').value !== "") { 
					document.getElementById('ttuno').disabled = false;
				} else {
					document.getElementById('ttuno').disabled = true;
					document.getElementById('ttuno').value='';
				}
			   
			}
	<?php } ?>
</script>
</head>


<BODY <?php	if ($ttuno == "") { ?>onload="awal();" <?php } ?>>
<?php include('../menu.php'); ?><br>
<form name="frmttu" action="ttu.php?update=1&ttuid=<?php echo $ttuid; ?>&l=<?php echo $l;?>&sid=<?php echo $sid;?>" method="POST" onSubmit="return validate()" >
<input type=hidden name=commgroupcode value=<?php echo $commgroupcode;?>>
<input type=hidden name=commdate value='<?php echo $commdate;?>'> <!-- not invoice date, because of delivery distance -->
<table border=0 cellspacing=1 cellpadding=1 width="40%" align=center>
	<tr class="small">
		<td colspan=2 ><font color=red><b><?php echo $message; ?></b></font></td>
	</tr>
	<tr>
		<td colspan=2 align=center><div><b class="big">TTU Information</b></div></td>
		</tr>
		<tr class="small">
			<td align="right" >Invoice No:</td>
			<td><a href="invoice.php?sid=<?php echo $sid; ?>" class=smalllink><?php echo $sid; ?></a></td>					
		</tr>
		<tr class="small">
			<td align="right" >Salesman:</td>
			<td><?php echo $salesman; ?></td>					
		</tr>
		<tr class="small">
			<td align="right" >Trainer:</td>
			<td><?php echo $trainer; ?></td>					
		</tr>
		<tr class="small">
			<td align="right" >District Supervisor:</td>
			<td><?php echo $district_supervisor; ?></td>					
		</tr>
		<tr class='small'>
			<td align='right'>Invoice (Commission) Date:</td>
			<td><?php echo $commdate;?></td>
		</tr>
		<tr valign=top class="small">
			<td align="right" >Commission Type:</td>
			<td><?php echo $commstr; ?></td>					
		</tr>
		<?php
		if($ttuno <> "") {
			echo "<tr><td align='right' class='small'>TTU No:</td>
					<td><input type=text name='ttuno' class=tBox size=20 value='".$ttuno."'></td></tr>";
			} else {
				echo "<tr><td align='right' class='small'>TTU No:</td>
				<td class='small'>
					<input type='radio' name='rdttuno' id='rdttuno' value='1' onclick='cekpilih(this);' checked>Auto Generate<br>    
					<input type='radio' name='rdttuno' id='rdttuno' value='2' onclick='cekpilih(this);'>Manual <br>
				<input type=text name='ttunoman' id='ttuno' class='tBox' size=20 value='".$ttuno."'></td>
				</tr>";
			}
		?>
		
		
		<tr>
			<td align="right" class="small">Commission Date:</td>
			<td class="small"><input type="text" name="ttudate" value="<?php echo $ttudate; ?>"  class="tBox" size=10 maxlength=10> 
				<a href="javascript:showCal('Calendar3')" class=smalllink>Select Date</a></td>
		</tr>
		<tr>
			<td align="right" class="small">LPU Date:</td>
			<td class="small"><input type="text" name="lpudate" value="<?php echo $lpudate; ?>"  class="tBox" size=10 maxlength=10> 
			<a href="javascript:showCal('Calendar4')" class=smalllink>Select Date</a></td>
		</tr>
		<tr>
			<td align="right" class="small">Deposit Date:</td>
			<td class="small"><input type="text" name="depositdate" value="<?php echo $depositdate; ?>"  class="tBox" size=10 maxlength=10> 
			<a href="javascript:showCal('Calendar8')" class=smalllink>Select Date</a></td>
		</tr>
		<?php if ($num_rows == 0 || ($ppn_payment<>'' && $ppn_payment > '0')) { 
				if ($invtax=='2' || $invtax=='3') { $ppn_payment=''; }
		?>
		<tr class=small>
			<td align="right">*) PPN:</td>
			<td><?php echo number_format($ppn_payment,0); ?><input type="hidden" name="ppn_payment" value="<?php echo $ppn_payment; ?>"></td>					
		</tr>
		<?php } ?>
		<tr>
			<td align="right" class="small">Payment (Gross):</td>
			<td><input type="text" name="payment" value="<?php echo number_format($strpayment,0); ?>" class="tBox" size=20 maxlength=20>
			<input type=hidden name=oldpayment value="<?php echo $strpayment; ?>"> <!-- to check in outstanding -->
			</td>					
		</tr>
		<tr>
			<td colspan=2 align=center>
			<input type=hidden name=outstanding value="<?php echo $outstanding; ?>">
			<input type="submit" value="Save" class="tBox" <?php if ($_SESSION['groups'] <> 'root' && $_SESSION['groups'] <> 'administrator' && $_SESSION['groups']<>'finance') { echo "disabled"; }?> >		
			<input type="reset" value="Clear" class="tBox">
			<?php
			if (($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator') && $ttuid <> '') { ?>
			<input type="button" value="Delete" class="tBox" onclick="deletettu('<?php echo $sid; ?>','<?php echo $ttuid; ?>','<?php echo $ttuno; ?>')">
			<?php } ?>
		
			<?
			if(isset($HTTP_REFERER)) {
				echo "&nbsp;";
			} else { 
				//echo "<a href='javascript:history.back()'>Back I</a>";
				echo "<input type='button' value='Back' class='tBox' onclick='javascript:history.back(1)'>";
			}?>
			
			
			
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr class=small><td align=right>Current Information:</td><td>&nbsp;</td></tr>
		<tr class="small" valign=top>
			<td align="right" >Invoice Price:</td><td>
		<?php 
			echo "Rp. ".number_format($total_pay,0);	
			if ($currency<>'Rp.') {				
				echo "<BR>(USD ".number_format($totalsalesusd,0).". 1 USD=Rp ".number_format($kurs,0).")";
				  }
		?>
		</tr>
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
		echo "Rp. ".number_format($outstanding,0); }
			
		?></td>
		</tr>
	</table>
</form>
	</body>
</html>
	