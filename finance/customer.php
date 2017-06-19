<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	if ($_SESSION['user'] == '')
	{
		Header('Location: '.DOMAIN_NAME.'index.php');
	}
	$cid = $_GET['cid'];	
	$bid = $_GET['bid'];
	
	if ($cid <>'') {
		$res = read_write("select * from tb_company where companycode='".$cid."'");
		$row=mysql_fetch_array($res);
		
		$c_building = trim($row['building ']);
		$c_address = trim($row['street']);
		
		$c_address = $c_address.", ".$c_building." ".trim($row['city']);
		$customer = $row['companyname']."<br>".$c_address;
		
	} else {
		$res = read_write("select personname,street,city from tb_buyer where buyercode='".$bid."'");
		$row=mysql_fetch_array($res);
		$customer = $row['personname'];
		$p_address = trim($row['street']);
		
		$p_address = $p_address.", ".trim($row['city']);
		$customer = $customer."<br>".$p_address;
	}
	$strsql="select 
				ttuid, ttuno, tb_invoice.invoiceno, tax+totalsales as total_pay, 
				kurs, currency, payment, ppn_payment, ppnreturn+totalreturn as total_return, 
				tb_deliveryaddr.deliverycode, tb_buyer.personname, 
				tb_deliveryaddr.street as c_street, 
				tb_deliveryaddr.building as c_building, 
				tb_deliveryaddr.city 	as c_city, 
				invtax, discount 
			from 
				tb_company, tb_buyer, tb_deliveryaddr, tb_invoice 
			left join 
				tb_ttu 
			on 
				tb_invoice.invoiceno=tb_ttu.invoiceno 
			where 
				tb_invoice.buyercode=tb_buyer.buyercode 
			and 
				tb_company.companycode=tb_deliveryaddr.companycode 
			and 
				tb_deliveryaddr.deliverycode=tb_buyer.deliverycode ";
	if ($cid<>"") {
		$strsql = $strsql." and tb_company.companycode='".$cid."'";
	} else {
		$strsql = $strsql." and tb_buyer.buyercode='".$bid."'";
	}
	$strsql = $strsql." order by tb_invoice.invoiceno desc, ttuno";

	$rsinv = read_write($strsql);
	
?>
<html>
	<head>
	<title>Customer TTU Information</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">	
		</head>
<body>
<?php include('../menu.php'); ?><br>
<center><b class="big">Customer TTU Information</b></center><br>
<font class=small><b><?php echo $customer; ?></b></font>



<br><br>
<table border=0 cellspacing=0 cellpadding=2 width="100%">
<?php 
echo "<tr bgcolor=#e5ebf9 class=header><td width=13%><b>Invoice No.</b></td>";
	echo "<td width=20%><b>Invoice Price</b>";
	if ($cid<>"") {
		echo "<td><b>Contact Name</b></td><td><b>Delivery Address</b></td>";
	} else {
		echo "<td>&nbsp;</td>";
	}
	echo "</tr>";

	$previnvno="";
	$i = 1;
	$num_rows=mysql_num_rows($rsinv);

	while ($row = mysql_fetch_array($rsinv)) {
		$invno = $row['invoiceno'];
		$contact = $row['personname'];
		$ttuid = $row['ttuid'];
		$ttuno = $row['ttuno'];
		
		if ($invno <> $previnvno && $previnvno <> "") {	
		
		if ($total_ttu > 0) {
				echo "<tr class=small><td>&nbsp;</td><td><i>Total</i></td><td colspan=2>Rp. ".number_format($total_ttu,0)."</td></tr>";
				echo "<tr class=small><td><b>Outstanding</b></td><td>&nbsp;</td><td colspan=2>";

				
				if ($total_pay*$kurs-$total_ttu==0 ) {
					echo "-";
				} else {
					echo "Rp. ".number_format($total_pay*$kurs-$total_ttu,0);
				}
				echo "</td></tr>";
				
				if($total_pay*$kurs-$total_ttu > 0) {
					// ADD NEW TTU 
					$linkaddttu="(<a href='ttu.php?sid=".$row['invoiceno']."' class=smalllink >Add TTU</a>)"; 
					echo "<tr><td>".$linkaddttu."</td>";				
				}
				echo "</tr>";
		} 
		
		echo "<tr class=small><td colspan=4 height=2 bgcolor=#33CCFF></td></tr>";
		$firstcol=1;

		} else {
			if ($previnvno=='') {
				$firstcol=1;
			} else {
				$firstcol=0;
			}
		}
		$previnvno = $invno;
		
		if ($cid <> '') {
			$address = trim($row['c_street']);
			if (trim($row['c_building'])<>"") {
				$address = $address.", ".trim($row['c_building']);
			}
			$address = $address.", ".trim($row['c_city']);
		}
		if ($firstcol==1) { $total_ttu = 0; }
		if ($ttuid<>"") {
			$payment = $row['payment'];
			$ppn_payment = $row['ppn_payment'];
			$strppn = "";
			if ($ppn_payment <> '' && $ppn_payment > 0) {
				$strppn = " (PPN: ".number_format($ppn_payment,0).")";
			}	
			$total_ttu = $total_ttu + $payment + $ppn_payment;
		} else {
			$payment = "";
			$strppn = "";			
		}
		$total_pay=$row['total_pay'];
		$total_return = $row['total_return'];
		$currency=$row['currency'];
		$kurs=$row['kurs'];
		
		
		if ($firstcol==1) {
			echo "<tr class=small valign=top>";			
			echo "<td><a href='invoice.php?sid=".$row['invoiceno']."' class=smalllink>".$invno."</a></td>";
			echo "<td>";
			echo "Rp. ".number_format($kurs*$total_pay,0);
			if ($currency<>"Rp.") {					
				echo "<BR>(USD ".number_format($total_pay,0).". 1 USD=Rp. ".number_format($kurs,0).")";
			}
			echo "</td>";
			if ($cid <>"") {
			echo "<td>".$contact."</td>";
			echo "<td>".$address."</td>";
			}
			echo "</tr>";
			if ($total_return>0) {
				
				echo "<tr class=small><td>Nota Credit:</td><td colspan=2>Rp. ".number_format($total_return)."</td></tr>";
				$total_pay=$total_pay-$total_return;
			}
			echo "<tr><td height=4></td></tr>"; 
			
			if ($total_pay > 0) {
			
				echo "<tr class=small><td>&nbsp;</td><td><i>TTU No.</i></td><td><i>Value</i></td></tr>";
				echo "<tr class=small>";	
				echo "<td><b>TTU:</b> &nbsp</td>";
								
				
				if ($ttuid<>"") {
					echo "<td><a href='ttu.php?ttuid=".$ttuid."&sid=".$row['invoiceno']."' class=smalllink>".$ttuno."</a></td>";				
					echo "<td colspan=2>Rp. ".number_format($payment,0).$strppn."</td>";
				} else {
					echo "<td colspan=3>No Payment Yet &nbsp;";
					$linkaddttu="(<a href='ttu.php?sid=".$row['invoiceno']."' class=smalllink >Add TTU</a>)"; 
					echo $linkaddttu."</td>";
		
				}
				echo "</tr>";
			}
			
			
		} else {
			echo "<tr class=small>";
			echo "<td>&nbsp;</td><td><a href='ttu.php?ttuid=".$ttuid."&sid=".$row['invoiceno']."' class=smalllink>".$ttuno."</a></td>";
			echo "<td colspan=2>Rp. ".number_format($payment,0).$strppn."</td></tr>";
		}
		
		
		if ($i==$num_rows && $total_ttu > 0) {
			echo "<tr class=small><td>&nbsp;</td><td><i>Total</i></td><td colspan=2>Rp. ".number_format($total_ttu,0)."</td></tr>";
			echo "<tr class=small><td><b>Outstanding  </b></td><td>&nbsp;</td><td colspan=2>";
			
			//OUTSTANDING
			$outstanding2 = $total_pay*$kurs-$total_ttu;
			if ($outstanding2==0) {
				echo "-";
			} else {
				echo "Rp. ".number_format($outstanding2,0);
			}
			echo "</td></tr>";
			if($total_pay*$kurs-$total_ttu > 0) {
				// ADD NEW TTU 
				$linkaddttu="(<a href='ttu.php?sid=".$row['invoiceno']."' class=smalllink >Add TTU</a>)"; 
				echo "<td>".$linkaddttu."</td>";
				//echo "<td>&nbsp;</td>";
				}
			
			echo "<tr class=small><td colspan=4 height=2 bgcolor=#33CCFF></td></tr>";
			
		} elseif ($i==$num_rows && $total_ttu ==0) {
			echo "<tr class=small><td>&nbsp;</td>";
			$linkaddttu="(<a href='ttu.php?sid=".$row['invoiceno']."' class=smalllink >Add TTU</a>)"; 
			echo "<td>".$linkaddttu."</td></tr>";
		}
		
		echo "";
		$i++;
		
		
	}
?>
</table>
</body>
</html>
