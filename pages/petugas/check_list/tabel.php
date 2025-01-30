<?php
session_start();

include "../../../config/koneksi.php";

$requestData= $_REQUEST;
$kolom='nim,nama,ta,wisuda_ke,wa';
$columns = explode(",", $kolom);
//----------------------------------------------------------------------------------
$filter = "";
$order="";
$prodi    =$_GET['pd'];
$wisuda_ke=$_GET['wisuda_ke'];

// $order = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
$order = $columns[$requestData['order'][0]['column']]=="nim" ? 'wisuda_ke, ta, kode_prodi, nim' :  $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
$limit = isset($requestData['length']) ? $requestData['length'] : 10;
$offset = isset($requestData['start']) ? $requestData['start'] : 0;
$where = " WHERE 1=1 AND wisuda_ke!=0 AND ta>=2017 ";
if($wisuda_ke!="") $where.=" AND wisuda_ke='$wisuda_ke'";
$where.=$prodi=="" ? "AND kode_prodi in ($_SESSION[kode_prodi])" : " AND kode_prodi='$prodi'";
if( !empty($requestData['search']['value']) ) {
	// $where="";
	foreach ($columns as $k) {
		if($where!="") $where.=" OR "; else $where=" WHERE ";
		$kl=trim($k);
		$where.="lower($kl) like '%".trim($requestData['search']['value'])."%' ";
	}
	$order="wisuda_ke, kode_prodi, ta, nim";
}
if($order!="") $order="ORDER BY $order";

$totalData = mysqli_num_rows(mysqli_query($koneksi_sikadu, "SELECT $kolom FROM mhs $where"));
$totalFiltered = $totalData;

$query="SELECT $kolom FROM mhs $where $order limit $offset, $limit";
$mhs=mysqli_query($koneksi_sikadu, $query) or die(mysqli_error($koneksi_sikadu));
$data = array();
$no=$requestData['start'];
while($p=mysqli_fetch_assoc($mhs)){
	$no++;
	$nestedData=array(); 
	$ada=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE nim='$p[nim]'"));
	$link=$ada==0 ? "<i class='fa fa-times-circle' style='color:red'></i>" : "";
	mysqli_query($koneksi_skpi, "UPDATE skpi SET wisuda_ke='$wisuda_ke' WHERE nim='$p[nim]'");
	$hp1="";
	$wa1=preg_replace("/[^0-9]/","", $p['wa']);
	$wa1=substr($wa1, 0, 2)=="62" ? $wa1 : "62".substr($wa1, 1, strlen($wa1)); 
	$hp1=$perangkat=="Android" ? "https://api.whatsapp.com/send?phone=$wa1" : "https://web.whatsapp.com/send?phone=$wa1";
	$hp1="<a href='$hp1' target='_blank' style='color:blue;'>$p[wa]</a>";

	$aksi='';
	$nestedData[] = $p['nim']." ".$link;
	$nestedData[] = strtoupper($p['nama']);
	$nestedData[] = $p['ta'];
	$nestedData[] = $p['wisuda_ke'];
	$nestedData[] = $hp1;
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