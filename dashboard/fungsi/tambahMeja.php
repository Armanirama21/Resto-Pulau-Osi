<?php
session_start();
include '../../koneksi.php';

// Ambil data dari form
$meja = $_POST['meja_id'];
$status = $_POST['status'];

// Cek apakah meja sudah ada atau belum
$cek = mysqli_query($kon, "SELECT * FROM tb_meja WHERE meja_id = '$meja'");
if (mysqli_num_rows($cek) > 0) {
    $_SESSION['pesan'] = '
    <div class="alert alert-warning mb-2 alert-dismissible text-small" role="alert">
        <b>Peringatan!</b> Meja sudah ada.
        <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
    </div>';
    header("Location: ../index.php?nomeja");
    exit();
} else {
    // Query insert data
    $query = "INSERT INTO tb_meja (meja_id, status) VALUES ('$meja', '$status')";
    $sql = mysqli_query($kon, $query);

    // Cek apakah query berhasil
    if ($sql) {
        $_SESSION['pesan'] = '
        <div class="alert alert-success mb-2 alert-dismissible text-small" role="alert">
            <b>Berhasil!</b> Data meja berhasil ditambahkan.
            <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
        </div>';
        header("Location: ../index.php?nomeja");
    } else {
        $_SESSION['pesan'] = '
        <div class="alert alert-danger mb-2 alert-dismissible text-small" role="alert">
            <b>Gagal!</b> Data gagal ditambahkan: ' . mysqli_error($kon) . '
            <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
        </div>';
        header("Location: ../index.php?nomeja");
    }
    exit();
}
?>
