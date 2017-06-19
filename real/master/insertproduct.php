<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?

	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
		
	$n				= $_POST['jumproduct']; //Baca Jumlah Data
	
	$add    		= $_GET['add'];
	$divisionid 	= $_GET['divisionid'];		
	
	if($add==1) 
	{
		$n = $_POST['jum'];
		
	
	
		for($i=1; $i<=$n; $i++) 
		{
		
			$productcode = $_POST['productcode'.$i];
			$productname = mysql_real_escape_string($_POST['productname'.$i]);
			$volume	     = $_POST['volume'.$i];
			$pcs		 = $_POST['pcs'.$i];
			$unit	     = $_POST['unit'.$i];
			$description = mysql_real_escape_string($_POST['description'.$i]);
			$createdate		 = $_POST['createdate'.$i]; 
			
		
				read_write("INSERT INTO tb_product (
					productcode, 
					productname,
					volume,
					pcs,
					unit, 
					description,
					divisionid,
					createdate) 
								VALUES (
								'".$productcode."',
								'".$productname."', 
								'".$volume."',
								'".$pcs."',
								'".$unit."',
								'".$description."',
								'".$divisionid."',
								'".$today."'		
								)");
				
								
				$message = "Product Code has been inserted ...";
			
			} //end FOR
			
			
		
	}
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
			
			
		</script>
	</head>
<body>

<table border="0" cellspacing="1" cellpadding="2" align="center" width="80%" bgcolor="#e5ebf9">
	<tr bgcolor="white">
		<td class="small">
			<div class="header icon-48-article"><b class='big'>Add Product</b></div>
		</td>
	</tr>

	<tr bgcolor="white"><td class="message" align="center"><b><?php echo $message; ?></b></td></tr>
	<tr>
		<td bgcolor="#ffffff">
	
	<form method="POST" onSubmit="return validate()" name="frmaddproduct" action="insertproduct.php?add=1&divisionid=<? echo $divisionid; ?>">		
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		
			<?
			for($i=1; $i<=$n; $i++) {
			
				echo '<tr bgcolor="#e5ebf9"> <td width="3%"><b></b></td>
						<td width="20%" class="header" >Product Code</td>
						<td width="2%" align="center" class="header">:</td>
						<td><input type="text" name=productcode'.$i.' class="tBox" ></td></tr>';
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
						<td><input type="text" name=description'.$i.' size="50" class="tBox"></td></tr>';
				
				echo '<tr><td colspan="4">&nbsp;</td></tr>';
			}
			
			?>
			
			<tr>
				<td><b></b></td>
				<td></td>
				<td></td>
				<td>
				
					<input type="hidden" name="jum" value=<? echo $n; ?> > 
					<input type="submit" value="Save" class="tBox">
					<input type="reset" class="tBox" value="Clear">
					<input type="button" value="Finish" class="tBox" onclick="javascript:window.close()";> &nbsp;
					<!--<input type="button" value="Exit" class="tBox" onclick=javascript:location.href='product.php?divisionid=<?php echo $divisionid; ?>'> -->
				</td>
			</tr>
		
		</table>
	</form>	
		</td>
	</tr>
</table><br><br>

</body>
</html>


