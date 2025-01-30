<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-md-none">
	<a class="navbar-brand mr-lg-5" href="index.html">
		<img class="navbar-brand-dark" src="assets/img/logo.png" alt="Logo" /> <img class="navbar-brand-light" src="assets/img/logo.png" alt="Logo" />
	</a>
	<div class="d-flex align-items-center">
		<button class="navbar-toggler d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
	</div>
</nav>

<div class="container-fluid bg-soft">
	<div class="row">
		<div class="col-12">
			<? include "pages/menu.php"; ?>

			<main class="content">
				<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark pl-0 pr-2 pb-0 mb-3">
					<div class="container-fluid px-0">
						<div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
							<div class="d-flex align-items-center">
								<button id="sidebar-toggle" class="sidebar-toggle me-3 btn btn-icon-only d-none d-lg-inline-block align-items-center justify-content-center">
									<i class="fa fa-bars"></i>
								</button>
							</div>

							<div class="d-flex justify-content-between w-100" id="navbarSupportedContent">
								<div class="d-flex">
									<!-- Search form -->
								<!-- <form class="navbar-search form-inline" id="navbar-search-main">
									<div class="input-group input-group-merge search-bar">
										<span class="input-group-text" id="topbar-addon"><span class="fas fa-search"></span></span>
										<input type="text" class="form-control" id="topbarInputIconLeft" placeholder="Search" aria-label="Search" aria-describedby="topbar-addon">
									</div>
								</form> -->
							</div>
							<!-- Navbar links -->
							<ul class="navbar-nav align-items-center">
								<li class="nav-item dropdown">
									<a class="nav-link pt-1 px-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<div class="media d-flex align-items-center">
											<!-- <img class="user-avatar md-avatar rounded-circle" alt="Image placeholder" src="assets/img/team/profile-picture-3.jpg"> -->
											<span class="fas fa-user bell-shake" style="color: black;"></span>
											<div class="media-body ml-2 text-dark align-items-center d-none d-lg-block">
												<span class="mb-0 font-small font-weight-bold"><?= strtoupper($_SESSION['nama_lengkap']) ?></span>
											</div>
										</div>
									</a>
									<div class="dropdown-menu dashboard-dropdown dropdown-menu-right mt-2">
										<a class="dropdown-item font-weight-bold" href="profil.html"><span class="far fa-user-circle"></span>My Profile</a>
									<!-- <a class="dropdown-item font-weight-bold" href="#"><span class="fas fa-cog"></span>Settings</a>
										<a class="dropdown-item font-weight-bold" href="#"><span class="fas fa-user-shield"></span>Support</a> -->
										<div role="separator" class="dropdown-divider"></div>
										<? if($_SESSION['admin_skpi']=="Y") echo '<a class="dropdown-item font-weight-bold" href="loginas.html"><span class="fas fa-unlock"></span>Login As</a>'; ?>
										<a class="dropdown-item font-weight-bold" href="logout.html"><span class="fas fa-sign-out-alt text-danger"></span>Logout</a>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</nav>
				<?php
			// echo $_SESSION['hak_akses_skpi'];
				if(!isset($url[0]) || $url[0]=="home" || $url[0]=="login"){
					include "pages/home.php";
				}
				else{
					if(file_exists("pages/$url[0].php")){
						include "pages/$url[0].php";
					}
					else{
						if(file_exists("pages/$url[0]/$url[0].php")){
							include "pages/$url[0]/$url[0].php";
						}
						else if(file_exists("pages/$_SESSION[hak_akses]/$url[0].php")){
							include "pages/$_SESSION[hak_akses]/$url[0].php";
						}
						else if(file_exists("pages/$_SESSION[hak_akses]/$url[0]/$url[0].php")){
							include "pages/$_SESSION[hak_akses]/$url[0]/$url[0].php";
						}
						else{
							include "pages/home.php";
						}
					}
				}
				?>
				<footer class="footer section py-5">
					<div class="row">
						<div class="col-12 col-lg-6 mb-4 mb-lg-0">
							<p class="mb-0 text-center text-xl-left">Copyright © 2019-<span class="current-year"></span> <a class="text-primary font-weight-normal" href="https://themesberg.com" target="_blank">Themesberg</a></p>
						</div>

						<div class="col-12 col-lg-6">
							<ul class="list-inline list-group-flush list-group-borderless text-center text-xl-right mb-0">
								<li class="list-inline-item px-0 px-sm-2">
									<a href="https://themesberg.com/about">About</a>
								</li>
								<li class="list-inline-item px-0 px-sm-2">
									<a href="https://themesberg.com/themes">Themes</a>
								</li>
								<li class="list-inline-item px-0 px-sm-2">
									<a href="https://themesberg.com/blog">Blog</a>
								</li>
								<li class="list-inline-item px-0 px-sm-2">
									<a href="https://themesberg.com/contact">Contact</a>
								</li>
							</ul>
						</div>
					</div>
				</footer>
			</main>
		</div>
	</div>
</div>

<!-- Modal Content -->
<div class="modal fade" id="modal-progress" role="dialog" aria-labelledby="modal-form-signup" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="card border-light p-4">
					<div class="card-header pb-0">
						<h2><span class="fas fa-map-marker"></span> Progres SKPI</h2>                               
					</div>
					<div class="card-body">
						<div id="progress"></div>
					</div>
					<div class="card-footer">
						<button class="btn btn-danger" type="button" data-dismiss="modal">Tutup</button>
						<div id="bukti" style="padding:5px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function progress(a){
		$.ajax({
			url: 'pages/cek_prestasi/proses.php',
			type: "POST",
			data: "aksi=progress&id_prestasi="+a,
			success: function (data) {
				$("#progress").html(data);
			}
		});
	}
</script>