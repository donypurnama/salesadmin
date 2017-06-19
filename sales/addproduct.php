<?php 
include('../constant.php'); 
include('../database.php'); 

$x = $_GET['x'];
$search = $_GET['search'];
$selgroup = $_GET['divid'];
$sid = $_GET['sid'];
$bid = $_GET['bid'];
$cid = $_GET['c'];
$p = $_GET['p'];
$currency = $_GET['currency'];

if ($search == 1) {
	$_SESSION['productname'] = $_POST['productname'];
	$_SESSION['productcode'] = $_POST['productcode'];
}
if ($x == 1) {
	$_SESSION['productname'] = "";
	$_SESSION['productcode'] = "";
}

?>

<html>
<head><title>Add product</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
</head>
<body>

<form name=frmadd method=post action="addproduct.php?sid=<?php echo $sid;?>&bid=<?php echo $bid;?>&c=<?php echo $cid;?>&p=<?php echo $p;?>&divid=<?php echo $selgroup; ?>&currency=<?php echo $currency; ?>&search=1">
<table border="0" cellspacing=1 cellpadding=2 width="70%" align=center>
	<tr><th class=small colspan=2>Search Product</th></tr>
	<tr class=small><td>Product Group</td>
	<td>
<?php
	$res = read_write("select * from tb_division where divisionid='".$selgroup."'");
	$row = mysql_fetch_array($res);
	echo $row['divisionname'];
?></td></tr>
<tr class="small">
	<td>Product Code</td>
	<td><input type="text" name="productcode" value="<?php echo $_SESSION['productcode'] ;?>" class="forms" size="20" maxlength="20"></td>
</tr>
<tr class=small>
<td>Product Name</td>
<td><input type="text" name="productname" value="<?php echo $_SESSION['productname']; ?>" class="forms" size=20 maxlength=20></td>
</tr>
<tr>
	<td colspan=2 align=center>
		<input type="submit" value="Search" class="forms">
		<input type="button" value="Exit" class="forms" onclick="javascript:window.close()">
</td></tr>
</table>
</form>

<?php
	if ($selgroup <> "") {
	echo "<table border=0 cellspacing=0 cellpadding=2 width=100% align=center>
			<tr class=small bgcolor=#0099ff><th width=10%>&nbsp;</th>
			<th align=left>Product Name</th>
			<th align=center> Code</th>
		</tr>";
	
	$strwhere = "";
	if ($selgroup <> "") { 
		$strwhere = $strwhere." and divisionid='".$selgroup."'"; 
	}
	if ($_SESSION['productname'] <> "") { 
		$strwhere = $strwhere." and productname like '%".$_SESSION['productname']."%'"; 
	}
	if ($_SESSION['productcode'] <> "" ) {
		$strwhere = $strwhere." and productcode like '".$_SESSION['productcode']."'";
	}
	
	if ($search == 1 || $x==1) {
		$rscnt = read_write("select count(productcode) as cnt 
						from 
							tb_product 
						where productcode is not null".$strwhere);
						
		$rwcnt = mysql_fetch_array($rscnt);
		$_SESSION['count'] = $rwcnt['cnt'];
	}
	
	$page = $_GET['page'];
	if($page == "") { 
		$page = 1; 
	}
	
	$recordperpage = 50;
	$start = ($page-1) * $recordperpage;

	$max_a = (int) ($_SESSION['count'] / $recordperpage);
	
	if(($_SESSION['count'] % $recordperpage) == 0) {  
		$max = $max_a;
	} else {
		$max = $max_a + 1;
	}

	$rs = read_write("select * 
						from 
							tb_product 
						where productcode is not null".$strwhere." order by productcode LIMIT ".$start.",".$recordperpage);
	
	while ($rw = mysql_fetch_array($rs)) {
		echo "<tr class=small>
		<td><a href='product.php?sid=".$sid."&bid=".$bid."&productcode=".$rw['productcode']."&c=".$cid."&p=".$p."&currency=".$currency."&divid=".$selgroup."' class='smalllink'>Add</a></td>
		<td>".$rw['productname']."</td><td>".$rw['volume']."&nbsp;".$rw['pcs'].
		"</td></tr>";
		
	}
	
	
	
	
	?>
	<tr><td height=6></td></tr>
	<?php 
	if ($page == $max) { ?>
	<tr class="small">
		<td colspan="3">
		<?php if ($search==1 || $_SESSION['productcode'] <> '' || $_SESSION['productname'])  { 
				echo "&nbsp;"; 
			} else {?>
				<a href="addotherproduct.php?sid=<?php echo $sid; ?>&bid=<?php echo $bid; ?>&c=<?php echo $cid; ?>&p=<?php echo $p;?> &currency=<?php echo $currency; ?>&divid=<?php echo $selgroup; ?>" class='smalllink'>Add Other Product</a>
		<?php } ?>
		</td>
	</tr>	
	<? } ?>
	<tr><td height=6></td></tr>
	<?php  
		if ($_SESSION['count'] > $recordperpage) { 			
	?>
	<tr>
		<th colspan=4 align="center"><font class="small">Page: </font>&nbsp;
	<?php
		if ($page > 1){ ?>
		<a href="addproduct.php?page=<?php echo ($page-1); ?>&sid=<?php echo $sid;?>&bid=<?php echo $bid;?>&c=<?php echo $cid;?>&p=<?php echo $p;?>&divid=<?php echo $selgroup; ?>&currency=<?php echo $currency; ?>" class="pagemenu">[Prev]</a> 
	<?php }
		for ($i=1;$i<=$max;$i++) {
			if ($i == $page) {	?>
				<font class="small">[<?php echo ($i); ?>]</font> 
	<?php 	} else { ?>
				<a href="addproduct.php?page=<?php echo $i; ?>&sid=<?php echo $sid;?>&bid=<?php echo $bid;?>&c=<?php echo $cid;?>&p=<?php echo $p;?>&divid=<?php echo $selgroup; ?>&currency=<?php echo $currency; ?>" class="pagemenu">[<?php echo ($i); ?>]</a> 
	<?php
			}
		}
	if ($page < $max) { ?>

		<a href="addproduct.php?page=<?php echo ($page+1); ?>&sid=<?php echo $sid;?>&bid=<?php echo $bid;?>&c=<?php echo $cid;?>&p=<?php echo $p;?>&divid=<?php echo $selgroup; ?>&currency=<?php echo $currency; ?>" class="pagemenu">[Next]</a> 
	<?php } ?>
		</th>
	</tr>
<?php } ?>
<?php
echo "</table>";
}
?>
</body>
</html>
