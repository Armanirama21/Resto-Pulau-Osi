<?php  
session_start();
error_reporting(0);
include "../../koneksi.php";

// ambil data dari form
$id = $_GET['id_masakan'];
$kategori = $_POST['kategori_masakan'];
$nama = $_POST['nama_masakan'];
$harga = $_POST['harga_masakan'];
$status = $_POST['status_masakan'];
$ket = $_POST['keterangan'];

// cek apakah ingin ubah foto atau tidak
if (isset($_POST['ubah_foto'])) {
    // menambahkan foto baru
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $fotobaru = date('dmYHis').$foto;
    $path = "../assets/image/makanan/$fotobaru";

    // ambil data lama
    $query = "SELECT * FROM tb_masakan WHERE id_masakan='$id'";
    $sql = mysqli_query($kon, $query);
    $data = mysqli_fetch_assoc($sql);

    // menghapus foto lama
    if (is_file("../assets/image/makanan/".$data['foto'])) {
        unlink("../assets/image/makanan/".$data['foto']);
    }

    // upload foto baru
    if (move_uploaded_file($tmp, $path)) {
        // update data dengan foto baru
        $query = "UPDATE tb_masakan SET kategori_masakan='$kategori', nama_masakan='$nama', harga_masakan='$harga', foto='$fotobaru', status_masakan='$status', keterangan='$ket' WHERE id_masakan='$id'";
        $sql = mysqli_query($kon, $query);

        if ($sql) {
            if ($kategori == "Makanan") {
                $_SESSION['pesan'] = '
                    <div class="alert alert-success mb-2 alert-dismissible text-small " role="alert">
                        <b>Berhasil!</b> Data berhasil diubah
                        <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    </div>
                ';
                header('location:../index.php?makanan');
            } elseif ($kategori == "Minuman") {
                $_SESSION['pesan'] = '
                    <div class="alert alert-success mb-2 alert-dismissible text-small " role="alert">
                        <b>Berhasil!</b> Data berhasil diubah
                        <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
                    </div>
                ';
                header('location:../index.php?minuman');
            }
        } else {
            errorHandling($kategori);
        }
    } else {
        errorHandling($kategori, 'Foto gagal diubah');
    }
} else {
    // update data tanpa ubah foto
    $query = "UPDATE tb_masakan SET kategori_masakan='$kategori', nama_masakan='$nama', harga_masakan='$harga', status_masakan='$status', keterangan='$ket' WHERE id_masakan='$id'";
    $sql = mysqli_query($kon, $query);        

    if ($sql) {
        successHandling($kategori);
    } else {
        errorHandling($kategori);
    }
}

function successHandling($kategori) {
    $_SESSION['pesan'] = '
        <div class="alert alert-success mb-2 alert-dismissible text-small " role="alert">
            <b>Berhasil!</b> Data berhasil diubah
            <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
    header('location:../index.php?' . strtolower($kategori));
}

function errorHandling($kategori, $message = 'Data gagal diubah') {
    $_SESSION['pesan'] = '
        <div class="alert alert-danger mb-2 alert-dismissible text-small " role="alert">
            <b>Gagal!</b> ' . $message . '
            <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
        </div>
    ';
    header('location:../index.php?' . strtolower($kategori));
}
?>