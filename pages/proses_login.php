<?php
session_start();
include "../config/koneksi.php";

$login = false;

if (empty($_POST['username'])) {
    echo "Username atau NIM harus diisi !";
} else if (empty($_POST['password'])) {
    echo "Password harus diisi !";
} else {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menggunakan Prepared Statement untuk menghindari SQL Injection
    $stmt = $koneksi_sikadu->prepare("SELECT nim, nama, kode_prodi, password FROM mhs WHERE nim = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $d = $result->fetch_assoc();

    if ($d) {
        if (password_verify($password, $d['password'])) {
            $_SESSION['nim'] = $d['nim'];
            $_SESSION['hak_akses'] = "mahasiswa";
            $_SESSION['hak_akses_skpi'] = "MHS";
            $_SESSION['nama_lengkap'] = $d['nama'];
            $_SESSION['kode_prodi'] = $d['kode_prodi'];

            $login = true;

            // Cek apakah data sudah ada di tabel skpi
            $stmt = $koneksi_skpi->prepare("SELECT nim FROM skpi WHERE nim = ?");
            $stmt->bind_param("s", $_SESSION['nim']);
            $stmt->execute();
            $cek = $stmt->get_result()->fetch_assoc();

            if (!$cek) {
                $sql = "INSERT INTO skpi (nim, nama, kode_prodi, upload_by, update_by) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $koneksi_skpi->prepare($sql);
                $stmt->bind_param(
                    "sssss",
                    $_SESSION['nim'],
                    $_SESSION['nama_lengkap'],
                    $_SESSION['kode_prodi'],
                    $_SESSION['nama_lengkap'],
                    $_SESSION['nama_lengkap']
                );
                $stmt->execute();
            }
        } else {
            echo "Password tidak valid !";
        }
    } else {
        // Cek apakah pengguna adalah pegawai
        $stmt = $koneksi_simpeg->prepare("SELECT id_pegawai, nama, password FROM pegawai WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $d = $result->fetch_assoc();

        if ($d) {
            if (password_verify($password, $d['password'])) {
                $_SESSION['id_pegawai'] = $d['id_pegawai'];
                $_SESSION['hak_akses'] = "petugas";
                $_SESSION['nama_lengkap'] = str_replace(",", "", $d['nama']);

                // Set default hak akses
                $_SESSION['hak_akses_skpi'] = "";
                $_SESSION['kode_prodi'] = "";
                $_SESSION['admin_skpi'] = "N";

                // Cek hak akses di sikadu
                $stmt = $koneksi_sikadu->prepare("SELECT hak_akses, kode_prodi FROM hak_akses_sikadu WHERE id_pegawai = ?");
                $stmt->bind_param("i", $_SESSION['id_pegawai']);
                $stmt->execute();
                $result = $stmt->get_result();
                $v = $result->fetch_assoc();

                if ($v) {
                    $_SESSION['hak_akses_skpi'] = $v['hak_akses'];
                    $_SESSION['kode_prodi'] = "'" . str_replace(",", "','", $v['kode_prodi']) . "'";
                    $_SESSION['admin_skpi'] = (strpos($v['hak_akses'], 'SPV') !== false) ? "Y" : "N";
                }

                $login = true;
            } else {
                echo "Password tidak valid !";
            }
        } else {
            echo "Username tidak terdaftar !";
        }
    }

    if ($login) {
        // Set session tambahan dari tabel global
        $stmt = $koneksi_sikadu->prepare("SELECT ta, smt FROM global");
        $stmt->execute();
        $result = $stmt->get_result();
        $glob = $result->fetch_assoc();

        if ($glob) {
            $_SESSION['ta'] = $glob['ta'];
            $_SESSION['smt'] = $glob['smt'];
            $_SESSION['pilihperiode'] = $_SESSION['ta'] . $_SESSION['smt'];
        }
    }
}
?>

