<?php 
include('../constant.php'); 
include('../database.php'); 
$otherproductname = $_POST['productname'];
$otherunit = $_POST['unit'];
$rs = read_write("SELECT * FROM tb_product WHERE productcode='".$otherunit."'");
$rsother = mysql_fetch_array($rs);



echo "productname = ".$otherproductname."<br>";
echo "prodcutcode = ".$otherunit."<br>";
echo "Volume = ".$rsother['volume']."&nbsp;".$rsother['pcs']."<br>";
echo "unit = ".$rsother['unit']."<br>";



?>