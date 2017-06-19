<?php 
	if(getenv(HTTP_XFORWARDED_FOR)) {
		echo "Access from proxy server <br>";
		echo "IP Anda: ".$_SERVER['HTTP_X_FORWARDED_FOR']."<br>";
		echo "Terkoneksi lewat engine : ". $_SERVER['HTTP_VIA']."<br>";
		echo "IP Proxy : ". $_SERVER['REMOTE_ADDR']; 
		
	} else {
		echo "Anda terkoneksi tanpa PROXY <br>";
		echo "IP Anda : ".$_SERVER['REMOTE_ADDR'];
	}
?>