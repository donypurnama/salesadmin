<?php 
include('../constant.php'); 
include('../database.php');

	if ($_SESSION['user'] == '')
	{
		Header('Location: ../index.php');
	}
	$productcode = $_GET['productcode'];
	$act 	   = $_GET['act'];
	$update    = $_GET['update'];
	$divisionid = $_GET['divisionid'];	
	
	$sid 	= $_GET['sid'];
	$bid = $_GET['bid'];
	$c = $_GET['c'];
	$p = $_GET['p'];
	$currency = $_GET['currency'];
	$divid = $_GET['divid'];
	
//	addotherproduct.php?
//sid=FK01061002-032	&bid=01.0001319		&productcode=	&c=01.0000918&p=0 &currency=Rp.&divid=F
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<title>Add New Poduct</title>
		<script language = "javascript">
			function validate() {
				if (document.forms[0].productname.value == "") {
					alert("Please fill up the product name");
					return false;
				} else if (document.forms[0].unit.value == "") {
					alert("Please fill the unit");
					return false;
				} else {
					return true;
				}
			}
			
		</script>
	</head>
<body>
<?php
//echo "sid".$sid."<br>";
//echo "bid".$bid."<br>";
//echo "c".$c."<br>";
//echo "p".$p."<br>";
//echo "currency".$currency."<br>";
//echo "divid".$divid."<br>";
//<a href='product.php?sid=".$sid."&bid=".$bid."&productcode=".$rw['productcode']."&c=".$cid."&p=".$p."&currency=".$currency."&divid=".$selgroup."' class='smalllink'>
		
?>
<form method="POST" onSubmit="return validate()" name="frmproduct" action="product.php?&o=1&sid=<? echo $sid; ?>&bid=<? echo $bid; ?>&c=<? echo $c;?>&p=<? echo $p;?>&currency=<? echo $currency; ?>&divid=<? echo $divid; ?>">
<table border="0" cellspacing="1" cellpadding="2"  width="500">
<tr bgcolor="white">
	<td colspan="3" class="small"><div class="header icon-48-article"> <b class='big'>Add Other Product</b></div>		</td>
</tr>
<tr bgcolor=white>
	<td  align="left" class="header" width="12%">Product Name:</td>
	<td width="40"><input type="text" name="otherproductname" size="25"  class=tBox></td>
</tr>
<tr>
	
	<td align="left" width="10%" class=header>Unit:</td>
	<td width="45%"><select name="otherunit" class="forms">
	<?php 
	$rs = read_write("SELECT * FROM tb_product WHERE productcode like 'X%'");
	while ($resrs = mysql_fetch_array($rs))
	{
		echo "<option value=$resrs[productcode]>$resrs[unit]";
	}
	echo "</option>";
			
		
	?>	</select> </td>
	
</tr>
<tr bgcolor=white>
	
	<td  align="left" colspan=2>	
	<input type="submit" class="tBox" value="Save">&nbsp;
	</td>	
</tr>
</table><br><br>
</body>
</html>
