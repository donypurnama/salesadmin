<?php include('../constant.php'); ?>
<?php include('../database.php');?>
<?php
function xlsBOF() { 
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
    return; 
} 

function xlsEOF() { 
    echo pack("ss", 0x0A, 0x00); 
    return; 
} 

function xlsWriteNumber($Row, $Col, $Value) { 
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0); 
    echo pack("d", $Value); 
    return; 
} 

function xlsWriteLabel($Row, $Col, $Value ) { 
    $L = strlen($Value); 
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L); 
    echo $Value; 
return; 
} 

function calcoverriding($salescode, $position, $strfromor, $sqlwhere) {
	if ($position <> 'SPK' && $position <> 'Sales') {
		if ($position == 'District Supervisor') {
			$res = read_write("select 
									sum(comm_ds) as sum_comm 
								from 
									".$strfromor." 
								where 
									tb_invoice.district_supervisor='".$salescode."'
								and 
									tb_ttu.invoiceno = tb_invoice.invoiceno 
								and  
									".$sqlwhere." 
								AND tb_salesman.active =1");		
		
			
		} else {
			$res = read_write("select 
									sum(comm_trainer) as sum_comm 
								from 
									".$strfromor." 
								where 
									tb_invoice.trainer='".$salescode."' 
								and 
									tb_ttu.invoiceno = tb_invoice.invoiceno 
								and 
									".$sqlwhere." 
								AND tb_salesman.active =1");
			
		}
		
		$row = mysql_fetch_array($res);
		return $row['sum_comm'];
	} else {
		return 0;
	}
}
$res = read_write($_SESSION['sqlcomm']);
$no_row = 2;
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=commission.xls "); 
header("Content-Transfer-Encoding: binary ");
xlsBOF(); 
if ($_SESSION['ver']==0) {
	xlsWriteLabel(1,0,"Salesman");
	xlsWriteLabel(1,1,"Collect Bruto");
	xlsWriteLabel(1,2,"Collect Net");
	xlsWriteLabel(1,3,"Commission");
	xlsWriteLabel(1,4,"Overriding");
	xlsWriteLabel(1,5,"Total");

	$i = 0;
	$j = 0;
	$k = 0;
	while ($row = mysql_fetch_array($res)) {
	
		xlsWriteLabel($no_row,0,$row['salesname']);	
		xlsWriteLabel($no_row,1,$row['collect_bruto']);
		xlsWriteLabel($no_row,2,$row['collect_net']);
		xlsWriteLabel($no_row,3,$row['total_comm']);
		$comm_or = calcoverriding($row['salescode'], $constant_salespos[$row['position']], $_SESSION['strsqlor'], $_SESSION['strsqlwhereor']);
		xlsWriteLabel($no_row,4,$comm_or);
		xlsWriteLabel($no_row,5,$row['total_comm'] + $comm_or);
		
		$sum_bruto = $sum_bruto + $row['collect_bruto'];
		$sum_net = $sum_net + $row['collect_net'];
		$sum_comm = $sum_comm + $row['total_comm'];
		$sum_or = $sum_or + $comm_or;
		if ($_SESSION['salesman']=='') {
				//input salesmanid, trainerid, dsid to array. check unvailable collection but existing overd.
				$arrsalesid[$i] = $row['salescode'];
				if ($row['trainer'] <> "") {
					if ($j==0) {
						$arrtrainer[$j] = $row['trainer'];
						$j++;
					} else {
						if (!in_array($row['trainer'], $arrtrainer)) {
							$arrtrainer[$j] = $row['trainer'];
							$j++;
						}
					}
				}
				if ($row['district_supervisor'] <> "") {
					if ($k==0) {
						$arrds[$k] = $row['district_supervisor'];
						$k++;
					} else {
						if (!in_array($row['district_supervisor'], $arrds)) {
							$arrds[$k] = $row['district_supervisor'];
							$k++;
						}
					}
				}
		}
		$no_row++;
		$i++;
	}

	if ($j>0) {
		for ($i=0;$i<count($arrtrainer);$i++) {
			if (!in_array($arrtrainer[$i],$arrsalesid) && $arrtrainer[$i] <> $branch.'.0000') {
				$comm_or = calcoverriding($arrtrainer[$i], 'Trainer', $_SESSION['strsqlor'], $_SESSION['strsqlwhereor']);
				$qsls = read_write("select salesname from tb_salesman where salescode='".$arrtrainer[$i]."'");
				$rssls = mysql_fetch_array($qsls);
				$salesname = $rssls['salesname'];
				xlsWriteLabel($no_row,0,$salesname);	
				xlsWriteLabel($no_row,1,"0");
				xlsWriteLabel($no_row,2,"0");
				xlsWriteLabel($no_row,3,"0");
				xlsWriteLabel($no_row,4,$comm_or);
				xlsWriteLabel($no_row,5,$comm_or);
				$sum_or = $sum_or + $comm_or;
				$no_row++;
			}
		}
	}

	if ($k>0) {
		for ($i=0;$i<count($arrds);$i++) {
			if (!in_array($arrds[$i],$arrsalesid) && $arrds[$i] <> $branch.'.0000') {
				$comm_or = calcoverriding($arrds[$i], 'District Supervisor', $_SESSION['strsqlor'], $_SESSION['strsqlwhereor']);
				$qsls = read_write("select salesname from tb_salesman where salescode='".$arrds[$i]."'");
				$rssls = mysql_fetch_array($qsls);
				$salesname = $rssls['salesname'];
				xlsWriteLabel($no_row,0,$salesname);	
				xlsWriteLabel($no_row,1,"0");
				xlsWriteLabel($no_row,2,"0");
				xlsWriteLabel($no_row,3,"0");
				xlsWriteLabel($no_row,4,$comm_or);
				xlsWriteLabel($no_row,5,$comm_or);
				$sum_or = $sum_or + $comm_or;
				$no_row++;
			}
		}
	}

	xlsWriteLabel($no_row,0,"Total");	
	xlsWriteLabel($no_row,1,$sum_bruto);
	xlsWriteLabel($no_row,2,$sum_net);
	xlsWriteLabel($no_row,3,$sum_comm);
	xlsWriteLabel($no_row,4,$sum_or);
	xlsWriteLabel($no_row,5,$sum_comm + $sum_or);

} elseif ($_SESSION['ver']==1) {
	xlsWriteLabel(1,0,"Customer");
	xlsWriteLabel(1,1,"Invoice");
	xlsWriteLabel(1,2,"TTU");
	xlsWriteLabel(1,3,"Inv Date");
	xlsWriteLabel(1,4,"TTU Date");
	xlsWriteLabel(1,5,"Days");
	xlsWriteLabel(1,6,"Collect Bruto");
	xlsWriteLabel(1,7,"Collect Net");
	xlsWriteLabel(1,8,"Comm (%)");
	xlsWriteLabel(1,9,"Commission");
	
	while ($row = mysql_fetch_array($res)) {
		
		if ($row['deliverycode']<> $branch.".0000000") {
			xlsWriteLabel($no_row,0,$row['companyname']);				
		} else {
			xlsWriteLabel($no_row,0,$row['personname']);				
		}
		xlsWriteLabel($no_row,1,$row['invoiceno']);
		xlsWriteLabel($no_row,2,$row['ttuno']);
		xlsWriteLabel($no_row,3,date("d-m-Y",strtotime($row['invoicedate'])));
		xlsWriteLabel($no_row,4,date("d-m-Y",strtotime($row['ttudate'])));
		xlsWriteLabel($no_row,5,$row['datediff']);
		xlsWriteLabel($no_row,6,$row['total_pay']);
		xlsWriteLabel($no_row,7,$row['payment']);
		xlsWriteLabel($no_row,8,$row['percent_comm']);
		xlsWriteLabel($no_row,9,$row['commission']);
		
		$sum_bruto = $sum_bruto + $row['total_pay'];
		$sum_net = $sum_net + $row['payment'];
		$sum_comm = $sum_comm + $row['commission'];
		$no_row++;
	}
	xlsWriteLabel($no_row,5,"Total:");
	xlsWriteLabel($no_row,6,$sum_bruto);
	xlsWriteLabel($no_row,7,$sum_net);
	
	xlsWriteLabel($no_row,9,$sum_comm);
	$no_row++;
	
	xlsWriteLabel($no_row,5,"Overriding:");	
	
	$qsls = read_write("select position from tb_salesman where salescode='".$_SESSION['salesman']."'");
	$rssls = mysql_fetch_array($qsls);
	
	$comm_or = calcoverriding($_SESSION['salesman'], $constant_salespos[$rssls['position']], $_SESSION['strsqlor'], $_SESSION['strsqlwhereor']);
	xlsWriteLabel($no_row,9,$comm_or);	
	$no_row++;
	xlsWriteLabel($no_row,5,"Commission & Overriding:");	
	xlsWriteLabel($no_row,9,$sum_comm + $comm_or);		
} elseif ($_SESSION['ver']==2) {
	$resds = read_write("select 
							tb_salesman.salescode, 
							salesname 
						from 
							tb_salesman where position=3");
	xlsWriteLabel(1,0,"No");
	xlsWriteLabel(1,1,"Nama District Supervisor");
	xlsWriteLabel(1,2,"Nama Trainee");
	xlsWriteLabel(1,3,"Net. Coll. (RP)");
	$i = 1;
	while ($rwds = mysql_fetch_array($resds)) {
		xlsWriteLabel($no_row,0,$i);	
		xlsWriteLabel($no_row,1,$rwds['salesname']);
		
		$resds_dl = read_write("select 
									salesname, 
									tb_invoice.salescode, 
									sum(payment) as collect_net 
								from 
									".$_SESSION['strsqlor']." 
								where 
									".$_SESSION['strsqlwhereor']." 
								and 
									commission > 0 
								and 
									tb_invoice.district_supervisor='".$rwds['salescode']."' 
								group by 
									tb_invoice.salescode");
		
		$total_coll = 0;
		
		while ($rwds_dl = mysql_fetch_array($resds_dl)) {
			xlsWriteLabel($no_row,2,$rwds_dl['salesname']);	
			xlsWriteLabel($no_row,3,$rwds_dl['collect_net']);
			
			$total_coll = $total_coll + $rwds_dl['collect_net'];
			$no_row++;
		}
				
		if ($total_coll > 0) {
			xlsWriteLabel($no_row,2,"Total Coll");	
			xlsWriteLabel($no_row,3,$total_coll);	
			$comm_or = calcoverriding($rwds['salescode'], 'District Supervisor', $_SESSION['strsqlor'], $_SESSION['strsqlwhereor']);
			$no_row++;
			xlsWriteLabel($no_row,2,"Overriding");		
			xlsWriteLabel($no_row,3,$comm_or);		
		} else {
			xlsWriteLabel($no_row,2,"Overriding");	
			xlsWriteLabel($no_row,3,"0");	
		}
		$no_row++;
		$i++;
	}
	$no_row = $no_row + 3;

	$resds = read_write("select tb_salesman.salescode, salesname from tb_salesman where position=2");
	xlsWriteLabel($no_row,0,"No");
	xlsWriteLabel($no_row,1,"Nama Trainer");
	xlsWriteLabel($no_row,2,"Nama Trainee");
	xlsWriteLabel($no_row,3,"Net. Coll. (RP)");
	$i = 1;
	$no_row++;
	while ($rwds = mysql_fetch_array($resds)) {
		xlsWriteLabel($no_row,0,$i);	
		xlsWriteLabel($no_row,1,$rwds['salesname']);
		
		$resds_dl = read_write("select 
									salesname, 
									tb_invoice.salescode, 
									sum(payment) as collect_net 
								from 
									".$_SESSION['strsqlor']." 
								where 
									".$_SESSION['strsqlwhereor']." 
								and 
									commission > 0 and 
									tb_invoice.trainer='".$rwds['salescode']."' 
								group by 
									tb_invoice.salescode");		
		$total_coll = 0;
		
		while ($rwds_dl = mysql_fetch_array($resds_dl)) {
			xlsWriteLabel($no_row,2,$rwds_dl['salesname']);
			xlsWriteLabel($no_row,3,$rwds_dl['collect_net']);
			
			$total_coll = $total_coll + $rwds_dl['collect_net'];	
			$no_row++;
		}
				
		if ($total_coll > 0) {
			xlsWriteLabel($no_row,2,"Total Coll");
			xlsWriteLabel($no_row,3,$total_coll);
			$comm_or = calcoverriding($rwds['salescode'], 'Trainer',$_SESSION['strsqlor'], $_SESSION['strsqlwhereor']);
			$no_row++;
			xlsWriteLabel($no_row,2,"Overriding");
			xlsWriteLabel($no_row,3,$comm_or);			
		} else {
			xlsWriteLabel($no_row,2,"Overriding");	
			xlsWriteLabel($no_row,3,"0");	
		}
		$no_row++;
		$i++;
	}

}

xlsEOF();
exit();
?>

	
