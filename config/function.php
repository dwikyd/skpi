<?php
//session_start();


function login_validate() {
	$timeout = 1800;
	$_SESSION["expires_by"] = time() + $timeout;
}

function login_check() {
	$exp_time = $_SESSION["expires_by"];
	if (time() < $exp_time) {
		login_validate();
		return true;
	} 
	else {
		unset($_SESSION["expires_by"]);
		return false;
	}
}

function rupiah($uang,$desimal){
	$muang=$uang;
	if($muang<0) $uang=$uang*(-1);
	$uang = number_format($uang,$desimal,",",".");
	if($muang<0) $uang="(".$uang.")";
	return $uang; 
}

function akademik($smt){
	$msmt=substr($smt, 4, 1);
	$mta=substr($smt, 0, 4)+1;
	if($msmt==1) $msmt="Gasal"; else $msmt="Genap";
	$smt=substr($smt, 0, 4)."/".$mta." ".$msmt;
	return $smt;
}

function getSmt($smt){
	if($smt==1) $smt="Gasal"; else $smt="Genap";
	return $smt;
} 

function Terbilang($x)
{
	$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	if ($x < 12)
		return " " . $abil[$x];
	elseif ($x < 20)
		return Terbilang($x - 10) . "belas";
	elseif ($x < 100)
		return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
	elseif ($x < 200)
		return " seratus" . Terbilang($x - 100);
	elseif ($x < 1000)
		return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
	elseif ($x < 2000)
		return " seribu" . Terbilang($x - 1000);
	elseif ($x < 1000000)
		return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
	elseif ($x < 1000000000)
		return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

function Romawi($n){
	$hasil = "";
	$iromawi = array("","I","II","III","IV","V","VI","VII","VIII","IX","X",20=>"XX",30=>"XXX",40=>"XL",50=>"L",
		60=>"LX",70=>"LXX",80=>"LXXX",90=>"XC",100=>"C",200=>"CC",300=>"CCC",400=>"CD",500=>"D",600=>"DC",700=>"DCC",
		800=>"DCCC",900=>"CM",1000=>"M",2000=>"MM",3000=>"MMM");
	if(array_key_exists($n,$iromawi)){
		$hasil = $iromawi[$n];
	}elseif($n >= 11 && $n <= 99){
		$i = $n % 10;
		$hasil = $iromawi[$n-$i] . Romawi($n % 10);
	}elseif($n >= 101 && $n <= 999){
		$i = $n % 100;
		$hasil = $iromawi[$n-$i] . Romawi($n % 100);
	}else{
		$i = $n % 1000;
		$hasil = $iromawi[$n-$i] . Romawi($n % 1000);
	}
	return $hasil;
}

function masa_kerja($tgl){
	$biday = new DateTime($tgl);
	$today = new DateTime();
	
	$diff = $today->diff($biday);
	
	// Display
	// echo "Tanggal Lahir: ". date('d M Y', strtotime($birthday)) .'<br />';
	$tanggal = $diff->y ." Tahun ". $diff->m ." Bulan ". $diff->d ." Hari";

	return $tanggal;
}

function MakeUrls($str)
{
	$find=array('`((?:https?|ftp)://\S+[[:alnum:]]/?)`si','`((?<!//)(www\.\S+[[:alnum:]]/?))`si');
	$replace=array('<a href="$1" target="_blank" title="Buka Link" style="color:blue;">$1</a>', '<a href="http://$1" target="_blank">$1</a>');
	return preg_replace($find, $replace, $str);
}
?>