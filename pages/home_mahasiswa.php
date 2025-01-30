<?
$m=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, a.kode_prodi, b.nama_prodi, c.nama_jurusan, a.ta, a.tmp_lahir, a.tgl_lahir, a.jenis_kel, a.email, a.hp, a.wa, a.alamat_lengkap, a.foto FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi inner join jurusan c on b.kode_jurusan=c.kode_jurusan WHERE a.nim='$_SESSION[nim]'"));
$foto=$m['foto']=="" ? trim($m['nim']).".jpg" : trim($m['foto']);
$foto="http://sikadu.iainkudus.ac.id/sikadu/foto/$foto";
?>
<div class="row">
	<div class="col-12 col-xl-8">
		<div class="card card-body bg-white border-light shadow-sm mb-4">
			<h2 class="h5 mb-4">Biodata Mahasiswa</h2>
			<form>
				<div class="row">
					<div class="col-md-6 mb-3">
						<div>
							<label for="first_name">NIM</label>
							<input class="form-control" id="nim" type="text" placeholder="NIM" value="<?= $m['nim'] ?>" readonly>
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<div>
							<label for="last_name">Nama Lengkap</label>
							<input class="form-control" id="nama" type="text" placeholder="Also your last name" value="<?= strtoupper($m['nama']) ?>" readonly>
						</div>
					</div>
				</div>
				<div class="row align-items-center">
					<div class="col-md-6 mb-3">
						<div>
							<label for="birthday">Tempat / Tgl Lahir</label>
							<input class="form-control" id="tgl_lahir" type="text" placeholder="dd/mm/yyyy" value="<?= $m['tmp_lahir'].", ".indonesia($m['tgl_lahir']) ?>" readonly>    
						</div>                                    
					</div>
					<div class="col-md-6 mb-3">
						<div>
							<label for="gender">Jenis Kel.</label>
							<input class="form-control" id="tgl_lahir" type="text" placeholder="dd/mm/yyyy" value="<?= $m['jenis_kel']=="L" ? "Laki-laki" : "Perempuan" ?>" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<div class="form-group">
							<label for="email">Email</label>
							<input class="form-control" id="email" type="email" value="<?= $m['email'] ?>" readonly>
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<div class="form-group">
							<label for="phone">HP / WA</label>
							<input class="form-control" id="phone" type="text" value="<?= $m['hp']." / ".$m['wa'] ?>" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<div class="form-group">
							<label for="email">Fakultas</label>
							<input class="form-control" id="email" type="text" value="<?= $m['nama_jurusan'] ?>" readonly>
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<div class="form-group">
							<label for="phone">Prodi</label>
							<input class="form-control" id="phone" type="text" value="<?= $m['nama_prodi'] ?>" readonly>
						</div>
					</div>
				</div>
				<!-- <div class="mt-3">
					<button type="submit" class="btn btn-primary">Save All</button>
				</div> -->
			</form>
		</div>
	</div>
	<div class="col-12 col-xl-4">
		<div class="row">
			<div class="col-12 mb-4">
				<div class="card border-light text-center p-0">
					<div class="profile-cover rounded-top" data-background="../assets/img/profile-cover.jpg"></div>
					<div class="card-body pb-5">
						<img src="<?= $foto ?>" class="user-avatar large-avatar rounded-circle mx-auto mt-n7 mb-4" alt="<?= $foto ?>">
						<h4 class="h3"><?= $m['nim'] ?></h4>
						<h5 class="font-weight-normal"><?= strtoupper($m['nama']) ?></h5>
						
						<!-- <p class="text-gray mb-4">New York, USA</p>
						<a class="btn btn-sm btn-primary mr-2" href="#"><span class="fas fa-user-plus mr-1"></span> Connect</a>
						<a class="btn btn-sm btn-secondary" href="#">Send Message</a> -->
					</div>
				</div>
			</div>
			<!-- <div class="col-12">
				<div class="card card-body bg-white border-light shadow-sm mb-4">
					<h2 class="h5 mb-4">Select profile photo</h2>
					<div class="d-xl-flex align-items-center">
						<div>
							<div class="user-avatar xl-avatar mb-3">
								<img class="rounded" src="../assets/img/team/profile-picture-3.jpg" alt="change avatar">
							</div>
						</div>
						<div class="file-field">
							<div class="d-flex justify-content-xl-center ml-xl-3">
								<div class="d-flex">
									<span class="icon icon-md"><span class="fas fa-paperclip mr-3"></span></span> <input type="file">
									<div class="d-md-block text-left">
										<div class="font-weight-normal text-dark mb-1">Choose Image</div>
										<div class="text-gray small">JPG, GIF or PNG. Max size of 800K</div>
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