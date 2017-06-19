<?php
$pos = $_GET['pos'];
$ds = $_GET['ds'];
$t = $_GET['t'];
//echo $_SESSION['sqloverriding_ds']."--<br>";
//echo $_SESSION['sqlfrom']."--<br>";
//echo $_SESSION['sqlwhere'];
if ($pos == "3" and $ds== "") {
$resds = read_write($_SESSION['sqloverriding_ds']);

$header = "<br><br><table border=0 cellspacing=0 cellpadding=3 align=center width=80%>";
$header .= "<tr bgcolor=#e5ebf9 class=header><td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>No</b></td>";
$header .= "<td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Nama DS</b></td>";
$header .= "<td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Trainee Name</b></td>";
$header .= "<td align=center style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Net. Coll</b></td><td align=center style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Total</b></td><td align=center style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>%</b></td><td align=center style='border-left:solid thin;border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Overriding</b></td></tr>";

$i=1;
$commstr = '';
$total_all_coll = 0;
$total_or_comm = 0;
	

while ($rwds = mysql_fetch_array($resds)) {
	
	$reshit = read_write("select salesname, active, position from tb_salesman where salescode='".$rwds['district_supervisor']."'");
	$rwhit = mysql_fetch_array($reshit);
	$salesname = $rwhit['salesname'];
	$active = $rwhit['active'];
	$position = $rwhit['position'];
	mysql_free_result($reshit);
		if ($active=="1" && $position=="3") {
		$reshit = read_write("select sum(comm_ds) as commds, percent_ds as ds  from ".$_SESSION['sqlfrom']."  where ".$_SESSION['sqlwhere']."  and tb_invoice.district_supervisor='".$rwds['district_supervisor']."'");
		$rwhit = mysql_fetch_array($reshit);
		$ds= $rwhit['ds'];
		$commtn = $rwhit['commds'];
		mysql_free_result($reshit);


		$commstr .= "<tr class=small><td align=right>".$i."</td>";
		$commstr .= "<td><a href='commission.php?search=1&ver=2&pos=3&ds=".$rwds['district_supervisor']."'>".$salesname."</a></td>";
		//$commstr .= "<td><a href='commission.php?search=1&ver=2&pos=".$pos."&pos=3' class=smalllink title='Re-Calc'>".$salesname."</a></td>";
		$res = read_write("select salescode, salesname from tb_salesman where district_supervisor='".$rwds['district_supervisor']."' and active=1");
		$idx = 0;
	
		unset($arrsales);unset($arrsalesname);
		while ($rs = mysql_fetch_array($res)) {
			$arrsales[$idx] = $rs['salescode'];
			$arrsalesname[$idx] = $rs['salesname'];
			$idx++;
		}
		$j=1;
		
		$total_coll = 0;
		if ($idx > 0) {
			foreach ($arrsales as $slcode) {
				$resds_dl = read_write("select 	tb_invoice.salescode,						
											sum(payment) as collect_net 
										from 
											".$_SESSION['sqlfrom']." 
										where 
											".$_SESSION['sqlwhere']." 							
										and 
											tb_invoice.salescode='".$slcode."' 
										group by 
											tb_invoice.salescode");	
						
					
											
				$num_dl = mysql_num_rows($resds_dl);
				
				if ($j>1) {
					$commstr .= "<tr class=small><td colspan=2></td>";
				}
				$commstr .= "<td>".$arrsalesname[$j-1]."</td>"; 
						
				
	
				if ($num_dl ==0) {			
					$commstr .= "<td align=right>0 &nbsp;</td>";
				} else {
					$rw_dl = mysql_fetch_array($resds_dl);
					$commstr .= "<td align=right>".number_format($rw_dl['collect_net'])." &nbsp;</td>";
					$total_coll += $rw_dl['collect_net'];	
				}
				if ($j==count($arrsales)) {
					$commstr .= "<td align=right>".number_format($total_coll)." &nbsp;</td><td align=center>".$ds."</td>";
					$commstr .= "<td align=right>".number_format($commtn)." &nbsp;</td>";
				} else {
					$commstr .= "<td colspan=3></td>";
				}		
				$commstr .= "</tr>";
				$j++;
			}
		}
		$total_all_coll += $total_coll;
		$total_or_comm += $commtn;
		$i++;
	}
	
}
$commstr .= "<tr class=small><td colspan=8><hr></td></tr>";
$commstr .= "<tr class=small><td colspan=4 align=center>JUMLAH</td><td align=right>".number_format($total_all_coll)." &nbsp;</td>";
$commstr .= "<td align=right>".number_format($total_all_coll)." &nbsp;</td><td></td><td align=right>".number_format($total_or_comm)." &nbsp;</td></tr>";
$commstr .= "</table>";

}
elseif ($pos=="3" and $ds == true){
$dsname = read_write("select salesname from tb_salesman where salescode='$ds'");
$dsnm = mysql_fetch_array($dsname);
$salesname = $dsnm['salesname'];
$header = "<br><br><table border=0 cellspacing=0 cellpadding=3 align=center width=50%>";
$header .= "<tr><td colspan==3><b>DISTRICT SUPERVISOR : </b>$salesname</td></tr>";
$header .= "<tr bgcolor=#e5ebf9 class=header><td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>No</b></td>";
$header .= "<td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Trainee Name</b></td>";
$header .= "<td align=center style='border-left:solid thin;border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Invoice</b></td></tr>";
$i=1;
$commstr = '';
$total_all_coll = 0;
$total_or_comm = 0;

$res = read_write("select   distinct tb_invoice.salescode as slcd,tb_salesman.salesname as sales from tb_invoice,tb_salesman where tb_invoice.salescode=tb_salesman.salescode and tb_invoice.district_supervisor='$ds' and validate=1 order by tb_salesman.salesname");
		
while ($rs = mysql_fetch_array($res)) {
		
$commstr .= "<tr class=small><td align=right>".$i."</td>";	
		
		$commstr .= "<td>".$rs['sales']."</td>";
		$qinv = read_write("select  distinct	tb_invoice.invoiceno as invo 
										from 
											".$_SESSION['sqlfrom']." 
										where 
											".$_SESSION['sqlwhere']." 							
										and 
											tb_invoice.salescode='".$rs['slcd']."' 
										order by tb_invoice.invoiceno");		
					?>
	
					<script type="text/javascript">
						/*function open_win(url)
						{
							if(url !='')
							{
							 window.location.reload()
							//window.location.reload()
							window.open(url,'','width=580,height=550,member=no,status=no,location=yes,toolbar=yes,menubar=yes,scrollbars=yes,left=400,top=30,resizable=no,align=center')
							}
						} */						
					</script>
		
						
						<?
						$commstr .="<td><select class='tBox' style='width:185px;'  onchange=\"location.href=this.options[this.selectedIndex].value\">";						
						//$commstr .="<td><select class='tBox' style='width:185px;'  onchange=\"open_win(this.options[this.selectedIndex].value)\">";						
						//$commstr .="<td align=center><select class='tBox' style='width:170px;'  { onchange=\" popup =window.open(this.options[this.selectedIndex].value,'','width=580,height=550,member=no,status=no,location=yes,toolbar=yes,menubar=yes,scrollbars=yes,left=400,top=30,resizable=no,align=center')\">";						
						//$commstr .="<td align=right><select class='tBox' style='width:170px;'  onchange=\" popup =window.open(this.options[this.selectedIndex].value)\">";	
						//$commstr .="<td align=right><select name='id[$m]' class='tBox' style='width:185px;'  onchange='window.location.href=this.options[this.selectedIndex].value'";
											
						$commstr .="<option value=''>Invoice's TTU Information</option>";
						while($inv = mysql_fetch_array($qinv))
						{
							$commstr .="<option value='invoice.php?sid=$inv[invo]&ds=$ds'>".$inv['invo']."</option>";
							//$commstr .="<option value='invoice.php?sid=$inv[invo]&comm=1'>".$inv['invo']."</option>";
						}
					
						$commstr .="</select></td></tr>";
					
						$i++;
			
		}
	
$commstr .= "<tr class=small><td colspan=7><hr></td></tr>";		
	$commstr .= "</table>";
}
elseif ($pos == "2" and $t == "") {
$resds = read_write($_SESSION['sqloverriding_tn']);
//echo $_SESSION['sqloverriding_tn']."<br>";
$header = "<br><br><table border=0 cellspacing=0 cellpadding=3 align=center width=80%>";
$header .= "<tr bgcolor=#e5ebf9 class=header><td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>No</b></td>";
$header .= "<td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Nama Trainer</b></td>";
$header .= "<td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Trainee Name</b></td>";
$header .= "<td align=center style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Net. Coll</b></td><td align=center style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Total</b></td><td align=center style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>%</b></td><td align=center style='border-left:solid thin;border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Overriding</b></td></tr>";
$commstr = '';
$total_all_coll = 0;
$total_or_comm = 0;
$i=1;
while ($rwds = mysql_fetch_array($resds)) {
	
	$reshit = read_write("select salesname, active, position from tb_salesman where salescode='".$rwds['trainer']."'");
	$rwhit = mysql_fetch_array($reshit);
	$salesname = $rwhit['salesname'];
	$active = $rwhit['active'];
	$position = $rwhit['position'];
	mysql_free_result($reshit);
	
	if ($active=="1" && $position=="2") {
		$reshit = read_write("select sum(comm_trainer) as commtn, percent_trainer as trainer  from ".$_SESSION['sqlfrom']."  where ".$_SESSION['sqlwhere']."  and tb_invoice.trainer='".$rwds['trainer']."'");

		//echo "select sum(comm_trainer) as commtn  from ".$_SESSION['sqlfrom']."  where ".$_SESSION['sqlwhere']."  and tb_invoice.trainer='".$rwds['trainer']."'";

		$rwhit = mysql_fetch_array($reshit);
		$commtn = $rwhit['commtn'];
		$trainer= $rwhit['trainer'];
		mysql_free_result($reshit);


		$commstr .= "<tr class=small><td align=right>".$i."</td>";
		$commstr .= "<td><a href='commission.php?search=1&ver=2&pos=2&t=".$rwds['trainer']."'>".$salesname."</a></td>";
		//$commstr .= "<td><a href='or_calc.php?code=".$rwds['district_supervisor']."&pos=2' class=smalllink title='Re-Calc'>".$salesname."</a></td>";
		$res = read_write("select salescode, salesname from tb_salesman where trainer='".$rwds['trainer']."' and active=1");
		$idx = 0;
		unset($arrsales);unset($arrsalesname);
		while ($rs = mysql_fetch_array($res)) {
			$arrsales[$idx] = $rs['salescode'];
			$arrsalesname[$idx] = $rs['salesname'];
			$idx++;
		}
		$j=1;
		
		
		$total_coll = 0;
		if ($idx > 0) {
			foreach ($arrsales as $slcode) {
				$resds_dl = read_write("select 	tb_invoice.salescode,						
											sum(payment) as collect_net
									
										from 
											".$_SESSION['sqlfrom']." 
										where 
											".$_SESSION['sqlwhere']." 							
										and 
											tb_invoice.salescode='".$slcode."' 
										group by 
											tb_invoice.salescode");	
					
									
				$num_dl = mysql_num_rows($resds_dl);
				
				
				if ($j>1) {
					$commstr .= "<tr class=small><td colspan=2></td>";
				}
				$commstr .= "<td>".$arrsalesname[$j-1]."</td>";
				if ($num_dl ==0) {			
					$commstr .= "<td align=right>0 &nbsp;</td>";
				} else {
					$rw_dl = mysql_fetch_array($resds_dl);
					$commstr .= "<td align=right>".number_format($rw_dl['collect_net'])." &nbsp;</td>";
					$total_coll += $rw_dl['collect_net'];	
				}
				if ($j==count($arrsales)) {
					$commstr .= "<td align=right>".number_format($total_coll)." &nbsp;</td><td align=center>".$trainer."</td>";
					$commstr .= "<td align=right>".number_format($commtn)." &nbsp;</td>";
				} else {
					$commstr .= "<td colspan=3></td>";
				}		
				$commstr .= "</tr>";
				$j++;
			}
		}
		$total_all_coll += $total_coll;
		$total_or_comm += $commtn;
		$i++;
	}
	
}
$commstr .= "<tr class=small><td colspan=7><hr></td></tr>";
$commstr .= "<tr class=small><td colspan=3 align=center>JUMLAH</td><td align=right>".number_format($total_all_coll)." &nbsp;</td>";
$commstr .= "<td align=right>".number_format($total_all_coll)." &nbsp;</td><td></td><td align=right>".number_format($total_or_comm)." &nbsp;</td></tr>";
$commstr .= "</table>";


}
elseif ($pos=="2" and $t == true){
$tname = read_write("select salesname from tb_salesman where salescode='$t'");
$tnm = mysql_fetch_array($tname);
$salesname = $tnm['salesname'];
$header = "<br><br><table border=0 cellspacing=0 cellpadding=3 align=center width=50%>";
$header .= "<tr><td colspan==3><b>TRAINER : </b>$salesname</td></tr>";
$header .= "<tr bgcolor=#e5ebf9 class=header><td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>No</b></td>";
$header .= "<td style='border-left:solid thin;border-top:solid thin;border-bottom:solid thin'><b>Trainee Name</b></td>";
$header .= "<td align=center style='border-left:solid thin;border-top:solid thin;border-right:solid thin;border-bottom:solid thin'><b>Invoice</b></td></tr>";
$i=1;
$commstr = '';
$total_all_coll = 0;
$total_or_comm = 0;

$res = read_write("select distinct tb_invoice.salescode as slcd,tb_salesman.salesname as sales from tb_invoice,tb_salesman where tb_invoice.salescode=tb_salesman.salescode and tb_invoice.trainer='$t' and validate=1 order by tb_salesman.salesname");
echo "select distinct tb_invoice.salescode as slcd,tb_salesman.salesname as sales from tb_invoice,tb_salesman where tb_invoice.salescode=tb_salesman.salescode and tb_invoice.trainer='$t' and validate=1 order by tb_salesman.salesname";		
while ($rs = mysql_fetch_array($res)) {
		
$commstr .= "<tr class=small><td align=right>".$i."</td>";	
		
		$commstr .= "<td>".$rs['sales']."</td>";
		$qinv = read_write("select  distinct	tb_invoice.invoiceno as invo 
										from 
											".$_SESSION['sqlfrom']." 
										where 
											".$_SESSION['sqlwhere']." 							
										and 
											tb_invoice.salescode='".$rs['slcd']."' 
										order by tb_invoice.invoiceno");		
	
				?>
	
					<script type="text/javascript">
						/*function open_win(url)
						{
							if(url !='')
							{
							 window.location.reload()
							//window.location.reload()
							window.open(url,'','width=580,height=550,member=no,status=no,location=yes,toolbar=yes,menubar=yes,scrollbars=yes,left=400,top=30,resizable=no,align=center')
							}
						} */						
					</script>
		
						
						<?
						$commstr .="<td><select class='tBox' style='width:185px;'  onchange=\"location.href=this.options[this.selectedIndex].value\">";						
						//$commstr .="<td><select class='tBox' style='width:185px;'  onchange=\"open_win(this.options[this.selectedIndex].value)\">";						
						//$commstr .="<td align=center><select class='tBox' style='width:170px;'  { onchange=\" popup =window.open(this.options[this.selectedIndex].value,'','width=580,height=550,member=no,status=no,location=yes,toolbar=yes,menubar=yes,scrollbars=yes,left=400,top=30,resizable=no,align=center')\">";						
						//$commstr .="<td align=right><select class='tBox' style='width:170px;'  onchange=\" popup =window.open(this.options[this.selectedIndex].value)\">";	
						//$commstr .="<td align=right><select name='id[$m]' class='tBox' style='width:185px;'  onchange='window.location.href=this.options[this.selectedIndex].value'";
											
						$commstr .="<option value=''>Invoice's TTU Information</option>";
						while($inv = mysql_fetch_array($qinv))
						{
							$commstr .="<option value='invoice.php?sid=$inv[invo]&t=$t'>".$inv['invo']."</option>";
							//$commstr .="<option value='invoice.php?sid=$inv[invo]&comm=1'>".$inv['invo']."</option>";
						}
					
						$commstr .="</select></td></tr>";
					
						$i++;
			
		}
	
$commstr .= "<tr class=small><td colspan=7><hr></td></tr>";		
	$commstr .= "</table>";
}
if ($i>1) {
	echo $header;
	
} else {
	$header = '';
	$commstr = '';
	echo "== No records found == ";
}
$_SESSION['commhdr'] .= $header;
?>
	