<table width="100%" style="border-collapse: collapse; border: none;">
	<tr style="font-weight: bold;" valign="top">
		<td width="2%">
			B.
		</td>
		<td width="48%">
			KOMPETENSI PENDUKUNG, PRESTASI, DAN <i>SOFTSKILLS</i> (KETERAMPILAN NON TEKNIS)
		</td>
		<td></td>
		<td width="2%">
			<i>B.</i>
		</td>
		<td width="48%">
			<i>SUPPLEMENT COMPETENCIES, AWARDS, AND SOFTSKILLS</i>
		</td>
	</tr>
	<?
	$mhs=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, a.tmp_lahir, a.tgl_lahir, a.texttgl_lahir, a.jenjang, b.nama_prodi, c.nama_jurusan, a.ta, a.tgl_munaqosah, a.no_ijazah, a.no_pin, b.gelar, b.gelar_singkat, b.sk_pendirian, b.sk_ban, b.eng_prodi, c.eng_jurusan, a.kode_prodi, b.eng_gelar, a.tgl_lulus, b.dekan FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi inner join jurusan c on b.kode_jurusan=c.kode_jurusan WHERE a.nim='$url[1]'"));

	$sql=mysqli_query($koneksi_skpi, "SELECT in_prestasi, eng_prestasi FROM prestasi WHERE nim='$mhs[nim]' ORDER BY id_jns_dokumen, id_bukti_dok, id_prestasi");
	$no=0;
	while($d=mysqli_fetch_assoc($sql)){
		$no++;
		echo "<tr style='text-align:justify;'  valign='top'><td width=70px>$no.</td><td>".($d['in_prestasi'])."</td><td></td><td width=70px>$no.</td><td><i>".($d['eng_prestasi'])."</i></td></tr>";
	}
	?>
</table>