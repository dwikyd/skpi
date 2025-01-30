<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4"><i class="fas fa-print"></i> Cetak SKPI</h1>
        </div>
    </div>
</div>


<div class="card border-light shadow-sm mb-4">
    <div class="card-body text-center">
        <?
        $sql=mysqli_query($koneksi_skpi, "SELECT * FROM skpi WHERE nim='$_SESSION[nim]'");
        $d=mysqli_fetch_assoc($sql);

        if($d["nim"]==""){
            echo "<p>Anda belum mengajukan SKPI</p>";
        }
        else{
            if($d['sts_dekan']=="Disetujui") echo "<button type='button' class='btn btn-success'><i class='fas fa-print'></i> Cetak SKPI</button>";
            echo "<button type='button' class='btn btn-success' onclick='window.open(\"cetak/prestasi.html\");'><i class='fas fa-print'></i> Cetak Prestasi</button>";
        }
        ?>
    </div>
</div>