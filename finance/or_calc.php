<?php include('../constant.php');?>
<?php include('../database.php');?>
<?php
	$pos = $_GET['pos'];
	$code = $_GET['code'];
	$tn = $_GET['tn'];
	if ($scode=='') {		
		$res = read_write("select salescode from tb_salesman where position=".$pos." and active=1");
		$cnt_all = mysql_num_rows($res);
		$i=0;
		while ($rw = mysql_fetch_array($res)) {
			$arrcode[$i] = $rw['salescode'];
			$i++;
		}
		mysql_free_result($res);
	} else {
		$arrcode[0] = $scode;
		$cnt_all = 1;
	}
	
	if ($cnt_all>0) {
		foreach ($arrcode as $salescode) {

			$query = "select tb_invoice.invoiceno from ".$_SESSION['sqlfrom']." where ".$_SESSION['sqlwhere']." and tb_invoice.trainer='".$salescode."'";
			//echo $query;
			$res = read_write($query);

			$cnt_inv = mysql_num_rows($res);
			if ($cnt_inv>0) {
				unset($arrtraininv);$i=0;
				while ($rw = mysql_fetch_array($res)) {
					$arrtraininv[$i] = $rw['invoiceno'];
					$i++;
				}
				$query = "update tb_invoice set tb_invoice.trainer='' where ";
				$i=0;
				foreach ($arrtraininv as $invno) {
					if ($i==0) {
						$querywhere = "tb_invoice.invoiceno='".$invno."'";
					} else {
						$querywhere .= " or tb_invoice.invoiceno='".$invno."'";
					}
					$i++;
				}
				//echo $query." (".$querywhere.")<br><br>";
				read_write($query." (".$querywhere.")");
			}
			mysql_free_result($res);
			
			if ($pos=="2") {
				$res = read_write("select salescode from tb_salesman where trainer='".$salescode."'");
			} elseif ($pos=="3") {
				$res = read_write("select salescode from tb_salesman where district_supervisor='".$salescode."'");
			}
			$cnt_res = mysql_num_rows($res);

			
			$i = 0;
			unset($arrsales);
			$cnt_inv = 0;
			if ($cnt_res > 0) {
				while ($rs = mysql_fetch_array($res)) {
					$arrsales[$i] = $rs['salescode'];
					$i++;
				}

				mysql_free_result($res);

				$query = "select tb_invoice.invoiceno from ".$_SESSION['sqlfrom']." where ".$_SESSION['sqlwhere'];
				$i =0;
				foreach ($arrsales as $slcode) {
					if ($i==0) {
						$querywhere = "tb_invoice.salescode='".$slcode."'";
					} else {
						$querywhere .= " or tb_invoice.salescode='".$slcode."'";
					}
					$i++;
				}
				//echo $query." and (".$querywhere.")<br><br>";
				$res = read_write($query." and (".$querywhere.")");
				$cnt_inv = mysql_num_rows($res);			
			}
			
			
			
			
			if ($cnt_inv > 0) {			
				unset($arrinv);
				$i=0;
				while ($rwinv = mysql_fetch_array($res)) {
					$arrinv[$i] = $rwinv['invoiceno'];
					$i++;
				}

				if ($pos=="2") {
					$query = "select tb_invoice.invoiceno from ".$_SESSION['sqlfrom']." where ".$_SESSION['sqlwhere']." and tb_invoice.trainer='".$salescode."'";
				} elseif ($pos=="3") {
					$query = "select tb_invoice.invoiceno from ".$_SESSION['sqlfrom']." where ".$_SESSION['sqlwhere']." and tb_invoice.district_supervisor='".$salescode."'";
				}

				$res = read_write($query);
				$cnt_prev_inv = mysql_num_rows($res);
				if ($cnt_prev_inv > 0) {
					unset($arr_prev_inv);
					$i=0;
					while ($rwinv = mysql_fetch_array($res)) {
						if (!array_search($rwinv['invoiceno'], $arrinv)) {
							$arr_prev_inv[$i] = $rwinv['invoiceno'];
							$i++;
						}
					}
				}

				if ($pos=="2") {
					$query = "update tb_invoice set tb_invoice.trainer='' where ";
				} elseif ($pos=="3") {
					$query = "update tb_invoice set tb_invoice.district_supervisor='' where ";
				}
				$i =0;
				foreach ($arrinv as $invno) {
					if ($i==0) {
						$querywhere = "tb_invoice.invoiceno='".$invno."'";
					} else {
						$querywhere .= " or tb_invoice.invoiceno='".$invno."'";
					}
					$i++;
				}
			
				if ($cnt_prev_inv>0) {
					foreach ($arr_prev_inv as $invno) {
						if ($i==0) {
							$querywhere = "tb_invoice.invoiceno='".$invno."'";
						} else {
							$querywhere .= " or tb_invoice.invoiceno='".$invno."'";
						}
						$i++;
					}
				}
				//echo $query." (".$querywhere.")<br><br>";
				$result = read_write($query." (".$querywhere.")");
				
				if ($pos=="2") {
					$query = "update tb_ttu set tb_ttu.comm_trainer=0, tb_ttu.percent_trainer=0 where ";
				} elseif ($pos=="3") {
					$query = "update tb_ttu set tb_ttu.comm_ds=0, tb_ttu.percent_ds=0 where ";
				}

				$i =0;
				foreach ($arrinv as $invno) {
					if ($i==0) {
						$querywhere = "invoiceno='".$invno."'";
					} else {
						$querywhere .= " or invoiceno='".$invno."'";
					}
					$i++;
				}
				
				//echo $query." (".$querywhere.")<br><br>";
				read_write($query." (".$querywhere.")");
				if ($pos=="2") {
					$query = "update tb_invoice set tb_invoice.trainer='".$salescode."' where ";
				} elseif ($pos=="3") {
					$query = "update tb_invoice set tb_invoice.district_supervisor='".$salescode."' where ";
				}

				$i =0;
				foreach ($arrinv as $invno) {
					if ($i==0) {
						$querywhere = "tb_invoice.invoiceno='".$invno."'";
					} else {
						$querywhere .= " or tb_invoice.invoiceno='".$invno."'";
					}
					$i++;
				}
				//echo $query." (".$querywhere.")<br><br>";
				read_write($query." (".$querywhere.")");
				if ($pos=="2") {
					$query = "update tb_ttu set tb_ttu.comm_trainer=".($percent_trainer/100)."*tb_ttu.payment, tb_ttu.percent_trainer=".$percent_trainer." where ";
				} elseif ($pos=="3") {
					$query = "update tb_ttu set tb_ttu.comm_ds=".($percent_ds/100)."*tb_ttu.payment, tb_ttu.percent_ds=".$percent_ds." where ";
				}
				$i =0;
				foreach ($arrinv as $invno) {
					if ($i==0) {
						$querywhere = "invoiceno='".$invno."'";
					} else {
						$querywhere .= " or invoiceno='".$invno."'";
					}
					$i++;
				}
				
				//echo $query." (".$querywhere.")<br><br>";
				$result = read_write($query." (".$querywhere.")");
				

			}
			mysql_free_result($res);
			
		}
	}
	if($tn and $pos == 3){
	header("Location: commission.php?search=1&ver=2&ds=$code&pos=".$pos);
	}elseif($tn and $pos == 2){
	header("Location: commission.php?search=1&ver=2&t=$code&pos=".$pos);
	}else{
		header("Location: commission.php?search=1&ver=2&pos=".$pos);
		}
?>