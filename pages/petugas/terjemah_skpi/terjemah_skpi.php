<div class="card border-light shadow-sm mb-4">
	<div class="card-header">
		<h3><span class="fas fa-archive"></span> Penerjemahan SKPI</h3>
	</div>
	<div class="card-body">
		<?
		if(empty($url[1])){
			include 'daftar.php';
		}
		else{
			include 'terjemah.php';
		}
		?>
	</div>
</div>