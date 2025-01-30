<script type="text/javascript" language="javascript" src="assets/js/jquery-3.3.1.js"></script>
<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-calculator"></span> Rekap Penerjemahan SKPI</h3>
	</div>
	<div class="card-body">
		<?
		if(empty($url[1])){ ?>
			<form method="post" action="">
				<div class="form-group row" style="padding-bottom: 10px;">
					<label class="col-sm-2 control-label text-right">Tanggal </label>
					<div class="col-sm-4">
						<select class='form-control' name='tanggal' id="tanggal" onchange="submit();">
							<?
							$per=mysqli_query($koneksi_skpi, "SELECT tgl_rekap_translate FROM prestasi_translate GROUP BY tgl_rekap_translate ORDER BY tgl_rekap_translate DESC");
							while($d=mysqli_fetch_assoc($per)){
								$sel=$d['tgl_rekap_translate']==$_POST['tanggal'] ? "selected" : "";
								echo "<option value='$d[tgl_rekap_translate]' $sel>".tgl_indo($d['tgl_rekap_translate'])."</option>";
							}
							?>
						</select>
					</div>
					<div class="col-sm-3">
						<a href="<?= $url[0] ?>-tambah.html" class="btn btn-primary" type="button"><i class="fa fa-calculator"></i> Perhitungan Baru</a>
					</div>
				</div>
			</form>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="datatable">
					<thead>
						<tr style="text-align:center; vertical-align: middle;">
							<th width="30px">No</th>
							<th>Nama Penerjemah</th>
							<th width="30px">Tugas Bahasa</th>
							<th width="30px">Jml Request Naskah</th>
							<th width="30px">Jml Naskah Diterjemah</th>
							<th width="30px">Jml Naskah Direview</th>
							<th width="30px">Jml Mahasiswa</th>
							<th width="30px">Jml Lembar (Jml Mhs x 4)</th>
						</tr>
					</thead>
					<tbody>
						<?
						$tanggal=$_POST['tanggal'];
						$ptj=mysqli_query($koneksi_skpi, "SELECT a.* FROM penerjemah a INNER JOIN prestasi_translate b ON a.id_pegawai=b.id_pegawai GROUP BY a.id_pegawai ORDER BY a.nama_lengkap");
						$no=0;
						while($d=mysqli_fetch_assoc($ptj)){
							$no++;
							$bagi=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT COUNT(id_pegawai) as jumlah FROM prestasi_translate WHERE id_pegawai='$d[id_pegawai]' AND tgl_rekap_translate='$tanggal'"));
							$terjemah=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT COUNT(id_pegawai) as jumlah FROM prestasi_translate WHERE id_pegawai='$d[id_pegawai]' AND tgl_rekap_translate='$tanggal' AND status_translate='S'"));
							$review=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT COUNT(id_pegawai) as jumlah FROM prestasi_translate WHERE id_pegawai='$d[id_pegawai]' AND tgl_rekap_translate='$tanggal' AND status_review='S'"));
							$mhs=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT COUNT(a.id_pegawai) as jumlah FROM prestasi_translate a INNER JOIN prestasi b ON a.id_prestasi=b.id_prestasi WHERE a.id_pegawai='$d[id_pegawai]' AND a.tgl_rekap_translate='$tanggal' AND a.status_review='S' GROUP BY b.nim"));
							$d['jenis']=$d['jenis']=="I" ? "Inggris" : "Arab";
							$lembar=$mhs*4;
							echo "<tr><td>$no</td><td>$d[nama_lengkap]</td><td align=center>$d[jenis]</td><td align=right>$bagi[jumlah]</td><td align=right>$terjemah[jumlah]</td><td align=right>$review[jumlah]</td><td align=right>$mhs</td><td align=right>$lembar</td></tr>";
						}
						?>
					</tbody>
				</table>
			</div>
			<?
		}
		else{
			?>
			<h5>Rekap penerjemahan baru</h5>
			<form method="post" action="#" id="form">
				<input type="hidden" name="aksi" value="rekap">
				<div class="form-group row mb-3">
					<label class="col-sm-2 control-label text-right">Tanggal </label>
					<div class="col-sm-4">
						<input type="date" name="tanggal" class="form-control" value="<?= date("Y-m-d") ?>" required>
					</div>
					<div class="col-sm-6">
						<button class="btn btn-warning" type="submit">Simpan</button>
						<a href="<?= $url[0] ?>.html" class="btn btn-success">Tutup</a>
					</div>
				</div>
				<table class="table table-bordered table-striped table-hover" id="datatables">
					<thead>
						<tr>
							<th width="10px">No</th>
							<th width="70px">NIM</th>
							<th>Nama</th>
							<th width="40px">Bahasa</th>
							<th width="80px">Tgl Terjemah</th>
							<th>Penerjemah</th>
						</tr>
					</thead>
					<tbody>
						<?
						$query="SELECT c.nim, c.nama, a.*, d.nama_lengkap FROM prestasi_translate a INNER JOIN prestasi b ON a.id_prestasi=b.id_prestasi INNER JOIN skpi c ON b.nim=c.nim INNER JOIN penerjemah d ON a.id_pegawai=d.id_pegawai WHERE a.tgl_translate IS NOT NULL AND a.tgl_rekap_translate IS NULL ORDER BY DATE(a.tgl_translate), b.nim, a.jenis";
						$rek=mysqli_query($koneksi_skpi, $query) or die(mysqli_error($koneksi_sikadu));	

						$no=0;
						while($d=mysqli_fetch_assoc($rek)){
							$no++;
							$d['jenis']=$d['jenis']=="I" ? "Inggris" : "Arab";
							echo "<tr>
							<td align=center>$no</td>
							<td>$d[nim]<input type='hidden' name='id_translate[]' value='$d[id_translate]'></td>
							<td>$d[nama]</td>
							<td>$d[jenis]</td>
							<td nowrap>".indonesia($d['tgl_translate'])."</td>
							<td>$d[nama_lengkap]</td>
							</tr>";
						}
						?>
					</tbody>
				</table>
			</form>
			<?
		}
		?>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('form').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				type: 'post',
				url: 'pages/petugas/terjemah_rekap/proses.php',
				data: $('form').serialize(),
				success: function (a) {
					if(a==""){
						window.location.href="terjemah_rekap.html";
					}
					else{
						Swal.fire(a, '', 'error');
					}
				}
			});
		});

	});
</script>