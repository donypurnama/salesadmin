<?php include('../constant.php'); ?>
<?php include('../database.php');?>

<?php
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	
	$x 		= $_GET['x'];
	$search = $_GET['search'];
	$ver 	= $_GET['ver'];
	
	if ($x == 1) {
		if ($_POST['periodfrom'] == '' && $_POST['periodto']=='') {
			$x = '';
			$search = '';
		} else {
			$_SESSION['periodfrom'] = $_POST['periodfrom'];
			$_SESSION['periodto'] 	= $_POST['periodto'];
			$_SESSION['divisionid'] = $_POST['divisionid'];
			$_SESSION['salesman'] 	= $_POST['salesman'];
			$_SESSION['ver']		= $_GET['ver'];
		}
	}
	
	
?>
<html>
	<head>
	<title>Commission</title>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language="javascript" src="cal2.js">
			/*
			Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
			Script featured on/available at http://www.dynamicdrive.com/
			This notice must stay intact for use
			*/
		</script>
		<script language="javascript" src="cal_conf2.js"></script>
		<script language='javascript'>
			function validate() {
				with (document.forms[0]) {
					if (periodfrom.value=='' && periodto.value=='') {
						alert('Please fill period');
						return false;
					}
				}
				return true;
			}
			function salescommdetails() {	
				var val=validate();
				
				if (val) {
					if (document.forms[0].salesman.value !== '') {
						document.forms[0].action = 'commission.php?x=1&search=1&ver=1';
						document.forms[0].submit();
					} else {
						alert('Please fill salesman');
					}
				}
			}
			
			function overridingreport(pos) {	
				var val=validate();
				
				if (val) {
					document.forms[0].action = 'commission.php?x=1&search=1&ver=2&pos=' + pos;
					document.forms[0].submit();
				}
			}
			
			
			
	</script>
</head>
<body class=land >
<div class=noprint>
<?php include('../menu.php');	?>
<br><br>
<form method="POST" name=frmcommission action="commission.php?x=1&search=1&ver=0" onsubmit="return validate()">
	<table border="0" cellspacing="0" cellpadding="0" bgcolor=#e5ebf9 class=header width="53%" align="center">
	<tr >
	<td width="40%"><div class="header icon-48-trash">Commission</div></td>
	<tr><td colspan="2">
		<table border="0" cellspacing="1" cellpadding="1" bgcolor="white" width="100%">
		<tr>
			<td class="small"><a href="javascript:showCal('Calendar6')" class=smalllink>Periode Date From:</a></td>
			<td class="small"><a href="javascript:showCal('Calendar7')" class=smalllink>Periode Date To:</a></td>
			<td class="small">Division: </td>
			<td class="small">Salesman: </td>
		</tr>
		<tr>
					<td><input type=text name=periodfrom class="tBox" size=10 style="width:120px;" value='<?php echo $_SESSION['periodfrom'];?>'> </td>
					<td><input type=text name=periodto class="tBox" size=10 style="width:120px;" value='<?php echo $_SESSION['periodto'];?>'></td>
					<td><select name=divisionid class="tBox" style="width:165px;">
					<option value=''>
					<?php
						$rsdiv = read_write("select * from tb_division where divisioninv is not null order by divisionid asc");
						while ($rwdiv = mysql_fetch_array($rsdiv)) {					
							echo "<option value=".$rwdiv['divisionid'];
							if ($_SESSION['divisionid']==$rwdiv['divisionid']) { echo " selected"; }
							echo ">".$rwdiv['divisionname'];
						}
						echo "<option value='F,Q' ";
						if ($_SESSION['divisionid']=="F,Q") { echo " selected"; }
						echo ">Fire (Fire & Service)";

						echo "<option value='C,E,O,N' ";
						if ($_SESSION['divisionid']=="C,E,O,N") { echo " selected"; }
						echo ">Welding (Machine & Magna)";
					?></select>
				</td>
				<td><select name=salesman class="tBox" style="width:170px;">
				<option value="">--
			<?php
				$rsales = read_write("select salescode,salesname from tb_salesman where active=1 order by salesname asc");
				
				while ($rowsales=mysql_fetch_array($rsales)) {
					echo "<option value='".$rowsales['salescode']."'";
					if ($_SESSION['salesman']==$rowsales['salescode']) { echo " selected"; }
					echo ">".$rowsales['salesname'];
				}?>
				</select></td>
				</tr>
			
			
				<tr><td height=10></td></tr>
		
			<tr><td colspan=8 align=center><input type=submit class="tBox" value='Summary Report'> &nbsp;
			<input type=button class="tBox" value='Salesman Details' onclick='javascript:salescommdetails()'> &nbsp;
			<input type=button class="tBox" value='Overriding Trainer' onclick='javascript:overridingreport("2")'> &nbsp;
			<input type=button class="tBox" value='Overriding Ds' onclick='javascript:overridingreport("3")'><br><br>
			</tr>
			</table>
		</td></tr>	
	</table>	
</form>
<?php
	if ($search == 1) {
		$strfrom = "tb_ttu, tb_invoice";
		if ($_SESSION['ver']==1) {
			$strfrom = $strfrom.", tb_deliveryaddr, tb_company, tb_buyer";
		} else {
			$strfrom = $strfrom.", tb_salesman";
		}
		$sqlwhere = "tb_ttu.invoiceno = tb_invoice.invoiceno and (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."')))";
		
		if ($_SESSION['ver']==1) {
			$sqlwhere = $sqlwhere." and tb_invoice.buyercode	= tb_buyer.buyercode 
									and tb_buyer.deliverycode	= tb_deliveryaddr.deliverycode 
									and tb_deliveryaddr.companycode	= tb_company.companycode";
		} else {
			$sqlwhere = $sqlwhere." and tb_invoice.salescode=tb_salesman.salescode "; // and tb_salesman.active=1. this is commented because to view last commission data
		}

		$strfromor = "tb_ttu, tb_invoice, tb_salesman";
		$sqlwhereor = "tb_salesman.salescode = tb_invoice.salescode and tb_ttu.invoiceno=tb_invoice.invoiceno and (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."')))";
		if ($_SESSION['periodfrom']<>'') {
			$sqlwhere = $sqlwhere." and lpudate >='".$_SESSION['periodfrom']."' and lpudate<='".$_SESSION['periodto']."'";

			$sqlwhereor = $sqlwhereor." and lpudate >='".$_SESSION['periodfrom']."' and lpudate<='".$_SESSION['periodto']."'";
		}

		if ($_SESSION['divisionid']<>'') {
			$sqlwhere = $sqlwhere." and tb_commgroup.commgroupcode=tb_invoice.commgroupcode and ("; 
			$strfrom = $strfrom.", tb_commgroup";

			$sqlwhereor = $sqlwhereor." and tb_commgroup.commgroupcode=tb_invoice.commgroupcode and ("; 
			$strfromor = $strfromor.", tb_commgroup";

			$arrdiv = explode(",",$_SESSION['divisionid']);
			$n = 0;
			foreach ($arrdiv as $div_id) {
				if ($n==0) {
					$sqlwhere = $sqlwhere."tb_commgroup.divisionid='".$div_id."'";
					$sqlwhereor = $sqlwhereor."tb_commgroup.divisionid='".$div_id."'";
				} else {
					$sqlwhere = $sqlwhere." or tb_commgroup.divisionid='".$div_id."'";
					$sqlwhereor = $sqlwhereor." or tb_commgroup.divisionid='".$div_id."'";
				}	
				$n++;
			}
			$sqlwhere = $sqlwhere.")";
			$sqlwhereor = $sqlwhereor.")";

		}

		if ($_SESSION['salesman']<>'') {
			$sqlwhere = $sqlwhere." and tb_invoice.salescode='".$_SESSION['salesman']."'";			
		}
		
		
		if ($_SESSION['ver'] == 0) {
			$strsql = "select 
							salesname, 
							tb_invoice.salescode, 
							position, 
							tb_invoice.trainer, 
							tb_invoice.district_supervisor, 
							sum(ppn_payment+payment) 		as collect_bruto, 
						sum(payment) 						as collect_net, 
						sum(commission) 					as total_comm from ".$strfrom." 
						where 
							".$sqlwhere." 
						
						group by 
							tb_invoice.salescode "; //and 	commission > 0 having total_comm > 0
				//echo $strsql."<br>";
			
		} elseif ($_SESSION['ver']==1) {
			$strsql = "select 
							tb_buyer.deliverycode, 
							companyname, 
							personname, 
							tb_invoice.invoiceno, 
							ttuno, 
							invoicedate, 
							ttudate, 
							to_days(ttudate)-to_days(invoicedate) 	as datediff, 
							ppn_payment + payment 		as total_pay, 
							payment, 
							tb_ttu.percent_comm, 
							commission, 
							ttuid, 
							tb_ttu.invoiceno 
						from 
							".$strfrom." 
						where 
							".$sqlwhere." 
						order by ttudate"; //and commission >= 0 
						
		} elseif ($_SESSION['ver']==2) {
			$_SESSION['sqloverriding_ds'] = "select distinct tb_invoice.district_supervisor 
							 from ".$strfrom." 
						where 
							".$sqlwhere." and tb_invoice.salescode like '".$branch.".%' and tb_invoice.district_supervisor like '".$branch.".%' and tb_invoice.district_supervisor<>'".$branch.".0000'";
			$_SESSION['sqloverriding_tn'] = "select distinct tb_invoice.trainer 
							 from ".$strfrom." 
						where 
							".$sqlwhere." and tb_invoice.salescode like '".$branch.".%' and tb_invoice.trainer like '".$branch.".%' and tb_invoice.trainer<>'".$branch.".0000'";
			
			$_SESSION['sqlfrom'] = $strfrom;
			$_SESSION['sqlwhere'] = $sqlwhere;
		}
		
		//echo $strsql;
		//for export to excel
		$_SESSION['sqlcomm'] = $strsql; 
		$_SESSION['strsqlor'] = $strfromor;
		$_SESSION['strsqlwhereor'] = $sqlwhereor;
		
		$res = read_write($strsql);
		
		$sum_bruto = 0;
		$sum_net = 0;
		$sum_comm = 0;
		
		if ($_SESSION['ver'] == 0) {
			$_SESSION['commhdr'] = "<font class=big>PT. Gapura Raya<BR>Rekapitulasi Komisi dan Overriding Sales (".$arr_branch[$branch].")</font>";
		} elseif ($_SESSION['ver']==1) {
			$_SESSION['commhdr'] = "<font class=big>PT. Gapura Raya<BR>Daftar Komisi dan Overriding Sales (".$arr_branch[$branch].")</font>";
		} elseif ($_SESSION['ver']==2) {
			$_SESSION['commhdr'] = "<font class=big>PT. Gapura Raya<BR>Rekapitulasi Komisi Overriding Sales (".$arr_branch[$branch].")</font>";
		}
		echo "<font class=smallmed>";
		if ($_SESSION['divisionid']<>'') {
			if (strpos($_SESSION['divisionid'],",") == false) {
				$rsdv = read_write("select divisionname from tb_division where divisionid='".$_SESSION['divisionid']."'");
				$rwdv = mysql_fetch_array($rsdv);
				$divname = $rwdv['divisionname'];
			} elseif ($_SESSION['divisionid']=='C,E,O,N') {
				$divname = "Welding";
			} elseif ($_SESSION['divisionid']=='F,Q') {
				$divname = "Fire";
			}
			$_SESSION['commhdr'] .= "<br>Divisi: ".$divname;
		}
		if ($_SESSION['periodfrom']<>'') {
			$_SESSION['commhdr'] .= "<br>Periode: ".date("j M Y", strtotime($_SESSION['periodfrom']))." to ".date("j M Y", strtotime($_SESSION['periodto']));
		}
		if ($_SESSION['salesman']<>'') {
			$rssl = read_write("select salesname from tb_salesman where salescode='".$_SESSION['salesman']."'");
			$rwsl = mysql_fetch_array($rssl);
			$_SESSION['commhdr'] .= "<br>Salesman: ".$rwsl['salesname'];
		}
		$_SESSION['commhdr'] .= "</font>";
		
		if ($_SESSION['ver']==0) {
			
			$commstr = "<table border=0 cellspacing=0 cellpadding=3 width=95% align=center>";
			$commstr .= "<tr bgcolor=#e5ebf9 class=header><td style='border-left:solid thin;border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Salesman</b></td>";
			$commstr .= "<td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Collect Bruto</b></td><td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Collect Net</b></td>";
			$commstr .= "<td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Commission</b></td>";
			$commstr .= "<td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Overriding</b></td>";
			$commstr .= "<td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin' ><b>Total</b></td>";
			$commstr .= "</tr>";
			$i = 0;
			$j = 0;
			$k = 0;
			while ($row = mysql_fetch_array($res)) {
				$commstr .= "<tr class=replaceclass>";
				$commstr .= "<td>".$row['salesname']."</td>";
				$commstr .= "<td align=right>".number_format($row['collect_bruto'],0)." &nbsp;</td>";
				$commstr .= "<td align=right>".number_format($row['collect_net'],0)." &nbsp;</td>";
				$commstr .= "<td align=right>".number_format($row['total_comm'],0)." &nbsp;</td>";
				
				$comm_or = calcoverriding($row['salescode'], $constant_salespos[$row['position']], $strfromor, $sqlwhereor);

				$commstr .= "<td align=right>".number_format($comm_or,0)." &nbsp;</td>";
				$commstr .= "<td align=right>".number_format($row['total_comm'] + $comm_or,0)." &nbsp;</td>";
				$commstr .= "</tr>";
				$sum_bruto = $sum_bruto + $row['collect_bruto'];
				$sum_net = $sum_net + $row['collect_net'];
				$sum_comm = $sum_comm + $row['total_comm'];
				$sum_or = $sum_or + $comm_or;
				
				if ($_SESSION['salesman']=='' && $_SESSION['divisionid']=='') {
					//input salescode, trainerid, dsid to array. check unvailable collection but existing overd.
					$arrsalesid[$i] = $row['salescode'];
					
					if ($row['trainer'] <> ""  && $row['trainer'] <> $branch.'.0000' && $row['trainer'] <> "0") {
						
						if ($j==0) {
							$arrtrainer[$j] = $row['trainer'];
							$j++;
						} else {
							if (!in_array($row['trainer'], $arrtrainer)) {
								$arrtrainer[$j] = $row['trainer'];
								$j++;
							}
						}
						
					}
					if ($row['district_supervisor'] <> "" && $row['district_supervisor'] <> $branch.'.0000' && $row['district_supervisor'] <> "0") {
						if ($k==0) {
							$arrds[$k] = $row['district_supervisor'];
							$k++;
						} else {
							if (!in_array($row['district_supervisor'], $arrds)) {
								$arrds[$k] = $row['district_supervisor'];
								$k++;
							}
						}
						
					}
				}
				$i++;
			}

			
			if ($j>0) {
				for ($i=0;$i<count($arrtrainer);$i++) {
					if (!in_array($arrtrainer[$i],$arrsalesid)) {
						$comm_or = calcoverriding($arrtrainer[$i], 'Trainer', $strfromor, $sqlwhereor);
						$qsls = read_write("select salesname from tb_salesman where salescode='".$arrtrainer[$i]."'");
						
						$rssls = mysql_fetch_array($qsls);
						$salesname = $rssls['salesname'];
						$commstr .= "<tr class=replaceclass>";
						$commstr .= "<td>".$salesname."</td>";
						$commstr .= "<td align=right>0 &nbsp;</td>";
						$commstr .= "<td align=right>0 &nbsp;</td>";
						$commstr .= "<td align=right>0 &nbsp;</td>";
						$commstr .= "<td align=right>".number_format($comm_or,0)." &nbsp;</td>";
						$commstr .= "<td align=right>".number_format($comm_or,0)." &nbsp;</td>";
						$commstr .= "</tr>";
						$sum_or = $sum_or + $comm_or;
					}
				}
			}
			
			if ($k>0) {
				for ($i=0;$i<count($arrds);$i++) {
					if (!in_array($arrds[$i],$arrsalesid)) {
						
						$comm_or = calcoverriding($arrds[$i], 'District Supervisor', $strfromor, $sqlwhereor);
						$qsls = read_write("select salesname from tb_salesman where salescode='".$arrds[$i]."'");
						
						$rssls = mysql_fetch_array($qsls);
						$salesname = $rssls['salesname'];
						$commstr .= "<tr class=replaceclass>";
						$commstr .= "<td>".$salesname."</td>";
						$commstr .= "<td align=right>0 &nbsp;</td>";
						$commstr .= "<td align=right>0 &nbsp;</td>";
						$commstr .= "<td align=right>0 &nbsp;</td>";
						$commstr .= "<td align=right>".number_format($comm_or,0)." &nbsp;</td>";
						$commstr .= "<td align=right>".number_format($comm_or,0)." &nbsp;</td>";
						$commstr .= "</tr>";
						$sum_or = $sum_or + $comm_or;
					}
				}
			}
			
			$commstr .= "<tr><td colspan=6><hr></td></tr>";
			$commstr .= "<tr class=replaceclass><td align=right><b>Total:</b> &nbsp;</td>";
			$commstr .= "<td align=right>".number_format($sum_bruto,0)." &nbsp;</td>"; 
			$commstr .= "<td align=right>".number_format($sum_net,0)." &nbsp;</td>"; 
			$commstr .= "<td align=right>".number_format($sum_comm,0)." &nbsp;</td>"; 
			$commstr .= "<td align=right>".number_format($sum_or,0)." &nbsp;</td>";
			$commstr .= "<td align=right>".number_format($sum_comm + $sum_or,0)." &nbsp;</td>";
			$commstr .= "</table>";
		} elseif ($_SESSION['ver']==1) {
			$commstr = "<table border=0 cellspacing=0 cellpadding=3 align=center width=95%>";
			$commstr .= "<tr bgcolor=#e5ebf9 class=header><td style='border-left:solid thin;border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Customer</b></td>";
			$commstr .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Invoice</b></td><td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>TTU</b></td><td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Inv Date</b></td>";
			$commstr .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>TTU Date</b></td><td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Days</b></td><td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Collect Bruto</b></td>";
			$commstr .= "<td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Collect Net</b></td><td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Comm (%)</b></td><td align=center style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Commission</b></td></tr>";
			
			while ($row = mysql_fetch_array($res)) {
				$commstr .= "<tr class=replaceclass>";
				if ($row['deliverycode'] <> $branch.".0000000") {
					$commstr .= "<td>".$row['companyname']."</td>";
				} else {
					$commstr .= "<td>".$row['personname']."</td>";
				}
				$commstr .= "<td><a href='invoice.php?sid=".$row['invoiceno']."' class=smalllink>".$row['invoiceno']."</a></td>";
				$commstr .= "<td><a href='ttu.php?ttuid=".$row['ttuid']."&sid=".$row['invoiceno']."' class=smalllink>".$row['ttuno']."</a></td>";
				$commstr .= "<td>".date("d-m-Y",strtotime($row['invoicedate']))."</td>";
				$commstr .= "<td>".date("d-m-Y",strtotime($row['ttudate']))."</td>";
				$commstr .= "<td>".$row['datediff']."</td>";
				$commstr .= "<td align=right>".number_format($row['total_pay'],0)." &nbsp;</td>";
				$commstr .= "<td align=right>".number_format($row['payment'],0)." &nbsp;</td>";
				$commstr .= "<td align=right>".$row['percent_comm']." &nbsp;</td>";
				$commstr .= "<td align=right>".number_format($row['commission'],0)." &nbsp;</td>";
				$commstr .= "</tr>";
				$sum_bruto = $sum_bruto + $row['total_pay'];
				$sum_net = $sum_net + $row['payment'];
				$sum_comm = $sum_comm + $row['commission'];
			}
			$commstr .= "<tr><td colspan=10><hr></td></tr>";
			$commstr .= "<tr class=replaceclass><td colspan=6 align=right><b>Total:</b> &nbsp;</td>";
			$commstr .= "<td align=right>".number_format($sum_bruto,0)." &nbsp;</td>";
			$commstr .= "<td align=right>".number_format($sum_net,0)." &nbsp;</td>";
			$commstr .= "<td>&nbsp;</td>";
			$commstr .= "<td align=right>".number_format($sum_comm,0)." &nbsp;</td></tr>";
			
			$commstr .= "<tr class=replaceclass><td colspan=6 align=right><b>Overriding:</b> &nbsp;</td>";
			$qsls = read_write("select position from tb_salesman where salescode='".$_SESSION['salesman']."'");
			$rssls = mysql_fetch_array($qsls);
			
			$comm_or = calcoverriding($_SESSION['salesman'], $constant_salespos[$rssls['position']], $strfromor, $sqlwhereor);
			$commstr .= "<td align=right colspan=4>".number_format($comm_or,0)." &nbsp;</td></tr>";
			$commstr .= "<tr class=replaceclass><td colspan=6 align=right><b>Commission & Overriding:</b> &nbsp;</td>";
			$commstr .= "<td align=right colspan=4>".number_format($sum_comm + $comm_or,0)." &nbsp;</td></tr>";
			$commstr .= "</table>";
		} else {
			include('overriding.php');
			
		}
		$_SESSION['commstr'] = $commstr;
		echo str_replace('replaceclass','small',$_SESSION['commstr']);
		echo "<br><center>";
		if ($ds == '' and $t =='') {
		echo "<input type=button value='Export To Excel' class=tBox onclick='javascript:window.frmexcel.location.href=\"savetoexcel.php\"'> &nbsp;";
		echo "<input type=button value='Print' class=tBox onclick='javascript:window.print()' style='width:100px'>";
		}
		elseif ($ds and $t =='' ) 
		{
		echo "<input type=button class='tBox' value='Back To Overriding Ds' onclick='javascript:overridingreport(3)'>";
		echo " &nbsp;<input type=button value='Re-Calc Ds' class=tBox onclick='javascript:location.href=\"or_calc.php?code=$ds&pos=3&tn=1\"' style='width:140px'>";
		}
		elseif ($ds=='' and $t ) 
		{
		echo "<input type=button class='tBox' value='Back To Overriding T ' onclick='javascript:overridingreport(2)'>";
		echo " &nbsp;<input type=button value='Re-Calc T' class=tBox onclick='javascript:location.href=\"or_calc.php?code=$t&pos=2&tn=1\"' style='width:140px'>";
		}
		if ($_SESSION['ver'] == '2' and $pos == '2' and $t =='') {
		echo " &nbsp;<input type=button value='Re-Calc All Trainer' class=tBox onclick='javascript:location.href=\"or_calc.php?pos=2\"' style='width:140px'>";
		}
		else if ($_SESSION['ver'] == '2'  and $pos == '3' and $ds =='') {
		echo " &nbsp;<input type=button value='Re-Calc All Ds' class=tBox onclick='javascript:location.href=\"or_calc.php?pos=3\"' style='width:140px'>";
		}
		
	
		

		if ($_SESSION['ver'] == '1') {
			echo "<br><br><font class=verysmalllink>*) Print: Page Setup -> 'Landscape'</font>";
		}
		echo "</center>";
		echo "<iframe id=frmexcel name=frmexcel style='visibility:hidden;width:10px;height:10px'></iframe>";
		
		//echo "<input type=button value='Export To Excel Open' class=forms onclick='window.open(\"savetoexcel.php\")'>";
	}
?>
</div>

<div class=noscr>
<?php
echo "<center>".$_SESSION['commhdr']."</center>";
echo str_replace('replaceclass','small',$_SESSION['commstr']);

echo "<table width=95% align=center>";
echo "<tr><td height=10></td></tr><tr class=smallmed><td >Dibuat Oleh, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Disetujui Oleh,</td></tr></table>";
?>
</div>
</body></html>
<?php
	function calcoverriding($salescode, $position, $strfromor, $sqlwhere) {
		if ($position <> 'SPK' && $position <> 'Sales') {
			if ($position == 'District Supervisor') {
				$res = read_write("select 
										sum(comm_ds) as sum_comm 
									from 
										".$strfromor." 
									where 
										tb_invoice.district_supervisor='".$salescode."' 
									and 
										tb_ttu.invoiceno = tb_invoice.invoiceno 
									and  ".$sqlwhere." and tb_salesman.active=1");	
				
			
			} else {
				$res = read_write("select 
										sum(comm_trainer) as sum_comm 
									from 
										".$strfromor." 
									where 
										tb_invoice.trainer='".$salescode."' 
									and 
										tb_ttu.invoiceno = tb_invoice.invoiceno 
									and 
										".$sqlwhere." and tb_salesman.active=1");		
				
			}
			
			$row = mysql_fetch_array($res);
			return $row['sum_comm'];
		} else {
			return 0;
			
		}
	}
?>
