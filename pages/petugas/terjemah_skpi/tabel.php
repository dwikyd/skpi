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
	5 =>"status_translate",
	6 =>"aksi",
);
$where  ="1=1 ";
if(!empty($_GET['id_pegawai'])) $where.=" AND d.id_pegawai='$_GET[id_pegawai]'";
if(!empty($_GET['kode_prodi'])) $where.=" AND b.kode_prodi='$_GET[kode_prodi]'";
if(!empty($_GET['terjemah'])) $where.=" AND d.status='$_GET[terjemah]'";
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

$sql="SELECT a.*, b.nama, b.kode_prodi, c.singkat_prodi, c.jenjang, d.*, e.nama_lengkap FROM prestasi a inner join skpi b on a.nim=b.nim INNER JOIN prodi c ON b.kode_prodi=c.kode_prodi INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi INNER JOIN penerjemah e ON d.id_pegawai=e.id_pegawai WHERE $where order by $order $dir LIMIT $limit OFFSET $start";

$query = mysqli_query($koneksi_skpi, $sql);

$data = array();
$no = $start;
while($r=mysqli_fetch_assoc($query)){
	$no++;
	$nestedData['no'] = $no;
	$nestedData['tgl_upload'] = indonesia($r['tgl_upload']);
	$nestedData['nim'] = $r['nim'];
	$nestedData['nama'] = $r['nama']."<br>$r[in_prestasi]<br><span class='badge rounded px-1 py-1 bg-success'>".$r['nama_lengkap']."</span>";
	$nestedData['singkat_prodi'] = $r['singkat_prodi']." - ".$r['jenjang'];
	if($r['status_review']=="B"){
		if($r['status_translate']=="S"){
			$nestedData['status_translate'] = "Sudah<br><span class='badge rounded px-1 py-1 bg-warning'>Belum</span>";
			$nestedData['aksi'] = "<div class=btn-group btn-group-xs>
			<button type='button' onclick='window.open(\"terjemah_skpi-$r[id_prestasi]-$r[id_pegawai].html\");' title='Edit Data' class='btn btn-primary btn-xs btn-fill'><i class='fa fa-fw fa-edit'></i></button>
			</div>";
		}
		elseif($r['status_translate']=="R"){
			$nestedData['status_translate'] = "Revisi<br><span class='badge rounded px-1 py-1 bg-warning'>Belum</span>";
			$nestedData['aksi'] = "<div class=btn-group btn-group-xs>
			<button type='button' onclick='window.open(\"terjemah_skpi-$r[id_prestasi]-$r[id_pegawai].html\");' title='Edit Data' class='btn btn-secondary btn-xs btn-fill'><i class='fa fa-fw fa-edit'></i></button>
			</div>";
		}
		else{
			$nestedData['status_translate'] = "Belum<br><span class='badge rounded px-1 py-1 bg-warning'>Belum</span>";
			$nestedData['aksi'] = "<div class=btn-group btn-group-xs>
			<button type='button' onclick='window.open(\"terjemah_skpi-$r[id_prestasi]-$r[id_pegawai].html\");' title='Edit Data' class='btn btn-warning btn-xs btn-fill'><i class='fa fa-fw fa-edit'></i></button>
			</div>";
		}
	}
	else{
		$status_review=$r['status_review']=="S" ? "<span class='badge rounded px-1 py-1 bg-success'>Sudah</span>" : "<span class='badge rounded px-1 py-1 bg-warning'>Revisi</span>";
		$nestedData['status_translate'] = "Sudah<br>$status_review";
		$nestedData['aksi'] = "<div class=btn-group btn-group-xs>
		<button type='button' onclick='window.open(\"terjemah_skpi-$r[id_prestasi]-$r[id_pegawai].html\");' title='Edit Data' class='btn btn-success btn-xs btn-fill'><i class='fa fa-fw fa-eye'></i></button>
		</div>";
	}

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