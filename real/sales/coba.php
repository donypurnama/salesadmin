<?php 
include ('../constant.php');
include ('../database.php');
$rstemp = read_write("SELECT REPLACE (salescode	, '01".".','') AS nbr 
FROM `tb_salesman` 
ORDER BY salescode 
DESC LIMIT 1");
					$rowtemp = mysql_fetch_array($rstemp);
					$codetemp = (int) $rowtemp	['nbr'];
					$codetemp++;
					if(strlen($codetemp)==3) {
						$codetemp = '0'.$codetemp;
					} elseif (strlen($codetemp)==2) {
						$codetemp = '00'.$codetemp;
					} elseif (strlen($codetemp)==1) {
						$codetemp = '000'.$codetemp;
					} 
					$salescode = $branch.".".$codetemp;

$inv = "FK".$branch.date("ym")."-".$invtemp;
echo "salescode ". $salescode;
echo "<br>";echo "<br>";
/*-------------------------------------------------*/
$rsdiv = read_write("select divisionid 
from tb_commgroup 
where commgroupcode='ZT1A'");
$rwdiv = mysql_fetch_array($rsdiv);
$divisionid = $rwdiv['divisionid'];


$rstemp = read_write("SELECT replace(ttuno, 'TU".$branch.$division.date("ym")."-','') as nbr
				FROM tb_ttu 
				order by ttuno
				desc limit 1");
				$rowtemp = mysql_fetch_array($rstemp);
				$ttutemp = (int) $rowtemp['nbr'];
				$ttutemp++;
				if (strlen($ttutemp)==1) {
					$ttutemp = '00'.$ttutemp;
				} elseif (strlen($ttutemp)==2) {
					$ttutemp = '0'.$ttutemp;
				}
				$ttuno = "TU".$branch.$divisionid.date("ym")."-".$ttutemp;			
				echo "ttutemp ". $ttutemp;					
echo "<br><br>";
echo "ttuno ". $ttuno;	
echo "<br><br>";
/*-------------------------------------------------*/
$rsdiv = read_write("select divisionid 
from tb_commgroup 
where commgroupcode='ZT1A'");
$rwdiv = mysql_fetch_array($rsdiv);
$divisionid = $rwdiv['divisionid'];

$rstemp = read_write("
SELECT REPLACE (invoiceno,'FK".$branch.$divisionid.date("ym")."-','') AS nbr 
FROM `tb_invoice` 
ORDER BY invoiceno
DESC LIMIT 1");		
				
	$rowtemp = mysql_fetch_array($rstemp);
				$invtemp = (int) $rowtemp ['nbr'];
				$invtemp++;			
		
		if (strlen($invtemp)==1) {
			$invtemp = '00'.$invtemp;
		} elseif (strlen($invtemp)==2) {
			$invtemp = '0'.$invtemp;
		}
		$sid = "FK".$branch.$divisionid.date("ym")."-".$invtemp;
				
echo "invoice no ". $sid;		
echo "<br><br>";
echo "inv temp ". $invtemp;		echo "<br><br>";
echo "divisionid ". $divisionid;		
?>