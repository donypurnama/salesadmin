<?php 
include('../constant.php'); 
include('../database.php');


$divisionid = $_GET['divisionid'];
$commgroupcode = $_GET['commgroupcode'];
$op = $_GET['op'];

$def = read_write("SELECT * from tb_defaultoverrules where divisionid = '".$divisionid."'");
$row = mysql_fetch_array($def);

if($op = 'delete'){

read_write("delete  from tb_defaultoverrules where divisionid = '".$divisionid."'");
read_write("INSERT INTO tb_defaultoverrules (divisionid,overgroupcode) VALUES ('".$divisionid."','".$commgroupcode."')");

Header("Location: comm-overiding.php?divisionid=".$divisionid."&search=1");




}
?>