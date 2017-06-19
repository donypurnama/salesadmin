<?php 
include('../constant.php'); 
include('../database.php'); 
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$bid = $_GET['bid'];
	$rs = read_write("select * from tb_buyer where buyercode='".$bid."'");
	$row = mysql_fetch_array($rs);
?>
<html>
	<head>
		<title>Personal Information</title>
			<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
</head>
<body>
<?php include('../menu.php');?><br>
<center><b class="big"><?php echo $row['personname']; ?></b></center>
<br>
<table width=550 cellpadding=0>
<tr class=small>
<td><b>Address:</b></td>
<?php
$address = trim($row['street']);
if (trim($row['city']) <> '') {
	$address = $address.", ".trim($row['city']);
}
?>
<td><?php echo $address; ?></td>
</tr>
<tr class=small>
<td><b>Phone:</b></td>
<?php
$phone = trim($row['phone']);
if (trim($row['mobilephone'])<>"") {
	$phone = $phone.", ".trim($row['mobilephone']);
}?>
<td><?php echo $phone; ?></td>
</tr>
<?php
if (trim($row['email'])<>'') { ?>
<tr class=small>
<td><b>Email:</b></td>
<td><?php echo $row['email']; ?></td>
</tr>
<?php } 
if (trim($row['birthday'])<>'' && trim($row['birthday'])<>'0000-00-00') { ?>
<tr class=small>
<td><b>Birthday:</b></td>
<td><?php echo $row['birthday']; ?></td>
</tr>
<?php } 
if (trim($row['hobby'])<>'') { ?>
<tr class=small>
<td><b>Hobby:</b></td>
<td><?php echo $row['hobby']; ?></td>
</tr>
<?php } ?>
</table><br>

<b>Sales Record(s)</b><br>
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr class=header bgcolor=#e5ebf9>
<td><b>Invoice</b></td><td><b>Date</b></td><td><b>Salesman</b></td><td><b>Product</b></td><td><b>Qty</b></td><td><b>Price/Unit</b></td><td><b>Sub Total</b></td>
</tr>
<?php
	$rsinv = read_write("select tb_invoice.invoiceno, tb_invoice.invoicedate, tb_invoice.buyercode, tb_salesman.salesname, (totalsales - totalreturn) as totalsales, tb_salesman.salescode, tb_company.companyname, tb_company.companycode
						FROM tb_invoice, 
								tb_company, 
								tb_buyer, 
								tb_deliveryaddr, 
								tb_salesman
						WHERE 
							tb_invoice.buyercode = tb_buyer.buyercode
						AND 
							tb_company.companycode = tb_deliveryaddr.companycode
						AND 
							tb_invoice.salescode = tb_salesman.salescode
						AND 
							tb_deliveryaddr.deliverycode = tb_buyer.deliverycode
						AND
							tb_buyer.buyercode = '".$bid."' order by tb_invoice.invoicedate desc");
	
	$total_buying = 0;
	while ($rowinv = mysql_fetch_array($rsinv)) {
		$invoiceno = $rowinv['invoiceno'];
		$rsitems = read_write("select tb_product.productname, (tb_invoiceitems.qty-tb_invoiceitems.qty_return) as qty, tb_product.unit, tb_invoiceitems.price, tb_product.volume * (tb_invoiceitems.qty-tb_invoiceitems.qty_return) * tb_invoiceitems.price as subtotal from tb_product, tb_invoiceitems where tb_product.productcode=tb_invoiceitems.productcode and invoiceno='".$invoiceno."'");

		echo "<tr class=small>";
		if ($rowinv['companyname']<>'') { 
			echo "<td><a href='sales.php?sid=".$invoiceno."&cid=".$rowinv['companycode']."&bid=".$rowinv['buyercode']."&p=0'  class=smalllink>".$invoiceno."</td>";
		} else {
			echo "<td><a href='sales.php?sid=".$invoiceno."&bid=".$rowinv['buyercode']."&p=1'  class=smalllink>".$invoiceno."</td>";
		}
			
		echo "<td>".date('j M Y', strtotime($rowinv['invoicedate']))."</td>";
		echo "<td><a href='salesreport.php?code=".$rowinv['salescode']."' class=smalllink>".$rowinv['salesname']."</a></td>";
		$item = 1;
		while ($rowitems = mysql_fetch_array($rsitems)) {
			if ($item>1) {
				echo "<tr class=small><td colspan=3></td>";
			}
			echo "<td>".$rowitems['productname']."</td>";
			echo "<td>".$rowitems['qty']." ".$rowitems['unit']."</td>";
			echo "<td>".number_format($rowitems['price'])."</td>";
			echo "<td>".number_format($rowitems['subtotal'])."</td>";
			echo "</tr>";
			
			$item++;
		}
		echo "<tr class=small>";
		echo "<td colspan=6 align=right>Total:</td>";
		echo "<td>".number_format($rowinv['totalsales'])."</td>";
		echo "</tr>";
		$total_buying = $total_buying + $rowinv['totalsales'];
	}
?>
</table>
<font class=small><b>Total Sales:</b> <?php echo number_format($total_buying); ?></font>
</body>
</html>