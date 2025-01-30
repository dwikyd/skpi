<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>
<? if(!isset($url[1])) $url[1]="peg"; 
?>
<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-unlock"></span> Login As</h3>
	</div>
	<div class="card-body">
		<div class="col-lg-3 col-sm-6 mt-4 mt-md-0">
			<!-- Radio -->
			<fieldset>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="pilihan" id="exampleRadios1" value="peg" <?= $url[1]=="peg" ? "checked" : "" ?> onclick="pilih(this);">
					<label class="form-check-label" for="exampleRadios1">
						Pegawai
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="pilihan" id="exampleRadios2" value="mhs" <?= $url[1]=="mhs" ? "checked" : "" ?> onclick="pilih(this);">
					<label class="form-check-label" for="exampleRadios2">
						Mahasiswa
					</label>
				</div>
				<input type="hidden" id="klp" value="<?= $url[1] ?>">
			</fieldset>
		</div>
		<div class="text-center" id="proses"></div>
		<div class="table-responsive py-4">
			<table class="table table-hover" id="data">
				<thead class="thead-light">
					<? if($url[1]=="peg") echo '<tr><th width="100px">NIP</th><th>Nama</th><th>Unit Kerja</th><th>Jabatan</th><th width="30px">Aksi</th></tr>';
					else echo '<tr><th width="30px">NIM</th><th>Nama</th><th>Prodi</th><th>TA</th><th width="30px">Aksi</th></tr>';
					?>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	function pilih(a){
		var kt=a.value;
		window.location.href="loginas-"+kt+".html";
	}
	function login(a, b){
		$.ajax({
			url: 'pages/loginas/proses.php',
			type: "POST",
			data: "klp="+a+"&id="+b,
			success: function (data) {
				if(data==""){
					window.location.reload();
				}
				else{
					alert(data);
				}
			}
		});
	}
</script>

<script type="text/javascript" language="javascript" >
	$(document).ready(function() {
		var klp=$("#klp").val();
		var dataTable = $('#data').DataTable( {
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": false,
			"deferRender": true,
			"ajax":{
				url: 'pages/loginas/tabel.php?klp='+klp,
				type: "post",
				error: function(){
					$(".data-error").html("");
					$("#data").append('<tbody class="data-error"><tr><th colspan="5">Tidak ada data untuk ditampilkan</th></tr></tbody>');
					$("#data-error-proses").css("display","none");
				}
			},
			"columnDefs": [
			{ "targets": [0], "className": "text-left", "width": "25%" },
			{ "targets": [1], "className": "text-left", "width": "25%" },
			{ "targets": [2], "className": "text-left", "width": "250px" },
			{ "targets": [3], "className": "text-center", "width": "30px" },
			{ "targets": [4], "className": "text-center", "width": "30px" },
			],
		} 
		);
	} 
	);
</script>