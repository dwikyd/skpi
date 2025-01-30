<link href="assets/css/wysiwyg.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-archive"></span> Bidang CP</h3>
	</div>
	<div class="card-body">
		<?
		if(empty($url[1])){
			?>
			<div>
				<button class="btn btn-secondary text-dark mr-2" onclick="window.location.href='cp_bidang-tambah.html';"><span class="fas fa-file-alt mr-2"></span>Tambah</button>
			</div>
			<div class="text-center" id="proses"></div>
			<div class="table-responsive py-4">
				<table class="table table-hover" id="data">
					<thead class="thead-light">
						<tr><th width="30px">ID</th><th>Nama Bidang</th><th>English</th><th>Arabic</th><th width="30px">Aksi</th></tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<?
		}
		else{
			$aksi="tambah";
			if(!empty($url[2])){
				$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM cp_bidang where id_bidang='$url[2]'"));
				$aksi="edit";
			}
			?>
			<form action="pages/petugas/cp_bidang/proses.php" id="form" method="post" enctype="multipart/form-data">
				<input type="hidden" name="aksi" value="<?= $aksi ?>" id="aksi">
				<div class="row form-group mb-4">
					<label class="col-3 text-right">ID Bidang</label>
					<div class="col-9">
						<input type="text" maxlength="2" class="form-control" placeholder="ID Bidang" name="id_bidang" value="<?= $d['id_bidang'] ?>" required autofocus <?= $read ?>>
					</div>  
				</div>
				<div class="row form-group mb-4">
					<label class="col-3 text-right">Nama Bidang</label>
					<div class="col-9">
						<input type="text" class="form-control" placeholder="Nama Bidang" name="nama_bidang" value="<?= $d['nama_bidang'] ?>" required>
					</div>  
				</div>
				<div class="row form-group mb-4">
					<label class="col-3 text-right">English</label>
					<div class="col-9">
						<input type="text" class="form-control" placeholder="English" name="eng_bidang" value="<?= $d['eng_bidang'] ?>" required>
					</div>  
				</div>
				<div class="row form-group mb-4">
					<label class="col-3 text-right">Arabic</label>
					<div class="col-9">
						<textarea class="form-control" style="display: none;" placeholder="Arabic" name="arb_bidang" id="arb_bidang" required><?= $d['arb_bidang'] ?></textarea>
					</div>  
				</div>
				<div class="text-center">
					<button class="btn btn-success" name="submit" type="submit">Simpan</button>
					<button class="btn btn-danger" type="button" data-dismiss="modal">Tutup</button>
				</div>
			</form>
			<?
		}
		?>
	</div>
</div>


<!-- <script src="assets/js/jquery-1.9.1.js"></script> -->
<script src="assets/js/wysiwyg.js"></script>

<script>
	$(document).ready(function () {
		$('#arb_bidang').wysiwyg({
			toolbar: [
				['mode'],
				['operations', ['undo','rendo','cut','copy','paste']],
				['styles'],
				['fonts', ['select','size']],
				['text', ['bold','italic','underline', 'font-color' ,'subscript','superscript']],
				['align', ['left','center','right','justify']],
				['lists', ['unordered','ordered','indent','outdent']],
			['components', ['table',/*'chart'*/]],
				['intervals', ['line-height','letter-spacing']],
				['fullscreen'],
				],
		});
	});
	$(function () {
		// $('form').on('submit', function (e) {
		// 	$("#proses").html("<span class='fas fa-spinner fa-spin'></span>");
		// 	$('#modal-form').modal('hide');
		// 	e.preventDefault();
		// 	$.ajax({
		// 		type: 'post',
		// 		url: 'pages/petugas/cp_bidang/proses.php',
		// 		data: $('form').serialize(),
		// 		success: function (a) {
		// 			if(a==""){
		// 				$('#data').DataTable().ajax.reload();
		// 			}
		// 			else{
		// 				Swal.fire(a, '', 'error');
		// 				$("#username").focus();
		// 			}
		// 			$("#proses").html("");
		// 		}
		// 	});
		// });

	});

	$('#form').submit(function(evt) {
		evt.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			type: 'POST',
			url: $(this).attr('action'),
			data:formData,
			cache:false,
			contentType: false,
			processData: false,
			success: function(a) {
				if(a==""){
					window.location.href="cp_bidang.html";
				}
				else{
					Swal.fire(a, '', 'error');
				}
			},
			error: function(data) {
				alert(data);
			}
		});
	});
	function tambah(){
		$("#aksi").val('tambah');
		$('#id_bidang').removeAttr("readonly");
		$("#id_bidang").val('');
		$("#nama_bidang").val('');
		$("#eng_bidang").val('');
		$("#arb_bidang").val('');
		$("#id_bidang").focus();
	}

	function edit(a){
		$.ajax({
			url: 'pages/petugas/cp_bidang/proses.php',
			type: "POST",
			data: "aksi=cari&id="+a,
			dataType: "JSON",
			success: function (data) {
				$("#aksi").val('edit');
				$('#id_bidang').attr("readonly", "readonly");
				$("#id_bidang").val(data.id_bidang);
				$("#nama_bidang").val(data.nama_bidang);
				$("#eng_bidang").val(data.eng_bidang);
				$("#arb_bidang").val(data.arb_bidang);
				$("#id_bidang").focus();
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
					url: 'pages/petugas/cp_bidang/proses.php',
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
				url: 'pages/petugas/cp_bidang/tabel.php',
				type: "post",
				error: function(){
					$(".data-error").html("");
					$("#data").append('<tbody class="data-error"><tr><th colspan="5">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			"columnDefs": [
				{ "targets": [0], "className": "text-center", "width": "30px" },
				{ "targets": [1], "className": "text-left", "width": "30%" },
				{ "targets": [2], "className": "text-left", "width": "30%" },
				{ "targets": [3], "className": "text-left", "width": "30%" },
				{ "targets": [4], "className": "text-center", "width": "30px" },
				],
		} 
		);
	} 
	);
</script>