<?php
include ('../constant.php');
include ('../database.php');
$os = 'linux';		//operating system 

if ($os == 'linux') {
	$bcklocation = "/var/www/html/salesadmin/real/backup/backupdb/";
	$dblocation = "/var/lib/mysql/".$databasename."/";
} else {
	$bcklocation = "d:/backupdb/"; //destination 
	$dblocation = "d:/mysql/".$databasename."/"; //source
}
$sdate = date("Ymd");
if (file_exists($bcklocation.'bck_'.$branch.'_'.$sdate.'.zip')) {
	unlink($bcklocation.'bck_'.$branch.'_'.$sdate.'.zip');
}

$zip = new ZipArchive;

$res = $zip->open($bcklocation.'bck_'.$branch.'_'.$sdate.'.zip', ZipArchive::CREATE);
//$res = $zip->open('bck_'.$branch.'_'.$sdate.'.zip', ZipArchive::CREATE);
if ($res === TRUE) {
    $zip->addEmptyDir($branch.'_db');
	$rs = read_write("SHOW TABLES");
	while ($row = mysql_fetch_row($rs)) {
		addzipfile($zip, $row[0], $dblocation, $branch);
	}

    $zip->close();
    Header("Location: index.php?done=2&bcklocation=".$bcklocation."bck_".$branch."_".$sdate.".zip");   
} else {
	Header("Location: index.php?failzip=1");
}

function addzipfile(&$zip, $tblname, $dblocation, $branch) {
	$zip->addFile($dblocation.$tblname.'.MYD', $branch.'_db/'.$tblname.'.MYD');
	$zip->addFile($dblocation.$tblname.'.MYI', $branch.'_db/'.$tblname.'.MYI');
	$zip->addFile($dblocation.$tblname.'.frm', $branch.'_db/'.$tblname.'.frm');	
}
?> 
