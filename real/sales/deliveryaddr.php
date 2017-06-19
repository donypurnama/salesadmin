<?php 
include('../constant.php'); 
include('../database.php'); 
	if ($_SESSION['user'] == '')
	{
		Header('Location: ../index.php');
	}
	$id = $_GET['id'];	//deliverycode
	$cid = $_GET['cid'];	//companycode
	$update = $_GET['update'];
	$delete = $_GET['delete'];

	if ($update==1) {
		$building = mysql_real_escape_string($_POST['building']);
		$street = mysql_real_escape_string($_POST['street']);
		$region = mysql_real_escape_string($_POST['region']);
		$city = $_POST['city'];
		$postal = $_POST['postal'];
		$phone = $_POST['phone'];
		if ($id<>"") {
			$res = read_write("update tb_deliveryaddr set 
									building='".$building."', 
									street='".$street."', 
									region='".$region."', 
									city='".$city."', 
									postal='".$postal."', 
									phone='".$phone."',
									sentstatus=0	
								where deliverycode='".$id."'");	
								
								$error = "Update Success ...";
		} else {
		
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
								building, 
								street, 
								region, 
								city, 
								postal, 
								phone,
								sentstatus) 
							values (
							'".$deliverycode."',
							'".$cid."',
							'".$building."',
							'".$street."',
							'".$region."',
							'".$city."',
							'".$postal."',
							'".$phone."',
							0)");
			
			Header("Location: corporate.php?c=".$cid);
			
		
		}
	}
	
	
	if ($delete==1) {
		
		if ($branch <> '01' && $branch <> '00' && $branch <> '') {
			/*-------INSERT DELIVERYCODE TO TBDELDELIVERYCODE-----*/
			$res = read_write("INSERT INTO tb_deldeliveryaddr (deliverycode, last_update, sentstatus) VALUES ('".$id."','".$today."',0)");
			echo "INSERT INTO tb_deldeliveryaddr (deliverycode, last_update, sentstatus) VALUES ('".$id."','".$today."',0)";echo "<br>";
			/*----------------------------------------------------*/
		}
		/*-------DELETE TBDELIVERADDR ETC-----*/
		$res = read_write("select buyercode from tb_buyer where deliverycode = '".$id."'");
		$i = 0;
		while ($row=mysql_fetch_array($res)) {
			if($i==0) {
				$strbuyer = "buyercode = '".$row['buyercode']."'";
			} else {
				$strbuyer = $strbuyer. " or buyercode = '".$row['buyercode']."'";
			}
			/***** INSERT BUYER TO TBDELBUYER  *******/ 
			if($branch <> '01' && $branch <> '00' && $branch <> ''){
				read_write("insert into tb_delbuyer(buyercode, last_update, sentstatus) values ('".$row['buyercode']."', '".$today."', 0) ");
			}
		$i++;		
		}
		
		$res = read_write("select invoiceno from tb_invoice where ".$strbuyer);
		echo "select invoiceno from tb_invoice where ".$strbuyer;echo "<br>"; 
		$i=0;
		while ($row=mysql_fetch_array($res)) {
			if($i==0) {
				$strinvoice = "invoiceno = '".$row['invoiceno']."'";				
			} else {
				$strinvoice = $strinvoice." or invoiceno='".$row['invoiceno']."'";
			}
			/***** INSERT INVOICE TO TBDELINVOICE  *******/ 
			if ($branch <> '01' && $branch <> '00' && $branch <> '') {
				read_write("INSERT INTO tb_delinvoice(invoiceno, last_update, sentstatus) VALUES ('".$row['invoiceno']."', '".$today."',0) ");
			}
			$i++;
		}
		$res = read_write("delete from tb_invoiceitems where ".$strinvoice);
		$res = read_write("delete from tb_invoice where ".$strinvoice);	
		$res = read_write("delete from tb_buyer where ".$strbuyer);
		$res = read_write("delete from tb_deliveryaddr where deliverycode= ".$id);
		Header("Location: corporate.php?c=".$cid);
	}

	if ($id<>"") {
	$res = read_write("select building, street, region, city, postal, phone, companycode from tb_deliveryaddr where deliverycode='".$id."'");
	$row = mysql_fetch_array($res);
	$cid = $row['companycode'];
	$building = $row['building'];
	$street = $row['street'];
	$region = $row['region'];
	$city = $row['city'];
	$postal = $row['postal'];
	$phone = $row['phone'];
	}
	
	
?>
<html>
	<head>
		<title>Add/Edit Delivery</title>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language="javascript">
			function validate()
			{
				if (document.forms[0].street.value == "")
				{
					alert("Please fill up the street");
					return false;
				}
				else
				{
					return true;
				}
			}
			function confirmdeletedelivery(id,cid) {
				if (confirm("Deleting the delivery address will also delete its buyers and invoices.\n Are you sure?")) {
					if (confirm("Are you really sure?")) {
						location.href='deliveryaddr.php?id='+id+'&cid='+cid+'&delete=1';

					}
				}
			}
		</script>
	</head>
	<body>
		<?php include('../menu.php'); ?><br>
		<center><font class="big"><b>Delivery Information</b></font></center>
		<form action="deliveryaddr.php?id=<?php echo $id; ?>&cid=<?php echo $cid;?>&update=1" method="POST" onSubmit="return validate()">
		
			<table border=0 cellspacing=1 cellpadding=1 width="50%">
				<tr>
					<th colspan=2 class="small"><font color=#ff0000><?php echo $error; ?></font></th>
				</tr>				
				
				<tr>
					<td align="right" class="small">Building</td>
					<td><input type="text" class="tBox"  name="building" value="<?php echo get_escape_string($building); ?>" size=50 maxlength=100></td>
				</tr>
				<tr>
					<td align="right" class="small">Street</td>
					<td><input type="text" class="tBox"  name="street" value="<?php echo get_escape_string($street); ?>" size=50 maxlength=100></td>
				</tr>
				<tr>
					<td align="right" class="small">Region</td>
					<td><input type="text" class="tBox" name="region" value="<?php echo get_escape_string($region); ?>" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">City</td>
					<td><input type="text" class="tBox"  name="city" value="<?php echo $city; ?>" size=50 maxlength=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Postal Code</td>
					<td><input type="text" class="tBox"  name="postal" value="<?php echo $postal; ?>" size=6 maxlength=6></td>
				</tr>
				<tr>
					<td align="right" class="small">Phone</td>
					<td><input type="text" class="tBox"  name="phone" value="<?php echo $phone; ?>" size=50 maxlength=50></td>
				</tr>
				<table border=0 cellspacing=1 cellpadding=1 width="50%">
				<tr>
					<td colspan=2 align=center>
						<input type="submit" value="Save" class="tBox" >
						<input type="reset" value="Reset" class="tBox" >
						<?
if(isset($HTTP_REFERER)) {
	echo "&nbsp;";
} else { 
	//echo "<a href='javascript:history.back()'>Back I</a>";?>
<input type="button" value="Back" class="tBox" onclick="javascript:location.href='<?php echo $_SESSION['backurl'];?>'">
<?}?>
						
						<?php
							if ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator') {
						?>
						<input type="button" value="Delete" class="tBox"  onclick="confirmdeletedelivery('<?php echo $id; ?>', '<?php echo $cid; ?>')">
						<?php } ?>
					</td>
				</tr>
		</table>
<Br>
<?php
						
	if ($id<>"") { ?>
		<table style="width:330px" border=0 cellspacing=1 cellpadding=3>
			<tr>
				<th bgcolor=#e5ebf9 class=header> Contact List</th>
			</tr>
			
		<?php
			$res = read_write("select personname, buyercode from tb_buyer where deliverycode = '".$id."'");
			$count = mysql_num_rows($res);
			if ($count > 0)
			{
				for ($i=0;$i<$count;$i++)
				{
					$row = mysql_fetch_array($res);
					echo "<tr ><td class='small'><a href='personal.php?bid=".$row['buyercode']."&cid=".$cid."&deliverycode=".$id."' class=smalllink>";
					if ($row['personname'] <> "") {
						echo stripslashes($row['personname']);
					} else {
						echo "NoName";
					}
					echo "</a></td></tr>";
				}
			}
			else
			{
				echo "<tr><th class='small'>No Contact</th></tr>";
			}
		?>
		<tr><td height=6></td></tr>
		<tr><td colspan=2><input type=button value="Add New Contact" class="tBox" onclick="location.href='personal.php?c=<?php echo $cid;?>&deliverycode=<?php echo $id;?>'"></td></tr>
		</table>
<?php } ?>
		</form>
	</body>
</html>