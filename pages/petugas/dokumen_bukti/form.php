<link href="assets/css/wysiwyg.css" rel="stylesheet">
<?
$sql="SELECT id_bukti_dok, id_jns_dokumen, nama_prestasi, bukti, jenis FROM bukti_dokumen WHERE id_bukti_dok='".str_replace("_", "#", $url[3])."'";
$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
?>
<form action="#" id="form" method="post">
	<input type="hidden" name="aksi" value="<?= $url[2] ?>" id="aksi">
	<input type="hidden" name="id_bukti_dok" value="<?= $d['id_bukti_dok'] ?>">
	<div class="form-group mb-4">
		<label>Jenis Dokumen</label>
		<div class="input-group">
			<select class="form-control" name="id_jns_dokumen" id="id_jns_dokumen" required>
				<option value=''>-- Pilih Jenis Dokumen --</option>
				<?
				$sqj=mysqli_query($koneksi_skpi, "SELECT id_jns_dokumen, nama_dokumen FROM jenis_dokumen ORDER BY id_jns_dokumen");
				while($j=mysqli_fetch_assoc($sqj)){
					$sel=$j['id_jns_dokumen']==$url[1] ? "selected" : "";
					echo "<option value='$j[id_jns_dokumen]' $sel>$j[nama_dokumen]</option>";
				}
				?>
			</select>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Jenis Prestasi</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Jenis Prestasi" name="nama_prestasi" id="nama_prestasi" rows="3" required autofocus><?= $d['nama_prestasi'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Bukti Dokumen</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Bukti Dokumen" name="bukti" id="bukti" rows="3" required><?= $d['bukti'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Jenis Dokumen</label>
		<div class="input-group">
			<input type="text" class="form-control" placeholder="Jenis Dokumen" name="jenis" id="jenis_bukti" required value="<?= $d['jenis'] ?>">
		</div>  
	</div>
	<button class="btn btn-success" name="submit" type="submit">Simpan</button>
	<button class="btn btn-danger" type="button" onclick="window.location.href='dokumen_bukti-<?= $url[1] ?>.html';">Batal</button>
</form>

<script src="assets/js/wysiwyg.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#nama_prestasi').wysiwyg({
			toolbar: [
			['mode'],
			['operations', ['undo','rendo','cut','copy','paste']],
			['styles'],
			['fonts', ['select','size']],
			['text', ['bold','italic','underline', 'font-color' /*,'subscript','superscript','bg-color'*/]],
			['align', ['left','center','right','justify']],
			['lists', ['unordered','ordered','indent','outdent']],
			['components', ['table',/*'chart'*/]],
			['intervals', ['line-height','letter-spacing']],
			['fullscreen'],
			],
		});

		$('#bukti').wysiwyg({
			toolbar: [
			['mode'],
			['operations', ['undo','rendo','cut','copy','paste']],
			['styles'],
			['fonts', ['select','size']],
			['text', ['bold','italic','underline', 'font-color' /*,'subscript','superscript','bg-color'*/]],
			['align', ['left','center','right','justify']],
			['lists', ['unordered','ordered','indent','outdent']],
			['components', ['table',/*'chart'*/]],
			['intervals', ['line-height','letter-spacing']],
			['fullscreen'],
			],
		});
	});
</script>