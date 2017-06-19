<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>

<?php
session_start();
if ($_SESSION['user'] == '')
{
	//Header('Location: '.DOMAIN_NAME.'advancedsearch.php');
	Header('Location: ../sales/advancedsearch.php');
}

   
?>	
<html>
	<head>
		<title>Advanced Search</title>
		<link rel="stylesheet" type="text/css" href="../style.css">
		
		<script language="javascript" src="cal2.js">
			/*
			Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
			Script featured on/available at http://www.dynamicdrive.com/
			This notice must stay intact for use	
			*/																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																													
		</script>
		<style type="text/css">
<!--
select { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; border: #000000; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px}
-->	
</style> 
		<script language="javascript" src="cal_conf2.js"></script>
		<script language="javascript">
		function validate() {
			with (document.frmsearch) {
				if (companyname.value=='' && personname.value=='' && invoiceno.value=='' &&
				    invoicedatefrom.value=='' && invoicedateto.value=='' && 
				    c_building.value=='' && c_street.value=='' && c_city.value=='' &&
				    p_street.value=='' && p_city.value=='' &&
					salesman.value=='' && selgroup.value==''
				    ) {
					alert('Please fill something to search');
					return false;
					} else {
					if (invoicedatefrom.value=='' && invoicedateto.value!=='') {
						alert('Please fill Date From');
						return false;
					}
				}
				return true;
			}
		}
		</script>
	</head>
	<body >
		<?php include('../menu.php'); ?>	
		<center><a class=smalllink href='index.php' >New Sales Order</a	> | <b class="big">Advanced Search </b> </center>
		<form method="POST" name=frmsearch action="advancedsearch.php?x=1" onsubmit="return validate()">
		<table border=0 cellspacing=1 cellpadding=15 align=center>
			<tr>
				<td valign=top>
				<table border=0 cellspacing=1 cellpadding=1 align=center>
					<tr bgcolor=#0099ff class=header align=center>
						<td colspan=3><b>Customer</b></td>
					</tr>	
						
					<tr class=small>
						<td>Corporate Name</td>
						<td>&nbsp;</td>
						<td>Personal Name</td>
					</tr>
					<tr>
						<td><input type=text class=forms name="companyname" value="<?php echo $companyname; ?>"></td>
						<td>&nbsp;</td>
						<td><input type=text class=forms name="personname" value="<?php echo $personname; ?>"></td>
					</tr>
					<tr class=small>
						<td>Building</td>
						<td>&nbsp;</td>
						<td>Street</td>
					</tr>
					<tr>
						<td><input type=text class=forms name="c_building" value="<?php echo $c_building; ?>"></td>
						<td>&nbsp;</td>
						<td><input type=text class=forms name="p_street" value="<?php echo $p_street; ?>"></td>
					</tr>
					<tr class=small>
						<td>Street</td>
						<td>&nbsp;</td>
						<td>City</td>
					</tr>
					<tr>
						<td><input type=text class=forms name="c_street" value="<?php  echo $c_street; ?>"></td>
						<td>&nbsp;</td>
						<td><input type=text class=forms name="p_city" value="<?php echo $p_city?>"></td>
					</tr>
					<tr class=small>
						<td>City</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						
					</tr>
					<tr>
						<td><input type=text class=forms name="c_city" value="<?php echo $c_city?>"></td>
						<td></td>
						<td>&nbsp;</td>
						
					</tr>
				</table>
				</td>
				<td valign=top>
				<table border=0 cellspacing=3 cellpadding=1 align=center>
					<tr bgcolor=#0099ff class=header align=center>
						<td colspan=3><b>Sales</b></td>
					</tr>	
					<tr class=small>
						<td>Salesman</td>
						<td><select class="forms" name="salesman" style="width:100px;">
					<option value="">--
					<?php
					$res = read_write("SELECT * FROM tb_salesman ");
					while ($row = mysql_fetch_array($res)) 
					{
						echo "<option value='".$row['salesmanid']."'>".$row['salesname']."</option>";
					}
					?></select></td></tr>		
					<tr class=small>
						<td>Product Group</td>
						<td><select class="forms" name="selgroup"  style="width:100px;">
					<option value="">--
					<?php
					$res = read_write("select * from tb_division");
					while ($row = mysql_fetch_array($res))
					{
						echo "<option value='".$row['divisionid']."'>".$row['divisionname']."</option>";
							
					}
					?></select></td>
					</tr>
					<tr>
						<td class=small>Product Name</td>
						<td><input type=text class=forms style="width:100px;" name="productname" value="<?php echo $divisionname; ?>"></td>
					</tr>
					
					</table>
				</td>
				<td valign=top>
				<table border=0 cellspacing=1 cellpadding=1 align=center>
					<tr bgcolor=#0099ff class=header align=center>
						<td colspan=3><b>Invoice</b></td>
					</tr>	
					<tr class=small>
						<td colspan=2>Invoice No.</td>
					</tr>
					<tr>
						<td colspan=2><input type=text class=forms name="invoiceno" value="<?php echo $invno; ?>"></td>
					</tr>
					<tr class=small>
						<td colspan=2>From</td>
					</tr>
					<tr>
						<td><input type=text class=forms name="invoicedatefrom" value="<?php echo $invoicedatefrom; ?>"></td>
						<td><a href="javascript:showCal('Calendar7')" class=smalllink>Select Date</a></td>	</tr>
					<tr class=small>
						<td colspan=2>To</td>
					</tr>
						<tr>
							<td><input type=text class=forms name="invoicedateto" value="<?php echo $invoicedateto; ?>"></td>
							<td><a href="javascript:showCal('Calendar8')" class=smalllink>Select Date</a></td>
					</tr>						
					</table>
				</td>   
			</tr>
			<tr>
				<td colspan=3 align=center>
					<input type=submit class=forms value="Find">
					<input type="button" value="Back" class="forms" onclick="location.href='<?php 	echo $_SESSION['backurl'];?>'">
			</td>
			</tr>
		</table>
		</form>
<?php



//batas jumlah yang ingin 
$limit = 4;
if(empty ($halaman))
{
	$offset=0;
	$halaman=1;
} else {
$offset = ($halaman-1) * $limit; 
} 

 $x                  = $_GET['x'] ;
    $invno	        = $_POST['invoiceno'];
    $companyname        = $_POST['companyname'];
    $personname  	= $_POST['personname'];
    $invoicedatefrom    = $_POST['invoicedatefrom'];
    $invoicedateto      = $_POST['invoicedateto'];
    $c_building         = $_POST['c_building'];
    $c_street  	        = $_POST['c_street'];		
    $c_city		= $_POST['c_city'];
    $p_street  	        = $_POST['p_street'];		
    $p_city		= $_POST['p_city'];
    $salesmanid         = $_POST['salesman'];
    $selgroup           = $_POST['selgroup'];
    
if ($x == '1')
{
 $_SESSION['invoice'] 		= $invo;
 $_SESSION['companyname'] 	= $companyname;
 $_SESSION['personname']	= $personname;
 

if ($invno <> "" || $companyname <> "" || $personname <> "" || $invoicedatefrom <> "" || $invoicedateto <> "" || 
	$c_building <> "" || $c_street <> "" || $c_city <> "" ||
	$p_street <> "" || $p_city <> "" || 
	$divisionname <> "" ||
	$salesmanid <> "" || $selgroup <> "") 	{
	
	echo "
		<table border=0 cellspacing=0 cellpadding=2 width=100%>
		<tr bgcolor=#0099ff class=header>
			<td><b>Invoice No.</b></td>
			<td><b>Corporate Name</b></td>
			<td><b>Contact Name</b></td>
			<td><b>Delivery Address</b></td>
			<td><b>Invoice Date</b></td>
			<td><b>Product Name</b></td>
			<td><b>Sales Name</b></td>
		</tr>";	
	
	$strwhere = "";
	if ($invno<>'') {
            $strwhere = $strwhere." and tb_invoice.invoiceno like '%".$invno."%'";
	}
	if ($companyname<>'') {
            $strwhere = $strwhere." and tb_company.companyname like '%".$companyname."%'";
	}
	if ($personname<>'') {
            $strwhere = $strwhere." and personname like '%".$personname."%'";
	}
	if ($c_building<>''){
            $strwhere = $strwhere."and tb_deliveryaddr.building like '%".$c_building."%'";
	}
	if ($c_street<>'') {
            $strwhere = $strwhere."and tb_deliveryaddr.street like '%".$c_street."%'";
	}
	if ($c_city<>'') {
            $strwhere = $strwhere."and tb_deliveryaddr.city like '%".$c_city."%'";
	}
	if ($p_street<>''){
            $strwhere = $strwhere."and tb_buyer.street like '%".$p_street."%'";
	}
	if ($p_city<>''){
            $strwhere = $strwhere."and tb_buyer.city like '%".$p_city."%'";
	}
	if ($salesname<>'') {
            $strwhere = $strwhere." and salesname like '%".$salesname."%'";
	}
    if($divisionname<>'') {
            $strwhere = $strwhere." and divisionname like '%".$divisionname."%'";
	}
    if ($salesmanid <> '') {
            $strwhere = $strwhere."and tb_salesman.salesmanid='".$salesmanid."'";
    }
	if ($selgroup<>'') {
            $strwhere = $strwhere." and tb_division.divisionid='".$selgroup."'";
	}
	if ($productname<>'') {
            $strwhere = $strwhere." and tb_product.productname like '%".$productname."%'";
	}
	if ($invoicedatefrom<>'' ) {
            if ($invoicedateto=='') { 
                $strwhere = $strwhere." and invoicedate >='".$invoicedatefrom."'";
            } else {
		$strwhere = $strwhere." and invoicedate between '".$invoicedatefrom."' and '".$invoicedateto."'";
            }
	} 


$rsinv = read_write ("SELECT DISTINCT 
						tb_invoice.invoicedate, 
						tb_invoice.invoiceid, 
						tb_buyer.buyerid, 
						tb_invoice.salesmanid, 
						tb_deliveryaddr.deliveryid, 
						tb_company.companyid, 
						tb_invoice.invoiceno, 
						tb_salesman.salesname, 
						tb_company.companyname, 
						tb_buyer.personname, 
						tb_deliveryaddr.street 		AS c_street, 
						tb_deliveryaddr.building 	AS c_building, 
						tb_deliveryaddr.city AS c_city, 
						tb_buyer.street AS p_street, 
						tb_buyer.city AS p_city, 
						tb_division.divisionname AS product
					FROM 
						tb_invoice, 
						tb_company, 
						tb_buyer, 
						tb_deliveryaddr, 
						tb_salesman, 
						tb_invoiceitems, 
						tb_product, 
						tb_division

				WHERE 
					tb_invoice.buyerid = tb_buyer.buyerid
				AND 
					tb_company.companyid = tb_deliveryaddr.companyid
				AND 
					tb_invoice.salesmanid = tb_salesman.salesmanid
				AND 
					tb_invoiceitems.invoiceid = tb_invoice.invoiceid
				AND 
					tb_product.divisionid = tb_division.divisionid
				AND 
					tb_deliveryaddr.deliveryid = tb_buyer.deliveryid
				AND 
					tb_salesman.salesmanid ".$strwhere." 
				ORDER BY 
					tb_invoice.invoiceno DESC LIMIT 20" );

while ($row = mysql_fetch_array($rsinv)) {
	if ($row['deliveryid']>0) {
		$address = trim($row['c_street']);
		if (trim($row['c_building'])<>"") {
			$address = $address.", ".trim($row['c_building']);
		}
		$address = $address.", ".trim($row['c_city']);
		$b_company=1;				
	} else {
		$address = trim($row['p_street']);
		$address = $address.", ".trim($row['p_city']);
		$b_company=0;
	}
	$invno = $row['invoiceno'];
		if ($invno=="") { $invno="No Invoice";}
			
			if ($row['companyid']==0) {
				$p = '1';			
			} else {
				$p = '0';
			}
			echo "<tr class=small>
				<td><a href='sales.php?sid=".$row['invoiceid']."&bid=".$row['buyerid']."&c=".$row['companyid']."&p=".$p."' class=smalllink>".$invno."</a></td>";
			if ($b_company==1) {
				echo "<td><a href='corporate.php?c=".$row['companyid']."' class=smalllink>".$row['companyname']."</a></td>";
			} else {
				echo "<td></td>";
			}			
			if ($b_company==1) {
				$url = 'personal.php?url=advancedsearch.php&bid='.$row['buyerid'].'&c='.$row['companyid'];
			} else {
				$url = 'personal.php?url=advancedsearch.php&bid='.$row['buyerid'];
			}
			echo "<td><a href='".$url."' class=smalllink>".stripslashes($row['personname'])."</a></td>";
			if ($b_company==1) {
				echo "<td><a href='deliveryaddr.php?id=".$row['deliveryid']."' class=smalllink>".stripslashes($address)."</a></td>";
			} else {
				echo "<td>".stripslashes($address)."</td>";
			}
			$time = new waktu();
			echo "<td>".$time->tanggal('j F Y',$row['invoicedate'])."</td>";
			echo "<td>".$row['product']."</td>"; 
			echo "<td>".$row['salesname']."</td>"; 
			echo "</tr>"; 
		}
	}
}

$tampil = read_write("SELECT 
						tb_invoice.invoicedate, 
						tb_invoice.invoiceid, 
						tb_buyer.buyerid, 
						tb_invoice.salesmanid, 
						tb_deliveryaddr.deliveryid, 
						tb_company.companyid, 
						tb_invoice.invoiceno, 
						tb_salesman.salesname, 
						tb_company.companyname, 
						tb_buyer.personname, 
						tb_deliveryaddr.street 		AS c_street, 
						tb_deliveryaddr.building 	AS c_building, 
						tb_deliveryaddr.city AS c_city, 
						tb_buyer.street AS p_street, 
						tb_buyer.city AS p_city, 
						tb_division.divisionname AS product
					FROM 
						tb_invoice, 
						tb_company, 
						tb_buyer, 
						tb_deliveryaddr, 
						tb_salesman, 
						tb_invoiceitems, 
						tb_product, 
						tb_division");
						
$jumbar = mysql_num_rows($tampil);

$total_halaman = ceil($jumbaris/$limit);

if(!empty($halaman) && $halaman !=1)
{
	$previous=$halaman-1;
	echo "<a href=advancedsearch.php?halaman=$previous>Previous</a> -";
} else {
	echo "Previous -";
}

for($i=1; $i<=$total_halaman; $i++)
if($i !=$halaman) {
	echo "<a href=advancedsearch.php?halaman=$i>$i</a> - ";
} else {
	echo "$i - ";
}

if($halaman < $total_halaman) 
{
	$next=$halaman+1;
	echo "<a href=advancedseach.php?halaman=$next>Next</a>";
} else {
	echo "Next";
}


?>	
</body>
</html>