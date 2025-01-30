<?php
session_start();

include "../../config/koneksi.php";
include "../../config/fungsi_indotgl.php";

$requestData= $_REQUEST;
$kolom='a.tgl_update, a.nim, a.in_prestasi, a.sts_translate, a.id_prestasi, a.id_translate';
$columns = explode(",", $kolom);
//----------------------------------------------------------------------------------
$filter = "";
$order="";
$limit = isset($requestData['length']) ? $requestData['length'] : 10;
$offset = isset($requestData['start']) ? $requestData['start'] : 0;

$order = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];

$where = " WHERE 1=1 ";
if( !empty($requestData['search']['value']) ) {
	$where="";
	foreach ($columns as $k) {
		if($where!="") $where.=" OR "; else $where=" WHERE (";
		$kl=trim($k);
		$where.="lower($kl) like '%".trim($requestData['search']['value'])."%' ";
	}
	$where .= ")";
	// $order="c.kode_kelas";
}
$where .= " AND sts_skpi='Y'";
if(!empty($_GET['status'])) $where .= " AND sts_translate='$_GET[status]'";
if($order!="") $order="ORDER BY $order";
$totalData = mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT $kolom FROM prestasi a $where"));
$totalFiltered = $totalData;

$query="SELECT $kolom FROM prestasi a $where $order limit $offset, $limit";
$mhs=mysqli_query($koneksi_skpi, $query) or die(mysqli_error($koneksi_sikadu));	

$data = array();
$no=$requestData['start'];
while($p=mysqli_fetch_assoc($mhs)){
	$no++;
	$aksi=$p['id_translate']==$_SESSION['id_pegawai'] || empty($p['id_translate']) ? "<a href='translate-$p[id_prestasi].html' target='_blank' class='btn btn-info btn-xs'><i class='fas fa-edit'></i></a>" : "";
	if(!empty($p['id_translate'])){
		$peg=mysqli_fetch_assoc(mysqli_query($koneksi_simpeg, "SELECT nama_lengkap FROM pegawai WHERE id_pegawai='$p[id_translate]'"));
		$p['id_translate']=$peg['nama_lengkap'];
	}
	$nestedData=array(); 
	$nestedData[]=$no;
	$nestedData[] = indonesia($p['tgl_update']);
	$nestedData[] = $p['nim'];
	$nestedData[] = $p['in_prestasi'];
	$nestedData[] = $p['sts_translate']=="Y" ? "Sudah" : "Belum";
	$nestedData[] = $p['id_translate'];
	$nestedData[] = $aksi;
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