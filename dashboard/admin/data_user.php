<?php
session_start();

// cek apakah user sudah login
if (!isset($_SESSION['level'])) {
	echo "<script>
        window.history.back();
    </script>";
	exit();
}

// cek level user
$allowed_level = ["Admin"];
if (!in_array($_SESSION['level'], $allowed_level)) {
	echo "<script>
        window.history.back();
    </script>";
	exit();
}
?>
<div class="container mt-3">
	<?php if (isset($_SESSION['pesan'])) : ?>
		<?= $_SESSION['pesan'] ?>
	<?php unset($_SESSION['pesan']);
	endif; ?>
	<div class="card">
		<div class="card-header">
			Data User
		</div>
		<div class="card-body">
			<?php if ($_SESSION['level'] == "Admin"): ?>
				<a href="index.php?registrasi"><button class="btn btn-primary btn-sm mb-3">Registrasi User</button></a>
			<?php endif ?>
			<table class="table table-bordered table-hover table-striped" id="tabel">
				<thead>
					<tr>
						<th>No</th>
						<th>Nama User</th>
						<th>Username</th>
						<th>Level</th>
						<?php if ($_SESSION['level'] == "Admin"): ?>
							<th>Aksi</th>
						<?php endif ?>
					</tr>
				</thead>
				<tbody>
					<!-- mengambil data dari database -->
					<?php
					$i = 1;
					$sql = mysqli_query($kon, "SELECT * FROM tb_user");
					while ($data = mysqli_fetch_array($sql)) : ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $data['nama_user'] ?></td>
							<td><?= $data['username'] ?></td>
							<?php
							if ($data['id_level'] == 1) {
								$level = "Admin";
							} elseif ($data['id_level'] == 2) {
								$level = "Waiter";
							} else {
								$level = "Kasir";
							} 
							?>
							<td><?= $level; ?></td>
							<?php if ($_SESSION['level'] == "Admin"): ?>
								<td>
									<div class="btn-group">
										<a href="index.php?ubah_user=<?= $data['id_user'] ?>" class="btn btn-sm btn-warning">Ubah</a>
										<a href="fungsi/hapusUser.php?id_user=<?= $data['id_user']; ?>"
											class="btn btn-danger btn-sm"
											onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
									</div>
								</td>
							<?php endif; ?>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>