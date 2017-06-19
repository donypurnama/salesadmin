<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>

<?php
if ($_SESSION['user'] == '')
{
	Header('Location: ../index.php');
}	
	$constantbranch = $branch;
	$x    = $_GET['x'] ;
    $page = $_GET['page'];
	$search = $_GET['search'];	
	$prod = $_GET['prod'];
	
	$invno	        	= trim($_POST['invoiceno']);
    $companyname        = trim($_POST['companyname']);
    $personname  		= trim($_POST['personname']);
    $invoicedatefrom    = trim($_POST['invoicedatefrom']);
    $invoicedateto      = trim($_POST['invoicedateto']);
    $c_building         = trim($_POST['c_building']);
	
    $c_city				= trim($_POST['c_city']);
    $p_street  	        = trim($_POST['p_street']);		
    $p_city				= trim($_POST['p_city']);
    $salescode         	= trim($_POST['salesman']);
    $selgroup           = trim($_POST['selgroup']);
	$productname 		= trim($_POST['productname']);
	$slbranch			= trim($_POST['slbranch']);
   
   
   /*
	if ($invno == "" &&  $companyname == "" && $personname == "" && $c_building == "" &&  $c_city == "" &&  $p_street == "" &&  $p_city == "" && $productname == ""	){
		Header("Location: advancedsearch.php");
	}
   */
	if($page == "") { 
		$page = 1; 
	}
	
	$recordperpage = 40;
	$start = ($page-1) * $recordperpage;
	$result = 	"<tr><th class='small'>No Result Found</th></tr>";
	$_SESSION['backurl'] = "advancedsearch.php?search=".$search;
	$_SESSION['sidcust'] = '';
	
	
if ($search == '1') 
{
	if($x == '1') 
	{ 
		
		$_SESSION['invno'] = $invno;
		$_SESSION['companyname'] = $companyname;
		$_SESSION['personname']	= $personname;
		$_SESSION['c_building']	= $c_building;	
		
		$_SESSION['c_city'] = $c_city;
		$_SESSION['p_street'] = $p_street;
		$_SESSION['p_city'] = $p_city;
		$_SESSION['salesname'] = $salesname;
		$_SESSION['divisionname'] = $divisionname;
		$_SESSION['salescode'] = $salescode;
		$_SESSION['selgroup'] = $selgroup;
		$_SESSION['productname'] = $productname;
		$_SESSION['invoicedatefrom'] = $invoicedatefrom;
		$_SESSION['invoicedateto'] = $invoicedateto;
		$_SESSION['slbranch'] = $slbranch;		
		if ($_SESSION['slbranch'] <> '') { $branch = $_SESSION['slbranch']; }
	
	} 
		$strwhere = "";
		if ($_SESSION['invno']<>'') {
				$strwhere = $strwhere." and tb_invoice.invoiceno like '%".$_SESSION['invno']."%'";
		}
		if ($_SESSION['companyname']<>'') {
				$strwhere = $strwhere." and tb_company.companyname like '%".$_SESSION['companyname']."%'";
		}
		if ($_SESSION['personname']<>'') {
				$strwhere = $strwhere." and personname like '%".$_SESSION['personname']."%'";
		}
		if ($_SESSION['c_building']<>''){
				$strwhere = $strwhere."and (tb_deliveryaddr.building like '%".$_SESSION['c_building']."%' OR tb_deliveryaddr.street like '%".$_SESSION['c_building']."%' OR tb_deliveryaddr.region like '%".$_SESSION['c_building']."%') " ;
		}
		
		if ($_SESSION['c_city']<>'') {
				$strwhere = $strwhere."and tb_deliveryaddr.city like '%".$_SESSION['c_city']."%'";
		}
		if ($_SESSION['p_street']<>''){
				$strwhere = $strwhere."and tb_buyer.street like '%".$_SESSION['p_street']."%'";
		}
		if ($_SESSION['p_city']<>''){
				$strwhere = $strwhere."and tb_buyer.city like '%".$_SESSION['p_city']."%'";
		}
		
		if ($_SESSION['salescode']<> '') {
				$strwhere = $strwhere."and tb_salesman.salescode='".$_SESSION['salescode']."'";
		}
		if ($_SESSION['selgroup']<>'') {
				$strwhere = $strwhere." and tb_division.divisionid='".$_SESSION['selgroup']."'";
		}
		if ($_SESSION['productname']<>'') {
				$strwhere = $strwhere." and (tb_product.productname like '%".$_SESSION['productname']."%' or tb_invoiceitems.otherproductname like '%".$_SESSION['productname']."%')";
		}
		if ($_SESSION['invoicedatefrom']<>'' ) {
				if ($_SESSION['invoicedateto']=='') { 
					$strwhereinv = $strwhere. " and invoicedate >='".$_SESSION['invoicedatefrom']."'";
					$strwherecn = $strwhere. " and cn_date >='".$_SESSION['invoicedatefrom']."'";
					$strwhere = $strwhere." and (invoicedate >='".$_SESSION['invoicedatefrom']."' or cn_date >='".$_SESSION['invoicedatefrom']."')";
					 
				} else {
					$strwhereinv = $strwhere. " and (invoicedate between '".$_SESSION['invoicedatefrom']."' and '".$_SESSION['invoicedateto']."')";
					$strwherecn = $strwhere. " and (cn_date between '".$_SESSION['invoicedatefrom']."' and '".$_SESSION['invoicedateto']."')";

					$strwhere = $strwhere." and (invoicedate between '".$_SESSION['invoicedatefrom']."' and '".$_SESSION['invoicedateto']."' or cn_date between '".$_SESSION['invoicedatefrom']."' and '".$_SESSION['invoicedateto']."')";
				}
		} 
		
		if($x=='1') {		// SEARCHING	
			$strsql =  "select distinct tb_invoice.invoiceno as cnt FROM 
							tb_invoice, 
							tb_company, 
							tb_buyer, 
							tb_deliveryaddr, 
							tb_salesman,
							tb_invoiceitems,
							tb_product,
							tb_division, tb_commgroup
						WHERE 
								tb_invoice.buyercode = tb_buyer.buyercode
							AND 
								tb_company.companycode = tb_deliveryaddr.companycode
							AND 
								tb_invoice.salescode = tb_salesman.salescode	
							AND tb_invoiceitems.productcode = tb_product.productcode
							AND tb_invoice.invoiceno = tb_invoiceitems.invoiceno
							and
								tb_invoice.commgroupcode = tb_commgroup.commgroupcode
							AND 
								tb_commgroup.divisionid = tb_division.divisionid
							AND
								tb_invoice.invoiceno like 'FK".$branch."%'
							AND 
								tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere;
			//echo $strsql;
			$rs = read_write($strsql);
			if (mysql_num_rows($rs) == 0 && $_SESSION['invno']<>'') {
				$strsql =  "select distinct tb_invoice.invoiceno as cnt FROM 
							tb_invoice, 
							tb_company, 
							tb_buyer, 
							tb_deliveryaddr, 
							tb_salesman							
							
						WHERE 
								tb_invoice.buyercode = tb_buyer.buyercode
							AND 
								tb_company.companycode = tb_deliveryaddr.companycode
							AND 
								tb_invoice.salescode = tb_salesman.salescode							
							AND 
								tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere;
				//commented because of invoice can be any format beside standard format. i.e makassar invoice ->fk15610912005, 15610912003
				//AND (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."')))  
				mysql_free_result($rs);
				$rs = read_write($strsql);
				
				if (mysql_num_rows($rs) >0) {$old_inv = '1'; }
			}
			$_SESSION['count'] = mysql_num_rows($rs);
		
		}
	
	// end if for ($x == 1) 
} else {
	
	$_SESSION['invno'] = "";
	$_SESSION['companyname'] = "";
	$_SESSION['personname']	= "";
	$_SESSION['c_building']	= "";	
	
	$_SESSION['c_city'] = "";
	$_SESSION['p_street'] = "";
	$_SESSION['p_city'] = "";
	$_SESSION['salesname'] = "";
    $_SESSION['divisionname'] = "";
    $_SESSION['salescode'] = "";
    $_SESSION['selgroup'] = "";
    $_SESSION['productname'] = "";
	$_SESSION['invoicedatefrom'] = "";
	$_SESSION['invoicedateto'] = "";
	$_SESSION['count']="";
	$_SESSION['slbranch']="";
}
if ($_SESSION['slbranch'] <> '') { $branch = $_SESSION['slbranch']; }
	?>
	
	<html>
	<head>
		<title>Pencarian & Laporan</title>
		<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<!--[if IE 7]>
		<link href="templates/khepri/css/ie7.css" rel="stylesheet" type="text/css" />
		<![endif]-->

<!--[if lte IE 6]>
<link href="templates/khepri/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
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
			// Make quick references to our fields
			var personname = document.getElementById('personname');	
			with (document.frmsearch) {
				if (companyname.value=='' && personname.value=='' && invoiceno.value=='' && invoicedatefrom.value=='' && invoicedateto.value=='' 
				&& c_building.value=='' && c_city.value=='' && p_street.value=='' && p_city.value=='' &&  salesman.value=='' 
				&& selgroup.value=='' && productname.value=='') {				
						alert('Please fill something to search');
						return false;
					} else {
					if ((companyname.value.length>0 && companyname.value.length<3) || 
						(personname.value.length>0 && personname.value.length<3) || 
						(invoiceno.value.length>0 && invoiceno.value.length<3) ||
						(productname.value.length>0 && productname.value.length<3) ||
						(c_building.value.length>0 && c_building.value.length<3) ||
						(c_city.value.length>0 && c_city.value.length<3) ||
						(p_city.value.length>0 && p_city.value.length<3) ||
						(p_street.value.length>0 && p_street.value.length<3) 
						) {
						alert('Please enter at least 3 characters to search');
						return false;
					} else {
					return true;
					}
						if (invoicedatefrom.value=='' && invoicedateto.value!=='') {
							alert('Please fill Date From');
							return false;
						}
					} 
				return true;
			}
			
			
		}
		
			function lengthRestriction(elem, min, max){
				var uInput = elem.value;
				if(uInput.length >= min && uInput.length <= max){
					return true;
				}else{
					alert("Please enter between " +min+ " and " +max+ " characters");
					elem.focus();
					return false;
				}
			}
			
			function go_productreport() {
				with (document.forms[0]) {
					action='advancedsearch.php?x=1&search=1&prod=1';
					submit();
				}
			}
		
		</script>
	</head>
	<body >
		<?php include('../menu.php');?><br>
		<form method="POST" name=frmsearch action="advancedsearch.php?x=1&search=1" onsubmit="return validate()">	
		<table border="0" cellspacing="0" cellpadding="0" bgcolor=#e5ebf9 class=header width="60%" align="center">
		<tr >
		<td><div class="header icon-48-media">Pencarian & Laporan</div></td>
		<Td align=right>
		
		<?php
					if ($constantbranch == "") {	?>
					Branch &nbsp; &nbsp;				
				<?php
						
						echo "<select name=slbranch class=forms>";
						echo "<option value=''>";
						foreach ($arr_branch as $key => $value) {
							echo "<option value='".$key."'";
							if ($key == $_SESSION['slbranch']) { echo " selected"; }
							echo ">".$value;
						}
						echo "</select>"; ?>
				<?php } ?>
		</td>
		</tr>		
		
				<tr>
		<td colspan="2" align=center>
			<table border="0" cellspacing="1" cellpadding="1" bgcolor="white" width="100%" align=center>
			<tr>
			<td class="small"><a href="javascript:showCal('Calendar7')" class=smalllink> Period From: </a></td>
		
				<td class="small" valign="middle"  width="8%">Salesman:</td>
				<td class="small" valign="middle"  width="8%">Company Name:</td>
				<td class="small" valign="middle"  width="8%">Person Name:</td>
			</tr>
			<tr>		
				<td class="small"><input type=text style="width:150px;" class="tBox" name="invoicedatefrom" value="<?php echo $_SESSION['invoicedatefrom']; ?>"></td>
				<td class="small">
					<select class="tBox" name="salesman" style="width:150px;">
					<option value="" class="tBox">--
					<?php
					$res = read_write("SELECT * FROM tb_salesman where salescode like '".$branch."%' order by salesname ASC");
					while ($row = mysql_fetch_array($res)) {
						echo "<option value='".$row['salescode']."'"; 
						if($row['salescode']==$_SESSION['salescode']) { echo "selected"; } echo ">".$row['salesname'];
					}
					?></select></td>
				<td class="small"><input type="text" class="tBox" style="width:150px;" name="companyname" value="<?php echo $_SESSION['companyname']; ?>"></td>
				<td class="small"><input type="text" class="tBox" style="width:150px;" name="personname" id='personname' value="<?php echo $_SESSION['personname']; ?>"></td>
			</tr>
			<tr>
						<td class="small"><a href="javascript:showCal('Calendar8')" class=smalllink>Period To:</a></td>
				
				<td class="small">Product Group:<br>
				<td class="small">Company Building/Street:<br>
				<td class="small">Person Street: </td>
			</tr>	
			<tr>
		<td class="small"><input type=text style="width:150px;" class="tBox" name="invoicedateto" value="<?php echo $_SESSION['invoicedateto']; ?>"></td>

				<td class="small">
				<select class="tBox" name="selgroup"   style="width:150px;">
						<option value="" class="tBox">--
						<?php
						$res = read_write("select * from tb_division where divisioninv is not null");
						while ($row = mysql_fetch_array($res))
						{
							echo "<option value='".$row['divisionid']."'";
							if($row['divisionid']==$_SESSION['selgroup']) { echo "selected"; } echo ">".$row['divisionname'];
								
						}
					?></select></td>
			<td class="small"><input type="text"  style="width:150px;" class="tBox" name="c_building" value="<?php echo $_SESSION['c_building']; ?>"></td>
			<td class="small"><input type=text class="tBox"  style="width:150px;" name="p_street" value="<?php echo $_SESSION['p_street']; ?>"></td>
			</tr>
			<tr>
					<td class="small" valign="middle" width="8%">Invoice No:
					<td class="small">Product Name:</td>
					<td class="small">Company City:</td>
					<td class="small">Person City:</td>
			</tr>	
			<tr>
				<td class="small"><input type=text class="tBox" style="width:150px;" name="invoiceno" value="<?php echo $_SESSION['invno']; ?>"></td>
				<td class="small"><input type=text class="tBox" style="width:150px;" name="productname" value="<?php echo $_SESSION['productname']; ?>"></td>
				<td class="small"><input type=text class="tBox" style="width:150px;" name="c_city" value="<?php echo $_SESSION['c_city']; ?>"></td>
				<td class="small"><input type=text class="tBox" style="width:150px;" name="p_city" value="<?php echo $_SESSION['p_city']; ?>"></td>
			</tr>
			<tr>
				<td colspan="4" align="center" height="20">
				<input type="submit" class="tBox" value="Search">&nbsp;&nbsp;
				<input type="button" class="tBox" value="Product Report" onclick="go_productreport();">&nbsp;&nbsp;
				<!-- <input type="button" class="tBox" value="Salesman Report">&nbsp;&nbsp; -->
				<input type="reset" class="tBox" value="Clear" onclick="location.href='advancedsearch.php'">
			</tr>
			</table>
		</td></tr>	
			</table>
	<br>	
		</form>
	<?php 
	if($search == '1') {
	
	// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data

		$max = ceil($_SESSION['count'] / $recordperpage);
		
		
		if ($prod <> '1' && $sls <> '1') {
			
			$strsqlhead = "SELECT DISTINCT 
							tb_invoice.invoicedate, 
							tb_invoice.invoiceno, 		
							tb_invoice.buyercode,
							tb_invoice.validate,
							tb_company.companycode,
							tb_deliveryaddr.deliverycode,
							tb_salesman.salesname, 
							tb_company.companyname, 
							tb_buyer.personname, 
							tb_deliveryaddr.street 		AS c_street, 
							tb_deliveryaddr.building  	AS c_building, 
							tb_deliveryaddr.region  	AS c_region, 
							tb_deliveryaddr.city 		AS c_city, 
							tb_buyer.street 			AS p_street, 
							tb_buyer.region 			AS p_region,
							tb_buyer.city 				AS p_city ";
			if ($old_inv <> '1') { $strsqlhead=$strsqlhead.",tb_division.divisionname 	as product "; }
		} else {
			$strsqlhead = "SELECT DISTINCT tb_invoice.invoiceno, 		
							tb_invoice.buyercode,
							tb_company.companycode,
							tb_deliveryaddr.deliverycode,
							tb_salesman.salesname, 
							tb_company.companyname, 
							tb_buyer.personname,							
							(tb_invoice.tax-tb_invoice.ppnreturn) as tax,
							(tb_invoice.totalsales-tb_invoice.totalreturn) as totalsales ";
		}
				
		$strsql = " FROM tb_invoice, 
							tb_company, 
							tb_buyer,
							tb_deliveryaddr, 
							tb_salesman";
		if ($old_inv <> '1') {
			$strsql = $strsql.",tb_invoiceitems, tb_product, tb_division, tb_commgroup";
		}
		$strsql = $strsql."	WHERE tb_invoice.buyercode = tb_buyer.buyercode
					AND 
						tb_company.companycode = tb_deliveryaddr.companycode
					AND 
						tb_invoice.salescode = tb_salesman.salescode";
					
		if ($old_inv <> '1') {
			$strsql = $strsql." AND tb_invoice.invoiceno = tb_invoiceitems.invoiceno AND tb_invoiceitems.productcode = tb_product.productcode and tb_invoice.commgroupcode = tb_commgroup.commgroupcode AND tb_commgroup.divisionid = tb_division.divisionid";
		}
		
		$strsql = $strsql."	AND tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere;
		if ($_SESSION['invno']=='') {
			$strsql = $strsql ." AND (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."')))  ";
		}

		$query = $strsqlhead.$strsql."	ORDER BY 
											tb_invoice.invoicedate DESC, 
											tb_invoice.invoiceno DESC 
										LIMIT 
											".$start.",".$recordperpage;
		
		
		//echo $query;
		$rsinv = read_write ($query);
		
		if ($prod== '1' && $x=='1') {
			$querysum = "select sum(price*volume*(qty-qty_return)) as sumbasicprice ".$strsql;			
			
			$rssum = read_write($querysum);
			$rwsum = mysql_fetch_array($rssum);
			$_SESSION['sumbasicprice'] = $rwsum['sumbasicprice'];
			mysql_free_result($rssum);
			
			$querysum = "select (ppn-ppnreturn) as ppnnet, (totalsales+tax-totalreturn-ppnreturn) as pricenet, (totalsales-totalreturn) as subtotal ".$strsql." group by tb_invoice.invoiceno";
			
			$sumppn = 0; $suminvoiceprice = 0; $sumsubtotal=0;
			$rssum = read_write($querysum);
			while ($rwsum = mysql_fetch_array($rssum)) {
				$sumppn += $rwsum['ppnnet'];
				$suminvoiceprice += $rwsum['pricenet'];
				$sumsubtotal += $rwsum['subtotal'];
			}

			$_SESSION['sumppn'] = $sumppn;
			$_SESSION['suminvoiceprice'] = $suminvoiceprice;
			$_SESSION['sumsubtotal'] = $sumsubtotal;
		}
		//for product report
		$_SESSION['queryprod'] = "SELECT DISTINCT tb_invoice.invoiceno, 		
							tb_invoice.buyercode,
							tb_company.companycode,
							tb_deliveryaddr.deliverycode,
							tb_salesman.alias, 
							tb_company.companyname, 
							tb_buyer.personname, 							
							(tb_invoice.tax-tb_invoice.ppnreturn) as tax,
							(tb_invoice.totalsales-tb_invoice.totalreturn) as totalsales FROM tb_invoice, 
							tb_company, 
							tb_buyer, 
							tb_deliveryaddr, 
							tb_salesman,
							tb_invoiceitems,
							tb_product,
							tb_division, tb_commgroup
					WHERE 
						tb_invoice.buyercode = tb_buyer.buyercode
					AND 
						tb_company.companycode = tb_deliveryaddr.companycode
					AND 
						tb_invoice.salescode = tb_salesman.salescode
					AND 
						tb_invoice.invoiceno = tb_invoiceitems.invoiceno
					AND 
						tb_invoiceitems.productcode = tb_product.productcode
					and
						tb_invoice.commgroupcode = tb_commgroup.commgroupcode
					AND 
						tb_commgroup.divisionid = tb_division.divisionid
					AND 
						tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere." 
					AND
						tb_invoice.invoiceno like 'FK".$branch."%'
					ORDER BY 
						tb_invoice.invoiceno";
						
		//for branch report excel
		$_SESSION['queryinv'] = "SELECT DISTINCT 
							tb_invoice.invoicedate, 
							tb_invoice.invoiceno, 								
							tb_salesman.salesname, 
							tb_company.companyname, 
							tb_buyer.personname, 								
							tb_division.divisionname as product,
							tb_division.divisioncode,
							tb_deliveryaddr.street as c_street,	tb_deliveryaddr.building as c_building, tb_deliveryaddr.region as c_region, tb_deliveryaddr.city as c_city, tb_buyer.street as p_street,tb_buyer.region as p_region, tb_buyer.city as p_city,
							(tb_invoice.totalsales + tb_invoice.tax) as invamount
						FROM 
							tb_invoice, 
							tb_company, 
							tb_buyer, 
							tb_deliveryaddr, 
							tb_salesman,
							tb_invoiceitems,
							tb_product,
							tb_division, tb_commgroup
					WHERE 
						tb_invoice.buyercode = tb_buyer.buyercode
					AND 
						tb_company.companycode = tb_deliveryaddr.companycode
					AND 
						tb_invoice.salescode = tb_salesman.salescode
					AND 
						tb_invoice.invoiceno = tb_invoiceitems.invoiceno
					AND 
						tb_invoiceitems.productcode = tb_product.productcode
					and
						tb_invoice.commgroupcode = tb_commgroup.commgroupcode
					AND 
						tb_commgroup.divisionid = tb_division.divisionid
					AND 
						tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere." 
					AND
						tb_invoice.invoiceno like 'FK".$branch."%'";
		
		//for sales book excel
		$_SESSION['querysb'] = "SELECT 
							tb_invoice.taxno, 
							tb_invoice.invoicedate,	tb_invoice.invoiceno, cncode, discount, totalsales, ppn, ppnreturn, totalreturn, 
							tb_salesman.salesname, 
							tb_company.companyname, tb_company.npwp, 
							tb_buyer.personname, 	
							concat(tb_product.productname, tb_invoiceitems.otherproductname) as productname, tb_product.volume, tb_product.unit,  
							tb_invoiceitems.price, tb_invoiceitems.qty*tb_invoiceitems.price*tb_product.volume as totalprice, tb_invoiceitems.qty_return, 
							tb_division.divisionname
							
						FROM 
							tb_invoice, 
							tb_company, 
							tb_buyer, 
							tb_deliveryaddr, 
							tb_salesman,
							tb_invoiceitems,
							tb_product,
							tb_division, tb_commgroup

					WHERE 
						tb_invoice.buyercode = tb_buyer.buyercode
					AND 
						tb_company.companycode = tb_deliveryaddr.companycode
					AND 
						tb_invoice.salescode = tb_salesman.salescode
					AND 
						tb_invoice.invoiceno = tb_invoiceitems.invoiceno
					AND 
						tb_invoiceitems.productcode = tb_product.productcode
					and
						tb_invoice.commgroupcode = tb_commgroup.commgroupcode
					AND 
						tb_commgroup.divisionid = tb_division.divisionid
					AND 
						tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ";
						
		if ($strwhereinv<>"") {
			$_SESSION['querysb'] = $_SESSION['querysb'].$strwhereinv;
		} else {
			$_SESSION['querysb'] = $_SESSION['querysb'].$strwhere;
		}
		$_SESSION['querysb']= $_SESSION['querysb']."and tb_invoice.invoiceno like 'FK".$branch."%' ";
		$_SESSION['querysbcn'] = $_SESSION['querysb']." AND cncode <>'' ";
		$_SESSION['querysb'] = $_SESSION['querysb']." order by tb_invoice.invoicedate desc, tb_invoice.invoiceno desc";
		$_SESSION['querysbcn'] = $_SESSION['querysbcn']." order by tb_invoice.invoicedate desc, tb_invoice.invoiceno desc";
		
		if ($_SESSION['count'] > 0)
		{						
			$result = "
				<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr bgcolor=#e5ebf9 class=header>";
			if ($prod <> '1' && $sls <> '1') {
				$result = $result."<td width=9%><b>Invoice No.</b></td>
					<td><b>Corporate Name</b></td>
					<td><b>Contact Name</b></td>
					<td><b>Delivery Address</b></td>
					<td><b>Invoice Date</b></td>
					<td><b>Product Name</b></td>
					<td><b>Sales Name</b></td>
					<td><b>Validate</b></td>
					";
					
			} else {
				$result = $result."<td><b>Salesman</b></td>
					<td><b>Customer Name</b></td>
					<td width=9%><b>Invoice No</b></td>
					<td><b>Product Name</b></td>
					<td><b>Qty</b></td>
					<td align=right><b>Price/Unit</b> &nbsp;</td>
					<td align=right><b>Product Price</b> &nbsp;</td>
					<td align=right><b>Sub Total</b> &nbsp;</td>
					<td align=right><b>PPN</b> &nbsp;</td>
					<td align=right><b>Total</b> &nbsp;</td>";
			}
			
			$result = $result."</tr>";								
			while ($row = mysql_fetch_array($rsinv)) {				
				if ($row['deliverycode'] <> $branch.'.0000000' && $row['deliverycode'] <> '00.0000000' && $row['deliverycode'] <> '01.0000000') {
					if ($prod <> '1' && $sls <> '1') {
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
					}
					$b_company=1;				
				} else {
					if ($prod <> '1' && $sls <> '1') {
						$address = trim($row['p_street']);
						if (trim($row['p_region'])<>"") {
							$address = $address.", ".trim($row['p_region']);
						}
						if (trim($row['p_city'])<>"") {
							$address = $address.", ".trim($row['p_city']);
						}
					}
					$b_company=0;
				}
				$invno = $row['invoiceno'];
				if ($invno=="") { $invno="No Invoice";}
					
				if ($row['companycode'] == $branch.'.0000000' || $row['companycode'] == '00.0000000' || $row['companycode'] == '01.0000000') {
					$p = '1';			
				} else {
					$p = '0';
				}
				
				if ($prod<>'1' && $sls <> '1') {
					$result = $result."<tr class=small>";
					/*-----COMPANY--------*/
					$result = $result."<td><a href='sales.php?sid=".$row['invoiceno']."&bid=".$row['buyercode']."&c=".$row['companycode']."&p=".$p."' class=smalllink>".$invno."</a></td>";
					if ($b_company==1) {
						$result = $result."<td><a href='corporate.php?c=".$row['companycode']."' class=smalllink>".$row['companyname']."</a></td>";
					} else {
						$result= $result."<td></td>";
					}			
					/*-----PERSON NAME--------*/
					if ($b_company==1) {
						$url = 'personal.php?url=advancedsearch.php&bid='.$row['buyercode'].'&cid='.$row['companycode'];
					} else {
						$url = 'personal.php?url=advancedsearch.php&bid='.$row['buyercode'];
					}
					$result = $result."<td><a href='".$url."' class=smalllink>".stripslashes($row['personname'])."</a></td>";
					if ($b_company==1) {
						$result = $result."<td><a href='deliveryaddr.php?id=".$row['deliverycode']."' class=smalllink>".stripslashes($address)."</a></td>";
					} else {
						$result = $result."<td>".stripslashes($address)."</td>";
					}
					$time = new waktu();
					$result = $result. "<td>".$time->tanggal('j M Y',$row['invoicedate'])."</td>";
					$result = $result. "<td>".$row['product']."</td>"; 
					$result = $result. "<td>".$row['salesname']."</td>"; 
					$validate = $row['validate'];
					
					
					if ($validate==1) {
					$result = $result. "<td align='center'><img src='checklist.gif'></td>";
					} else {
						$result = $result. "<td></td>"; 
					}
					$result = $result. "</tr>";
					
					
				} else {
					
					$queryitems = "select tb_product.unit, concat(tb_product.productname, tb_invoiceitems.otherproductname) as productname, tb_product.volume,  (tb_invoiceitems.qty-tb_invoiceitems.qty_return) as qty, tb_invoiceitems.price from tb_invoiceitems, tb_product where tb_invoiceitems.invoiceno='".$invno."' and tb_invoiceitems.productcode = tb_product.productcode";
					if ($_SESSION['productname']<>'') {
						$queryitems .= " and (tb_product.productname like '%".$_SESSION['productname']."%' or tb_invoiceitems.otherproductname like '%".$_SESSION['productname']."%')";
					}
//echo $queryitems;
					$rsitems = read_write($queryitems);				
					$j = 0;
					
					while ($rowitems = mysql_fetch_array($rsitems)) {
						$result = $result."<tr class=small>";
						if ($j == 0) {
							
							$result = $result."<td>".$row['salesname']."</td>";
							if ($b_company==1) {
								$result = $result."<td><a href='corporate.php?c=".$row['companycode']."' class=smalllink>".$row['companyname']."</a></td>";
							} else {
								$result = $result."<td><a href='personal.php?url=advancedsearch.php&bid=".$row['buyercode']."' class=smalllink>".stripslashes($row['personname'])."</a></td>";
							}
							$result = $result."<td><a href='sales.php?sid=".$row['invoiceno']."&bid=".$row['buyercode']."&c=".$row['companycode']."&p=".$p."' class=smalllink>".$invno."</a></td>";
						} else {
							$result = $result."<td colspan=3></td>";
						}
						$result = $result."<td>".$rowitems['productname']."</td>";
						$result = $result."<td>".$rowitems['qty']." ".$rowitems['unit']."</td>";
						
						$result = $result."<td align=right>".number_format($rowitems['price'],0)." &nbsp;</td>";
						$result = $result."<td align=right>".number_format($rowitems['price']*$rowitems['volume']*$rowitems['qty'],0)." &nbsp;</td>";
						
						if ($j ==0) {							
							$result = $result."<td align=right>".number_format($row['totalsales'],0)." &nbsp;</td>";			
							$result = $result."<td align=right>".number_format($row['tax'],0)." &nbsp;</td>";							
							$result = $result."<td align=right>".number_format(($row['totalsales']+$row['tax']),0)." &nbsp;</td>";
							
						} else {
							$result = $result."<td colspan=2></td>";
						}
						$result = $result."</tr>";
						$j++;
					}
					
				}
				
			}
		}
		?>
		<table border='0' cellspacing=0 cellpadding=2 width="100%" align="center">
		<?php echo $result; ?>
		<tr><td height=6></td></tr>
		
		<?php 
			if ($page == $max && $prod == '1') { ?>
		<tr class=small><td colspan=5  align=right></td><td><b>Total:</b> &nbsp;</td>
		<td align=right><?php echo number_format($_SESSION['sumbasicprice'],0); ?> &nbsp;</td>
		<td align=right><?php echo number_format($_SESSION['sumsubtotal'],0); ?> &nbsp;</td>
		<td align=right><?php echo number_format($_SESSION['sumppn'],0); ?> &nbsp;</td>
		<td align=right><?php echo number_format($_SESSION['suminvoiceprice'],0); ?> &nbsp;</td>
		</tr>

		<?php }
		if ($_SESSION['count'] > $recordperpage) { ?>
		<tr><td align="center">
			<th colspan=4 align="center"><font class="small">Pages: </font>&nbsp;
			<?php
			// menampilkan link previous
			if ($page > 1) {?> 
			<a href="advancedsearch.php?page=<?php echo ($page-1); ?>&search=1&prod=<?php echo $prod; ?>" class="pagemenu">Prev</a> 
	<?php 	}
				for ($i = 1;$i <= $max; $i++) 
				{
					if((($i >= $page - 7) && ($i <= $page + 7)) || ($i == 1) || ($i == $max))
					{
						if (($showPage == 1) && ($i != 2)) echo "...";
						if (($showPage != ($max - 1)) && ($i == $max)) echo "...";
						if ($i == $page)   { ?>
							<font class="small">[<?php echo ($i); ?>]</font> 
						<? 
						} else { 
						?>
								<a href="advancedsearch.php?page=<?php echo $i; ?>&search=1&prod=<?php echo $prod; ?>" class="pagemenu">[<?php echo ($i); ?>]</a> 
						<?php }
						$showPage = $i;	
					}
				}
					if ($page < $max) {?>
						<a href="advancedsearch.php?page=<?php echo ($page+1); ?>&search=1&prod=<?php echo $prod; ?>" class="pagemenu">Next</a> 
			</th>
		</td></tr>
						
			<?php 	} 
		} 
		
		//echo "count ". $_SESSION['count'];
		
		if ($_SESSION['count']>0) {
			if ($prod<>"1" && $sls<>"1") {?>
		<tr>
			<th colspan=8 align=center>
			<input type=button value='Export To Branch Report XL' class="tBox" onclick='javascript:window.frmexcel.location.href="savetoexcel.php?ver=1"'> &nbsp;

			<!--<input type=button value='Export To Branch Report Excel' class="tBox" onclick='javascript:window.open("savetoexcel.php?ver=4")'> &nbsp;-->

			<input type=button value='Export To Sales Book XL' class="tBox" onclick='javascript:window.frmexcel.location.href="savetoexcel.php?ver=2"'>	&nbsp;

			<input type=button value='Export To Salesman XL' class="tBox" onclick='javascript:window.frmexcel.location.href="savetoexcel.php?ver=4"'> &nbsp;

			<iframe id=frmexcel name=frmexcel style='visibility:hidden;width:10px;height:10px'></iframe>
			
			<!--<input type=button value='Export To Excel' class=forms onclick='javascript:saveexcel();'>-->
			</th>
		</tr>
		<?php } else { ?>
		<tr>
			<th colspan=8 align=center><input type=button value='Export To Excel' class="tBox" onclick='javascript:window.frmexcel.location.href="savetoexcel.php?ver=3"'> <iframe id=frmexcel name=frmexcel style='visibility:hidden;width:10px;height:10px'></iframe>
		</th>
	</tr>
						<?php 
			} 
		}?>
					</table> 
				<?php 
} //end of x==1
				?>
</body>
</html>