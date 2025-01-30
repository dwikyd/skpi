<?php
session_start();

include "../../config/koneksi.php";

$requestData= $_REQUEST;
if($_GET['klp']=="peg")
	$kolom='a.nip, a.nama_lengkap, b.nama_unit, a.jabatan, a.id_pegawai';
else
	$kolom='a.nim, a.nama, b.nama_prodi, a.ta, a.id_pegawai';
$columns = explode(",", $kolom);
//----------------------------------------------------------------------------------
$filter = "";
$order="";
$limit = isset($requestData['length']) ? $requestData['length'] : 10;
$offset = isset($requestData['start']) ? $requestData['start'] : 0;

if($_GET['klp']=="peg"){
	$order = $columns[$requestData['order'][0]['column']]=="a.nama_lengkap" ? 'a.nama_lengkap, a.status_peg' :  $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];

	$where = " WHERE 1=1 AND a.nama_lengkap!='' ";
	if( !empty($requestData['search']['value']) ) {
		$where="";
		foreach ($columns as $k) {
			if($where!="") $where.=" OR "; else $where=" WHERE ";
			$kl=trim($k);
			$where.="lower($kl) like '%".trim($requestData['search']['value'])."%' ";
		}
	// $order="c.kode_kelas";
	}
	if($order!="") $order="ORDER BY $order";
	$totalData = mysqli_num_rows(mysqli_query($koneksi_simpeg, "SELECT $kolom FROM pegawai a inner join unit b on a.id_unit=b.id_unit $where"));
	$totalFiltered = $totalData;

	$query="SELECT $kolom FROM pegawai a inner join unit b on a.id_unit=b.id_unit $where $order limit $offset, $limit";
	$mhs=mysqli_query($koneksi_simpeg, $query) or die(mysqli_error($koneksi_simpeg));
}
else{
	$order = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];

	$where = " WHERE 1=1 AND a.id_pegawai!='' ";
	if( !empty($requestData['search']['value']) ) {
		$where="";
		foreach ($columns as $k) {
			if($where!="") $where.=" OR "; else $where=" WHERE ";
			$kl=trim($k);
			$where.="lower($kl) like '%".trim($requestData['search']['value'])."%' ";
		}
	// $order="c.kode_kelas";
	}

	if($order!="") $order="ORDER BY $order";
	$totalData = mysqli_num_rows(mysqli_query($koneksi_sikadu, "SELECT $kolom FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi $where"));
	$totalFiltered = $totalData;

	$query="SELECT $kolom FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi $where $order limit $offset, $limit";
	$mhs=mysqli_query($koneksi_sikadu, $query) or die(mysqli_error($koneksi_sikadu));	
}

$data = array();
$no=$requestData['start'];
while($p=mysqli_fetch_assoc($mhs)){
	$no++;
	$nestedData=array(); 
	$i=0;
	foreach ($columns as $k) {
		$i++;
		if($i<=4){
			$kl=trim(str_replace("a.", "", $k));
			$kl=trim(str_replace("b.", "", $kl));
			// $kl=trim(str_replace("c.", "", $kl));
			// $kl=trim(str_replace("d.", "", $kl));
			$nestedData[] = $p[$kl];
		}
	}
	$key=$_GET['klp']=="peg" ? $p['id_pegawai'] : $p['nim'];
	$nestedData[] = '<button class="btn btn-link text-dark btn-xs" onclick="login(\''.$_GET['klp'].'\', \''.$key.'\');"><span class="icon icon-sm"><span class="fas fa-unlock icon-dark"></span></span></button>';
	$data[] = $nestedData;
}
//----------------------------------------------------------------------------------
$json_data = array(
	"draw"            => intval( $requestData['draw'] ),  
	"recordsTotal"    => intval( $totalData ), 
	"recordsFiltered" => intval( $totalFiltered ), 
	"data"            => $data );
//----------------------------------------------------------------------------------
echo json_encode($json_data);
?>