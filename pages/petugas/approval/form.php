<link href="assets/css/wysiwyg.css" rel="stylesheet">
<?
$sql="SELECT a.*, b.nama_dokumen, c.nama_prestasi FROM prestasi a inner join jenis_dokumen b on a.id_jns_dokumen=b.id_jns_dokumen inner join bukti_dokumen c on a.id_bukti_dok=c.id_bukti_dok WHERE a.id_prestasi='$url[2]'";
$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
$array = explode('.', $d['file']);
$extension = strtolower(end($array));
?>
<form action="#" id="form" method="post">
	<input type="hidden" name="aksi" value="approval" id="aksi">
	<input type="hidden" name="nim" value="<?= $url[1] ?>" id="nim">
	<input type="hidden" name="id_prestasi" value="<?= $d['id_prestasi'] ?>">
	<input type="hidden" name="approval" value="<?= $approval ?>">
	<div class="row form-group mb-4">
		<label class="col-2">Bidang</label>
		<div class="col-10">
			: <?= $d['nama_dokumen'] ?>
		</div>  
	</div>

	<div class="row form-group mb-4">
		<label class="col-2">Jenis Prestasi</label>
		<div class="col-10">
			: <?= strip_tags($d['nama_prestasi']) ?>
		</div>
	</div>
	<div class="form-group mb-4">
		<label>Uraian dalam Bahasa Indonesia</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Capaian Pembelajaran" name="in_prestasi" id="in_prestasi" rows="3" required><?= $d['in_prestasi'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Uraian dalam Bahasa Inggris</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Bukti Dokumen" name="eng_prestasi" id="eng_prestasi" rows="3" required><?= $d['eng_prestasi'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>File Bukti</label>
		<div class="input-group">
			<?
			$file="file_prestasi/".$d['nim']."/".$d['file'];
			if(file_exists($file) && $d['file']!="") {
				if($extension=="pdf"){
					echo "<embed src='$file#toolbar=0&navpanes=0&scrollbar=0' type='application/pdf' width='100%' height='600px' />";
				}
				?>
				<span id="derajat" style="display: none;">0</span>
				<button id="rotate" class="btn btn-info m-3" type="button" onclick="rotateImage()">Rotate image</button>
				<div class="table-responsive">
					<img src="<?= $file ?>" id="file_prestasi"> 
				</div>
				<?
			}
			else echo "<p>Tidak ada file pendukung</p>";
			?>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Rekomendasi</label>
		<div class="input-group">
			<select class="form-control" name="rekomendasi" id="rekomendasi">
				<?
				if($approval=="operator") $sts=array('Diproses','Disetujui','Revisi');
				elseif($approval=="kasub") $sts=array('Pengajuan','Diproses','Disetujui','Revisi');
				elseif($approval=="kabag") $sts=array('Pengajuan','Diproses','Disetujui','Revisi');
				elseif($approval=="prodi") $sts=array('Pengajuan','Diproses','Disetujui','Revisi','Ditolak');
				else $sts="-";
				if(is_array($sts)){
					foreach ($sts as $k) {
						$sel=$d["sts_".$approval]==$k ? "selected" : "";
						echo "<option value='$k' $sel>$k</option>";
					}
				}
				?>
			</select>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Catatan</label>
		<div class="input-group">
			<input class="form-control" placeholder="Catatan" type="text" name="catatan" id="catatan" value="<?= $d['catatan_'.$approval] ?>">
		</div>  
	</div>
	<button class="btn btn-success" name="submit" type="submit">Simpan</button>
	<button class="btn btn-danger" type="button" onclick="window.location.href='approval-<?= $url[1] ?>.html';">Batal</button>
</form>

<script src="assets/js/wysiwyg.js"></script>
<script type="text/javascript">
	const rotated = document.getElementById("file_prestasi");

	function rotateImage() {
		var derajat=parseInt($("#derajat").html());
		der=derajat+90;
		if(der==360){ der=0; }
		$("#derajat").html(der);
		rotated.style.transform = "rotate("+der+"deg)";
	}
	
	function bidang(a){
		$("#bukti").html("<i class='fas fa-spinner fa-spin'></i> Loading....");
		var bd=a.value;
		$.ajax({
			url: 'pages/mahasiswa/sertifikat/proses.php',
			type: "POST",
			data: "aksi=bidang&id="+bd,
			success: function (data) {
				$("#bukti").html(data);
			}
		});
	}

	function bukti(a){
		$("#keterangan").html("<i class='fas fa-spinner fa-spin'></i> Loading....");
		var bk=a.value;
		$.ajax({
			url: 'pages/mahasiswa/sertifikat/proses.php',
			type: "POST",
			data: "aksi=bukti&id="+bk,
			success: function (data) {
				$("#keterangan").html(data);
			}
		});
	}

	$('#form').submit(function(evt) {
		evt.preventDefault();
		var nim=$("#nim").val();
		var formData = new FormData(this);

		$.ajax({
			type: 'POST',
			url: 'pages/petugas/approval/proses.php',
			data:formData,
			cache:false,
			contentType: false,
			processData: false,
			success: function(data) {
				if(data==""){
					window.location.href="approval-"+nim+".html";
				}
				else{
					Swal.fire('Error!',data,'error');
				}
			},
			error: function(data) {
				Swal.fire('Error!',data,'error');
			}
		});
	});

	// $(function () {
	// 	$('form').on('submit', function (e) {
	// 		var jenis=$("#id_jns_dokumen").val();
	// 		var bukti=$("#id_bukti_dok").val();
	// 		// var in=$("#in_prestasi").val();
	// 		// var en=$("#eng_prestasi").val();

	// 		if(bukti==""){
	// 			Swal.fire('Error!', 'Prestasi belum dipilih !', 'error');
	// 			return(false);
	// 		}
	// 		$('#submit').attr("disabled", "disabled");
	// 		$("#proses").html("<span class='fas fa-spinner fa-spin'></span>");
	// 		e.preventDefault();
	// 		var datatosend = $('#form').serialize()+ '&file='+new FormData('#form');
	// 		alert(datatosend);
	// 		$.ajax({
	// 			type: 'post',
	// 			url: 'pages/mahasiswa/sertifikat/proses.php',
	// 			data: $('form').serialize(),
	// 			success: function (a) {
	// 				if(a==""){
	// 					window.location.href='sertifikat-'+jenis+'.html';
	// 				}
	// 				else{
	// 					Swal.fire(a, '', 'error');
	// 					$('#submit').removeAttr('disabled');
	// 				}
	// 				$("#proses").html("");
	// 			}
	// 		});
	// 	});

	// });

	$(document).ready(function () {
		$('#in_prestasi').wysiwyg({
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

		$('#eng_prestasi').wysiwyg({
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
</script>