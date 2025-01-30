<?php
session_start();
include "../../../config/koneksi.php";
include "../../../config/fungsi_indotgl.php";

$columns = array( 
	0 =>"no",
	1 =>"nim",
	2 =>"nama",
	3 =>"prodi",
	4 =>"nomor_skpi",
	5 =>"jml_prestasi",
	6 =>"aksi",
);

$prodi 		=$_COOKIE['prodi'];
$status 	=$_COOKIE['status'];
$wisuda_ke 	=$_COOKIE['wisuda'];
$status 	=$_COOKIE['status'];

$where = " 1=1 AND finalisasi='Y'";
$where .= $prodi=="" ? " AND kode_prodi in($_SESSION[kode_prodi]) " : " AND kode_prodi='$prodi'";
$where .= $wisuda_ke=="" ? "" : " AND wisuda_ke='$wisuda_ke'";
if($status!="") $where .= $status=="Sudah" ? " AND nomor_skpi!=''" : " AND (nomor_skpi='' OR ISNULL(nomor_skpi))";
$querycount = mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE $where") or die(mysqli_error($koneksi_skpi));
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
	$where.=" AND (nim LIKE '%$search%' or nama LIKE '%$search%' or nomor_skpi LIKE '%$search%') ";
	$querycount = mysqli_query($koneksi_skpi, "SELECT nim as jumlah FROM skpi WHERE $where");
	$datacount = mysqli_fetch_assoc($querycount);
	$totalFiltered = mysqli_num_rows($querycount);
}

$sql="SELECT * FROM skpi WHERE $where order by $order $dir LIMIT $limit OFFSET $start";

$query = mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));

$data = array();
if(!empty($query)){
	$no = $start + 1;
	while ($r = mysqli_fetch_assoc($query)){
		$nim=str_replace("-", "_", $r['nim']);
		$aksi="<div class='btn-group'><button class='btn btn-secondary btn-xs text-dark' data-toggle='modal' data-target='#modal-form' onclick='update(\"$nim\");'><i class='fas fa-file-alt'></i></button>";
		if(empty($r['nomor_skpi'])){	
			$nomer="";
			$aksi.="<button class='btn btn-xs btn-primary' onclick='window.open(\"cetak/preview-$nim.html\")'><i class='fas fa-eye'></i></button>";
		}
		else{
			$flag=sha1($nim);
			$nomer=$r['nomor_skpi'];
			// $aksi.="<button class='btn btn-xs btn-success' onclick='window.open(\"cetak/skpi-$nim.html\")' title='Cetak F4'><i class='fas fa-print'></i></button>";	
			$aksi.="<button class='btn btn-xs btn-success' onclick='window.open(\"cetak/skpi-$nim-$flag.html\")' title='Cetak'><i class='fas fa-print'></i></button>";	
		}
		$aksi.="</div>";

		$j=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT COUNT(a.nim) as jumlah FROM prestasi a INNER JOIN prestasi_translate b ON a.id_prestasi=b.id_prestasi WHERE a.nim='$r[nim]' AND b.status_review='S'"));
		$nestedData['no'] = $no;
		$nestedData['nim'] = $r['nim'];
		$nestedData['nama'] = strtoupper($r['nama']);
		$nestedData['prodi'] = $r['prodi'];
		$nestedData['nomor_skpi'] = $r['nomor_skpi'];
		$nestedData['jml_prestasi'] = $j['jumlah'];
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
