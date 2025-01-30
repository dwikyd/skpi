<?php
session_start();

include "../../../config/koneksi.php";
include "../../../config/fungsi_indotgl.php";

$prodi=$_GET['prodi'];
$status=$_GET['status'];
$wisuda_ke=$_GET['wisuda'];
$approval=$_GET['id'];

$columns = array( 
	0 =>"no",
	1 =>"tgl_skpi",
	2 =>"nim",
	3 =>"nama",
	4 =>"kode_prodi",
	5 =>"sts_skpi",
	6 =>"sts_prestasi",
	7 =>"aksi",
);
$where  ="1=1 ";
$where .= $prodi=="" ? " AND kode_prodi in($_SESSION[kode_prodi]) " : " AND kode_prodi='$prodi'";
$where .= $wisuda_ke=="" ? "" : " AND wisuda_ke='$wisuda_ke'";
$where .= $status=="" ? "" : " AND sts_prestasi='$status'";

$querycount = mysqli_query($koneksi_skpi, "SELECT a.id_prestasi FROM prestasi a INNER JOIN skpi b on a.nim=b.nim WHERE $where");
$totalData = mysqli_num_rows($querycount);

$totalFiltered = $totalData; 

// $kolom=$_POST['order']['0']['column']>0 ? $_POST['order']['0']['column']-1 : $_POST['order']['0']['column'];
$kolom=$_POST['order']['0']['column'];
$limit = $_POST['length'];
$start = $_POST['start'];
$order = $columns[$kolom];
$dir = $_POST['order']['0']['dir'];

$sqlk="SELECT count(a.id_prestasi) as jumlah FROM prestasi a INNER JOIN skpi b on a.nim=b.nim WHERE $where";
$querycount = mysqli_query($koneksi_skpi, $sqlk);
$datacount = mysqli_fetch_assoc($querycount);
$kosong = $datacount['jumlah'];

if(empty($_POST['search']['value'])){            
}
else {
	$search = $_POST['search']['value']; 
	$where.=" AND (a.nim LIKE '%$search%') ";

	$querycount = mysqli_query($koneksi_skpi, "SELECT count(a.id_prestasi) as jumlah FROM prestasi a INNER JOIN skpi b on a.nim=b.nim WHERE $where");
	$datacount = mysqli_fetch_assoc($querycount);
	$totalFiltered = $datacount['jumlah'];
}

$sql="SELECT a.*, b.nama, b.kode_prodi FROM prestasi a INNER JOIN skpi b on a.nim=b.nim  WHERE $where order by $order $dir LIMIT $limit OFFSET $start";

$query = mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));

$data = array();
if(!empty($query)){
	$no = $start + 1;
	while ($r = mysqli_fetch_assoc($query)){
		$nestedData['no'] = $no;
		$p=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT nama_prodi, singkat_prodi FROM prodi WHERE kode_prodi='$r[kode_prodi]'"));
		$aksi="<div class='btn-group'>";
		// if($r['id_user']==$_SESSION['id_pegawai']){
		$aksi.="<a href='file_prestasi/$r[nim]/$r[file]' target='_blank' title='File Bukti' class='btn btn-xs btn-primary'><i class='fa fa-archive'></i></a> ";
		$aksi.="<a href='#' title='Approval Prestasi' class='btn btn-xs btn-secondary'><i class='fa fa-edit'></i></a> ";
		$aksi.="</div>";
		$nestedData['tgl_skpi'] = indonesia($r['tgl_skpi']).substr($r['tgl_skpi'], 10, 6);
		$nestedData['nim'] = $r['nim'];
		$nestedData['nama'] = $r['nama'];
		$nestedData['kode_prodi'] = $p['singkat_prodi'];
		$nestedData['sts_skpi'] = $r['sts_skpi']=="Y" ? "SKPI" : "-";
		$nestedData['sts_prestasi'] = $r['sts_prestasi'];
		$nestedData['aksi'] = $aksi;
		$data[] = $nestedData;
		$no++;
	}
}

$json_data = array(
	"draw"            => intval($_POST['draw']),  
	"recordsTotal"    => intval($totalData),  
	"recordsFiltered" => intval($totalFiltered), 
	"data"            => $data   
);

echo json_encode($json_data); 
?>