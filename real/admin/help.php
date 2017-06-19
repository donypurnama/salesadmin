<?php
include ('../constant.php');
include ('../database.php');
session_start();
if ($_SESSION['user'] == '') {
	//Header('Location: '.DOMAIN_NAME.'index.php');
	Header('Location: ../index.php');
}
?>
<html>
<head>
<title>Sales Administration Help</title>
<link rel='stylesheet' type='text/css' href='../templates/css/style.css'>
<link rel='stylesheet' type='text/css' href='../templates/css/system.css'>
<link rel='stylesheet' type='text/css' href='../templates/css/templates.css'>
 <script type="text/javascript" src="../templates/css/mootools.js"></script>
  <script type="text/javascript" src="../templates/css/switcher.js"></script>
<link href="templates/khepri/css/ie7.css" rel="stylesheet" type="text/css" />
<link href="templates/khepri/css/ie6.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="../templates/css/rounded.css" />
	<script type="text/javascript" src="../templates/css/menu.js"></script>
	<script type="text/javascript" src="../templates/css/index.js"></script>
  
</head>
<body>
<?php include ('../menu.php'); ?><br>
<p><a name="18">
<div class="header icon-48-systeminfo">Sales Administration Help </div>
</p>
<table border='0' cellpadding='5' cellspacing='1' width="70%">
	<tr class='small'>
		<td width='120'><b>Faktur</b></a></td>
		<td width='120'><b>Pembayaran</b></td>
		<td width='120'><b>Master</b></td>
		<td width='120'><b>System</b></td>
	</tr>
<tr class='small'>
	<td><a href='help.php#1'>- Faktur Baru</a></td>
	<td><a href='help.php#3'>- TTu Baru & Pencarian</a></td>
	<td><a href='help.php#6'>- Product</td>
	<td>- User Manager&nbsp;&nbsp;</a><b>(Administrator)</b></td>
</tr>
<tr class='small'>
	<td><a href='help.php#2'>- Laporan & Pencarian</a></td>
	<td><a href='help.php#4'>- Outstanding </a></td>
	<td><a href='help.php#7'>- Salesman</a></td>
	<td><a href='help.php#10'>- Change Password</a></td>
</tr>
	<tr class='small'>
	<td><a href='help.php#'> </td>
	<td><a href='help.php#5'>- Commission&nbsp;</a></td>
	<td><a href='help.php#8'>- Commission</td>
	<td><a href='help.php#11'>- Backup</a></td>
</tr>
</table><hr>
		<br><br>
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="1"></a>Faktur Baru</strong></div></td>
		</tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melakukan penambahan faktur corporate atau personal, pencarian faktur.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur.htm" target="_blank">
					<img src="../templates/images/help/faktur_1.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Masukkan kata kunci untuk melakukan pencarian kemudian klik search</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_2.htm" target="_blank">
					<img src="../templates/images/help/faktur_2.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Hasil dari pencarian, pilih <b>"Tambah Pelanggan Corporate"</b> jika costumer adalah corporate atau <b>"Tambah Pelanggan Personal"</b> jika costumer adalah personal </font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_3.htm" target="_blank">
					<img src="../templates/images/help/faktur_3.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			jika <b>"Tambah Pelanggan Corporate"</b> yang dipilih kemudian isikan formulir <b>Company Information</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_4.htm" target="_blank">
					<img src="../templates/images/help/faktur_4.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			jika <b>"Tambah Pelanggan Personal"</b> yang dipilih kemudian isikan formulir <b>Company Information</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_5d.htm" target="_blank">
					<img src="../templates/images/help/faktur_5d.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			<b>"Tambah Faktur Lama"</b> untuk memasukkan data faktur yang terdahulu </b></font>
			</td>
		</tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Isikan formulir <b>Informasi Order</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_5.htm" target="_blank">
					<img src="../templates/images/help/faktur_5.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Pilih <b>Commission Type </b>yang akan digunakan</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_5a.htm" target="_blank">
					<img src="../templates/images/help/faktur_5a.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Jika pembayaran menggunakan  <b>USD </b> pilih USD kemudian masukkan nilai kurs dan sertakan keterangan tanggal kurs yang berlaku </font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_5e.htm" target="_blank">
					<img src="../templates/images/help/faktur_5e.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		
		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			<b>Add Product</b> yang akan dibeli</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_5b.htm" target="_blank">
					<img src="../templates/images/help/faktur_5b.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			klik <b>Add</b> kemudian isikan formulir harga product dan quantity, jika harga yang dipilih USD masukkan juga harga dalam USD dan Rp,  klik <b>Save </b> kemudian <b> Finish</b> jika selesai</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/faktur_5c.htm" target="_blank">
					<img src="../templates/images/help/faktur_5c.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			<b>Save</b> kemudian klik <b>Validate</b></font>
			</td>
		</tr>
        <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table></td>
</tr>
</table>

<br><br>
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="2"></a>Laporan & Pencarian </strong></div></td>
		</tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melakukan <b>Laporan & Pencarian </b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/pencarian_1.htm" target="_blank">
					<img src="../templates/images/help/cari_1.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Lakukan pencarian sesuai dengan menu yang tersedia, kemudian klik <b>Search</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/pencarian_2.htm" target="_blank">
					<img src="../templates/images/help/cari_2.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Hasil dari pencarian berdasarkan kategori <b>product</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/pencarian_3.htm" target="_blank">
					<img src="../templates/images/help/cari_3.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Untuk menampilkan report product klik <b>Product Report</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/pencarian_4.htm" target="_blank">
					<img src="../templates/images/help/cari_4.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Untuk menampilkan salesman product klik <b>Salesman Report</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/pencarian_5.htm" target="_blank">
					<img src="../templates/images/help/cari_5.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Untuk menampilkan hasil pencarian dalam format dokumen excel klik <b>Import to Excel</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/pencarian_6.htm" target="_blank">
					<img src="../templates/images/help/cari_6.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
        <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table></td>
          </tr>
</table>
<br><br>
<!----------------- TTU Baru & Pencarian ------------------->		
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="3"></a>TTU Baru & Pencarian </strong></div></td>
		</tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melakukan pencarian dan penambahan TTU .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/ttu.htm" target="_blank">
					<img src="../templates/images/help/ttu1.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Masukkan kata kunci untuk melalukan pencarian berdasarkan kategori yang tersedia kemudian klik <b> 				search </b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/ttu2.htm" target="_blank">
					<img src="../templates/images/help/ttu2.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Untuk menambah TTU baru, ketikkan <b>No Faktur</b> pada kategori invoice kemudian klik <b>Search</b> kemudian klik <b>Add TTU</b></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/ttu3.htm" target="_blank">
					<img src="../templates/images/help/ttu3.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Isikan formulir TTU kemudian klik <b>Save</b> </font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/ttu4.htm" target="_blank">
					<img src="../templates/images/help/ttu4.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				klik <b>Invoice No</b> untuk melihat  Invoice TTU Information</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/ttu5.htm" target="_blank">
					<img src="../templates/images/help/ttu5.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/ttu6.htm" target="_blank">
					<img src="../templates/images/help/ttu6.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				klik <b>Nama Perusahaan</b> untuk melihat Customer TTU Information </font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/ttu7.htm" target="_blank">
					<img src="../templates/images/help/ttu7.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table></td>
          </tr>
</table>	

<br><br>
<!----------------- Outstanding ------------------->		
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="4"></a>Outstanding </strong></div></td>
		</tr>
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melakukan penghitungan Outstanding / Aging .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/outstanding_1.htm" target="_blank">                   
					<img src="../templates/images/help/outstand1.png" width="305" height="102" border="0">
					</a></div>
			</td>
        </tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Masukkan periode yang akan dihitung dengan klik <b>Periode</b> kemudian pilih <b>Salesman</b>, <br>klik <b>Aging</b> jika ingin mengetahui nilai Aging, atau klik <b>Outstanding</b> jika ingin mengetahui nilai Outstanding.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/outstanding_2.htm" target="_blank">                   
					<img src="../templates/images/help/outstand2.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/outstanding_3.htm" target="_blank">                   
					<img src="../templates/images/help/outstand3.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Hasil dari perhitungan <b>Aging</b>.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/outstanding_4.htm" target="_blank">                   
					<img src="../templates/images/help/outstand4.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Hasil dari perhitungan <b>Outstanding</b>.</font>
			</td>
		</tr>
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table></td>
          </tr>
</table>
<!----------------- Commission ------------------->		
<br><br>
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="5"></a>Commision</strong></div></td>
		</tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melakukan penghitungan Commission.<br><br>
			- Masukkan <b>periode date from</b>, <b>periode date to</b>, <b>Division</b> dan <b>Salesman</b><br>
			- Klik <b>Summary Report</b> untuk mengetahui Summary Report, <br>
			- Klik <b>Salesman Details</b> untuk mengetahui Salesman Details, <br>
			- Klik <b>Overriding Report</b> untuk mengetahui Overriding Report <br>
			</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/commision_1.htm" target="_blank">                   
					<img src="../templates/images/help/commision1.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			- Klik <b>Summary Report</b> untuk mengetahui Summary Report.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/commision_2.htm" target="_blank">                   
					<img src="../templates/images/help/commision2.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Hasil dari <b>Summary Report</b> untuk meng Export to Excel klik <b>Export to Excel </b> atau klik <b>Print</b> untuk mencetak  Summary Report.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/commision_5.htm" target="_blank">                   
					<img src="../templates/images/help/commision5.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
			
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			- Klik <b>Salesman Details</b> untuk mengetahui Salesman Details.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/commision_3.htm" target="_blank">                   
					<img src="../templates/images/help/commision3.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			- Klik <b>Overriding Report</b> untuk mengetahui Overriding Report .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/commision_4.htm" target="_blank">                   
					<img src="../templates/images/help/commision4.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table>
	</td>
</tr>
</table>	
<!----------------- Product ------------------->		
<br><br>
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="6"></a>Product</strong></div></td>
		</tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melihat daftar produk yang tersedia.<br><br></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/product.htm" target="_blank">                   
					<img src="../templates/images/help/product.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			- Klik <b>Nama Product</b> untuk melihat detail produk.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/product_1.htm" target="_blank">                   
					<img src="../templates/images/help/product_1.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Detail Informasi <b>Produk</b>.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/product_2.htm" target="_blank">                   
					<img src="../templates/images/help/product_2.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table>
	</td>
</tr>
</table>	
<!----------------- Salesman ------------------->		
<br><br>
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="7"></a>Salesman</strong></div></td>
		</tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melakukan <b>Add / Edit Salesman</b>.<br><br>
			</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman.html" target="_blank">                   
					<img src="../templates/images/help/salesman.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Klik <b>Add New Salesman </b> untuk meng-Insert / Tambah Salesman .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_2.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_4.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_3.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_6.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>				
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Klik <b>Overridding Salesman </b> untuk melihat Informasi Overridding Salesman .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_4.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_5.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_5.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_7.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Klik <b>Nama Salesman</b> untuk meng-Edit Detail Salesman.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_6.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_2.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_7.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_3.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
<!------------->				
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table>
	</td>
</tr>
</table>
<!----------------- Commission ------------------->		
<br><br>
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="8"></a>Commission</strong></div></td>
		</tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melihat daftar Commission dari masing masing divisi.<br><br></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/commission_master1.htm" target="_blank">                   
					<img src="../templates/images/help/commision_master.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/commission_master2.htm" target="_blank">                   
					<img src="../templates/images/help/commision_master1.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table>
	</td>
</tr>
</table>		
<br><br>
<!--------- USER MANAGER -------------->
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="9"></a>User Manager</strong></div></td>
		</tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk melakukan <b>Add / Edit User</b>.<br><br>
			</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/user.htm" target="_blank">                   
					<img src="../templates/images/help/user.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Klik <b>System > User Manager </b> untuk meng-Insert / Tambah Salesman .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_2.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_4.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_3.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_6.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>				
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Klik <b>Overridding Salesman </b> untuk melihat Informasi Overridding Salesman .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_4.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_5.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_5.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_7.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Klik <b>Nama Salesman</b> untuk meng-Edit Detail Salesman.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_6.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_2.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_7.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_3.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
<!------------->				
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table>
	</td>
</tr>
</table>	
<br><br>
<!--------- CHANGE PASSWORD -------------->
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="9"></a>Change Password</strong></div></td>
		</tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk <b>Edit Password User</b>.<br><br>
			</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/user.htm" target="_blank">                   
					<img src="../templates/images/help/user.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Klik <b>System > User Manager </b> untuk meng-Insert / Tambah Salesman .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_2.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_4.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_3.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_6.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>				
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Klik <b>Overridding Salesman </b> untuk melihat Informasi Overridding Salesman .</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_4.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_5.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_5.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_7.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Klik <b>Nama Salesman</b> untuk meng-Edit Detail Salesman.</font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_6.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_2.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/salesman_7.htm" target="_blank">                   
					<img src="../templates/images/help/salesman_3.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
<!------------->				
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table>
	</td>
</tr>
</table>	
<!----------------- BAckup ------------------->		
<br><br>
<table border="1"  cellpadding="0" cellspacing="0">
<tr> 
	<td><table width="450" height="176" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor=#e5ebf9 class=header> 
           <td height="19" colspan="2" >
			<div align="center"><strong><a name="11"></a>Backup</strong></div></td>
		</tr>		
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Menu untuk <b>Back Up</b> data.<br><br></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/backup.htm" target="_blank">                   
					<img src="../templates/images/help/backup.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		<tr> 
			<td width="17" height="48"></td>
			<td width="433">
			<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
			Untuk melakukan <b>Back Up</b> ikuti petunjuk berikut.<br>
			<b>1.</b> Klik <b>Go</b> untuk mem <b>Backup</b> data-data terbaru.<br><br>
			
			Untuk mengirimkan data ke Kantor Pusat ikuti petunjuk berikut<br>
			<b>2.</b> Cek koneksi internet anda, klik <b>Connection Test</b>.<br>
			<b>3.</b> Klik <b>Select Date</b>  Pilih tanggal dari data-data yang akan di kirimkan. <br>
			<b>4.</b> Klik <b>Send</b>.
			
			<br></font>
			</td>
		</tr>
		<tr> 
			<td height="71"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
			<td><div align="center"><br>
					<a href="help/backup2.htm" target="_blank">                   
					<img src="../templates/images/help/backup2.png" width="305" height="102" border="0">
					</a></div>
			</td>			
        </tr>	
		
      <tr> 
            <td height="19" colspan="2"><div align="center"></div></td>
        </tr>
        <tr> 
            <td height="19" colspan="2">
				<table width="333" height="19" border="0" align="left" cellpadding="0" cellspacing="0">
					<tr> 
					<td width="128" height="19" bgcolor=#e5ebf9 class=header>
						<font color="black" size="1" face="Verdana, Arial, Helvetica, sans-serif">
						<a href="help.php#18">
						Kembali ke atas</a></font></td>
                    <td width="94">
						<font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
						<img src="../templates/images/panah-atas.GIF" width="19" height="19"></font></td>
                    <td width="111">&nbsp;</td>
					</tr>
                </table></td>
                </tr>
              </table>
	</td>
</tr>
</table>			
              </table>
	</td>
</tr>
</table>		
</body>
</html>
 
  
  