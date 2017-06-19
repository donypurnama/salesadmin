<?php include('../constant.php');?>
<?php include('../database.php');?>
<?php
if ($_SESSION['user'] == '')
{
	//Header('Location: '.DOMAIN_NAME.'index.php');
	Header('Location: ../index.php');
}
	
$divisionid 	= $_REQUEST['divisionid'];
$act 			= $_REQUEST['act'];
$productcode 	= $_REQUEST['productcode'];

$x 				= $_GET['x'];
$search 		= $_GET['search'];

$result = "<tr><th class='small'>No Result Found</th></tr>";
if ($search == 1) {

	$_SESSION['divisionid'] = $_SESSION['divisionid']; 
	$_SESSION['productname'] = $_POST['productname'];
	$_SESSION['productcode'] = $_POST['productcode'];
	$_SESSION['product'] = $_POST['product'];
}
if ($x == 1) {
	$_SESSION['divisionid'] = $_SESSION['divisionid']; 
	$_SESSION['productname'] = "";
	$_SESSION['productcode'] = "";
	$_SESSION['product'] = "";
}


if ($_REQUEST['update']==1) {
	$productname = $_POST['hidprodname'];
	if ($act==1) {
		read_write("insert into tb_product (productname, productcode, divisionid) values ('".$productname."','".$productcode."','".$divisionid."')");
	} elseif ($act==2) {
		read_write("update tb_product set productname='".$productname."' where productcode='".$productcode."'");	
	} else {
		read_write("delete from tb_product where productcode='".$productcode."'");
	}
}

?>
<html>
<head>
	<title>Master</title>
	<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
	<script language=javascript>
	function editproduct(act, productcode, divisionid) {
		if (act==1 || act==2) {
		var prodname = prompt("Enter the product name","");
			if (typeof(stext)=='string') {
				document.forms[0].hidprodname.value = prodname;			
				document.forms[0].action='product.php?update=1&act='+act+'&productcode='+productcode+'&divisionid='+divisionid;
				document.forms[0].submit();
			}
		} else {
			if (confirm("Are you sure to delete this product?")) {
				location.href='product.php?update=1&act='+act+'&productcode='+productcode+'&divisionid='+divisionid;
				
			}
		}
	}
	
	function choosediv() {
		var divisionid = document.forms[0].seldivision.value;		
		location.href='product.php?divisionid='+divisionid+'&x=1';
	}
	
	function choosediv2() {
		var divisionid = document.forms[0].seldivision2.value;		
		location.href='product.php?divisionid='+divisionid+'&x=1';
	}
	
	function openaddproduct(divisionid) {
		window.open('addnewproduct.php?divisionid='+divisionid);
	}
	
	function openwindow(divisionid)
	{
		window.open("addnewproduct.php?divisionid="+divisionid);
	}


	</script>	
</head>
<body>
	<?php include('../menu.php'); ?><br>
	<form name='frmprod' method='post' action="product.php?divisionid=<? echo $divisionid; ?>&search=1" onSubmit="return validate()" >
	<input type=hidden name=hidprodname>
	
		<table border="0" cellspacing="0" cellpadding="7" align="center" width="60%" bgcolor="#e5ebf9">
			<tr bgcolor='white'>
				<td colspan="3" class="small" valign='top'>
					<div class="header icon-48-article"><b class='big'>Division: </b>
				<?
				$res = read_write("SELECT * FROM tb_division WHERE divisionid='".$divisionid."'");
				$row = mysql_fetch_array($res);
				$_SESSION ['divisionid'] = $divisionid;
				echo "<b class='big'>".$row['divisionname']."</b>";
				
				?>
				</div>
				<br>
				
				<table border="0" cellspacing="0" cellpadding="5" align="center" width="100%" >
				<tr>
					<td width='45%' class="small">
					Search Product: 
					<input type="text" name="product" value='<?php echo $_SESSION['product'] ;?>' size="20" maxlength="20">	
					<input type="submit" value="Find">
					<input type="reset" class="tBox" value="Clear" onclick="location.href='../master/product.php?divisionid=<? echo $divisionid; ?>&x=1'">&nbsp;</td>
					<td width='55%' align='right' class="small">
					<?	if ($_SESSION['groups'] == 'root') 
					{ ?>
				
						<A href="javascript: openwindow('<? echo $divisionid; ?>')">Add New Product</A> |

						
					<? } ?>
					Division: 
					<select name="seldivision" onchange="choosediv()" class="inputbox">
					<option value="">--
					<?php
					$res = read_write("SELECT divisionid,
												divisionname 
										FROM
											tb_division 
										where 
											divisioninv is not null");

					while ($row = mysql_fetch_array($res)) {
						echo "<option value='".$row['divisionid']."'";
						if ($row['divisionid']==$divisionid	) { echo " selected"; }	echo ">".$row['divisionname'];
					}?>
					</select>
					
					
					
				
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td align="center" width="18%" class=header><b>Product Code</b></td>
				<td align="center" width="60%" class=header><b>Product Name</b></td>
				<td align="center" class=header>&nbsp;</td>
			</tr>
			<tr>
				<table border="0" cellspacing="2" cellpadding="2" width="60%" bgcolor="white" align=center>
				<?php //echo $result; 
				
				$strwhere = "";
				if($divisionid <> "") {
					$strwhere = $strwhere." and divisionid = '".$divisionid."'";
				}
				
				if ($_SESSION['product'] <> "") { 
					$strwhere = $strwhere." and productname like '%".$_SESSION['product']."%'"; 
				}
				
				if ($_SESSION['product'] <> "" ) {
					$strwhere = $strwhere." or productcode like '".$_SESSION['product']."'";
				}
				/*------------------------------------------------------------------------------------------------------------------------------------------------*/
				
			
				if($search == 1 || $x == 1) {			
					$rscnt = read_write("select count(productcode) as cnt 
											from 
												tb_product 
											where productcode is not null".$strwhere);
								
					$rwcnt = mysql_fetch_array($rscnt);
					$_SESSION['count'] = $rwcnt['cnt']; //jumlah data		
												
				}
				
				
				// apabila $_GET['page'] sudah didefinisikan, gunakan nomor halaman tersebut, 
				// sedangkan apabila belum, nomor halamannya 1.
				if(isset($_GET['page'])) {
					$page = $_GET['page'];
				} else {
					$page = 1;
				}

				//jumlah data yang akan ditampilkan 
				$recordperpage = 50;

				//perhitungan offset 
				$start = ($page - 1) * $recordperpage;
				
				//menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
				$max = ceil($_SESSION['count'] / $recordperpage);					
			
				//menampilkan data perhalaman sesuai offset dan recordperpage
				$res = read_write("select * from tb_product 
								where 
									productcode is not null".$strwhere." 
								order by
									createdate DESC
								LIMIT 
									".$start.",".$recordperpage);
									
				$count = mysql_num_rows($res);
				
				
				
				while ($row = mysql_fetch_array($res)) {
			
					echo "<tr class=small>";
					echo "<td width=15% align=left>".$row['productcode']."</td>";
					echo "<td width=70%><a href='addproduct.php?act=2&productcode=".$row['productcode'].		"&divisionid=".$row['divisionid']."' class=smalllink>".$row['productname']."</td>";
					echo "<td widt=10% align=left>".$row['volume']."&nbsp;".$row['pcs']."</td>";
					echo "</tr>";
				} 			
			
			
			echo "<tr><td colspan='3' align='right' class='small'>";
			
			//jika hanya 1 halaman makan nomor halaman tidak ditampilkan
			if($_SESSION['count'] > $recordperpage) {
				
				echo "<font class='pagemenu' color='#0000a4'><b>Page ".$page." of ".$_SESSION['count']." &nbsp;&nbsp;</b></font>";
								
				if($page == 1) {
					echo "&nbsp;";
				} else {
					echo "<b><a href='".$_SERVER['PHP_SELF']."?page=1&divisionid=".$divisionid."' class='pagemenu'>&nbsp; << First &nbsp;</a></b>";
				}
				
				// << previous  link
				if($page > 1) echo "<b><a href='".$_SERVER['PHP_SELF']."?page=".($page-1)."&divisionid=".$divisionid."' class='pagemenu'> < &nbsp;</a></b>";
				
				//menampilkan nomor halaman dengan menggunakan looping 
				for($i = 1; $i <= $max; $i++) {
								
					if((($i >= $page - 3) && ($i <= $page + 3)) || ($i == 5) || ($i == $max)) {
						
						//if(($showPage == 1) && ($i != 2)) echo " ... ";
						//if(($showPage != ($max -1)&& ($i == $max))) echo " ... ";
						if($i == $page) {
							echo "<b><font class='small'>&nbsp;[".$i."]&nbsp;</font></b>";
						} else {
							echo "<a href='".$_SERVER['PHP_SELF']."?page=".$i."&divisionid=".$divisionid."' class='pagemenu'>&nbsp;".$i."&nbsp;</a>";
						}
						$showPage = $i;
					}
				}
				
				//next link
				if($page < $max) {
					echo "<b><a href='".$_SERVER['PHP_SELF']."?page=".($page+1)."&divisionid=".$divisionid."' class='pagemenu'>&nbsp; > </a></b>";
				}
				
				//last page link
				if($page == $max) {
					echo "&nbsp;";
				} else {
					echo "<b><a href='".$_SERVER['PHP_SELF']."?page=".($max)."&divisionid=".$divisionid."' class='pagemenu'>&nbsp; Last >> </a></b>";
				}				
			
			}//jika hanya 1 halaman makan nomor halaman tidak ditampilkan
			echo "</td></tr>";
			?>
			
			</table>
		</table>

		
	<table border="0" cellspacing="0" cellpadding="7" align="center" width="60%" >
		<tr>
			<td class=small align=right>
			<?php if ($_SESSION['groups'] == 'root') { ?>
					
					<a href=addproduct.php?act=1&divisionid=<?php echo $divisionid; ?> class='small'>Add New Product</a> | 
					<?php } ?>
			Division: 
			
			<select name='seldivision2' onchange='choosediv2()' class='inputbox'>
					<option value=''> -- 
			<?php 
			$res_a = read_write("SELECT divisionid, divisionname FROM tb_division WHERE divisioninv is not null");
			while ($row = mysql_fetch_array($res_a)) {
				echo "<option value='".$row['divisionid']."'";
				if($row['divisionid'] == $divisionid) { echo "selected"; } echo ">".$row['divisionname'];
			}
				?>
			</select>
				</td>
		</tr>
		
	</table>
	</form>
	</body>
</html>