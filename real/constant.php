<?php
	session_start();
	
	include ('localipserver.php');
	/*
	$localipserver = "10.20.0.15";
	$remoteipserver = "10.20.0.15"; //"125.161.160.92";
	define('DOMAIN_NAME','http://'.$localipserver.'/salesadmin/real');
	*/
	
	$constant_user = array(array('U','User'),
						   array('A','Administrator'));
						   
	$user_record = 2;
	$constant_invtax = array('inc','exc', 'kbn', 'ssp');	
	$constant_term = array('Cash','Credit');
	
	$constant_volume = array("Box", "Btl", "UNT", "Bh", "Jar", "Tube", "Cont", "Pail", "Kg", "Bag", "Can", "Set", "Lbs", "Pcs", "Appl", "Drum", "Each", "Ctrg", "Rool", "Unit", "Pack", "Gallon","Quart");
	$constant_pcs = array("UNT","PCS","Ltr", "Unit", "Set", "Btl", "botol", "Quart", 
							"Kg", "Lt", "Tb", "Jar", "Feet", "Mm", "Gr", "Lb", "Lbs", "Cm",
							"Box", "Each", "Spool", "Can", "Tube", "Kit", "Crtg", "Cont", "Ctrg",
							"Appl", "Bh", "Rool", "Gallon");
	
	$constant_salespos = array('SPK', 'Sales', 'Trainer', 'District Supervisor');
	$percent_trainer = 1;
	$percent_ds = 1;
	
	$arr_branch = array('00'=>'Pusat', '01'=>'Jakarta', '02'=>'Jawa Barat', '03'=>'Semarang', '04'=>'Jawa Timur', '05'=>'Palembang', 
						'06'=>'Lampung', '07'=>'Bali NTB NTT', '08'=>'Banjarmasin', '09'=>'Samarinda', '10'=>'Sulawesi', 
						'11'=>'Pontianak', '12'=>'Solo', '13'=>'Cilegon', '14'=>'Karawang', '15'=>'Manado');
	//$branch = '00'; KODE AREA PUSAT 
	//$branch = '01'; //kode area perwakilan JAKARTA 
	//$old_branch = '1';
	$combine_db = true;
	
	$branch = substr($_SESSION['user'], 0, strpos($_SESSION['user'], "."));
	if ($branch<>'A') {
		if (substr($branch, 0, 1) == 0) {
			$old_branch = substr($branch,1,1);
		} else {
			$old_branch = $branch;
		}
	} else {
		$branch = "";
		$old_branch = "";
	}	
	
	
	$today = date("Y-m-d");
	$oldinv_date = "2009-12-31";
	
	if ($branch == "01") {
		$default_sa = "01.0011";
	} else {
		$default_sa = "00.0018";
	}
	$default_taxoff = "A.0001";

	//echo "--".$branch;
?>
