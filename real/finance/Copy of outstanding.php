<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	session_start();
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}

	$x 		= $_GET['x'];
	$search = $_GET['search'];
	if ($x==1) {
		$_SESSION['period'] = $_POST['period'];
		$_SESSION['salesman'] = $_POST['salesman'];
		$_SESSION['ver'] = $_GET['ver'];

		if ($_SESSION['ver']==0) {
			
			/*$query = "SELECT distinct(tb_invoice.salescode) FROM tb_salesman, tb_invoice 
								LEFT JOIN tb_ttu oN tb_invoice.invoiceno = tb_ttu.invoiceno
								WHERE tb_invoice.salescode = tb_salesman.salescode
								and	tb_invoice.invoiceno like 'FK".$branch."%' 
								And tb_salesman.active=1
								AND tb_invoice.invoicedate <= '".date("Y",strtotime($_SESSION['period']))."-".date("m",strtotime($_SESSION['period']))."-".date("d",strtotime($_SESSION['period']))."' 
								AND (( tax + totalsales ) - ( ppn_payment + payment ) - totalreturn-ppnreturn >0 OR ( tax + totalsales ) - ( ppn_payment + payment ) - totalreturn-ppnreturn IS NULL)";*/
			$query = "select distinct tb_invoice.salescode, tb_salesman.salesname from tb_salesman, tb_invoice where tb_salesman.salescode=tb_invoice.salescode and invoicedate<'".date("Y",strtotime($_SESSION['period']))."-".date("m",strtotime($_SESSION['period']))."-01' and active=1 and tb_salesman.salescode like '".$branch.".%'";

			if ($_SESSION['salesman']<>'') {
				$query = $query." and tb_invoice.salescode ='".$_SESSION['salesman']."'";
			}
			$res = read_write($query);
			
			$_SESSION['end'] = mysql_num_rows($res);
		} else {
			if (!is_array($_SESSION['arrinv'])) {
				unset($_SESSION['arrinv']);
			}

			$res = read_write("SELECT tb_invoice.invoiceno,((tax + totalsales ) - sum(ppn_payment + payment) - totalreturn-ppnreturn) AS outstanding 
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
						GROUP BY 
							tb_invoice.invoiceno 
						HAVING outstanding > 0 OR outstanding IS NULL");
			$i = 0;
			while ($rwres = mysql_fetch_array($res)) {
				$_SESSION['arrinv'][$i] = $rwres['invoiceno'];
				$i++;
			}
			$res2 = read_write("SELECT tb_invoice.invoiceno,((tax + totalsales ) - sum(ppn_payment + payment) - totalreturn-ppnreturn) AS outstanding 
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
						GROUP BY 
							tb_invoice.invoiceno 
						HAVING outstanding IS NOT NULL");
			while ($rwres2 = mysql_fetch_array($res2)) {
				$_SESSION['arrinv'][$i] = $rwres2['invoiceno'];
				$i++;
			}
			$_SESSION['end'] = count($_SESSION['arrinv']);
		}
		 
		
		
	}
	
	$page = $_GET['page'];
	$recordperpage = 30;
	if ($page == "")
	{
		$page = 1;
	}
	
	$start = ($page-1) * $recordperpage;
	
	$max = (int) ceil($_SESSION['end'] / $recordperpage);
	
	if ($max==0) {$max=1;}
	if ($search == 1) {
		if ($_SESSION['ver']==0) {			
			$query = "select distinct tb_invoice.salescode, tb_salesman.salesname from tb_salesman, tb_invoice where tb_salesman.salescode=tb_invoice.salescode and invoicedate<'".date("Y",strtotime($_SESSION['period']))."-".date("m",strtotime($_SESSION['period']))."-01' and active=1 and tb_salesman.salescode like '".$branch.".%'";
			if ($_SESSION['salesman']<>'') {
				$query = $query." and tb_invoice.salescode ='".$_SESSION['salesman']."'";
			}
			$query = $query." order by salesname limit ".$start.",".$recordperpage;
			$res = read_write($query);

		} else {
			$n=0;
			for ($i=$start;$i<$start+$recordperpage;$i++) {
				if ($_SESSION['arrinv'][$i]<>"") {
					if ($n==0) {
						$q_where = "tb_invoice.invoiceno='".$_SESSION['arrinv'][$i]."'";
					} else {
						$q_where =$q_where." or tb_invoice.invoiceno='".$_SESSION['arrinv'][$i]."'";
					}
					$n++;
				}
			}
			$res = read_write("SELECT 
								tb_company.companycode, 
								tb_company.companyname, 
								tb_buyer.deliverycode, 
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
							
							AND (".$q_where.") 
							and
								tb_invoice.salescode ='".$_SESSION['salesman']."' 
							GROUP BY 
								tb_invoice.invoiceno
							ORDER BY 
								companyname, 
								personname");
					
			
		}
	}
?>
<html>
	<head>
	<title>Outstanding</title>
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
			function clickoutstanding(intver) {
				if (intver == 0) {
				} else {
				}
				document.frmaging.action = 'outstanding.php?x=1&search=1&ver='+intver;
				document.frmaging.submit();
			}
				
		</script>
		</head>
	<body>
		<?php 
		if ($search <> 1) {
		include('../menu.php');?><br>
		<form method="POST" name=frmaging action="outstanding.php?x=1&search=1" onsubmit="return validate()">
		<table border="0" cellspacing="0" cellpadding="0" bgcolor=#e5ebf9 class=header width="25%" align="center">
		<tr >
		<td width="40%"><div class="header icon-48-menu">Outstanding / Aging </div></div></td>
		
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
				$rsales = read_write("select salescode,salesname from tb_salesman where active=1 and salescode like '".$branch.".%' order by salesname");
				
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
	</table></form>
	<?php 
	} else {
		if ($page==1) {
			echo "<center><font class=small>";
			if ($_SESSION['ver']==0) {
				echo "<b>Aging Report - Salesman</b>";
			} else {
				echo "<b>Outstanding Report</b>";
			}
			echo "<br>Period: ".date("d M Y",strtotime($_SESSION['period']))."<br>";
			echo "Salesman: ";
			if ($_SESSION['salesman']=='') {
				echo "All";
			} else {
				$rsales = read_write("select salesname from tb_salesman where salescode=".$_SESSION['salesman']);
				$rowsales = mysql_fetch_array($rsales);
				echo $rowsales['salesname'];
			}
			echo "</font></center>";
		}
		echo "<table border=0 cellspacing=0 cellpadding=2 width=100%><tr valign=top bgcolor=#e5ebf9 class=header><td><b>No</b></td>";
		if ($_SESSION['ver']==0) {
			echo "<td><b>Sales Name</b></td>";
			//<td><b>Initial</b></td><td><b>Sales</b></td><td><b>Payment</b></td><td><b>Last</b></td>";
		} else {
			echo "<td><b>Date</b></td><td><b>Invoice No.</b></td>";
		}
		echo "<td><b>0-30<br>Days</b></td><td><b>31-60<br>Days</b></td><td><b>61-90<br>Days</b></td><td><b>91-120<br>Days</b></td><td><b>>120<br>Days</b></td>";
		if ($_SESSION['ver']==1) { echo "<td>&nbsp;</td>"; }
		echo "</tr>";
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
					
					$no++;
					echo "<tr class=small>";
					echo "<td>".$no."</td>";
					//$rssales = read_write("select salesname from tb_salesman where salescode=".$row['salescode']);
					//$rowcur = mysql_fetch_array($rssales);
					echo "<td>".$row['salesname']."</td>";
					//calc initial sales
					/*$rssales = read_write("select sum((tax + totalsales) -totalreturn-ppnreturn) as total_sales 
											from 
												tb_invoice where tb_invoice.salescode='".$row['salescode']."' 
											and 
												invoicedate < '".date("Y",strtotime($_SESSION['period']))."-".date("m",strtotime($_SESSION['period']))."-01'");
									
					$rowcur = mysql_fetch_array($rssales);
					$total_sales_initial = $rowcur['total_sales'];
		
		//calc initial pay
					$rssales = read_write("select sum(ppn_payment+payment) as total_pay 
											from 
												tb_ttu, tb_invoice 
											where 
												tb_invoice.invoiceno=tb_ttu.invoiceno 
											and 
												tb_invoice.salescode='".$row['salescode']."' 
											and 
												invoicedate < '".date("Y",strtotime($_SESSION['period']))."-".date("m",strtotime($_SESSION['period']))."-01'
											and ttudate <												'".date("Y",strtotime($_SESSION['period']))."-".date("m",strtotime($_SESSION['period']))."-01'");
					
					$rowcur = mysql_fetch_array($rssales);					
					$total_pay_initial = $rowcur['total_pay'];
					
					//calc os initial
					$os_initial = $total_sales_initial-$total_pay_initial;
					$tot_os_initial = $tot_os_initial + $os_initial;
					
					$rssales = read_write("SELECT 
												sum((tax + totalsales) -totalreturn-ppnreturn) as total_sales 
											FROM  
												tb_invoice where tb_invoice.salescode='".$row['salescode']."' 
											AND 
												month(invoicedate)=".date('m',strtotime($_SESSION['period']))."
											And
												year(invoicedate)=".date('Y',strtotime($_SESSION['period'])));
				
					$rowcur = mysql_fetch_array($rssales);
					$total_sales = $rowcur['total_sales'];

					$rssales = read_write("select 
												sum(ppn_payment+payment) as total_pay 
											from 	
												tb_ttu, tb_invoice 
											where 
												tb_invoice.invoiceno=tb_ttu.invoiceno 	
											and
												tb_invoice.salescode='".$row['salescode']."' 
											and 
												month(invoicedate)=".date('m',strtotime($_SESSION['period']))."
											And
												year(invoicedate)=".date('Y',strtotime($_SESSION['period']))."
											and 
												month(ttudate)=".date('m',strtotime($_SESSION['period']))."
											And
												year(ttudate)=".date('Y',strtotime($_SESSION['period'])));
				
					$rowcur = mysql_fetch_array($rssales);
					
					$total_pay = $rowcur['total_pay'];
					$os_cur = $os_initial + $total_sales - $total_pay;
					$tot_os_cur = $tot_os_cur + $os_cur;
					
					/*$resinv = read_write("SELECT tb_invoice.invoiceno,((tax + totalsales ) - sum(ppn_payment + payment) - totalreturn-ppnreturn) AS outstanding 
						FROM 							
							tb_invoice 
						LEFT JOIN 
							tb_ttu 
						ON 
							tb_invoice.invoiceno = tb_ttu.invoiceno 
						WHERE 
						 
							tb_invoice.invoicedate <= '".$_SESSION['period']."' 
						AND
							ifnull( ttudate, '0000-00-00' )  <= '".$_SESSION['period']. "'
						and
							tb_invoice.salescode ='".$row['salescode']."' 						
						GROUP BY 
							tb_invoice.invoiceno 
						HAVING outstanding > 0 OR outstanding IS NULL");
					$n = 0;
					$q_where = "";
					while ($rwinv = mysql_fetch_array($resinv)) {
						if ($n==0) {
							$q_where = "tb_invoice.invoiceno='".$rwinv['invoiceno']."'";
						} else {
							$q_where = $q_where." or tb_invoice.invoiceno='".$rwinv['invoiceno']."'";
						}

						$n++;
					}

					$resinv2 = read_write("SELECT tb_invoice.invoiceno,((tax + totalsales ) - sum(ppn_payment + payment) - totalreturn-ppnreturn) AS outstanding 
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
							tb_invoice.invoicedate <= '".$_SESSION['period']."' 
						AND
							ifnull( ttudate, '0000-00-00' ) > '".$_SESSION['period']. "'
						and
							tb_invoice.salescode ='".$row['salescode']."' 						
						GROUP BY 
							tb_invoice.invoiceno 
						HAVING outstanding IS NOT NULL");

					while ($rwinv2 = mysql_fetch_array($resinv2)) {
							if ($n==0) {
								$q_where = "tb_invoice.invoiceno='".$rwinv2['invoiceno']."'";
							} else {
								$q_where = $q_where." or tb_invoice.invoiceno='".$rwinv2['invoiceno']."'";
							}

							$n++;
						}*/

					for ($j=0;$j<count($arrtotout);$j++) {
						
						switch ($j) {
							case 0:
								$s = 0;
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
						
						//it means no invoiceno that has outstanding
						//if ($q_where <> "") {
							$query = "select sum((tax + totalsales) -totalreturn-ppnreturn) as total_sales 
										from  tb_invoice where tb_invoice.salescode='".$row['salescode']."' and ";
							//and (".$q_where.") 
							if ($j<4) {
								$datequery = "to_days('".$_SESSION['period']."') - to_days(invoicedate)>=".$s." and to_days('".$_SESSION['period']."') - to_days(invoicedate)<=".$l;
								
							} else {
								$datequery = "to_days('".$_SESSION['period']."') - to_days(invoicedate)>".$s;
								
							}
							
							$query = $query.$datequery;
							$rssales = read_write($query);
							$rowcur = mysql_fetch_array($rssales);
							
							$arrsales = $rowcur['total_sales'];
							//$tot_arr_sales = $tot_arr_sales + $arrsales;
							//echo $query." = ".$arrsales."<br>";

							$query = "select 
											sum(ppn_payment+payment) as total_pay 
										from 
											tb_ttu, tb_invoice 
										where 
											tb_invoice.invoiceno=tb_ttu.invoiceno											
										and 
											tb_invoice.salescode='".$row['salescode']."' and "; 
										//and (".$q_where.")
							
							if ($j<4) {
								$datequeryttu = "to_days('".$_SESSION['period']."') - to_days(invoicedate)>=".$s." and to_days('".$_SESSION['period']."') - to_days(invoicedate)<=".$l;
							} else {
								$datequeryttu = "to_days('".$_SESSION['period']."') - to_days(invoicedate)>".$s;
							}
							$datequeryttu = $datequeryttu." and ttudate<='".$_SESSION['period']."'";
							$query = $query.$datequeryttu;

							$rssales = read_write($query);
							$rowcur = mysql_fetch_array($rssales);
							$arrpay = $rowcur['total_pay'];
							if ($arrpay=="") { $arrpay = 0; }

							//echo $query." = ".$arrpay."<br>";
							//$tot_arr_pay = $tot_arr_pay + $arrpay;

							$arrtotout[$j] = $arrsales-$arrpay;
							
							
					//	} else {
					//		$arrtotout[$j] = 0;	
					//	}
						$tot_arr_os[$j] = $tot_arr_os[$j] + $arrtotout[$j];
						
					}

					
					
					/*echo "<td>".number_format($os_initial,0)."</td>";
					echo "<td>".number_format($total_sales,0)."</td>";					
					echo "<td>".number_format($total_pay,0)."</td>";
					
					
					echo "<td>".number_format($os_cur,0)."</td>";*/

					for ($j=0;$j<count($arrtotout);$j++) {
						if ($arrtotout[$j]>0) {
							echo "<td>".number_format($arrtotout[$j],0)."</td>";
						} else {
							echo "<td>-</td>";
						}
					}
					echo "<td>&nbsp;</td></tr>";
					
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
					 
					if ($deliveryid <> $branch.".0000000") {
						$customer = stripslashes($row['companyname']);
						$cid = $row['companycode'];
						$customerid = $cid;
					} else {
						$customer = stripslashes($row['personname']);	
						
						$bid = $row['buyercode'];
						$customerid = $bid;
					}
					
					$newrow = 0;
					
					if ($i==0 || ($prevcustomerid <> $customerid)) {
						$newrow = 1;						
					}

					if ($newrow==1) { 
						if ($i>0) { echo "<tr><td height=10></td></tr>"; }
						echo "<tr class=small><td></td><td colspan=8>".$customer."</td></tr>";
					}
					$no++;
					echo "<tr class=small>";
					echo "<td>".$no."</td><td>".$invoicedate."</td><td>".$invno."</td>";
					
					for ($j=0;$j<=4;$j++) {
						if ($j == $idxoutpos) {
							echo "<td>".number_format($outstanding,0)."</td>";
						} else {
							echo "<td></td>";
						}
					}
					echo "<td>".$datediff." days</td>";
					echo "</tr>";
					
					
					$prevcustomerid = $customerid;
				}
				$i++;
				
			}
			if ($page == $max) {
				echo "<tr><td height=10></td></tr>";
				echo "<tr bgcolor=#e5ebf9 class=header>";
				if ($_SESSION['ver']==0) {
					echo "<td></td><td><b>Total:</b></td>";
					/*echo "<td>".number_format($tot_os_initial,0)."</td>";
					echo "<td>".number_format($tot_arr_sales,0)."</td>";
					echo "<td>".number_format($tot_arr_pay,0)."</td>";
					echo "<td>".number_format($tot_os_cur,0)."</td>";*/
					for ($j=0;$j<count($tot_arr_os);$j++) {
						if ($tot_arr_os[$j]>0) {
							echo "<td>".number_format($tot_arr_os[$j],0)."</td>";
						} else {
							echo "<td>-</td>";
						}
					}
					echo "</tr>";
				} else {
					echo "<td colspan=2></td><td><b>Total:</b></td>";
					$totalos =0;
					for ($j=0;$j<count($arrtotout);$j++) {
						
						echo "<td>";
						if ($arrtotout[$j]>0) {
							echo number_format($arrtotout[$j],0);
							$totalos = $totalos+$arrtotout[$j];
							
						} else {
							echo "";
						}
						echo "</td>";

					}
					echo "<td>".number_format($totalos,0)."</td></tr>";
				}
			}
		} ?>
		</table><br>
	<?php
		if ($max > 1) { ?>
		<table border=0 cellspacing=0 cellpadding=2 width="100%">			
				<tr>
					<td align=center><font class="small"><a href="outstanding.php" class="smalllink">Home</a> &nbsp;&nbsp;  Page: </font>&nbsp;
						<?php
							
							if ($page > 1)
							{
						?>
						<a href="outstanding.php?page=1&search=1" class="smalllink">[First]</a> 
						<a href="outstanding.php?page=<?php echo ($page-1); ?>&search=1" class="smalllink">[Prev]</a> 
						<?php
							}
							for ($i=1;$i<=$max;$i++)
							{
								if ($i == $page)
								{
						?>
						<font class="small">[<?php echo ($i); ?>]</font> 
						<?php
								}
								else
								{
						?>
						<a href="outstanding.php?page=<?php echo $i; ?>&search=1" class="smalllink">[<?php echo ($i); ?>]</a> 
						<?php
								}
							}
							if ($page < $max)
							{
						?>
						<a href="outstanding.php?page=<?php echo ($page+1); ?>&search=1" class="smalllink">[Next]</a> 
						<a href="outstanding.php?page=<?php echo $max; ?>&search=1" class="smalllink">[Last]</a> 
						<?php
							}
						?>
					</td>
				</tr>
			</table>
<?php
		} else {
		
			echo "<table border=0 align=center><tr class=small><td><center><a href=outstanding.php class=smalllink>Home</a></center></td></tr></table>";
		}
	}	?>
	</body>
</html>