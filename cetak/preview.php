<div style="background-color: white; color: black; width:21cm; padding:10px;" align="center">
	<?
	if($nim!=""){
		$mhs=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, a.tmp_lahir, a.tgl_lahir, a.texttgl_lahir, a.jenjang, b.nama_prodi, c.nama_jurusan, a.ta, a.tgl_munaqosah, a.no_ijazah, a.no_pin, b.gelar, b.gelar_singkat, b.sk_pendirian, b.sk_ban, b.eng_prodi, c.eng_jurusan, a.kode_prodi FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi inner join jurusan c on b.kode_jurusan=c.kode_jurusan WHERE a.nim='$nim'"));
		
		include "isi_skpi_$url[3].php";
	}
	?>
</div>
<style type="text/css">
	@media screen {
		body {
			margin: auto;
			color: #fff;
			background-color: #d4d4d4;
		}

	}
	/* print styles */
	@media print {
		body {
			margin: 0;
			color: #000;
			background-color: #fff;
		}

	}

	.tabel {
		border-collapse: collapse;
		margin-top: 10px;
		margin-bottom: 10px;
		width: 100%;
	}
	.tabel th {
		height: 35px;
		padding-left: 5px;
		padding-right: 5px;
		color: #000000;
		text-align: center;
		text-transform:uppercase;
		background-color: #CCCCCC;
	}
	.tabel tr {
		height: 29px;
		vertical-align: top;
	}
	.tabel td {
		padding: 5px;
		border-collapse: collapse; 
	}

	.tabel td.judul_kiri {
		border: black 1px solid;
		border-right: none;
	}

	.tabel td.judul_kanan {
		border: black 1px solid;
		border-left: none;
	}

	.tabel td.subjudul {
		padding-top: 5px;
		border: black 1px solid;
	}
	.tabel tr.row-a {
		font-weight: bold;
		vertical-align: top;
	}
	.tabel tr.ganjil {
		background-color: #e2f7c8;
		border-color: #e2f7c8;
	}
</style>