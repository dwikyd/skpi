<?
$mulai =str_replace(".", "-", $url[1])." 00:00:00";
$sampai=str_replace(".", "-", $url[2])." 23:59:59";
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<html>
<head>
	<title>Laporan Pembayaran <?= indonesia($mulai)." s/d ".indonesia($sampai) ?></title>	
</head>
<body>
	<?
	if($_SESSION['id_pegawai']==""){
		echo "<meta http-equiv=refresh content=0;url=\"../login.html\">";
	}
	else{
		$sql="SELECT kode_biaya FROM tagihan_bayar WHERE 1=1 AND status='Bayar' AND year(tgl_bayar)>0 AND (tgl_bayar between '$mulai' and '$sampai') GROUP BY kode_biaya ORDER BY kode_biaya";

		$biaya=mysqli_query($koneksi_pembayaran, $sql);
		$i=0;
		while($d=mysqli_fetch_assoc($biaya)){
			$i++;
			$kode[]=$d['kode_biaya'];
			$gkode[$i]=0;
		}
		?>
		<div class="text-center">LAPORAN PEMBAYARAN TANGGAL <?= indonesia($mulai)." s/d ".indonesia($sampai) ?></div>
		<table class="table table-bordered">
			<tr style="font-weight:bold; background-color: #bdbebf; text-align: center;">
				<td width="20px" align="center">No</td>
				<td width="80px" align="center">NIM</td>
				<td>Nama</td>
				<td>Prodi</td>
				<?
				foreach ($kode as $k) {
					$b=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT nama_biaya FROM ref_biaya WHERE id_biaya='$k'"));
					echo "<td width=80px align=center>$b[nama_biaya]</td>";
				}
				?>
				<td width="80px" align="center">Total</td>
			</tr>
			<?
			$sql="SELECT a.id_bayar, a.nim FROM tagihan a inner join tagihan_bayar b on a.id_bayar=b.id_bayar WHERE b.status='Bayar' AND year(b.tgl_bayar)>0 AND (b.tgl_bayar between '$mulai' and '$sampai') GROUP BY a.nim ORDER BY a.nim";

			$bayar=mysqli_query($koneksi_pembayaran, $sql);
			$no=0;
			$gtotal=0;
			while($d=mysqli_fetch_assoc($bayar)){
				$no++;
				$m=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, b.nama_prodi FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi WHERE a.nim='$d[nim]'"));
				echo "<tr>
				<td width='20px' align=center>$no</td>
				<td width='80px' align=center>$d[nim]</td>
				<td>$m[nama]</td>
				<td>$m[nama_prodi]</td>";
				$total=0;
				$i=0;
				foreach ($kode as $k) {
					$i++;
					$b=mysqli_fetch_assoc(mysqli_query($koneksi_pembayaran, "SELECT jumlah FROM tagihan_bayar WHERE id_bayar='$d[id_bayar]' AND kode_biaya='$k'"));
					echo "<td width='80px' align=right>".rupiah($b['jumlah'], 0)."</td>";
					$gkode[$i]+=$b['jumlah'];
					$total+=$b['jumlah'];
				}
				echo "<td width='80px' align=right>".rupiah($total, 0)."</td>";
				echo "</tr>";
				$gtotal+=$total;
			}
			echo "<tr style='font-weight:bold; background-color: #bdbebf;'>
			<td align=right colspan=4>TOTAL</td>";
			$i=0;
			foreach ($kode as $k) {
				$i++;
				echo "<td width='80px' align=right>".rupiah($gkode[$i], 0)."</td>";
			}
			echo "<td width='80px' align=right>".rupiah($gtotal, 0)."</td></tr>";
			?>
		</table>
		<? 
	} 
	?>
</body>
</html>