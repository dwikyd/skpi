<?php
session_start();
include "../../../config/koneksi.php";
include "../../../config/fungsi_indotgl.php";

$columns = array( 
	0 =>"no",
	1 =>"nim",
	2 =>"nama",
	3 =>"prodi",
	4 =>"wisuda_ke",
	5 =>"jml_prestasi",
	6 =>"aksi",
);

$where = " 1=1 ";
$where .= empty($_GET['prodi']) ? " AND a.kode_prodi in($_SESSION[kode_prodi]) " : " AND a.kode_prodi='$_GET[prodi]'";
// $where .= $wisuda_ke=="" ? "" : " AND wisuda_ke='$wisuda_ke'";
// if($status!="") $where .= $status=="Sudah" ? " AND nomor_skpi!=''" : " AND nomor_skpi=''";
$querycount = mysqli_query($koneksi_skpi, "SELECT a.nim FROM skpi a INNER JOIN prestasi b ON a.nim=b.nim WHERE $where GROUP BY a.nim ") or die(mysqli_error($koneksi_skpi));
$totalData = mysqli_num_rows($querycount);
$totalFiltered = $totalData; 

$kolom =$_POST['order']['0']['column'];
$limit = $_POST['length'];
$start = $_POST['start'];
$order = $columns[$kolom];
$dir   = $_POST['order']['0']['dir'];

if(empty($_POST['search']['value'])){            
}
else {
	$search = $_POST['search']['value']; 
	$where.=" AND (a.nim LIKE '%$search%' or a.nama LIKE '%$search%' or a.tgl_finalisasi LIKE '%$search%') ";
	$querycount = mysqli_query($koneksi_skpi, "SELECT a.nim as jumlah FROM skpi a INNER JOIN prestasi b ON a.nim=b.nim WHERE $where GROUP BY a.nim ");
	$datacount = mysqli_fetch_assoc($querycount);
	$totalFiltered = mysqli_num_rows($querycount);
}

$sql="SELECT a.*, COUNT(b.id_prestasi) as jml_prestasi FROM skpi a INNER JOIN prestasi b ON a.nim=b.nim WHERE $where GROUP BY a.nim order by $order $dir LIMIT $limit OFFSET $start";

$query = mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));

$data = array();
if(!empty($query)){
	$no = $start + 1;
	while ($r = mysqli_fetch_assoc($query)){
		$nim=str_replace("-", "_", $r['nim']);
		if($p['wisuda_ke']==0){
			$w=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT wisuda_ke FROM mhs WHERE nim='$p[nim]'"));
			mysqli_query($koneksi_skpi, "UPDATE skpi SET wisuda_ke='$w[wisuda_ke]' WHERE nim='$p[nim]'");
			$p['wisuda_ke']=$w['wisuda_ke'];
		}

		// $aksi="<div class='btn-group'><button class='btn btn-xs btn-success' onclick='window.open(\"cetak/prestasi-$nim.html\")'><i class='fas fa-print'></i></button><button class='btn btn-xs btn-info' onclick='window.open(\"approval-$nim.html\");'><i class='fas fa-eye'></i></button></div>";

		$aksi="<div class='btn-group'><button onclick='pilih(\"$r[nim]\");' class='btn btn-warning btn-xs'><i class='fas fa-eye'></i></button>";
		// if($r['finalisasi']=="Y") $aksi.="<button class='btn btn-xs btn-primary' onclick='batal(\"$r[nim]\");'><i class='fas fa-undo'></i></button>";
		$aksi.="</div>";

		$nestedData['no'] = $no;
		$nestedData['nim'] = $r['nim'];
		$nestedData['nama'] = strtoupper($r['nama']);
		$nestedData['prodi'] = $r['prodi'];
		$nestedData['wisuda_ke'] = $r['wisuda_ke'];
		$nestedData['jml_prestasi'] = $r['jml_prestasi'];
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
