<?php
session_start();
include '../../koneksi.php'; // Pastikan path koneksi benar

// Cek apakah user sudah login
if (!isset($_SESSION['level'])) {
    echo "<script>
        alert('Anda harus login terlebih dahulu.');
        window.location.href = 'login.php'; // Arahkan ke halaman login
    </script>";
    exit();
}

// Cek level user
$allowed_level = ["Admin"];
if (!in_array($_SESSION['level'], $allowed_level)) {
    echo "<script>
        alert('Anda tidak memiliki akses.');
        window.history.back();
    </script>";
    exit();
}
?>

<div class="container mt-3">
    <?php if (isset($_SESSION['pesan'])) : ?>
        <div class="alert alert-info"><?= $_SESSION['pesan'] ?></div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>
    <div class="card">
        <div class="card-header">
            Data Meja
        </div>
        <a href="index.php?tambah_meja"><button class="btn btn-success btn-sm mt-3 ml-3">Tambah Data</button></a>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped" id="tabel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Meja</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Mengambil data dari database -->
                    <?php
                    $i = 1;
                    $sql = mysqli_query($kon, "SELECT * FROM tb_meja");
                    if (mysqli_num_rows($sql) > 0) {
                        while ($data = mysqli_fetch_array($sql)) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($data['meja_id']) ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?ubah_meja=<?= urlencode($data['meja_id']) ?>" class="btn btn-sm btn-warning">Ubah</a>
                                        <a href="fungsi/hapusMeja.php?meja_id=<?= urlencode($data['meja_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus meja ini?');">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Tidak ada data meja</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
