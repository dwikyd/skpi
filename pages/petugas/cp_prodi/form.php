<link href="assets/css/wysiwyg.css" rel="stylesheet">
<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>

<?
if(!isset($url[4])) $url[4]="";
$sql="SELECT * FROM cp_prodi WHERE id_cp='$url[4]'";
$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
if(empty($d['order_cp'])) $d['order_cp']=1;
?>
<form action="#" id="form" method="post">
	<input type="hidden" name="aksi" value="<?= $url[3] ?>" id="aksi">
	<input type="hidden" name="id_cp" value="<?= $d['id_cp'] ?>">
	<input type="hidden" name="kode_prodi" id="kode_prodi" value="<?= $url[1] ?>">
	<div class="form-group mb-4">
		<label>Bidang</label>
		<div class="input-group">
			<select class="form-control" name="id_bidang" id="id_bidang" required>
				<option value=''>-- Bidang --</option>
				<?
				$sqj=mysqli_query($koneksi_skpi, "SELECT id_bidang, nama_bidang FROM cp_bidang ORDER BY id_bidang");
				while($j=mysqli_fetch_assoc($sqj)){
					$sel=$j['id_bidang']==$url[2] ? "selected" : "";
					echo "<option value='$j[id_bidang]' $sel>$j[nama_bidang]</option>";
				}
				?>
			</select>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Capaian Pembelajaran</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Capaian Pembelajaran" name="in_cp" id="in_cp" rows="3" required autofocus><?= $d['in_cp'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Learning Outcomes</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Learning Outcomes" name="eng_cp" id="eng_cp" rows="3"><?= $d['eng_cp'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>نتائج التعلم</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="" name="arb_cp" id="arb_cp" rows="3"><?= $d['arb_cp'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Urutan</label>
		<div class="input-group">
			<input type="number" class="form-control" name="order_cp" min=1 id="order_cp" value="<?= $d['order_cp'] ?>">
		</div>  
	</div>
	<button class="btn btn-success" name="submit" id="submit" type="submit">Simpan</button>
	<button class="btn btn-danger" type="button" onclick="window.location.href='cp_prodi-<?= $url[1]."-".$url[2] ?>.html';">Batal</button>
</form>

<script src="assets/js/wysiwyg.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#in_cp').wysiwyg({
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

		$('#eng_cp').wysiwyg({
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

		$('#arb_cp').wysiwyg({
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