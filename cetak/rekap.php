<div align="center"><h2 class="h5">Rekap SKPI</h2></div>
<table class="tabel" style="margin-top:1cm; border-collapse: collapse; border: 1px black solid; font-size:80%;">
	<tr style="text-align:center; background-color: #cfcfcf;">
		<th width="20px" rowspan="2">No</th>
		<th rowspan="2">Prodi</th>
		<th width="70px" rowspan="2">Ajuan</th>
		<th width="70px" colspan="2">Operator</th>
		<th width="70px" colspan="2">Kasubag</th>
		<th width="70px" colspan="2">Kabag</th>
		<th width="70px" colspan="2">KaProdi</th>
		<th width="70px" colspan="2">Cetak</th>
	</tr>
	<tr style="text-align:center; background-color: #cfcfcf;">
		<th width="35px">Jml</th>
		<th width="35px">%</th>
		<th width="35px">Jml</th>
		<th width="35px">%</th>
		<th width="35px">Jml</th>
		<th width="35px">%</th>
		<th width="35px">Jml</th>
		<th width="35px">%</th>
		<th width="35px">Jml</th>
		<th width="35px">%</th>
	</tr>
	<?
	$sql="SELECT kode_prodi, nama_prodi, jenjang FROM prodi WHERE kode_prodi in ($_SESSION[kode_prodi]) ORDER BY jenjang, kode_prodi";

	$jns=mysqli_query($koneksi_sikadu, $sql);
	$no=0;
	$tajuan=0;
	$topr=0;
	$tkasub=0;
	$tkabag=0;
	$tprodi=0;
	$tcetak=0;
	while($d=mysqli_fetch_assoc($jns)){
		$no++;
		$ajuan=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE kode_prodi='$d[kode_prodi]'"));
		$opr=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE kode_prodi='$d[kode_prodi]' AND sts_operator in('Pengajuan', 'Diproses')"));
		$kasub=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE kode_prodi='$d[kode_prodi]' AND sts_kasub in('Pengajuan', 'Diproses')"));
		$kabag=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE kode_prodi='$d[kode_prodi]' AND sts_kabag in('Pengajuan', 'Diproses')"));
		$prodi=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE kode_prodi='$d[kode_prodi]' AND sts_prodi in('Pengajuan', 'Diproses')"));
		$cetak=mysqli_num_rows(mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE kode_prodi='$d[kode_prodi]' AND sts_mhs='Cetak'"));
		$popr=round($opr/$ajuan*100, 2);
		$pkasub=round($kasub/$ajuan*100, 2);
		$pkabag=round($kabag/$ajuan*100, 2);
		$pprodi=round($prodi/$ajuan*100, 2);
		$pcetak=round($cetak/$ajuan*100, 2);

		$tajuan+=$ajuan;
		$topr+=$opr;
		$tkasub+=$kasub;
		$tkabag+=$kabag;
		$tprodi+=$prodi;
		$tcetak+=$cetak;
		echo "<tr valign=top><td align=center>$no</td>
		<td>$d[nama_prodi]</td>
		<td align=right>$ajuan</td>
		<td align=right>$opr</td>
		<td align=right>$popr</td>
		<td align=right>$kasub</td>
		<td align=right>$pkasub</td>
		<td align=right>$kabag</td>
		<td align=right>$pkabag</td>
		<td align=right>$prodi</td>
		<td align=right>$pprodi</td>
		<td align=right>$cetak</td>
		<td align=right>$pcetak</td></tr>";
	}
	$popr=round($topr/$tajuan*100, 2);
	$pkasub=round($tkasub/$tajuan*100, 2);
	$pkabag=round($tkabag/$tajuan*100, 2);
	$pprodi=round($tprodi/$tajuan*100, 2);
	$pcetak=round($tcetak/$tajuan*100, 2);
	echo "<tr style='font-weight:bold;'><td colspan=2>TOTAL</td>
	<td align=right>$tajuan</td>
	<td align=right>$topr</td>
	<td align=right>$popr</td>
	<td align=right>$tkasub</td>
	<td align=right>$pkasub</td>
	<td align=right>$tkabag</td>
	<td align=right>$pkabag</td>
	<td align=right>$tprodi</td>
	<td align=right>$pprodi</td>
	<td align=right>$tcetak</td>
	<td align=right>$pcetak</td>
	</tr>";
	?>
</table>

<style type="text/css">
	th{
		padding: 5px;
		border-collapse: collapse;
		border: thin solid black;
	}

	td{
		padding: 5px;
		border-collapse: collapse;
		border: thin solid black;
	}
</style>