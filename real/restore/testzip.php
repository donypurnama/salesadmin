<?php
$zip = new ZipArchive;
$res = $zip->open('test.zip', ZipArchive::CREATE);
if ($res === TRUE) {
    $zip->addEmptyDir("dir");
    $zip->addFile('tb_commgroup.MYD', 'dir/tb_commgroup.MYD');
	$zip->addFile('tb_commgroup.MYI', 'dir/tb_commgroup.MYI');
	$zip->addFile('tb_commgroup.frm', 'dir/tb_commgroup.frm');
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}
?> 
