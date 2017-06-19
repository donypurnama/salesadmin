<?php 
include ('menu.php');
include('constant.php'); ?>
<?php include('database.php'); ?>
<?php
	session_start();
	if ($_SESSION['user'] == '')
	{
		Header('Location: '.DOMAIN_NAME.'index.php');
	}
?>
<html>
	<head>
		<title>New Sales Order</title>
		<link rel="stylesheet" type="text/css" href="../style.css">
		<script language="javascript">
			function checkkey(event) {
				var b_company;
				if (event.keyCode==13) {
					if (document.forms[0].tbuyer.value=='') {
						alert('Please choose buyer type');
						return;
					} else if (document.forms[0].tbuyer.value=='company') {
						b_company=1;
					} else {
						b_company=0;
					}
					findsales(b_company);
				}
			}
			
			function checkclick() {
				var b_company;				
				if (document.forms[0].tbuyer.value=='') {
					alert('Please choose buyer type');
					return;
				} else if (document.forms[0].tbuyer.value=='company') {
					b_company=1;
				} else {
					b_company=0;
				}
				
				findsales(b_company);
				
			}

			function findsales(b_company) {

				if (b_company=='1') {
					if (document.forms[0].buyer_name.value=="") {
						alert('Please input company name');
					} else {
						document.forms[0].action = 'result.php?x=1&search=c';						
						document.forms[0].submit();
					}
				} else {
					if (document.forms[0].buyer_name.value=="") {
						alert('Please input contact name');
					} else {
						document.forms[0].action = 'result.php?x=1&search=p';
						document.forms[0].submit();
					}
				}

			}
		</script>
	</head>
	<body >
	
		<?php include('../menu.php'); ?>
		<center><b class="big">New Sales Order</b> | <a class=smalllink href='advancedsearch.php' >Advanced Search</a></center>
		<form method="POST">
		<table border=0 cellspacing=1 cellpadding=1 width="100%">
			<tr>
				<td>&nbsp;</td>
				<td colspan=3 class="small" valign="top"><b>Search Buyer</b></td>
			</tr>
			<tr>
				<td width="30%">&nbsp;</td>
				<td width="10%" class="small" align="right">
				<select name=tbuyer>
				<option value=''>---
				<option value='company'>Corporate
				<option value='personal'>Personal
				</select>
				</td>
				<td><input type="text" name="buyer_name" class="forms" size=40 maxlength=140 onKeyDown="checkkey(event)"> 
				&nbsp;<input type=button value="Find" name="btnfindbuyer" onClick="javascript:checkclick()" ></td>
			</tr>
			
			<tr>
				<td colspan=4>&nbsp;</td>
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
									tb_deliveryaddr.city 		AS c_city, 
									tb_buyer.street 			AS p_street, 
									tb_buyer.city 				AS p_city, 
									tb_invoice.validate 
								FROM 
									tb_invoice, 
									tb_company, 
									tb_buyer, 
									tb_deliveryaddr 
								WHERE 
									tb_invoice.buyercode=tb_buyer.buyercode
								AND
									tb_company.companycode=tb_deliveryaddr.companycode 
								AND 
									tb_deliveryaddr.deliverycode = tb_buyer.deliverycode
								And
									tb_invoice.invoiceno like 'FK".$branch."%'
								ORDER BY 
									tb_invoice.invoicedate DESC, tb_invoice.invoiceno DESC limit 20");


		?>
		<table border=0 cellspacing=0 cellpadding=2 width="100%">
		<tr bgcolor=#0099ff class=header><td><b>Invoice No.</b></td><td><b>Company Name</b></td><td><b>Contact Name</td><td><b>Delivery Address</b></td><td><b>Validate</b></td></tr>
		<? 
			$_SESSION['backurl'] = "index.php?";

			while ($row = mysql_fetch_array($rsinv)) {
			

			if ($row['deliverycode']<> $branch.'.0000000') {
				$address = trim($row['c_street']);
				if (trim($row['c_building'])<>"") {
					$address = $address.", ".trim($row['c_building']);
				}
				$address = $address.", ".trim($row['c_city']);
				$b_company=1;
			} else {
				$address = trim($row['p_street']);
				$address = $address.", ".trim($row['p_city']);
				$b_company=0;
			}
			
			$invno = $row['invoiceno'];
			if ($invno=="") { $invno="No Invoice";}
			
			if ($row['companycode']==$branch.'.0000000') {
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
				echo "<td></td>";
			}

			if ($b_company==1) {
				$url = 'personal.php?url=index.php&bid='.$row['buyercode'].'&c='.$row['companycode'];
			} else {
				$url = 'personal.php?url=index.php&bid='.$row['buyercode'];
			}
			echo "<td><a href='".$url."' class=smalllink>".stripslashes($row['personname'])."</a></td>";
			if ($b_company==1) {
				echo "<td><a href='deliveryaddr.php?id=".$row['deliverycode']."' class=smalllink>".stripslashes($address)."</a></td>";
			} else {
				echo "<td>".stripslashes($address)."</td>";
			}
			
			echo "<td align=center>";
			$validate = $row['validate'];
			if ($validate==1) {
				echo "<img src='sales/checklist.gif'>";
			} else {
				//echo "x";
			}
			echo "</td></tr>";
		}
		?>
		</table>
	</body>
</html>
<?php 
include('footer.html'); 
?>