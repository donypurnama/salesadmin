<?php 
include('../constant.php'); 
include('../database.php'); 
	if ($_SESSION['user'] == '')
	{
		Header('Location: ../index.php');
	}
	$companycode = $_GET['c'];	
	$cid = $_GET['cid'];	
	
	if ($companycode=="0") { 
		$companycode=""; 
	}
	$delete = $_GET['delete'];
	$update = $_GET['update'];
	$error = '';

	if ($delete == '1' && ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator'))
	{
		if ($branch <> '01' && $branch <> '00' && $branch <> '') {
			/***** INSERT COMPANYCODE TO TBDELCOMPANYCODE  *******/
				$res = read_write("insert into tb_delcompany (companycode, last_update, sentstatus) VALUES ('".$companycode."','".$today."', 0)");
				echo "insert into tb_delcompany (companycode, last_update, sentstatus) VALUES ('".$companycode."','".$today."', 0)";echo "<br>";
			/*----------------------------------------------------*/
		}
		
		$res = read_write("select deliverycode from tb_deliveryaddr where companycode='".$companycode."'");
		
		$i =0;
		while ($row=mysql_fetch_array($res)) {
			if ($i==0) {
				$strdelivery = "deliverycode='".$row['deliverycode']."'";
			} else {				
				$strdelivery = $strdelivery." or deliverycode='".$row['deliverycode']."'";
			}
			/***** INSERT BUYER TO TBDELDELIVERYADDR   *******/ 
			if($branch <> '01' && $branch <> '00' && $branch <> '') {
			read_write("insert into tb_deldeliveryadd(deliverycode, last_update, sentstatus) values ('".$row['deliverycode']."', '".$today."', 0)");
			}
			$i++;	
		}
		
		$res = read_write("select buyercode from tb_buyer where ".$strdelivery);
		$i =0;
		while ($row=mysql_fetch_array($res)) {
			if ($i==0) {
				$strbuyer = "buyercode='".$row['buyercode']."'";
			} else {				
				$strbuyer = $strbuyer." or buyercode='".$row['buyercode']."'";
				/***** INSERT BUYER TO TBDELBUYER  *******/ 
				if ($branch <> '01' && $branch <> '00' && $branch <> '') {
				read_write("insert into tb_delbuyer(buyercode, last_update, sentstatus) VALUES ('".$row['buyercode']."', '".$today."',0) ");	
				}
			}
			$i++;	
		}
		
		$res = read_write("select invoiceno from tb_invoice where ".$strbuyer);		
		$i =0;
		while ($row=mysql_fetch_array($res)) {
			if ($i==0) {
				$strinvoice = "invoiceno = '".$row['invoiceno']."'";
			} else {
				$strinvoice = $strinvoice." or invoiceno = '".$row['invoiceno']."'";
				/***** INSERT INVOICE TO TBDELINVOICE  *******/ 
				if ($branch <> '01' && $branch <> '00' && $branch <> '') {
				read_write("INSERT INTO tb_delinvoice(invoiceno, last_update, sentstatus) VALUES ('".$row['invoiceno']."', '".$today."',0) ");	
				}
			}
			$i++;							
		}
		
		$res = read_write("delete from tb_invoiceitems where ".$strinvoice);
		$res = read_write("delete from tb_invoice where ".$strinvoice);
		$res = read_write("delete from tb_buyer where ".$strbuyer);
		$res = read_write("delete from tb_deliveryaddr where ".$strdelivery);
		$res = read_write("delete from tb_company where companycode = '".$companycode."'");
	
		Header("Location: index.php");
	}

	if ($update == 1)
	{
		$name = mysql_real_escape_string($_POST['name']);
		$building = mysql_real_escape_string($_POST['building']);
		$addr = mysql_real_escape_string($_POST['addr']);
		$region = mysql_real_escape_string($_POST['region']);
		$city = $_POST['city'];
		$postal = $_POST['postal'];
		$phone1 = $_POST['phone1'];
		$phone2 = $_POST['phone2'];
		$fax    = $_POST['fax'];
		$email  = $_POST['email'];
		$hp 	= $_POST['hp'];
		$npwp   = $_POST['npwp'];
		
		
		if ($name == '')
		{
			$error = 'Company name must not be empty';
		}
		else
		{
			if ($companycode <> "")
			{
				$res = read_write("update tb_company set 
										companyname='".$name."',
										building='".$building."',
										street='".$addr."',
										region='".$region."',
										city='".$city."',
										postal='".$postal."',
										phone1='".$phone1."',
										phone2='".$phone2."',
										fax='".$fax."',
										email='".$email."',
										homepage='".$hp."',
										npwp='".$npwp."',
										last_update='".$today."',
										sentstatus=0
									where 
										companycode = '".$companycode."'");
			}
			else
			{
					
			//AUTO NUMBERING FOR CODE
			$rstemp = read_write("select replace(companycode, '".$branch.".','') as nbr 
										FROM `tb_company` where companycode like '".$branch.".%'
										ORDER BY companycode DESC LIMIT 1");
			$rowtemp = mysql_fetch_array($rstemp);
			$invtemp = (int) $rowtemp['nbr'];
			$invtemp++;
				
			if (strlen($invtemp)==6) {
					$invtemp = '0'.$invtemp;
			} elseif (strlen($invtemp)==5) {
					$invtemp = '00'.$invtemp;
			} elseif (strlen($invtemp)==4) {
					$invtemp = '000'.$invtemp;
			} elseif (strlen($invtemp)==3) {
					$invtemp = '0000'.$invtemp;
			} elseif (strlen($invtemp)==2) {
					$invtemp = '00000'.$invtemp;
			} elseif (strlen($invtemp)==1) {
					$invtemp = '000000'.$invtemp;
			}
				$companycode = $branch.".".$invtemp;
			
			$res = read_write("insert into tb_company (
									companycode, 
									companyname, 
									building, 
									street, 
									region,
									city, 
									postal, 
									phone1, 
									phone2, 
									fax, 
									email, 
									homepage, 
									npwp,
									last_update,
									sentstatus
									) 
											values (
										'".$companycode."',
										'".$name."',
										'".$building."',
										'".$addr."',
										'".$region."',
										'".$city."',
										'".$postal."',
										'".$phone1."',
										'".$phone2."',
										'".$fax."',
										'".$email."',
										'".$hp."', 
										'".$npwp."',
										'".$today."',
										0
										)");
								
				$d_addr = $_POST['d_addr'];
				$d_building = $_POST['d_building'];
				$d_region = $_POST['d_region'];
				$d_city = $_POST['d_city'];
				$d_postal = $_POST['d_postal'];
				$d_phone = $_POST['d_phone'];
				
				$rstemp = read_write("select replace(deliverycode, '".$branch.".','') as nbr 
										FROM `tb_deliveryaddr` where deliverycode like '".$branch.".%'
										ORDER BY deliverycode DESC LIMIT 1");
				$rowtemp = mysql_fetch_array($rstemp);
				$invtemp = (int) $rowtemp['nbr'];
				$invtemp++;
				if (strlen($invtemp)==6) {
						$invtemp = '0'.$invtemp;
				} elseif (strlen($invtemp)==5) {
						$invtemp = '00'.$invtemp;
				} elseif (strlen($invtemp)==4) {
						$invtemp = '000'.$invtemp;
				} elseif (strlen($invtemp)==3) {
						$invtemp = '0000'.$invtemp;
				} elseif (strlen($invtemp)==2) {
						$invtemp = '00000'.$invtemp;
				} elseif (strlen($invtemp)==1) {
						$invtemp = '000000'.$invtemp;
				}
					$deliverycode = $branch.".".$invtemp;
				
				$res = read_write("insert into tb_deliveryaddr (
										deliverycode, 
										companycode, 
										street, 
										building, 
										region, 
										city, 
										postal, 
										phone, 
										sentstatus
										) 
									values (
										'".$deliverycode."',
										'".$companycode."',
										'".$d_addr."',
										'".$d_building."',
										'".$d_region."',
										'".$d_city."',
										'".$d_postal."',
										'".$d_phone."',
										0)");
								
				$personname = $_POST['personname'];
				
				$rstemp = read_write("select replace(buyercode, '".$branch.".','') as nbr 
										FROM `tb_buyer` where buyercode like '".$branch.".%'
										ORDER BY buyercode DESC LIMIT 1");
				$rowtemp = mysql_fetch_array($rstemp);
				$invtemp = (int) $rowtemp['nbr'];
				$invtemp++;
					
				if (strlen($invtemp)==6) {
						$invtemp = '0'.$invtemp;
				} elseif (strlen($invtemp)==5) {
						$invtemp = '00'.$invtemp;
				} elseif (strlen($invtemp)==4) {
						$invtemp = '000'.$invtemp;
				} elseif (strlen($invtemp)==3) {
						$invtemp = '0000'.$invtemp;
				} elseif (strlen($invtemp)==2) {
						$invtemp = '00000'.$invtemp;
				} elseif (strlen($invtemp)==1) {
						$invtemp = '000000'.$invtemp;
				}
					$buyercode = $branch.".".$invtemp;
				$res = read_write("insert into tb_buyer (
										buyercode, 
										deliverycode, 
										personname,
										sentstatus) 
									values (
									'".$buyercode."',
									'".$deliverycode."',
									'".$personname."',
									0)");
				if ($_SESSION['sidcust'] <> "") { 
					Header("Location: sales.php?sid=".$_SESSION['sidcust']."&p=0&c=".$companycode."&bid=".$buyercode);
				} else {
					Header("Location: sales.php?url=index.php&add=1&c=".$companycode."&bid=".$buyercode."&p=0");
				}
			}
			$error = "Database has been successfully updated ...";
		}
	}
	else
	{
		if ($companycode <> "")
		{
			$res = read_write("select * from tb_company where companycode = '".$companycode."'");
			$row = mysql_fetch_array($res);
			
			$name = stripslashes($row['companyname']);
			$building = stripslashes($row['building']);
			$addr = stripslashes($row['street']);
			$region = stripslashes($row['region']);
			$city = stripslashes($row['city']);
			$postal = stripslashes($row['postal']);
			$phone1 = stripslashes($row['phone1']);
			$phone2 = stripslashes($row['phone2']);
			$fax = stripslashes($row['fax']);
			$email 	= stripslashes($row['email']);
			$hp 	= stripslashes($row['homepage']);
			$npwp  	= stripslashes($row['npwp']);
		}
		else
		{	
			$companycode = '';
			$name = '';
			$building = '';
			$addr = '';
			$region = '';
			$city = '';
			$postal = '';
			$phone1 = '';
			$phone2 = '';
			$fax = '';
			$email = '';
			$hp = '';
			$npwp = '';
		}
	}
	
?>
<html>
	<head>
		<title>Edit Corporate</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language="javascript">
			function validate()
			{
				if (document.forms[0].name.value == "")
				{
					alert("Please fill up the Company Name");
					return false;
				} 
				
				if (document.forms[0].addr.value == "")
				{
					alert("Please fill up the Company Street");
					return false;
				} 

				if (typeof(document.forms[0].d_addr)!=='undefined') {
					if (document.forms[0].d_addr.value == "") {
						alert("Please fill delivery address (street)");
						return false;
					}
				} 
				if (typeof(document.forms[0].personname)!=='undefined') {
					if (document.forms[0].personname.value == "") {
						alert("Please fill Person Name");
						return false;
					}
				}
				return true;
				
			}

			function insertdeliveryaddr() {
				with (document.forms[0]) {
					d_building.value = building.value;
					d_addr.value = addr.value;
					d_region.value = region.value;
					d_city.value = city.value;
					d_phone.value = phone1.value;
					d_postal.value = postal.value;
				}
			}

			function confirmdeletecompany(cid) {
				if (confirm("Deleting the company will delete its delivery address, buyer, invoice and items.\nAre you sure?")) {
					if (confirm("Are you really sure?")) {
						location.href='corporate.php?c='+cid+'&delete=1';
					}
				}
			}
		</script>
	</head>
	<body>
		<?php include('../menu.php'); ?><br>
		<center><font class="big"><b>Company Information</b></font></center>
		<form action="corporate.php?c=<?php echo $companycode; ?>&update=1" method="POST" onSubmit="return validate()">
		
			<table border=0 cellspacing=1 cellpadding=1 width="50%">
				<tr>
					<th colspan=2 class="small"><font color=#ff0000><?php echo $error; ?></font></th>
				</tr>
				<?php if($companycode <> "") {
							echo "<tr>
									<td align='right' class='small'>Company Code</td>
									<td class='small'><a href='corpreport.php?cid=".$companycode."' class=smalllink>".$companycode."</td></tr>";
						} 
				?>
				<tr>
					<td align="right" class="small">Company Name</td>
					<td><input type="text" name="name" value="<?php echo get_escape_string($name); ?>" size=50 class="tBox"></td>
				</tr>
				<tr>
					<td align="right" class="small">Building</td>
					<td><input type="text" class="tBox" name="building" value="<?php echo get_escape_string($building); ?>" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Street</td>
					<td><input type="text" class="tBox" name="addr" value="<?php echo get_escape_string($addr); ?>" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Region</td>
					<td><input type="text" class="tBox" name="region" value="<?php echo get_escape_string($region); ?>" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">City</td>
					<td><input type="text" class="tBox" name="city" value="<?php echo $city; ?>" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Postal Code</td>
					<td><input type="text" class="tBox" name="postal" value="<?php echo $postal; ?>" size=6></td>
				</tr>
				<tr>
					<td align="right" class="small">Office Phone 1</td>
					<td><input type="text" class="tBox" name="phone1" value="<?php echo $phone1; ?>" size=20></td>
				</tr>
				<tr>
					<td align="right" class="small">Office Phone 2</td>
					<td><input type="text" class="tBox" name="phone2" value="<?php echo $phone2; ?>" size=20></td>
				</tr>
				<tr>
					<td align="right" class="small">Fax</td>
					<td><input type="text" class="tBox" name="fax" value="<?php echo $fax; ?>" size=20></td>
				</tr>
				<tr>
					<td align="right" class="small">NPWP </td>
					<td>
					<input type="text" name="npwp" value="<?php echo $npwp; ?>" class="tBox" size=20 maxlength=20>
				</tr>
				<tr>
					<td align="right" class="small">E-Mail</td>
					<td><input type="text" class="tBox" name="email" value="<?php echo $email; ?>" size=50 maxlength=100></td>
				</tr>
				<tr>
					<td align="right" class="small">Home Page</td>
					<td><input type="text" class="tBox" name="hp" value="<?php echo $hp; ?>" size=50 maxlength=200></td>
				</tr>				
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				
			</table>
<?php if ($companycode <> "") { ?>
			<table border=0 cellspacing=1 cellpadding=1 width="50%">
				<tr>
					<td colspan=2 align=center>
						<input type="submit" value="Save" class="tBox">
						<input type="reset" value="Reset" class="tBox">
						<input type="button" value="Back" class="tBox" onclick="location.href='<?php echo $_SESSION['backurl'];?>'">
						<?php
							if ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator')
							{
						?>
						<input type="button" value="Delete" class="tBox" onclick="confirmdeletecompany('<?php echo $companycode; ?>')">
						<?php } ?>
					</td>
				</tr>
		</table>
<br>
		<table width="100%" border=0 cellspacing=1 cellpadding=1>
			<tr  bgcolor=#e5ebf9 class=header>
				<th width="100%" colspan=2> Delivery Address List</th>
			</tr>
			
		<?php
			$res = read_write("select deliverycode,street,building,region,city from tb_deliveryaddr where companycode='".$companycode."'");
			$count = mysql_num_rows($res);
			if ($count > 0)
			{
				for ($i=0;$i<$count;$i++)
				{
					$row = mysql_fetch_array($res);
					echo "<tr><td width=1%>";
					echo "&nbsp;";				
					echo "</td>";
					echo "<td class='small'><a href='deliveryaddr.php?id=".stripslashes($row['deliverycode'])."&cid=".$companycode."' class=smalllink>";
					
					$address = trim($row['street']);
					if (trim($row['building'])<>"") {
						$address = trim($row['building']).", ".$address;
					}
					if (trim($row['region'])<>"") {
						$address = $address.", ".trim($row['region']);
					}
					if (trim($row['city'])<>"") {
						$address = $address.", ".trim($row['city']);
					}
					if (stripslashes($address) <> "") {
						echo stripslashes($address);
					} else {
						echo "No Address";
					}
					echo "</a></td></tr>";
				}
			} else{
				echo "<tr><th colspan=3 class='small'>No Delivery Address</th></tr>";
			}
		?>
		<tr><td height=6></td></tr>
		<tr><td colspan=2><input type=button value="Add New Delivery Address" class="tBox" onclick="location.href='deliveryaddr.php?cid=<?php echo $companycode; ?>'"></td></tr>
		</table>
		<?php } else { ?>
			<table border=0 cellspacing=1 cellpadding=1 width="100%">
				<tr>
					<th colspan=2 class="small" align=left>Delivery Address</th>
				</tr>
				<tr>
					<td colspan=2 class="small">
					<input type=checkbox name='chkdelivery' onclick='javascript:insertdeliveryaddr()'> Check this if delivery address is the same as official address</td>
				</tr>
				<tr>
					<td width=10% align="right" class="small">Building</td>
					<td><input type="text" class="tBox" name="d_building" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Street</td>
					<td><input type="text" class="tBox" name="d_addr" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Region</td>
					<td><input type="text" class="tBox" name="d_region" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">City</td>
					<td><input type="text" class="tBox" name="d_city" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Postal Code</td>
					<td><input type="text" class="tBox" name="d_postal" size=6 ></td>
				</tr>
				<tr>
					<td align="right" class="small">Delivery Phone</td>
					<td><input type="text" class="tBox" name="d_phone" size=20></td>
				</tr>
			</table><br>
			<table border=0 cellspacing=1 cellpadding=1 width="100%">
				<tr>
					<th colspan=2 class="small" align=left>Contact Person</th>
				</tr>
				<tr>
					<td width=10% align="right" class="small">Person Name</td>
					<td><input type="text" class="tBox" name="personname" size=50></td>
				</tr>
				
			</table>
		<?php } ?>
		<br>
		<?php
		if ($companycode =="") { ?>
		<table border=0 cellspacing=1 cellpadding=1 width="100%">
				<tr>
					<td colspan=2 align="center">
					
						<? if ($_SESSION['sidcust'] <> "") { ?>
						<input type="submit" value="Save & Change Customer" class="tBox">
						<? } else { ?>
						<input type="submit" value="Save" class="tBox">
						<? } ?>
						<input type="reset" value="Reset" class="tBox">
						
					</td>
				</tr>
		</table>
		<?php } ?>
	</form>
	</body>
</html>