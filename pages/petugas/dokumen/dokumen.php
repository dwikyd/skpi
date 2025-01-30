<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-archive"></span> Jenis Dokumen</h3>
	</div>
	<div class="card-body">
		<div>
			<button class="btn btn-secondary text-dark mr-2" data-toggle="modal" data-target="#modal-form" onclick="tambah();"><span class="fas fa-file-alt mr-2"></span>Tambah</button>
		</div>
		<div class="text-center" id="proses"></div>
		<div class="table-responsive py-4">
			<table class="table table-hover" id="data">
				<thead class="thead-light">
					<tr><th width="30px">ID</th><th>Nama Dokumen</th><th width="30px">Aksi</th></tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form-signup" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card border-light p-4">
					<div class="card-header text-center pb-0">
						<h2 class="mb-0 h4"><span class="fas fa-archive"></span> Jenis Dokumen</h2>                               
					</div>
					<div class="card-body">
						<form action="#" id="form" method="post">
							<input type="hidden" name="aksi" value="" id="aksi">
							<div class="form-group mb-4">
								<label>ID Jenis Dokumen</label>
								<div class="input-group">
									<input type="text" maxlength="2" class="form-control" placeholder="Jenis Dokumen" name="id_jns_dokumen" id="id_jns_dokumen" required autofocus>
								</div>  
							</div>
							<div class="form-group mb-4">
								<label>Nama Dokumen</label>
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Nama Dokumen" name="nama_dokumen" id="nama_dokumen" required>
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
				url: 'pages/petugas/dokumen/proses.php',
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
		$('#id_jns_dokumen').removeAttr("readonly");
		$("#id_jns_dokumen").val('');
		$("#nama_dokumen").val('');
		$("#id_jns_dokumen").focus();
	}

	function edit(a){
		$.ajax({
			url: 'pages/petugas/dokumen/proses.php',
			type: "POST",
			data: "aksi=cari&id="+a,
			dataType: "JSON",
			success: function (data) {
				$("#aksi").val('edit');
				$('#id_jns_dokumen').attr("readonly", "readonly");
				$("#id_jns_dokumen").val(data.id_jns_dokumen);
				$("#nama_dokumen").val(data.nama_dokumen);
				$("#id_jns_dokumen").focus();
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
					url: 'pages/petugas/dokumen/proses.php',
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
				url: 'pages/petugas/dokumen/tabel.php',
				type: "post",
				error: function(){
					$(".data-error").html("");
					$("#data").append('<tbody class="data-error"><tr><th colspan="8">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			"columnDefs": [
			{ "targets": [0], "className": "text-center", "width": "30px" },
			{ "targets": [2], "className": "text-center", "width": "30px" },
			],
		} 
		);
	} 
	);
</script>