<?php 
include('../constant.php'); 
include('../database.php'); 

$o = $_GET['o'];
if ($o == 1) {
	$otherproductname = $_POST['otherproductname'];
	$otherunit = $_POST['otherunit'];
	$res = read_write("select * from tb_product where productcode='".$otherunit."' order by productcode");
	$rspother = mysql_fetch_array($res);
}

$productcode = $_GET['productcode'];
$sid = $_GET['sid'];
$bid = $_GET['bid'];
$cid = $_GET['c'];
$p = $_GET['p'];
$divid = $_GET['divid'];
$currency = $_GET['currency'];

$iptfill = $_POST['iptfill'];
$invitemid = $_GET['invitemid'];


if ($iptfill == 1) {
	
	$qty = $_POST['qty'];
	$price = $_POST['price'];
	$priceusd = $_POST['priceusd'];
	$batchno = $_POST['batchno'];
	
	

	if ($invitemid == "") {
		$res = read_write("select max(rank) as mrank from tb_invoiceitems where invoiceno='".$sid."'");
		$row = mysql_fetch_array($res);
		$mrank = $row['mrank'];
		
		if($currency == 'Rp.'){
			$priceusd = 0;
		}
		
		if($o<>1) {
			$otherproductname = '';
		}
		
		
		if ($o == 1) {
			$otherproductname 	= $_POST['otherproductname'];
			$productcode 		= $_GET['productcode'];
		
				$res = read_write("insert into tb_invoiceitems (
									invoiceno, 
									productcode, 
									otherproductname,
									batchno,
									qty, 
									price, 
									priceusd,
									qty_return, 
									rank,
									rank_return) 
											values (
											'".$sid."',
											'".$productcode."',
											'".$otherproductname."',
											'".$batchno."',
											".$qty.",
											".$price.",
											".$priceusd.",
											0,
											".($mrank+1).",0)");
					
			$invitemid = mysql_insert_id();
			$o = 0;								
				
		} else {
		
			$res = read_write("insert into tb_invoiceitems (
							invoiceno, 
							productcode, 
							otherproductname,
							batchno,
							qty, 
							price, 
							priceusd,
							qty_return, 
							rank,
							rank_return) 
									values (
									'".$sid."',
									'".$productcode."',
									'".$otherproductname."',
									'".$batchno."',
									".$qty.",
									".$price.",
									".$priceusd.",
									0,
									".($mrank+1).",0)");
			
			$invitemid = mysql_insert_id();
		
		
		
		}
		
		$res = read_write("UPDATE tb_invoice SET 
								usercode		='".$_SESSION['user']."',
								last_update     ='".$today."',
								sentstatus 		= 0
							WHERE 
								tb_invoice.invoiceno = '".$sid."'");
		
	} else {
		if($currency == 'Rp.'){
			$priceusd = 0;
		}
		
		
		$res = read_write("update tb_invoiceitems 
							set 
							qty=".$qty.",
							batchno='".$batchno."',
							price=".$price.",
							priceusd=".$priceusd."
							where invitemid=".$invitemid);
		
		$res = read_write("UPDATE tb_invoice SET 
								usercode		='".$_SESSION['user']."',
								last_update     ='".$today."',
								sentstatus 		= 0
							WHERE 
								tb_invoice.invoiceno = '".$sid."'");
	
	
	}
	$message = "Update success ...";
	echo "<script language=javascript>";
	echo "window.opener.location.href='sales.php?sid=".$sid."&bid=".$bid."&c=".$cid."&p=".$p."&bcalcsales=1';";
	echo "</script>";
} else {
	if ($invitemid <> "") {
		$res = read_write("select * from tb_invoiceitems where invitemid=".$invitemid);
		$row = mysql_fetch_array($res);
		$qty = $row['qty'];
		$price = $row['price'];
		$priceusd = $row['priceusd'];
		$batchno = $row['batchno'];
	}
}

if($o==1) {
	$productcode = $rspother['productcode'];

}


$othername = read_write("select 
							* 
						from 
							tb_invoiceitems 
						where 
							productcode='".$productcode."' and invoiceno ='".$sid."'order by productcode");		
$resothername = mysql_fetch_array($othername);

					
$res = read_write("select 
						* 
					from 
						tb_product 
					where 
						productcode='".$productcode."' 	
					order by 
						productcode");
$rsp = mysql_fetch_array($res);		





?>

<html>
<head><title>Add Price</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
</head>
<body>
<form name=frmadd method=post action="product.php?&o=<?php echo $o; ?>&sid=<?php echo $sid;?>&bid=<?php echo $bid;?>&productcode=<?php echo $productcode;?>&invitemid=<?php echo $invitemid;?>&c=<?php echo $cid;?>&p=<?php echo trim($p);?>&currency=<? echo $currency;?>&divid=<?php echo $divid;?>">
<input type=hidden name=iptfill value=1>
<input type=hidden name="otherproductname" value="<? echo stripslashes($otherproductname); ?>">
<table border=0 cellspacing=1 cellpadding=1 width="100%" align=center>
<tr><td class=small colspan=2><font color=#ff0000><?php echo $message; ?></font></td></tr>
<tr class=small>
	<td width="25%">Product Name:</td>
	<td><?php 
	if($o==1){
		echo $otherproductname;
	} else {
		if ($rsp['productname'] == '') {
			echo $resothername['otherproductname'];
		} else {
			echo $rsp['productname']; 
		}
	}
	?></td>
</tr>
<tr>
	<td class=small>Volume:</td>
	<td class=small><?php 
	if ($o==1){
		echo $rspother['volume']."&nbsp;". $rspother['pcs'];
	} else {
		echo $rsp['volume']."&nbsp;".$rsp['pcs'];
	}
	?></td>
</tr>
<tr>
	<td class=small>Unit:</td>
	<td class=small><?php 
	if($o==1) {
		echo $rspother['unit'];
	} else {
		echo $rsp['unit']; 
	}
	?></td>
</tr>
<?php
	if (substr($qty, strlen($qty)-2)=="00") {
		$qty = number_format($qty,0);
	}
?>
<tr class=small><td>Qty Per Volume:</td><td><input type=text name="qty" value="<?php echo $qty; ?>" class="tBox" size=4 maxlength=4></td></tr>
<tr class=small><td>Price Per Unit (IDR):</td><td><input type=text name="price" value="<?php echo $price; ?>" class="tBox" size=8 maxlength=8></td></tr>
<? if ($currency == 'USD') { ?>
<tr class=small><td>Price Per Unit (USD):</td><td><input type=text name="priceusd" value="<?php echo $priceusd; ?>" class="tBox" size=8 maxlength=8></td></tr>
<?} 
?>
<tr class=small><td>Batch No.:</td><td><input type=text name="batchno" value="<?php echo $batchno; ?>" class="tBox" size=20 maxlength=20></td></tr>
<tr><td height=6></td></tr>
<tr><td colspan=2>
<input type="submit" value="Save" class="tBox"> &nbsp;
<input type="button" value="Finish" class="tBox" <?php if ($invitemid=="") { echo " disabled"; } ?> onclick="javascript:window.close()";> &nbsp;
<input type="button" value="Add More" class="tBox" <?php if ($invitemid=="") { echo " disabled"; }?> onclick="javascript:location.href='addproduct.php?sid=<?php echo $sid;?>&bid=<?php echo $bid;?>&divid=<?php echo $divid;?>&currency=<?php echo $currency;?>'">&nbsp;
<?
if(isset($HTTP_REFERER)) {
	echo "&nbsp;";
} else { 
	//echo "<a href='javascript:history.back()'>Back I</a>";
?>
<input type="button" value="Back" class="tBox" onclick="javascript:history.back(1)" <?php if ($invitemid<>"") { echo " disabled"; } ?>>
<?}?>

</td></tr>

</table>
</form>
</body></html>
