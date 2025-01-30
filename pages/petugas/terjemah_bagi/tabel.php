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
	5 =>"penerjemah",
	6 =>"aksi",
);
$where  ="1=1 AND a.sts_skpi='Y'";
if(!empty($_GET['kode_prodi'])) $where.=" AND b.kode_prodi='$_GET[kode_prodi]'";
if(!empty($_GET['terjemah'])) $where.=" AND a.sts_bagi_translate='$_GET[terjemah]'";
$querycount = mysqli_query($koneksi_skpi, "SELECT a.nim FROM prestasi a inner join skpi b on a.nim=b.nim WHERE $where");
$totalData = mysqli_num_rows($querycount);

$totalFiltered = $totalData; 

// $kolom=$_POST['order']['0']['column']>0 ? $_POST['order']['0']['column']-1 : $_POST['order']['0']['column'];
$kolom =$_POST['order']['0']['column'];
$limit = $_POST['length'];
$start = $_POST['start'];
$order = $columns[$kolom];
$dir = $_POST['order']['0']['dir'];

$sqlk="SELECT count(a.nim) as jumlah FROM prestasi a WHERE $where";
$querycount = mysqli_query($koneksi_skpi, $sqlk);
$datacount = mysqli_fetch_assoc($querycount);
$kosong = $datacount['jumlah'];
if(empty($_POST['search']['value'])){            
}
else {
	$search = $_POST['search']['value']; 
	$where.=" AND (a.nim LIKE '%$search%') ";
	$querycount = mysqli_query($koneksi_skpi, "SELECT count(a.nim) as jumlah FROM prestasi a WHERE $where");
	$datacount = mysqli_fetch_assoc($querycount);
	$totalFiltered = $datacount['jumlah'];
}

$sql="SELECT a.*, b.nama, b.kode_prodi, c.singkat_prodi, c.jenjang FROM prestasi a inner join skpi b on a.nim=b.nim INNER JOIN prodi c ON b.kode_prodi=c.kode_prodi WHERE $where order by $order $dir LIMIT $limit OFFSET $start";

$query = mysqli_query($koneksi_skpi, $sql);

$data = array();
$no = $start;
while($r=mysqli_fetch_assoc($query)){
	$no++;
	$nestedData['no'] = $no;
	$nestedData['tgl_upload'] = indonesia($r['tgl_upload']);
	$nestedData['nim'] = $r['nim'];
	$nestedData['nama'] = $r['nama'];
	$nestedData['singkat_prodi'] = $r['singkat_prodi']." - ".$r['jenjang'];
	$trans="";
		$status="B";
	if($r['sts_bagi_translate']=="Y"){
		$trans.="<ol style='margin-left:-30px;'>";
		$tr=mysqli_query($koneksi_skpi, "SELECT a.jenis, b.nama_lengkap, a.status_translate FROM prestasi_translate a INNER JOIN penerjemah b ON a.id_pegawai=b.id_pegawai WHERE a.id_prestasi='$r[id_prestasi]' ORDER BY a.jenis DESC");
		while($t=mysqli_fetch_assoc($tr)){
			$trans.="<li style='font-size:80%;'>$t[nama_lengkap]</li>";
			if($t['status_translate']=="S") $status="S";
		}
		$trans.="</ol>";
	}
	$nestedData['penerjemah'] = $trans;
	$nestedData['aksi'] = "<div class=btn-group btn-group-xs>";
	if($status=="B"){
		$nestedData['aksi'] .= "<button type='button' data-toggle='modal' data-target='#modal-form' onclick='edit(\"$r[id_prestasi]\");' title='Edit Data' class='btn btn-success btn-xs btn-fill'><i class='fa fa-fw fa-eye'></i></button>";
	}
	else{
		$nestedData['aksi'] .= "<span class='badge bg-success'><i class='fa fa-check'></i></span>";
	}
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