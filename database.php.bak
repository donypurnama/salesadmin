<?php
	$connected = false;
	$databasename = 'marketing_jkt';
	
	function connect()
	{
		@ $database = mysql_pconnect('localhost','root','');
		mysql_select_db($GLOBALS['databasename']);
		
		$connected = true;
	}
	function read_write($query)
	{
		if (!$connected)
		{
			connect();
		}
		
		$result = mysql_query($query);
		
		return $result;
	}
	
	function get_order($x)
	{
		if ($x == 1)
		{
			return " asc";
		}
		else
		{
			return " desc";
		}
	}
	function parse_date($x)
	{
		list($year,$month,$date) = split('-',$x);
		$arr = str_split($date);
		if ($arr[0] == '0')
		{
			$result = $arr[1];
		}
		else
		{
			$result = $date;
		}
		switch ($month)
		{
			case '1': $result = $result.' Januari ';break;
			case '2': $result = $result.' Februari ';break;
			case '3': $result = $result.' Maret ';break;
			case '4': $result = $result.' April ';break;
			case '5': $result = $result.' Mei ';break;
			case '6': $result = $result.' Juni ';break;
			case '7': $result = $result.' Juli ';break;
			case '8': $result = $result.' Agustus ';break;
			case '9': $result = $result.' September ';break;
			case '10': $result = $result.' Oktober ';break;
			case '11': $result = $result.' November ';break;
			case '12': $result = $result.' Desember ';break;
		}
		$result = $result.$year;
		return $result;
	}

	function parse_date_eng($x)
	{
		list($year,$month,$date) = split('-',$x);
		$arr = str_split($date);
		if ($arr[0] == '0')
		{
			$result = $arr[1];
		}
		else
		{
			$result = $date;
		}
		switch ($month)
		{
			case '1': $result = $result.' January ';break;
			case '2': $result = $result.' February ';break;
			case '3': $result = $result.' March ';break;
			case '4': $result = $result.' April ';break;
			case '5': $result = $result.' May ';break;
			case '6': $result = $result.' June ';break;
			case '7': $result = $result.' July ';break;
			case '8': $result = $result.' August ';break;
			case '9': $result = $result.' September ';break;
			case '10': $result = $result.' October ';break;
			case '11': $result = $result.' November ';break;
			case '12': $result = $result.' December ';break;
		}
		$result = $result.$year;
		return $result;
	}

	function parse_money($money)
	{
		$result = '';
		if ($money == 0)
		{
			$result = 'Gratis/<i>Free</i>';
		}
		else
		{
			$arr = str_split($money);
			$len = count($arr);
			$exist = false;
			for ($i=$len;$i>0;$i--)
			{
				$num = $arr[($len-$i)];
				switch ($num)
				{
					case '1': $result = $result."Satu ";break;
					case '2': $result = $result."Dua ";break;
					case '3': $result = $result."Tiga ";break;
					case '4': $result = $result."Empat ";break;
					case '5': $result = $result."Lima ";break;
					case '6': $result = $result."Enam ";break;
					case '7': $result = $result."Tujuh ";break;
					case '8': $result = $result."Delapan ";break;
					case '9': $result = $result."Sembilan ";break;
				}
				$d = ($i - 1) % 3;
				if ($num > 0)
				{
					$exist = true;
					switch ($d)
					{
						case 2: $result = $result."Ratus ";break;
						case 1: $result = $result."Puluh ";break;
					}
				}
				if ($d == 0)
				{
					if ($exist)
					{
						$d3 = (int) (($i - 1) / 3);
						switch ($d3)
						{
							case 1: $result = $result."Ribu ";break;
							case 2: $result = $result."Juta ";break;
							case 3: $result = $result."Milyar ";break;
							case 4: $result = $result."Trilyun ";break;
						}
						$exist = false;
					}
				}
			}
			$result = str_replace("Satu Puluh","Sepuluh",$result);
			$result = str_replace("Sepuluh Satu","Sebelas",$result);
			$result = str_replace("Sepuluh Dua","Dua Belas",$result);
			$result = str_replace("Sepuluh Tiga","Tiga Belas",$result);
			$result = str_replace("Sepuluh Empat","Empat Belas",$result);
			$result = str_replace("Sepuluh Lima","Lima Belas",$result);
			$result = str_replace("Sepuluh Enam","Enam Belas",$result);
			$result = str_replace("Sepuluh Tujuh","Tujuh Belas",$result);
			$result = str_replace("Sepuluh Delapan","Delapan Belas",$result);
			$result = str_replace("Sepuluh Sembilan","Sembilan Belas",$result);
			$result = str_replace("Satu Ratus","Seratus",$result);
			$result = str_replace("Satu Ribu","Seribu",$result);
			$result = $result."Rupiah";
		}
		return $result;
	}

	function parse_sub_dollar($money)
	{
		$result = '';
		if ($money <> '0')
		{
			$arr = str_split($money);
			$len = count($arr);
			$exist = false;
			for ($i=$len;$i>0;$i--)
			{
				$num = $arr[($len-$i)];
				switch ($num)
				{
					case '1': $result = $result."One ";break;
					case '2': $result = $result."Two ";break;
					case '3': $result = $result."Three ";break;
					case '4': $result = $result."Four ";break;
					case '5': $result = $result."Five ";break;
					case '6': $result = $result."Six ";break;
					case '7': $result = $result."Seven ";break;
					case '8': $result = $result."Eight ";break;
					case '9': $result = $result."Nine ";break;
				}
				$d = ($i - 1) % 3;
				if ($num > 0)
				{
					$exist = true;
					switch ($d)
					{
						case 2: $result = $result."Hundred ";break;
						case 1: $result = $result."Ten ";break;
					}
				}
				if ($d == 0)
				{
					if ($exist)
					{
						$d3 = (int) (($i - 1) / 3);
						switch ($d3)
						{
							case 1: $result = $result."Thousand ";break;
							case 2: $result = $result."Million ";break;
							case 3: $result = $result."Billion ";break;
							case 4: $result = $result."Trillion ";break;
						}
						$exist = false;
					}
				}
			}
			$result = str_replace("One Ten","Ten",$result);
			$result = str_replace("Two Ten","Twenty",$result);
			$result = str_replace("Three Ten","Thirty",$result);
			$result = str_replace("Four Ten","Fourty",$result);
			$result = str_replace("Five Ten","Fifty",$result);
			$result = str_replace("Six Ten","Sixty",$result);
			$result = str_replace("Seven Ten","Seventy",$result);
			$result = str_replace("Eight Ten","Eighty",$result);
			$result = str_replace("Nine Ten","Ninety",$result);
			$result = str_replace("Ten One","Eleven",$result);
			$result = str_replace("Ten Two","Twelve",$result);
			$result = str_replace("Ten Three","Thirteen",$result);
			$result = str_replace("Ten Four","Fourteen",$result);
			$result = str_replace("Ten Five","Fifteen",$result);
			$result = str_replace("Ten Six","Sixteen",$result);
			$result = str_replace("Ten Seven","Seventeen",$result);
			$result = str_replace("Ten Eight","Eighteen",$result);
			$result = str_replace("Ten Nine","Nineteen",$result);
		}
		return $result;
	}

	function parse_dollar($money) {
		$pos = strpos($money,".");
		if (is_integer($pos)) {
			$left = substr($money,0,$pos);
			$right = substr($money,$pos+1);
			if ($right<>"00") {
				$result = parse_sub_dollar($left)." Point ".parse_sub_dollar($right);
			} else {
				$result = parse_sub_dollar($left);
			}
		} else {
			$result = parse_sub_dollar($money);
		}
		$result = $result."Dollar";
		return $result;
	}
	
	function count_vocalconsonant($strblc, $blnconstovocl=true) {
		
		$vocal = $consonant = $number = $trailcons = 0;
        $exist_cons = false;
		
		for($i = 0; $i < strlen($strblc); $i++)
		{
			$count_chars = substr($strblc, $i, 1);
			if ($blnconstovocl) {
				$boolcheckvocl = ord($count_chars)==97 || ord($count_chars)==101 || ord($count_chars)==105 || ord($count_chars)==111 || ord($count_chars)==117 || ord($count_chars)==121 || ord($count_chars)==104;					
			} else {
				$boolcheckvocl = ord($count_chars)==97 || ord($count_chars)==101 || ord($count_chars)==105 || ord($count_chars)==111 || ord($count_chars)==117;
			}
			if ($boolcheckvocl) {
				$vocal++;
			} else {
				if (ord($count_chars)>=98 && ord($count_chars)<=122) {
					$consonant++;					
					$n_after = $i;
					
					if ($exist_cons == true && ($n_after - $n_before) == 1) {	
						if ($count_chars<>'r') {
							$trailcons++;	
						}
					} 
					$n_before = $n_after;
					$exist_cons = true;					
				} elseif (ord($count_chars)>=48 && ord($count_chars)<=57) {
					$number++;
				}
			}
		}
		
		$arrresult[0] = $vocal;
		$arrresult[1] = $consonant;
		$arrresult[2] = $trailcons;
		$arrresult[3] = $number;

		return $arrresult;
	}

	function validate_oneblock($strblc, $ncol, $count_col) {
		$arrcount = count_vocalconsonant($strblc);
		
		if ($ncol > 1) {
			if ($strblc=='pt' || $strblc=='tk' || $strblc=='cv' || $strblc=='ud' || $strblc=='bp' || $strblc=='ibu' || $strblc=='mr' || $strblc=='mrs' || $strblc=='bkl' || $strblc=='tbk' || $strblc=='tb' || $strblc=='ltd') {
				return true;
			}
		}
		
		if ($arrcount[3]>4) { //check number
			return false;
		} elseif ($arrcount[3]==4 && strlen($strblc)==4) {
			if ($strblc=='4848' || $strblc=='5000') {
				return true;
			} else {
				return false;
			}
		} elseif ($arrcount[3]==3 && strlen($strblc)==3) {
			return true;

		} elseif ($arrcount[3]<3) {
			$strblc = ereg_replace('[0-9]','',$strblc);
		}
		
		if (strlen($strblc) >= 4 && $arrcount[2] >=4) {				
			return false;
		}

		if (strlen($strblc) <= 15 && strlen($strblc) >=3 ) {		
			
			if (strlen($strblc) == 3 || strlen($strblc) == 4) {		
				
				if ($arrcount[0] >= 1 && $arrcount[1]>=1) {	
					return true;					
				} else {
					return false;
				}			
			} elseif (strlen($strblc) == 5 || strlen($strblc) == 6) {
				if ($arrcount[0] >= 2 && $arrcount[1]>=2) {
					return true;					
				} else {
					if ($arrcount[1]<2) {
						$arrcount = count_vocalconsonant($strblc, false);
						if ($arrcount[0] >= 2 && $arrcount[1]>=2) {
							return true;
						} else {
							return false;
						}
					} else {
						return false;
					}
				}
			} elseif (strlen($strblc) == 7) {
				if ($arrcount[0] >= 2 && $arrcount[1]>=3) {
					return true;
				} else {
					if ($arrcount[1]<3) {
						$arrcount = count_vocalconsonant($strblc, false);
						if ($arrcount[0] >= 2 && $arrcount[1]>=3) {
							return true;
						} else {
							return false;
						}
					} else {
						return false;
					}
				}
			} else {
				
				if ($arrcount[0] >= 3 && $arrcount[1]>=3) {
					return true;
				} elseif ($arrcount[0] < 3 && $arrcount[1]>=3 && $arrcount[2]>=2)  {
					return true;
				} else {
					if ($arrcount[1]<3) {
						$arrcount = count_vocalconsonant($strblc, false);
						if ($arrcount[0] >= 3 && $arrcount[1]>=3) {
							return true;
						} else {
							return false;
						}
					} else {
						return false;
					}
				}
			}
		} else {
			if (strlen($strblc)==2 && $count_col > 1) {
				return true;
			} else {
				return false;
			}
		}
	}
	function validate_search($strsearch) {
		$strsearch = strtolower($strsearch);
		$count_blocks = explode(' ', $strsearch);
		$bool = true;
		for($i = 0; $i < sizeof($count_blocks); $i++) {
			$count_blocks[$i] = ereg_replace('[^a-z0-9]','',$count_blocks[$i]);
			$arrbool[$i] = validate_oneblock($count_blocks[$i], ($i+1), (sizeof($count_blocks)));
			$bool = $bool && $arrbool[$i];
		}
		return $bool;
	}		
	
	
	
	
	/* MEMFORMAT TAMPILAN WAKTU */
class waktu {
//selisih jam dengan server
private $diff = 12;
private $now; protected $dayName, $namaHari, $monthName, $namaBulan;
	function __construct() {
		$this->now = mktime(date("G")+$this->diff, date("i"), date("s"), date("n"), date("j"), date("Y"));
		$this->dayName = array('Sunday', 'Monday', 'Tuesday',
					'Wednesday', 'Thursday', 'Friday', 'Saturday');
		$this->namaHari = array('Minggu', 'Senin', 'Selasa',
					'Rabu', 'Kamis', 'Jumat', 'Sabtu');
		$this->monthName = array('January', 'February', 'March', 'April', 'May',
					'June', 'July', 'August', 'September', 'October', 'November' , 'December');
		$this->namaBulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei',
					'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	}
 
	private function ganti($format, $time) {
		$result = str_replace($this->dayName, $this->namaHari, date($format, $time));
		return str_replace($this->monthName, $this->namaBulan, $result);
	}
 
	public function tanggal($format, $time=0) {
		# tentukan apakah $time berisi data dari database ataukah berupa timestamp #
		if($time != 0) {
			if(strpos($time, '-') !== false) {
				/* dari database dengan format 0000-00-00 00:00:00 */
				$unixTime = mktime(substr($time, 11, 2), substr($time, 14, 2), substr($time, 17, 2), substr($time, 5, 2), substr($time, 8, 2), substr($time, 0, 4));
				return $this->ganti($format, $unixTime);
			} else {
				/* $time berupa timestamp */
				if($time < 0) { echo 'timestamp negatif'; return; }
				return $this->ganti($format, $time);
			}
		}
		/* default $time = 0, gunakan $now */
		return $this->ganti($format, $this->now);
	}
}
 
/* buat objek */
$time = new waktu();
 
/* tampilkan hari ini dengan format: "03/03/09" */
//echo $time->tanggal('d/m/y');
 
/* tampilkan hari ini dengan format: "Selasa, 3 Maret 2009" */
//echo $time->tanggal('l, j F Y');
	

function get_escape_string($str) {
	$str = stripslashes($str);
	$str = str_replace('"','&quot;',$str);
	return $str;
}
	
function tambahdata($table, $field1, $field2, $field3, $data1, $data2, $data3) {
	
	$proses = mysql_query("INSERT INTO ".$table." ($field1, $field2, $field3) VALUES ('".$data1."', '".$data2."', $data3)");
	mysql_close();
	if($proses) return true;
	else return false;	
}	


function selectdata($tabel, $fieldpenanda) {
	
	$proses = mysql_query("SELECT * from $tabel WHERE $fieldpenanda");
	mysql_close();
	if($proses) return true;
	else return false;
}	
	

function hapusdata($tabel, $fieldpenanda)
{
    
    $proses = mysql_query("DELETE FROM $tabel WHERE $fieldpenanda");
    mysql_close();
    if ($proses) return true;
    else return false;
}

	

	
?>
