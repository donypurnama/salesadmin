<?php include('../constant.php');?>
<?php include('../database.php');?>
<?php
	if ($_SESSION['user'] == '')
	{
		Header('Location: ../index.php');
	}
?>
<html>
<head>
	<title>Faktur Baru</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
	<script language="javascript">
	function validate() {
		with(document.frmsearch) {
			if (buyer_name.value== '' && salesman.value=='') {
				alert('Please fill something to search');
				return false;
			} else {
				return true;
			}				
		}
	}
	</script>
</head>
<body >
	<?php include('../menu.php'); 
 ?><br>
		<form method="POST" action="result.php?x=1" name=frmsearch onsubmit="return validate()" >
		<table border="0" cellspacing="1" cellpadding="1" width="100%">
		<tr>
			<td width="15%"><div class="header icon-48-menumgr">Faktur Baru</div></td>
			<td class="small"><b>Costumer Name:</b>&nbsp;&nbsp;
				<input type="text" name="buyer_name" class="tBox" size="40" maxlength=220 >
				<select class="tBox" name="salesman" style="width:150px;">
					<option value="" class="tBox">==Salesman==
					<?php
					$res = read_write("SELECT * FROM tb_salesman where salescode like '".$branch."%' order by salesname ASC");
					while ($row = mysql_fetch_array($res)) {
						echo "<option value='".$row['salescode']."'"; 
						echo ">".$row['salesname'];
					}
					?></select>
				<input type="submit" value="Search" class="tBox">
			</td>
		</tr>
		</table>
		</form>
		<?php
			$rsinv = read_write("SELECT 
									tb_invoice.invoiceno, 
									tb_buyer.buyercode,
									tb_buyer.deliverycode,
									tb_buyer.personname, 
									tb_company.companycode, 
									tb_company.companyname, 
									tb_deliveryaddr.street 		AS c_street,
									tb_deliveryaddr.building 	AS c_building, 
									tb_deliveryaddr.region 		AS c_region,
									tb_deliveryaddr.city 		AS c_city, 
									tb_buyer.street 			AS p_street, 
									tb_buyer.region 			AS p_region, 
									tb_buyer.city 				AS p_city, 
									tb_invoice.validate,
									tb_salesman.salesname,
									tb_division.divisionname
								FROM 
									tb_invoice, 
									tb_company, 
									tb_buyer, 
									tb_deliveryaddr,
									tb_salesman,
									tb_division,
									tb_commgroup
								WHERE 
									tb_invoice.buyercode=tb_buyer.buyercode
								AND
									tb_company.companycode=tb_deliveryaddr.companycode 
								AND 
									tb_deliveryaddr.deliverycode = tb_buyer.deliverycode
								And
									tb_invoice.salescode = tb_salesman.salescode
								and
									tb_invoice.commgroupcode = tb_commgroup.commgroupcode
								and 
									tb_commgroup.divisionid = tb_division.divisionid
								And
									tb_invoice.invoiceno like 'FK".$branch."%'
								ORDER BY 
									tb_invoice.invoicedate DESC, tb_invoice.invoiceno DESC limit 50");


									
?>

		<table border='0' cellspacing=0 cellpadding=2 width="100%">
		<tr class=small><td colspan="6"><b>Daftar 50 faktur terbaru</b></td></tr>
		<tr bgcolor=#e5ebf9 class=header>
		
		<td width='9%'><b>Invoice No.</b></td>
		<td><b>Customer Name</b></td>
		<td><b>Delivery Address</b></td>
		<td><b>Division</b></td>
		<td><b>Salesman</b></td>
		<td>&nbsp;</td>
		</tr>
		<? 
		$_SESSION['backurl'] = "index.php?";
		$_SESSION['sidcust'] = '';
		
		while ($row = mysql_fetch_array($rsinv)) {
			//krn 00 dan 01 bisa gabung customernya
			if ($row['deliverycode']<> $branch.'.0000000' && $row['deliverycode'] <> "01.0000000" && $row['deliverycode'] <> "00.0000000") 
			{  
			
				$address = trim($row['c_street']);
				if (trim($row['c_building'])<>"") {
					$address = trim($row['c_building']).", ".$address;
				}
				if (trim($row['c_region'])<>"") {
					$address = $address.", ".trim($row['c_region']);
				}
				if (trim($row['c_city'])<>"") {
					$address = $address.", ".trim($row['c_city']);
				}
				$b_company=1;
			} else {
				$address = trim($row['p_street']);
				if (trim($row['p_region'])<>"") {
					$address = $address.", ".trim($row['p_region']);
				}
				if (trim($row['p_city'])<>"") {
					$address = $address.", ".trim($row['p_city']);
				}
				$b_company=0;
			}
			
			$invno = $row['invoiceno'];
			if ($invno=="") { $invno="No Invoice";}
			if ($b_company<>1) {
				$p = '1';			
			} else {
				$p = '0';
			}
			echo "<tr class=small><td>
			<a href='sales.php?sid=".$row['invoiceno']."&bid=".$row['buyercode']."&c=".$row['companycode']."&p=".$p."' class=smalllink>".$invno."</a>
			</td>";
			if ($b_company==1) {
				echo "<td><a href='corporate.php?c=".$row['companycode']."' class=smalllink>".$row['companyname']."</a></td>";
			} else {
				$url = 'personal.php?url=index.php&bid='.$row['buyercode'];
				echo "<td><a href='".$url."' class=smalllink>".stripslashes($row['personname'])."</a></td>";
			}
			if ($b_company==1) {
				echo "<td><a href='deliveryaddr.php?id=".$row['deliverycode']."' class=smalllink>".stripslashes($address)."</a></td>";
			} else {
				echo "<td>".stripslashes($address)."</td>";
			}
			
			echo "<td>".$row['divisionname']."</td>";
			echo "<td>".$row['salesname']."</td>";
			echo "<td align=center>";
			$validate = $row['validate'];
			if ($validate==1) {
				echo "<img src='../templates/images/checklist.gif'>";
			} else {
			}
			echo "</td></tr>";
		}
		?>
		</table>
	</body>
</html>