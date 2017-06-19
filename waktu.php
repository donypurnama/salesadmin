<?php
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
?>