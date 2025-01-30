<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-tag"></span> Bukti Dokumen</h3>
	</div>
	<div class="card-body">
		<?
		$url[1]=!isset($url[1]) ? "" : $url[1];
		if(!isset($url[2])){ ?>
			<div class="form-group row" style="padding-bottom: 10px;">
				<label class="col-sm-2 control-label text-right">Jenis Dokumen </label>
				<div class="col-sm-10">
					<select class='form-control select' data-live-search='true' id="jenis" onchange="pilih(this);">
						<option value="">-- Semua Jenis Dokumen --</option>
						<?
						$jns=mysqli_query($koneksi_skpi, "SELECT id_jns_dokumen, nama_dokumen FROM jenis_dokumen ORDER BY id_jns_dokumen");
						while($d=mysqli_fetch_assoc($jns)){
							$sel=$url[1]== $d['id_jns_dokumen'] ? "selected" : "";
							echo "<option value='$d[id_jns_dokumen]' $sel>$d[nama_dokumen]</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div>
				<button class="btn btn-secondary text-dark mr-2" onclick="window.location.href='dokumen_bukti-<?= $url[1] ?>-tambah.html';"><span class="fas fa-file-alt mr-2"></span>Tambah</button>
			</div>
			<div class="text-center" id="proses"></div>
			<div class="table-responsive py-4" id="content">
				<table class="table table-hover" id="data">
					<thead class="thead-light">
						<tr><th>Jenis Prestasi</th><th>Bukti Dokumen</th><th>Jenis Dokumen</th><th width="30px">Aksi</th></tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<? 
		}
		else{
			include "pages/petugas/dokumen_bukti/form.php";
		}
		?>
	</div>
</div>

<!-- <script src="assets/js/jquery-1.9.1.js"></script> -->
<script>
	$(function () {
		$('form').on('submit', function (e) {
			var id=$("#id_jns_dokumen").val();
			$("#proses").html("<span class='fas fa-spinner fa-spin'></span>");
			$('#modal-form').modal('hide');
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: 'pages/petugas/dokumen_bukti/proses.php',
				data: $('form').serialize(),
				success: function (a) {
					if(a==""){
						// $('#data').DataTable().ajax.reload();
						window.location.href='dokumen_bukti-'+id+'.html';
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

	function pilih(a){
		var jns=a.value;
		window.location.href="dokumen_bukti-"+jns+".html";
	}

	function edit(a){
		$.ajax({
			url: 'pages/petugas/dokumen_bukti/proses.php',
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
					url: 'pages/petugas/dokumen_bukti/proses.php',
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
		var id=$("#jenis").val();
		var dataTable = $('#data').DataTable( {
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": false,
			"deferRender": true,
			"ajax":{
				url: 'pages/petugas/dokumen_bukti/tabel.php?id='+id,
				type: "post",
				error: function(){
					$(".data-error").html("");
					$("#data").append('<tbody class="data-error"><tr><th colspan="8">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			"columnDefs": [
			// { "targets": [0], "className": "text-left", "width": "100px" },
			// { "targets": [2], "className": "text-left", "width": "30px" },
			],
		} 
		);
	} 
	);
</script>