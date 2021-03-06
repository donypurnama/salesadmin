<?php
include('../constant.php'); 
include('../database.php');

$xmlcompany = $_POST['xmlcompany'];
$xmlbuyer = $_POST['xmlbuyer'];
$xmldelivery = $_POST['xmldelivery'];
$xmlinvoice = $_POST['xmlinvoice'];
$xmlsalesman = $_POST['xmlsalesman'];
$xmlttu = $_POST['xmlttu'];
$xmldelcompany = $_POST['xmldelcompany'];
$xmldelbuyer = $_POST['xmldelbuyer'];
$xmldeldelivery = $_POST['xmldeldelivery'];
$xmldelinvoice = $_POST['xmldelinvoice'];
$xmldelttu = $_POST['xmldelttu'];
$xmldelsalesman = $_POST['xmldelsalesman'];

$last_update = $_POST['last_update'];


if (trim($xmldelcompany) <> "") {
	$xml = new SimpleXMLElement($xmldelcompany); 
	foreach ($xml->delcompany as $delcompany) {
		$companycode = $delcompany['code'];
		read_write("delete from tb_company where companycode='".$companycode."'");
	}
}

if (trim($xmldelbuyer) <> "") {
	$xml = new SimpleXMLElement($xmldelbuyer); 
	foreach ($xml->delbuyer as $delbuyer) {
		$buyercode = $delbuyer['code'];
		read_write("delete from tb_buyer where buyercode='".$buyercode."'");
	}
}

if (trim($xmldeldelivery) <> "") {
	$xml = new SimpleXMLElement($xmldeldelivery); 
	foreach ($xml->deldelivery as $deldelivery) {
		$deliverycode = $deldelivery['code'];
		read_write("delete from tb_deliveryaddr where deliverycode='".$deliverycode."'");
	}
}

if (trim($xmldelinvoice) <> "") {
	$xml = new SimpleXMLElement($xmldelinvoice); 
	foreach ($xml->delinvoice as $delinvoice) {
		$invoiceno = $delinvoice['code'];
		read_write("delete from tb_invoice where invoiceno='".$invoiceno."'");
	}
}

if (trim($xmldelttu) <> "") {
	$xml = new SimpleXMLElement($xmldelttu); 
	foreach ($xml->delttu as $delttu) {
		$ttuno = $delttu['code'];
		read_write("delete from tb_ttu where ttuno='".$ttuno."'");
	}
}

if (trim($xmldelsalesman) <> "") {
	$xml = new SimpleXMLElement($xmldelsalesman); 
	foreach ($xml->delsales as $delsales) {
		$salescode = $delsales['code'];
		read_write("delete from tb_salesman where salescode='".$salescode."'");
	}
}

if (trim($xmldeluser) <> "") {
	$xml = new SimpleXMLElement($xmldeluser); 
	foreach ($xml->deluser as $deluser) {
		$usercode = $deluser['code'];
		read_write("delete from tb_user where usercode='".$usercode."'");
	}
}

if (trim($xmlcompany) <> "") {
	$xml = new SimpleXMLElement($xmlcompany); 
	foreach ($xml->company as $company) {
		$companycode = $company['code'];
		$rs = read_write("select count(*) as cnt from tb_company where companycode='".$companycode."'");
		$rwcnt = mysql_fetch_array($rs);
		$cnt = $rwcnt['cnt'];
		if ($cnt == 0) {
			$query = "insert into tb_company (companycode, companyname, street, building, city, postal, phone1, phone2, fax, email, homepage, npwp, last_update) values ('".$companycode."','".$company->companyname."','".$company->street."','".$company->building."','".$company->city."','".$company->postal."','".$company->phone1."','".$company->phone2."','".$company->fax."','".$company->email."','".$company->homepage."','".$company->npwp."','".$last_update."')";
		} else {
			$query = "update tb_company set companyname='".$company->companyname."', street='".$company->street."', building='".$company->building."', city='".$company->city."', postal='".$company->postal."', phone1='".$company->phone1."', phone2='".$company->phone2."', fax='".$company->fax."', email='".$company->email."', homepage='".$company->homepage."', npwp='".$company->npwp."', last_update='".$last_update."' where companycode='".$companycode."'";
		}
//echo $query."<br>";
		read_write($query);
	}
}

if (trim($xmlbuyer) <> "") {
	$xml = new SimpleXMLElement($xmlbuyer); 
	foreach ($xml->buyer as $buyer) {
		$buyercode = $buyer['code'];
		$rs = read_write("select count(*) as cnt from tb_buyer where buyercode='".$buyercode."'");
		$rwcnt = mysql_fetch_array($rs);
		$cnt = $rwcnt['cnt'];
		if ($cnt == 0) {
			$query = "insert into tb_buyer (buyercode, deliverycode, personname, title, street, city, postal, email, phone, mobilephone, birthday, hobby, npwp, last_update) values ('".$buyercode."','".$buyer->deliverycode."','".$buyer->personname."','".$buyer->title."','".$buyer->street."','".$buyer->city."','".$buyer->postal."','".$buyer->email."','".$buyer->phone."','".$buyer->mobilephone."','".$buyer->birthday."','".$buyer->hobby."','".$buyer->npwp."','".$last_update."')";
		} else {
			$query = "update tb_buyer set deliverycode='".$buyer->deliverycode."', personname='".$buyer->personname."', title='".$buyer->title."', street='".$buyer->street."', city='".$buyer->city."', postal='".$buyer->postal."', email='".$buyer->email."', phone='".$buyer->phone."', mobilephone='".$buyer->mobilephone."', birthday='".$buyer->birthday."' , hobby='".$buyer->hobby."', npwp='".$buyer->npwp."', last_update='".$last_update."' where buyercode='".$buyercode."'";
		}
//echo $query."<br>";
		read_write($query);
	}
}

if (trim($xmldelivery) <> "") {
	$xml = new SimpleXMLElement($xmldelivery); 
	foreach ($xml->delivery as $delivery) {
		$deliverycode = $delivery['code'];
		$rs = read_write("select count(*) as cnt from tb_deliveryaddr where deliverycode='".$deliverycode."'");
		$rwcnt = mysql_fetch_array($rs);
		$cnt = $rwcnt['cnt'];
		if ($cnt == 0) {
			$query = "insert into tb_deliveryaddr (deliverycode, companycode, street, building, city, postal, phone, last_update) values ('".$deliverycode."','".$delivery->companycode."','".$delivery->street."','".$delivery->building."','".$delivery->city."','".$delivery->postal."','".$delivery->phone."','".$last_update."')";
		} else {
			$query = "update tb_deliveryaddr set companycode='".$delivery->companycode."', street='".$delivery->street."', building='".$delivery->building."', city='".$delivery->city."', postal='".$delivery->postal."', phone='".$delivery->phone."', last_update='".$last_update."' where deliverycode='".$deliverycode."'";
		}
//echo $query."<br>";
		read_write($query);
	}
}

if (trim($xmlinvoice) <> "") {
	$xml = new SimpleXMLElement($xmlinvoice); 
	foreach ($xml->invoice as $invoice) {
		$invoiceno = $invoice['code'];
		$rs = read_write("select count(*) as cnt from tb_invoice where invoiceno='".$invoiceno."'");
		$rwcnt = mysql_fetch_array($rs);
		$cnt = $rwcnt['cnt'];
		if ($invoice->validate=="") {
			$validate = 0;
		} else {
			$validate = 1;
		}
		if ($cnt == 0) {
			$query = "insert into tb_invoice (invoiceno, buyercode, salescode, discount, createddate, currency, kurs, custpo, usercode, transactdate, invoicedate, commdate, tax, totalsales, invtax, validate, term, days, totalreturn, cncode, itemreceiptcode, cn_date, itemreceipt_date, ppnreturn, commgroupcode, trainer, district_supervisor, taxno, taxofficer, last_update) values ('".$invoiceno."','".$invoice->buyercode."','".$invoice->salescode."',".$invoice->discount.",'".$invoice->createddate."','".$invoice->currency."',".$invoice->kurs.",'".$invoice->custpo."','".$invoice->usercode."','".$invoice->transactdate."','".$invoice->invoicedate."','".$invoice->commdate."',".$invoice->tax.",".$invoice->totalsales.",".$invoice->invtax.",".$validate.",".$invoice->term.",".$invoice->days.",".$invoice->totalreturn.",'".$invoice->cncode."','".$invoice->itemreceiptcode."','".$invoice->cn_date."','".$invoice->itemreceipt_date."',".$invoice->ppnreturn.",'".$invoice->commgroupcode."','".$invoice->trainer."','".$invoice->district_supervisor."','".$invoice->taxno."','".$invoice->taxofficer."','".$last_update."')";
		} else {
			$query = "update tb_invoice set buyercode='".$invoice->buyercode."', salescode='".$invoice->salescode."', discount=".$invoice->discount.", createddate='".$invoice->createddate."', currency='".$invoice->currency."', kurs=".$invoice->kurs.", custpo='".$invoice->custpo."', usercode='".$invoice->usercode."', transactdate='".$invoice->transactdate."', invoicedate='".$invoice->invoicedate."', commdate=".$invoice->commdate.", tax=".$invoice->tax.", totalsales=".$invoice->totalsales.", invtax=".$invoice->invtax.", validate=".$validate.", term=".$invoice->term.", days=".$invoice->days.", totalreturn=".$invoice->totalreturn.", cncode='".$invoice->cncode."', itemreceiptcode='".$invoice->itemreceiptcode."', cn_date='".$invoice->cn_date."', itemreceipt_date='".$invoice->itemreceipt_date."', ppnreturn='".$invoice->ppnreturn."', commgroupcode='".$invoice->commgroupcode."', trainer='".$invoice->trainer."', district_supervisor='".$invoice->district_supervisor."', taxno='".$invoice->taxno."', taxofficer='".$invoice->taxofficer."', last_update='".$last_update."' where invoiceno='".$invoiceno."'";
		}
//echo $query."<br>";
		read_write($query);
	}
}

if (trim($xmlsalesman) <> "") {
	$xml = new SimpleXMLElement($xmlsalesman); 
	foreach ($xml->salesman as $salesman) {
		$salescode = $salesman['code'];
		$rs = read_write("select count(*) as cnt from tb_salesman where salescode='".$salescode."'");
		$rwcnt = mysql_fetch_array($rs);
		$cnt = $rwcnt['cnt'];
		if ($cnt == 0) {
			$query = "insert into tb_salesman (salescode, salesname, alias, position, trainer, district_supervisor, active, last_update) values ('".$salescode."','".$salesman->salesname."','".$salesman->alias."',".$salesman->position.",'".$salesman->trainer."','".$salesman->district_supervisor."',".$salesman->active.",'".$last_update."')";
		} else {
			$query = "update tb_salesman set salesname='".$salesman->salesname."', alias='".$salesman->alias."', position=".$salesman->position.", district_supervisor='".$salesman->district_supervisor."', active=".$salesman->active.", last_update='".$last_update."' where salescode='".$salescode."'";
		}
//echo $query."<br>";
		read_write($query);
	}
}

if (trim($xmlttu) <> "") {
	$xml = new SimpleXMLElement($xmlttu); 
	foreach ($xml->ttu as $ttu) {
		$ttuno = $ttu['code'];
		$rs = read_write("select count(*) as cnt from tb_ttu where ttuno='".$ttuno."'");
		$rwcnt = mysql_fetch_array($rs);
		$cnt = $rwcnt['cnt'];
		if ($cnt == 0) {
			$query = "insert into tb_ttu (ttuno, invoiceno, ttudate, lpudate, depositdate, payment, ppn_payment, commission, percent_comm, percent_trainer, comm_trainer, percent_ds, comm_ds, last_update) values ('".$ttuno."','".$ttu->invoiceno."','".$ttu->ttudate."','".$ttu->lpudate."','".$ttu->depositdate."',".$ttu->payment.",".$ttu->ppn_payment.",".$ttu->commission.",".$ttu->percent_comm.",".$ttu->percent_trainer.",".$ttu->comm_trainer.",".$ttu->percent_ds.",".$ttu->comm_ds.",'".$last_update."')";
		} else {
			$query = "update tb_ttu set invoiceno='".$ttu->invoiceno."', ttudate='".$ttu->ttudate."', lpudate='".$ttu->lpudate."', depositdate='".$ttu->depositdate."', payment=".$ttu->payment.", ppn_payment=".$ttu->ppn_payment.", commission=".$ttu->commission.", percent_comm=".$ttu->percent_comm.", percent_trainer=".$ttu->percent_trainer.", comm_trainer=".$ttu->comm_trainer.", percent_ds=".$ttu->percent_ds.", comm_ds=".$ttu->comm_ds.", last_update='".$last_update."' where ttuno='".$ttuno."'";
		}
//echo $query."<br>";
		read_write($query);
	}
}

if (trim($xmluser) <> "") {
	$xml = new SimpleXMLElement($xmluser); 
	foreach ($xml->user as $user) {
		$usercode = $user['usercode'];
		$rs = read_write("select count(*) as cnt from tb_user where usercode='".$usercode."'");
		$rwcnt = mysql_fetch_array($rs);
		$cnt = $rwcnt['cnt'];
		if ($cnt == 0) {
			$query = "insert into tb_user (usercode, user_name, password, groups, homepage, realname, position, last_update) values ('".$usercode."','".$user->user_name."','".$user->password."','".$user->groups."','".$user->homepage."','".$user->realname."','".$user->position."','".$last_update."')";
		} else {
			$query = "update tb_user set user_name='".$user->user_name."', password='".$user->password."', groups='".$user->groups."', homepage='".$user->homepage."', realname='".$user->realname."', position='".$user->position."', last_update='".$last_update."' where usercode='".$usercode."'";
		}
//echo $query."<br>";
		read_write($query);
	}
}


Header("Location: http://".$localipserver."/salesadmin/backup/index.php?done=1&last_update=".$last_update);