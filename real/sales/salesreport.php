<?php 
include('../constant.php'); 
include('../database.php'); 
	
	if ($_SESSION['user'] == '')
	{
		Header('Location: '.DOMAIN_NAME.'index.php');
	}
	$salescode = $_GET['code'];
	$rs = read_write("select * from tb_salesman where salescode='".$salescode."'");
	$row = mysql_fetch_array($rs);
?>
<html>
	<head>
		<title>Salesman Information</title>
			<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
	</head>
	<body>
	<?php include('../menu.php');?><br>
	<center><b class="big"><?php echo $row['salesname']; ?></b></center>
	<br>
<table border=0 cellspacing=1 cellpadding=1 width=100%>
<tr class=header bgcolor=#e5ebf9>
<td><b>Invoice</b></td><td><b>Date</b></td><td><b>Customer</b></td><td><b>Product</b></td><td><b>Qty</b></td><td><b>Price/Unit</b></td><td><b>Sub Total</b></td>
</tr>
<?php

$rsinv = read_write("select tb_invoice.invoiceno, tb_invoice.invoicedate, tb_invoice.buyercode, tb_salesman.salesname, (totalsales - totalreturn) as totalsales, tb_salesman.salescode, tb_company.companyname, tb_buyer.personname, tb_company.companycode
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
							tb_salesman.salescode = '".$salescode."' order by tb_invoice.invoicedate desc");
while ($rowinv = mysql_fetch_array($rsinv)) {
	$invoiceno = $rowinv['invoiceno'];

	$rsitems = read_write("select tb_product.productname, (tb_invoiceitems.qty-tb_invoiceitems.qty_return) as qty, tb_product.unit, tb_invoiceitems.price, tb_product.volume * (tb_invoiceitems.qty-tb_invoiceitems.qty_return) * tb_invoiceitems.price as subtotal from tb_product, tb_invoiceitems where tb_product.productcode=tb_invoiceitems.productcode and invoiceno='".$invoiceno."'");

	if (trim($rowinv['companyname'])<>'') {
		
		$customer = "<a href='corpreport.php?cid=".$rowinv['companycode']."' class=smalllink>".trim($rowinv['companyname'])."</a>";
		$href = "sales.php?sid=".$invoiceno."&c=".$rowinv['companycode']."&bid=".$rowinv['buyercode']."&p=0";
	} else {
		
		$customer = "<a href='personreport.php?bid=".$rowinv['buyercode']."' class=smalllink>".trim($rowinv['personname'])."</a>";
		$href = "sales.php?sid=".$invoiceno."&bid=".$rowinv['buyercode']."&p=1";
	}

	echo "<tr class=small>";
	echo "<td><a href='".$href."' class=smalllink>".$invoiceno."</td>";
	echo "<td>".date('j M Y', strtotime($rowinv['invoicedate']))."</td>";
	
	echo "<td>".$customer."</td>";

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