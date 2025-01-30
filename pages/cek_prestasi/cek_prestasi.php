<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-certificate"></span> Cek Sertifikat Aktifitas, Prestasi dan Penghargaan</h3>
	</div>
	<div class="card-body">
		<?
		$url[1]=!isset($url[1]) ? "" : $url[1];
		$prodi =$_POST['prodi'];
		$status=isset($_POST['status']) ? $_POST['status'] : "Pengajuan";
		if(!isset($url[2]) && $url[1]!="pengajuan"){ ?>
			<form class="form-horizontal mb-3" method="post">
				<div class="form-group row mb-3">
					<label class="col-sm-1 control-label text-right">Prodi </label>
					<div class="col-sm-8">
						<select class='form-control select' data-live-search='true' name='prodi' id="prodi" onchange="submit();">
							<option value="">-- Semua Prodi --</option>
							<?
							$sql="SELECT kode_prodi, singkat_prodi, nama_prodi, jenjang FROM prodi WHERE kode_prodi in ($_SESSION[kode_prodi]) ORDER BY jenjang, kode_prodi";

							$jns=mysqli_query($koneksi_sikadu, $sql);
							while($d=mysqli_fetch_assoc($jns)){
								$sel=$prodi== $d['kode_prodi'] ? "selected" : "";
								echo "<option value='$d[kode_prodi]' $sel>$d[nama_prodi] ($d[jenjang] - $d[singkat_prodi])</option>";
							}
							?>
						</select>
					</div>
					<label class="col-sm-1 control-label text-right">Status </label>
					<div class="col-sm-2">
						<select class='form-control select' data-live-search='true' name='status' id="status" onchange="submit();">
							<option value="">-- Semua --</option>
							<?
							$sts=array('Pengajuan', 'Setuju', 'Tolak', 'Revisi');

							foreach($sts as $k){
								$sel=$status== $k ? "selected" : "";
								echo "<option value='$k' $sel>$k</option>";
							}
							?>
						</select>
					</div>
				</div>
			</form>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover mb-0 rounded" id="data">
					<thead class="thead-light">
						<tr>
							<th width="30px">No.</th>
							<th width="80px">Tgl. Submit</th>
							<th width="80px">NIM</th>
							<th>Capaian Pembelajaran</th>
							<th width="80px">Status</th>
							<th width="40px">Aksi</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<? 
		}
		else{
			include "pages/sertifikat/form.php";
		}
		?>
	</div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-form" role="dialog" aria-labelledby="modal-form-signup" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card border-light p-4">
					<form action="pages/cek_prestasi/proses.php" id="form" method="post">
						<div class="card-header pb-0">
							<h2><span class="fas fa-certificate"></span> Validasi Prestasi</h2>                               
						</div>
						<div class="card-body">
							<input type="hidden" name="aksi" value="update" id="aksi">
							<input type="hidden" name="id_prestasi" value="" id="id_prestasi">
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label text-right">NIM </label>
								<div class="col-sm-3">
									<input type="text" class="form-control" placeholder="NIM" name="nim" id="nim" required readonly>
								</div>  
								<div class="col-sm-7">
									<input type="text" maxlength="2" class="form-control" placeholder="Nama" name="nama" id="nama" readonly>
								</div>  
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label text-right">Prodi</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Nama Prodi" name="prodi" id="nama_prodi" readonly>
								</div>  
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label text-right">Jenis</label>
								<div class="col-sm-10">
									<select class="form-control" name="id_bukti_dok" id="id_bukti_dok" style="width:100%;">	
										<?
										$jns=mysqli_query($koneksi_skpi, "SELECT a.id_bukti_dok, a.nama_prestasi, b.nama_dokumen, a.id_jns_dokumen FROM bukti_dokumen a INNER JOIN jenis_dokumen b ON a.id_jns_dokumen=b.id_jns_dokumen ORDER BY a.id_jns_dokumen");
										$jenis="";
										while($d=mysqli_fetch_assoc($jns)){
											if($d['id_jns_dokumen']!=$jenis){
												if(!empty($jenis)) echo "</optgroup>";
												$jenis=$d['id_jns_dokumen'];
												echo "<optgroup label='$d[nama_dokumen]'>";
											}
											echo "<option value='$d[id_bukti_dok]'>$d[nama_prestasi]</option>";
										}
										echo "</optgroup>";
										?>
									</select>
								</div>  
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label text-right">Uraian</label>
								<div class="col-sm-10">
									<textarea class="form-control" placeholder="Uraian" name="in_prestasi" id="in_prestasi" rows="4" required></textarea>
									<!-- <textarea class="form-control" style="display: none;" placeholder="Uraian prestasi" name="in_prestasi" id="in_prestasi" rows="3" required><?= $d['in_prestasi'] ?></textarea> -->
								</div>  
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label text-right">Tgl Prestasi</label>
								<div class="col-sm-4">
									<input type="date" class="form-control" placeholder="Tanggal" name="tgl_prestasi" id="tgl_prestasi" required>
								</div>  
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label text-right">Masuk SKPI</label>
								<div class="col-sm-4">
									<select class="form-control" name="sts_skpi" id="sts_skpi">	
										<option value="Y">Termasuk</option>
										<option value="N">Tidak Termasuk</option>
									</select>
								</div>
								<label class="col-sm-2 control-label text-right">Status</label>
								<div class="col-sm-4">
									<select class="form-control" name="sts_prestasi" id="sts_prestasi">	
										<?
										$sts=array('Pengajuan','Setuju','Tolak','Revisi');
										foreach ($sts as $k) {
											echo "<option value='$k'>$k</option>";
										}
										?>
									</select>
								</div>  
							</div>
							<div class="form-group row mb-3">
								<label class="col-sm-2 control-label text-right">Catatan</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" placeholder="Berikan catatan jika diperlukan" name="catatan" id="catatan">
								</div>  
							</div>
						</div>
						<div class="card-footer">
							<button class="btn btn-success" name="submit" type="submit">Simpan</button>
							<button class="btn btn-danger" type="button" data-dismiss="modal">Tutup</button>
							<div id="bukti" style="padding:5px;"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End of Modal Content -->

<!-- <script src="assets/js/jquery-1.9.1.js"></script> -->
<!-- <script src="assets/js/wysiwyg.js"></script> -->
<script type="text/javascript" language="javascript" >
	document.addEventListener('visibilitychange', onVisibilityChange);
	function onVisibilityChange() {
		if (document.visibilityState === 'visible') {
			$('#data').DataTable().ajax.reload(null, false);
		} else {
      		// alert("user left the page 2");
		}
	}

	$(document).ready(function() {
		var pd=$("#prodi").val();
		var st=$("#status").val();
		var dataTable = $('#data').DataTable( {
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": false,
			"deferRender": true,
			"ajax":{
				url: 'pages/cek_prestasi/tabel.php?prodi='+pd+'&status='+st,
				type: "post",
				error: function(){
					$(".data-error").html("");
					$("#data").append('<tbody class="data-error"><tr><th colspan="8">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			// "columnDefs": [
			// 	{ "targets": [0], "className": "text-left", "width": "45%" },
			// 	{ "targets": [2], "className": "text-left", "width": "30px" },
			// 	],
		});
	});

	function validasi(a){
		$.ajax({
			url: 'pages/cek_prestasi/proses.php',
			type: "POST",
			data: "aksi=cari&id_prestasi="+a,
			dataType: "JSON",
			success: function (data) {
				$("#id_prestasi").val(data.id_prestasi);
				$("#nim").val(data.nim);
				$("#nama").val(data.nama);
				$("#nama_prodi").val(data.nama_prodi);
				$("#id_bukti_dok").val(data.id_bukti_dok);
				$("#sts_skpi").val(data.sts_skpi);
				$("#sts_prestasi").val(data.sts_prestasi);
				$("#in_prestasi").val(data.in_prestasi);
				$("#tgl_prestasi").val(data.tgl_prestasi);
				$("#catatan").val(data.catatan);
				$("#bukti").html(data.file);
			}
		});
	}

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
			success: function(data) {
				if(data!=""){
					alert(data);
				}
				else{
					$('#modal-form').modal('hide');
					$('#data').DataTable().ajax.reload(null, false);
				}
			},
			error: function(data) {
				alert(data);
			}
		});
	});

	// $(document).ready(function () {
	// 	$('#in_prestasi').wysiwyg({
	// 		toolbar: [
	// 			['mode'],
	// 			['operations', ['undo','rendo','cut','copy','paste']],
	// 			['styles'],
	// 			['fonts', ['select','size']],
	// 			['text', ['bold','italic','underline', 'font-color' ,'subscript','superscript']],
	// 			['align', ['left','center','right','justify']],
	// 			['lists', ['unordered','ordered','indent','outdent']],
	// 		['components', ['table',/*'chart'*/]],
	// 			['intervals', ['line-height','letter-spacing']],
	// 			['fullscreen'],
	// 			],
	// 	});
	// });
</script>