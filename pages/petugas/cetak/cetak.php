<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>
<?
$prodi=$_COOKIE['prodi'];
$status=$_COOKIE['status'];
$wisuda_ke=$_COOKIE['wisuda'];
?>
<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-print"></span> Cetak SKPI</h3>
	</div>
	<div class="card-body">
		<input type="hidden" id="approval" value="<?= $approval ?>">
		<div class="form-group row" style="padding-bottom: 10px;">
			<label class="col-sm-1 control-label text-right">Prodi </label>
			<div class="col-sm-5">
				<select class='form-control select' data-live-search='true' id="prodi" onchange="prodi(this);">
					<option value="">-- Semua Prodi --</option>
					<?
					$sql="SELECT a.kode_prodi, a.nama_prodi, a.jenjang, a.kode_jurusan, b.nama_jurusan FROM prodi a INNER JOIN jurusan b ON a.kode_jurusan=b.kode_jurusan WHERE a.kode_prodi in ($_SESSION[kode_prodi]) ORDER BY a.kode_jurusan, a.jenjang, a.kode_prodi";

					$jns=mysqli_query($koneksi_sikadu, $sql);
					$jurusan="";
					while($d=mysqli_fetch_assoc($jns)){
						if($jurusan!=$d['kode_jurusan']){
							if(!empty($jurusan)) echo "</optgroup>";
							$jurusan=$d['kode_jurusan'];
							echo "<optgroup label='$d[nama_jurusan]'>";
						}
						$sel=$prodi== $d['kode_prodi'] ? "selected" : "";
						echo "<option value='$d[kode_prodi]' $sel>$d[jenjang] - $d[nama_prodi]</option>";
					}
					if(!empty($jurusan)) echo "</optgroup>";
					?>
				</select>
			</div>
			<label class="col-sm-1 control-label text-right">Wisuda </label>
			<div class="col-sm-2">
				<select class='form-control select' data-live-search='true' id="wisuda_ke" onchange="wisuda(this);">
					<option value="">-- Semua --</option>
					<?
					$sql="SELECT wisuda_ke FROM skpi WHERE kode_prodi in ($_SESSION[kode_prodi]) GROUP BY wisuda_ke ORDER BY wisuda_ke DESC";

					$jns=mysqli_query($koneksi_skpi, $sql);
					while($d=mysqli_fetch_assoc($jns)){
						$sel=$wisuda_ke== $d['wisuda_ke'] ? "selected" : "";
						echo "<option value='$d[wisuda_ke]' $sel>$d[wisuda_ke]</option>";
					}
					?>
				</select>
			</div>
			<label class="col-sm-1 control-label text-right">Nomor </label>
			<div class="col-sm-2">
				<select class='form-control select' data-live-search='true' id="status" onchange="sts(this);">
					<option value="">-- Semua --</option>
					<?
					$sts=array('Sudah','Belum');

					foreach ($sts as $k) {
						$sel=$status== $k ? "selected" : "";
						echo "<option value='$k' $sel>$k</option>";
					}
					?>
				</select>
			</div>
		</div>
		<div class="text-center" id="proses"></div>
		<div class="table-responsive py-4">
			<table class="table table-hover" id="data">
				<thead class="thead-light">
					<tr><th width="20px">No</th><th width="80px">NIM</th><th width="150px">Nama</th><th width="80px">Prodi</th><th width="120px">Nomor</th><th width="100px">Tgl Lulus</th><th width="30px">Jml Prestasi</th><th width="30px">Aksi</th></tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-form" role="dialog" aria-labelledby="modal-form-signup" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card border-light p-4">
					<div class="card-header text-center pb-0">
						<h2 class="mb-0 h4"><span class="fas fa-calculator"></span> Nomor SKPI</h2>                               
					</div>
					<div class="card-body">
						<form action="#" id="form" method="post">
							<input type="hidden" name="aksi" value="" id="aksi">
							<div class="form-group row" style="padding-bottom: 10px;">
								<label class="col-sm-3 control-label text-right">NIM </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="NIM" name="nim" id="nim" required readonly>
								</div>  
							</div>
							<div class="form-group row" style="padding-bottom: 10px;">
								<label class="col-sm-3 control-label text-right">Nama</label>
								<div class="col-sm-9">
									<input type="text" maxlength="2" class="form-control" placeholder="Nama" name="nama" id="nama" readonly>
								</div>  
							</div>
							<div class="form-group row" style="padding-bottom: 10px;">
								<label class="col-sm-3 control-label text-right">Prodi</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="Nama Prodi" name="prodi" id="nama_prodi" readonly>
								</div>  
							</div>
							<div class="form-group row" style="padding-bottom: 10px;">
								<label class="col-sm-3 control-label text-right">Nomor Ijazah</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="Nomor Ijazah" name="no_ijazah" id="no_ijazah" readonly>
								</div>  
							</div>
							<div class="form-group row" style="padding-bottom: 10px;">
								<label class="col-sm-3 control-label text-right">Tgl. Lulus</label>
								<div class="col-sm-9">
									<input type="date" class="form-control" placeholder="Tanggal Lulus sesuai Ijazah" name="tgl_lulus" id="tgl_lulus" autofocus>
								</div>  
							</div>
							<div class="form-group row" style="padding-bottom: 10px;">
								<label class="col-sm-3 control-label text-right">Nomor SKPI</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" placeholder="Nomor SKPI" name="nomor_skpi" id="nomor_skpi">
								</div>  
							</div>
							<div class="form-group row" style="padding-bottom: 10px;">
								<label class="col-sm-3 control-label text-right">Penandatangan</label>
								<div class="col-sm-9">
									<select class="form-control select mb-3" name="ttd" id="ttd" style="width:100%;">
										<option value="">-- Pilih Dekan --</option>
										<?
										$dos=mysqli_query($koneksi_sikadu, "SELECT id_pegawai, trim(nama_peg) as nama_peg FROM pegawai WHERE id_pegawai!='' ORDER BY trim(nama_peg)");
										while($d=mysqli_fetch_assoc($dos)){
											echo "<option value='$d[id_pegawai]'>$d[nama_peg]</option>";
										}
										?>
									</select>
									<input type="text" class="form-control mb-2" name="nama_dekan" id="nama_dekan" value="">
								</div>  
							</div>
							<button class="btn btn-success" name="submit" type="submit">Simpan</button>
							<button class="btn btn-danger" type="button" data-dismiss="modal">Tutup</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End of Modal Content -->

<!-- <script src="assets/js/jquery-1.9.1.js"></script> -->
<script>
	$( ".select" ).change(function() {
		var id_pegawai=$("#ttd").val();
		$.ajax({
			url: 'pages/petugas/cetak/proses.php',
			type: "POST",
			data: "aksi=dekan&id_pegawai="+id_pegawai,
			success: function (data) {
				$("#nama_dekan").val(data);
			}
		});
	});

	function prodi(a){
		var pd=a.value;
		document.cookie = "prodi="+pd;
		window.location.reload();
		// $('#data').DataTable().ajax.reload();
	}

	function wisuda(a){
		var wsd=a.value;
		document.cookie = "wisuda="+wsd;
		// $('#data').DataTable().ajax.reload();
		window.location.reload();
	}

	function sts(a){
		var sts=a.value;
		document.cookie = "status="+sts;
		// $('#data').DataTable().ajax.reload();
		window.location.reload();
	}

	function update(a){
		$.ajax({
			url: 'pages/petugas/cetak/proses.php',
			type: "POST",
			data: "aksi=cari&nim="+a,
			dataType: "JSON",
			success: function (data) {
				$("#aksi").val(data.aksi);
				$("#nim").val(data.nim);
				$("#nama").val(data.nama);
				$("#nama_prodi").val(data.prodi);
				$("#tgl_lulus").val(data.tgl_lulus);
				$("#no_ijazah").val(data.no_ijazah);
				$("#nomor_skpi").val(data.nomor_skpi);
				// $("#ttd").val(data.ttd);
				// $("#ttd").val(data.ttd).trigger("chosen:updated");
				$("#ttd").val(data.ttd).trigger('change');
				$("#nama_dekan").val(data.nama_dekan);
				$("#nomor_skpi").focus();
			}
		});
	}

	$(function () {
		$('form').on('submit', function (e) {
			$("#proses").html("<span class='fas fa-spinner fa-spin'></span>");
			$('#modal-form').modal('hide');
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: 'pages/petugas/cetak/proses.php',
				data: $('form').serialize(),
				success: function (a) {
					if(a==""){
						$('#data').DataTable().ajax.reload();
					}
					else{
						Swal.fire(a, '', 'error');
					}
					$("#proses").html("");
				}
			});
		});

	});
</script>

<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		var prodi=$("#prodi").val();
		var ke   =$("#wisuda_ke").val();
		var sts  =$("#status").val();
		var dataTable = $('#data').DataTable( {
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": true,
			"deferRender": true,
			"order": [[ 1, 'asc' ]],
			"ajax":{
				url: 'pages/petugas/cetak/tabel.php?kode_prodi='+prodi+'&wisuda_ke='+ke+'&status='+sts,
				type: "post",
				error: function(){
					$(".belum-error").html("");
					$("#belum").append('<tbody class="belum-error"><tr><th colspan="6">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#belum-error-proses").css("display","none");

				}
			},
			"columns": [
				{ "data": "no" },
				{ "data": "nim" },
				{ "data": "nama" },
				{ "data": "prodi"},
				{ "data": "nomor_skpi"},
				{ "data": "tgl_lulus"},
				{ "data": "jml_prestasi"},
				{ "data": "aksi"},
				],

			'columnDefs': [ {
        'targets': [0, 6, 7], /* table column index */
        'orderable': false, /* true or false */
			}]  

		}); 
	});
</script>