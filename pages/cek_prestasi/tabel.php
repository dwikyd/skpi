<?php
session_start();

include "../../config/koneksi.php";
include "../../config/fungsi_indotgl.php";

$requestData= $_REQUEST;
$kolom='a.tgl_update, a.nim, a.in_prestasi, a.sts_prestasi, a.id_prestasi, b.nama_prestasi, c.nama, d.singkat_prodi';
$columns = explode(",", $kolom);
//----------------------------------------------------------------------------------
$filter = "";
$order="";
$limit = isset($requestData['length']) ? $requestData['length'] : 10;
$offset = isset($requestData['start']) ? $requestData['start'] : 0;

$order = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];

$where = " WHERE 1=1 ";
if(!empty($_GET['prodi'])) $where .= " AND c.kode_prodi='$_GET[prodi]'";
else $where .= " AND c.kode_prodi in($_SESSION[kode_prodi]) ";
if(!empty($_GET['status'])) $where .= " AND sts_prestasi='$_GET[status]'";
else $where .= " AND sts_prestasi!='Draft'";

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


if($order!="") $order="ORDER BY $order";
$totalData = mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT $kolom FROM prestasi a INNER JOIN bukti_dokumen b ON a.id_bukti_dok=b.id_bukti_dok INNER JOIN skpi c ON a.nim=c.nim INNER JOIN prodi d ON c.kode_prodi=d.kode_prodi $where"));
$totalFiltered = $totalData;

$query="SELECT $kolom FROM prestasi a INNER JOIN bukti_dokumen b ON a.id_bukti_dok=b.id_bukti_dok INNER JOIN skpi c ON a.nim=c.nim INNER JOIN prodi d ON c.kode_prodi=d.kode_prodi $where $order limit $offset, $limit";
$mhs=mysqli_query($koneksi_skpi, $query) or die(mysqli_error($koneksi_sikadu));	

$data = array();
$no=$requestData['start'];
while($p=mysqli_fetch_assoc($mhs)){
	$no++;
	$nestedData=array(); 
	$nestedData[]=$no;
	$nestedData[] = indonesia($p['tgl_update']);
	$nestedData[] = $p['nim']."<br>".$p['nama']." (<b>$p[singkat_prodi]</b>)";
	$nestedData[] = "<b>$p[nama_prestasi]</b> : ".$p['in_prestasi'];
	$nestedData[] = $p['sts_prestasi'];
	$nestedData[] = "<div class='btn-group'><a href='#' data-toggle='modal' data-target='#modal-form' onclick='validasi(\"$p[id_prestasi]\");' class='btn btn-info btn-xs'><i class='fas fa-edit'></i></a><a href='#' data-toggle='modal' data-target='#modal-progress' onclick='progress(\"$p[id_prestasi]\");' class='btn btn-success btn-xs'><i class='fas fa-map-marker'></i></a></div>";
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