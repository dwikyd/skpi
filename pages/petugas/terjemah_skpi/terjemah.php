<link href="assets/css/wysiwyg.css" rel="stylesheet">
<?
$sql="SELECT a.*, b.nama_dokumen, c.nama_prestasi, d.*, e.nama FROM prestasi a inner join jenis_dokumen b on a.id_jns_dokumen=b.id_jns_dokumen inner join bukti_dokumen c on a.id_bukti_dok=c.id_bukti_dok INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi INNER JOIN skpi e ON a.nim=e.nim WHERE a.id_prestasi='$url[1]' AND d.id_pegawai='$url[2]'";
$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
$array = explode('.', $d['file']);
$extension = strtolower(end($array));
?>
<form action="#" id="form" method="post">
	<input type="hidden" name="aksi" value="approval" id="aksi">
	<input type="hidden" name="id_prestasi" value="<?= $d['id_prestasi'] ?>">
	<input type="hidden" name="id_pegawai" value="<?= $d['id_pegawai'] ?>">
	<input type="hidden" name="jenis" value="<?= $d['jenis'] ?>">
	<div class="row form-group mb-3">
		<label class="col-2 text-right">NIM :</label>
		<div class="col-3">
			<b><?= $d['nim'] ?></b>
		</div>
		<label class="col-1 text-right">Nama :</label>
		<div class="col-6">
			<?= $d['nama'] ?>
		</div>  
	</div>
	<div class="row form-group mb-3">
		<label class="col-2 text-right">Bidang :</label>
		<div class="col-10">
			<?= $d['nama_dokumen'] ?>
		</div>  
	</div>

	<div class="row form-group mb-3">
		<label class="col-2 text-right">Jenis Prestasi :</label>
		<div class="col-10">
			<?= strip_tags($d['nama_prestasi']) ?>
		</div>
	</div>
	<div class="row form-group mb-3">
		<label class="col-2 text-right">Uraian :</label>
		<div class="col-10">
			<?= $d['in_prestasi'] ?>
		</div>  
	</div>
	<div class="row form-group mb-3">
		<label class="col-2 text-right">Translate Awal :</label>
		<div class="col-10">
			<?= $d['jenis'] == "I" ? $d['eng_prestasi'] : $d['arb_prestasi'] ?>
		</div>  
	</div>
	<? if($d['status_review']=="B"){ ?>
		<div class="form-group mb-3">
			<label>Hasil Terjemah <?= $d['jenis']=="I" ? "Inggris" : "Arab" ?></label>
			<div class="input-group">
				<textarea class="form-control" style="display: none;" placeholder="Bukti Dokumen" name="arti" id="arti" rows="3" ><?= $d['hasil_translate'] ?></textarea>
			</div>  
		</div>
		<div class="mb-3">
			<button class="btn btn-success btn-sm" name="submit" type="submit">Simpan</button>
			<button class="btn btn-danger btn-sm" type="button" onclick="window.close();">Tutup</button>
		</div>
	<? } else { ?>
		<div class="row form-group mb-3">
			<label class="col-2 text-right">Hasil Translate :</label>
			<div class="col-10">
				<?= $d['hasil_translate'] ?>
			</div>  
		</div>
		<div class="row form-group mb-3">
			<label class="col-2 text-right">Tanggal Translate :</label>
			<div class="col-4">
				<?= tgl_indo($d['tgl_translate']).substr($d['tgl_translate'], 10, 9) ?>
			</div> 
			<label class="col-2 text-right">Tanggal Review :</label>
			<div class="col-4">
				<?
				echo tgl_indo($d['tgl_review']).substr($d['tgl_review'], 10, 9);
				if(!empty($d['id_reviewer'])){
					$ref=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM reviewer WHERE id_pegawai='$d[id_reviewer]'"));
					echo "<br>[ $ref[nama_lengkap] ]";
				}
				?>
			</div>  
		</div>
		<div class="mb-3">
			<button class="btn btn-danger btn-sm" type="button" onclick="window.close();">Tutup</button>
		</div>
	<? } ?>
	<div class="form-group mb-3">
		<label>File Bukti</label>
		<div class="input-group">
			<?
			$file="file_prestasi/".$d['nim']."/".$d['file'];
			if(file_exists($file) && $d['file']!="") {
				if($extension=="pdf"){
					echo "<embed src='$file#toolbar=0&navpanes=0&scrollbar=0' type='application/pdf' width='100%' height='600px' />";
				}
				else echo "<img src='$file' width='100%'>";
			}
			else echo "<p>Tidak ada file pendukung</p>";
			?>
		</div>  
	</div>
</form>

<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script src="assets/js/wysiwyg.js"></script>
<script type="text/javascript">
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
			url: 'pages/petugas/terjemah_skpi/proses.php',
			data:formData,
			cache:false,
			contentType: false,
			processData: false,
			success: function(data) {
				if(data==""){
					window.close();
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

	$(document).ready(function () {
		$('#arti').wysiwyg({
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