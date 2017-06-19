<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>

<?php

if ($_SESSION['user'] == '')
{
	Header('Location: ../index.php');
	exit();
}
	$bulanan=array(1=>"Jan","Feb","Mar","Apr",
			                    "Mei","Juni","Juli","Agt","Sept",
			                    "Okt","Nov","Des");
	$constantbranch = $branch;
	$post	= $_GET['post'];
	$page = $_GET['page'];
	$search = $_GET['search'];
	$rekap = $_GET['rekap'];

	$salescode    		= trim($_POST['salesman']);
	$selgroup    		= trim($_POST['selgroup']);
	$slbranch   		= trim($_POST['slbranch']);
	$productname  		= trim($_POST['productname']);

	$qrymaxyear=read_write("SELECT MAX(year(invoicedate)) as mthn FROM tb_invoice");
	$maxyear=mysql_fetch_array($qrymaxyear);
	$tahun    = $_POST['tahun']?trim($_POST['tahun']):$maxyear['mthn'];

	if($rekap==2)
	{
	$_SESSION['bulan']    = $_POST['bulan']?trim($_POST['bulan']):date("n");
	}

    $invoicedatefrom    = trim($_POST['invoicedatefrom']);
    $invoicedateto      = trim($_POST['invoicedateto']);


	if($page == "") {
		$page = 1;
	}

	$recordperpage =40;
	$start = ($page-1) * $recordperpage;
	$result = 	"<tr><th class='small'>No Result Found</th></tr>";
	$_SESSION['backurl'] = "advancedsearch.php?search=".$search;
	$_SESSION['sidcust'] = '';


if ($search == '1')
{
				if($post)
				{

				$_SESSION['salescode'] = $salescode;
				$_SESSION['tahun'] = $tahun;
				//$_SESSION['bulan'] = $_POST['bulan'];
				$_SESSION['selgroup'] = $selgroup;
				$_SESSION['productname'] = $productname;
				$_SESSION['invoicedatefrom'] = $invoicedatefrom;
				$_SESSION['invoicedateto'] = $invoicedateto;
				}

		$_SESSION['slbranch'] = $slbranch;
		if ($_SESSION['slbranch'] <> '') { $branch = $_SESSION['slbranch']; }

		$strwhere = " and tb_invoice.validate='1'";
		if ($_SESSION['salescode']<> '' ) {
				$strwhere = $strwhere." and tb_salesman.salescode='".$_SESSION['salescode']."'";
		}

		if ($_SESSION['selgroup']<>'' ) {
				$strwhere = $strwhere." and tb_division.divisionid='".$_SESSION['selgroup']."'";
		}
		if ($_SESSION['bulan']<>'' and $rekap=='2' ) {
				$strwhere = $strwhere." and MONTH(tb_invoice.invoicedate)='".$_SESSION['bulan']."'";
		}
		if ($_SESSION['tahun']<>'' and ($rekap==2 or $rekap==3)) {
				$strwhere = $strwhere." AND YEAR(tb_invoice.invoicedate)='".$_SESSION['tahun']."'";
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
//-----------------------------------------------------------------------------------QUERY PAGES
		if( $rekap=='1') {
			$strsql = "SELECT DISTINCT tb_invoice.invoiceno,
						tb_invoice.buyercode,
						tb_company.companycode,
						tb_deliveryaddr.deliverycode,
						tb_salesman.salesname,
						tb_company.companyname,
						tb_buyer.personname,
						(tb_invoice.tax-tb_invoice.ppnreturn) as tax,
						(tb_invoice.totalsales-tb_invoice.totalreturn) as totalsales

			FROM tb_invoice,
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
						tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere." AND
						(tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."')))  ";



			$rs = read_write($strsql);

			$_SESSION['count'] = mysql_num_rows($rs);
		// end if for ($rekap == 1)
		}
		else if ($rekap == '2')
		{
		$strsql = "select tb_invoice.invoiceno,salesname,tb_salesman.salescode as code, sum(ppn-ppnreturn) as ppnnet, sum(totalsales+tax-totalreturn-ppnreturn) as pricenet, sum(totalsales-totalreturn) as subtotal
					FROM tb_invoice,tb_salesman,tb_commgroup,tb_division
					WHERE tb_invoice.salescode = tb_salesman.salescode
						and tb_invoice.commgroupcode=tb_commgroup.commgroupcode
						and tb_division.divisionid = tb_commgroup.divisionid
							".$strwhere."  
							AND
						(tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) 
							group by tb_salesman.salescode";





			$rs = read_write($strsql);

			$_SESSION['count'] = mysql_num_rows($rs);}
		   else if ($rekap=='3')
		{


		$strsql =  "SELECT salesname,salescode FROM tb_salesman ";
		$strsql =$strsql ."  WHERE active=1 and salescode like '".$branch.".%'"; 
		if($_SESSION[salescode] <> "")
		{
		$strsql =$strsql ."   AND salescode ='$_SESSION[salescode]'
		";
		}
		$strsql = $strsql ."	ORDER BY salesname";
		
			
			$rs = read_write($strsql);

			$_SESSION['count'] = mysql_num_rows($rs);

	   }
} else {

	$_SESSION['invno'] = "";
	$_SESSION['companyname'] = "";
	$_SESSION['personname']	= "";
	$_SESSION['c_building']	= "";

	$_SESSION['c_city'] = "";
	$_SESSION['p_street'] = "";
	$_SESSION['p_city'] = "";
	$_SESSION['salesname'] = "";

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
		<title>Rekapitulasi Penjualan</title>
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




			function go_harian() {
				with (document.forms[0]) {
				if (invoicedatefrom.value=='' || invoicedateto.value=='') {
					alert('Please fill Period');
				} else {
					action='rekapitulasi.php?post=1&search=1&rekap=1';

					submit();
				}
				}
			}



			function go_bulanan() {

				with (document.forms[0]) {

					action='rekapitulasi.php?post=1&search=1&rekap=2';
					submit();

				}
			}

			function go_tahunan() {

				with (document.forms[0]) {
					action='rekapitulasi.php?post=1&search=1&rekap=3';
					submit();
					}
			}
		</script>
	</head>
	<body >
		<?php
include('../menu.php');?><br>


		<form method="POST" name=frmrekap action="rekapitulasi.php" onSubmit="return validate()">
		<table border="0" cellspacing="0" cellpadding="0" bgcolor=#e5ebf9 class=header  align="center">
		<tr >
		<td><div class="header icon-48-media">Rekapitulasi Penjualan</div></td>

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
		<td align=center>
			<table border="0" cellspacing="1" cellpadding="0" bgcolor="white" width="100%" align=center>

			<tr>
			<?if($rekap==1){?>
				<td class="small" ><a href="javascript:showCal('Calendar10')" class=smalllink> Period From: </a></td>
				<td class="small"><a href="javascript:showCal('Calendar11')" class=smalllink>Period To:</a></td>
					<?}?>

			<td class="small" valign="middle" >Salesman:</td>


			<td class="small">Product Group:</td>

			<?if($rekap==2){?>
				<td class="small">Month:</td>
				<?}?>
				<?if($rekap==3 or $rekap==2){?>
				<td class="small">Year:</td>
				<?}?>
			</tr>

			<tr>	<?if($rekap==1){?>
				<td class="small"><input type=text style="width:150px;" class="tBox" name="invoicedatefrom" value="<?php echo $_SESSION['invoicedatefrom']; ?>"></td>
				<td class="small"><input type=text style="width:150px;" class="tBox" name="invoicedateto" value="<?php echo $_SESSION['invoicedateto']; ?>"></td>
				    	<?}?>
				<td class="small">
					<select class="tBox" name="salesman" style="width:150px;">
					<option value="" class="tBox">--
					<?php
					$res = read_write("SELECT * FROM tb_salesman WHERE active=1  AND salescode like '".$branch."%' order by salesname ASC");
					while ($row = mysql_fetch_array($res)) {
						echo "<option value='".$row['salescode']."'";
						if($row['salescode']==$_SESSION['salescode']) { echo "selected"; } echo ">".$row['salesname'];
					}
					?></select></td>

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

					<?if($rekap==2){?>
					<td class="small">
					<select class="tBox" name="bulan" style="width:75px;">

					<?php
					echo $_SESSION['bulan'];
					for($i=1;$i<=12;$i++)
					{
						echo "<option value='".$i."'";
						if($i==$_SESSION['bulan']) { echo "selected"; } echo ">".$bulanan[$i];
					}
					?></select></td>
				<?}?>


					<?if($rekap==3 or $rekap==2){?>
					<td class="small">
					<select class="tBox" name="tahun" style="width:75px;">

					<?php
					$res = read_write("SELECT DISTINCT  YEAR(invoicedate) as tahun FROM tb_invoice ORDER BY YEAR(invoicedate) DESC");
					while ($row = mysql_fetch_array($res)) {
						echo "<option value='".$row['tahun']."'";
						if($row['tahun']==$_SESSION['tahun']) { echo "selected"; } echo ">".$row['tahun'];
					}
					?></select></td>
				<?}?>
			</tr>

			<tr><td >&nbsp;</td></tr>

			<tr>
				<td colspan="4" align="center" height="20">
				<?if($rekap==1)
				{?>
				<input type="button" class="tBox" value="Harian" onClick="go_harian();">&nbsp;&nbsp;
				<?}else if($rekap==2)
				{?>
				<input type="button" class="tBox" value="Bulanan" onClick="go_bulanan();">&nbsp;&nbsp;
				<?}else if($rekap==3)
				{?>
				<input type="button" class="tBox" value="Tahunan" onClick="go_tahunan();">&nbsp;&nbsp;
				<?}?>

				<input type="reset" class="tBox" value="Clear" onClick="location.href='rekapitulasi.php?rekap=<?echo $rekap;?>">



			</tr>
			</table>
		</td></tr>
			</table>
	<br>
		</form>
	<?php
if($search==1)
{





switch ($rekap) {
case 1:
$max = ceil($_SESSION['count'] / $recordperpage);
		//echo "$_SESSION[count]";
		$strsqlhead = "SELECT DISTINCT tb_invoice.invoiceno,
						tb_invoice.buyercode,
						tb_company.companycode,
						tb_deliveryaddr.deliverycode,
						tb_salesman.salesname,
						tb_company.companyname,
						tb_buyer.personname,
						(tb_invoice.tax-tb_invoice.ppnreturn) as tax,
						(tb_invoice.totalsales-tb_invoice.totalreturn) as totalsales ";

		$strsql = " FROM tb_invoice, tb_invoiceitems, tb_product,tb_commgroup,
							tb_company,
							tb_buyer,
							tb_deliveryaddr,
							tb_salesman,tb_division ";

		$strsql = $strsql."WHERE 		tb_invoice.buyercode = tb_buyer.buyercode
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
						tb_commgroup.divisionid = tb_division.divisionid";


		$strsql = $strsql." and tb_deliveryaddr.deliverycode = tb_buyer.deliverycode ".$strwhere." AND
						(tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."')))  ";

		$query = $strsqlhead.$strsql."	ORDER BY
											tb_invoice.invoicedate DESC,
											tb_invoice.invoiceno DESC
										LIMIT
											".$start.",".$recordperpage;

		$rsinv = read_write ($query);
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

		//for product report
		$_SESSION['queryprod'] = "SELECT DISTINCT tb_invoice.invoiceno,
							tb_invoice.buyercode,
							tb_company.companycode,
							tb_deliveryaddr.deliverycode,
							tb_salesman.alias,
							tb_company.companyname,
							tb_buyer.personname,
							(tb_invoice.tax-tb_invoice.ppnreturn) as tax,
							(tb_invoice.totalsales-tb_invoice.totalreturn) as totalsales 
					FROM tb_invoice,
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
						tb_invoice.invoicedate DESC,
											tb_invoice.invoiceno DESC";


		if ($_SESSION['count'] > 0)
		{
		
			$result = "
				<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr bgcolor=#e5ebf9 class=header>";

				$result = $result."<td><b>Salesman</b></td>
					<td><b>Customer Name</b></td>
					<td width=9%><b>Invoice No</b></td>
					<td><b>Product Name</b></td>
					<td><b>Qty</b></td>
					<td ><b>Price/Unit</b> </td>

					<td align=right><b>NetTotal</b> &nbsp;</td>
					<td align=right><b>PPN</b> &nbsp;</td>
					<td align=right><b>Total</b> &nbsp;</td>";


			$result = $result."</tr>";
			while ($row = mysql_fetch_array($rsinv)) {
				if ($row['deliverycode'] <> $branch.'.0000000' && $row['deliverycode'] <> '00.0000000' && $row['deliverycode'] <> '01.0000000') {

					$b_company=1;
				} else {

					$b_company=0;
				}
				$invno = $row['invoiceno'];
				if ($invno=="") { $invno="No Invoice";}

				if ($row['companycode'] == $branch.'.0000000' || $row['companycode'] == '00.0000000' || $row['companycode'] == '01.0000000') {
					$p = '1';
				} else {
					$p = '0';
				}


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
							$result = $result."<td><a href='personal.php?url=rekapitulasi.php&bid=".$row['buyercode']."' class=smalllink>".stripslashes($row['personname'])."</a></td>";
						}
						$result = $result."<td><a href='sales.php?sid=".$row['invoiceno']."&bid=".$row['buyercode']."&c=".$row['companycode']."&p=".$p."' class=smalllink>".$invno."</a></td>";
					} else {
						$result = $result."<td colspan=3></td>";
					}
					$result = $result."<td>".$rowitems['productname']."</td>";
					$result = $result."<td>".$rowitems['qty']." ".$rowitems['unit']."</td>";

					$result = $result."<td align=right>".number_format($rowitems['price'],0)." &nbsp;</td>";
					//$result = $result."<td align=right>".number_format($rowitems['price']*$rowitems['volume']*$rowitems['qty'],0)." &nbsp;</td>";

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
		?>
		<table border='0' cellspacing=0 cellpadding=2 width="100%" align="center">
		<?php echo $result; ?>
		<tr><td height=6></td></tr>

		<?php
			if ($page == $max) { ?>
		<tr class=small><td colspan=5  align=right></td><td><b>Total:</b> &nbsp;</td>

		<td align=right><?php echo number_format($_SESSION['sumsubtotal'],0); ?> &nbsp;</td>
		<td align=right><?php echo number_format($_SESSION['sumppn'],0); ?> &nbsp;</td>
		<td align=right><?php echo number_format($_SESSION['suminvoiceprice'],0); ?> &nbsp;</td>
		</tr>

		<?php }

break;
//end of rekap==1 ---------------------------------------------------------------------------------------------------------------------------------------------------
case 2 :

		$max = ceil($_SESSION['count'] / $recordperpage);

		//$strsqlhead = "SELECT SUM((tb_invoice .totalsales+tb_invoice.ppn)-tb_invoice .totalreturn) as bruto,SUM(tb_invoice.ppn) as sumppn,tb_salesman.salesname,tb_invoice.salescode,tb_salesman.salescode ";
		$strsqlhead = "select tb_invoice.invoiceno,salesname,tb_salesman.salescode as code, sum(ppn-ppnreturn) as ppnnet, sum(totalsales+tax-totalreturn-ppnreturn) as pricenet, sum(totalsales-totalreturn) as subtotal";
		$strsql = " FROM tb_invoice,tb_salesman,tb_commgroup,tb_division";

		$strsql = $strsql."	WHERE tb_invoice.salescode = tb_salesman.salescode
						and tb_invoice.commgroupcode=tb_commgroup.commgroupcode
						and tb_division.divisionid = tb_commgroup.divisionid
							".$strwhere."  
						AND (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) 	
							group by tb_salesman.salescode";
		$query = $strsqlhead.$strsql."	ORDER BY tb_salesman.salesname
						LIMIT ".$start.",".$recordperpage;


		//echo $query;
		$rsinv = read_write ($query);
		//for rekap_bulan report
		$_SESSION['queryrkpbln'] = "select tb_invoice.invoiceno,salesname,tb_salesman.salescode as code, sum(ppn-ppnreturn) as ppnnet, sum(totalsales+tax-totalreturn-ppnreturn) as pricenet, sum(totalsales-totalreturn) as subtotal
					FROM tb_invoice,tb_salesman,tb_commgroup,tb_division
					WHERE tb_invoice.salescode = tb_salesman.salescode
					and tb_invoice.commgroupcode=tb_commgroup.commgroupcode
					and tb_division.divisionid = tb_commgroup.divisionid ".$strwhere."  
					AND (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) 	
					group by tb_salesman.salescode
					ORDER BY tb_salesman.salesname";
		

		if ($_SESSION['count'] > 0)
		{
			$result = "
				<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr bgcolor=#e5ebf9 class=header>";

				$result = $result."<td width=20%><b>Salesman</b></td>
					<td align=right><b>Penjualan</b></td>
					<td align=right><b>Bruto</b></td>
					<td align=right><b>Ppn</b></td>
					<td align=right><b>Net</b></td>
					<td align=right><b>NetCollection</b> &nbsp;</td>
					";


			//echo $strwhere;
			$result = $result."</tr>";
			while ($row= mysql_fetch_array($rsinv))
			{
			//echo "$row[invoicedate]<br/>";
			//$net =($row['bruto']-$row['sumppn']);

			$inv=read_write("SELECT DISTINCT invoiceno FROM tb_invoice,tb_salesman,tb_division,tb_commgroup
			where tb_salesman.salescode=tb_invoice.salescode
			and tb_invoice.salescode=$row[code]
			and tb_invoice.commgroupcode=tb_commgroup.commgroupcode
				and tb_division.divisionid = tb_commgroup.divisionid
			".$strwhere."");
			$suminv=mysql_num_rows($inv);
			$ttu=read_write("SELECT tb_invoice.salescode,sum(tb_ttu.payment-tb_ttu.ppn_payment) as colnet
			FROM tb_invoice ,tb_ttu
			WHERE tb_invoice.invoiceno=tb_ttu.invoiceno
			AND tb_invoice.salescode='$row[code]'
			and MONTH(ttudate)='".$_SESSION['bulan']."'
			and YEAR(ttudate)='".$_SESSION['tahun']."'
			GROUP BY tb_invoice.salescode");
			$ttu=mysql_fetch_array($ttu);


					$result = $result."<tr class=small>";

					$result = $result."<td  >".$row['salesname']."&nbsp;</td>";
					$result = $result."<td align=right>".$suminv."</td>";
					$result = $result."<td align=right>".number_format($row['pricenet'])."</td>";
					$result = $result."<td align=right>".number_format($row['ppnnet'])."</td>";
					$result = $result."<td align=right>".number_format($row['subtotal'])."</td>";
					$result = $result."<td align=right>".number_format($ttu['colnet'])."</td>";
					$result = $result."</tr>";
					
			

			}

			
		$querysum= "select tb_salesman.salescode as code, sum(ppn-ppnreturn) as ppnnet, sum(totalsales+tax-totalreturn-ppnreturn) as pricenet, sum(totalsales-totalreturn) as subtotal
					".$strsql."";
		$rssum = read_write($querysum);
		while($rwsum = mysql_fetch_array($rssum))
		{

		$ttusum= "SELECT sum(payment) as colnet
					FROM tb_ttu,tb_invoice
					WHERE tb_invoice.invoiceno=tb_ttu.invoiceno
					and salescode ='$rwsum[code]'
					and MONTH(ttudate)='".$_SESSION['bulan']."'
					and YEAR(ttudate)='".$_SESSION['tahun']."'
					GROUP BY tb_invoice.salescode";
	
		$ttu = read_write($ttusum);			
		$ttutot = mysql_fetch_array($ttu);
		$sumpricenet	+= $rwsum['pricenet'];
		$sumppnnet		+= $rwsum['ppnnet'];
		$sumsubtotal	+= $rwsum['subtotal'];
		$sumcolnet		+= $ttutot['colnet'];

		}	 
		$_SESSION['sumpricenet']	= $sumpricenet;
		$_SESSION['sumppnnet']		= $sumppnnet;
		$_SESSION['sumsubtotal']	= $sumsubtotal;
		$_SESSION['sumcolnet']		= $sumcolnet;
		/* $ttusum= "SELECT sum(payment-ppn_payment) as colnet
					FROM tb_ttu,tb_invoice
					WHERE tb_invoice.invoiceno=tb_ttu.invoiceno
					and tb_ttu.salescode = '(select salescode from tb_invoice where month(invoicedate)='".$_SESSION['bulan']."')'
					and MONTH(ttudate)='".$_SESSION['bulan']."'
					and YEAR(ttudate)='".$_SESSION['tahun']."'";
		if($_SESSION['salescode'])
		{
		$ttusum=$ttusum." and tb_invoice.salescode='".$_SESSION['salescode']."'";
		}
		else if($_SESSION['selgroup'])			
		$ttusum=$ttusum." GROUP BY MONTH(ttudate)";
		
		$ttu = read_write($ttusum);
		$ttutot=mysql_fetch_array($ttu);*/
		
		
			
	}

		?>
		<table border='' cellspacing=0 cellpadding=2 width="100%" >
		<?php echo $result; ?>
		<tr><td height=6></td></tr>

		<?php
			if ($page == $max) { ?>
		<tr class=small><td  align=right></td><td><b>Total:</b> </td>
		<td align=right><?php echo number_format($_SESSION['sumpricenet'],0); ?> </td>
		<td align=right><?php echo number_format($_SESSION['sumppnnet'],0); ?> </td>
		<td align=right><?php echo number_format($_SESSION['sumsubtotal'],0); ?> </td>
		<td align=right><?php echo number_format($_SESSION['sumcolnet'],0); ?> </td>
		</tr>

		<?php }



break;
//end offrekap==2 ------------------------------------------------------------------------------------------------------------------------------------
case 3:


	$max = ceil($_SESSION['count'] / $recordperpage);
		$strsqlhead = "SELECT salesname,salescode  ";
		$strsql = " FROM tb_salesman ";
		$strsql =$strsql ."  WHERE active=1 and salescode like '".$branch.".%'"; 
		if($_SESSION[salescode] <> "")
		{
		$strsql=$strsql." AND  salescode ='$_SESSION[salescode]'";
		}
		$query = $strsqlhead.$strsql." 	ORDER BY salesname
		LIMIT ".$start.",".$recordperpage;
		//echo $query;
		$rsinv = read_write ($query);
		//for rekap_tahun report
		$_SESSION['queryrkpthn'] = "SELECT salesname,salescode FROM tb_salesman";
		$_SESSION['queryrkpthn'] =$_SESSION['queryrkpthn'] ."  WHERE active=1 and salescode like '".$branch."%' OR substring(salescode, 0, 1 )='". $old_branch."'"; 
		if($_SESSION[salescode] <> "")
		{
		$_SESSION['queryrkpthn'] =$_SESSION['queryrkpthn'] ."   AND salescode ='$_SESSION[salescode]'";
		}
		$_SESSION['queryrkpthn'] = $_SESSION['queryrkpthn'] ." 	ORDER BY salesname";
		
		if ($_SESSION['count'] > 0)
		{
			$result = "
				<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr bgcolor=#e5ebf9 class=header>";

			$result = $result."<td width=''><b>Salesman</b></td>";
			$qbln=read_write("SELECT MAX(MONTH(invoicedate)) as jmlbln FROM tb_invoice WHERE YEAR(invoicedate)='$_SESSION[tahun]'");
			$jbln=mysql_fetch_array($qbln);
			$jmlbln= $jbln['jmlbln'];
			
			for($b=1;$b<=$jmlbln;$b++)
			{
			$result = $result."<td align=right width=''><b>$bulanan[$b]</b>&nbsp;</td>";
			}

			$result = $result."<td align=right width=''><b>TOTAL</b></td>";
			$result = $result."</tr>";
			//echo $strwhere;
			while ($row= mysql_fetch_array($rsinv))
			{

			//echo "$row[invoicedate]<br/>";     sum(ppn-ppnreturn) as ppnnet, sum(totalsales+tax-totalreturn-ppnreturn) as pricenet, sum(totalsales-totalreturn) as subtotal";
			
			$rkpthn=read_write("SELECT  sum(totalsales+tax-totalreturn-ppnreturn) as pricenet,MONTH(tb_invoice.invoicedate) as month
						FROM tb_invoice,tb_salesman,tb_commgroup,tb_division
						WHERE tb_invoice.salescode = tb_salesman.salescode 
						and tb_salesman.salescode='$row[salescode]'
						and tb_invoice.commgroupcode=tb_commgroup.commgroupcode
						and tb_commgroup.divisionid=tb_division.divisionid
			 			".$strwhere ."
						AND (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) 	
						group by month
						ORDER BY  month ");


					$result = $result."<tr class=small>";
					$result = $result."<td  >".$row['salesname']."&nbsp;</td>";
					$k=1;
					$sumpricenetver = "";
					
				While ($rkp=mysql_fetch_array($rkpthn))
				{
					$bln=$rkp['month'];
					for ($i=$k;$i<=$bln;$i++)
					{
					if($bln==$i){
					$result = $result."<td align=right>".number_format($rkp['pricenet'])."</td>";
					$sumpricenetver += $rkp['pricenet'];
					}
					else {
					$result = $result."<td align=right>-</td>";}
				
					$k++;
					}
				
	 
				}
			//tot ver	
				for($i=$k;$i<=$jmlbln;$i++)
				{
				$result = $result."<td align=right>-</td>";	
				 }
				$result = $result."<td align=right>".number_format($sumpricenetver)."</td>";
			$result = $result."</tr>";
		//	$totsumpricenetver += $sumpricenetver;
			}
			
			if ($page == $max) {
	$result = $result."<tr><td height=6></td></tr>
	<tr class=small><td align=right ><b>TOTAL</b></td>
	";
	
	// Total horizontal	

	$qsum2=read_write("SELECT  sum(totalsales+tax-totalreturn-ppnreturn) as pricenethor,MONTH(tb_invoice.invoicedate) as month
						FROM tb_invoice,tb_salesman,tb_commgroup,tb_division
						WHERE tb_invoice.salescode = tb_salesman.salescode 
						and tb_invoice.commgroupcode=tb_commgroup.commgroupcode
						and tb_commgroup.divisionid=tb_division.divisionid
			 			".$strwhere ."
						AND (tb_invoice.invoiceno like 'FK".$branch."%' OR (substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' or (substring(tb_invoice.invoiceno,1,1)='".$old_branch."' and substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) 	
						group by month
						ORDER BY  month ");
			
		
			$j=1;
			while($sum2=mysql_fetch_array($qsum2))
			{
			$bln2 = $sum2['month'];	
				for ($i=$j;$i<=$bln2;$i++)
					{
					if($bln2==$i){$result = $result."<td align=right>".number_format($sum2['pricenethor'])."</td>";
					$totsumpricenet += $sum2['pricenethor'];
					}
					else {$result = $result."<td align=right>-</td>";}
					$j++;
					$sumpricenethor[$bln2] = $sum2['pricenethor'];
					
					}
				}
			for($i=$j;$i<=$jmlbln;$i++)
					{
					$result = $result."<td align=right>-</td>";	
					}
				
			$result = $result."<td align=right >".number_format($totsumpricenet)."</td>";
			$result = $result."</tr>";
		//makes session
			
			$_SESSION['jmlbln']=$jmlbln;
			for($i=1;$i<=$jmlbln;$i++)
			{
			$_SESSION['sumpricenethor'.$i] = $sumpricenethor[$i];
			}
			$_SESSION['totsumpricenet']= $totsumpricenet;
		
	}	
		 echo $result; ?>
		
		<?php }

		

	break;
//end of rekap 3 --------------------------------------
}//end of case
//-------pages
	if ($_SESSION['count'] > $recordperpage) { ?>
		<tr><td align="center">
			<th colspan=4 align="center"><font class="small">Pages: </font>&nbsp;
			<?php
			// menampilkan link previous
			if ($page > 1) {?>
			<a href="rekapitulasi.php?page=<?php echo ($page-1); ?>&search=1&rekap=<?php echo $rekap; ?>" class="pagemenu">Prev</a>
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
								<a href="rekapitulasi.php?page=<?php echo $i; ?>&search=1&rekap=<?php echo $rekap; ?>" class="pagemenu">[<?php echo ($i); ?>]</a>
						<?php }
						$showPage = $i;
					}
				}
					if ($page < $max) {?>
						<a href="rekapitulasi.php?page=<?php echo ($page+1); ?>&search=1&rekap=<?php echo $rekap; ?>" class="pagemenu">Next</a>
			</th>
		</td></tr>

			<?php 	}
		}

//end of pages

		if ($_SESSION['count']>0) {
			?>
		<tr>
		<? if($rekap==1){?>
			<th colspan=8 align=center><input type=button value='Export To Excel' class="tBox" onclick='javascript:window.frmexcel.location.href="savetoexcel.php?ver=3"'> <iframe id=frmexcel name=frmexcel style='visibility:hidden;width:10px;height:10px'></iframe>
			</th>
		<?php }elseif($rekap==2){ ?>
		<th colspan=8 align=center><input type=button value='Export To Excel' class="tBox" onclick='javascript:window.frmexcel.location.href="savetoexcel.php?ver=5"'> <iframe id=frmexcel name=frmexcel style='visibility:hidden;width:10px;height:10px'></iframe>
		</th>
		<?php } elseif($rekap==3){ ?>
		<th colspan=15 align=center><input type=button value='Export To Excel' class="tBox" onclick='javascript:window.frmexcel.location.href="savetoexcel.php?ver=6"'> <iframe id=frmexcel name=frmexcel style='visibility:hidden;width:10px;height:10px'></iframe>
		</th>
		<?php } ?>
	</tr>

		
</table>
<?php
}
}
?>
</body>
</html>