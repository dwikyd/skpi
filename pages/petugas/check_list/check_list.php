<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-check"></span> Cek SKPI Wisudawan</h3>
	</div>
	<div class="card-body">
		<?
		$url[1]=!isset($url[1]) ? "" : $url[1];
		$url[2]=!isset($url[2]) ? "" : $url[2];
		if(!isset($url[3])){ ?>
			<div class="form-group row" style="padding-bottom: 10px;">
				<label class="col-sm-2 control-label text-right">Prodi </label>
				<div class="col-sm-10">
					<select class='form-control select' data-live-search='true' id="prodi" onchange="prodi(this);">
						<option value="">-- Semua Prodi --</option>
						<?
						$sql="SELECT kode_prodi, nama_prodi, jenjang FROM prodi WHERE kode_prodi in ($_SESSION[kode_prodi]) ORDER BY jenjang, kode_prodi";
						$jns=mysqli_query($koneksi_sikadu, $sql);
						while($d=mysqli_fetch_assoc($jns)){
							$sel=$url[1]== $d['kode_prodi'] ? "selected" : "";
							echo "<option value='$d[kode_prodi]' $sel>$d[jenjang] - $d[nama_prodi]</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group row" style="padding-bottom: 10px;">
				<label class="col-sm-2 control-label text-right">Wisuda Ke </label>
				<div class="col-sm-10">
					<select class='form-control select' data-live-search='true' id="wisuda_ke" onchange="pilih(this);">
						<option value="">-- Wisuda Ke --</option>
						<?
						$jns=mysqli_query($koneksi_sikadu, "SELECT wisuda_ke FROM wisuda_periode ORDER BY wisuda_ke DESC");
						while($d=mysqli_fetch_assoc($jns)){
							$sel=$url[2]== $d['wisuda_ke'] ? "selected" : "";
							echo "<option value='$d[wisuda_ke]' $sel>$d[wisuda_ke]</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div>
				<? if($url[2]!="") echo '<button class="btn btn-secondary text-dark mr-2" onclick="window.location.href=\'check_list-'.$url[1]."-".$url[2].'-tambah.html\';"><span class="fas fa-file-alt mr-2"></span>Tambah</button>'; ?>
			</div>
			<div class="text-center" id="proses"></div>
			<div class="table-responsive py-4" id="content">
				<table class="table table-hover" id="data">
					<thead class="thead-light">
						<tr><th>NIM</th><th>Nama</th><th>Akt</th><th width="30px">Wisuda Ke</th><th width="120px">HP / WA</th></tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<? 
		}
		else{
			include "pages/petugas/check_list/form.php";
		}
		?>
	</div>
</div>

<!-- <script src="assets/js/jquery-1.9.1.js"></script> -->
<script>
	$(function () {
		$('form').on('submit', function (e) {
			var pd=$("#kode_prodi").val();
			var bd=$("#id_bidang").val();
			$('#submit').attr("disabled", "disabled");
			$("#proses").html("<span class='fas fa-spinner fa-spin'></span>");
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: 'pages/petugas/check_list/proses.php',
				data: $('form').serialize(),
				success: function (a) {
					if(a==""){
						// $('#data').DataTable().ajax.reload();
						window.location.href='check_list-'+pd+'-'+bd+'.html';
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

	function prodi(a){
		var pd=a.value;
		var jns=$("#wisuda_ke").val();
		window.location.href="check_list-"+pd+"-"+jns+".html";
	}

	function pilih(a){
		var pd=$("#prodi").val();
		var jns=a.value;
		window.location.href="check_list-"+pd+"-"+jns+".html";
	}

	function edit(a){
		$.ajax({
			url: 'pages/petugas/check_list/proses.php',
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
					url: 'pages/petugas/check_list/proses.php',
					type: "POST",
					data: "aksi=delete&id="+a,
					success: function (data) {
						if(data==''){
							$('#data').DataTable().ajax.reload();
							Swal.fire(
								'Deleted!',
								'Your file has been deleted.',
								'success'
								)
						}
						else{
							Swal.fire(
								'Error!',
								data,
								'error'
								)	
						}
					}
				});
			}
		})
	}
</script>

<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		var pd=$("#prodi").val();
		var id=$("#wisuda_ke").val();
		var dataTable = $('#data').DataTable( {
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": false,
			"deferRender": true,
			"ajax":{
				url: 'pages/petugas/check_list/tabel.php?pd='+pd+'&wisuda_ke='+id,
				type: "post",
				error: function(){
					$(".data-error").html("");
					$("#data").append('<tbody class="data-error"><tr><th colspan="5">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			"columnDefs": [
			{ "targets": [0], "className": "text-left", "width": "120px" },
			{ "targets": [2], "className": "text-center", "width": "50px" },
			{ "targets": [3], "className": "text-center", "width": "70px" },
			{ "targets": [4], "className": "text-left", "width": "120px" },
			],
		} 
		);
	} 
	);
</script>