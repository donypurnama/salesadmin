<?php
function createxmlcompany($last_update) {
	$rs = read_write("select * from tb_company where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		$xmlstr = "<companies>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<company code='".$row['companycode']."'>";
			$xmlstr .= "<companyname>".$row['companyname']."</companyname>";
			$xmlstr .= "<street>".$row['street']."</street>";
			$xmlstr .= "<building>".$row['building']."</building>";
			$xmlstr .= "<city>".$row['city']."</city>";
			$xmlstr .= "<postal>".$row['postal']."</postal>";
			$xmlstr .= "<phone1>".$row['phone1']."</phone1>";
			$xmlstr .= "<phone2>".$row['phone2']."</phone2>";
			$xmlstr .= "<fax>".$row['fax']."</fax>";
			$xmlstr .= "<email>".$row['email']."</email>";
			$xmlstr .= "<homepage>".$row['homepage']."</homepage>";
			$xmlstr .= "<npwp>".$row['npwp']."</npwp>";
			$xmlstr .= "</company>";
		}
		$xmlstr .= "</companies>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmlbuyer($last_update) {
	$rs = read_write("select * from tb_buyer where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr .= "<buyers>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<buyer code='".$row['buyercode']."'>";
			$xmlstr .= "<deliverycode>".$row['deliverycode']."</deliverycode>";
			$xmlstr .= "<personname>".$row['personname']."</personname>";
			$xmlstr .= "<title>".$row['title']."</title>";
			$xmlstr .= "<street>".$row['street']."</street>";
			$xmlstr .= "<city>".$row['city']."</city>";
			$xmlstr .= "<postal>".$row['postal']."</postal>";
			$xmlstr .= "<email>".$row['email']."</email>";
			$xmlstr .= "<phone>".$row['phone']."</phone>";
			$xmlstr .= "<mobilephone>".$row['mobilephone']."</mobilephone>";
			$xmlstr .= "<birthday>".$row['birthday']."</birthday>";
			$xmlstr .= "<hobby>".$row['hobby']."</hobby>";
			$xmlstr .= "<npwp>".$row['npwp']."</npwp>";
			$xmlstr .= "</buyer>";
		}
		$xmlstr .= "</buyers>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmldelivery($last_update) {
	$rs = read_write("select * from tb_deliveryaddr where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<deliveries>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<delivery code='".$row['deliverycode']."'>";
			$xmlstr .= "<companycode>".$row['companycode']."</companycode>";
			$xmlstr .= "<street>".$row['street']."</street>";
			$xmlstr .= "<building>".$row['building']."</building>";
			$xmlstr .= "<city>".$row['city']."</city>";
			$xmlstr .= "<postal>".$row['postal']."</postal>";
			$xmlstr .= "<phone>".$row['phone']."</phone>";
			
			$xmlstr .= "</delivery>";
		}
		$xmlstr .= "</deliveries>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmlinvoice($last_update) {
	$rs = read_write("select * from tb_invoice where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<invoices>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<invoice code='".$row['invoiceno']."'>";
			$xmlstr .= "<buyercode>".$row['buyercode']."</buyercode>";
			$xmlstr .= "<salescode>".$row['salescode']."</salescode>";
			$xmlstr .= "<discount>".$row['discount']."</discount>";
			$xmlstr .= "<createddate>".$row['createddate']."</createddate>";
			$xmlstr .= "<currency>".$row['currency']."</currency>";
			$xmlstr .= "<kurs>".$row['kurs']."</kurs>";
			$xmlstr .= "<custpo>".$row['custpo']."</custpo>";
			$xmlstr .= "<usercode>".$row['usercode']."</usercode>";
			$xmlstr .= "<transactdate>".$row['transactdate']."</transactdate>";
			$xmlstr .= "<invoicedate>".$row['invoicedate']."</invoicedate>";
			$xmlstr .= "<commdate>".$row['commdate']."</commdate>";
			$xmlstr .= "<tax>".$row['tax']."</tax>";
			$xmlstr .= "<ppn>".$row['ppn']."</ppn>";			
			$xmlstr .= "<totalsales>".$row['totalsales']."</totalsales>";
			$xmlstr .= "<ppnusd>".$row['ppnusd']."</ppnusd>";			
			$xmlstr .= "<totalsalesusd>".$row['totalsalesusd']."</totalsalesusd>";
			$xmlstr .= "<invtax>".$row['invtax']."</invtax>";
			$xmlstr .= "<validate>".$row['validate']."</validate>";
			$xmlstr .= "<term>".$row['term']."</term>";
			$xmlstr .= "<days>".$row['days']."</days>";
			$xmlstr .= "<totalreturn>".$row['totalreturn']."</totalreturn>";
			$xmlstr .= "<cncode>".$row['cncode']."</cncode>";
			$xmlstr .= "<itemreceiptcode>".$row['itemreceiptcode']."</itemreceiptcode>";
			$xmlstr .= "<cn_date>".$row['cn_date']."</cn_date>";
			$xmlstr .= "<itemreceipt_date>".$row['itemreceipt_date']."</itemreceipt_date>";
			$xmlstr .= "<ppnreturn>".$row['ppnreturn']."</ppnreturn>";
			$xmlstr .= "<commgroupcode>".$row['commgroupcode']."</commgroupcode>";
			$xmlstr .= "<trainer>".$row['trainer']."</trainer>";
			$xmlstr .= "<district_supervisor>".$row['district_supervisor']."</district_supervisor>";
			$xmlstr .= "<taxno>".$row['taxno']."</taxno>";
			$xmlstr .= "<taxofficer>".$row['taxofficer']."</taxofficer>";
			$xmlstr .= "<salesofficer>".$row['salesofficer']."</salesofficer>";

			$rsitem = read_write("select * from tb_invoiceitems where invoiceno='".$row['invoiceno']."'");
			if (mysql_num_rows($rsitem) > 0) {
				$xmlstr .= "<invitems>";
				while ($rowitem = mysql_fetch_array($rsitem)) {
					$xmlstr .= "<invitem>";
					$xmlstr .= "<productcode>".$rowitem['productcode']."</productcode>";
					$xmlstr .= "<batchno>".$rowitem['batchno']."</batchno>";
					$xmlstr .= "<qty>".$rowitem['qty']."</qty>";
					$xmlstr .= "<price>".$rowitem['price']."</price>";
					$xmlstr .= "<priceusd>".$rowitem['priceusd']."</priceusd>";
					$xmlstr .= "<qty_return>".$rowitem['qty_return']."</qty_return>";
					$xmlstr .= "<otherproductname>".$rowitem['otherproductname']."</otherproductname>";
					$xmlstr .= "<batchno>".$rowitem['batchno']."</batchno>";
					$xmlstr .= "<rank>".$rowitem['rank']."</rank>";
					$xmlstr .= "<rank_return>".$rowitem['rank_return']."</rank_return>";
					$xmlstr .= "</invitem>";
				}
				$xmlstr .= "</invitems>";
			}

			$xmlstr .= "</invoice>";
		}
		$xmlstr .= "</invoices>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmlsalesman($last_update) {
	$rs = read_write("select * from tb_salesman where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<salesmen>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<salesman code='".$row['salescode']."'>";
			$xmlstr .= "<salesname>".$row['salesname']."</salesname>";
			$xmlstr .= "<alias>".$row['alias']."</alias>";
			$xmlstr .= "<position>".$row['position']."</position>";
			$xmlstr .= "<trainer>".$row['trainer']."</trainer>";
			$xmlstr .= "<district_supervisor>".$row['district_supervisor']."</district_supervisor>";
			$xmlstr .= "<active>".$row['active']."</active>";
			$xmlstr .= "</salesman>";
		}
		$xmlstr .= "</salesmen>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmlttu($last_update) {
	$rs = read_write("select * from tb_ttu where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<ttus>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<ttu code='".$row['ttuno']."'>";
			$xmlstr .= "<invoiceno>".$row['invoiceno']."</invoiceno>";
			$xmlstr .= "<ttudate>".$row['ttudate']."</ttudate>";
			$xmlstr .= "<lpudate>".$row['lpudate']."</lpudate>";
			$xmlstr .= "<depositdate>".$row['depositdate']."</depositdate>";
			$xmlstr .= "<payment>".$row['payment']."</payment>";
			$xmlstr .= "<ppn_payment>".$row['ppn_payment']."</ppn_payment>";
			$xmlstr .= "<commission>".$row['commission']."</commission>";
			$xmlstr .= "<percent_comm>".$row['percent_comm']."</percent_comm>";
			$xmlstr .= "<percent_trainer>".$row['percent_trainer']."</percent_trainer>";
			$xmlstr .= "<comm_trainer>".$row['comm_trainer']."</comm_trainer>";
			$xmlstr .= "<percent_ds>".$row['percent_ds']."</percent_ds>";
			$xmlstr .= "<comm_ds>".$row['comm_ds']."</comm_ds>";
			$xmlstr .= "</ttu>";
		}
		$xmlstr .= "</ttus>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmluser($last_update) {
	$rs = read_write("select * from tb_user where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<users>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<user code='".$row['usercode']."'>";
			$xmlstr .= "<user_name>".$row['user_name']."</user_name>";
			$xmlstr .= "<password>".$row['password']."</password>";
			$xmlstr .= "<groups>".$row['groups']."</groups>";
			$xmlstr .= "<homepage>".$row['homepage']."</homepage>";
			$xmlstr .= "<realname>".$row['realname']."</realname>";
			$xmlstr .= "<position>".$row['position']."</position>";
			$xmlstr .= "</user>";
		}
		$xmlstr .= "</users>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmldel_company($last_update) {
	$rs = read_write("select distinct companycode from tb_delcompany where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<delcompanies>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<delcompany code='".$row['companycode']."'>";		
			$xmlstr .= "</delcompany>";
		}
		$xmlstr .= "</delcompanies>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmldel_buyer($last_update) {
	$rs = read_write("select distinct buyercode from tb_delbuyer where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<delbuyers>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<delbuyer code='".$row['buyercode']."'>";		
			$xmlstr .= "</delbuyer>";
		}
		$xmlstr .= "</delbuyers>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmldel_deliveryaddr($last_update) {
	$rs = read_write("select distinct deliverycode from tb_deldeliveryaddr where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<deldeliveries>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<deldelivery code='".$row['deliverycode']."'>";		
			$xmlstr .= "</deldelivery>";
		}
		$xmlstr .= "</deldeliveries>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmldel_invoice($last_update) {
	$rs = read_write("select distinct invoiceno from tb_delinvoice where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<delinvoices>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<delinvoice code='".$row['invoiceno']."'>";		
			$xmlstr .= "</delinvoice>";
		}
		$xmlstr .= "</delinvoices>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmldel_salesman($last_update) {
	$rs = read_write("select distinct salescode from tb_delsalesman where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<delsalesmen>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<delsales code='".$row['salescode']."'>";		
			$xmlstr .= "</delsales>";
		}
		$xmlstr .= "</delsalesmen>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmldel_ttu($last_update) {
	$rs = read_write("select distinct ttuno from tb_delttu where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<delttus>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<delttu code='".$row['ttuno']."'>";		
			$xmlstr .= "</delttu>";
		}
		$xmlstr .= "</delttus>";
		return $xmlstr;
	} else {
		return "";
	}
}

function createxmldel_user($last_update) {
	$rs = read_write("select distinct usercode from tb_deluser where last_update='".$last_update."' and (sentstatus<>1 or sentstatus is null)");
	if (mysql_num_rows($rs) > 0) {
		
		$xmlstr = "<delusers>";
		while ($row = mysql_fetch_array($rs)) {			
			$xmlstr .= "<deluser code='".$row['usercode']."'>";		
			$xmlstr .= "</deluser>";
		}
		$xmlstr .= "</delusers>";
		return $xmlstr;
	} else {
		return "";
	}
}

function getminlufromdb() {
		$i = 0;
		$rslu = read_write("select min(last_update) as minlu from tb_invoice where last_update is not null and last_update<>'0000-00-00'");
		$rowlu = mysql_fetch_array($rslu);
		$invoice_lu = $rowlu['minlu'];
		mysql_free_result($rslu);
		
		if ($invoice_lu <> "") {
			$arr_lu[$i] = $invoice_lu;
			$i++;
		}

		$rslu = read_write("select min(last_update) as minlu from tb_company where last_update is not null and last_update<>'0000-00-00'");
		$rowlu = mysql_fetch_array($rslu);
		$company_lu = $rowlu['minlu'];
		mysql_free_result($rslu);
		if ($company_lu <> "") {
			$arr_lu[$i] = $company_lu;
			$i++;
		}

		$rslu = read_write("select min(last_update) as minlu from tb_buyer where last_update is not null and last_update<>'0000-00-00'");
		$rowlu = mysql_fetch_array($rslu);
		$buyer_lu = $rowlu['minlu'];
		mysql_free_result($rslu);
		if ($buyer_lu <> "") {
			$arr_lu[$i] = $buyer_lu;
			$i++;
		}

		$rslu = read_write("select min(last_update) as minlu from tb_deliveryaddr where last_update is not null and last_update<>'0000-00-00'");
		$rowlu = mysql_fetch_array($rslu);
		$delivery_lu = $rowlu['minlu'];
		mysql_free_result($rslu);
		if ($delivery_lu <> "") {
			$arr_lu[$i] = $delivery_lu;
			$i++;
		}

		$rslu = read_write("select min(last_update) as minlu from tb_salesman where last_update is not null and last_update<>'0000-00-00'");
		$rowlu = mysql_fetch_array($rslu);
		$salesman_lu = $rowlu['minlu'];
		mysql_free_result($rslu);
		if ($salesman_lu <> "") {
			$arr_lu[$i] = $salesman_lu;
			$i++;
		}

		$rslu = read_write("select min(last_update) as minlu from tb_ttu where last_update is not null and last_update<>'0000-00-00'");
		$rowlu = mysql_fetch_array($rslu);
		$ttu_lu = $rowlu['minlu'];
		mysql_free_result($rslu);
		if ($ttu_lu <> "") {
			$arr_lu[$i] = $ttu_lu;
			$i++;
		}

		$rslu = read_write("select min(last_update) as minlu from tb_user where last_update is not null and last_update<>'0000-00-00'");
		$rowlu = mysql_fetch_array($rslu);
		$user_lu = $rowlu['minlu'];
		mysql_free_result($rslu);
		if ($user_lu <> "") {
			$arr_lu[$i] = $user_lu;
			$i++;
		}

		sort($arr_lu);
		return $arr_lu[0];
		
	}

	function getlastsent() {
		$rs = read_write("select lastsent from tb_sentdata");
		if (mysql_num_rows($rs) > 0) {
			$row = mysql_fetch_array($rs);
			$lastsent = $row['lastsent'];
			
			mysql_free_result($rs);
			if ($lastsent == "") {
				$lastsent = getminlufromdb();
			} else {
				$lastsent  = date("Y-m-d",strtotime(date("Y-m-d", strtotime($lastsent)) . " +1 day"));
			}
		} else {
			$lastsent = getminlufromdb();
		}
		return $lastsent;
	}


	function check_lu_between_ls($last_update, $lastsent) {
		$rs = read_write("select count(*) as cnt from tb_invoice where last_update>='".$lastsent."' and last_update<'".$last_update."' and (sentstatus<>1 or sentstatus is null)");

		$row = mysql_fetch_array($rs);
		if ($row['cnt'] > 0) {
			return true;
		}
		mysql_free_result($rs);

		$rs = read_write("select count(*) as cnt from tb_company where last_update>='".$lastsent."' and last_update<'".$last_update."' and (sentstatus<>1 or sentstatus is null)");
		$row = mysql_fetch_array($rs);
		if ($row['cnt'] > 0) {
			return true;
		}
		mysql_free_result($rs);

		$rs = read_write("select count(*) as cnt from tb_buyer where last_update>='".$lastsent."' and last_update<'".$last_update."' and (sentstatus<>1 or sentstatus is null)");
		$row = mysql_fetch_array($rs);
		if ($row['cnt'] > 0) {
			return true;
		}
		mysql_free_result($rs);

		$rs = read_write("select count(*) as cnt from tb_deliveryaddr where last_update>='".$lastsent."' and last_update<'".$last_update."' and (sentstatus<>1 or sentstatus is null)");
		$row = mysql_fetch_array($rs);
		if ($row['cnt'] > 0) {
			return true;
		}
		mysql_free_result($rs);

		$rs = read_write("select count(*) as cnt from tb_salesman where last_update>='".$lastsent."' and last_update<'".$last_update."' and (sentstatus<>1 or sentstatus is null)");
		$row = mysql_fetch_array($rs);
		if ($row['cnt'] > 0) {
			return true;
		}
		mysql_free_result($rs);

		$rs = read_write("select count(*) as cnt from tb_ttu where last_update>='".$lastsent."' and last_update<'".$last_update."' and (sentstatus<>1 or sentstatus is null)");
		$row = mysql_fetch_array($rs);
		if ($row['cnt'] > 0) {
			return true;
		}
		mysql_free_result($rs);

		$rs = read_write("select count(*) as cnt from tb_user where last_update>='".$lastsent."' and last_update<'".$last_update."' and (sentstatus<>1 or sentstatus is null)");
		$row = mysql_fetch_array($rs);
		if ($row['cnt'] > 0) {
			return true;
		}
		mysql_free_result($rs);

		return false;
	}
?>