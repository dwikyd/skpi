<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>
<div>
	<form method="post" action="">
		<div class="form-group row" style="padding-bottom: 10px;">
			<label class="col-sm-2 control-label text-right">Penerjemah </label>
			<div class="col-sm-10">
				<select class='form-control select' name='id_pegawai' id="id_pegawai" onchange="submit();">
					<?
					if($_SESSION['admin_skpi']=="Y") echo "<option value=''>-- Semua Penerjemah --</option>";
					if(!isset($_POST['id_pegawai'])){
						if($_SESSION['admin_skpi']=="N") $_POST['id_pegawai']=$_SESSION['id_pegawai'];
					}
					$where=$_SESSION['admin_skpi']=="Y" ? "" : "WHERE id_pegawai='$_SESSION[id_pegawai]'";
					$sql="SELECT * FROM penerjemah $where ORDER BY trim(nama_lengkap)";

					$jns=mysqli_query($koneksi_skpi, $sql);
					while($d=mysqli_fetch_assoc($jns)){
						$sel=$_POST['id_pegawai']== $d['id_pegawai'] ? "selected" : "";
						echo "<option value='$d[id_pegawai]' $sel>$d[nama_lengkap]</option>";
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group row" style="padding-bottom: 10px;">
			<label class="col-sm-2 control-label text-right">Prodi </label>
			<div class="col-sm-7">
				<select class='form-control select' name='prodi' id="prodi" onchange="submit();">
					<option value="">-- Semua Prodi --</option>
					<?
					$sql="SELECT kode_prodi, nama_prodi, jenjang FROM prodi ORDER BY jenjang, kode_prodi";

					$jns=mysqli_query($koneksi_sikadu, $sql);
					while($d=mysqli_fetch_assoc($jns)){
						$sel=$_POST['prodi']== $d['kode_prodi'] ? "selected" : "";
						echo "<option value='$d[kode_prodi]' $sel>$d[jenjang] - $d[nama_prodi]</option>";
					}
					?>
				</select>
			</div>
			<label class="col-sm-1 control-label text-right">Status</label>
			<div class="col-sm-2">
				<select class='form-control select' name="terjemah" id="terjemah" onchange="submit();">
					<option value="">-- Semua --</option>
					<?
					$jns=array("S", "B");
					foreach ($jns as $k) {
						$dk=$k=="S" ? "Sudah" : "Belum";
						$sel=$_POST['terjemah']== $k ? "selected" : "";
						echo "<option value='$k' $sel>$dk</option>";
					}
					?>
				</select>
			</div>
		</div>
	</form>
	<!-- <button class="btn btn-secondary btn-sm text-dark mr-2" type="button"><span class="fas fa-print mr-2"></span>Cetak</button> -->
</div>
<div class="text-center" id="proses"></div>
<div class="table-responsive py-4">
	<table class="table table-hover table-striped table-bordered" id="data">
		<thead class="thead-light">
			<tr><th width="30px">No</th><th width="80px">Tanggal</th><th width="80px">NIM</th><th>Nama</th><th width="80px">Prodi</th><th width="30px">Terjemah / Review</th><th width="30px">Aksi</th></tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<script type="text/javascript" language="javascript" >
	function onVisibilityChange() {
		if (document.visibilityState === 'visible') {
			$('#data').DataTable().ajax.reload(null, false);
		} 
	}
	document.addEventListener('visibilitychange', onVisibilityChange);

	$(function(){
		var id_pegawai=$("#id_pegawai").val();
		var prodi=$("#prodi").val();
		var trj  =$("#terjemah").val();
		$('#data').DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": true,
			"deferRender": true,
			"order": [[ 1, 'asc' ]],
			"ajax":{
				url: 'pages/petugas/terjemah_skpi/tabel.php?id_pegawai='+id_pegawai+'&kode_prodi='+prodi+"&terjemah="+trj,
				"dataType": "json",
				"type": "POST",
				error: function(xhr, error, code){
					$("#data").append('<tbody class="data-error"><tr><th colspan="6">Tidak ada data untuk ditampilkan : '+code+' : '+error+'</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			"columns": [
				{ "data": "no" },
				{ "data": "tgl_upload" },
				{ "data": "nim" },
				{ "data": "nama" },
				{ "data": "singkat_prodi" },
				{ "data": "status_translate" },
				{ "data": "aksi" },
				],

			'columnDefs': [ {
				'targets': [0, 6], /* table column index */
				'orderable': false, /* true or false */
			}]  
		});
	});
</script>