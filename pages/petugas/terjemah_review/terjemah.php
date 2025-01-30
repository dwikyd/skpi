<link href="assets/css/wysiwyg.css" rel="stylesheet">
<?
$sql="SELECT a.*, b.nama_dokumen, c.nama_prestasi, d.*, e.nama FROM prestasi a inner join jenis_dokumen b on a.id_jns_dokumen=b.id_jns_dokumen inner join bukti_dokumen c on a.id_bukti_dok=c.id_bukti_dok INNER JOIN prestasi_translate d ON a.id_prestasi=d.id_prestasi INNER JOIN skpi e ON a.nim=e.nim WHERE a.id_prestasi='$url[1]' AND d.jenis='$url[2]'";
$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
$array = explode('.', $d['file']);
$extension = strtolower(end($array));
?>
<form action="#" id="form" method="post">
	<input type="hidden" name="aksi" value="approval" id="aksi">
	<input type="hidden" name="id_prestasi" value="<?= $d['id_prestasi'] ?>">
	<input type="hidden" name="id_pegawai" value="<?= $url[3] ?>">
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
	<? if(empty($d['hasil_review'])){ ?>
		<div class="row form-group mb-3">
			<label class="col-2 text-right">Hasil Translate :</label>
			<div class="col-10">
				<?= $d['hasil_translate'] ?>
			</div>  
		</div>
		<input type="hidden" name="hasil" id="hasil">
		<div class="mb-3 text-center">
			<button class="btn btn-success btn-sm" onclick="simpan('S');" type="button">Setuju</button>
			<button class="btn btn-warning btn-sm" onclick="simpan('R');" type="button">Revisi</button>
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
			<label class="col-2 text-right">Hasil Review :</label>
			<div class="col-10">
				<?= $d['hasil_review'] ?>
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
	function simpan(a){
		$("#hasil").val(a);
		$('#form').submit();
	}

	$('#form').submit(function(evt) {
		evt.preventDefault();
		var nim=$("#nim").val();
		var formData = new FormData(this);

		$.ajax({
			type: 'POST',
			url: 'pages/petugas/terjemah_review/proses.php',
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