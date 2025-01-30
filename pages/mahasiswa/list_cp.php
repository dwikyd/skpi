<?
$m=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, a.kode_prodi, b.nama_prodi, c.nama_jurusan, a.ta, a.tmp_lahir, a.tgl_lahir, a.jenis_kel, a.email, a.hp, a.wa, a.alamat_lengkap, a.foto, b.jenjang FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi inner join jurusan c on b.kode_jurusan=c.kode_jurusan WHERE a.nim='$_SESSION[nim]'"));
?>
<div class="py-4">
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Capaian Pembelajaran Prodi <?= $m['nama_prodi']." - ".$m['jenjang'] ?></h1>
        </div>
    </div>
</div>


<div class="card border-light shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th width="75px">No.</th>
                        <th>Capaian Pembelajaran</th>
                        <th>Learning Outcomes</th>
                    </tr>
                </thead>
                <tbody>
                    <?
                    $sql=mysqli_query($koneksi_skpi, "SELECT a.id_cp, a.in_cp, a.eng_cp, a.id_bidang, b.nama_bidang, b.eng_bidang FROM cp_prodi a inner join cp_bidang b on a.id_bidang=b.id_bidang WHERE a.kode_prodi='$m[kode_prodi]' ORDER BY a.id_bidang, a.order_cp, a.id_cp");
                    $no=0;
                    $bidang="";
                    while($d=mysqli_fetch_assoc($sql)){
                        if($bidang!=$d['id_bidang']){
                            $no=0;
                            $bidang=$d['id_bidang'];
                            echo "<tr><td colspan=3><b>$d[nama_bidang] (<i>$d[eng_bidang]</i>)</b></td></tr>";
                        }
                        $no++;
                        echo "<tr><td width=70px>$no</td><td>".strip_tags($d['in_cp'])."</td><td>".strip_tags($d['eng_cp'])."</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>