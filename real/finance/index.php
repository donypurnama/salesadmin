<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	
	$constantbranch = $branch;
	$x    = $_GET['x'] ;
    $page = $_GET['page'];
	$search = $_GET['search'];		
	
	$invno 		 = $_POST['invoiceno'];
	$ttuno 		 = $_POST['ttuno'];
	$companyname = $_POST['companyname'];
	$personname  = $_POST['personname'];
	$lpudatefrom = $_POST['lpudatefrom'];
	$lpudateto 	 = $_POST['lpudateto'];
	$slbranch	 = $_POST['slbranch'];
	
	$divisionid	 = $_POST['divisionid'];
	
	// apabila $_GET['page'] sudah didefinisikan, gunakan nomor halaman tersebut, 
	// sedangkan apabila belum, nomor halamannya 1.
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	} else 
		$page = 1;
	
	//jumlah data yang ditampilkan 
	$recordperpage = 20; 
	
	//perhitungan offset
	$start = ($page-1) * $recordperpage;
	
	$_SESSION['backurl'] = "index.php?search=".$search;
	$_SESSION['sidcust'] = '';
	

	if ($search == '1') {
		if($x == '1') {	
			$_SESSION['invno'] 		= $invno;
			$_SESSION['ttuno'] 		= $ttuno;
			$_SESSION['companyname'] = $companyname;
			$_SESSION['personname']  = $personname;
			$_SESSION['lpudatefrom'] = $lpudatefrom;
			$_SESSION['lpudateto']   = $lpudateto;
			$_SESSION['slbranch']  	 = $slbranch;
			$_SESSION['divisionid']   = $divisionid;
		}
		if ($_SESSION['slbranch'] <> '') { $branch=$_SESSION['slbranch']; }

		$strwhere = "";
		if ($_SESSION['invno']<>'') {
			$strwhere = $strwhere." and tb_invoice.invoiceno like '%".$_SESSION['invno']."%'";
		}
		if ($_SESSION['ttuno']<>'') {
			$strwhere = $strwhere." and ttuno='".$_SESSION['ttuno']."'";
		}
		if ($_SESSION['companyname']<>'') {
			$strwhere = $strwhere." and companyname like '%".$_SESSION['companyname']."%'";
		}
		if ($_SESSION['personname']<>'') {
			$strwhere = $strwhere." and personname like '%".$_SESSION['personname']."%'";
		}

		if ($_SESSION['divisionid']<>'') {
			$strwhere = $strwhere." and tb_commgroup.divisionid='".$_SESSION['divisionid']."'";
		}
		if ($_SESSION['lpudatefrom']<>'' ) {
			if ($_SESSION['lpudateto']=='') {
				$strwhere = $strwhere." and lpudate >='".$_SESSION['lpudatefrom']."'";
			} else {
				$strwhere = $strwhere." and lpudate between '".$_SESSION['lpudatefrom']."' and '".$_SESSION['lpudateto']."'";
			}
		} 
		
		if($x == '1') {
			/*--------COUNT RECORD-------*/
			$query = "SELECT count(tb_invoice.invoiceno) as cnt 
								FROM 
									tb_company, 
									tb_buyer, 
									tb_deliveryaddr, tb_commgroup,
									tb_invoice
								LEFT JOIN 
									tb_ttu 
								ON 
									tb_ttu.invoiceno 	= tb_invoice.invoiceno
								WHERE 
									tb_invoice.buyercode = tb_buyer.buyercode 
								AND 
									tb_company.companycode = tb_deliveryaddr.companycode
								And
									tb_commgroup.commgroupcode=tb_invoice.commgroupcode
								And
									tb_invoice.validate=1 ";
			if ($_SESSION['invno']=='') {
					$query = $query." AND (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) ";
			}
			$query = $query." AND tb_deliveryaddr.deliverycode 	= tb_buyer.deliverycode ".$strwhere;
			$res = read_write($query);
		
			//echo $query;	
			$row2 = mysql_fetch_array($res);
			$row = $row2['cnt'];
			
			$_SESSION['count'] = $row;
		}
		
	} else {
	$_SESSION['invno'] = "";
	$_SESSION['ttuno'] = "";
	$_SESSION['companyname'] = "";
	$_SESSION['personname']  = "";
	$_SESSION['lpudatefrom'] = "";
	$_SESSION['lpudateto']   = "";
	$_SESSION['slbranch'] = "";
	$_SESSION['divisionid'] = "";
	
	$res = read_write("SELECT DISTINCT ttuid, 
								ttuno, 
								tb_ttu.payment + tb_ttu.ppn_payment 	AS total_pay, 
								tb_invoice.invoiceno, 
								tb_buyer.buyercode, 
								tb_deliveryaddr.deliverycode,
								
								
								tb_deliveryaddr.street 		AS c_street,
								tb_deliveryaddr.building 	AS c_building, 
								tb_deliveryaddr.city 		AS c_city, 
								tb_buyer.street 			AS p_street, 
								tb_buyer.city 				AS p_city, 
								
								tb_company.companycode, 
								tb_company.companyname, 
								tb_buyer.personname, 
								tb_salesman.salesname,
								tb_ttu.lpudate
								
							FROM 
								tb_invoice, 
								tb_company,
								tb_buyer, 
								tb_deliveryaddr, 
								tb_ttu, tb_salesman
							WHERE 
								tb_invoice.buyercode=tb_buyer.buyercode 
							AND 
								tb_company.companycode=tb_deliveryaddr.companycode
							AND 
								tb_deliveryaddr.deliverycode=tb_buyer.deliverycode 
							AND
								tb_salesman.salescode = tb_invoice.salescode
							AND 
								tb_ttu.invoiceno=tb_invoice.invoiceno
							AND
								tb_ttu.ttuno like 'TU".$branch."%'
							ORDER BY 
								tb_ttu.lpudate DESC , 
								tb_ttu.ttuno DESC LIMIT 20 ");
							
			$_SESSION['count'] = mysql_num_rows($res);

	}
	
	if ($_SESSION['slbranch'] <> '') { $branch=$_SESSION['slbranch']; }
?>

<html>
	<head>
	<title>TTU Baru & Pencarian</title>
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
		function validate() {
			with (document.frmttu) {
				
				if (invoiceno.value=='' && ttuno.value=='' && companyname.value=='' && personname.value=='' && lpudatefrom.value=='' && lpudateto.value=='' && divisionid.value=='') {
					
					alert('Please fill something to search');
					return false;
				} 

				if (divisionid.value!=='' && lpudatefrom.value=='') {
					alert('Please fill LPU Date From');
					return false;
				}
				if (lpudatefrom.value=='' && lpudateto.value!=='') {
					alert('Please fill LPU Date From');
					return false;
				}
				
				return true;
			}
		}

		function golpureport() {
			with (document.frmttu) {
				if (lpudatefrom.value=='') {
					alert('Please fill Lpu Date');
				} else {
					varno = prompt("LPU No.",'');
					action = 'lpureport.php?x=1&lpusect=0&lpuno=' + varno;					
					onsubmit='';
					submit();
					
				}
			}
		}

		
		</script>
	</head>
	<body >
		<?php include('../menu.php'); ?><br>
		<?php 		
		include('formttu.php'); 
		
		echo createttuform('index.php?x=1&search=1', 'return validate()', 'index', $constantbranch, $arr_branch);
		?>
<table border='0' cellspacing=0 cellpadding=2 width="100%" align=center>
	<tr bgcolor=#e5ebf9 class=header>
	<td align='center'><b>TTU No.</b></td>
	<td align='center'><b>LPU Date</b></td>
	<td align='center'><b>Invoice No.</b></td>
	<td align='center'><b>Payment<br> (RP)</b></td>
	<td align='center'><b>Customer Name</b></td>
	<td align='center'><b>Delivery Address</b></td>
	<td align='center'><b>Salesman</b></td>

	</tr>	
		<?php
		/* ----------------PENCARIAN ----------------*/
		if ($search == '1') 
		{
			$max =  ceil($_SESSION['count'] / $recordperpage);

			$query = "SELECT DISTINCT 
								ttuid, 
								ttuno, 
								tb_ttu.payment + tb_ttu.ppn_payment 	AS total_pay, 
								tb_invoice.invoiceno, 
								tb_buyer.buyercode, 
								tb_deliveryaddr.deliverycode, 
								tb_deliveryaddr.street 		AS c_street,
								tb_deliveryaddr.building 	AS c_building, 
								tb_deliveryaddr.city 		AS c_city, 
								tb_buyer.street 			AS p_street, 
								tb_buyer.city 				AS p_city, 
								tb_company.companycode, 
								tb_company.companyname, 
								tb_buyer.personname, 
								tb_salesman.salesname,
								tb_ttu.lpudate
								
							FROM 
								tb_company, 
								tb_buyer, 
								tb_deliveryaddr, tb_salesman, tb_commgroup,
								tb_invoice
							LEFT JOIN 
								tb_ttu 
							ON 
								tb_ttu.invoiceno 		= tb_invoice.invoiceno
							WHERE 
								tb_invoice.buyercode 	= tb_buyer.buyercode 
							AND 
								tb_company.companycode 	= tb_deliveryaddr.companycode
							AND
								tb_salesman.salescode = tb_invoice.salescode
							And
								tb_commgroup.commgroupcode=tb_invoice.commgroupcode
							And
								tb_invoice.validate=1 ";
			if ($_SESSION['invno']=='') {
				$query = $query." AND
								(tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='1' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) ";
			}
			$query = $query." AND 
								tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere." 
							ORDER BY 
								tb_ttu.ttuno DESC LIMIT ".$start.",".$recordperpage;
			$res = read_write($query);	
				
						
		}

		if($_SESSION['count'] > 0 )  
		{
			while ($row = mysql_fetch_array($res)) 
			{
				//krn 00 dan 01 bisa gabung customernya
				if ($row['deliverycode']<> $branch.'.0000000' && $row['deliverycode'] <> "01.0000000" && $row['deliverycode'] <> "00.0000000") 
				{  			
					$address = trim($row['c_street']);
					if (trim($row['c_building'])<>"") {
						$address = $address."<br>".trim($row['c_building']);
					}
					if (trim($row['c_city'])<>"") {
						$address = $address."<br>".trim($row['c_city']);
					}
					$b_company=1;
				} else {
					$address = trim($row['p_street']);
					if (trim($row['p_city'])<>"") {
						$address = $address."<br>".trim($row['p_city']);
					}
					$b_company=0;
				}
				
				
			
				if ($row['companyname'] <> "") {
					$customer = $row['companyname'];				
					$b_company=1;
				} else {
					$customer = $row['personname'];
					$b_company=0;
				}
				
				$ttuid = $row['ttuid'];
				$invno = $row['invoiceno'];
				if ($invno=="") { $invno="No Invoice";}
				if ($b_company<>1) {
					$p = '1';			
				} else {
					$p = '0';
				}

			
				echo "<tr class=small ><td>";
				if ($ttuid<>'') {
					echo "<a href='ttu.php?ttuid=".$ttuid."' class=smalllink>".$row['ttuno']."</a>";
				} else {
					echo "<a href='ttu.php?sid=".$row['invoiceno']."' class=smalllink>Add TTU</a>";
				}
				echo "</td>";			
				echo "<td>".$row['lpudate']."</td>";
				echo "<td><a href='invoice.php?sid=".$row['invoiceno']."' class=smalllink>".$invno."</a></td>";

				$total_pay = $row['total_pay'];
				if ($total_pay==0) {
					$total_pay = "-";
				} else {
					$total_pay = number_format($total_pay,0);
				}
				echo "<td align='right'>".$total_pay."</td>";
				
				if ($b_company<>1) {
					$p = '1';			
				} else {
					$p = '0';
				}			
				/*-----COSTUMER NAME--------*/
				if ($b_company==1) {
					echo "<td><a href='customer.php?cid=".$row['companycode']."' class=smalllink>".$row['companyname']."</a></td>";
				} else {
					$url = 'customer.php?bid='.$row['buyercode'];
					echo "<td><a href='".$url."' class=smalllink>".stripslashes($row['personname'])."</a></td>";
				}
				/*-------------*/
				if ($b_company==1) {
					echo "<td><a href='customer.php?id=".$row['deliverycode']."&cid=".$row['companycode']."'  class=smalllink>".stripslashes($address)."</a></td>";
				} else {
					echo "<td>".stripslashes($address)."</td>";
				}
				
				echo "<td>".$row['salesname']."</td>";	
				echo "</tr>";
			} //end while ....
							
		}

if($search == '1') 
{	?>
	
		<?
		echo "<br><center>";
		
		echo "<table border='0' cellspacing='0' cellpadding='2' width='100%' align='center'>";
		echo "<tr><td height='6'></td></tr>";
		if($_SESSION['count'] > $recordperpage) { 
		
		echo "<tr><th colspan=4 align=center><font class=small></font>&nbsp;";
			
			//previous link
			if ($page > 1) echo "<a href=index.php?page".($page-1)."&search=1 class=pagemenu>Prev &nbsp;</a></b>";
			
			//tampilkan link nomor nomor halammnya menggunakan looping 
			for($i < 1; $i <= $max; $i++) {
				if((($i >= $page - 7) && ($i <= $page + 7)) || ($i == 1) || ($i == $max)) 
				{
					if (($showPage == 1) && ($i != 2)) echo " ... ";
					if (($showPage != ($max - 1)) && ($i == $max)) echo " ... ";
					if ($i == $page) { 
						echo "<b><font class='small'>[" .$i. "] </font></b>";
					} else { 
				
						echo "<b><a href=index.php?page=".$i."&search=1 class=pagemenu>[".$i."] </a></b>";						
					}
					$showPage = $i;	
				}
				
			}
			
				if($page < $max) {
					echo "<a href=index.php?page=".($page+1)."&search=1 class=pagemenu>[Next]</a></b>";
				}
			echo "</th></tr>";		

		
		}
		?>
		</table>
		</center>
		<?
} 	 //end search  == 1
		
		?>
		</table>
	</body>
</html>