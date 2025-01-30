<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>

<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-file"></span> Penerjemahan Sertifikat Aktifitas, Prestasi dan Penghargaan</h3>
	</div>
	<div class="card-body">
		<?
		$status=isset($_POST['status']) ? $_POST['status'] : "N";
		if(!isset($url[1])){ ?>
			<form class="form-horizontal mb-3" method="post">
				<div class="form-group row mb-3">
					<label class="col-sm-3 control-label text-right">Status Translate</label>
					<div class="col-sm-2">
						<select class='form-control' name='status' id="status" onchange="submit();">
							<option value="">-- Semua --</option>
							<?
							$sts=array('Y', 'N');

							foreach($sts as $k){
								$sel=$status== $k ? "selected" : "";
								$mk=$k=="Y" ? "Sudah" : "Belum";
								echo "<option value='$k' $sel>$mk</option>";
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
							<th width="80px">Tanggal</th>
							<th width="80px">NIM</th>
							<th>Capaian Pembelajaran</th>
							<th width="60px">Status</th>
							<th width="80px">Penerjemah</th>
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
			include "pages/translate/form.php";
		}
		?>
	</div>
</div>

<!-- <script src="assets/js/jquery-1.9.1.js"></script> -->
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
		var st=$("#status").val();
		var dataTable = $('#data').DataTable( {
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": false,
			"deferRender": true,
			"ajax":{
				url: 'pages/translate/tabel.php?status='+st,
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

	function terjemah(a){
		// window.open('translate/terjemah.php?'+a,'winname','titlebar=no, toolbar=no, location=no, status=no, menubar=no, scrollbars=no, resizable=no, fullscreen=yes');
		var popup = window.open('pages/translate/terjemah.php?id='+a, "popup", "fullscreen=yes");//titlebar=no, toolbar=no, location=no, status=no, menubar=no, scrollbars=no, resizable=no, 
		if (popup.outerWidth < screen.availWidth || popup.outerHeight < screen.availHeight)
		{
			popup.moveTo(0,0);
			popup.resizeTo(screen.availWidth, screen.availHeight);
		}
	}
</script>