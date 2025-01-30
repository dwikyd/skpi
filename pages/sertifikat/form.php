<link href="assets/css/wysiwyg.css" rel="stylesheet">
<?
if(!isset($url[3])) $url[3]="";
$sql="SELECT * FROM prestasi WHERE id_prestasi='$url[3]'";
$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
// if(empty($d['tgl_prestasi'])) $d['tgl_prestasi']=date("Y-m-d");
?>

<form action="pages/sertifikat/proses.php" id="form" method="post" enctype="multipart/form-data"> 
	<input type="hidden" name="aksi" value="<?= $url[2] ?>" id="aksi">
	<input type="hidden" name="id_prestasi" value="<?= $d['id_prestasi'] ?>">
	<div class="form-group mb-4">
		<label>Bidang</label>
		<div class="input-group">
			<select class="form-control" name="id_jns_dokumen" id="id_jns_dokumen" required onchange="bidang(this);">
				<option value=''>-- Bidang --</option>
				<?
				$jns=mysqli_query($koneksi_skpi, "SELECT id_jns_dokumen, nama_dokumen FROM jenis_dokumen ORDER BY id_jns_dokumen");
				while($bd=mysqli_fetch_assoc($jns)){
					$sel=$url[1]== $bd['id_jns_dokumen'] ? "selected" : "";
					echo "<option value='$bd[id_jns_dokumen]' $sel>$bd[nama_dokumen]</option>";
				}
				?>
			</select>
		</div>  
	</div>

	<div class="form-group mb-4">
		<label>Jenis Prestasi</label>
		<div class="input-group" id="bukti">
			<select class="form-control" name="id_bukti_dok" id="id_bukti_dok" required onchange="bukti(this);">
				<option value=''>-- Jenis Prestasi --</option>
				<?
				$jns=mysqli_query($koneksi_skpi, "SELECT id_jns_dokumen, nama_prestasi, id_bukti_dok FROM bukti_dokumen WHERE id_jns_dokumen='$url[1]' ORDER BY id_bukti_dok");
				while($bd=mysqli_fetch_assoc($jns)){
					$sel=$bd['id_bukti_dok']== $d['id_bukti_dok'] ? "selected" : "";
					echo "<option value='$bd[id_bukti_dok]' $sel>$bd[nama_prestasi]</option>";
				}
				?>
			</select>
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
			<textarea class="form-control" style="display: none;" placeholder="Uraian prestasi" name="eng_prestasi" id="eng_prestasi" rows="3"><?= $d['eng_prestasi'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Uraian dalam Bahasa Arab</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Uraian prestasi" name="arb_prestasi" id="arb_prestasi" rows="3"><?= $d['arb_prestasi'] ?></textarea>
		</div>  
	</div>
	<div class="row form-group mb-4">
		<div class="col-4">
			<label>Tgl. Penerbitan Sertifikat</label>
			<div class="input-group">
				<input type="date" class="form-control" name="tgl_prestasi" id="tgl_prestasi" required value="<?= $d['tgl_prestasi'] ?>" onblur="tanggal(this);">
			</div>  
		</div>
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
			else {
				?>
				<span id="derajat" style="display: none;">0</span>
				<button id="rotate" class="btn btn-info m-3" type="button" onclick="rotateImage()">Rotate image</button>
				<div class="table-responsive">
					<img src="<?= $file ?>" id="file_prestasi"> 
				</div>
				<?
			}
		}
		else echo "<p>Tidak ada file pendukung</p>";
		?>
	</div>
	<div class="form-group mb-4">
		<label>Catatan</label>
		<div class="input-group">
			<?
			if($d['catatan_operator']!= "") echo $d['catatan_operator']."<br>";
			if($d['catatan_kasub']!= "") echo $d['catatan_kasub']."<br>";
			if($d['catatan_kabag']!= "") echo $d['catatan_kabag']."<br>";
			if($d['catatan_prodi']!= "") echo $d['catatan_prodi']."<br>";
			?>
		</div>  
	</div>
	<button class="btn btn-success" name="submit" id="submit" type="submit">Simpan</button>
	<button class="btn btn-danger" type="button" onclick="window.location.href='sertifikat-<?= $url[1] ?>.html';">Batal</button>
	<p>Semua isian wajib diisi, jika tombol <b>Simpan</b> tidak bisa di Klik pastikan isian sudah lengkap.</p>
</form>

<script src="assets/js/wysiwyg.js"></script>
<!-- <script src="assets/js/jquery-1.11.2.min.js"></script> -->


<script type="text/javascript">
	const rotated = document.getElementById("file_prestasi");

	function rotateImage() {
		var derajat=parseInt($("#derajat").html());
		der=derajat+90;
		if(der==360){ der=0; }
		$("#derajat").html(der);
		rotated.style.transform = "rotate("+der+"deg)";
	}
	
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