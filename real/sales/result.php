<?php 
include('../constant.php'); 
include('../database.php'); 
	if ($_SESSION['user'] == '') {
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$page 		= $_GET['page']; // index  paging
	$x 			= $_GET['x'];
	$search 	= $_GET['search'];
	$buyer_name = $_POST['buyer_name'];
	$salesman = $_POST['salesman'];
	if ($x == '1')
	{
		if ($buyer_name=="" && $salesman == '') {
			Header('Location: index.php');
		}
		$_SESSION['search']     = $search;
		$_SESSION['buyer_name'] = $buyer_name;
		$_SESSION['salesman'] = $salesman;

		
		if ( $_GET['sidcust']<>'') {
			
			$_SESSION['sidcust'] = $_GET['sidcust']; }
		else {
			$_SESSION['sidcust'] = '';
		}

	} else {
		$search = $_SESSION['search'];
		$buyer_name = $_SESSION['buyer_name'];	
		$salesman = $_SESSION['salesman'];	
	}	

	
	if ($page == "")
	{
		$page = 1;
	}
	
	$result = "<tr><th class='small'>No Result Found</th></tr>";
	$_SESSION['backurl'] = "result.php?search=".$search;
	if ($x == '1') {
		
		$where = '';
		if ($_SESSION['salesman'] <> '') {
			$where .= " and tb_salesman.salescode='".$salesman."'";
		}
		
		if ($_SESSION['buyer_name'] <> '') {
			$where .= " and (tb_company.companyname like '%".$buyer_name."%' or tb_buyer.personname like '%".$buyer_name."%')";
		}
		if ($_SESSION['salesman'] <> '') {
			$rscount = read_write("select count(distinct tb_company.companycode, tb_buyer.buyercode, tb_salesman.salescode) as cnt 			from tb_invoice, tb_salesman, tb_company, tb_deliveryaddr, tb_buyer where
					tb_invoice.salescode = tb_salesman.salescode and
					tb_company.companycode = tb_deliveryaddr.companycode and
					tb_buyer.deliverycode = tb_deliveryaddr.deliverycode and
					tb_invoice.buyercode = tb_buyer.buyercode ".$where);
		} else {
			$rscount = read_write("select count(distinct tb_company.companycode, tb_buyer.buyercode) as cnt from tb_company, tb_deliveryaddr, tb_buyer where
					tb_company.companycode = tb_deliveryaddr.companycode and
					tb_buyer.deliverycode = tb_deliveryaddr.deliverycode ".$where);
		}
		
		$rowcount = mysql_fetch_array($rscount);
		$_SESSION['end'] = $rowcount['cnt'];
		$_SESSION['where'] = $where;
	}
	$recordperpage	= 60;
	$start 			= ($page-1) * $recordperpage;
	$max 			= ceil($_SESSION['end'] / $recordperpage);

	$query = "select distinct					
							tb_buyer.buyercode, 							
							tb_company.companycode, 
							tb_deliveryaddr.deliverycode,
							tb_company.companyname, 
							tb_buyer.personname, 
							tb_deliveryaddr.street 		AS c_street, 
							tb_deliveryaddr.building 	AS c_building, 
							tb_deliveryaddr.city 		AS c_city, 
							tb_buyer.street 			AS p_street, 
							tb_buyer.city 				AS p_city, 
							tb_buyer.personname";
	if ($_SESSION['salesman'] <> '') {
		$query .= ", tb_salesman.salesname FROM 
								tb_invoice,
								tb_salesman,
								tb_company, 
								tb_deliveryaddr, 
								tb_buyer 
							
							WHERE
								tb_invoice.salescode = tb_salesman.salescode and tb_invoice.buyercode = tb_buyer.buyercode and ";
	} else {
		$query .= " FROM 						
								tb_company, 
								tb_deliveryaddr, 
								tb_buyer 							
							WHERE ";
	}

	$query .= " tb_company.companycode=tb_deliveryaddr.companycode 							
							AND 
								tb_deliveryaddr.deliverycode=tb_buyer.deliverycode 
							".$_SESSION['where']." ORDER BY 
								companyname limit ".$start.",".$recordperpage;

	$res = read_write($query);
	
if ($_SESSION['end'] > 0)
{
	$result = "<tr bgcolor=#e5ebf9 class=header>				
				<td><b>Company Name</b></td>
				<td><b>Contact Name</td>
				<td><b>Delivery Address</b></td>
				<td><b>Salesman</b></td>
				</tr>";
	while ($row = mysql_fetch_array($res)) {	
		
		if ($row['deliverycode']<> $branch.'.0000000' && $row['deliverycode']<> '01.0000000' && $row['deliverycode']<> '00.0000000') {
			$address = trim($row['c_street']);
			if (trim($row['c_building'])<>"") {
				$address = $address.", ".trim($row['c_building']);
			}
			if (trim($row['c_city'])<>"") {
				$address = $address.", ".trim($row['c_city']);
			}
			$b_company=1;
		} else {
			$address = trim($row['p_street']);
			if (trim($row['p_city'])<>"") {
				$address = $address.", ".trim($row['p_city']);
			}
			$b_company=0;
		}
		
						
		if ($b_company<>1) {
			$p = '1';			
		} else {
			$p = '0';
		}
		$result = $result."<tr class=small>";					
		if($b_company==1) {
			$result = $result."<td><a href='corporate.php?c=".$row['companycode']."' class=smalllink>".$row['companyname']."</a></td>";					
		} else {
			$result = $result."<td></td>";
		}
		
		if($b_company==1) {
			$result = $result."<td><a href='personal.php?bid=".$row['buyercode']."&cid=".$row['companycode']."' class=smalllink>".stripslashes($row['personname'])."</a></td>";		
		} else {
			$result = $result."<td><a href='personal.php?bid=".$row['buyercode']."' class=smalllink>".stripslashes($row['personname'])."</a></td>";	
		}
		
		if($b_company==1) {
			$result = $result."<td><a href='deliveryaddr.php?id=".$row['deliverycode']."' class=smalllink>".stripslashes($address)."</a></td>";		
		} else {
			$result = $result."<td>".stripslashes($address)."</td>";	
		}
		if ($_SESSION['salesman'] <> '') {
			$result = $result."<td>".stripslashes($row['salesname'])."</td>";
		} else {
			$result = $result."<td> &nbsp;</td>";
		}

		$result = $result."</tr>";	
	}
}
?>
<html>
<head>
<title>Search Result</title>
<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
<script language=javascript>
function validate() {
		with(document.frmsearch) {
			if (buyer_name.value== '' && salesman.value=='') {
				alert('Please fill something to search');
				return false;
			} else {
				return true;
			}				
		}
	}
</script>
</head>
<body>
<?php include('../menu.php'); ?>
<?
	if (!validate_search($buyer_name) && $buyer_name <> '') {
		echo "<br><br><div align=center>Pencarian anda kurang baik. Harap diulang dengan me-klik <a href=index.php>disini</a></div>";
		exit();
	}
	?>
<br>
<form method="POST" action="result.php?x=1" name=frmsearch onsubmit="return validate()" >
	<table border="0" cellspacing="1" cellpadding="1" width="100%">
	<tr>
		<td width="15%"><div class="header icon-48-mediamanager">Search Result</div></td>
		<td class="small"><b>Costumer Name:</b>&nbsp;&nbsp;
			<input type="text" name="buyer_name" class="tBox" size="40" maxlength=220 value='<?php echo $_SESSION['buyer_name']; ?>'>
			<select class="tBox" name="salesman" style="width:150px;">
					<option value="" class="tBox">==Salesman==
					<?php
					$res = read_write("SELECT * FROM tb_salesman where salescode like '".$branch."%' order by salesname ASC");
					while ($row = mysql_fetch_array($res)) {
						echo "<option value='".$row['salescode']."'"; 
						if($row['salescode']==$_SESSION['salesman']) { echo "selected"; } echo ">".$row['salesname'];
					}
					?></select>
			<input type="submit" value="Search" class="tBox">
		</td>
	</tr>
	</table>
</form>
	<table border=0 cellspacing=0 cellpadding=2 width="100%">
		<?php echo $result; ?>
		<tr><td height=10></td></tr>
		<?php 
			if ($_SESSION['end'] > $recordperpage) { ?>
		<tr>
			<th colspan=4><font class="small">Page: </font>&nbsp;
			<?php
			if ($page > 1) {
			?>
				<a href="result.php?page=1" class="pagemenu">[First]</a> 
				<a href="result.php?page=<?php echo ($page-1); ?>" class="menu">[Prev]</a> 
			<?php
							}
							for ($i=1;$i<=$max;$i++)
							{
								if ($i == $page)
								{
						?>
						<font class="small">[<?php echo ($i); ?>]</font> 
						<?php
								}
								else
								{
						?>
						<a href="result.php?page=<?php echo $i; ?>" class="pagemenu">[<?php echo ($i); ?>]</a> 
						<?php
								}
							}
							if ($page < $max)
							{
						?>
						<a href="result.php?page=<?php echo ($page+1); ?>" class="pagemenu">[Next]</a> 
						<a href="result.php?page=<?php echo $max; ?>" class="menu">[Last]</a> 
						
					</th>
				</tr>
				<?php } 
}?>
			</table>
			<br>
			<div align="center">
			<?php
			echo "<input type=button value='Tambah Pelanggan Corporate' class=tBox onclick='window.location.href=\"corporate.php?c=0\"'>";
			echo "&nbsp;&nbsp; ";
			echo "<input type=button value='Tambah Pelanggan Personal' class=tBox onclick='window.location.href=\"personal.php?bid=0\"'>";
			
			?></div>
		
	</body>
</html>