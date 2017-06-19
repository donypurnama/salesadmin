<?php include('../constant.php');?>
<?php include('../database.php');?>
<?php
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	
	$sid = $_GET['sid'];
	$bid = $_GET['bid'];
	
	
	if ($sid == '0') { $sid = ""; } 
	
	
	$l   = $_GET['l'];		//faktur lama
	$p 	= $_GET['p'];
	$cid = $_GET['c'];
	
	
	
	if ($cid == '0') { $cid = ""; }
	$update = $_GET['update'];
	$delete = $_GET['delete'];
	$bcalcsales = $_GET['bcalcsales'];
	
	
	if ($delete == '1' && ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator'))
	{
			if ($branch <> '') {
			
			/*------------------ mencari tgl akhir ------------------*/
			$res = read_write("select * from tb_sentdata ");
			$row = mysql_fetch_array($res);
			list($y,$m,$d) = explode('-',$row['lastsent']);
			$tglawal = $y . '-' . $m . '-' . $d;
			$n = 1;
			// menentukan timestamp 10 hari berikutnya dari tanggal hari ini
			$nextN = mktime(0, 0, 0, $m + $n, $d, $y);
			// menentukan timestamp 10 hari sebelumnya dari tanggal hari ini
			$prevN = mktime(0, 0, 0, $m - $n, $d, $y);
		 	$next = date("Y-m-d", $prevN);
			/*----------------------------------------------------------*/			


			
			/*------------------ DELETE ALL TB_DELINVOICE ------------------*/
			$pros = hapusdata(tb_delinvoice, "last_update < '".$next."'");
			
			
			/*------------------ INSERT TO  TB_DELINVOICE ------------------*/
			//$pros = tambahdata(tb_delinvoice, invoiceno, last_update, sentstatus, $sid, $today, '1');
			
			
				/***** INSERT INVOICENO TO TB_DELINVOICE   *******/
				$res = read_write("INSERT INTO tb_delinvoice (invoiceno, last_update, sentstatus) VALUES ('".$sid."','".$today."',0)");
				/*----------------------------------------------------*/
			}
			/*-------DELETE TB INVOICE-----*/
			$res = read_write("delete from tb_invoiceitems where invoiceno = '".$sid."'");
			$res = read_write("delete from tb_invoice where invoiceno = '".$sid."'");
			/*----------------------------------------------------*/
			
		if ($p == '1')
		{
			//echo "<script>document.location.href='personal.php?bid='".$bid."''</script>";
			Header('Location: personal.php?bid='.$bid);
		}
		else
		{
			//echo "<script>document.location.href='corporate.php?c='".$c."''</script>";
			Header('Location: corporate.php?c='.$cid);
		}
		
	} elseif ($delete==2) {
		$invitemid = $_GET['invitemid'];
		$res = read_write("select rank, qty_return from tb_invoiceitems where invitemid=".$invitemid);
		$rw = mysql_fetch_array($res);
		$rank = $rw['rank'];
		$qty_return = $rw['qty_return'];
		if ($qty_return > 0) {
			$errprod = "<br>The product you want to delete has Nota Credit. Please look at the Nota Credit Link";
		} else {
		$res = read_write("delete from tb_invoiceitems where invitemid = ".$invitemid);
		$res = read_write("update tb_invoiceitems set rank = rank-1 where invitemid=".$invitemid." and rank>".$rank);
		$res = read_write("UPDATE tb_invoice SET 
								usercode		='".$_SESSION['user']."',
								last_update     ='".$today."',
								sentstatus 		= 0
							WHERE 
								tb_invoice.invoiceno = '".$sid."'");	
		}
	}
	$error = '';	
	if ($update == 1)
	{
		$invoiceno 		= $_POST['invoiceno'];		
		$seller 		= $_POST['seller'];
		$custpo 		= $_POST['custpo'];
		$transactdate 	= $_POST['transactdate'];
		$invoicedate 	= $_POST['invoicedate'];
		$commdate 		= $_POST['commdate'];
		$sid 			= $_GET['sid'];
		$taxno = $_POST['taxno'];
		$taxofficer = $_POST['taxofficer'];
		$salesofficer = $_POST['salesofficer'];
		$currency = $_POST['rdocurr'];
		$kurs = $_POST['kurs'];
		$notesusd = $_POST['notesusd'];
		if ($kurs == 0) { $kurs=1; }
		$rdotax = $_POST['rdotax'];
		$term = $_POST['term'];
		$days = $_POST['days'];
		
		$commgroupcode = $_POST['commgroupcode'];
		$dbcommgroupcode = $_POST['dbcommgroupcode'];

		if ($days=='') { $days=0; }
		$rssales = read_write("select trainer, district_supervisor from tb_salesman where salescode='".$seller."'");
		$rwsales = mysql_fetch_array($rssales);
		$trainer = $rwsales['trainer'];
		$district_supervisor = $rwsales['district_supervisor'];
		
		if ($sid <> '') 
		{	//UPDATE FAKTUR
			
			$queryupd = "update tb_invoice 
									set 
										salescode		='".$seller."', 
										currency		='".$currency."', 
										kurs			='".$kurs."', 
										transactdate	='".$transactdate."', 
										invoicedate		='".$invoicedate."', 		
										commdate		='".$commdate."',
										custpo			='".$custpo."',
										usercode		='".$_SESSION['user']."',
										invtax			=".$rdotax.",
										term			=".$term.",
										days			=".$days.",
										commgroupcode	='".$commgroupcode."', 
										trainer			='".$trainer."', 
										district_supervisor	='".$district_supervisor."',  
										taxno			='".$taxno."', 	
										taxofficer		='".$taxofficer."',
										salesofficer	='".$salesofficer."',
										last_update     ='".$today."',
										notesusd         ='".$notesusd."',
										sentstatus 		= 0";

			if ($_SESSION['sidcust'] <> '') { //change customer if sidcust not empty
				$queryupd .= ", buyercode='".$bid."' "; 
			}
			$queryupd .= " where tb_invoice.invoiceno='".$sid."'";

			$res = read_write($queryupd); 

			$_SESSION['sidcust'] = '';					
								
			$error = "Data has been successfully updated ...";		
			
			$bchangeinvno = false;
			$invoiceno = $_POST['invoiceno'];  //to change invoice number by admin / root
			if (trim($invoiceno) <> "") {
				if ($invoiceno <> $sid) {
					$bchangeinvno = true;
				}
			}

			if ($commgroupcode <> $dbcommgroupcode && strlen($sid)==14) { //to change invoice number coz commission type changed
				$rsdiv = read_write("select tb_commgroup.divisionid, tb_division.divisioninv from tb_commgroup, tb_division where commgroupcode='".$commgroupcode."' and tb_division.divisionid=tb_commgroup.divisionid");
				$rwdiv = mysql_fetch_array($rsdiv);
				$divisionid = $rwdiv['divisionid'];
				$divisioninv = $rwdiv['divisioninv'];
			
				$rstemp = read_write("select substring(invoiceno, 7, 4) as dateym, substring(invoiceno, length(invoiceno)-2) as nbr from tb_invoice where invoiceno='".$sid."'");	
				$rowtemp = mysql_fetch_array($rstemp);
				$invoiceno = "FK".$branch.$divisioninv.$rowtemp['dateym']."-".$rowtemp['nbr'];

				$bchangeinvno = true;
			}

			if ($bchangeinvno == true) {
				read_write("update tb_invoice set invoiceno='".$invoiceno."' where invoiceno='".$sid."'");
				read_write("update tb_invoiceitems set invoiceno='".$invoiceno."' where invoiceno='".$sid."'");
				read_write("update tb_ttu set invoiceno='".$invoiceno."' where invoiceno='".$sid."'");
				$sid = $invoiceno;
			}
		} else {			//TAMBAH FAKTU (BARU & LAMA) 	
			if($l == 1) {		
			//TAMBAH FAKTUR LAMA			
			/*********HITUNG JUMLAH**************/
			$res = read_write("select count(invoiceno) as cnt from tb_invoice where invoiceno='".$invoiceno."'");
		    $jum = mysql_fetch_row($res);
			/*----------------------------------------------------------------*/
				if($invoicedate < $oldinv_date) {
					if($jum[0] == 0) 
					{
							$res = read_write("insert into tb_invoice (
											buyercode, 
											invoiceno, 
											salescode, 
											currency, 
											kurs, 
											transactdate, 
											invoicedate, 
											commdate, 
											custpo, 
											usercode, 
											invtax, 
											term, 
											days, 
											totalreturn, 
											ppnreturn,
											commgroupcode, 
											trainer, 
											district_supervisor,  
											taxno,	 
											taxofficer,
											salesofficer,
											notesusd,
											createddate,
											last_update,
											sentstatus
											) 
														values (
														'".$bid."',
														'".$invoiceno."',
														'".$seller."',
														'".$currency."',
														'".$kurs."',
														'".$transactdate."',
														'".$invoicedate."',
														'".$commdate."',
														'".$custpo."',
														'".$_SESSION['user']."',
														".$rdotax.",
														".$term.",
														".$days.",0,0,
														'".$commgroupcode."',
														'".$trainer."',
														'".$district_supervisor."', 
														'".$taxno."',
														'".$taxofficer."',
														'".$salesofficer."',
														'".$notesusd."',
														'".$today."',
														'".$today."',
														0
														)");	
								$sid = $invoiceno;
								$error = "(OLD) Data has been successfully inserted ...";	
						
					} else { 
						$error = "Sorry ........ Invoice Number already exists"; 
						$sid = "";
					}	
				} else {
					$error = "Sorry ........ Please check your invoice date"; 
				}	
	
			} else {
				/*--------------------AUTO NUMBERING FOR invoiceno-----------------------------*/
				/*--------------------TAMBAH FAKTUR BARU -----------------------------*/
				$rsdiv = read_write("select 
										tb_commgroup.divisionid, 
										tb_division.divisioninv 
									from 
										tb_commgroup, 
										tb_division 
									where 
										commgroupcode='".$commgroupcode."' 
									and 
										tb_division.divisionid=tb_commgroup.divisionid");
				
				$rwdiv = mysql_fetch_array($rsdiv);
				$divisionid = $rwdiv['divisionid'];
				$divisioninv = $rwdiv['divisioninv'];
			
				$rstemp = read_write("select 
										max(substring(invoiceno, length(invoiceno)-2)) as nbr 
									from 
										tb_invoice 
									where
										year(invoicedate)=".date('Y')." 
									and 
										month(invoicedate)=".date('n')." 
									and 
										invoiceno like 'Fk".$branch."%'");						
				if(mysql_num_rows($rstemp) > 0) {
				$rowtemp = mysql_fetch_array($rstemp);
				$invtemp = (int) $rowtemp ['nbr'];
				$invtemp++;					
				
					
				if (strlen($invtemp)==1) {
					$invtemp = '00'.$invtemp;
				} elseif (strlen($invtemp)==2) {
					$invtemp = '0'.$invtemp;
				}
				
				} else {
					$invtemp = '001';
				}
				$sid = "FK".$branch.$divisioninv.date("ym")."-".$invtemp;
			
			$res = read_write("insert into tb_invoice (
									buyercode, 
									invoiceno, 
									salescode, 
									currency, 
									kurs, 
									transactdate, 
									invoicedate, 
									commdate, 
									custpo, 
									usercode, 
									invtax, 
									term, 
									days, 
									totalreturn, 
									ppnreturn,
									commgroupcode, 
									trainer, 
									district_supervisor,  
									taxno, 
									taxofficer,
									salesofficer,
									notesusd,
									createddate,
									last_update,
									sentstatus
									) 
												values (
												'".$bid."',
												'".$sid."',
												'".$seller."',
												'".$currency."',
												'".$kurs."',
												'".$transactdate."',
												'".$invoicedate."',
												'".$commdate."',
												'".$custpo."',
												'".$_SESSION['user']."',
												".$rdotax.",
												".$term.",
												".$days.",0,0,
												'".$commgroupcode."',
												'".$trainer."',
												'".$district_supervisor."', 
												'".$taxno."',
												'".$taxofficer."',
												'".$salesofficer."',
												'".$notesusd."',
												'".$today."',
												'".$today."',
												0
												)");	
				$error = "Database has been successfully inserted ...";
			}
		}
		$invtax = $constant_invtax[$rdotax];
	} elseif ($update == 2) {
		$discount = $_POST['discount'];
		if ($discount=='') { $discount = 0; }
		$tax = $_POST['tax'];
		$ppnusd = $_POST['ppnusd'];
		if ($tax=='') { $tax = 0; }
		$totalsales = $_POST['totalsales'];
		$totalsalesusd = $_POST['totalsalesusd'];
		$hid_rdotax = $_POST['hid_rdotax'];

		if ($hid_rdotax == 0) {  //ppn include	
			$ppn_for_outstanding = $tax;
		} elseif ($hid_rdotax == 1) { //ppn exclude
			$ppn_for_outstanding = $tax;
		} else if ($hid_rdotax == 2) { //ppn kbn
			$tax = $totalsales * 0.1;
			$ppn_for_outstanding = 0;
		} else if ($hid_rdotax == 3) { // ppn ssp			
			$tax = $totalsales * 0.1;
			$ppn_for_outstanding = 0;
		}
		
		if ($totalsales=='') {
			$message = "Please insert total sales";
		} else {
			$queryupd = "update tb_invoice set 
									discount	=".$discount.", 
									tax			=".$ppn_for_outstanding.", 
									ppn			=".$tax.", 
									totalsales	=".$totalsales.",";
			if (($ppnusd <> '' &&  $ppnusd <> '0') || ($totalsalesusd <> '' && $totalsalesusd <> '0')) {
				$queryupd .= "ppnusd=".$ppnusd.", totalsalesusd=".$totalsalesusd.", ";
			} else {
				$queryupd .= "ppnusd=0, totalsalesusd=0, ";
			}
			$queryupd .= "usercode	='".$_SESSION['user']."', 
									last_update	='".$today."',
									sentstatus 	=0
								where 
									invoiceno = '".$sid."'";

			$res = read_write($queryupd);
			
			$message = "Database has been successfully updated ...";
			
		}
	} elseif ($update == 3) {
		$res = read_write("update tb_invoice set 
								validate	=1, 
								usercode	='".$_SESSION['user']."', 
								last_update ='".$today."',
								sentstatus 	=0
							where 
								invoiceno='".$sid."'");
		$message = "Database has been successfully Validated ...";
		
	} elseif ($update == 4) {
		$res = read_write("update tb_invoice set 
								validate	=0, 
								usercode	='".$_SESSION['user']."', 
								last_update ='".$today."',
								sentstatus 	=0
							where 
								invoiceno='".$sid."'");
		$message = "Database has been successfully Un-Validated ...";
		
	}

	
	if ($cid <> "" && $cid <> $branch.".000000") {
	
		if ($sid <>"") {
		
			$res = read_write("SELECT 
									tb_invoice.* ,
									tb_company.companyname, 
									tb_buyer.personname, 
									tb_commgroup.divisionid
								FROM 
									tb_invoice, 
									tb_company, 
									tb_buyer,
									tb_deliveryaddr,
									tb_commgroup 
								WHERE 
									tb_invoice.buyercode=tb_buyer.buyercode 
								AND 
									tb_company.companycode=tb_deliveryaddr.companycode 
								AND 
									tb_deliveryaddr.deliverycode=tb_buyer.deliverycode 
								AND
									tb_commgroup.commgroupcode=tb_invoice.commgroupcode 
								and 
									tb_invoice.invoiceno='".$sid."'"); 

			$count_row = mysql_num_rows($res);
			//if no record it is probably because of one of data doesn't exist. i.e commgroupcode is null
			if ($count_row == 0) {
				mysql_free_result($res);
				$res = read_write("SELECT 
									tb_invoice.* ,
									tb_company.companyname, 
									tb_buyer.personname
									
								FROM 
									tb_invoice, 
									tb_company, 
									tb_buyer,
									tb_deliveryaddr
									
								WHERE 
									tb_invoice.buyercode=tb_buyer.buyercode 
								AND 
									tb_company.companycode=tb_deliveryaddr.companycode 
								AND 
									tb_deliveryaddr.deliverycode=tb_buyer.deliverycode								
								and 
									tb_invoice.invoiceno='".$sid."'");
			}
				
		} 		else {
			$res = read_write("SELECT tb_company.companyname, tb_buyer.personname FROM tb_company, tb_buyer, tb_deliveryaddr 
			WHERE tb_company.companycode=tb_deliveryaddr.companycode
			AND tb_deliveryaddr.deliverycode=tb_buyer.deliverycode AND tb_buyer.buyercode='".$bid."'");
		}
		
	} else {

		
	if ($sid <> "") {
			$res = read_write("SELECT 
									tb_invoice.*, 
									tb_buyer.personname, 
									tb_commgroup.divisionid 
								FROM 
									tb_invoice, 
									tb_buyer, 
									tb_commgroup 
								WHERE 
									tb_invoice.buyercode=tb_buyer.buyercode 
								AND 
									tb_commgroup.commgroupcode=tb_invoice.commgroupcode 
								AND 
								tb_invoice.invoiceno='".$sid."'"); 
			
					$count_row = mysql_num_rows($res);
			//if no record it is probably because of one of data doesn't exist. i.e commgroupcode is null
			if ($count_row == 0) {
				mysql_free_result($res);
				$res = read_write("SELECT 
									tb_invoice.* ,
									
									tb_buyer.personname
									
								FROM 
									tb_invoice, 
									
									tb_buyer,
									tb_deliveryaddr
									
								WHERE 
									tb_invoice.buyercode=tb_buyer.buyercode 
								
								AND 
									tb_deliveryaddr.deliverycode=tb_buyer.deliverycode								
								and 
									tb_invoice.invoiceno='".$sid."'");
		}							
				
	} else {
		
			$res = read_write("SELECT tb_buyer.personname FROM tb_buyer where buyercode = '".$bid."'");
		}
	}

	$row = mysql_fetch_array($res);
	
	if ($cid <> "" && $cid <>  $branch.".000000" && $cid <> "01.000000" && $cid <> "00.000000") { 
		$c_name = stripslashes($row['companyname']); 
		$p_name = stripslashes($row['personname']); 
		if (trim($p_name) == '') { $p_name = 'NoName'; }
		
	} else {
		$c_name = ''; 
		$p_name = stripslashes($row['personname']); 
	}
	
	$buyercode = stripslashes($row['buyercode']);
	
	if ($_SESSION['sidcust'] <> '') { // change customer display if sidcust is not empty
		if ($cid <> "" && $cid <>  $branch.".000000") { 
			$rs_name = read_write("select companyname from tb_company where companycode='".$cid."'");
			$rw_name = mysql_fetch_array($rs_name);
			$c_name = stripslashes($rw_name['companyname']); 
			mysql_free_result($rs_name);
		}
		
		$rs_name = read_write("select personname from tb_buyer where buyercode='".$bid."'");
		$rw_name = mysql_fetch_array($rs_name);
		$p_name = stripslashes($rw_name['personname']); 
		
	}

	
	$sid = stripslashes($row['invoiceno']);
	
	$taxno = stripslashes($row['taxno']);	
	$seller = stripslashes($row['salescode']);
	$discount = stripslashes($row['discount']);
	if ($discount==0) { $discount = ''; }
	$currency = stripslashes($row['currency']);
	$kurs = stripslashes($row['kurs']);
	if ($kurs==0 || $kurs==1) { $kurs=''; }

	$transactdate = $row['transactdate'];	
	$custpo = $row['custpo'];	
	$invoicedate = $row['invoicedate'];	
	$commdate = $row['commdate'];	
	$taxofficer = $row['taxofficer'];
	$salesofficer = $row['salesofficer'];
	
	
	$rdotax = $row['invtax'];
	
	$invtax = $constant_invtax[$rdotax];

	$tax = $row['tax'];	
	$ppn = $row['ppn'];
	$ppnusd = $row['ppnusd'];
	$notesusd = $row['notesusd'];
	
	
	if ($tax==0) { $tax = ''; }
	
	$ori_ppn = $_POST['ori_ppn'];
	if ($ori_ppn <> "" && $ori_ppn <> $tax) { $bcalcsales=1; }
	$totalsales = $row['totalsales'];
	$totalsalesusd	 = $row['totalsalesusd'];
	$validate = $row['validate'];
	
	
	$term = $row['term'];
	
	$days = $row['days'];
	if ($days==0) { $days = ''; }
	$totalreturn = $row['totalreturn'];

	$commgroupcode = $row['commgroupcode'];
	$divisionid = $row['divisionid'];
	
	if ($commgroupcode <> "") {
		$rsdiv = read_write("select divisionname from tb_division where divisionid='".$divisionid."'");
		$rowdiv = mysql_fetch_array($rsdiv);

		$commstr = $rowdiv['divisionname'];
		$rscomm = read_write("select * from tb_commrules where commgroupcode='".$commgroupcode."'");
		while ($rowcomm = mysql_fetch_array($rscomm)) {
			$startdate = $rowcomm['startdate'];
			$enddate = $rowcomm['enddate'];
			$commstr = $commstr."<br>".$startdate." - ".$enddate.": ".$rowcomm['percent_comm']." %";
		}
		$commstr = $commstr."<br>> ".$enddate.": 0<br>";
	}

	

	
	
	


	
?>
<html>
	<head>
	<title>Add/Edit Invoice</title>
			<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language="javascript" src="cal2.js">
			/*
			Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
			Script featured on/available at http://www.dynamicdrive.com/
			This notice must stay intact for use
			*/
		</script>
		<script language="javascript" src="cal_conf2.js"></script>
		<script language="javascript">
			function validate()
			{		
				var counter; 
				
				var pilihan_tax = false;
				var c=document.frmSales;
				
				for (counter = 0; counter < c.rdotax.length; counter++) {
					if (c.rdotax[counter].checked)
					pilihan_tax = true;
				}
			
				if (document.frmSales.seller.value == "") {
					alert("Please fill the Salesman");
					return false;
				} else if (document.frmSales.transactdate.value == "") {
					alert("Please fill up the Transaction Date");
					return false;					
				} else if (document.frmSales.commgroupcode.value == "") {
					alert("Please choose Commission type");
					return false;
				} else if (!pilihan_tax) {
					alert ("Please choose Tax type")
					return false;
				} else {
					return true;
				}
			}
			function openaddproduct(sid, bid,cid,p, divid, currency) {
			window.open('addproduct.php?sid='+sid+'&bid='+bid+'&c='+cid+'&p='+p+'&divid='+divid+'&currency='+currency+'&x=1','mywin','scrollbars=1,width=550,height=450,resizable=1');
				
			}
			function openeditproduct(invitemid,productcode,sid,bid,cid,p, divid, currency) {								 
			window.open('product.php?invitemid='+invitemid+'&productcode='+productcode+'&sid='+sid+'&bid='+bid+'&c='+cid+'&currency='+currency+'&p='+p+'&divid='+divid, 'mywin','scrollbars=1, width=550, height=450, resizable=1');
			}

			function deleteproduct(invitemid,sid,bid,cid,p) {
				if (confirm("Are you sure you want to delete this product?")) {					
				location.href='sales.php?delete=2&sid='+sid+'&bid='+bid+'&invitemid='+invitemid+'&c='+cid+'&p='+p+'&bcalcsales=1';
				}
			}

			function opencommission() {
				var scommgroupcode = document.forms[0].commgroupcode.value;
				var sdivisionid = document.forms[0].divisionid.value;
				window.open('commission.php?commgroupcode='+scommgroupcode+'&divisionid='+sdivisionid+'&search=1','mywin','scrollbars=1,width=550,height=450,resizable=1');
				
			}

			function deletesales(sid,bid,cid,p) {
		
				if (confirm("Deleting invoice will also delete invoice items\nAre you sure?")) {
					if (confirm("Are you really sure?")) {
						location.href='sales.php?delete=1&sid='+sid+'&bid='+bid+'&c='+cid+'&p='+p;
					}
				}
			}
			function dovalidate(sid,bid,cid,p) {
				if (confirm("Are you sure to validate this data?")) {
					location.href='sales.php?update=3&sid='+sid+'&bid='+bid+'&c='+cid+'&p='+p;
				}
			}
			function dounvalidate(sid,bid,cid,p) {
				if (confirm("Are you sure to Un-validate this data?")) {
					location.href='sales.php?update=4&sid='+sid+'&bid='+bid+'&c='+cid+'&p='+p;
				}
			}

			function calcdiscount() {
				with (document.frmdiscount) {
					var sumprice 	= sum_price.value;
					var sumpriceusd = sum_priceusd.value;
					var discountvalue; 
					
					
					if (discount.value !== '' && discount.value > 0) {
						discountvalue 	= sumprice * discount.value / 100;
						sumprice 		= sumprice - discountvalue;	
						
						discountvalue = sumpriceusd * discount.value / 100;
						sumpriceusd = sumpriceusd - discountvalue;	
					}					
					var ppn, calc_sales, ppn_usd, calc_salesusd;	
					
					if (document.frmSales.rdotax[0].checked) {						
						ppn = sumprice / 11;
						ppn_usd = sumpriceusd / 11;
						calc_sales = ppn * 10;						
						calc_salesusd = ppn_usd * 10;
						
					} else if (document.frmSales.rdotax[1].checked) {
						ppn = sumprice * 0.1;
						calc_sales = sumprice;
						
						ppn_usd = sumpriceusd * 0.1;
						calc_salesusd = sumpriceusd;
						
					} else if (document.frmSales.rdotax[2].checked) {
						ppn = sumprice * 0.1;
						calc_sales = sumprice ;
						
						ppn_usd = sumpriceusd * 0.1;
						calc_salesusd = sumpriceusd ;
						
					} else if (document.frmSales.rdotax[3].checked) {
						ppn = sumprice * 0.1;
						calc_sales = sumprice;		
						
						ppn_usd = sumpriceusd * 0.1;
						calc_salesusd = sumpriceusd;											
					} 
					tax.value = ppn;
					totalsales.value = calc_sales;
					
					if (typeof(ppnusd) != 'undefined' && typeof(totalsalesusd) != 'undefined') {
						ppnusd.value = ppn_usd;
						totalsalesusd.value = calc_salesusd;							
					}
				}
			}
			
			
			
		</script>
	</head>
<body>
<?php include('../menu.php'); ?>
<br>
<?php 

if ($_SESSION['groups'] == 'administrator' || $_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'sales' || $_SESSION['groups'] == 'finance') { 
	if($l == '') { ?>
		<div class="header icon-48-menumgr"> Informasi Order | 
		<a href='return.php?sid=<?php echo $sid; ?>&bid=<?php echo $bid;?>&c=<?php echo $cid;?>&p=<?php echo $p;?>' class=smalllink>Nota Credit</a>			</div>
	<?} else { ?>
		<div class="header icon-48-menumgr"> Informasi Order | 
		<a href='return.php?sid=<?php echo $sid; ?>&bid=<?php echo $bid;?>&c=<?php echo $cid;?>&p=<?php echo $p;?>&l=<? echo $l; ?>' class=smalllink>Nota Credit</a>	</div>
	<?} ?>
<?php } else  {?><div class="header icon-48-menumgr"> Informasi Order </div>
<?php }?>

<form name="frmSales" action="sales.php?update=1&l=<?php echo $l;?>&sid=<?php echo $sid; ?>&bid=<?php echo $bid;?>&c=<?php echo $cid; ?>&p=<?php echo $p; ?>" method="POST" onSubmit="return validate()" >
<input type=hidden name=ori_ppn value=<?php echo $rdotax; ?>>
<table border='0' cellspacing='1' cellpadding='1' width="60%">
<tr><td colspan=2 align='center'>
<font color=#ff0000 class='small'><b><?php echo $error.$errprod; ?></b></font></td>
</tr>
<?php 
	if ($totalreturn <> "" && $totalreturn <> "0") { 
		
?><tr class="small"><td colspan=2><font color=red><b>This invoice consist Nota Credit</b></font></td></tr>
<?php }

	if ($cid <> "" && $cid <> $branch.".0000000" && $cid <> "01.0000000" && $cid <> "00.0000000") { 
?>
		<tr class="small">
					<td align="right" >Company:</td>
					<td><a href="corporate.php?c=<?php echo $cid; ?>" class=smalllink><?php echo $c_name; ?></a></td>
				</tr>
				
<?php } ?>
				<tr class="small">
					<td align="right" >Contact Person:</td>
					<td><a href="personal.php?bid=<?php echo $bid; ?>&cid=<?php echo $cid;?>" class=smalllink><?php echo $p_name; ?></a>
			
			<?php 
				if ($sid<>"") { ?>
			&nbsp;|&nbsp; <a href="changecust.php?sid=<?php echo $sid;?>" class=smalllink>Change Customer</a>
			<? } 
				
			?>
				</td></tr>
			
				<tr>
					<td width='18%' align="right" class="small">Salesman:</td>
					<td width='80%'>
						<select name="seller" class="forms">
						<option value="">--
						<?php
								$res = read_write("select 
														salescode,salesname 
													from 
														tb_salesman 
													where 
														active=1 
													and 
														salescode like '".$branch.".%'
													order by 
														salesname ASC
													");
								$count = mysql_num_rows($res);
								for ($i=0;$i<$count;$i++)
								{
									$row = mysql_fetch_array($res);
									if ($seller == stripslashes($row['salescode']))
									{
										echo "<option value='".stripslashes($row['salescode'])."' selected>".stripslashes($row['salesname']);
									}
									else
									{
									echo "<option value='".stripslashes($row['salescode'])."'>".stripslashes($row['salesname']);
									}
								}
							?>
						</select>
						
					</td>
				</tr>
				<tr>
					<td align="right" class="small">Customer PO:</td>
					<td><input type="text" name="custpo" value="<?php echo $custpo; ?>" class="tBox" size=20></td>					
				</tr>
		<tr>
			<td align="right" class="small">Transaction Date:</td>
			<td class="small">		
			<?
			if ($sid == '' && $l == '') {
				echo "<input type='text' name='transactdate' value='".$today."' class='tBox' size='10' maxlength='10'> "; 
				?><a href="javascript:showCal('Calendar1')" class=smalllink>Select Date</a></td><?
			} else  {
			?>
				<input type="text" name="transactdate" value="<?php echo $transactdate; ?>" class="tBox" size=10 maxlength=10> <a href="javascript:showCal('Calendar1')" class=smalllink>Select Date</a></td>					
			<? } ?>
		</tr>
		<tr>
			<td align="right" class="small">Invoice Date:</td>
			<td class="small">
			<?php 
			if ($sid == '' && $l == '') {
				echo "<input type='text' name='invoicedate' value='".$today."' class='tBox' size='10' maxlength='10'>";
				?><a href="javascript:showCal('Calendar2')" class=smalllink>Select Date</a></td><?
			} else {
			?>
				<input type="text" name="invoicedate" value="<?php echo $invoicedate; ?>" class="tBox" size=10 maxlength=10> 
				<a href="javascript:showCal('Calendar2')" class=smalllink>Select Date</a></td>
			<?} ?>
		</tr>
		<?php
				if($sid <> "") {
					echo "<tr><td align='right' class='small'>Invoice Number:</td><td class='small'>";
					if ($_SESSION['groups'] == 'administrator' || $_SESSION['groups'] == 'root')  {
						echo "<input type=text name='invoiceno' class=tBox size=20 value='".$sid."'>";
					} else {
						echo $sid;
					}
					echo "</td></tr>";
				} 	else if ($l == "1") {
					echo "<tr><td align='right' class='small'>Invoice Number:</td><td class='small'>";
					
					echo "<input type=text name='invoiceno' class=tBox size=20 value='".$sid."'> &nbsp; ";
					echo "</td></tr>";
					
				}
				?>
		<tr>
			<td align="right" class="small">Commission Date:</td>
			<td class="small">
			<?php 
			if ($sid == '' && $l == '') {
				echo "<input type='text' name='commdate' value='".$today."' class='tBox' size='10' maxlength='10'>";
				?><a href="javascript:showCal('Calendar3')" class=smalllink>Select Date</a></td><?
			} else {
			?>
				<input type="text" name="commdate" value="<?php echo $commdate; ?>" class="tBox" size=10 maxlength=10> 
			<a href="javascript:showCal('Calendar3')" class=smalllink>Select Date</a></td>
			<?} ?>			
		</tr>
				<tr valign=top class="small">
					<td align="right" >Commission Type:</td>
					<td><div id=commtype><?php echo $commstr; ?></div><a href='javascript:opencommission()' class=smalllink>Select type</a><input type=hidden name=commgroupcode value=<?php echo $commgroupcode;?>><input type=hidden name=divisionid value='<?php echo $divisionid;?>'>
					<input type=hidden name=dbcommgroupcode value=<?php echo $commgroupcode;?>>
					</td>					
				</tr>
				<tr>
					<td align="right" class="small">Sales Officer: </td>
					<td>
					<select name="salesofficer" class="tBox">
						<option value="">--
						<?php
			if ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator') {
							$res = read_write("select usercode,realname from tb_user where usercode like '".$branch.".%'");
			} else {
						$res = read_write("select usercode,realname from tb_user where groups='sales' and usercode like '".$branch.".%'");
			}
							while ($rwtax = mysql_fetch_array($res)) {
								echo "<option value='".$rwtax['usercode']."'";
								if ($sid == '') { 
									$salesofficer=$default_sa; 
								} 
									if ($salesofficer==$rwtax['usercode']) { echo " selected"; }
									echo ">".$rwtax['realname'];
							}
						?>
					</select>
					
					</td>
				</tr>
				<tr>
					<td align="right" class="small">Tax Number:</td>
					<td>
					<input type="text" name="taxno" value="<?php echo $taxno; ?>" class="tBox" size=20 maxlength=20>
					</td>
				</tr>
				<tr>
					<td align="right" class="small">Tax Officer: </td>
					<td>
					<select name="taxofficer" class="tBox">
						<option value="">--
						<?php
							$res = read_write("select usercode,realname from tb_user where groups='taxhead'");
								while ($rwtax = mysql_fetch_array($res)) {
									echo "<option value='".$rwtax['usercode']."'";
									if ($sid == '') {
										$taxofficer=$default_taxoff;
									}
										if ($taxofficer==$rwtax['usercode']) { echo " selected"; }
										echo ">".$rwtax['realname'];
								}
						?>
					</select>
					</td>
				</tr>
				<tr valign=top>
					<td align="right" class="small">Tax:</td>
					<td class=small>
					<input type=radio name=rdotax value='0' <?php if ($invtax=='inc') { echo "checked"; }?>>&nbsp;Include &nbsp;
					<input type=radio name=rdotax value='1'  <?php if ($invtax=='exc') { echo "checked"; }?>>&nbsp;Exclude<br>
					<input type=radio name=rdotax value='2' <?php if ($invtax=='kbn') { echo "checked"; }?>>&nbsp;KBN &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type=radio name=rdotax value='3'  <?php if ($invtax=='ssp') { echo "checked"; }?>>&nbsp;SSP</td>					
				</tr>
<tr>
	<td align="right" class="small">Currency:</td>
	<td class=small>
		
		<input type=radio name=rdocurr value='Rp.' <?php if ($currency=='Rp.' || $sid == '') { echo "checked"; }?>>&nbsp;IDR &nbsp;&nbsp;
		<input type=radio name=rdocurr value='USD'  <?php if ($currency=='USD') { echo "checked"; }?>>&nbsp;USD &nbsp;&nbsp;
		
		Kurs: <input type="text" name="kurs" value="<?php echo $kurs; ?>" class="tBox" size=5 maxlength=5>
					&nbsp;&nbsp;<font size='1pt'>* Note USD</font>&nbsp;&nbsp;
					<input type="text" name="notesusd" value="<?php echo $notesusd; ?>" class="tBox" size=30 maxlength=50> 
					</td>					
				</tr>

				<tr>
					<td align="right" class="small">Term:</td>
					<td class=small><input type=radio name=term value='0' <?php if ($term==0) { echo "checked"; }?>>&nbsp;Cash 
					<input type=radio name=term value='1'  <?php if ($term==1) { echo "checked"; }?>>&nbsp; Credit &nbsp;Days: 
					<input type="text" name="days" value="<?php echo $days; ?>" class="tBox" size=5 maxlength=5></td>					
				</tr>

				<tr>
					<td colspan=2 height=6></td>
				</tr>
				<tr>
					<td colspan=2 align=center>
					<input type="submit" value="Save" class="tBox" <?php if ($validate==1 || ($_SESSION['groups'] <> 'root' && $_SESSION['groups'] <> 'administrator' && $_SESSION['groups']<>'sales' && $_SESSION['groups']<>'finance')) {echo "disabled"; } ?>>
					<input type="reset" value="Reset" class="tBox">
					
					<?
						if(isset($HTTP_REFERER)) {
							echo "&nbsp;";
						} else { ?>
						<input type="button" value="Back" class="tBox" onclick="location.href='<?php echo $_SESSION['backurl'];?>'">
						
					<?} ?>
	
					
					
					
					<?php if ($sid <> "" || $_GET['sid'] <> "") {
						
					if ( $_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator' ) { ?>
					<input type="button" value="Delete" class="tBox" onClick="deletesales('<?php echo $sid; ?>','<?php echo $bid; ?>','<?php echo $cid; ?>','<?php echo $p; ?>')">
					<?php } 
					} ?>
					</td>
				</tr>
			</table>
		</form>
		<table border=0 cellspacing=0 cellpadding=2 width="100%">
			<tr class=small>
				<th colspan=4 align=left>Product List</th>				
			</tr>
			<tr bgcolor=#e5ebf9 class=header>
			<td><b>Product Name</b></td>
			<td><b>Qty / Unit</b></td>
			<td><b>Price / Unit</b></td>
			<td><b>Sub Total</b></td>
			<td>&nbsp;</td>
			</tr>
        <?php		
		$sum_price = 0;


		if ($sid<>"") {
			$res = read_write("select 
								tb_product.productname,
								tb_invoiceitems.otherproductname,
								tb_invoiceitems.batchno,
								tb_invoiceitems.qty, 
								tb_invoiceitems.price, 
								tb_invoiceitems.priceusd,
								tb_invoiceitems.invitemid, 
								tb_product.productcode,
								tb_product.volume,
								tb_product.unit
							from 
								tb_product, 
								tb_invoiceitems 
							where 
								tb_product.productcode = tb_invoiceitems.productcode 
							and 
								tb_invoiceitems.invoiceno='".$sid."'");
			$cntprod =mysql_num_rows($res);
			if ($cntprod>0) {		
				while ($row = mysql_fetch_array($res)) {
					echo "<tr class=small>";
					echo "<td>".stripslashes($row['productname']);
					if ($row['otherproductname'] <> "") { echo stripslashes($row['otherproductname']); }
					if ($row['batchno'] <> "") {
						echo " &nbsp;".$row['batchno']; }
					echo "</td>";		
					if (substr($row['qty'],strlen($row['qty'])-2)=="00") {
						echo "<td>".number_format($row['qty'],0)." ".$row['unit']."</td>";
					} else {
						echo "<td>".$row['qty']." ".$row['unit']."</td>";
					}
					echo "<td>".number_format($row['price'],0)."&nbsp;IDR&nbsp;&nbsp;";
					if ($row['priceusd'] <> 0 && $currency == 'USD') { 
					echo "<b>".$row['priceusd']."&nbsp; USD </b>";
					}					
					echo "</td>";
					$totalitemprice = $row['qty'] * $row['price'] * $row['volume'];
					$sum_price = $sum_price + $totalitemprice;
					if ($currency == 'USD') {
						$totalitempriceusd = $row['qty'] * $row['priceusd'] * $row['volume'];
						$sum_priceusd = $sum_priceusd + $totalitempriceusd;
					}
					echo "<td>".number_format($totalitemprice,0)."&nbsp;IDR&nbsp;&nbsp;";
					if ($row['priceusd'] <> 0 && $currency == 'USD') { 
					echo "<b>".$totalitempriceusd."&nbsp; USD </b>";
					}
					echo "</td>";
					echo "</td>";
					echo "<td>";
					if ($validate<>1) {
								echo "<a href=\"javascript:openeditproduct('".$row['invitemid']."','".$row['productcode']."','".$sid."','".$bid."','".$cid."','".$p."','".$divisionid."','".$currency."')\" class=smalllink>Edit</a> &nbsp;
							  <a href=\"javascript:deleteproduct('".$row['invitemid']."','".$sid."','".$bid."','".$cid."','".$p."')\" class=smalllink>Delete</a>";
					} else { echo "&nbsp;"; }
					echo "</td></tr>";
				} 				
			} else {
				echo "<tr class=small><td colspan=5>== No product found ==</td></tr>";
			}
		} else {
			echo "<tr class=small><td colspan=5>== No product found ==</td></tr>";
		}
		?>
<tr><td height=4></td></tr>
<tr><td colspan=4>
<input type=button class="tBox" value="Add Product" <?php if ($sid=='' || $validate==1) { echo "disabled"; } ?> onClick="javascript:openaddproduct('<?php echo $sid;?>','<?php echo $bid; ?>','<?php echo $cid;?>','<?php echo $p; ?>','<?php echo $divisionid; ?>','<? echo $currency;?>', '<? echo $currency; ?>')"
<?php if ($validate==1 || ($_SESSION['groups'] <> 'root' && $_SESSION['groups'] <> 'administrator' && $_SESSION['groups']<>'sales' && $_SESSION['groups']<>'finance')) {echo "disabled"; } ?>
></td></tr>
</table>
<form name='frmdiscount' <?php if ($sid=='' || ($cntprod==0 && $l<>1) || $validate==1) { echo "disabled"; } ?> action="sales.php?update=2&sid=<?php echo $sid; ?>&bid=<?php echo $bid;?>&c=<?php echo $cid;?>&p=<?php echo $p;?>" method=post>
<input type=hidden name='sum_price' value='<?php echo $sum_price;?>'>
<input type=hidden name='sum_priceusd' value='<?php echo $sum_priceusd;?>'>
<input type=hidden name='hid_rdotax' value='<?php echo $rdotax; ?>'>
<?php		

if (($tax=='' && $totalsales=='') || $bcalcsales == 1) {
	if (($sum_price > 0) || ($sum_priceusd > 0)) {
		$tax 			= "";
		$totalsales 	= "";
		$totalsalesusd 	= "";
		
		if ($discount <> '' && $discount > 0) {
			$discountvalue 	= $sum_price * $discount / 100;
			$sum_price 		= $sum_price - $discountvalue;
			
			if ($currency == 'USD') {
				$discountvalue 	= $sum_priceusd * $discount / 100;
				$sum_priceusd 	= $sum_priceusd - $discountvalue;
			}
		}
	
				if ($rdotax == 0) {  //ppn include
					$ppn = (int) $sum_price / 11;
					$calc_sales = $ppn * 10;
					$ppn_for_outstanding = $ppn;
					
					if ($currency == 'USD') {
						$ppnusd = $sum_priceusd / 11;
						$calc_salesusd = $ppnusd * 10;
					}
					
				} elseif ($rdotax == 1) { //ppn exclude
					$ppn = (int) $sum_price * 0.1;
					$calc_sales = $sum_price;
					$ppn_for_outstanding = $ppn;
					
					if ($currency == 'USD') {
						$ppnusd =  $sum_priceusd * 0.1;
						$calc_salesusd = $sum_priceusd;
					}
				} else if ($rdotax == 2) { //ppn kbn -> ppn = 0
					$ppn = (int) $sum_price * 0.1; //tetap diprint di faktur pajak tetapi ada cap / kode nomor pajak. Klo di faktur komersil nol
					$calc_sales = $sum_price ;
					$ppn_for_outstanding = 0;

					if ($currency == 'USD') {
						$ppnusd =  $sum_priceusd * 0.1; //tetap diprint di faktur pajak tetapi ada cap / kode nomor pajak. Klo di faktur komersil nol
						$calc_salesusd = $sum_priceusd ;
					}
				} else if ($rdotax == 3) { // ppn ssp sama dgn kbn cuma pengertiannya berbeda
					$ppn = (int) $sum_price * 0.1;
					$calc_sales = $sum_price;
					$ppn_for_outstanding = 0;
					
					if ($currency == 'USD') {
						$ppnusd =  $sum_priceusd * 0.1;
						$calc_salesusd = $sum_priceusd;
					}
					
				}
				$ppn = str_replace(",","",number_format($ppn,0));
				$calc_sales = str_replace(",","",number_format($calc_sales,0));
			
			if($discount == '') //no discount
				{
					if($currency == 'Rp.'){
						$ppnusd 		= 0;
						$calc_salesusd 	= 0;
						$totalsales 	= $calc_salesusd;
					}
					$discount = 0;		
					$res = read_write("update tb_invoice set 
										discount	=".$discount.", 
										tax			=".$ppn_for_outstanding.", 
										ppn			=".$ppn.", 
										totalsales	=".$calc_sales.", 
										ppnusd		=".$ppnusd.", 
										totalsalesusd=".$calc_salesusd.", 
										usercode	='".$_SESSION['user']."', 
										last_update	='".$today."',
										sentstatus 	=0
									where invoiceno='".$sid."'");
					
						$message = "Database has been successfully updated ...";
				} else {
						if($currency == 'Rp.'){
							$ppnusd = 0;
							$calc_salesusd = 0;				
						}	
								$res = read_write("update tb_invoice set 
														discount		=".$discount.", 
														tax				=".$ppn_for_outstanding.", 
														ppn				=".$ppn.", 
														totalsales		=".$calc_sales.", 
														ppnusd			=".$ppnusd.", 
														totalsalesusd	=".$calc_salesusd.", 
														usercode		='".$_SESSION['user']."', 
														last_update		='".$today."',
														sentstatus	 	=0
													where invoiceno='".$sid."'");
								//$message = "Update success 	(1) from add & edit (dengan discount = x) & delete  ...";
								$message = "Database has been successfully updated ...";
				}	
				$tax = $ppn_for_outstanding;
	}
} else {
		
		
		$calc_sales = $totalsales;
		
		$calc_salesusd = $totalsalesusd;
		
}
		
?>
<table border='0' cellspacing='1' cellpadding='1' width="40%" >
<tr>
	<th colspan=4 class="small"><font color=#ff0000><?php echo $message; ?></font></th>
</tr>
<?php 
//if ($_SESSION['groups'] == 'administrator' || $_SESSION['groups'] == 'root') { ?>
<tr class=small>
	<td width=35% align=right>Discount (%):</td>
	<td><input type="text" name="discount" value="<?php echo $discount; ?>" class="tBox" size=3 maxlength=3  > 
		<input type=button value="Calculate" class="tBox" onClick="javascript:calcdiscount()">
	</td>
</tr>

<tr class=small>
	<td align=right>Ppn (IDR):</td>
	<!-- tax -> outstanding, ppn -> printout -->
	<td><input type="text" name="tax" value="<?php echo $tax; ?>" class="tBox" size=20 maxlength=20 ></td>
</tr>
<tr class=small>
	<td align=right>Sales (IDR):</td>
	<td><input type="text" name="totalsales" value="<?php echo $calc_sales; ?>" class="tBox" size=20 maxlength=20></td>
</tr>
<?  if ($currency == 'USD') { ?>
<tr class=small>
	<td align=right>Ppn (USD):</td>
	<td><input type="text" name="ppnusd" value="<?php echo $ppnusd; ?>" class="tBox" size="20" maxlength="20" ></td>
</tr>
<tr class=small>
	<td align=right>Sales (USD):</td>
	<td><input type="text" name="totalsalesusd" value="<?php echo $calc_salesusd; ?>" class="tBox" size=20 maxlength=20></td>
</tr><? 
	} 
	?>

<tr><td height=3></td></tr>
<tr>
	<td colspan=2 align=center>
	<input type="submit" value="Save" class="tBox" <?php if ($validate==1 || ($_SESSION['groups'] <> 'root' && $_SESSION['groups'] <> 'administrator' && $_SESSION['groups']<>'sales' && $_SESSION['groups']<>'finance')) {echo "disabled"; } ?>>&nbsp;
	<input type="reset" value="Reset" class="tBox">
	</td>
</tr>
<!--
<?php 
//} else { 
	if ($discount <> "" && $discount > 0) {	?>
		<tr class=small>
			<td width=35% align=right>Discount (%):</td><td><?php echo $discount; ?> </td>
		</tr> <?php 
	} ?>
	<tr class=small>
		<td align=right>Ppn (IDR):</td><td><?php echo number_format($ppn,0); ?></td>
	</tr>
	<tr class=small>
		<td align=right>Total Sales (IDR):</td><td><?php echo number_format($calc_sales,0); ?></td>
	</tr>
	<?
	if ($currency == 'USD.') { ?>
		<tr class=small>
			<td align=right>Ppn (USD):</td><td><?php echo number_format($ppnusd,3); ?></td>
		</tr>
			<tr class=small>
				<td align=right>Sales (USD):</td><td><?php echo number_format($calc_salesusd,3); ?></td>
			</tr><? 
		} else { 
			echo "<tr><td>&nbsp;</td></tr> ";
		}	?>
	<tr><td height=3></td></tr>
	<?php 
//} ?>
-->



</table>		
</form> 
<table border=0 cellspacing=1 cellpadding=1 width="40%">
<tr class=small>
	<td align=center>
	<input type=button class="tBox" value="Validate" onClick="dovalidate('<?php echo $sid;?>','<?php echo $bid;?>','<?php echo $cid;?>','<?php echo $p;?>')" <?php if ($sid=='' || $cntprod==0 || $validate==1) { echo "disabled"; } ?>>
	<?php 
	if ($_SESSION['groups'] == 'administrator' || $_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'finance') 
		{?>
		<input type=button class="tBox" value="Un-Validate" onClick="dounvalidate('<?php echo $sid;?>','<?php echo $bid;?>','<?php echo $cid;?>','<?php echo $p;?>')" <?php if ($validate=='' || $validate=='0') { echo "disabled"; }?>>
	<?} else {
			$rs_fin = read_write("select count(*) as ttucnt from tb_ttu where invoiceno='".$sid."'");
			$row_fin = mysql_fetch_array($rs_fin);

			if ($row_fin['ttucnt'] == 0) {
	?>
			<input type=button class="tBox" value="Un-Validate" onClick="dounvalidate('<?php echo $sid;?>','<?php echo $bid;?>','<?php echo $cid;?>','<?php echo $p;?>')" <?php if ($validate=='' || $validate=='0') { echo "disabled"; }?>>
	
	<?		}
		} ?>
	</td></tr>		
		</table>		
	</body>
</html>
