<nav id="sidebarMenu" class="sidebar d-md-block bg-primary text-white collapse" data-simplebar>
	<div class="sidebar-inner px-4 pt-3">
		<div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
			<div class="d-flex align-items-center">
				<div class="d-block">
					<h2 class="h6"><?= $_SESSION['nama_lengkap'] ?></h2>
				</div>
			</div>
			<div class="collapse-close d-md-none">
				<a href="#sidebarMenu" class="fas fa-times" data-toggle="collapse"
				data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true"
				aria-label="Toggle navigation"></a>
			</div>
		</div>
		<ul class="nav flex-column">
			<li class="nav-item <?= $url[0]=="home" ? "active" : "" ?>">
				<a href="index.html" class="nav-link">
					<span class="sidebar-icon"><span class="fas fa-home"></span></span>
					<span>Dashboard</span>
				</a>
			</li>
			<?
			$where="";
			$_SESSION['hak_akses_skpi']=str_replace("'", "", $_SESSION['hak_akses_skpi']);

			if(!empty($_SESSION['hak_akses_skpi'])){
				$hak=explode(",", $_SESSION['hak_akses_skpi']);
				foreach ($hak as $k) {
					if(!empty($k)){
						if($where=="") $where.=" (user like '%$k%' ";
						else $where.=" OR user like '%$k%' ";
					}
				}
			}
			else{
				$where="(user like '%MBR%'";
			}
			if(isset($where)) $where.=")";

			$aktif=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT id, parent_id, level FROM fitur_skpi WHERE url='$url[0]'"));
			
			$sql="SELECT id, parent_id, url, title, icon from fitur_skpi where level=0 and publish='Y' and user!='' and $where order by fitur_order";
						
			$menu=mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi).$sql);
			while($m1=mysqli_fetch_assoc($menu)){
				$url1=$m1['url']=="" ? "" : $m1['url'].".html";
				$sql2="SELECT id, parent_id, url, title, icon from fitur_skpi where parent_id='$m1[id]' and publish='Y' and  user!='' and $where order by fitur_order";
				$menu2=mysqli_query($koneksi_skpi, $sql2) or die(mysqli_error($koneksi_skpi).$sql2);
				
				
				if(mysqli_num_rows($menu2)==0){
					$active1="";
					$show="";
					if(isset($aktif['id'])){
						if(substr($m1['id'], 0, 2)==$aktif['id']){
							$active1="active";	
							$show="show";
						}
					}
					?>
					<li class="nav-item <?= $active1 ?>">
						<a href="<?= $url1 ?>" class="nav-link">
							<span class="sidebar-icon"><span class="fas fa-<?= $m1['icon'] ?>"></span></span>
							<span><?= $m1['title'] ?></span>
						</a>
					</li>
					<?
				}
				else{ 
					$active1="";
					$show="";
					if(substr($m1['id'], 0, 2)==$aktif['parent_id']){
						$active1="active";	
						$show="show";
					}
					?>
					<li class="nav-item <?= $active1 ?>">
						<span class="nav-link  d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#submenu-<?= $m1['id'] ?>">
							<span>
								<span class="sidebar-icon"><span class="fas fa-<?= $m1['icon'] ?>"></span></span> 
								<?= $m1['title'] ?>
							</span>
							<span class="link-arrow"><span class="fas fa-chevron-right"></span></span> 
						</span>
						<div class="multi-level collapse <?= $show ?>" role="list" id="submenu-<?= $m1['id'] ?>" aria-expanded="false">
							<ul class="flex-column nav">
								<? while($m2=mysqli_fetch_assoc($menu2)){ 
									$url2=$m2['url']=="" ? "" : $m2['url'].".html";
									$active=$m2['id']==$aktif['id'] ? "active" : "";
									?>
									<li class="nav-item <?= $active ?>"><a class="nav-link" href="<?= $url2 ?>"><span><?= $m2['title'] ?></span></a></li>
								<? } ?>
							</ul>
						</div>
					</li>
					<?
				}
			}
			?>
			<li class="nav-item <?= $url[0]=="list_cp" ? "active" : "" ?>">
				<a href="list_cp.html" class="nav-link">
					<span class="sidebar-icon"><span class="fas fa-list"></span></span>
					<span>List CP Prodi</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="logout.html" class="nav-link">
					<span class="sidebar-icon"><span class="fas fa-sign-out-alt"></span></span>
					<span>Logout</span>
				</a>
			</li>
		</ul>
	</div>
</nav>