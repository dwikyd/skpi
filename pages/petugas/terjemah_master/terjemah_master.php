<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-archive"></span> Penerjemah SKPI</h3>
	</div>
	<div class="card-body">
		<div>
			<button class="btn btn-secondary text-dark mr-2" data-toggle="modal" data-target="#modal-form" onclick="tambah();"><span class="fas fa-file-alt mr-2"></span>Tambah</button>
		</div>
		<div class="text-center" id="proses"></div>
		<div class="table-responsive py-4">
			<table class="table table-hover" id="data">
				<thead class="thead-light">
					<tr><th width="30px">No</th><th>Nama Penerjemah</th><th width="30px">Jenis</th><th width="30px">Aktif</th><th width="30px">Aksi</th></tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-form" role="dialog" aria-labelledby="modal-form-signup" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card border-light p-4">
					<div class="card-header">
						<h2 class="mb-0 h4"><span class="fas fa-language"></span> Penerjemah SKPI</h2>
					</div>
					<div class="card-body">
						<form action="#" id="form" method="post">
							<input type="hidden" name="aksi" value="" id="aksi">
							<div class="form-group mb-4">
								<label>Nama Pegawai</label>
								<div class="input-group">
									<select class="form-control select" style="width:100%;" name="id_pegawai" id="id_pegawai" required autofocus>
										<option value="">-- Pilih Pegawai --</option>
										<?
										$peg=mysqli_query($koneksi_simpeg, "SELECT id_pegawai, nama_lengkap FROM pegawai ORDER by trim(nama_lengkap)");
										while($d=mysqli_fetch_assoc($peg)){
											echo "<option value='$d[id_pegawai]'>$d[nama_lengkap]</option>";
										}
										?>
									</select>
								</div>  
							</div>
							<div class="form-group mb-4">
								<label>Bahasa</label>
								<div class="input-group">
									<select class="form-control" name="jenis" id="jenis" required>
										<option value="I">Inggris</option>
										<option value="A">Arab</option>
									</select>
								</div>  
							</div>
							<div class="form-group mb-4">
								<label>Aktif</label>
								<div class="input-group">
									<select class="form-control" name="aktif" id="aktif" required>
										<option value="Y">Aktif</option>
										<option value="N">Tidak</option>
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
				url: 'pages/petugas/terjemah_master/proses.php',
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
			url: 'pages/petugas/terjemah_master/proses.php',
			type: "POST",
			data: "aksi=cari&id="+a,
			dataType: "JSON",
			success: function (data) {
				$("#aksi").val('edit');
				$('#id_pegawai').attr("readonly", "readonly");
				$("#id_pegawai").val(data.id_pegawai).trigger('change');
				$("#jenis").val(data.jenis);
				$("#aktif").val(data.aktif);
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
					url: 'pages/petugas/terjemah_master/proses.php',
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
	$(document).ready(function() {
		var dataTable = $('#data').DataTable( {
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": false,
			"deferRender": true,
			"ajax":{
				url: 'pages/petugas/terjemah_master/tabel.php',
				type: "post",
				error: function(){
					$(".data-error").html("");
					$("#data").append('<tbody class="data-error"><tr><th colspan="4">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			"columnDefs": [
				{ "targets": [0], "className": "text-center", "width": "30px" },
				{ "targets": [3], "className": "text-center", "width": "30px" },
				],
		} 
		);
	} 
	);
</script>