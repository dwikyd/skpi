<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-bullseye"></span> CP Prodi</h3>
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
						//WHERE kode_prodi in ($_SESSION[kode_prodi])
						$sql="SELECT kode_prodi, nama_prodi, jenjang FROM prodi ORDER BY jenjang, kode_prodi";
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
				<label class="col-sm-2 control-label text-right">Bidang </label>
				<div class="col-sm-10">
					<select class='form-control select' data-live-search='true' id="jenis" onchange="pilih(this);">
						<option value="">-- Semua Bidang --</option>
						<?
						$jns=mysqli_query($koneksi_skpi, "SELECT id_bidang, nama_bidang FROM cp_bidang ORDER BY id_bidang");
						while($d=mysqli_fetch_assoc($jns)){
							$sel=$url[2]== $d['id_bidang'] ? "selected" : "";
							echo "<option value='$d[id_bidang]' $sel>$d[nama_bidang]</option>";
						}
						?>
					</select>
				</div>
			</div>
			<div>
				<? if($url[2]!="") echo '<button class="btn btn-secondary text-dark mr-2" onclick="window.location.href=\'cp_prodi-'.$url[1]."-".$url[2].'-tambah.html\';"><span class="fas fa-file-alt mr-2"></span>Tambah</button>'; ?>
			</div>
			<div class="text-center" id="proses"></div>
			<div class="table-responsive py-4" id="content">
				<table class="table table-hover" id="datatable">
					<thead class="thead-light">
						<tr><th width="70px">NO</th><th width="30px">Aksi</th><th>CAPAIAN PEMBELAJARAN</th><th>LEARNING OUTCOMES</th></tr>
					</thead>
					<tbody>
						<?
						$where = $url[2]=="" ? "" : " AND id_bidang='$url[2]'";
						$sql=mysqli_query($koneksi_skpi, "SELECT * FROM cp_prodi WHERE kode_prodi='$url[1]' $where ORDER BY order_cp, tgl_upload, id_cp");
						$no=0;
						while($d=mysqli_fetch_assoc($sql)){
							$no++;
							if($d['order_cp']==0) mysqli_query($koneksi_skpi, "UPDATE cp_prodi SET order_cp=$no WHERE id_cp='$d[id_cp]'");
							$aksi='<div class="btn-group">
							<a class="btn btn-info btn-xs" href="cp_prodi-'.$d['kode_prodi'].'-'.$d['id_bidang'].'-edit-'.$d['id_cp'].'.html"><i class="fas fa-edit"></i></a>
							<a class="btn btn-danger btn-xs" href="#" onclick="hapus(\''.$d['id_cp'].'\');"><i class="fas fa-trash-alt"></i></a>
							</div>';
							echo "<tr><td>$no</td><td>$aksi</td><td>$d[in_cp]</td><td>$d[eng_cp]<br>$d[arb_cp]</td></tr>";
						}
						?>
					</tbody>
				</table>
			</div>
			<? 
		}
		else{
			include "pages/petugas/cp_prodi/form.php";
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
				url: 'pages/petugas/cp_prodi/proses.php',
				data: $('form').serialize(),
				success: function (a) {
					if(a==""){
						// $('#data').DataTable().ajax.reload();
						window.location.href='cp_prodi-'+pd+'-'+bd+'.html';
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
		var jns=$("#jenis").val();
		window.location.href="cp_prodi-"+pd+"-"+jns+".html";
	}

	function pilih(a){
		var pd=$("#prodi").val();
		var jns=a.value;
		window.location.href="cp_prodi-"+pd+"-"+jns+".html";
	}

	function edit(a){
		$.ajax({
			url: 'pages/petugas/cp_prodi/proses.php',
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
					url: 'pages/petugas/cp_prodi/proses.php',
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
		var id=$("#jenis").val();
		var dataTable = $('#data').DataTable( {
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": false,
			"deferRender": true,
			"ajax":{
				url: 'pages/petugas/cp_prodi/tabel.php?pd='+pd+'&id='+id,
				type: "post",
				error: function(){
					$(".data-error").html("");
					$("#data").append('<tbody class="data-error"><tr><th colspan="8">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			"columnDefs": [
			{ "targets": [0], "className": "text-left", "width": "45%" },
			{ "targets": [2], "className": "text-left", "width": "30px" },
			],
		} 
		);
	} 
	);
</script>