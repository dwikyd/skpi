<link href="assets/css/wysiwyg.css" rel="stylesheet">
<?
$sql="SELECT a.*, b.nama_prestasi, c.nama_dokumen FROM prestasi a INNER JOIN bukti_dokumen b ON a.id_bukti_dok=b.id_bukti_dok INNER JOIN jenis_dokumen c ON b.id_jns_dokumen=c.id_jns_dokumen WHERE a.id_prestasi='$url[1]'";
$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
?>

<form action="pages/translate/proses.php" id="form" method="post" enctype="multipart/form-data"> 
	<input type="hidden" name="aksi" value="<?= $url[2] ?>" id="aksi">
	<input type="hidden" name="id_prestasi" value="<?= $d['id_prestasi'] ?>">
	<div class="form-group mb-4">
		<label>Bidang</label>
		<div class="input-group">
			<?= $d['nama_dokumen'] ?>
		</div>  
	</div>

	<div class="form-group mb-4">
		<label>Jenis Prestasi</label>
		<div class="input-group">
			<?= $d['nama_prestasi'] ?>
		</div>
	</div>
	<div class="form-group mb-4">
		<label>Uraian dalam Bahasa Indonesia</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Uraian prestasi" name="in_prestasi" id="in_prestasi" rows="3" required><?= $d['in_prestasi'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Uraian dalam Bahasa Inggris</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Uraian prestasi" name="eng_prestasi" id="eng_prestasi" rows="3" required><?= $d['eng_prestasi'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Uraian dalam Bahasa Arab</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Uraian prestasi" name="arb_prestasi" id="arb_prestasi" rows="3" required><?= $d['arb_prestasi'] ?></textarea>
		</div>  
	</div>
	<button class="btn btn-success" name="submit" id="submit" type="submit">Simpan</button>
	<button class="btn btn-danger" type="button" onclick="window.close();">Tutup</button>
	<p>Semua isian wajib diisi, jika tombol <b>Simpan</b> tidak bisa di Klik pastikan isian sudah lengkap.</p>
	<div class="row form-group mb-4">
		<div class="col-8">
			<label>File Bukti</label>
			<div class="input-group">
				<input type="file" class="form-control" name="file" id="file" <?= $url[2]=="tambah" ? "required" : "" ?>>
			</div>  
		</div>
		<span id="keterangan"></span>
		<?
		$file="file_prestasi/".$d['nim']."/".$d['file'];
		$array = explode('.', $d['file']);
		$extension = strtolower(end($array));
		if(file_exists($file) && $d['file']!="") {
			if($extension=="pdf"){
				echo "<embed src='$file#toolbar=0&navpanes=0&scrollbar=0' type='application/pdf' width='100%' height='600px' />";
			}
			else echo "<img src='$file' width='100%'>";
		}
		else echo "<p>Tidak ada file pendukung</p>";
		?>
	</div>
</form>

<script src="assets/js/wysiwyg.js"></script>
<!-- <script src="assets/js/jquery-1.11.2.min.js"></script> -->


<script type="text/javascript">
	function tanggal(a){
		var tanggal = a.value;
		$.ajax({
			url: 'pages/sertifikat/proses.php',
			type: "POST",
			data: "aksi=cek_tanggal&tanggal="+tanggal,
			success: function (data) {
				if(data!=''){
					Swal.fire('Error!', 'Tanggal tidak boleh melebihi tanggal hari ini !', 'error');
					$("#tgl_prestasi").val(data);
				}
			}
		});
	}


	function bidang(a){
		$("#bukti").html("<i class='fas fa-spinner fa-spin'></i> Loading....");
		var bd=a.value;
		$.ajax({
			url: 'pages/sertifikat/proses.php',
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
			url: 'pages/sertifikat/proses.php',
			type: "POST",
			data: "aksi=bukti&id="+bk,
			success: function (data) {
				$("#keterangan").html(data);
			}
		});
	}

	// $('#form').submit(function(evt) {
	// 	evt.preventDefault();

	// 	var formData = new FormData(this);

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: $(this).attr('action'),
	// 		data:formData,
	// 		cache:false,
	// 		contentType: false,
	// 		processData: false,
	// 		success: function(data) {
	// 			alert(berhasil);
	// 			// $('#imagedisplay').html("<img src=" + data.url + "" + data.name + ">");
	// 		},
	// 		error: function(data) {
	// 			alert(data);
	// 			// $('#imagedisplay').html("<h2>this file type is not supported</h2>");
	// 		}
	// 	});
	// });

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

		$('#arb_prestasi').wysiwyg({
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