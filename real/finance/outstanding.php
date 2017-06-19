<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}

	$constantbranch = $branch;
	
	$x 		= $_GET['x'];
	$search = $_GET['search'];
	$xbranch = $_GET['xbranch'];
	if ($xbranch<>"") { 
		$_SESSION['slbranch'] = $xbranch;		
	}
	$slbranch = $_POST['slbranch'];

	if ($slbranch<>"") { 
		$_SESSION['slbranch'] = $slbranch;		
		
	}
	if ($_SESSION['slbranch']<>"" ) { $branch = $_SESSION['slbranch']; }

	if ($x==1) {
		$_SESSION['period'] = $_POST['period'];
		$_SESSION['salesman'] = $_POST['salesman'];
		$_SESSION['ver'] = $_GET['ver'];

		if ($_SESSION['ver']==0) {
			
			
		} else {
			if (!is_array($_SESSION['arrinv'])) {
				unset($_SESSION['arrinv']);
			}

			$res = read_write("SELECT tb_invoice.invoiceno,((tax + totalsales ) - ifnull(sum(ppn_payment + payment),0) - totalreturn-ppnreturn) AS outstanding
						FROM 
							tb_company, 
							tb_deliveryaddr, 
							tb_buyer, 
							tb_invoice 
						LEFT JOIN 
							tb_ttu 
						ON 
							tb_invoice.invoiceno = tb_ttu.invoiceno 
						WHERE 
							tb_invoice.buyercode = tb_buyer.buyercode 
						AND 
							tb_company.companycode = tb_deliveryaddr.companycode 
						AND 
							tb_deliveryaddr.deliverycode = tb_buyer.deliverycode 
						AND 
							tb_invoice.invoicedate < '".$_SESSION['period']."' 
						AND
							ifnull( ttudate, '0000-00-00' )  < '".$_SESSION['period']. "'
						and
							tb_invoice.salescode ='".$_SESSION['salesman']."' 		
						and
							tb_invoice.validate=1
						GROUP BY 
							tb_invoice.invoiceno 
						HAVING outstanding > 0"); // and net_sales>0) OR outstanding IS NULL
			
			$i = 0;
			while ($rwres = mysql_fetch_array($res)) {
				$_SESSION['arrinv'][$i] = $rwres['invoiceno'];
				$i++;
			}
			$res2 = read_write("SELECT tb_invoice.invoiceno,((tax + totalsales ) - ifnull(sum(ppn_payment + payment),0) - totalreturn-ppnreturn) AS outstanding, (tax+totalsales-totalreturn-ppnreturn) as net_sales 
						FROM 
							tb_company, 
							tb_deliveryaddr, 
							tb_buyer, 
							tb_invoice 
						LEFT JOIN 
							tb_ttu 
						ON 
							tb_invoice.invoiceno = tb_ttu.invoiceno 
						WHERE 
							tb_invoice.buyercode = tb_buyer.buyercode 
						AND 
							tb_company.companycode = tb_deliveryaddr.companycode 
						AND 
							tb_deliveryaddr.deliverycode = tb_buyer.deliverycode 
						AND 
							tb_invoice.invoicedate < '".$_SESSION['period']."' 
						AND
							ifnull( ttudate, '0000-00-00' ) > '".$_SESSION['period']. "'
						and
							tb_invoice.salescode ='".$_SESSION['salesman']."' 	
						and
							tb_invoice.validate=1
						GROUP BY 
							tb_invoice.invoiceno");
			
			while ($rwres2 = mysql_fetch_array($res2)) {
				$_SESSION['arrinv'][$i] = $rwres2['invoiceno'];
				$i++;
			}
			
			$_SESSION['end'] = count($_SESSION['arrinv']);
		}	 
		
		
	}
	
	/*$page = $_GET['page'];
	$recordperpage = 30;
	if ($page == "")
	{
		$page = 1;
	}
	
	$start = ($page-1) * $recordperpage;
	
	$max = (int) ceil($_SESSION['end'] / $recordperpage);*/
	
	if ($max==0) {$max=1;}
	if ($search == 1) {
		if ($_SESSION['ver']==0) {		
			
			$query = "select distinct tb_invoice.salescode, tb_salesman.salesname from tb_salesman, tb_invoice where tb_salesman.salescode=tb_invoice.salescode and invoicedate<'".date("Y",strtotime($_SESSION['period']))."-".date("m",strtotime($_SESSION['period']))."-01' and tb_invoice.validate=1 and tb_salesman.salescode like '".$branch.".%'";
			

			if ($_SESSION['salesman']<>'') {
				$query = $query." and tb_invoice.salescode ='".$_SESSION['salesman']."'";
			}
			$query = $query." order by salesname"; // limit ".$start.",".$recordperpage;
			$res = read_write($query);
			

		} else {
			$n=0;
			//for ($i=$start;$i<$start+$recordperpage;$i++) {
			for ($i=0;$i<=count($_SESSION['arrinv']);$i++) {
				if ($_SESSION['arrinv'][$i]<>"") {
					if ($n==0) {
						$q_where = "tb_invoice.invoiceno='".$_SESSION['arrinv'][$i]."'";
					} else {
						$q_where =$q_where." or tb_invoice.invoiceno='".$_SESSION['arrinv'][$i]."'";
					}
					$n++;
				}
			}

			if (count($_SESSION['arrinv'])>0) {
				$res = read_write("SELECT 
								tb_company.companycode, 
								tb_company.companyname, 
								tb_buyer.deliverycode, tb_deliveryaddr.street,
								tb_buyer.buyercode, 
								tb_buyer.personname, 
								tb_invoice.invoiceno, 
								(tax + totalsales - totalreturn-ppnreturn) as s_total,
									invoicedate
								
							FROM 
								tb_company, 
								tb_deliveryaddr, 
								tb_buyer, 
								tb_invoice 
							LEFT JOIN 
								tb_ttu 
							ON 
								tb_invoice.invoiceno = tb_ttu.invoiceno 
							WHERE 
								tb_invoice.buyercode = tb_buyer.buyercode
							AND 
								tb_company.companycode = tb_deliveryaddr.companycode 
							AND 
								tb_deliveryaddr.deliverycode = tb_buyer.deliverycode  
							AND 
								tb_invoice.invoicedate < '".$_SESSION['period']."' 
							and
								tb_invoice.validate=1							
							AND (".$q_where.") 
							and
								tb_invoice.salescode ='".$_SESSION['salesman']."' 
							GROUP BY 
								tb_invoice.invoiceno
							ORDER BY 
								companyname, tb_buyer.deliverycode, 
								personname");
			}
			
		}
	}
?>
<html>
	<head>
	<title>Outstanding</title>
		
		<script language="javascript" src="cal2.js">
			/*
			Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
			Script featured on/available at http://www.dynamicdrive.com/
			This notice must stay intact for use
			*/
		</script>
		<script language="javascript" src="cal_conf2.js"></script>
		<script language='javascript'>
			function clickoutstanding(intver) {
				if (intver == 0) {
				} else {
				}
				document.frmaging.action = 'outstanding.php?x=1&search=1&ver='+intver;
				document.frmaging.submit();
			}
			
			function changebranch(branch) {
				location.href='outstanding.php?xbranch=' + branch;
			}
		</script>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		</head>
	<body>
	<div class=noprint>
		<?php 
		//if ($search <> 1) {
			
		include('../menu.php');?><br>
	
		<form method="POST" name=frmaging action="outstanding.php?x=1&search=1" onsubmit="return validate()">
		<table border="0" cellspacing="0" cellpadding="0" bgcolor=#e5ebf9 class=header width="25%" align="center">
		<tr >
		<td width="50%"><div class="header icon-48-menu">Outstanding / Aging </div></td>
		</tr>
		
		
		<?php
		if ($constantbranch == "") {	
			echo "<tr>
			<td colspan=2>";
				echo " &nbsp;Branch: &nbsp;<select name=slbranch class=tBox onchange='changebranch(this.value)'>";
				echo "<option value=''>";
				foreach ($arr_branch as $key => $value) {
					
					echo "<option value='".$key."'";
					if ($key == $_SESSION['slbranch'] || $key==$xbranch) { echo " selected"; }
					echo ">".$value;
				}
				echo "</select>";
			echo "</td>	</tr>";
		}?>
		
		<tr><td colspan="2">
			<table border="0" cellspacing="1" cellpadding="1" bgcolor="white" width="100%">
			<tr>
					<td class="small"><a href="javascript:showCal('Calendar5')" class=smalllink>Periode:</a></td>
					<td class="small">Salesman:</td>
				</tr>
			<tr>
					<td><input type=text name="period" class=tBox size=10 style="width:130px;" value=<?php echo $_SESSION['period'];?>> </td>
					<td><select name=salesman class=tBox style="width:130px;">
				<option value="">--
			<?php
		
				$rsales = read_write("select salescode,salesname from tb_salesman where salescode like '".$branch.".%' order by salesname");
		
				
				while ($rowsales=mysql_fetch_array($rsales)) {
					echo "<option value='".$rowsales['salescode']."'>".$rowsales['salesname'];
				}?>
				</select></td>
				</tr>
			<tr><td height=10></td></tr>
			
			<tr>
				<td colspan=5 align=center><input type=button value='Aging' class=tBox onclick="javascript:clickoutstanding(0)"> &nbsp;
				<input type=button value='Outstanding' class=tBox onclick="javascript:clickoutstanding(1)"></td>
			</tr>
			</table>
		</td></tr>	
	</table></form><br>
	<?php 
	//} else {
		//if ($page==1) {
	if ($search== 1) {
			
			$out_header = "<center><font class=header><b>PT. GAPURA RAYA</b></font><br><font class=small>";
			if ($_SESSION['ver']==0) {
				$out_header .= "<b>Aging Report - Salesman</b>";
			} else {
				$out_header .= "<b>Outstanding Report</b>";
			}
			$out_header .= " (".$arr_branch[$branch].")<br>Period: ".date("d M Y",strtotime($_SESSION['period']))."<br>";
			$out_header .= "Salesman: ";
			if ($_SESSION['salesman']=='') {
				$out_header .= "All";
			} else {
				$rsales = read_write("select salesname from tb_salesman where salescode=".$_SESSION['salesman']);
				$rowsales = mysql_fetch_array($rsales);
				$out_header .= $rowsales['salesname'];
			}
			$out_header .= "</font></center>";
		//}

		$out_content = "<table border=0 cellspacing=0 cellpadding=2 width=100%><tr valign=top bgcolor=#e5ebf9 class=header><td style='border-top:solid thin;border-left:solid thin;border-right:solid thin;border-bottom:solid thin'><b>No</b></td>";
		if ($_SESSION['ver']==0) {
			$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Sales Name</b></td>";
			
		} else {
			$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Date</b></td>";
			$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Invoice No.</b></td>";
		}
		$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin' align=right><b>0-30<br>Days</b></td>";
		$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin' align=right><b>31-60<br>Days</b></td>";
		$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin' align=right><b>61-90<br>Days</b></td>";
		$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin' align=right><b>91-120<br>Days</b></td>";
		$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin' align=right><b>>120<br>Days</b></td>";
		if ($_SESSION['ver']==1) { 
			$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin' align=right><b>Days</b></td>"; 
		} else {
			$out_content .= "<td style='border-top:solid thin;border-right:solid thin;border-bottom:solid thin' align=right><b>Total</b></td>"; 
		}
		$out_content .= "</tr>";
		$num_rows = mysql_num_rows($res);
		if ($num_rows > 0) {
			$i = 0;
			$no = $start;
			$arrtotout = array(0,0,0,0,0);
			$tot_arr_os = array(0,0,0,0,0);
			$tot_os_initial = 0;
			$tot_arr_pay = 0;
			$tot_arr_sales = 0;
			$tot_os_cur = 0;
			while ($row=mysql_fetch_array($res)) {	
				if ($_SESSION['ver']==0) {
					
					$b_exist = false;
					for ($j=0;$j<count($arrtotout);$j++) {						
						switch ($j) {
							case 0:
								$s = 1;
								$l = 30;								
								break;
							case 1:
								$s = 31;
								$l = 60;
								break;
							case 2:
								$s = 61;
								$l = 90;
								break;
							case 3:
								$s = 91;
								$l = 120;
								break;
							case 4:
								$s = 121;
								$l = 0;
								break;
						}						
						
						$query = "select sum((tax + totalsales) -totalreturn-ppnreturn) as total_sales 
									from  tb_invoice where tb_invoice.salescode='".$row['salescode']."' and tb_invoice.validate=1 and ";
						
						if ($j<4) {
							$datequery = "to_days('".$_SESSION['period']."') - to_days(invoicedate)>=".$s." and to_days('".$_SESSION['period']."') - to_days(invoicedate)<=".$l;
							
						} else {
							$datequery = "to_days('".$_SESSION['period']."') - to_days(invoicedate)>=".$s;
							
						}
						
						$query = $query.$datequery;
						//echo $query."<br>";
						$rssales = read_write($query);
						$rowcur = mysql_fetch_array($rssales);
						
						$arrsales = $rowcur['total_sales'];
						//if ($j==4 && $row['salescode']=='01.0006') { echo $query." - ".$arrsales."<br>"; }
						$query = "select 
										sum(ppn_payment+payment) as total_pay 
									from 
										tb_ttu, tb_invoice 
									where 
										tb_invoice.invoiceno=tb_ttu.invoiceno											
									and 
										tb_invoice.salescode='".$row['salescode']."' and tb_invoice.validate=1 and "; 							
						if ($j<4) {
							$datequeryttu = "to_days('".$_SESSION['period']."') - to_days(invoicedate)>=".$s." and to_days('".$_SESSION['period']."') - to_days(invoicedate)<=".$l;
						} else {
							$datequeryttu = "to_days('".$_SESSION['period']."') - to_days(invoicedate)>=".$s;
						}
						$datequeryttu = $datequeryttu." and ttudate<='".$_SESSION['period']."'";
						$query = $query.$datequeryttu;

						//echo $query."<br>";
						$rssales = read_write($query);
						$rowcur = mysql_fetch_array($rssales);
						$arrpay = $rowcur['total_pay'];
						if ($arrpay=="") { $arrpay = 0; }

						//if ($j==4 && $row['salescode']=='01.0006') { echo $query." - ".$arrpay."<br>"; }
						$arrtotout[$j] = $arrsales-$arrpay;
						
						if ($arrtotout[$j]>0) { $b_exist = true; 
					
						$tot_arr_os[$j] = $tot_arr_os[$j] + $arrtotout[$j];
						/*if ($j==4) {
							echo $tot_arr_os[$j]."<br>";
						}*/
						}
						//try nested array
						
					}
					
					if ($b_exist) {
						$no++;
						$out_content .= "<tr class=small>";
						$out_content .= "<td>".$no."</td>";					
						$out_content .= "<td>".$row['salesname']."</td>";
						$sum_per_sales = 0;
						for ($j=0;$j<count($arrtotout);$j++) {
							if ($arrtotout[$j]>0) {
								$out_content .= "<td align=right>".number_format($arrtotout[$j],0)." &nbsp;</td>";
								$sum_per_sales += $arrtotout[$j];
							} else {
								$out_content .= "<td align=right>- &nbsp;</td>";
							}
						}
						$out_content .= "<td align=right>".number_format($sum_per_sales,0)."</td></tr>";
					}
					
				} else {
					
					$invno = $row['invoiceno'];
					$q_pay = "SELECT  sum(ppn_payment + payment) as total_pay FROM  tb_invoice, tb_ttu where tb_invoice.invoiceno = tb_ttu.invoiceno and ifnull( ttudate, '0000-00-00' )  < '".$_SESSION['period']."' and tb_invoice.invoiceno='".$invno."' GROUP BY tb_invoice.invoiceno";

					$rspay = read_write($q_pay);
					$rwpay = mysql_fetch_array($rspay);
					$total_pay = $rwpay['total_pay'];
					mysql_free_result($rspay);

					$outstanding = $row['s_total'] - $total_pay;

					$invoicedate = $row['invoicedate'];
					$yrls = date("Y",strtotime($invoicedate));
					$mthls = date("n",strtotime($invoicedate));
					$ddls = date("j",strtotime($invoicedate));
					$ts_invoicedate = mktime(0,0,0,$mthls,$ddls,$yrls);
					
					$yrls = date("Y",strtotime($_SESSION['period']));
					$mthls = date("n",strtotime($_SESSION['period']));
					$ddls = date("j",strtotime($_SESSION['period']));
					$ts_period = mktime(0,0,0,$mthls,$ddls,$yrls);

					$datediff = ((($ts_period - $ts_invoicedate) / 60) / 60) / 24;				
					
					switch (true) {
						case ($datediff >=0 && $datediff<=30):
							$idxoutpos = 0;break;
						case ($datediff >=31 && $datediff<=60):
							$idxoutpos=1;break;
						case ($datediff >=61 && $datediff<=90):
							$idxoutpos=2;break;
						case ($datediff >=91 && $datediff<=120):
							$idxoutpos=3;break;
						case ($datediff >=121):
							$idxoutpos=4;break;
					}
					$arrtotout[$idxoutpos]=$arrtotout[$idxoutpos]+$outstanding;

					$deliveryid = $row['deliverycode'];					
					 
					if ($branch == "00" || $branch == "01") {
						if ($deliveryid <> "00.0000000" && $deliveryid <> "01.0000000") {
							$customer = stripslashes($row['companyname']);
							if (stripslashes($row['street'])<>"") {
								$customer .= " <font class=smalllest>(".stripslashes($row['street']).")</font>";
							}
							
							$cid = $row['companycode'];
							//$customerid = $cid;
							$customerid = $deliveryid;
						} else {
							$customer = stripslashes($row['personname']);	
							
							$bid = $row['buyercode'];
							$customerid = $bid;
						}
					} else {
						if ($deliveryid <> $branch.".0000000") {
							$customer = stripslashes($row['companyname']);
							if (stripslashes($row['street'])<>"") {
								$customer .= " <font class=smalllest>(".stripslashes($row['street']).")</font>";
							}
							$cid = $row['companycode'];
							//$customerid = $cid;
							$customerid = $deliveryid;
						} else {
							$customer = stripslashes($row['personname']);	
							
							$bid = $row['buyercode'];
							$customerid = $bid;
						}
					}
					
					$newrow = 0;
					
					if ($i==0 || ($prevcustomerid <> $customerid)) {
						$newrow = 1;						
					}

					if ($newrow==1) { 
						if ($i>0) { $out_content .= "<tr><td height=10></td></tr>"; }
						$out_content .= "<tr class=small><td></td><td colspan=8>".$customer."</td></tr>";
					}
					$no++;
					$out_content .= "<tr class=small>";
					$out_content .= "<td>".$no."</td><td>".$invoicedate."</td><td>".$invno."</td>";
					
					for ($j=0;$j<=4;$j++) {
						if ($j == $idxoutpos) {
							$out_content .= "<td align=right>".number_format($outstanding,0)." &nbsp;</td>";
						} else {
							$out_content .= "<td></td>";
						}
					}
					//$out_content .= "<td align=right>".$datediff." &nbsp;</td>";
					$out_content .= "<td align=right>".ceil($datediff)." &nbsp;</td>";
					$out_content .= "</tr>";
					
					
					$prevcustomerid = $customerid;
				}
				$i++;
				
			}
			//if ($page == $max) {
				$out_content .= "<tr><td height=10></td></tr>";
				$out_content .= "<tr><td colspan=9><hr></td></tr>";
				$out_content .= "<tr bgcolor=#e5ebf9 class=header>";
				if ($_SESSION['ver']==0) {
					$out_content .= "<td></td><td><b>Total:</b></td>";
					$sum_tot_arr_os = 0;
					for ($j=0;$j<count($tot_arr_os);$j++) {
						if ($tot_arr_os[$j]>0) {
							$out_content .= "<td align=right>".number_format($tot_arr_os[$j],0)." &nbsp;</td>";
							$sum_tot_arr_os += $tot_arr_os[$j];
						} else {
							$out_content .= "<td align=right>- &nbsp;</td>";
						}
					}
					$out_content .= "<td align=right>".number_format($sum_tot_arr_os,0)."</td></tr>";
				} else {
					
					$out_content .= "<td colspan=2></td><td><b>Total:</b></td>";
					$totalos =0;
					for ($j=0;$j<count($arrtotout);$j++) {
						
						$out_content .= "<td align=right>";
						if ($arrtotout[$j]>0) {
							$out_content .= number_format($arrtotout[$j],0);
							$totalos = $totalos+$arrtotout[$j];
							
						} else {
							$out_content .= "";
						}
						$out_content .= " &nbsp;</td>";

					}
					$out_content .= "<td align=right>".number_format($totalos,0)." &nbsp;</td></tr>";
				}
			//}
		} 
		$out_content .= "</table><br>";
		echo $out_content;

		echo "<table width=100%><tr><td height=10></td></tr><tr class=smallmed><td align=center>";
		echo "<input type=button value='Print' class=tBox onclick='javascript:window.print()' style='width:100px'></td></tr></table>";
	} // if search==1

	unset($_SESSION['arrinv']);
		?>
	</div>

<div class=noscr>

	<?php
		echo $out_header;
		echo $out_content;
		echo "<table width=95% align=center>";
echo "<tr><td height=10></td></tr><tr class=smallmed><td >Dibuat Oleh, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Disetujui Oleh,</td></tr></table>";
		
	?>
	</div>
	</body>
</html>