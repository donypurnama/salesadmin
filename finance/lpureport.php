<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	$constantbranch = $branch;
	$lpudatefrom = $_POST['lpudatefrom'];
	$lpudateto = $_POST['lpudateto'];
	$invno 		 = $_POST['invoiceno'];
	$ttuno 		 = $_POST['ttuno'];
	$companyname = $_POST['companyname'];
	$personname  = $_POST['personname'];
	$slbranch	 = $_POST['slbranch'];
	$divisionid	 = $_POST['divisionid'];
	$lpuno = $_GET['lpuno'];

	$x = $_GET['x'];
	$lpusect = $_GET['lpusect'];
	if ($lpusect == "") { $lpusect="0"; }

	$strwhere = "";
	if ($x == 1) {
		$_SESSION['invno'] = $invno;
		$_SESSION['ttuno'] = $ttuno;
		$_SESSION['companyname'] = $companyname;
		$_SESSION['personname'] = $personname;
		$_SESSION['lpudatefrom'] = $lpudatefrom;
		$_SESSION['lpudateto'] = $lpudateto;
		$_SESSION['divisionid']   = $divisionid;
		$_SESSION['slbranch']  	 = $slbranch;
	}

	$strwherecond = "";
	if ($_SESSION['slbranch'] <> '') { $branch=$_SESSION['slbranch']; }
	if ($_SESSION['invno']<>'') {
		$strwhere = $strwhere." and tb_invoice.invoiceno='".$_SESSION['invno']."'";
		
		$strwherecond = $strwherecond." and tb_invoice.invoiceno='".$_SESSION['invno']."'";
	}
	if ($_SESSION['ttuno']<>'') {
		$strwhere = $strwhere." and ttuno='".$_SESSION['ttuno']."'";

		$strwherecond = $strwherecond." and ttuno='".$_SESSION['ttuno']."'";
	}
	if ($_SESSION['companyname']<>'') {
		$strwhere = $strwhere." and companyname like '%".$_SESSION['companyname']."%'";	
		
		$strwherecond = $strwherecond." and companyname like '%".$_SESSION['companyname']."%'";	
	}
	if ($_SESSION['personname']<>'') {
		$strwhere = $strwhere." and personname like '%".$_SESSION['personname']."%'";	

		$strwherecond = $strwherecond." and personname like '%".$_SESSION['personname']."%'";	
		
	}
	
	if ($_SESSION['lpudatefrom']<>'' ) {
		if ($_SESSION['lpudateto']=='') {
			$strwhere = $strwhere." and lpudate ='".$_SESSION['lpudatefrom']."'";
		} else {
			$strwhere = $strwhere." and lpudate between '".$_SESSION['lpudatefrom']."' and '".$_SESSION['lpudateto']."'";
		}
	} 
	
	if ($_SESSION['divisionid']<>'') {
			$arrdiv = explode(",",$_SESSION['divisionid']);
			$n = 0;
			$strwhere = $strwhere." and (";
			$strwherecond = $strwherecond." and (";
			foreach ($arrdiv as $div_id) {
				if ($n==0) {
					$strwhere = $strwhere."tb_commgroup.divisionid='".$div_id."'";
					$strwherecond = $strwherecond."tb_commgroup.divisionid='".$div_id."'";
				} else {
					$strwhere = $strwhere." or tb_commgroup.divisionid='".$div_id."'";
					$strwherecond = $strwherecond." or tb_commgroup.divisionid='".$div_id."'";
				}	
				$n++;
			}
			$strwhere = $strwhere.")";
			$strwherecond = $strwherecond.")";
		}

	$strfrom = "tb_ttu";
	
	if ($_SESSION['companyname'] <> '' || $_SESSION['personname']<>'' || $_SESSION['invno']<>'' || $_SESSION['divisionid']<>'') {
		$strfrom .= ", tb_company, tb_deliveryaddr, tb_buyer, tb_invoice, tb_commgroup";
		$strwherecond .= " and tb_ttu.invoiceno = tb_invoice.invoiceno and
								tb_invoice.buyercode 	= tb_buyer.buyercode 
							AND 
								tb_company.companycode 	= tb_deliveryaddr.companycode
							And
								tb_commgroup.commgroupcode=tb_invoice.commgroupcode
							AND 
								tb_deliveryaddr.deliverycode = tb_buyer.deliverycode";
	} 
	
	$query = "SELECT ttuid, 
						ttuno, 
						tb_ttu.payment + tb_ttu.ppn_payment AS total_pay, 
						tb_invoice.invoiceno, 								
						tb_company.companyname, 
						tb_buyer.personname, tb_buyer.buyercode,
						tb_buyer.deliverycode,
						tb_salesman.alias,
						tb_ttu.ppn_payment, tb_ttu.payment,
						tb_ttu.lpudate, tb_deliveryaddr.street
											
					FROM 
						tb_company, 
						tb_buyer, 
						tb_deliveryaddr, tb_salesman, 
						tb_invoice,
						tb_ttu ,
						tb_commgroup
						
					WHERE 
						tb_ttu.invoiceno 		= tb_invoice.invoiceno and
						tb_invoice.buyercode 	= tb_buyer.buyercode 
					AND 
						tb_company.companycode 	= tb_deliveryaddr.companycode
					AND
						tb_salesman.salescode = tb_invoice.salescode
					And
						tb_commgroup.commgroupcode=tb_invoice.commgroupcode
					AND
						(tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."')))  
					And
						tb_ttu.ttuno like 'TU".$branch."%'
					AND 
						tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere." 
					ORDER BY ";
	if ($lpusect == "0") {
		$query .= "tb_invoice.invoiceno ASC, tb_invoice.invoicedate ASC";
	} elseif ($lpusect == "1") {
		$query .= "tb_company.companyname, tb_buyer.deliverycode, tb_buyer.personname";
	}
//echo $query;
	$res = read_write($query);
				
	
	
?>

<html>
<head>
<title>
Laporan Penerimaan Uang
</title>
<script language="javascript" src="cal2.js">
			/*
			Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
			Script featured on/available at http://www.dynamicdrive.com/
			This notice must stay intact for use	
			*/
		</script>
<script language="javascript" src="cal_conf2.js"></script>
		<script language="javascript">
		function validate() {
			with (document.frmttu) {
				
				if (invoiceno.value=='' && ttuno.value=='' && companyname.value=='' && personname.value=='' && lpudatefrom.value=='' && lpudateto.value=='' && divisionid.value=='') {
					
					alert('Please fill something to search');
					return false;
				} else {
					if (lpudatefrom.value=='' && lpudateto.value!=='') {
						alert('Please fill LPU Date From');
						return false;
					}
				}
				return true;
			}
		}

		function gosearch() {
			
			with (document.frmttu) {
				
			action = 'index.php?x=1&search=1';			
			onsubmit='return validate()';
			submit();
			}
		}
		
		function golpureport() {
			with (document.frmttu) {
				if (lpudatefrom.value=='') {
					alert('Please fill Lpu Date');
				} else {
					varno = prompt("LPU No.",'');
					action = 'lpureport.php?x=1&lpuno=' + varno;					
					onsubmit='';
					submit();
					
				}
			}
		}
		
		</script>
<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
</head>
<body class=portrait>
<div class=noprint>
<?php include('../menu.php'); 

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent,'MSIE') > 0) {
	$browser = 'ie';
} elseif (strpos($user_agent,'Firefox') > 0)  {
	$browser = 'firefox';
} else {
	$browser = '';
}

echo "<br>";
include('formttu.php'); 
echo createttuform('', 'return golpureport()', 'lpureport', $constantbranch, $arr_branch); //'lpureport.php?x=1&lpusect='.$lpusect,
$_SESSION['commhdr'] = "<font class=big>PT. Gapura Raya<br>Laporan Penerimaan Uang (".$arr_branch[$branch].") ";
if ($lpuno <> "") { $_SESSION['commhdr'] .= "No. ".$lpuno; }

if ($_SESSION['divisionid']<>'') {
	if (strpos($_SESSION['divisionid'],",") == false) {
		$rsdiv = read_write("select divisionname from tb_division where divisionid = '".$_SESSION['divisionid']."'");
		$rwdiv = mysql_fetch_array($rsdiv);
		$divname = $rwdiv['divisionname'];
	} elseif ($_SESSION['divisionid']=='C,E,O,N') {
		$divname = "Welding";
	} elseif ($_SESSION['divisionid']=='F,Q') {
		$divname = "Fire";
	}
	$_SESSION['commhdr'] .= "<br>Division: ".$divname;
}

if ($_SESSION['lpudatefrom']<>'') {
	$_SESSION['commhdr'] .= "<br>LPU Date: ".date('j M Y',strtotime($_SESSION['lpudatefrom']));
	if ($_SESSION['lpudateto']<>'' && $_SESSION['lpudatefrom']<>$_SESSION['lpudateto']) {
		$_SESSION['commhdr'] .= " - ".date('j M Y',strtotime($_SESSION['lpudateto']));
	}
}

$_SESSION['commhdr'] .="</font>";
$_SESSION['commhdr'] .="<br>";


$_SESSION['commstr'] = "<br><table border=0 cellspacing=0 cellpadding=2 width='95%' align=center>";
$_SESSION['commstr'] .= "<tr bgcolor=#e5ebf9 class=header >";
if ($lpusect=="0") {
	$_SESSION['commstr'] .= "<td colspan=2 style='border-top:solid thin;border-left:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Customer Name</b></td>";
	$_SESSION['commstr'] .=	"<td width=15% style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Invoice No</b></td>";
	$_SESSION['commstr'] .= "<td width=15% style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>TTu No</b></td>";
	$_SESSION['commstr'] .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Terima Gross</b></td>";
	$_SESSION['commstr'] .=	"<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>PPN</b></td>";
	$_SESSION['commstr'] .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Terima Net</b></td>";
} else {
	$_SESSION['commstr'] .= "<td style='border-top:solid thin;border-left:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Customer Name</b></td>";
	$_SESSION['commstr'] .= "<td style='border-top:solid thin;border-left:solid thin;border-right:solid thin;border-bottom:solid thin'><b>LPU Date</b></td>";	
	$_SESSION['commstr'] .= "<td style='border-top:solid thin;border-left:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Invoice No</b></td>";
	$_SESSION['commstr'] .= "<td style='border-top:solid thin;border-left:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Payment</b></td>";
}
$_SESSION['commstr'] .= "</tr>";
	
	$rowno=1;
	$prevcustomerid="";
	$cntrec = mysql_num_rows($res);
	while ($row = mysql_fetch_array($res)) {
		if ($row['companyname'] <> '') {
			$customer = $row['companyname'];
			if ($row['street']<>"" && $lpusect=="1") { $customer .= " (".$row['street'].")"; }
		} else {
			$customer = $row['personname'];
		}
		if ($lpusect == "0") {
			$_SESSION['commstr'] .= "<tr class=replaceclass><td>".$customer."</td><td> &nbsp;".$row['alias']."</td><td>".$row['invoiceno']."</td><td>".$row['ttuno']."</td><td align=right>".number_format($row['payment'] + $row['ppn_payment'],0)." &nbsp;</td><td align=right>".number_format($row['ppn_payment'],0)." &nbsp;</td><td align=right>".number_format($row['payment'],0)." &nbsp;</td></tr>";
		} else {
			$deliveryid = $row['deliverycode'];
			if ($branch == "00" || $branch == "01") {
				if ($deliveryid <> "00.0000000" && $deliveryid <> "01.0000000") {
					$customerid=$deliveryid;
				} else {
					$customerid=$row['buyercode'];
				}
			} else {
				if ($deliveryid <> $branch.".0000000") {
					$customerid=$deliveryid;
				} else {
					$customerid=$row['buyercode'];
				}
			}
			
			if ($customerid <> $prevcustomerid) {
				if ($rowno>1) {
					$_SESSION['commstr'] .= "<tr class=replaceclass><td colspan=4 align=right>".number_format($sumpercustomer,0)."</td></tr>";
					$_SESSION['commstr'] .= "<tr><td height=3></td></tr>";
				}
				
				$_SESSION['commstr'] .= "<tr class=replaceclass><td colspan=4>".$customer."</td></tr>";
				
				$sumpercustomer=0;
				
			} 
			$prevcustomerid=$customerid;
			$_SESSION['commstr'] .= "<tr class=replaceclass>";
			$_SESSION['commstr'] .= "<td>".$row['ttuno']."</td>";
			$_SESSION['commstr'] .= "<td>".$row['lpudate']."</td>";
			$_SESSION['commstr'] .= "<td>".$row['invoiceno']."</td>";
			$_SESSION['commstr'] .= "<td align=right>".number_format($row['payment'] + $row['ppn_payment'],0)."</td>";
			$_SESSION['commstr'] .= "</tr>";
			$sumpercustomer += $row['payment'] + $row['ppn_payment'];
			if ($rowno==$cntrec) {
				$_SESSION['commstr'] .= "<tr class=replaceclass><td colspan=4 align=right>".number_format($sumpercustomer,0)."</td></tr>"; }
			$rowno++;
		}

	}
	
	mysql_free_result($res);
	$query_sum_current = "select sum(tb_ttu.payment) as netpay, sum(tb_ttu.ppn_payment) as ppnpay from ".$strfrom." where ttuno like 'TU".$branch."%' ".$strwherecond;
	if ($_SESSION['lpudatefrom']<>'' ) {
		if ($_SESSION['lpudateto']=='') {
			$query_sum_current = $query_sum_current." and lpudate ='".$_SESSION['lpudatefrom']."'";
		} else {
			$query_sum_current = $query_sum_current." and lpudate between '".$_SESSION['lpudatefrom']."' and '".$_SESSION['lpudateto']."'";
		}
	} 
	$res = read_write($query_sum_current);

			
	$row = mysql_fetch_array($res);
	if ($lpusect=="0") {
		$_SESSION['commstr'] .= "<tr><td height=10></td></tr><tr><td colspan=7><hr></td></tr><tr class=replaceclass><td colspan=2></td><td colspan=2>Total hari ini</td><td align=right>".number_format($row['netpay']+$row['ppnpay'],0)." &nbsp;</td><td align=right>".number_format($row['ppnpay'])." &nbsp;</td><td align=right>".number_format($row['netpay'])." &nbsp;</td></tr>";
	} else {
		$_SESSION['commstr'] .= "<tr><td height=10></td></tr><td colspan=4><hr></td></tr><tr class=replaceclass><td>Total</td><td colspan=3 align=right>".number_format($row['netpay']+$row['ppnpay'],0)." &nbsp;</td></tr>";
	}

	if ($lpusect=="0") {
		$res2 = read_write("select sum(tb_ttu.payment) as payment_before, sum(tb_ttu.ppn_payment) as ppn_payment_before from ".$strfrom." where lpudate<'".$_SESSION['lpudatefrom']."' and lpudate>='".date("Y",strtotime($_SESSION['lpudatefrom']))."-".date("m",strtotime($_SESSION['lpudatefrom']))."-01' and ttuno like 'TU".$branch."%' ".$strwherecond);
		$row2 = mysql_fetch_array($res2);

		$_SESSION['commstr'] .= "<tr><td height=10></td></tr><tr class=replaceclass><td colspan=2></td><td colspan=2>Total s/d kemarin</td><td align=right>".number_format($row2['payment_before']+$row2['ppn_payment_before'],0)." &nbsp;</td><td align=right>".number_format($row2['ppn_payment_before'])." &nbsp;</td><td align=right>".number_format($row2['payment_before'])." &nbsp;</td></tr>";

		$_SESSION['commstr'] .= "<tr><td height=10></td></tr><tr class=replaceclass><td colspan=2></td><td colspan=2>Total seluruhnya</td><td align=right>".number_format($row2['payment_before']+$row2['ppn_payment_before']+$row['netpay']+$row['ppnpay'],0)." &nbsp;</td><td align=right>".number_format($row2['ppn_payment_before']+$row['ppnpay'])." &nbsp;</td><td align=right>".number_format($row2['payment_before']+$row['netpay'])." &nbsp;</td></tr>";
		
		
	}
	echo str_replace('replaceclass','small',$_SESSION['commstr']);
	echo "<tr><td height=10></td></tr><tr class=smallmed><td align=center colspan=7>";
	echo "<input type=button value='Print' class=tBox onclick='javascript:window.print()' style='width:100px'> &nbsp;";
	
	if ($lpusect=="0") {
		echo "<input type=button value='Per Customer View' class=tBox onclick='javascript:location.href=\"lpureport.php?lpusect=1\"' >";
	} else {
		echo "<input type=button value='Table View' class=tBox onclick='javascript:location.href=\"lpureport.php?lpusect=0\"' >";
	}
	echo "</td></tr></table>";

	$_SESSION['commstr'] .= "</table>";
	
?>
</div>

<div class=noscr>
<?php
echo "<center>".$_SESSION['commhdr']."</center>";
/*if ($browser == 'ie') {
	echo str_replace('replaceclass','smallmed',$_SESSION['commstr']);
} else {
	echo str_replace('replaceclass','smallmed',$_SESSION['commstr']);
}*/

echo str_replace('replaceclass','smallmed',$_SESSION['commstr']);

echo "<table width=95% align=center>";
echo "<tr><td height=10></td></tr><tr class=smallmed><td >Dibuat Oleh, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Disetujui Oleh,</td></tr></table>";
?>
</div>
</body>
</html>
