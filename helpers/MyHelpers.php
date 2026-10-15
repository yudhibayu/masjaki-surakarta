<?php
namespace app\helpers;
// namespace app\components; // For Yii2 Basic (app folder won't actually exist)
class MyHelpers
{
    public static function nf($data) {
        return number_format($data,0,",",".");
    }

    public static function nf2($data) {
        return number_format($data,2,",",".");
    }

	public static function tglindo($tgl){
		$tanggal = substr($tgl,8,2);
		$bulan = MyHelpers::getBulan(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		$jam = substr($tgl,11,2);
		$menit = substr($tgl,14,2);
		$detik = substr($tgl,17,2);
		$hari = MyHelpers::getHari(date('l',strtotime($tgl)));
		//return $hari.', '.$tanggal.' '.$bulan.' '.$tahun.', Jam '.$jam.':'.$menit.':'.$detik; 
		return $hari.', '.$tanggal.' '.$bulan.' '.$tahun;		 
	}	

	public static function getBulan($bln){
		switch ($bln){
			case 1: 
				return "Januari";
				break;
			case 2:
				return "Februari";
				break;
			case 3:
				return "Maret";
				break;
			case 4:
				return "April";
				break;
			case 5:
				return "Mei";
				break;
			case 6:
				return "Juni";
				break;
			case 7:
				return "Juli";
				break;
			case 8:
				return "Agustus";
				break;
			case 9:
				return "September";
				break;
			case 10:
				return "Oktober";
				break;
			case 11:
				return "November";
				break;
			case 12:
				return "Desember";
				break;
		}
	} 
			
	public static function getHari($hri){
		switch ($hri){
			case 'Sunday': 
				return "Minggu";
				break;
			case 'Monday':
				return "Senin";
				break;
			case 'Tuesday':
				return "Selasa";
				break;
			case 'Wednesday':
				return "Rabu";
				break;
			case 'Thursday':
				return "Kamis";
				break;
			case 'Friday':
				return "Jumat";
				break;
			case 'Saturday':
				return "Sabtu";
				break;
		}
	}
}