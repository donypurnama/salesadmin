<?php
function count_vocalconsonant($strblc) {
		
		$vocal = $consonant = $number = $trailcons = 0;
        $exist_cons = false;
		
		for($i = 0; $i < strlen($strblc); $i++)
		{
			$count_chars = substr($strblc, $i, 1);
			if (ord($count_chars)==97 || ord($count_chars)==101 || ord($count_chars)==105 || ord($count_chars)==111 || ord($count_chars)==117 || ord($count_chars)==121 || ord($count_chars)==104) {
				
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
		
		if (strlen($strblc) >= 4 && $arrcount[2] >=2) {				
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
					return false;
				}
			} elseif (strlen($strblc) == 7) {
				if ($arrcount[0] >= 2 && $arrcount[1]>=3) {
					return true;
				} else {
					return false;
				}
			} else {
				if ($arrcount[0] >= 3 && $arrcount[1]>=3) {
					return true;
				} else {
					return false;
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
			echo $count_blocks[$i]."<br>";
			$arrbool[$i]=validate_oneblock($count_blocks[$i], ($i+1), (sizeof($count_blocks)));
			$bool = $bool && $arrbool[$i];
		}
		return $bool;
	}		

	$arrcount = validate_search('3s-a, pt');
	if ($arrcount) {
		echo "True";
	} else {
		echo "false";
	}
?>