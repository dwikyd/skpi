<div class="row">
	<div class="col-12 mb-4">
		<div class="row">
			<div class="col-12 col-lg-12 mb-4">
				<div class="card border-light shadow-sm">
					<div class="card-header">
						<div class="row align-items-center">
							<div class="col">
								<h2 class="h5">Rekap SKPI</h2>
							</div>
							<div class="col text-right">
								<a href="cetak/rekap.html" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i> Cetak</a>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table align-items-center table-flush">
							<thead class="thead-light">
								<tr style="text-align:center;">
									<th scope="col" width="20px" rowspan="2">No</th>
									<th scope="col" rowspan="2">Prodi</th>
									<th scope="col" width="70px" rowspan="2">Ajuan</th>
									<th scope="col" width="70px" colspan="2">Operator</th>
									<th scope="col" width="70px" colspan="2">Kasubag</th>
									<th scope="col" width="70px" colspan="2">Kabag</th>
									<th scope="col" width="70px" colspan="2">KaProdi</th>
									<th scope="col" width="70px" colspan="2">Cetak</th>
								</tr>
								<tr style="text-align:center;">
									<th scope="col" width="35px">Jml</th>
									<th scope="col" width="35px">%</th>
									<th scope="col" width="35px">Jml</th>
									<th scope="col" width="35px">%</th>
									<th scope="col" width="35px">Jml</th>
									<th scope="col" width="35px">%</th>
									<th scope="col" width="35px">Jml</th>
									<th scope="col" width="35px">%</th>
									<th scope="col" width="35px">Jml</th>
									<th scope="col" width="35px">%</th>
								</tr>
							</thead>
							<tbody>
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
									$popr=$opr>0 ? round($opr/$ajuan*100, 2) : 0;
									$pkasub=$kasub>0 ? round($kasub/$ajuan*100, 2) : 0;
									$pkabag=$kabag>0 ? round($kabag/$ajuan*100, 2) : 0;
									$pprodi=$prodi>0 ? round($prodi/$ajuan*100, 2) : 0;
									$pcetak=$cetak>0 ? round($cetak/$ajuan*100, 2) : 0;
									
									$tajuan+=$ajuan;
									$topr+=$opr;
									$tkasub+=$kasub;
									$tkabag+=$kabag;
									$tprodi+=$prodi;
									$tcetak+=$cetak;
									echo "<tr><td>$no</td>
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
								$popr=0;
								$pkasub=0;
								$pkabag=0;
								$pprodi=0;
								$pcetak=0;

								if($tajuan>0){
									$popr=round($topr/$tajuan*100, 2);
									$pkasub=round($tkasub/$tajuan*100, 2);
									$pkabag=round($tkabag/$tajuan*100, 2);
									$pprodi=round($tprodi/$tajuan*100, 2);
									$pcetak=round($tcetak/$tajuan*100, 2);
								}
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
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- <div class="col-12 col-lg-4 mb-4">
				<div class="card border-light shadow-sm">
					<div class="card-header border-bottom border-light">
						<h2 class="h5 mb-0">Progress track</h2>
					</div>
					<div class="card-body">
						<div class="row align-items-center mb-4">
							<div class="col-auto">
								<span class="icon icon-md text-purple"><span class="fab fa-bootstrap"></span></span>
							</div>
							<div class="col">
								<div class="progress-wrapper">
									<div class="progress-info">
										<div class="h6 mb-0">Pengajuan</div>
										<div class="small font-weight-bold text-dark"><span>34 %</span></div>
									</div>
									<div class="progress mb-0">
										<div class="progress-bar bg-purple" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 34%;"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row align-items-center mb-4">
							<div class="col-auto">
								<span class="icon icon-md text-danger"><span class="fab fa-angular"></span></span>
							</div>
							<div class="col">
								<div class="progress-wrapper">
									<div class="progress-info">
										<div class="h6 mb-0">Operator</div>
										<div class="small font-weight-bold text-dark"><span>60 %</span></div>
									</div>
									<div class="progress mb-0">
										<div class="progress-bar bg-danger" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row align-items-center mb-4">
							<div class="col-auto">
								<span class="icon icon-md text-success"><span class="fab fa-vuejs"></span></span>
							</div>
							<div class="col">
								<div class="progress-wrapper">
									<div class="progress-info">
										<div class="h6 mb-0">Kasubag</div>
										<div class="small font-weight-bold text-dark"><span>45 %</span></div>
									</div>
									<div class="progress mb-0">
										<div class="progress-bar bg-tertiary" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row align-items-center mb-4">
							<div class="col-auto">
								<span class="icon icon-md text-info"><span class="fab fa-react"></span></span>
							</div>
							<div class="col">
								<div class="progress-wrapper">
									<div class="progress-info">
										<div class="h6 mb-0">Kaprodi</div>
										<div class="small font-weight-bold text-dark"><span>35 %</span></div>
									</div>
									<div class="progress mb-0">
										<div class="progress-bar bg-info" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%;"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row align-items-center">
							<div class="col-auto">
								<span class="icon icon-md text-purple"><span class="fab fa-bootstrap"></span></span>
							</div>
							<div class="col">
								<div class="progress-wrapper">
									<div class="progress-info">
										<div class="h6 mb-0">Dekan</div>
										<div class="small font-weight-bold text-dark"><span>34 %</span></div>
									</div>
									<div class="progress mb-0">
										<div class="progress-bar bg-purple" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 34%;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</div>