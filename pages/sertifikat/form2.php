<link href="assets/css/smart_wizard.css" rel="stylesheet" type="text/css" />

<link href="assets/css/smart_wizard_theme_dots.css" rel="stylesheet" type="text/css" />

<link href="assets/css/wysiwyg.css" rel="stylesheet">
<?
if(!isset($url[4])) $url[4]="";
$sql="SELECT id_cp, kode_prodi, id_bidang, in_cp, eng_cp FROM cp_prodi WHERE id_cp='$url[4]'";
$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
?>

<div class="container">
	<br />
	<form action="#" id="myForm" role="form" method="post" accept-charset="utf-8">

		<!-- SmartWizard html -->
		<div id="smartwizard">
			<ul>
				<li><a href="#step-1">Step 1<br /><small>Kategori</small></a></li>
				<li><a href="#step-2">Step 2<br /><small>Prestasi</small></a></li>
				<li><a href="#step-3">Step 3<br /><small>Deskripsi</small></a></li>
				<li><a href="#step-4">Step 4<br /><small>Upload Bukti</small></a></li>
			</ul>

			<div>
				<div id="step-1">
					<h2>Kategori</h2>
					<div class="form-group mb-4">
						<label>Bidang</label>
						<div class="input-group">
							<select class="form-control" name="id_bidang" id="id_bidang" required>
								<option value=''>-- Bidang --</option>
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
				</div>
				<div id="step-2">
					<h2>Prestasi</h2>
					
				</div>
				<div id="step-3">
					<h2>Form Data Pribadi</h2>
					<!-- isi dengan form sesuai kebutuhan -->
				</div>
				<div id="step-4" class="">
					<h2>Form Data Pengalaman Kerja</h2>
					<!-- isi dengan form sesuai kebutuhan -->
				</div>
			</div>
		</div>

	</form>

</div>

<!-- <form action="#" id="form" method="post">
	<input type="hidden" name="aksi" value="<?= $url[3] ?>" id="aksi">
	<input type="hidden" name="id_cp" value="<?= $d['id_cp'] ?>">
	<input type="hidden" name="kode_prodi" id="kode_prodi" value="<?= $url[1] ?>">
	<div class="form-group mb-4">
		<label>Bidang</label>
		<div class="input-group">
			<select class="form-control" name="id_bidang" id="id_bidang" required>
				<option value=''>-- Bidang --</option>
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
	<div class="form-group mb-4">
		<label>Capaian Pembelajaran</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Capaian Pembelajaran" name="in_cp" id="in_cp" rows="3" required autofocus><?= $d['in_cp'] ?></textarea>
		</div>  
	</div>
	<div class="form-group mb-4">
		<label>Learning Outcomes</label>
		<div class="input-group">
			<textarea class="form-control" style="display: none;" placeholder="Bukti Dokumen" name="eng_cp" id="eng_cp" rows="3"><?= $d['eng_cp'] ?></textarea>
		</div>  
	</div>
	<button class="btn btn-success" name="submit" id="submit" type="submit">Simpan</button>
	<button class="btn btn-danger" type="button" onclick="window.location.href='sertifikat-<?= $url[1] ?>.html';">Batal</button>
</form> -->

<script src="assets/js/wysiwyg.js"></script>
<!-- <script src="assets/js/jquery-1.11.2.min.js"></script> -->
<script src="assets/js/jquery.smartWizard.js"></script>


<script type="text/javascript">
	$(document).ready(function(){
		var btnFinish = $('<button></button>').text('Simpan')
		.attr('id','btn-finish')
		.addClass('btn btn-info')
		.on('click', function(){ 

		});
		
		// $("#btn-finish").addClass('disabled');
		// $("#btn-finish").removeClass('disabled');

		var btnCancel = $('<button></button>').text('Batal')
		.addClass('btn btn-danger')
		.on('click', function(){ 
			window.location.href="sertifikat.html";
			//$('#smartwizard').smartWizard("reset");  
		});                         


		$('#smartwizard').smartWizard({ 
			selected: 0, 
			theme: 'dots',
			transitionEffect:'fade',
			toolbarSettings: {toolbarPosition: 'bottom',
			toolbarExtraButtons: [btnFinish, btnCancel]
		},
		anchorSettings: {
			markDoneStep: true, 
			markAllPreviousStepsAsDone: true,
			removeDoneStepOnNavigateBack: true,
			enableAnchorOnDoneStep: true 
		}
	});

		$("#btn-finish").addClass('disabled');
		$("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
			// alert("You are on step "+stepNumber+" now");
			if(stepPosition == 'first'){
				$("#prev-btn").addClass('disabled');
				$("#btn-finish").addClass('disabled');
			}else if(stepPosition == 'final'){
				$("#next-btn").addClass('disabled');
				$("#btn-finish").removeClass('disabled');
			}else{
				$("#prev-btn").removeClass('disabled');
				$("#next-btn").removeClass('disabled');
				$("#btn-finish").addClass('disabled');
			}
		});                               

	});   
</script>  

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
	});
</script>