<?php include('../constant.php'); ?>
<?php include('../database.php'); ?>
<?php
	if ($_SESSION['user'] == '')
	{
		//Header('Location: '.DOMAIN_NAME.'index.php');
		Header('Location: ../index.php');
	}
	$salescode = $_GET['salescode'];
	$act = $_GET['act'];
	$update = $_GET['update'];
	$page = $_GET['page'];
	
	
	
	
if ($update==1) {
		$salesname 	= str_replace("'","",$_POST['salesname']);
		$alias 		= $_POST['alias'];
		$position 	= $_POST['position'];	
		$trainer 	= $_POST['trainer'];
		if ($trainer=='') { $trainer= $branch.'.0000'; }
		$district_supervisor = $_POST['district_supervisor'];
		if ($district_supervisor=='') { $district_supervisor= $branch.'.0000'; }
		$active = $_POST['rdoactive'];
		if ($act=='1') {
		
		/*--------------------AUTO NUMBERING FOR SALESCODE-----------------------------*/
		$rstemp = read_write("SELECT REPLACE (salescode	, '".$branch.".','') AS nbr FROM `tb_salesman` ORDER BY salescode DESC LIMIT 1");
		//echo "SELECT REPLACE (salescode	, '".$branch.".','') AS nbr FROM `tb_salesman` ORDER BY salescode DESC LIMIT 1<br>";
		$rowtemp = mysql_fetch_array($rstemp);
		$codetemp = (int) $rowtemp	['nbr'];
		$codetemp++;
					if(strlen($codetemp)==3) {
						$codetemp = '0'.$codetemp;
					} elseif (strlen($codetemp)==2) {
						$codetemp = '00'.$codetemp;
					} elseif (strlen($codetemp)==1) {
						$codetemp = '000'.$codetemp;
					} 
					$salescode = $branch.".".$codetemp;
			read_write("insert into tb_salesman (
							salescode, 
							salesname, 
							alias, 
							position, 
							trainer, 
							district_supervisor, 
							active, 
							last_update, 
							sentstatus) 
						values ('".$salescode."', '".$salesname."', '".$alias."', ".$position.", '".$trainer."', '".$district_supervisor."', ".$active.", '".$today."', 0 )");

			Header("Location: salesman.php");
		} elseif ($act=='2') {
			$query = "UPDATE tb_salesman SET
							salescode   ='".$salescode."',
							salesname	='".$salesname."',
							alias		='".$alias."'";
			
							
			if ($position <> '') {
				$query = $query.",position=".$position;
			}
			$query = $query.",trainer		='".$trainer."', district_supervisor ='".$district_supervisor."', active		=".$active.",	last_update ='".$today."',		sentstatus 	= 0 WHERE 
							salescode	='".$salescode."'";
							
			read_write($query);
			$message = "Database has been successfully updated ...";
			
									
							
		} elseif ($act=='3') {
			
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
			
			
			/*********HITUNG JUMLAH**************/
			
			$res = read_write("select count(salescode) as cnt from tb_invoice where salescode='".$salescode."'");
		    $jum = mysql_fetch_row($res);
			/*----------------------------------------------------------------*/
			
			if($jum[0] == 0) {
					if ($branch <> '') {
					
					/*------------------ DELETE ALL TB_DELDELIVERYADDR ------------------*/

					$pros = hapusdata(tb_delsalesman, "last_update < '".$next."'");
					/*-------------------------------------------------------------------------*/	
					
						//INSERT NILAI SALESCODE DAN LAST UPDATE KE TABEL TB_DELSALESMAN
						read_write("INSERT INTO tb_delsalesman (salescode, last_update, sentstatus) VALUES ('".$salescode."','".$today."',0)");
						/*----------------------------------------------------------------*/
					}
					
					read_write("delete from tb_salesman where salescode='".$salescode."'");
					Header("Location: salesman.php");
			} else {
					$message = "Sorry ........ Salesman Have an Invoice";
			}
		}
}
	if ($salescode <> '') {
		$res = read_write("select * from tb_salesman where salescode='".$salescode."'");
		$row = mysql_fetch_array($res);
		$salescode 	= $row['salescode'];
		$salesname 	= $row['salesname'];
		$alias 		= $row['alias'];
		$position 	= $row['position'];
		$trainer 	= $row['trainer'];
		$district_supervisor = $row['district_supervisor'];
		$active 	= $row['active'];
	}
	
	
	
	
?>
<html>
	<head>
	<title>Add/Edit Salesman</title>
			<link rel="stylesheet" type="text/css" href="../templates/css/style.css">
		<script language = "javascript">
			function validate()
			{
				if (document.forms[0].salesname.value == "") {
					alert("Please fill up the salesname");
					return false; 
				}
				else if(document.forms[0].alias.value == "") {
					alert("Please fill up the alias");
					return false;
				} else {
					return true;
				}
			}
			
			function delsales(salescode) {
				if (confirm("Are you sure to delete this salesman?")) {
					if (confirm("Are you really sure?")) {
						location.href='editsales.php?act=3&update=1&salescode='+salescode; 
					}
				}
			}
			
			
			
		</script>
	</head>
<body>
<?php include('../menu.php');
$rssales = read_write("select * from tb_salesman where salescode > '".$branch .".0000' order by salesname asc"); 
$i = 0;
$num_sales = mysql_num_rows($rssales);
while ($rwsales = mysql_fetch_array($rssales)) {
	$arrsalesname[$i]=$rwsales['salesname'];
	$arrsalescode[$i] = $rwsales['salescode'];
	$i++;
}
?><br>
<form name="frmsalesman" action="editsales.php?update=1&salescode=<?php echo $salescode; ?>&act=<?php echo $act;?>" method="POST" onSubmit="return validate()" >
<table border="0" cellspacing="1" cellpadding="1" width="40%" align=center>
<tr><td colspan=2>
<div class="header icon-48-trash"><font class="big"><b>Sales Information</b></font></div></td></tr>
		<tr class="small">
			<td colspan=2 align="center"><font color=red><b><?php echo $message; ?></b></font></td>
		</tr>
		<?php if($salescode <> "") {
							echo "<tr>
									<td align='right' class='small'>Sales Code</td>
									<td class='small'>$salescode</td></tr>";
						} 
				?>
		
		<tr class="small">
			<td align="right" >Sales Name:</td>
			<td><input type=text name=salesname class=tBox value="<?php echo $salesname; ?>"></td>					
		</tr>
		<tr class="small">
			<td align="right">Alias:</td>
			<td><input type=text name=alias class=tBox value="<?php echo $alias; ?>"></td>
		</tr>
		<tr class="small">
			<td align="right" >Position:</td>
			<td><select name='position' class=tBox>
						<?php
				for ($i=0;$i<count($constant_salespos);$i++) {
					echo "<option value=".$i;
					if ($position == $i && $position<>'') { echo " selected"; }	
					echo ">".$constant_salespos[$i];
			}
			?>
			</td>					
		</tr>
		<tr class="small">
			<td align="right" >Trainer:</td>
			<td><select name='trainer' class=tBox>
			<option value=''>-
			<?php
				for ($i=0;$i<$num_sales;$i++) {
					echo "<option value=".$arrsalescode[$i];
					if ($trainer == $arrsalescode[$i]) { echo " selected"; }	
					echo ">".$arrsalesname[$i];
				}
			?>
			</td></tr>
		
		<tr class="small">
			<td align="right" >District Supervisor:</td>
			<td><select name='district_supervisor' class=tBox>
			<option value=''>-
			<?php
				for ($i=0;$i<$num_sales;$i++) {
					echo "<option value=".$arrsalescode[$i];
					if ($district_supervisor == $arrsalescode[$i]) { echo " selected"; }	
					echo ">".$arrsalesname[$i];
				}
			?>
			</td></tr>
		<tr class="small">
			<td align="right">Active:</td>
			<td><input type=radio value=1 name=rdoactive <?php if ($active==1 || $act=='1') { echo "checked"; }?>> Yes &nbsp;
			<input type=radio value=0 name=rdoactive <?php if ($active==0 && $act<>'1') { echo "checked"; }?>> No</td>					
		</tr>
		<tr><td height=6></td></tr>
		<tr><td colspan='2'>
		<?php 
/* -------------------------------------------------- */
		$res = read_write("select * from tb_salesman");
		$data = mysql_fetch_array($res);
		$jumsales=mysql_num_rows($res);

		$data = read_write("select * from tb_salesman where salescode = '".$salescode."'");
		$row = mysql_fetch_array($data);

		$tempsales = $row['salesname'];

		$tampil = read_write("select * from tb_salesman where salesname > '".$tempsales."' ORDER BY salesname LIMIT 1" );
		$hasil = mysql_fetch_array($tampil);

		$temp = $hasil['salescode'];

		$tampil2 = read_write("select * from tb_salesman where salesname < '".$tempsales."' ORDER BY salesname DESC LIMIT 1" );

		$resprev = mysql_fetch_array($tampil2);
		$prev = $resprev['salescode'];

/* -------------------------------------------------- */

	
?>
<table border="0" cellspacing="0" cellpadding="0" width="100%" align=center>
<tr>
	<?
	if($act <> 1) {
		if($halaman < $jumsales) {
		$previous = $prev;
			if ($prev <> '') {
				echo "<td width='5%' align='center'><a href=editsales.php?act=".$act."&salescode=".$prev.">
				<img src='../templates/images/toolbar/prev.png'></a></td>";
				echo "<td width='40%' align='left'><a href=editsales.php?act=".$act."&salescode=".$prev.">
				<font face='arial' size='1pt'>".$resprev['salesname']."</font>&nbsp;</a></td>";
			} else {
				echo "<td width='5%' align='center'><img src='../templates/images/toolbar/prev_off.png'></td><td>&nbsp</td>";
			}
		} 	
	
		if($halaman < $jumsales) {
			$next = $temp;
			if ($next <> '') {
				echo "<td width='40%' align='right'><a href=editsales.php?act=".$act."&salescode=".$next.">
				<font face='arial' size='1pt'>".$hasil['salesname']."</font></a></td>";
				echo "<td width='5%' align='center'><a href=editsales.php?act=".$act."&salescode=".$next.">
				<img src='../templates/images/toolbar/next.png'></a></td>";
			} else { 
				echo "<td width='5%' align='center'><img src='../templates/images/toolbar/next_off.png'></td><td>&nbsp;</td>";
			}
		}
	}?>
</tr>
</table>
		
		
		
		</td></tr>
		<tr><td colspan=2 align=center valign="middle">

		&nbsp;<input type=submit value='Save' class=tBox <?php //if ($_SESSION['groups'] <> 'root' && $_SESSION['groups'] <> 'administrator' && $_SESSION['groups'] <> 'personnel') { echo "disabled"; } 
		?>> 
		<?php //if ($_SESSION['groups'] == 'root' || $_SESSION['groups'] == 'administrator' || $_SESSION['groups'] == 'personnel') { ?>
		&nbsp;<input type="button" value="Delete" class="tBox" onclick="delsales('<?php echo $salescode; ?>')">
		<? //} ?>
		&nbsp;<input type=button value='Exit' class=tBox onclick='javascript:location.href="salesman.php"'>&nbsp;
		
</td></tr>
</table>
</form>
<br><br>


</body></html>
