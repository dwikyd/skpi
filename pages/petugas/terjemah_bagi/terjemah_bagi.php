<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-archive"></span> Pembagian Penerjemah SKPI</h3>
	</div>
	<div class="card-body">
		<div>
			<form method="post" action="">
				<div class="form-group row" style="padding-bottom: 10px;">
					<label class="col-sm-1 control-label text-right">Prodi </label>
					<div class="col-sm-8">
						<select class='form-control' name='prodi' id="prodi" onchange="submit();">
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
						<select class='form-control' name="terjemah" id="terjemah" onchange="submit();">
							<option value="">-- Semua --</option>
							<?
							$jns=array("Y", "N");
							foreach ($jns as $k) {
								$dk=$k=="Y" ? "Sudah" : "Belum";
								$sel=$_POST['terjemah']== $k ? "selected" : "";
								echo "<option value='$k' $sel>$dk</option>";
							}
							?>
						</select>
					</div>
				</div>
			</form>
			<button class="btn btn-secondary btn-sm text-dark mr-2" type="button"><span class="fas fa-print mr-2"></span>Cetak</button>
		</div>
		<div class="text-center" id="proses"></div>
		<div class="table-responsive py-4">
			<table class="table table-hover table-striped table-bordered" id="data">
				<thead class="thead-light">
					<tr><th width="30px">No</th><th width="80px">Tanggal</th><th width="80px">NIM</th><th width="200px">Nama</th><th width="80px">Prodi</th><th>Penerjemah</th><th width="30px">Aksi</th></tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form-signup" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card border-light p-4">
					<div class="card-header">
						<h2 class="mb-0 h4"><span class="fas fa-language"></span> Penerjemah SKPI</h2>
					</div>
					<div class="card-body">
						<form action="#" id="form" method="post">
							<input type="hidden" name="aksi" value="" id="aksi">
							<input type="hidden" name="id_prestasi" value="" id="id_prestasi">
							<div class="row mb-3">
								<label class="col-sm-3 text-right">NIM :</label>
								<div class="col-sm-9">
									<span id="nim"></span>
								</div>  
							</div>
							<div class="row mb-3">
								<label class="col-sm-3 text-right">Prestasi :</label>
								<div class="col-sm-9">
									<p id="in_prestasi"></p>
								</div>  
							</div>
							<div class="row mb-3">
								<label class="col-sm-3 text-right">Penerjemah Inggris :</label>
								<div class="col-sm-9">
									<select class="form-control select2" name="id_inggris" id="id_inggris">
										<option value="">-- Pilih Pegawai --</option>
										<?
										$peg=mysqli_query($koneksi_skpi, "SELECT * FROM penerjemah WHERE jenis='I' ORDER by trim(nama_lengkap)");
										while($d=mysqli_fetch_assoc($peg)){
											echo "<option value='$d[id_pegawai]'>$d[nama_lengkap]</option>";
										}
										?>
									</select>
								</div>  
							</div>
							<div class="row mb-3">
								<label class="col-sm-3 text-right">Penerjemah Arab :</label>
								<div class="col-sm-9">
									<select class="form-control select2" name="id_arab" id="id_arab">
										<option value="">-- Pilih Pegawai --</option>
										<?
										$peg=mysqli_query($koneksi_skpi, "SELECT * FROM penerjemah WHERE jenis='A' ORDER by trim(nama_lengkap)");
										while($d=mysqli_fetch_assoc($peg)){
											echo "<option value='$d[id_pegawai]'>$d[nama_lengkap]</option>";
										}
										?>
									</select>
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
	$(function () {
		$('form').on('submit', function (e) {
			$("#proses").html("<span class='fas fa-spinner fa-spin'></span>");
			$('#modal-form').modal('hide');
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: 'pages/petugas/terjemah_bagi/proses.php',
				data: $('form').serialize(),
				success: function (a) {
					if(a==""){
						$('#data').DataTable().ajax.reload();
					}
					else{
						Swal.fire(a, '', 'error');
						$("#username").focus();
					}
					$("#proses").html("");
				}
			});
		});

	});

	function tambah(){
		$("#aksi").val('tambah');
		// $('#id_pegawai').removeAttr("readonly");
		$("#id_pegawai").val('');
		$("#aktif").val('Y');
		$("#id_pegawai").focus();
	}

	function edit(a){
		$.ajax({
			url: 'pages/petugas/terjemah_bagi/proses.php',
			type: "POST",
			data: "aksi=cari&id="+a,
			dataType: "JSON",
			success: function (data) {
				$("#aksi").val('edit');
				$("#id_prestasi").val(data.id_prestasi);
				$("#nim").html(data.nim);
				$("#nama").html(data.nama);
				$("#in_prestasi").html(data.in_prestasi);
				$("#id_inggris").val(data.id_inggris);
				$("#id_arab").val(data.id_arab);				
				$("#id_pegawai").focus();
			}
		});
	}

	function hapus(a){
		Swal.fire({
			title: 'Anda yakin ?',
			text: "Data dengan ID "+a+" akan dihapus.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, hapus !'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'pages/petugas/terjemah_bagi/proses.php',
					type: "POST",
					data: "aksi=delete&id="+a,
					success: function (data) {
						$('#data').DataTable().ajax.reload();
						Swal.fire(
							'Deleted!',
							'Your file has been deleted.',
							'success'
							)
					}
				});
			}
		})
	}
</script>

<script type="text/javascript" language="javascript" >
	$(function(){
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
				url: 'pages/petugas/terjemah_bagi/tabel.php?kode_prodi='+prodi+"&terjemah="+trj,
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
				{ "data": "penerjemah" },
				{ "data": "aksi" },
				],

			'columnDefs': [ {
				'targets': [0, 5, 6], /* table column index */
				'orderable': false, /* true or false */
			}]  
		});
	});
</script>