<?php 
include('../constant.php'); 
include('../database.php'); 
if ($_SESSION['user'] == '')
{
	//Header('Location: '.DOMAIN_NAME.'index.php');
	Header('Location: ../index.php');
}
	
$buyercode = $_GET['bid'];
	
if ($buyercode == '0') {
	$buyercode = ""; 
} else {
	$buyercode = $_GET['bid'];
}
	
	$bcode 			= $_GET['bcode'];
	$deliverycode 	= $_GET['deliverycode'];
	$cid 			= $_GET['cid'];	
	$update 		= $_GET['update'];
	$delete 		= $_GET['delete'];	
	$page 			= $_GET['page'];
	$error 			= '';
	 
	 
	//jumlah data yang ditampilkan 
	$recordperpage = 20; 
	
	// apabila $_GET['page'] sudah didefinisikan, gunakan nomor halaman tersebut, 
	// sedangkan apabila belum, nomor halamannya 1.
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	} else 
		$page = 1;
	
	

	//perhitungan offset
	$start = ($page - 1) * $recordperpage;
	
	$_SESSION['buyercode'] 		= $buyercode; 
	$_SESSION['cid'] 			= $cid;
	$_SESSION['deliverycode'] 	= $deliverycode;
	
	
	$deliverycode = $_SESSION['deliverycode'];
	$buyercode 	= $_SESSION['buyercode'] ;
	$cid 		= $_SESSION['cid'];
	
	
	
	
	if ($delete==1)
	{
	
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
	
	
		/*------------------ DELETE ALL TB_DELBUYER ------------------*/

		$pros = hapusdata(tb_delbuyer, "last_update < '".$next."'");
		/*-------------------------------------------------------------------------*/	
		
		/***** INSERT BUYERCODE TO TBDELBUYER  *******/ 
		
		if ($branch <> '') {	
		
			$res = read_write("INSERT INTO tb_delbuyer (buyercode, last_update, sentstatus) VALUES ('".$buyercode."', '".$today."',0)");
		}
		
		$res = read_write("select invoiceno from tb_invoice where buyercode ='".$buyercode."'");
		$i=0;
			while ($row=mysql_fetch_array($res)) {
				if($i==0) {
					$strinvoice = "invoiceno = '".$row['invoiceno']."'";				
				} else {
					$strinvoice = $strinvoice." or invoiceno='".$row['invoiceno']."'";
				}
			
				/*------------------ INSERT INVOICE TO TBDELINVOICE ------------------*/
				if ($branch <> '01' && $branch <> '00' && $branch <> '') {
					read_write("INSERT INTO tb_delinvoice(invoiceno, last_update, sentstatus) VALUES ('".$row['invoiceno']."', '".$today."',0) ");	
				}
			$i++;
		}
			
		$res = read_write("delete from tb_invoiceitems where ".$strinvoice);
		$res = read_write("delete from tb_invoice where ".$strinvoice);
		$res = read_write("delete from tb_buyer where buyercode = '".$buyercode."'");
		
		if($cid <> '') {

			header("Location: deliveryaddr.php?id=".$deliverycode."&cid=".$cid."'");
			
		} else {

			header("Location: index.php");
		}
		
	}
	if ($update == 1)
	{
		
	$title 			= $_POST['title'];	
	$personname 	= mysql_real_escape_string($_POST['personname']);		
	$street 		= mysql_real_escape_string($_POST['street']);
	$region 		= mysql_real_escape_string($_POST['region']);
	$city 			= $_POST['city'];
	$postal 		= $_POST['postal'];
	$phone 			= $_POST['phone'];
	$mobilephone 	= $_POST['mobilephone'];
	$email 			= $_POST['email'];
	$birthday 		= $_POST['birthday'];
	$hobby 			= $_POST['hobby'];
	$npwp 			= $_POST['npwp'];	
	$deliverycode = $_GET['deliverycode'];

		if ($personname == "")
		{
			$error = 'Name must not be empty';
		} else	{
		
			if ($buyercode == "") { 
			
				if ($deliverycode == "") {
				
				//AUTO NUMBERING FOR CODE
				$rstemp = read_write("select   
											replace(buyercode, '".$branch.".','') as nbr 
										FROM 
											`tb_buyer` where buyercode like '".$branch.".%'
										ORDER BY 
											buyercode DESC LIMIT 1");
				$rowtemp = mysql_fetch_array($rstemp);
				$invtemp = (int) $rowtemp['nbr'];
				$invtemp++;
					
				if (strlen($invtemp)==6) {
						$invtemp = '0'.$invtemp;
				} elseif (strlen($invtemp)==5) {
						$invtemp = '00'.$invtemp;
				} elseif (strlen($invtemp)==4) {
						$invtemp = '000'.$invtemp;
				} elseif (strlen($invtemp)==3) {
						$invtemp = '0000'.$invtemp;
				} elseif (strlen($invtemp)==2) {
						$invtemp = '00000'.$invtemp;
				} elseif (strlen($invtemp)==1) {
						$invtemp = '000000'.$invtemp;
				}
					$buyercode = $branch.".".$invtemp;
				
				$res = read_write("insert into tb_buyer (
											buyercode, 
											deliverycode, 
											personname, 
											title, 
											street,
											region,
											city, 
											postal, 
											phone, 
											mobilephone, 
											email, 
											birthday, 
											hobby, 
											npwp,
											last_update,
											sentstatus
											) 
										values (
										'".$buyercode."', 
										'".$branch.".0000000',
										'".$personname."',
										'".$title."',
										'".$street."',
										'".$region."',
										'".$city."',
										'".$postal."',
										'".$phone."',
										'".$mobilephone."',
										'".$email."',
										'".$birthday."',
										'".$hobby."',
										'".$npwp."',
										'".$today."',
										0
										)");
					
					if ( $_SESSION['sidcust']<> "") { 
						header ("Location: sales.php?sid=".$_SESSION['sidcust']."&p=1&bid=".$buyercode."&c=0");
					} else {
						header("Location: sales.php?url=index.php&add=1&bid=".$buyercode."&p=1");
					}
						
				} else {
				
				//AUTO NUMBERING FOR CODE
				$rstemp = read_write("select  
											replace(buyercode, '".$branch.".','') as nbr 
										FROM 
											`tb_buyer` where buyercode like '".$branch.".%'
										ORDER BY 
											buyercode DESC LIMIT 1");
				$rowtemp = mysql_fetch_array($rstemp);
				$invtemp = (int) $rowtemp['nbr'];
				$invtemp++;
					
				if (strlen($invtemp)==6) {
						$invtemp = '0'.$invtemp;
				} elseif (strlen($invtemp)==5) {
						$invtemp = '00'.$invtemp;
				} elseif (strlen($invtemp)==4) {
						$invtemp = '000'.$invtemp;
				} elseif (strlen($invtemp)==3) {
						$invtemp = '0000'.$invtemp;
				} elseif (strlen($invtemp)==2) {
						$invtemp = '00000'.$invtemp;
				} elseif (strlen($invtemp)==1) {
						$invtemp = '000000'.$invtemp;
				}
					$buyercode = $branch.".".$invtemp;
				
					$res = read_write("insert into tb_buyer (
											buyercode, 
											deliverycode, 
											personname, 
											title, 
											street,
											region,
											city, 
											postal, 
											phone, 
											mobilephone, 
											email, 
											birthday, 
											hobby,
											last_update,
											sentstatus
											) 
								values (
									'".$buyercode."', 
									'".$deliverycode."',
									'".$personname."',
									'".$title."',									
									'".$street."',
									'".$region."',
									'".$city."',
									'".$postal."',
									'".$phone."',
									'".$mobilephone."',
									'".$email."',
									'".$birthday."',
									'".$hobby."',
									'".$today."',
									0
									
									)");
									
					header("Location: deliveryaddr.php?id=".$deliverycode."&cid=".$cid);
				}
			}
			else
			{
				$res = read_write("update tb_buyer set 
										title='".$title."',
										personname='".$personname."',
										street='".$street."',
										region='".$region."',
										city='".$city."',
										postal='".$postal."',
										phone='".$phone."',
										mobilephone='".$mobilephone."',
										email='".$email."',
										birthday='".$birthday."', 
										hobby='".$hobby."', 
										npwp='".$npwp."',
										last_update='".$today."',
										sentstatus=0
									where 
										buyercode='".$buyercode."'");
			}
			$error = "Database has been successfully updated ...";
		}
	}
	
	
	if ($buyercode == "")
	{
		$title = 'Mr.';
		$buyercode = '';
		$personname = '';
		$street = '';
		$city = '';
		$postal = '';
		$phone = '';
		$mobilephone = '';
		$email = '';
		$birthday = '';
		$npwp = '';
	}
	else
	{
		$res = read_write("select buyercode,personname,title,street,region, city,postal,phone,mobilephone,email,birthday,hobby, npwp 
							from tb_buyer where buyercode = '".$buyercode."'");
		$row = mysql_fetch_array($res);
		$title 		= stripslashes($row['title']);
		
		$buyercode  = stripslashes($row['buyercode']);
		$personname = stripslashes($row['personname']);			
		$street 	= stripslashes($row['street']);
		$region 	= stripslashes($row['region']);
		$city 		= stripslashes($row['city']);
		$postal 	= stripslashes($row['postal']);
		$phone 		= stripslashes($row['phone']);
		$mobilephone = stripslashes($row['mobilephone']);
		$email 		= stripslashes($row['email']);
		$birthday 	= stripslashes($row['birthday']);
		if ($birthday=='0000-00-00') { $birthday=''; }
		$hobby 		= stripslashes($row['hobby']);
		$npwp 		= stripslashes($row['npwp'] );
	}
	
	
	
	if($page <> 1) {
		
		$deliverycode 	= $_SESSION['deliverycode'];
		$buyercode 		= $_SESSION['buyercode'];
		$cid  			= $_SESSION['cid'];
	}
?>
<html>
	<head>
			<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<title>Add/Edit Personal Buyer</title>
		<script language="javascript">
			function validate()
			{
				if (document.forms[0].personname.value == "")
				{
					alert("Please fill up the Name");
					return false;
				} else {
					return true;
				}
			}
			
			function delcorporate(deliverycode, cid) {
				if (confirm("Deleting the buyer will also delete invoices.\n Are you sure?")) {
					if (confirm("Are you really sure?")) {
						location.href='personal.php?deliverycode='+deliverycode+'&cid='+cid+'&delete=1';

					}
				}
			}
			
			function delbuyer(bcode) {
				if (confirm("Deleting the buyer will also delete invoices.\n Are you sure?")) {
					if (confirm("Are you really sure?")) {
						location.href='personal.php?bid='+bcode+'&delete=1';

					}
				}
			}
			
			
		</script>
		<script language="javascript" src="cal2.js">
				/*
				Xin's Popup calendar script-  Xin Yang (http://www.yxscripts.com/)
				Script featured on/available at http://www.dynamicdrive.com/
				This notice must stay intact for use
				*/
		</script>
		<script language="javascript" src="cal_conf2.js"></script>
	</head>
	<body>
		<?php include('../menu.php'); ?><br>
		<center>
		<font class="big"><b>Personal Information</b></font>
		</center>
		<form onSubmit="return validate()" name=frmSales action="personal.php?update=1&bid=<?php echo $buyercode; ?>&cid=<?php echo $cid;?>&deliverycode=<?php echo $deliverycode;?>" method="POST" 	>
			<table border=0 cellspacing=1 cellpadding=1 width="100%">
				<tr>
					<th colspan=2 class="small"><font color=#ff0000><?php echo $error; ?></font></th>
				</tr>
				<?php if($buyercode <> "") {
						echo "<tr>
							<td align='right' class='small'>Buyer Code</td>
							<td class='small'><a href='personreport.php?bid=".$buyercode."' class=smalllink>".$buyercode."</a></td></tr>";
						} 
				?>
				<tr>
					<td align="right" class="small">Title</td>
					<td>
						<select name="title" class="forms">
						<option value="BP" <?php if ($title == 'BP') { ?>selected<?php } ?>>BP
						<option value="IBU" <?php if ($title == 'IBU') { ?>selected<?php } ?>>IBU
												</select>
					</td>
				</tr>
				
				<tr>
					<td align="right" class="small">Contact Name</td>
					<td><input type="text" name="personname" value="<?php echo get_escape_string($personname); ?>" class="tBox" size=40 maxlength=40></td>
				</tr>
				
				<tr>
					<td align="right" class="small">Person Street</td>
					<td><input type="text" name="street" value="<?php echo get_escape_string($street); ?>" class="tBox" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Region</td>
					<td><input type="text" name="region" value="<?php echo get_escape_string($region); ?>" class="tBox" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">City</td>
					<td><input type="text" name="city" value="<?php echo $city; ?>" class="tBox" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Postal Code</td>
					<td><input type="text" name="postal" value="<?php echo $postal; ?>" class="tBox" size=6></td>
				</tr>
				<tr>
					<td align="right" class="small">Phone</td>
					<td><input type="text" name="phone" value="<?php echo $phone; ?>" class="tBox" size=20></td>
				</tr>
				<tr>
					<td align="right" class="small">Mobile Phone</td>
					<td><input type="text" name="mobilephone" value="<?php echo $mobilephone; ?>" class="tBox" size=20></td>
				</tr>
				<tr>
					<td align="right" class="small">NPWP</td>
					<td><input type="text" name="npwp" value="<?php echo $npwp; ?>" class="tBox" size=20></td>
				</tr>
				<tr>
					<td align="right" class="small">E-Mail</td>
					<td><input type="text" name="email" value="<?php echo $email; ?>" class="tBox" size=50></td>
				</tr>
				<tr>
					<td align="right" class="small">Birth Day</td>
					
					<td class=small><input type="text" name="birthday" value="<?php echo $birthday; ?>" class="tBox" size=10> <a href="javascript:showCal('Calendar4')">Select Date</a></td>
				</tr>
				<tr>
					<td align="right" class="small">Hobby</td>
					<td><input type="text" name="hobby" value="<?php echo $hobby; ?>" class="tBox" size=50></td>
				</tr>
				<tr>
					<td colspan=2>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=2 align="center">
					<?php
						if ( $_SESSION['sidcust']<> "") { 
							if ($cid <> "" && $cid<>"0") {?>
						<input type="button" value="Change Customer" class="tBox" onclick='window.location.href="sales.php?sid=<?php echo $_SESSION['sidcust'];?>&p=0&bid=<?php echo $buyercode; ?>&c=<?php echo $cid;?>"'>
					<?		} else { 
								if ($buyercode <> "" && $buyercode <>"0") { ?>
						<input type="button" value="Change Customer" class="tBox" onclick='window.location.href="sales.php?sid=<?php echo $_SESSION['sidcust'];?>&p=1&bid=<?php echo $buyercode; ?>&c=0"'>
					<?			}
							}
						} 
						if ( $_SESSION['sidcust']<> "" && ($buyercode == "0" || $buyercode=="")) {
					?>
						<input type="submit" value="Save & Change Customer" class="tBox">
					<?	} else { ?>
						<input type="submit" value="Save" class="tBox">
					<?	} ?>
						<input type="reset" value="Reset" class="tBox">
	
					<?
						if(isset($HTTP_REFERER)) {
							echo "&nbsp;";
						} else { 
							//echo "<a href='javascript:history.back()'>Back I</a>";?>
						<input type="button" value="Back" class="tBox" onclick="javascript:location.href='<?php echo $_SESSION['backurl'];?>'">
					<?}?>
						<?php
						if ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator')
						{ 
							if($cid <> '') { ?>

								<input type="button" value="Delete " class="tBox" onclick="javascript:delcorporate('<?php echo $deliverycode;?>','<? echo $cid; ?>')">
							<?} else {?>
								<input type="button" value="Delete " class="tBox" onclick="javascript:delbuyer('<?php echo $buyercode; ?>')">
						<?php } 
						}?>
					</td>
				</tr>
			</table>
		</form>
		<?php 
		
		if ($buyercode <> $branch.'.0000000' && $buyercode<>"" && $_SESSION['sidcust']=='') { ?>
			<table border='0' cellspacing='1' cellpadding='1' width="100%">
				<tr>
					<th bgcolor=#e5ebf9 class='header'>Invoice List</th>
				</tr>
				<tr><td height='10'></td></tr>
			<?php 
			
			// query SQL untuk menampilkan data perhalaman sesuai offset
			$res = read_write("select 
										invoiceno 
									from 
										tb_invoice 
									where 
										buyercode='".$buyercode."' 
									and 
										(invoiceno like 'FK".$branch."%' 
									OR 
										(substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' 
									OR 
										(substring(tb_invoice.invoiceno,1,1)='". $old_branch."' 
									AND 
										substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) 
									order by 
										invoicedate DESC, invoiceno DESC
									LIMIT 
										".$start.", ".$recordperpage."
									");
				$count = mysql_num_rows($res);

				if ($count > 0)
				{
					for ($i=0;$i<$count;$i++)
					{
						$row = mysql_fetch_array($res);
						echo "<tr><td class='small' align='center'>";
						if ($cid <> "" && $cid<>"0") { 
							echo "<a href='sales.php?p=0&sid=".$row['invoiceno']."&bid=".$buyercode."&c=".$cid."'>";
						} else {
							echo "<a href='sales.php?p=1&sid=".$row['invoiceno']."&bid=".$buyercode."&c=0'>";
						}
						echo stripslashes($row['invoiceno'])."</a>";
						echo "</td></tr>";
					}
				} else {
					echo "<tr><th colspan=4 class='small'>No Sales Made</th></tr>";
				}
				
				echo "<tr><td height='10'></td></tr>";
				
			//mencari jumlah semua data dalam tabel 
			$jum = read_write("select distinct invoiceno as jumdata
									from 
										tb_invoice 
									where 
										buyercode='".$buyercode."' 
									and 
										(invoiceno like 'FK".$branch."%' 
									OR 
										(substring( tb_invoice.invoiceno, 2, 1 )='". $old_branch."' 
									OR 
										(substring(tb_invoice.invoiceno,1,1)='". $old_branch."' 
									AND 
										substring(tb_invoice.invoiceno, 3, 1)='".$old_branch."'))) 
									order by 
										invoicedate DESC, invoiceno DESC");
				
			$_SESSION['count'] = mysql_num_rows($jum);
		
			$jumData = $data['jumdata'];
		
		// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
		$max = ceil($_SESSION['count'] / $recordperpage);
		
		echo "<tr><td colspan='4' class='small' align='center'>";
		
		
		if ($_SESSION['count'] > $recordperpage) { //jika hanya 1 page maka tidak ditampilkan number
		//previous link
		if($page > 1) echo "<b><a href='".$_SERVER['PHP_SELF']."?page=".($page-1)."&bid=".$buyercode."&cid=".$cid."&deliverycode=".$deliverycode."' class='pagemenu'>Prev &nbsp;</a></b>";
		
		//tampilkan link nomor nomor halammnya menggunakan looping 
		for ($i = 1;$i <= $max; $i++) 
		{
			if((($i >= $page - 2) && ($i <= $page + 2)) || ($i == 1) || ($i == $max)) 
			{
				if (($showPage == 1) && ($i != 2)) echo " ... ";
				if (($showPage != ($max - 1)) && ($i == $max)) echo " ... ";
				if ($i == $page) { 
					echo "<b><font class='small'>[" .$i. "] </font></b>";
				} else { 
					echo "<b><a href='".$_SERVER['PHP_SELF']."?page=".$i."&bid=".$buyercode."&cid=".$cid."&deliverycode=".$deliverycode."' class='pagemenu'>[".$i."]&nbsp;</a></b>";
				}
				$showPage = $i;	
			}
		}
	
		if($page < $max) {
			echo "<b><a href='".$_SERVER['PHP_SELF']."?page=".($page+1)."&bid=".$buyercode."&cid=".$cid."&deliverycode=".$deliverycode."' class='pagemenu'>&nbsp;Next </a></b>";
		}
		echo "</td></tr>";
		}
		
		?>
				<tr>
					<td colspan=4>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=4 align="center">
					<?php 
	
					if ($cid <> "" && $cid<>"0") { ?>
						<input type="button" value="Tambah Faktur Baru" class="tBox" onclick='window.location.href="sales.php?add=1&sid=0&p=0&bid=<?php echo $buyercode; ?>&c=<?php echo $cid;?>"' <? if ($buyercode == "") { echo "disabled"; } ?>>
						&nbsp
<input type="button" value="Tambah Faktur Lama" class="tBox" onclick='window.location.href="sales.php?add=1&sid=0&p=0&bid=<?php echo $buyercode; ?>&c=<?php echo $cid;?>&add=1&l=1"' <? if ($buyercode == "") { echo "disabled"; } ?>>								
					<?php } else { ?>
<input type="button" value="Tambah Faktur Baru" class="tBox" onclick='window.location.href="sales.php?add=1&sid=0&p=1&bid=<?php echo $buyercode; ?>&c=0"' <? if ($buyercode == "") { echo "disabled"; } ?>>
						&nbsp
<input type="button" value="Tambah Faktur Lama" class="tBox" onclick='window.location.href="sales.php?add=1&sid=0&p=1&bid=<?php echo $buyercode; ?>&c=<?php echo $cid;?>&add=1&l=1"' <? if ($buyercode == "") { echo "disabled"; } ?>>								
					<?php } ?>
					</td>
				</tr>
			</table>
		<?php } ?>
	</body>
</html>
