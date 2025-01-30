<?
if(!isset($_POST['status'])) $_POST['status']="Pengajuan";
?>
<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-tag"></span> Persetujuan SKPI</h3>
	</div>
	<div class="card-body">
		<?
		if(!isset($url[1])){ ?>
			<form class="form-horizontal form" method="post" action="#" enctype="multipart/form-data">
				<input type="hidden" id="approval" value="<?= $approval ?>">
				<div class="form-group row" style="padding-bottom: 10px;">
					<label class="col-sm-1 control-label text-right">Prodi </label>
					<div class="col-sm-5">
						<select class='form-control select' data-live-search='true' name="prodi" id="prodi" onchange="submit();">
							<option value="">-- Semua Prodi --</option>
							<?
							$sql="SELECT kode_prodi, nama_prodi, jenjang FROM prodi WHERE kode_prodi in ($_SESSION[kode_prodi]) ORDER BY jenjang, kode_prodi";

							$jns=mysqli_query($koneksi_sikadu, $sql);
							while($d=mysqli_fetch_assoc($jns)){
								$sel=$_POST['prodi']== $d['kode_prodi'] ? "selected" : "";
								echo "<option value='$d[kode_prodi]' $sel>$d[jenjang] - $d[nama_prodi]</option>";
							}
							?>
						</select>
					</div>
					<label class="col-sm-2 control-label text-right">Wisuda Ke </label>
					<div class="col-sm-1">
						<select class='form-control select' data-live-search='true' name="wisuda_ke" id="wisuda_ke" onchange="submit();">
							<option value="">-- Semua --</option>
							<?
							$sql="SELECT wisuda_ke FROM skpi WHERE kode_prodi in ($_SESSION[kode_prodi]) GROUP BY wisuda_ke ORDER BY wisuda_ke DESC";

							$jns=mysqli_query($koneksi_skpi, $sql);
							while($d=mysqli_fetch_assoc($jns)){
								$sel=$_POST['wisuda_ke']== $d['wisuda_ke'] ? "selected" : "";
								echo "<option value='$d[wisuda_ke]' $sel>$d[wisuda_ke]</option>";
							}
							?>
						</select>
					</div>
					<label class="col-sm-1 control-label text-right">Status </label>
					<div class="col-sm-2">
						<select class='form-control select' data-live-search='true' name="status" id="sts" onchange="submit();">
							<option value="">-- Semua Status --</option>
							<?
							$opr=strpos($_SESSION['hak_akses_skpi'], 'OPR') !== false ? "Y" : "N";
							$sts=$opr=="Y" ? array('Draft','Pengajuan','Setuju','Tolak','Revisi') : array('Pengajuan','Setuju','Tolak','Revisi');
							$i=0;
							foreach ($sts as $k) {
								$i++;
								if(isset($_POST['status'])){
									$sel=$_POST['status']== $k ? "selected" : "";
								}
								else{
									$sel=$i==1 ? "selected" : "";
								}
								echo "<option value='$k' $sel>$k</option>";
							}
							?>
						</select>
					</div>
				</div>
			</form>
			<div class="text-center" id="proses"></div>
			<div class="table-responsive py-4" id="content">
				<table class="table table-hover" id="data">
					<thead class="thead-light">
						<tr><th width="30px">No.</th><th width="120px">Tgl. Pengajuan</th><th width="80px">NIM</th><th>Nama</th><th width="60px">Prodi</th><th width="60px">SKPI</th><th width="60px">Status</th><th width="30px">Aksi</th></tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<? 
		}
		elseif(!isset($url[2])){ 
			$nim=str_replace("_", "-", $url[1]);
			$mhs=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, a.jenjang, b.nama_prodi, a.wa, a.hp FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi WHERE a.nim='$nim'"));

			$skpi=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT sts_mhs, sts_operator, sts_kasub, sts_kabag, sts_prodi, sts_dekan FROM skpi WHERE nim='$nim'"));
			if($skpi['sts_'.$approval]=="Pengajuan"){
				if($approval!='dekan'){
					$skpi['sts_'.$approval]="Diproses";
					mysqli_query($koneksi_skpi, "UPDATE skpi SET sts_mhs='Operator', sts_$approval='Diproses' WHERE nim='$nim'");
					mysqli_query($koneksi_skpi, "UPDATE prestasi SET sts_$approval='Diproses' WHERE nim='$nim' AND sts_$approval='Pengajuan'");
				} 
			}

			$hp1="";
			$wa1=preg_replace("/[^0-9]/","", $mhs['wa']);
			$wa1=substr($wa1, 0, 2)=="62" ? $wa1 : "62".substr($wa1, 1, strlen($wa1)); 
			$hp1=$perangkat=="Android" ? "https://api.whatsapp.com/send?phone=$wa1" : "https://web.whatsapp.com/send?phone=$wa1";
			$hp1="<a href='$hp1' target='_blank' style='color:blue;'>$mhs[wa]</a>";
			?>
			<div class="row">
				<div class="col-6">
					<table>
						<tr><td width="120px" nowrap>NIM</td><td width="10px">:</td><td><?= $mhs['nim'] ?></td></tr>
						<tr><td>Nama</td><td>:</td><td><?= $mhs['nama'] ?></td></tr>
						<tr><td>Prodi</td><td>:</td><td><?= $mhs['nama_prodi'] ?></td></tr>
						<tr><td>HP/WA</td><td>:</td><td><?= $hp1 ?></td></tr>
					</table>
				</div>
				<div class="col-6">
					<table>
						<tr><td width="120px" nowrap>Operator</td><td width="10px">:</td><td><?= $skpi['sts_operator'] ?></td></tr>
						<tr><td>Kasub</td><td>:</td><td><?= $skpi['sts_kasub'] ?></td></tr>
						<tr><td>Kabag</td><td>:</td><td><?= $skpi['sts_kabag'] ?></td></tr>
						<tr><td>Prodi</td><td>:</td><td><?= $skpi['sts_prodi'] ?></td></tr>
						<tr><td>Dekan</td><td>:</td><td><?= $skpi['sts_dekan'] ?></td></tr>
					</table>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered mb-0 rounded">
					<thead class="thead-light">
						<tr>
							<th width="75px">No.</th>
							<th>Capaian Pembelajaran</th>
							<th width="120px">Status</th>
							<th width="120px">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?
						$sql=mysqli_query($koneksi_skpi, "SELECT a.*, b.nama_dokumen FROM prestasi a inner join jenis_dokumen b on a.id_jns_dokumen=b.id_jns_dokumen WHERE a.nim='$nim' ORDER BY a.id_jns_dokumen, a.id_prestasi");
						$no=0;
						$bidang="";
						$setuju=0;
						while($d=mysqli_fetch_assoc($sql)){
							if($bidang!=$d['id_jns_dokumen']){
								$no=0;
								$bidang=$d['id_jns_dokumen'];
								echo "<tr><td colspan=4><b>$d[nama_dokumen]</b></td></tr>";
							}
							$no++;

							$aksi="";
							$sts=$approval=='dekan' ? "sts_prodi" : "sts_$approval";
							$ctt="catatan_$approval";
							$catatan=$d[$ctt]=="" ? "" : "<br><b>Catatan</b> : ".$d[$ctt];
							// if($approval!='dekan') $update=($skpi[$sts]=='Pengajuan' || $skpi[$sts]=='Diproses') && $d[$sts]!="Draft" ? "<a href='approval-$url[1]-$d[id_prestasi].html' class='btn btn-info btn-xs'><i class='fas fa-edit'></i></a>" : "";
							$update="";
							if($approval!='dekan') {
								if($skpi[$sts]=='Pengajuan' || $skpi[$sts]=='Diproses'){
									if($d[$sts]!="Draft") $update="<a href='approval-$url[1]-$d[id_prestasi].html' class='btn btn-info btn-xs'><i class='fas fa-edit'></i></a>";
								} 

							}
							// $aksi="<a href='approval-$url[1]-$d[id_prestasi].html' class='btn btn-info btn-xs'><i class='fas fa-edit'></i></a>";
							$aksi.="<div class='btn-group'>";
							if(file_exists("file_prestasi/$d[nim]/$d[file]")) $aksi.="<a href='file_prestasi/$d[nim]/$d[file]' target='_blank' class='btn btn-success btn-xs'><i class='fas fa-download'></i></a>";
							$aksi.="<a href='#' title='Netralkan Uraian' onclick='hilangkan(\"$d[id_prestasi]\");' class='btn btn-warning btn-xs'><i class='fas fa-check'></i></a>";
							$aksi.="$update</div>";

							if($d[$sts]=='Disetujui') $setuju++;
							echo "<tr><td width=70px>$no</td><td>".strip_tags($d['in_prestasi'])."<br><i>".strip_tags($d['eng_prestasi'])."</i>$catatan<td>".$d[$sts]."</td><td align=center nowrap>$aksi</td></tr>";
						}
						?>
					</tbody>
				</table>
			</div>
			<div style="padding:10px; text-align: center;">
				<?
				if($approval=="operator" && $skpi['sts_operator']=="Diproses"){
					echo "<button class='btn btn-warning btn-sm' onclick='batal(\"mhs\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-undo'></i> Batalkan</button> ";	
				}
				if($approval=="kasub" && $skpi['sts_kasub']=="Diproses"){
					echo "<button class='btn btn-warning btn-sm' onclick='batal(\"operator\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-undo'></i> Batalkan</button> ";	
				}
				if($approval=="kabag" && $skpi['sts_kabag']=="Diproses"){
					echo "<button class='btn btn-warning btn-sm' onclick='batal(\"kasub\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-undo'></i> Batalkan</button> ";	
				}
				if($approval=="prodi" && $skpi['sts_prodi']=="Diproses"){
					if($skpi['sts_kabag']=="Draft") echo "<button class='btn btn-warning btn-sm' onclick='batal(\"kasub\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-undo'></i> Batalkan</button> ";
					else echo "<button class='btn btn-warning btn-sm' onclick='batal(\"kabag\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-undo'></i> Batalkan</button> ";
				}
				if($approval=="dekan" && $skpi['sts_prodi']=="Disetujui"){
					echo "<button class='btn btn-warning btn-sm' onclick='batal(\"prodi\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-undo'></i> Batalkan</button> ";	
				}

				if($setuju>0){
					if($approval=="operator" && $skpi['sts_operator']=="Diproses"){
						echo "<button class='btn btn-primary btn-sm' onclick='submit(\"kasub\", \"$nim\", \"$approval\")' type='button'>Submit Kasubag</button> <button class='btn btn-info btn-sm' onclick='submit(\"kabag\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-share'></i> Submit Kabag</button>";	
					}
					if($approval=="kasub" && $skpi['sts_kasub']=="Diproses"){
						echo "<button class='btn btn-info btn-sm' onclick='submit(\"kabag\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-share'></i> Submit Kabag</button> ";
						echo "<button class='btn btn-secondary btn-sm' onclick='submit(\"prodi\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-share'></i> Submit Kaprodi</button>";		
					}
					if($approval=="kabag" && $skpi['sts_kabag']=="Diproses"){
						echo "<button class='btn btn-info btn-sm' onclick='submit(\"prodi\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-share'></i> Submit Kaprodi</button>";	
					}
					if($approval=="prodi" && $skpi['sts_prodi']=="Diproses"){
						echo "<button class='btn btn-info btn-sm' onclick='submit(\"dekan\", \"$nim\", \"$approval\")' type='button'><i class='fas fa-signature'></i> Setujui Cetak</button>";	
					}
				}
				?>
				<button class="btn btn-success btn-sm" onclick="window.open('cetak/prestasi-<?= $url[1] ?>.html')" type="button"><i class="fas fa-eye"></i> Preview</button>
				<button class="btn btn-danger btn-sm" type="button" onclick="window.location.href='approval.html';"><i class="fas fa-times"></i> Tutup</button>
			</div>
			<? 
		}
		elseif(isset($url[2])){
			include "pages/petugas/approval/form.php";
		}
		?>
	</div>
</div>


<!-- <script src="assets/js/jquery-1.9.1.js"></script> -->
<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<script type="text/javascript" language="javascript" src="assets/js/jquery.dataTables.min.js"></script>
<script>
	function prodi(a){
		var pd=a.value;
		document.cookie = "prodi="+pd;
		$('#data').DataTable().ajax.reload();
	}

	function wisuda(a){
		var wsd=a.value;
		document.cookie = "wisuda="+wsd;
		$('#data').DataTable().ajax.reload();
	}

	function sts(a){
		var sts=a.value;
		document.cookie = "status="+sts;
		$('#data').DataTable().ajax.reload();
	}
</script>

<script type="text/javascript" language="javascript" >
	$(function(){
		var prodi=$("#prodi").val();
		var ke=$("#wisuda_ke").val();
		var sts=$("#sts").val();
		$('#data').DataTable({
			"processing": true,
			"serverSide": true,
			"searching": true,
			"orderMulti": true,
			"deferRender": true,
			"order": [[ 1, 'asc' ]],
			"ajax":{
				"url": "pages/petugas/approval/tabel.php?prodi="+prodi+"&status="+sts+"&wisuda_ke="+ke,
				"dataType": "json",
				"type": "POST"
			},
			"columnDefs": [
				{ "targets": [0], "className": "text-center", "width": "20px" },
				// { "targets": [1], "className": "text-center", "width": "100px" },
				{ "targets": [7], "className": "text-center", "width": "20px" },
				],
			"columns": [
				{ "data": "no" },
				{ "data": "tgl_skpi" },
				{ "data": "nim" },
				{ "data": "nama" },
				{ "data": "kode_prodi" },
				{ "data": "sts_skpi" },
				{ "data": "sts_prestasi" },
				{ "data": "aksi" },
				]  
		});
	});

	function submit(a, b, c){
		var str = a;
		str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
			return letter.toUpperCase();
		});

		var pesan="SKPI NIM "+b+" akan disubmit ke "+str+" ?";
		if(str=="Dekan"){
			pesan="SKPI NIM "+b+" telah memenuhi syarat untuk dicetak ?";
		}
		Swal.fire({
			title: 'Anda yakin ?',
			text: pesan,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes !'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'pages/petugas/approval/proses.php',
					type: "POST",
					data: "aksi=submit&tujuan="+a+"&nim="+b+"&sumber="+c,
					success: function (data) {
						if(data==""){
							window.location.reload();
						}
						else{
							Swal.fire('Info!', data, 'info');
						}
					}
				});
			}
		})
	}

	function batal(a, b, c){
		var str = a;
		str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
			return letter.toUpperCase();
		});
		Swal.fire({
			title: 'Anda yakin ?',
			text: "SKPI NIM "+b+" akan dikembalikan ke "+str+" ?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, kembalikan !'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'pages/petugas/approval/proses.php',
					type: "POST",
					data: "aksi=batal&tujuan="+a+"&nim="+b+"&sumber="+c,
					success: function (data) {
						if(data==""){
							window.location.reload();
						}
						else{
							Swal.fire('Info!', data, 'info');
						}
					}
				});
			}
		})
	}

	function hilangkan(a){
		var str = a;
		Swal.fire({
			title: 'Anda yakin ?',
			text: "Uraian Prestasi akan dinetralkan ?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes !'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'pages/petugas/approval/proses.php',
					type: "POST",
					data: "aksi=netral&id_prestasi="+a,
					success: function (data) {
						if(data==""){
							window.location.reload();
						}
						else{
							Swal.fire('Info!', data, 'info');
						}
					}
				});
			}
		})
	}
</script>