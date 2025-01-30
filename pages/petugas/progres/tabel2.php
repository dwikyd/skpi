<?php
session_start();

include "../../../config/koneksi.php";
include "../../../config/fungsi_indotgl.php";

$prodi=$_COOKIE['prodi'];
$status=$_COOKIE['status'];
$wisuda_ke=$_COOKIE['wisuda'];
$approval=$_GET['id'];
$requestData= $_REQUEST;
$kolom='tgl_finalisasi, nim, nama, prodi, wisuda_ke, finalisasi';
$columns = explode(",", $kolom);
//----------------------------------------------------------------------------------
$filter = "";
$order="";
// $order = $columns[$requestData['order'][0]['column']]-1."   ".$requestData['order'][0]['dir'];
// $order = $columns[$requestData['order'][0]['column']]=="nim" ? 'tgl_finalisasi, nim' :  $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
$limit = isset($requestData['length']) ? $requestData['length'] : 10;
$offset = isset($requestData['start']) ? $requestData['start'] : 0;
$where = " WHERE 1=1";
$where .= $prodi=="" ? " AND kode_prodi in($_SESSION[kode_prodi]) " : " AND kode_prodi='$prodi'";
$where .= $wisuda_ke=="" ? "" : " AND wisuda_ke='$wisuda_ke'";

if( !empty($requestData['search']['value']) ) {
	$where.=" AND (";
	$i=0;
	foreach ($columns as $k) {
		// if($where!="") 
		if($i>0) $where.=" OR ";// else $where=" WHERE ";
		$kl=trim($k);
		$where.="lower($kl) like '%".trim($requestData['search']['value'])."%' ";
		$i++;
	}
	$where.=") ";
	$order="tgl_pengajuan, nim";
}
if($order!="") $order="ORDER BY $order";

$totalData = mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT $kolom FROM skpi $where"));
$totalFiltered = $totalData;

$query="SELECT $kolom FROM skpi $where $order limit $offset, $limit";
$mhs=mysqli_query($koneksi_skpi, $query) or die(mysqli_error($koneksi_skpi));
$data = array();
$no=$requestData['start'];
while($p=mysqli_fetch_assoc($mhs)){
	$no++;
	$nestedData=array(); 
	$nim=str_replace("-", "_", $p['nim']);
	if($p['wisuda_ke']==0){
		$w=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT wisuda_ke FROM mhs WHERE nim='$p[nim]'"));
		mysqli_query($koneksi_skpi, "UPDATE skpi SET wisuda_ke='$w[wisuda_ke]' WHERE nim='$p[nim]'");
		$p['wisuda_ke']=$w['wisuda_ke'];
	}

	$aksi="<div class='btn-group'><button class='btn btn-xs btn-success' onclick='window.open(\"cetak/prestasi-$nim.html\")'><i class='fas fa-print'></i></button><button class='btn btn-xs btn-info' onclick='window.location.href=\"approval-$nim.html\"'><i class='fas fa-eye'></i></button></div>";

	$nestedData[] = $no;
	$nestedData[] = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']; //indonesia($p['tgl_finalisasi']);
	$nestedData[] = "<button onclick='pilih(\"$p[nim]\");' class='btn btn-warning btn-xs'>$p[nim]</button>";
	$nestedData[] = strtoupper($p['nama']);
	$nestedData[] = $p['prodi'];
	$nestedData[] = $p['wisuda_ke'];
	$nestedData[] = $p['finalisasi'];
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