<?php
session_start();

include "../../../config/koneksi.php";
include "../../../config/fungsi_indotgl.php";

$columns = array( 
	0 =>"no",
	1 =>"tgl_upload",
	2 =>"nim",
	3 =>"nama",
	4 =>"singkat_prodi",
	5 =>"status_review",
	6 =>"aksi",
);
$where  ="1=1 AND d.status_translate='S'";//AND d.jenis='$_GET[jenis]'
if(!empty($_GET['id_pegawai'])) {
	$j=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT jenis FROM reviewer WHERE id_pegawai='$_GET[id_pegawai]'"));
	$jenis=$j['jenis'];
	$where.=" AND (d.id_reviewer='$_GET[id_pegawai]' OR d.id_reviewer='' OR isnull(d.id_reviewer)) AND d.jenis='$jenis'";
}

// if(!empty($_GET['jenis'])) $where.=" AND d.jenis='$_GET[jenis]'";
if(!empty($_GET['kode_prodi'])) $where.=" AND b.kode_prodi='$_GET[kode_prodi]'";
if(!empty($_GET['terjemah'])) $where.=" AND d.status_review='$_GET[terjemah]'";
$querycount = mysqli_query($koneksi_skpi, "SELECT a.nim FROM prestasi a inner join skpi b on a.nim=b.nim INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi WHERE $where");
$totalData = mysqli_num_rows($querycount);

$totalFiltered = $totalData; 

// $kolom=$_POST['order']['0']['column']>0 ? $_POST['order']['0']['column']-1 : $_POST['order']['0']['column'];
$kolom =$_POST['order']['0']['column'];
$limit = $_POST['length'];
$start = $_POST['start'];
$order = $columns[$kolom];
$dir = $_POST['order']['0']['dir'];

$sqlk="SELECT count(a.nim) as jumlah FROM prestasi a INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi WHERE $where";
$querycount = mysqli_query($koneksi_skpi, $sqlk);
$datacount = mysqli_fetch_assoc($querycount);
$kosong = $datacount['jumlah'];
if(empty($_POST['search']['value'])){            
}
else {
	$search = $_POST['search']['value']; 
	$where.=" AND (a.nim LIKE '%$search%' OR b.nama LIKE '%$search%') ";
	$querycount = mysqli_query($koneksi_skpi, "SELECT count(a.nim) as jumlah FROM prestasi a INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi WHERE $where");
	$datacount = mysqli_fetch_assoc($querycount);
	$totalFiltered = $datacount['jumlah'];
}

$sql="SELECT a.*, b.nama, b.kode_prodi, c.singkat_prodi, c.jenjang, d.* FROM prestasi a inner join skpi b on a.nim=b.nim INNER JOIN prodi c ON b.kode_prodi=c.kode_prodi INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi WHERE $where order by $order $dir LIMIT $limit OFFSET $start";

$query = mysqli_query($koneksi_skpi, $sql);

$data = array();
$no = $start;
while($r=mysqli_fetch_assoc($query)){
	$no++;
	if(empty($r['id_reviewer'])) $r['id_reviewer']=$_GET['id_pegawai'];
	$nestedData['no'] = $no;
	$nestedData['tgl_upload'] = indonesia($r['tgl_upload']);
	$nestedData['nim'] = $r['nim'];
	$jenis=$r['jenis']=="I" ? "info" : "success";
	$nestedData['nama'] = 	" <span class='badge rounded px-1 py-1 bg-$jenis'>$r[jenis]</span> ".$r['nama']."<br>$r[in_prestasi]";
	$nestedData['singkat_prodi'] = $r['singkat_prodi']." - ".$r['jenjang'];
	$nestedData['status_review'] = $r['status_review']=="S" ? "Sudah" : "Belum";
	$tombol=$r['status_review']=="S" ? "success" : "warning";
	$nestedData['aksi'] = "<div class=btn-group btn-group-xs>";
	if(!empty($r['id_reviewer'])) $nestedData['aksi'] .= "<button type='button' onclick='window.open(\"terjemah_review-$r[id_prestasi]-$r[jenis]-$r[id_reviewer].html\");' title='Edit Data' class='btn btn-$tombol btn-xs btn-fill'><i class='fa fa-fw fa-edit'></i></button>";
	$nestedData['aksi'] .= "</div>";
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