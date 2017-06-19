<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?

	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$productcode 	= $_GET['productcode'];
	$jumprod		= $_GET['jumprod'];
	$act 	   		= $_GET['act'];
	$update    		= $_GET['update'];
	$divisionid 	= $_GET['divisionid'];	
	
	
	
	if($update==1) 
	{
		$rdoinsert	= $_POST['rdoinsert'];
		
	
		for($i=1; $i<=$jumprod; $i++) 
		{
			$_SESSION['productcode'] = $_POST['productcode'.$i];
			$productname = mysql_real_escape_string($_POST['productname'.$i]);
			$volume	     = $_POST['volume'.$i];
			$pcs		 = $_POST['pcs'.$i];
			$unit	     = $_POST['unit'.$i];
			$description = mysql_real_escape_string($_POST['description'.$i]);
			
			
			$res = read_write("SELECT COUNT(productcode) as cnt from tb_product where productcode = '".$_SESSION['productcode']."'");
			$jum=mysql_fetch_row($res);
			if($jum[0] == 0) 
			{
				
		
				read_write("INSERT INTO tb_product (
					productcode, 
					productname,
					volume,
					pcs,
					unit, 
					description,
					divisionid) 
								VALUES (
								'".$_SESSION['productcode']."',
								'".$productname."', 
								'".$volume."',
								'".$pcs."',
								'".$unit."',
								'".$description."',
								'".$divisionid."'			
								)");
							
				
			} else {
				$_SESSION['productcode'] = '';
				$message = "Product Code has been use ...";
			}
		}
		
		if($rdoinsert == 1) {
			Header("Location: product.php?divisionid=".$divisionid); 
		} else {
			$_SESSION['productcode'] = '';
			Header("Location: insertproduct.php?jumprod=".$jumprod."&act=1&divisionid=".$divisionid); 			
		}
	}
	
	$jumprod=2;
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<title>Add New Poduct</title>
		<script language = "javascript">
			function validate() {
				if (document.forms[0].productcode1.value == "")	{
					alert("Please fill up the Product Code");
					return false;
				} else if (document.forms[0].productcode2.value == "") {
					alert("Please fill up the second Product Code");
					return false;
				} else if(document.forms[0].productname1.value == "") {
					alert("Please fill up the product name");
					return false;
				} else if (document.forms[0].productname2.value == "") {
					alert("Please fill up the second product name");					
					return false;
				} else if (document.forms[0].volume1.value == ""){
					alert("Please fill the volume");
					return false;
				} else if (document.forms[0].valume2.value == "") {
					alert ("Please fill up the second volume");
					return false;
				} else if (document.forms[0].unit1.value == "") {
					alert("Please fill the unit");
					return false;
				} else if (document.forms[0].unit2.value == "") {
					alert("Please fill up the second unit");
					return false;
				} else {
					return true;
				}
			}
			
			function editproduct(productcode, divisionid) {
				if (confirm("Are you sure to delete this product?")) {
					if (confirm("Are you really sure?")) {
						location.href='addproduct.php?update=1&act=3&productcode='+productcode+'&divisionid='+divisionid;

					}
				}
			}
		</script>
	</head>
<body>
<?php include('../menu.php'); 
		$rsproduct 	= read_write("select * from tb_product where productcode > 0 order by productname");
		$i = 0;
		$num_product = mysql_num_rows($rsproduct);
		while ($rwproduct = mysql_fetch_array($rsproduct)) {
			$arrproductname[$i] = $rwproduct['productname'];
			$arrvolume[$i]      = $rwproduct['volume'];
			$arrunit[$i]	    = $rwproduct['unit'];
			$arrpcs[$i]			= $rwproduct['pcs'];
			$arrdescription[$i] = $rwproduct['description'];
			$arrproductcode[$i] = $rwproduct['productcode'];
			$i++;
		}
		?><br>

<table border="0" cellspacing="1" cellpadding="2" align="center" width="60%" bgcolor="#e5ebf9">
	<tr bgcolor="white">
		<td class="small"><div class="header icon-48-article"> 	
		<?php
		if ($act==1) 
		{
			echo "<b class='big'>Add Product</b>";
		} 
			else 
		{
			echo "<b class='big'>Edit Product</b> ";
		} ?></div>
		</td>
	</tr>
	<tr bgcolor="white"><td class="message" align="center"><b><?php echo $message; ?></b></td></tr>
	<tr>
		<td bgcolor="#ffffff">
		<form method="post" onSubmit="return validate()" name="frmaddproduct" action="insertproduct.php?update=1&jumprod=<? echo $jumprod; ?>&divisionid=<?php echo $divisionid;?>">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		
			<?

			
			for($i=1; $i<=$jumprod; $i++) {
			
			
				echo '<tr bgcolor="#e5ebf9"> <td width="3%"><b></b></td>
						<td width="15%" class="header" >Product Code</td>
						<td width="2%" align="center" class="header">:</td>
						<td><input type="text" name=productcode'.$i.' class="tBox" value='.$_SESSION['productcode'].'></td></tr>';
				echo '<tr><td><b></b></td>
						<td class="header">Product Name</td>
						<td align="center" class="header">:</td>
						<td><input type="text" name=productname'.$i.' size="50" class="tBox"></td>
					</tr>';
				echo '<tr ><td><b></b></td>
						<td class="header">Volume</td>
						<td align="center" class="header">:</td>
						<td><input type="text" name=volume'.$i.' class="tBox"></tr>';
				echo '<tr><td><b></b></td>
						<td class="header">Pcs</td>
						<td align="center" class="header">:</td>
						<td><select name=pcs'.$i.' class="forms">
							<option value="">--';
			
							foreach($constant_pcs as $pcs)
								if($pcs_dbase <> $pcs ) {
									echo "<option value='$pcs'>".$pcs;	
								} else {
									echo "<option value='$pcs' selected> $pcs";	
								} 		
						echo '</select> </td></tr>';
				
				echo '<tr ><td><b></b></td>
						<td class="header">Unit</td>
						<td align="center" class="header">:</td>
						<td><select name=unit'.$i.' class="forms">
							<option value="">--';
							foreach($constant_volume as $unit)
							if($unit_dbase <> $unit ) {
								echo "<option value='$unit'>".$unit;	
							} else {
								echo "<option value='$unit' selected> $unit";	
							} 		
							echo '</select></td></tr>';
				
				echo '<tr><td><b></b></td>
						<td class="header">Description</td>
						<td class="header" align="center">:</td>
						<td><input type="text" name=description'.$i.' size="50" class="tBox"></td></tr>
				';
				echo '<tr><td colspan="4">&nbsp;</td></tr>';
			}
			
			?>
			<tr><td colspan="4"></td></tr>
			<tr>
				<td colspan="3" class="small" align="center">Insert A New Product <strong>AND</strong></td>
				<td class="small" align="left">
					<input type="radio" value=1 name=rdoinsert> Go back to previous page <br><br>
					<input type="radio" value=2 name=rdoinsert checked> Insert another new row
				</td>
			</tr>
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr>
				<td><b></b></td>
				<td></td>
				<td></td>
				<td>
					<input type="submit" value="Save" class="tBox">
					<input type="reset" class="tBox" value="Clear" onclick="location.href='../master/insertproduct.php?act=1&divisionid=<? echo $divisionid; ?>'">
					<input type="button" value="Exit" class="tBox" onclick=javascript:location.href='product.php?divisionid=<?php echo $divisionid; ?>'>
				</td>
			</tr>
		
		</table>
		</form>
		</td>
	</tr>
</table><br><br>
</body>
</html>


