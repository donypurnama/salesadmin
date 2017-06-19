<?php
function createttuform($action, $onsubmit, $page, $branch, $arr_branch) {
?>
<form method="POST" name=frmttu action="<?php echo $action; ?>" onsubmit="<?echo $onsubmit;?>">		
		<table border="0" cellspacing="0" cellpadding="0" bgcolor=#e5ebf9 class=header width="60%" align="center">
		<tr >
		<td width="40%"><div class="header icon-48-themes">TTU Baru & Pencarian </div></td>

		<td align=right >
		<?php
		if ($branch == "") {		
			
				echo "Branch: &nbsp;<select name=slbranch class=tBox>";
				echo "<option value=''>";
				foreach ($arr_branch as $key => $value) {
					echo "<option value='".$key."'";
					if ($key == $_SESSION['slbranch']) { echo " selected"; }
					echo ">".$value;
				}
				echo "</select>";
		}
		?>
		</td></tr>
		<tr><td colspan="2">
			<table border="0" cellspacing="1" cellpadding="1" bgcolor="white" width="100%">
			<tr>
				<td class="small">Invoice No:</td>
				<td class="small" valign="middle"  width="8%">Company Name:</td>
				<td class="small" valign="middle"  width="8%">Person Name:</td>
				<td class="small" valign="middle"  width="8%"><a href="javascript:showCal('Calendar1')" class=smalllink>LPU Date From:</a></td>
				<td class="small"valign="middle"  width="8%"><a href="javascript:showCal('Calendar2')" class=smalllink>LPU Date To:</a></td>
				<td class="small" valign="middle"  width="8%">Division:</td>
				<td class="small"valign="middle"  width="8%">TTU No:</td>
				
			</tr>
			<tr>
				<td class="small">
				<input id='restrict' type=text style="width:150px;" class="tBox" name="invoiceno" value="<?php echo $_SESSION['invno']; ?>">
				</td>
				<td><input id='restrict' type=text class="tBox" name="companyname" value="<?php echo $_SESSION['companyname']; ?>"></td>
				<td><input id='restrict' type=text class="tBox" name="personname" value="<?php echo $_SESSION['personname']; ?>"></td>
				<td><input id='restrict' type=text class="tBox" name="lpudatefrom" value="<?php echo $_SESSION['lpudatefrom']; ?>" style="width:100px;"></td>
				<td><input id='restrict' type=text class="tBox" name="lpudateto" value="<?php echo $_SESSION['lpudateto']; ?>" style="width:100px;"></td>
				<td><select name=divisionid class="tBox" style="width:165px;">
					<option value=''>
					<?php
						$rsdiv = read_write("select * from tb_division where divisioninv is not null order by divisionid asc");
						while ($rwdiv = mysql_fetch_array($rsdiv)) {					
							echo "<option value='".$rwdiv['divisionid']."'";
							if ($_SESSION['divisionid']==$rwdiv['divisionid']) { echo " selected"; }
							echo ">".$rwdiv['divisionname'];
						}
						echo "<option value='F,Q' ";
						if ($_SESSION['divisionid']=="F,Q") { echo " selected"; }
						echo ">Fire (Fire & Service)";
						echo "<option value='C,E,O,N' ";
						if ($_SESSION['divisionid']=="C,E,O,N") { echo " selected"; }
						echo ">Welding (Machine & Magna)";
					?></select>
				<td><input id='restrict' type=text class="tBox" name="ttuno" value="<?php echo $_SESSION['ttuno']; ?>" style="width:100px;"></td>	
			</tr>
			
			
			<tr>
				<td colspan="6" align="center" height="20" >
			<?php
				if ($page == "index") {
					echo "<input type=submit class=tBox value=Search>&nbsp;&nbsp;";
					echo "<input type=button class=tBox value='LPU Report' onclick='golpureport()'>";
				} else {
					echo "<input type=button class=tBox value=Search onclick='gosearch()'>&nbsp;&nbsp;";
					echo "<input type=submit class=tBox value='LPU Report'>";
				}
			?>
				&nbsp;&nbsp;

				<input type="reset" class="tBox" value="Clear" onclick="location.href='index.php'">
			</tr>
			</table>
		</td></tr>	
	</table>	
<br><br>
</form>
<?php } ?>