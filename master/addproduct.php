<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?

	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$productcode = $_GET['productcode'];
	$act 	   = $_GET['act'];
	$update    = $_GET['update'];
	$divisionid = $_GET['divisionid'];	
	
	
	
	if($update==1) {
		$productcode = $_POST['productcode'];
		$productname = mysql_real_escape_string($_POST['productname']);
		$description = mysql_real_escape_string($_POST['description']);
		$volume	     = $_POST['volume'];
		$pcs		 = $_POST['pcs'];
		$unit	     = $_POST['unit'];
			
		if($act==1) {
		read_write("INSERT INTO tb_product (
			productcode, 
			productname,
			volume,
			pcs,
			unit, 
			description,
			divisionid) 
						VALUES (
						'".$productcode."',
						'".$productname."', 
						'".$volume."',
						'".$pcs."',
						'".$unit."',
						'".$description."',
						'".$divisionid."'			
						)");
			
			Header("Location: product.php?divisionid=".$divisionid);
		} elseif ($act==2) {
		
		
		read_write("UPDATE tb_product SET 
							productcode='".$productcode."', 
							productname='".$productname."',
							volume='".$volume."', 	
							pcs='".$pcs."',
							unit='".$unit."',
							description='".$description."' 
									WHERE productcode='".$productcode."'");
		
			$message = "Database has been successfully updated ...";
		
		} elseif($act==3){
			$productcode = $_GET['productcode'];
			
			read_write("delete from tb_product where productcode='".$productcode."'");
			//echo "delete from tb_product where productcode='".$productcode."'";
			Header("Location: product.php?divisionid=".$divisionid);
			
		}
	}
	if ($productcode <> '') {
		$res = read_write("select * from tb_product where productcode='".$productcode."'");
		$row = mysql_fetch_array($res);
		$productcode = $row['productcode'];
		$productname = $row['productname'];
		$volume      = $row['volume'];
		$unit_dbase  = $row['unit'];
		$pcs_dbase	 = $row['pcs'];
		$description = $row['description'];
		$active 	 = $row['active'];
		
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<title>Add New Poduct</title>
		<script language = "javascript">
			function validate() {
				if (document.forms[0].productcode.value == "")	{
					alert("Please fill up the Product COde");
					return false;
				}
				else if(document.forms[0].productname.value == "") {
					alert("Please fill up the product name");
					return false;
				} else if (document.forms[0].volume.value == ""){
					alert("Please fill the volume");
					return false;
				} else if (document.forms[0].unit.value == "") {
					alert("Please fill the unit");
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
		?> 
		<br>
		
<form method="POST" onSubmit="return validate()" name="frmproduct" action="addproduct.php?update=1&act=<?php echo $act;?>&divisionid=<?php echo $divisionid;?>"  >
<table border="0" cellspacing="1" cellpadding="2" align="center" width="60%" bgcolor="#e5ebf9">
<tr bgcolor="white">
<td colspan="3" class="small"><div class="header icon-48-article"> 	
			<?php
			if ($act==1) {
				echo "<b class='big'>Add Product</b>";
			} else {
				echo "<b class='big'>Edit Product</b> ";
			} ?></div>
			
</td>
</tr>
<tr bgcolor="white"><td colspan="3" class="message" align="center"><b><?php echo $message; ?></b></td></tr>
<tr>
	<td  align="left" width="3%"><b></b></td>
	<td  align="left" width="10%" class=header>Product Code:</td>
	<td   width="45%">
	<?php 
	if ($act == 2) { 
			echo $productcode;
			echo '<input type="hidden" name="productcode" class=tBox value="'.$productcode.'">';
		} else {
	?>
		
	<input type="text" name="productcode" class=tBox value="<?php echo $productcode; ?>">
	<? } ?>
	</td>
</tr>
<tr bgcolor=white>
	<td  align="left" width="3%"><b></b></td>
	<td  align="left" width="10%" class=header>Product Name:</td>
	<td width="45%"><input type="text" name="productname" size="50"  class=tBox value="<?php echo get_escape_string($productname); ?>"></td>
	
</tr>
<tr >
	<td  align="left" width="3%"><b></b></td>
	<td  align="left" width="10%" class=header>Volume:</td>
	<td   width="45%"><input type="text" name="volume" class=tBox value="<?php echo $volume; ?>"></td>
	
</tr>
<tr bgcolor=white>
	<td  align="left" width="3%"><b></b></td>
	<td  align="left" width="10%" class=header>Pcs:</td>
	<td width="45%"><select name="pcs" class="forms">
		<option value="">--
		<?
		foreach($constant_pcs as $pcs)
			if($pcs_dbase <> $pcs ) {
			echo "<option value='$pcs'>".$pcs;	
			} else {
			echo "<option value='$pcs' selected> $pcs";	
			} 		
	?>	</select> </td>
	
</tr>
<tr>
	<td align="left" width="3%"><b></b></td>
	<td align="left" width="10%" class=header>Unit:</td>
	<td width="45%"><select name="unit" class="forms">
		<option value="">--
		<?
		foreach($constant_volume as $unit)
			if($unit_dbase <> $unit ) {
			echo "<option value='$unit'>".$unit;	
			} else {
			echo "<option value='$unit' selected> $unit";	
			} 		
	?>	</select> </td>
	
</tr>
<tr bgcolor=white >
	<td  align="left" width="3%"><b></b></td>
	<td  align="left" width="10%" class=header>Description:</td>
	<td   width="45%"><input type="text" name="description" size="50" class=tBox value="<?php echo get_escape_string($description); ?>"></td>
	
</tr>
<tr bgcolor=white>
	<td  align="left" width="3%"><b></b></td>
	<td  align="center" colspan=2>	
	<input type="submit" class="tBox" value="Save" <?php if ($_SESSION['groups'] <> 'root') { echo "disabled"; } ?> >&nbsp;
	<input type="button" value="Delete" <?php if ($_SESSION['groups'] <> 'root') { echo "disabled"; } ?> class="tBox" onclick="editproduct('<?php echo $productcode;?>', '<?php echo $divisionid; ?>' )">&nbsp;		
	<input type="button" value="Exit" class="tBox" onclick=javascript:location.href='product.php?divisionid=<?php echo $divisionid; ?>'>
	</td>	
</tr>
</table><br><br>
</body>
</html>
