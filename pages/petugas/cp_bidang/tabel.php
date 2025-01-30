<?php
session_start();

include "../../../config/koneksi.php";

$requestData= $_REQUEST;
$kolom='id_bidang,nama_bidang,eng_bidang,arb_bidang';
$columns = explode(",", $kolom);
//----------------------------------------------------------------------------------
$filter = "";
$order="";
$order = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
// $order = $columns[$requestData['order'][0]['column']]=="a.nim" ? 'a.nim, a.kode_mk' :  $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
$limit = isset($requestData['length']) ? $requestData['length'] : 10;
$offset = isset($requestData['start']) ? $requestData['start'] : 0;
$where = " WHERE 1=1 ";
if( !empty($requestData['search']['value']) ) {
	$where="";
	foreach ($columns as $k) {
		if($where!="") $where.=" OR "; else $where=" WHERE ";
		$kl=trim($k);
		$where.="lower($kl) like '%".trim($requestData['search']['value'])."%' ";
	}
	$order="c.kode_kelas";
}
if($order!="") $order="ORDER BY $order";

$totalData = mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT $kolom FROM cp_bidang $where"));
$totalFiltered = $totalData;

$query="SELECT $kolom FROM cp_bidang $where $order limit $offset, $limit";
$mhs=mysqli_query($koneksi_skpi, $query) or die(mysqli_error($koneksi_skpi));
$data = array();
$no=$requestData['start'];
while($p=mysqli_fetch_assoc($mhs)){
	$no++;
	$nestedData=array(); 
	// $nestedData[]=$no;
	$aksi='<div class="btn-group">
	<button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	<span class="icon icon-sm">
	<span class="fas fa-ellipsis-h icon-dark"></span>
	</span>
	<span class="sr-only">Toggle Dropdown</span>
	</button>
	<div class="dropdown-menu">
	<a class="dropdown-item" href="cp_bidang-edit-'.$p['id_bidang'].'.html"><span class="fas fa-edit mr-2"></span>Edit</a>
	<a class="dropdown-item text-danger" href="#" onclick="hapus(\''.$p['id_bidang'].'\');"><span class="fas fa-trash-alt mr-2"></span>Remove</a>
	</div>
	</div>';
	foreach ($columns as $k) {
		$kl=trim(str_replace("a.", "", $k));
		$kl=trim(str_replace("b.", "", $kl));
		$kl=trim(str_replace("c.", "", $kl));
		$kl=trim(str_replace("d.", "", $kl));
		$nestedData[] = $p[$kl];
	}
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