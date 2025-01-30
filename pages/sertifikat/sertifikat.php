<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-file"></span> Sertifikat Aktifitas, Prestasi dan Penghargaan</h3>
	</div>
	<div class="card-body">
		<?
		$url[1]=!isset($url[1]) ? "" : $url[1];
		$nim=isset($_SESSION['nim']) ? $_SESSION['nim'] : $_COOKIE['nim'];
	
		$mhs=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, b.nama_prodi, c.nama_peg FROM mhs a INNER JOIN prodi b ON a.kode_prodi=b.kode_prodi INNER JOIN pegawai c ON b.kaprodi=c.id_pegawai WHERE a.nim='$nim' AND a.kode_prodi in ($_SESSION[kode_prodi])"));
		if($mhs['nim']!=""){
			if(!isset($url[2]) && $url[1]!="pengajuan"){ ?>
				<div class="row">
					<div class="col-6">
						<div class="form-group row">
							<label class="col-sm-3 control-label text-right">NIM :</label>
							<div class="col-sm-9">
								<b><?= $nim ?></b>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-right">Nama :</label>
							<div class="col-sm-9">
								<b><?= $mhs['nama'] ?></b>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group row">
							<label class="col-sm-3 control-label text-right">Prodi :</label>
							<div class="col-sm-9">
								<b><?= $mhs['nama_prodi'] ?></b>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-3 control-label text-right">Kaprodi :</label>
							<div class="col-sm-9">
								<b><?= $mhs['nama_peg'] ?></b>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="form-group row mb-3">
					<label class="col-sm-2 control-label text-right">Kategori Sertifikat :</label>
					<div class="col-sm-10">
						<select class='form-control' data-live-search='true' id="jenis" onchange="pilih(this);">
							<option value="">-- Semua Sertifikat --</option>
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
				<div style="padding-bottom: 10px;">
					<?
					$sk=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT nim, finalisasi FROM skpi WHERE nim='$_SESSION[nim]'"));
					if(!empty($sk['nim'])){
						if($sk['finalisasi']=="N"){
							echo '<button class="btn btn-secondary text-dark mr-2 btn-sm mb-3" onclick="window.location.href=\'sertifikat-'.$url[1].'-tambah.html\';"><span class="fas fa-file-alt mr-2"></span>Tambah</button>';
							$pt=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT nim FROM prestasi WHERE nim='$_SESSION[nim]' AND STS_SKPI='Y'"));
							$dis=$pt>0 ? "" : "disabled";
							echo "<button class='btn btn-primary mr-2 btn-sm mb-3' onclick='window.location.href=\"sertifikat-pengajuan.html\";' $dis><span class='fas fa-paper-plane mr-2'></span>Finalisasi SKPI</button>";
							if($pt==0){
								echo '<div class="alert alert-secondary" role="alert">
								Untuk dapat melakukan <b>Finalisasi SKPI</b> harus ada minimal 1 (satu) sertifikat prestasi yang diakui sebagai prestasi dalam SKPI.
								</div>';
							}
						}
						else{
							echo '<div class="alert alert-warning" role="alert">
								<font style="color:black;">Anda sudah melakukan <b>Finalisasi SKPI</b> update data tidak dapat lagi dilakukan.</font>
								</div>';
						}
					}
					?>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered mb-0 rounded">
						<thead class="thead-light">
							<tr>
								<th class="p-2" width="75px">No.</th>
								<th class="p-2" width="130px">Tgl. Sertifikat</th>
								<th class="p-2">Capaian Pembelajaran</th>
								<th class="p-2" width="80px">Status</th>
								<th class="p-2" width="120px">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?
							$where=$url[1]=="" ? "" : " AND a.id_jns_dokumen='$url[1]'";
							$sql="SELECT a.*, b.nama_dokumen FROM prestasi a inner join jenis_dokumen b on a.id_jns_dokumen=b.id_jns_dokumen WHERE a.nim='$nim' $where ORDER BY a.id_jns_dokumen, a.id_prestasi";
							$sql=mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));

							$no=0;
							$bidang="";
							while($d=mysqli_fetch_assoc($sql)){
								if($bidang!=$d['id_jns_dokumen']){
									$no=0;
									$bidang=$d['id_jns_dokumen'];
									echo "<tr><td class='p-2' class='p-2' colspan=5><b>$d[nama_dokumen]</b></td></tr>";
								}
								$language=$d['sts_translate']=="Y" ? "<span class='badge badge-md bg-info'><i class='fa fa-language'></i></span>" : "";
								$skpi=$d['sts_skpi']=="Y" ? "<span class='badge badge-md bg-danger'><i class='fa fa-check'></i></span>" : "";
								$no++;
								$update="";
								if($d['sts_prestasi']!='Setuju' && $d['sts_prestasi']!='Pengajuan'){
									$update="<a href='sertifikat-$d[id_jns_dokumen]-edit-$d[id_prestasi].html' class='btn btn-info btn-xs'><i class='fas fa-edit'></i></a>";
									$update.="<button class='btn btn-warning btn-xs' onclick='hapus(\"$d[id_prestasi]\");'><i class='fas fa-trash'></i></button>";
									$update.="<button class='btn btn-primary btn-xs' onclick='submit(\"$d[id_prestasi]\");'><i class='fas fa-paper-plane'></i></button>";
								}
								else{
									$update.="<a href='#' data-toggle='modal' data-target='#modal-progress' onclick='progress(\"$d[id_prestasi]\");' class='btn btn-primary btn-xs'><i class='fas fa-map-marker'></i></a>";
								}
								$aksi="<div class='btn-group'>";
								if(file_exists("file_prestasi/$d[nim]/$d[file]")) $aksi.="<a href='file_prestasi/$d[nim]/$d[file]' target='_blank' class='btn btn-success btn-xs'><i class='fas fa-download'></i></a>";
								$aksi.="$update</div>";
								echo "<tr><td class='p-2' width=70px>$no</td><td class='p-2'>".indonesia($d['tgl_prestasi'])."</td><td class='p-2'>$d[in_prestasi]<br>$d[eng_prestasi]<br>$d[arb_prestasi]</td><td class='p-2'>$d[sts_prestasi] <div class='btn-group'>$language $skpi</div></td><td class='p-2' align=center nowrap>$aksi</td></tr>";
							}
							?>
						</tbody>
					</table>
				</div>
				<? 
			}
			elseif($url[1]=="pengajuan"){
				include "pages/sertifikat/pengajuan.php";
			}
			else{
				include "pages/sertifikat/form.php";
			}
		}
		else{
			echo "Anda tidak berhak mengakses mahasiswa di prodi ini. ";
			if(isset($_SESSION['id_pegawai'])){
				echo "Silahkan pilih mahasiswa di menu <a href='progres.html'><b>Progres Pengajuan</b></a>";
			}
		}
		?>
	</div>
</div>

<!-- <script src="assets/js/jquery-1.9.1.js"></script> -->
<script>
	function pilih(a){
		var jns=a.value;
		window.location.href="sertifikat-"+jns+".html";
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
			text: "Data Prestasi dengan ID "+a+" akan dihapus.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, hapus !'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'pages/sertifikat/proses.php',
					type: "POST",
					data: "aksi=delete&id="+a,
					success: function (data) {
						if(data==''){
							Swal.fire(
								'Deleted!',
								'Your file has been deleted.',
								'success'
								);
							window.location.reload();	
						}
						else{
							Swal.fire(
								'Error!',
								data,
								'error'
								);
						}
					}
				});
			}
		})
	}

	function submit(a){
		Swal.fire({
			title: 'Anda yakin ?',
			text: "Data Prestasi dengan ID "+a+" akan disubmit.",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, submit !'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'pages/sertifikat/proses.php',
					type: "POST",
					data: "aksi=submit_prestasi&id="+a,
					success: function (data) {
						if(data==''){
							Swal.fire({
								title: 'Success',
								text: "Data Prestasi berhasil disubmit.",
								icon: 'success',
								showCancelButton: false,
								confirmButtonColor: '#3085d6',
								cancelButtonColor: '#d33',
								confirmButtonText: 'Oke'
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.reload();
								}
							})
						}
						else{
							Swal.fire(
								'Error!',
								data,
								'error'
								);
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