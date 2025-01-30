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
	5 =>"tgl_lulus",
	6 =>"jml_prestasi",
	7 =>"aksi",
);
$where  ="1=1";
// $where  .="AND d.status_review='S'";

if(!empty($_GET['kode_prodi'])) $where.=" AND b.kode_prodi='$_GET[kode_prodi]'";
if(!empty($_GET['wisuda_ke'])) $where.=" AND b.wisuda_ke='$_GET[wisuda_ke]'";
if(!empty($_GET['status'])){
	$where .= $_GET['status']=="Sudah" ? " AND (b.nomor_skpi IS NOT NULL)" : " AND (b.nomor_skpi IS NULL)";
} 
$querycount = mysqli_query($koneksi_skpi, "SELECT a.nim FROM prestasi a inner join skpi b on a.nim=b.nim INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi WHERE $where GROUP BY a.nim");
$totalData = mysqli_num_rows($querycount);

$totalFiltered = $totalData; 

// $kolom=$_POST['order']['0']['column']>0 ? $_POST['order']['0']['column']-1 : $_POST['order']['0']['column'];
$kolom =$_POST['order']['0']['column'];
$limit = $_POST['length'];
$start = $_POST['start'];
$order = $columns[$kolom];
$dir = $_POST['order']['0']['dir'];

$sqlk="SELECT count(a.nim) as jumlah FROM prestasi a INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi WHERE $where GROUP BY a.nim";
$querycount = mysqli_query($koneksi_skpi, $sqlk);
$datacount = mysqli_fetch_assoc($querycount);
$kosong = $datacount['jumlah'];
if(empty($_POST['search']['value'])){            
}
else {
	$search = $_POST['search']['value']; 
	$where.=" AND (a.nim LIKE '%$search%') ";
	$querycount = mysqli_query($koneksi_skpi, "SELECT count(a.nim) as jumlah FROM prestasi a INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi WHERE $where GROUP BY a.nim");
	$datacount = mysqli_fetch_assoc($querycount);
	$totalFiltered = $datacount['jumlah'];
}

$sql="SELECT b.nim, b.nama, b.tgl_lulus, b.nomor_skpi, b.kode_prodi, b.wisuda_ke, c.singkat_prodi, c.jenjang, d.* FROM prestasi a inner join skpi b on a.nim=b.nim INNER JOIN prodi c ON b.kode_prodi=c.kode_prodi INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi WHERE $where GROUP BY a.nim order by $order $dir LIMIT $limit OFFSET $start";

$query = mysqli_query($koneksi_skpi, $sql);

$data = array();
$no = $start;
while($r=mysqli_fetch_assoc($query)){
	$no++;
	$nim=str_replace("-", "_", $r['nim']);
	$aksi="<div class='btn-group'><button class='btn btn-secondary btn-xs text-dark' data-toggle='modal' data-target='#modal-form' onclick='update(\"$nim\");'><i class='fas fa-file-alt'></i></button>";
	$flag=sha1($nim);
	if(empty($r['wisuda_ke'])){
		$mhs=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT tgl_lulus, wisuda_ke FROM mhs WHERE nim='$r[nim]'"));
		$r['tgl_lulus']=$mhs['tgl_lulus'];
		mysqli_query($koneksi_skpi, "UPDATE skpi SET tgl_lulus='$r[tgl_lulus]', wisuda_ke='$r[wisuda_ke]' WHERE nim='$r[nim]'");
	}
	if(empty($r['nomor_skpi'])){	
		$nomer="";
		$aksi.="<button class='btn btn-xs btn-primary' onclick='window.open(\"cetak/preview-$nim-$flag-I.html\")'><i class='fas fa-eye'></i></button>";
	}
	else{
		$nomer=$r['nomor_skpi'];
		$aksi.="<button class='btn btn-xs btn-success' onclick='window.open(\"cetak/skpi-$nim-$flag-I.html\")' title='Cetak Bahasa Inggris'><i class='fas fa-print'></i></button>";	
		$aksi.="<button class='btn btn-xs btn-success' onclick='window.open(\"cetak/skpi-$nim-$flag-A.html\")' title='Cetak Bahasa Arab'><i class='fas fa-print'></i></button>";	
	}
	$aksi.="</div>";

	$j=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT COUNT(a.nim) as jumlah FROM prestasi a INNER JOIN prestasi_translate b ON a.id_prestasi=b.id_prestasi WHERE a.nim='$r[nim]' AND b.status_review='S'"));

	$tgl_lulus=empty($r['tgl_lulus']) ? "" : indonesia($r['tgl_lulus']);
	$nestedData['no'] = $no;
	$nestedData['nim'] = $r['nim'];
	$nestedData['nama'] = strtoupper($r['nama']);
	$nestedData['prodi'] = $r['jenjang']."-".$r['singkat_prodi'];
	$nestedData['nomor_skpi'] = $r['nomor_skpi'];
	$nestedData['tgl_lulus'] = $tgl_lulus;
	$nestedData['jml_prestasi'] = $j['jumlah'];
	$nestedData['aksi'] = $aksi;

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