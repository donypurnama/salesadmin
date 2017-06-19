<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$sid 	= $_GET['sid'];
	$bid 	= $_GET['bid'];
	$c 		= $_GET['c'];
	$p 		= $_GET['p'];
	$l 		= $_GET['l'];

	$update = $_GET['update'];
	$delete = $_GET['delete'];
	if ($update==1) {
		
		$cn_date 			= $_POST['cn_date'];
		$itemreceipt_date 	= $_POST['itemreceipt_date'];
		$itemreceiptcode	= $_POST['itemreceiptcode'];
		$discount 			= $_POST['discount'];
		$ppnreturn			= $_POST['taxret'];

		read_write("update tb_invoiceitems set rank_return=0, qty_return=0 where invoiceno='".$sid."'");
		$rescn = read_write("select cncode, invtax, tb_salesman.alias from tb_invoice, tb_salesman where tb_invoice.salescode=tb_salesman.salescode and tb_invoice.invoiceno = '".$sid."'");
		
		$rowcn = mysql_fetch_array($rescn);
		$rdotax = $rowcn['invtax'];
		if (is_array($_POST['chkid'])) {
			$totalreturn=0;
			$rankret = 1;
			for ($j=0;$j<count($_POST['chkid']);$j++) {
				$invitemid = $_POST['chkid'][$j];
				$qty_return = $_POST['qty_return'][$j];

				$res = read_write("select price, tb_product.volume from tb_invoiceitems, tb_product where invitemid=".$invitemid." and tb_invoiceitems.productcode=tb_product.productcode");
				$rowp = mysql_fetch_array($res);
				if ($rdotax==0) { //include
					$price = $rowp['price'] - (int) $rowp['price']/11;
				} else {
					$price = $rowp['price'];
				}
				$volume = $rowp['volume'];
				if ($discount=='' || $discount==0) {
					$totalreturn = $totalreturn + $price*$qty_return*$volume;
					
				}
				//echo $totalreturn.'<br>';
				$res = read_write("update tb_invoiceitems set 
										qty_return=".$qty_return.",
										rank_return=".$rankret." 
									where 
										invitemid=".$invitemid);	
				$rankret++;
			}	
			
		} else {
			$invitemid = $_POST['chkid'];
			if ($invitemid <> "") {
				$qty_return = $_POST['qty_return'];
				$res = read_write("select price, tb_product.volume from tb_invoiceitems, tb_product where invitemid=".$invitemid." and tb_invoiceitems.productcode=tb_product.productcode");
				$rowp = mysql_fetch_array($res);
				if ($rdotax==0) { //include
					$price = $rowp['price'] - (int) $rowp['price']/11;
				} else {
					$price = $rowp['price'];
				}
				$volume = $rowp['volume'];
				if ($discount=='' || $discount==0) {
					$totalreturn = $totalreturn + $price*$qty_return*$volume;
					
				}
				
				$res = read_write("update tb_invoiceitems set qty_return=".$qty_return.", rank_return=1 where invitemid=".$invitemid);	
			}
		}
		
		if ($discount <> '' && $discount > 0) {
			$totalreturn = $_POST['totalreturn'];
			
		}
		
		
		if (trim($rowcn['cncode'])=="") {
			$rstemp = read_write("SELECT replace(cncode, 'CN".$branch.date("ym")."-','') as nbr FROM `tb_invoice` order by invoiceno desc limit 1");						
				$rowtemp = mysql_fetch_array($rstemp);
				$invtemp = (int) $rowtemp['nbr'];
				$invtemp++;
							
				if (strlen($invtemp)==1) {
					$invtemp = '00'.$invtemp;
				} elseif (strlen($invtemp)==2) {
					$invtemp = '0'.$invtemp;
				}
				$cncode = "CN".$branch.date("ym")."-".$invtemp."/".$rowcn['alias'];
		} else {
			$cncode = $rowcn['cncode'];
		}
		
		read_write("update tb_invoice set
						cncode='".$cncode."',
						cn_date='".$cn_date."', 
						itemreceipt_date='".$itemreceipt_date."', 
						itemreceiptcode='".$itemreceiptcode."',
						totalreturn=".$totalreturn.", 
						ppnreturn=".$ppnreturn." 
					where invoiceno='".$sid."'");
			
				
		$error = "Database has been successfully updated ...";
		
		
	}
	
	if ($delete==1) {
		read_write("update tb_invoiceitems set qty_return=0 where invoiceno='".$sid."'");
		read_write("update tb_invoice set totalreturn=0, cncode=null, cn_date=null, itemreceipt_date=null, itemreceiptcode=null, ppnreturn=0 where invoiceno='".$sid."'");

		Header("location: sales.php?sid=".$sid."&bid=".$bid."&c=".$c."&p=".$p);
	}
	$res = read_write("select *  from tb_invoice where invoiceno = '".$sid."'");
	$row = mysql_fetch_array($res);
	$cn_date 			= $row['cn_date'];
	$cncode				= $row['cncode'];
	$itemreceiptcode	= $row['itemreceiptcode'];
	$itemreceipt_date 	= $row['itemreceipt_date'];		
	$invno 				= $row['invoiceno'];
	$ppn 				= $row['tax'];
	$discount 			= $row['discount'];
	$totalsales 		= $row['totalsales'];

	$totalreturn 		= $row['totalreturn'];
	$ppnreturn 			= $row['ppnreturn'];
	$rdotax				= $row['invtax'];
	$currency 			= $row['currency'];
	
?>

<html>
	<head>
	<title>Return Form</title>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language="javascript" src="cal2.js">
			/*
			Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
			Script featured on/available at http://www.dynamicdrive.com/
			This notice must stay intact for use
			*/
		</script>
		<script language="javascript" src="cal_conf2.js"></script>
		<script language=javascript>
			function enabledisableqty(obj, row, cntprod) {
				var objqty;
				with (document.frmreturn) {
					if (cntprod==1) {
						objqty = qtyret;
					} else {
						objqty = qtyret[row];
					}
					if (obj.checked) {						
						objqty.disabled = false;
					} else {
						objqty.disabled = true;
					}
				}
			}

			function dodelete(sid,bid,c,p) {
				if (confirm("Are you sure to delete this nota credit?")) {
					location.href='return.php?delete=1&sid=' + sid + '&bid=' + bid + '&c=' + c + '&p=' + p;
				}
			}
		</script>
	</head>
	<body>
		<?php include('../menu.php'); ?><br><?
		if($l<>1) { ?>
			<div class="header icon-48-menumgr">
			<a href='sales.php?sid=<?php echo $sid; ?>&bid=<?php echo $bid;?>&c=<?php echo $c;?>&p=<?php echo $p;?>&l=<? echo $l;?>' class=smalllink>Informasi Order</a>|	Nota Credit	</div>
		<?} else { ?>
			<div class="header icon-48-menumgr">
			<a href='sales.php?sid=<?php echo $sid; ?>&bid=<?php echo $bid;?>&c=<?php echo $c;?>&p=<?php echo $p;?>&l=<? echo $l;?>' class=smalllink>Informasi Order</a>|	Nota Credit	</div>
		<?} ?>
			
		<form name="frmreturn" action="return.php?update=1&sid=<?php echo $sid; ?>&bid=<?php echo $bid;?>&c=<?php echo $c; ?>&p=<?php echo $p; ?>" method="POST">
		<input type=hidden name=discount value=<?php echo $discount;?>>
				
		<table border=0 cellspacing=1 cellpadding=1 width="40%" >
		<tr>
			<th colspan=2 class="small"><font color=#ff0000><?php echo $error; ?></font></th>
		</tr>
<?php
		if ($cncode <> "") { ?>
		<tr class="small">
			<td align="right" >CN Code:</td>
			<td><?php echo $cncode; ?></td>
		</tr>
<?php	} ?>
		<tr class="small">
			<td align="right" >Invoice No:</td>
			<td><?php echo $sid; ?></td>
		</tr>
		<tr class="small">
			<td align="right" >CN Date:</td>
			<td><input type="text" name="cn_date" value="<?php echo $cn_date; ?>" class="tBox" size=10 maxlength=10> 
			<a href="javascript:showCal('Calendar5')" class=smalllink>[Select Date]</a></td>
		</tr>

		<tr class="small">
			<td align="right" >Item Receipt Code:</td>
			<td><input type=text name=itemreceiptcode value='<?php echo $itemreceiptcode; ?>' class="tBox"></td>
		</tr>

		<tr class="small">
			<td align="right" >Item Receipt Date:</td>
			<td><input type="text" name="itemreceipt_date" value="<?php echo $itemreceipt_date; ?>" class="tBox" size=10 maxlength=10> 
			<a href="javascript:showCal('Calendar6')" class=smalllink>[Select Date]</a></td>
		</tr>
		</table>

		<table border="0" cellspacing="0" cellpadding="2" width="100%">
			<tr class=small>
				<th colspan=4 align=left>Return List</th>				
			</tr>
			<tr bgcolor=#e5ebf9 class=header>
			<td width="3%">&nbsp;</td>
			<td width="15%"><b>Product Name</b></td>
			<td width="20%"><b>Return Qty</b></td>
			<td><b>Price</b></td>			
			</tr>
			
	 <?php
		
		if ($invno<>"") {
			$res = read_write("select 
				tb_product.productname, 
				tb_invoiceitems.qty, 
				tb_invoiceitems.qty_return, 
				tb_invoiceitems.price, 
				tb_invoiceitems.invitemid, 
				tb_product.productcode 
			from 
				tb_product, 
				tb_invoiceitems 
			where 
				tb_product.productcode = tb_invoiceitems.productcode 
			and tb_invoiceitems.invoiceno='".$sid."'");
			$cntprod =mysql_num_rows($res);
			if ($cntprod>0) {
				$i=0;
				while ($row = mysql_fetch_array($res)) {
					$qty_return = $row['qty_return'];
					echo "<tr class=small>";
					if ($cntprod>1) {
						echo "<td><input type=checkbox name=chkid[] value=".$row['invitemid']." onclick=\"javascript:enabledisableqty(this,".$i.",".$cntprod.")\" ";
						if ($qty_return<>"" && $qty_return>0) { echo "checked"; }
						echo "></td>";
					} else {
						echo "<td><input type=checkbox name=chkid value=".$row['invitemid']." onclick=\"javascript:enabledisableqty(this,".$i.",".$cntprod.")\" ";
						if ($qty_return<>"" && $qty_return>0) { echo "checked"; }
						echo "></td>";
					}
					echo "<td>".stripslashes($row['productname'])."</td>";
					echo "<td>";	
					
					if ($cntprod>1) {
						echo "<input id=qtyret type=text name=qty_return[] value='".$qty_return."' class=tBox size=3 ";
						if ($qty_return=="" || $qty_return==0) { echo "disabled=true"; }
						echo ">";	
					} else {
						echo "<input id=qtyret type=text name=qty_return value='".$qty_return."' class=tBox size=3 ";
						if ($qty_return=="" || $qty_return==0) { echo "disabled=true"; }
						echo ">";
					}
					echo " &nbsp; Max: ".$row['qty'];					
					echo "</td>";
					if ($rdotax==0) { //include
						echo "<td>".number_format($row['price'] - (int) $row['price']/11,0)."</td>";					
					} else {
						echo "<td>".number_format($row['price'],0)."</td>";		
					}
					echo "</tr>";
					$i++;
				} 			
				if ($discount<>'' && $discount >0) {
					
					echo "<tr class=small><td colspan=4>This invoice has a discount: ".$discount." %";
				}
				$noppnreturn=0;
				if ($totalreturn=='' || $totalreturn==0) {
					$noppnreturn=1;
					$ppnreturn = $ppn;
				}
				
				echo "<tr class=small><td colspan=4>PPn Return ".$currency." <input type=text name=taxret value='".$ppnreturn."'></td></tr>";
				if ($discount<>'' && $discount >0) {
					if ($totalreturn=='' || $totalreturn==0) {
						$totalreturn = $totalsales;
					}
					echo "<tr class=small><td colspan=4>Sales Return: &nbsp;<input type=text name=totalreturn class=tBox value='".$totalreturn."'></td></tr>";
				}
			} else {
				echo "<tr class=small><td colspan=5>== No product found ==</td></tr>";
			}
		} else {
			echo "<tr class=small><td colspan=5>== No product found ==</td></tr>";
		}
		?>
	</table><br>
<table border="0" cellspacing="1" cellpadding="1" width="40%" align="left">
		<tr>
			<td class="small" align=center><input type=submit value='Save' class=tBox> &nbsp;<input type=button value='Cancel' class=tBox>	
			&nbsp;<input type=button class=tBox value='Delete' onclick="javascript:dodelete('<?php echo $sid; ?>','<?php echo $bid; ?>','<?php echo $c; ?>','<?php echo $p; ?>')">
			</td>
		</tr>
</table>
</form>
</body></html>