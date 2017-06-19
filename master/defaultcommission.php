<?php 
include('../constant.php'); 
include('../database.php');


$divisionid = $_GET['divisionid'];
$commgroupcode = $_GET['commgroupcode'];
$op = $_GET['op'];

$def = read_write("SELECT * from tb_defaultcommrules where divisionid = '".$divisionid."'");
$row = mysql_fetch_array($def);

if($op = 'delete'){

read_write("delete  from tb_defaultcommrules where divisionid = '".$divisionid."'");
read_write("INSERT INTO tb_defaultcommrules (divisionid,commgroupcode) VALUES ('".$divisionid."','".$commgroupcode."')");

Header("Location: commission.php?divisionid=".$divisionid."&search=1");




}
?>