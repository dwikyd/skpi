<?php
class PagingTugas{
// Fungsi untuk mencek halaman dan posisi data
	function cariPosisi($batas){
		if(empty($url[2])){
			$posisi=0;
			$url[2]=1;
		}
		else{
			$posisi = ($url[2]-1) * $batas;
		}
		return $posisi;
	}

// Fungsi untuk menghitung total halaman
	function jumlahHalaman($jmldata, $batas){
		$jmlhalaman = ceil($jmldata/$batas);
		return $jmlhalaman;
	}

		// Fungsi untuk link halaman 1,2,3 (untuk admin)
	function navHalaman($url, $halaman_aktif, $jmlhalaman){
		$link_halaman = "";

		// Link ke halaman pertama (first) dan sebelumnya (prev)
		if($halaman_aktif > 1){
			$prev = $halaman_aktif-1;
			$link_halaman .= "<li><a href='$url-1.html'><i class='fa fa-left-arrow'></i><< First</a></li> <li><a href=$url-$prev>Prev</a></li>";
		}
		else{ 
			// $link_halaman .= "<li>First</li><li>Prev</li>";
		}

		// Link halaman 1,2,3, ...
		// $angka = ($halaman_aktif > 3 ? " ... " : " "); 
		for ($i=$halaman_aktif-2; $i<$halaman_aktif; $i++){
			if ($i < 1) continue;
			$angka .= "<li><a href='$url-$i.html'>$i</a></li>";
		}
		$angka .= "<li class='active'><a href='#'><b>$halaman_aktif</b></a></li>";

		for($i=$halaman_aktif+1; $i<($halaman_aktif+3); $i++){
			if($i > $jmlhalaman) break;
			$angka .= "<li><a href='$url-$i.html'>$i</a></li>";
		}
		// $angka .= ($halaman_aktif+2<$jmlhalaman ? "<li>...</li><li><a href='$url-$jmlhalaman.html'>$jmlhalaman</a></li>" : " ");

		$link_halaman .= "$angka";

		// Link ke halaman berikutnya (Next) dan terakhir (Last) 
		if($halaman_aktif < $jmlhalaman){
			$next = $halaman_aktif+1;
			$link_halaman .= "<li><a href='$url-$next.html'>Next ></a></li>
			<li><a href='$url-$jmlhalaman.html'>Last >></a></li>";
		}
		else{
			// $link_halaman .= "<li>Next</li><li>Last</li>";
		}
		return $link_halaman;
	}
}
?>