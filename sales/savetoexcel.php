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

$ver = $_GET['ver'];

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");

if ($ver==1) {
	header("Content-Disposition: attachment;filename=branch_report.xls"); 
} elseif ($ver==2) {
	header("Content-Disposition: attachment;filename=sales_book.xls"); 
} elseif ($ver==3) {
	header("Content-Disposition: attachment;filename=product_book.xls"); 
} elseif ($ver==4) {
	header("Content-Disposition: attachment;filename=salesman_report.xls"); 
}
elseif ($ver==5) {
	header("Content-Disposition: attachment;filename=rekap_bulanan.xls"); 
}
elseif ($ver==6) {
	header("Content-Disposition: attachment;filename=rekap_tahunan.xls"); 
}

header("Content-Transfer-Encoding: binary ");
xlsBOF(); 

if ($ver==1) {
	$res = read_write($_SESSION['queryinv']);
	
	xlsWriteLabel(1,0,"Branch");
	xlsWriteLabel(1,1,"Invoice Date");
	xlsWriteLabel(1,2,"Invoice Number");
	xlsWriteLabel(1,3,"Customer");
	xlsWriteLabel(1,4,"Sales Person");
	xlsWriteLabel(1,5,"Division");
	xlsWriteLabel(1,6,"Division Code");
	xlsWriteLabel(1,7,"Customer Category");
	xlsWriteLabel(1,8,"Inv Amount Inc PPn");
	xlsWriteLabel(1,9,"Date Paid");
	xlsWriteLabel(1,10,"Amount Paid Inc PPn");
	xlsWriteLabel(1,11,"Commiss Paid Date");
	xlsWriteLabel(1,12,"Commiss Amount");	
} elseif ($ver==2) {
	$res = read_write($_SESSION['querysb']);
	xlsWriteLabel(1,0,"Tipe");
	xlsWriteLabel(1,1,"Status");
	xlsWriteLabel(1,2,"No. Faktur Pajak");
	xlsWriteLabel(1,3,"Pelanggan");
	xlsWriteLabel(1,4,"NPWP");
	xlsWriteLabel(1,5,"Produk");
	xlsWriteLabel(1,6,"Qty");
	xlsWriteLabel(1,7,"Volume/Unit");
	xlsWriteLabel(1,8,"RP/Unit");
	xlsWriteLabel(1,9,"Total Price");
	xlsWriteLabel(1,10,"Pot. Harga");
	xlsWriteLabel(1,11,"DPP");
	xlsWriteLabel(1,12,"PKP");
	xlsWriteLabel(1,13,"Non PKP");
	xlsWriteLabel(1,14,"Invoice No.");
	xlsWriteLabel(1,15,"Inv Date");
	xlsWriteLabel(1,16,"Division");
	xlsWriteLabel(1,17,"Perwakilan");
	xlsWriteLabel(1,18,"Salesman");
} elseif ($ver ==3) {
	$res = read_write($_SESSION['queryprod']);
	xlsWriteLabel(1,0,"Salesman");
	xlsWriteLabel(1,1,"Pelanggan");
	xlsWriteLabel(1,2,"No. Faktur");
	xlsWriteLabel(1,3,"Nama Barang");
	xlsWriteLabel(1,4,"Qty");
	xlsWriteLabel(1,5,"Harga/Unit");
	
	xlsWriteLabel(1,6,"Net Total");
	xlsWriteLabel(1,7,"PPN");
	xlsWriteLabel(1,8,"Total");
} elseif ($ver ==4) {
	$res = read_write($_SESSION['queryinv']);
	xlsWriteLabel(1,0,"Invoice No");
	xlsWriteLabel(1,1,"Customer");	
	xlsWriteLabel(1,2,"Delivery Address");
	xlsWriteLabel(1,3,"Invoice Date");
	xlsWriteLabel(1,4,"Product Name");
	xlsWriteLabel(1,5,"Sales Name");
}
 elseif ($ver ==5) {
	$res = read_write($_SESSION['queryrkpbln']);
	xlsWriteLabel(1,0,"Salesman");
	xlsWriteLabel(1,1,"Penjualan");	
	xlsWriteLabel(1,2,"Bruto");
	xlsWriteLabel(1,3,"PPN");
	xlsWriteLabel(1,4,"Net");
	xlsWriteLabel(1,5,"NetCollection");
}
elseif ($ver ==6) {
xlsWriteLabel(0,0,$_SESSION['tahun']);	
$bulanan=array(1=>"Jan","Feb","Mar","Apr","Mei","Juni","Juli","Agt","Sept","Okt","Nov","Des");
	$res = read_write($_SESSION['queryrkpthn']);
	xlsWriteLabel(1,0,"Salesman");
	for($b=1;$b<=$_SESSION['jmlbln'];$b++)
	{
	xlsWriteLabel(1,$b,$bulanan[$b]);
	}
	xlsWriteLabel(1,$_SESSION['jmlbln']+1,"TOTAL");	
}
$no_row = 2;
if ($_SESSION['slbranch'] <> "") { $branch = $_SESSION['slbranch']; }
while ($row = mysql_fetch_array($res)) {
	$booladdrow = 1;
	
	if ($ver==1) {
		
		xlsWriteLabel($no_row,0,$arr_branch[$branch]);	
		xlsWriteLabel($no_row,1,$row['invoicedate']);
		xlsWriteLabel($no_row,2,$row['invoiceno']);
		if (trim($row['companyname'])<>'') {
			$bcompany = 1;
		} else {
			$bcompany = 0;
		}
		if ($bcompany == 1) {
			$customer = $row['companyname'];
		} else {
			$customer = $row['personname'];
		}
		xlsWriteLabel($no_row,3,$customer);
		xlsWriteLabel($no_row,4,$row['salesname']);
		xlsWriteLabel($no_row,5,$row['product']);
		xlsWriteLabel($no_row,6,$row['divisioncode']);
		xlsWriteLabel($no_row,7,$row['product']);
		xlsWriteLabel($no_row,8,$row['invamount']);

		$rsdate = read_write("select ifnull(max(ttudate),'') as maxdate from tb_ttu where invoiceno='".$row['invoiceno']."'");
		$rwdate = mysql_fetch_array($rsdate);
		$maxdate = $rwdate['maxdate'];
		xlsWriteLabel($no_row,9,$maxdate);

		$rsttu = read_write("select ifnull(sum(ppn_payment + payment),0) as sumttu from tb_ttu where invoiceno='".$row['invoiceno']."'");
		$rwttu = mysql_fetch_array($rsttu);
		$sumttu = $rwttu['sumttu'];
		xlsWriteLabel($no_row,10,$sumttu);
		
		$rscommdate = read_write("select ifnull(max(depositdate),0) as maxdatecomm from tb_ttu where invoiceno='".$row['invoiceno']."'");
		$rwcommdate = mysql_fetch_array($rscommdate);
		$maxdatecomm = $rwcommdate['maxdatecomm'];
		xlsWriteLabel($no_row,11,$maxdatecomm);

		$rscomm = read_write("select ifnull(sum(commission),0) as commiss from tb_ttu where invoiceno='".$row['invoiceno']."'");
		$rwcomm = mysql_fetch_array($rscomm);
		$commiss = $rwcomm['commiss'];
		xlsWriteLabel($no_row,12,$commiss);
	} elseif ($ver==2) {
		
		$invoiceno = trim($row['invoiceno']);	
		
		if ($previnvno == '' || $invoiceno <> $previnvno) {
			$npwp = trim($row['npwp']);
			$cncode = trim($row['cncode']);
			$branch = substr($invoiceno, 2, 2);
			if ($npwp <> '') {
				$taxtype = substr($row['taxno'],0,3);
				$taxstatus = 'Standard';
			} else {
				$taxtype = '';
				$taxstatus = 'Sederhana';
			}
			
			
			if (trim($row['companyname'])<>'') {
				$customer = trim($row['companyname']);
			} else {
				$customer = trim($row['personname']);
			}

			xlsWriteLabel($no_row,0,$taxtype);	
			xlsWriteLabel($no_row,1,$taxstatus);
			xlsWriteLabel($no_row,2,$row['taxno']);
			xlsWriteLabel($no_row,3,$customer);
			xlsWriteLabel($no_row,4,$npwp);
		}
		
		$price = $row['price'];
		$totalprice = $row['totalprice'];
		$ppn = $row['ppn'];
		$totalsales = $row['totalsales'];
		
		xlsWriteLabel($no_row,5,$row['productname']);
		xlsWriteLabel($no_row,6,$row['volume']);
		xlsWriteLabel($no_row,7,$row['unit']);
		
		xlsWriteLabel($no_row,8,$price);
		xlsWriteLabel($no_row,9,$totalprice);				

		if ($previnvno == '' || $invoiceno <> $previnvno) {
			if (trim($row['discount'])<>'' && $row['discount']>0) {
				$rsinvitem = read_write("select sum(qty * volume * price) as tot_sum_price from tb_invoiceitems, tb_product where tb_invoiceitems.productcode=tb_product.productcode and tb_invoiceitems.invoiceno='".$invoiceno."'");
				
				$rwii = mysql_fetch_array($rsinvitem);
				$tot_sum_price = $rwii['tot_sum_price'];
				$discount = $row['discount'] * $tot_sum_price/100;
			} else { $discount = ''; }
			xlsWriteLabel($no_row,10,$discount);
			xlsWriteLabel($no_row,11,$totalsales);
			if ($npwp <> '') {
				xlsWriteLabel($no_row,12,$ppn);
			} else {
				xlsWriteLabel($no_row,13,$ppn);
			}
			
			xlsWriteLabel($no_row,14,$invoiceno);
			
			xlsWriteLabel($no_row,15,$row['invoicedate']);
			xlsWriteLabel($no_row,16,$row['divisionname']);
			xlsWriteLabel($no_row,17,$arr_branch[$branch]);
			xlsWriteLabel($no_row,18,$row['salesname']);
		}

		$previnvno = $invoiceno;
		
	} elseif ($ver==3) {
		$invoiceno = trim($row['invoiceno']);	
		if (trim($row['companyname'])<>'') {
			$customer = trim($row['companyname']);
		} else {
			$customer = trim($row['personname']);
		}
		

		$rsitems = read_write("select tb_product.unit, tb_product.productname, tb_product.volume,  (tb_invoiceitems.qty-tb_invoiceitems.qty_return) as qty, tb_invoiceitems.price from tb_invoiceitems, tb_product where tb_invoiceitems.invoiceno='".$invoiceno."' and tb_invoiceitems.productcode = tb_product.productcode");
		$j = 0;
		$cntrecitems = mysql_num_rows($rsitems);
		while ($rowitems = mysql_fetch_array($rsitems)) {
			if ($j == 0) {
				xlsWriteLabel($no_row,0,$row['alias']);	
				xlsWriteLabel($no_row,1,$customer);	
			} else {
				xlsWriteLabel($no_row,0,"");	
				xlsWriteLabel($no_row,1,"");	
			}
			xlsWriteLabel($no_row,2,$invoiceno);
			xlsWriteLabel($no_row,3,$rowitems['productname']);
			xlsWriteLabel($no_row,4,$rowitems['qty']." ".$rowitems['unit']);	
			xlsWriteLabel($no_row,5,$rowitems['price']);
			//xlsWriteLabel($no_row,6,$rowitems['price']*$rowitems['volume']*$rowitems['qty']);
			if ($j ==0) {
				xlsWriteLabel($no_row,6,$row['totalsales']);
				xlsWriteLabel($no_row,7,$row['tax']);
				xlsWriteLabel($no_row,8,$row['totalsales']+$row['tax']);
			} else {
				xlsWriteLabel($no_row,6,"");	
				xlsWriteLabel($no_row,7,"");
				xlsWriteLabel($no_row,8,"");
			}
			$j++;
			if ($j < $cntrecitems) {$no_row++;}
		}

	} elseif ($ver==4) {
		xlsWriteLabel($no_row,0,$row['invoiceno']);	
		
		
		if (trim($row['companyname'])<>'') {
			$customer = $row['companyname'];
			$address = trim($row['c_street']);
						
			if (trim($row['c_building'])<>"") {
				$address = trim($row['c_building']).", ".$address;
			}
			if (trim($row['c_region'])<>"") {
				$address = $address.", ".trim($row['c_region']);
			}
			if (trim($row['c_city'])<>"") {
				$address = $address.", ".trim($row['c_city']);
			}
		} else {
			$customer = $row['personname'];
			$address = trim($row['p_street']);
			
			if (trim($row['p_region'])<>"") {
				$address = $address.", ".trim($row['p_region']);
			}
			if (trim($row['p_city'])<>"") {
				$address = $address.", ".trim($row['p_city']);
			}
		}

		
		xlsWriteLabel($no_row,1,$customer);
		xlsWriteLabel($no_row,2,$address);
		xlsWriteLabel($no_row,3,$row['invoicedate']);		
		xlsWriteLabel($no_row,4,$row['product']);
		xlsWriteLabel($no_row,5,$row['salesname']);
	}
elseif ($ver==5 or $ver==6) {
	$strwhere = "";
		
		
		if ($_SESSION['salescode']<> '') {
				$strwhere = $strwhere."and tb_salesman.salescode='".$_SESSION['salescode']."'";
		}
		if ($_SESSION['selgroup']<>'') {
				$strwhere = $strwhere." and tb_division.divisionid='".$_SESSION['selgroup']."'";
		}
		if ($_SESSION['selgroup']<>'' ) {
				$strwhere = $strwhere." and tb_division.divisionid='".$_SESSION['selgroup']."'";
		}
		if ($_SESSION['bulan']<>'' and $ver==5) {
				$strwhere = $strwhere." and MONTH(tb_invoice.invoicedate)='".$_SESSION['bulan']."'";
		}
		
		if ($_SESSION['invoicedatefrom']<>'' ) {
				if ($_SESSION['invoicedateto']=='') { 
					$strwhereinv = $strwhere. " and invoicedate >='".$_SESSION['invoicedatefrom']."'";
					$strwherecn = $strwhere. " and cn_date >='".$_SESSION['invoicedatefrom']."'";
					$strwhere = $strwhere." and (invoicedate >='".$_SESSION['invoicedatefrom']."')";//or cn_date >='".$_SESSION['invoicedatefrom']."')";
					 
				} else {
					$strwhereinv = $strwhere. " and (invoicedate between '".$_SESSION['invoicedatefrom']."' and '".$_SESSION['invoicedateto']."')";
					$strwherecn = $strwhere. " and (cn_date between '".$_SESSION['invoicedatefrom']."' and '".$_SESSION['invoicedateto']."')";
					$strwhere = $strwhere." and (invoicedate between '".$_SESSION['invoicedatefrom']."' and '".$_SESSION['invoicedateto']."')";// or cn_date between '".$_SESSION['invoicedatefrom']."' and '".$_SESSION['invoicedateto']."')";
				}
		} 
}
//----------------------------------------------------------	
if($ver==5)
{			
			$inv=read_write("SELECT DISTINCT invoiceno FROM tb_invoice,tb_salesman,tb_division,tb_commgroup
			where tb_salesman.salescode=tb_invoice.salescode 
			and tb_invoice.salescode=$row[code]
			and tb_invoice.commgroupcode=tb_commgroup.commgroupcode 
			and tb_division.divisionid = tb_commgroup.divisionid
			and tb_invoice.validate='1'
			and MONTH(invoicedate)='".$_SESSION['bulan']."' 
			and YEAR(invoicedate)='".$_SESSION['tahun']."' 
			".$strwhere."");
			
			$suminv=mysql_num_rows($inv);
			$ttu=read_write("SELECT tb_invoice.salescode,sum(tb_ttu.payment-tb_ttu.ppn_payment) as colnet 
			FROM tb_invoice ,tb_ttu  
			WHERE tb_invoice.invoiceno=tb_ttu.invoiceno 
			AND tb_invoice.salescode='$row[code]'
			and tb_invoice.validate='1'			
			and MONTH(ttudate)='".$_SESSION['bulan']."' 
			and YEAR(ttudate)='".$_SESSION['tahun']."' 
			GROUP BY tb_invoice.salescode");
			$ttu=mysql_fetch_array($ttu);
			
	xlsWriteLabel($no_row,0,$row['salesname']);
	xlsWriteLabel($no_row,1,$suminv);
	xlsWriteLabel($no_row,2,$row['pricenet']);
	xlsWriteLabel($no_row,3,$row['ppnnet']);
	xlsWriteLabel($no_row,4,$row['subtotal']);
	xlsWriteLabel($no_row,5,$ttu['colnet']);
}
elseif ($ver==6) {
	 
//----------------------------------------------------------	
			
			$rkpthn=read_write("SELECT  sum(totalsales+tax-totalreturn-ppnreturn) as pricenet,MONTH(tb_invoice.invoicedate) as month
						FROM tb_invoice,tb_salesman,tb_commgroup,tb_division
						WHERE tb_invoice.salescode = tb_salesman.salescode 
						and tb_salesman.salescode='$row[salescode]'
						and tb_invoice.commgroupcode=tb_commgroup.commgroupcode
						and tb_commgroup.divisionid=tb_division.divisionid
						and tb_invoice.validate='1'
						AND (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) 	
						and YEAR(invoicedate)='".$_SESSION['tahun']."' 
			
			 			".$strwhere ."
						group by month
						ORDER BY  month ");
						$k=1;
						$sumpricenetver = "";
				While ($rkp=mysql_fetch_array($rkpthn))
				{
					$bln=$rkp['month'];
					for ($i=$k;$i<=$bln;$i++)
					{
					if($bln==$i){
					xlsWriteLabel($no_row,$k,$rkp['pricenet']);
					$sumpricenetver += $rkp['pricenet'];
					}
					else {
					xlsWriteLabel($no_row,$k,"-");
					}
					
					$k++;
					
					}
						
				}
					for($i=$k;$i<=$_SESSION['jmlbln'];$i++)
					{
					xlsWriteLabel($no_row,$i,"-");	
					}
					xlsWriteLabel($no_row,$i,"$sumpricenetver");
					//$result = $result."<td align=right>".number_format($sumpricenetver)."</td>";
					
	xlsWriteLabel($no_row,0,$row['salesname']);
	
	}
	if ($booladdrow == 1) { $no_row++; }
	
}
//---------------------------------------------------
if ($ver==2) {
	$res = read_write($_SESSION['querysbcn']);
	while ($row = mysql_fetch_array($res)) {
		if ($row['qty_return']<>'' && $row['qty_return']>0) {
					
			$invoiceno = trim($row['invoiceno']);	

			if ($previnvno == '' || $invoiceno <> $previnvno) {
				$npwp = trim($row['npwp']);
				$cncode = trim($row['cncode']);
				$branch = substr($invoiceno, 2, 2);
				if ($npwp <> '') {
					$taxtype = substr($row['taxno'],0,3);				
				} else {
					$taxtype = '';				
				}
				$taxstatus = 'Retur';
				
				
				if (trim($row['companyname'])<>'') {
					$customer = trim($row['companyname']);
				} else {
					$customer = trim($row['personname']);
				}

				xlsWriteLabel($no_row,0,$taxtype);	
				xlsWriteLabel($no_row,1,$taxstatus);
				xlsWriteLabel($no_row,2,$row['taxno']);
				xlsWriteLabel($no_row,3,$customer);
				xlsWriteLabel($no_row,4,$npwp);
			}
			xlsWriteLabel($no_row,5,$row['productname']);
			xlsWriteLabel($no_row,6,$row['volume']);
			xlsWriteLabel($no_row,7,$row['unit']);
			
			$price = 0-$row['price'];
			$totalprice = 0-$row['totalprice'];
			$ppn = 0 - $row['ppnreturn'];
			$totalsales = 0 - $row['totalreturn'];

			xlsWriteLabel($no_row,8,$price);
			xlsWriteLabel($no_row,9,$totalprice);
			
			if ($previnvno == '' || $invoiceno <> $previnvno) {
				$discount = ''; 
				xlsWriteLabel($no_row,10,$discount);
				xlsWriteLabel($no_row,11,$totalsales);
				if ($npwp <> '') {
					xlsWriteLabel($no_row,12,$ppn);
				} else {
					xlsWriteLabel($no_row,13,$ppn);
				}
				xlsWriteLabel($no_row,14,$cncode);
				
				xlsWriteLabel($no_row,15,$row['cn_date']);
				xlsWriteLabel($no_row,16,$row['divisionname']);
				xlsWriteLabel($no_row,17,$arr_branch[$branch]);
				xlsWriteLabel($no_row,18,$row['salesname']);
			}

			$previnvno = $invoiceno;
			$no_row++;
		}
	}
} elseif ($ver==3) {
	$no_row++;
	xlsWriteLabel($no_row,5, "Total: ");

	xlsWriteLabel($no_row,6,$_SESSION['sumsubtotal']);
	xlsWriteLabel($no_row,7,$_SESSION['sumppn']);
	xlsWriteLabel($no_row,8,$_SESSION['suminvoiceprice']);
}
elseif ($ver==5) {
	$no_row++;
	xlsWriteLabel($no_row,1, "Total: ");
	xlsWriteLabel($no_row,2,$_SESSION['sumpricenet'] );
	xlsWriteLabel($no_row,3,$_SESSION['sumppnnet']);
	xlsWriteLabel($no_row,4,$_SESSION['sumsubtotal']);
	xlsWriteLabel($no_row,5,$_SESSION['sumcolnet']);
}
elseif ($ver==6) {
	$no_row++;
	xlsWriteLabel($no_row,0, "Total: ");
	for($i=1;$i<=$_SESSION['jmlbln'];$i++)
	{
		if($_SESSION['sumpricenethor'.$i])
		{
		xlsWriteLabel($no_row,$i,$_SESSION['sumpricenethor'.$i]);
		}
		else
		{
		xlsWriteLabel($no_row,$i,"-");
		}
	}
	xlsWriteLabel($no_row,$_SESSION['jmlbln']+1,$_SESSION['totsumpricenet']);
	}
		
xlsEOF();
exit();


?>

	
