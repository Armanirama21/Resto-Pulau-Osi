<?php
// memulai session
session_start();
include '../koneksi.php';

// menerima data username dan password dari form
$username = $_POST['username'];
$password = $_POST['password'];

// cek database
$login = mysqli_query($kon, "SELECT * FROM tb_user WHERE username ='$username'");
$cek = mysqli_num_rows($login);

// cek jika username ditemukan
if ($cek > 0) {
	$data = mysqli_fetch_assoc($login);

	// verifikasi password
	if (password_verify($password, $data['password'])) {
		// cek privilege dan set session
		if ($data['id_level'] == 1) {
			$_SESSION['nama_user'] = $data['nama_user'];
			$_SESSION['id_user'] = $data['id_user'];
			$_SESSION['level'] = "Admin";
			header("location:../dashboard/index.php?dashboard");
		} elseif ($data['id_level'] == 2) {
			$_SESSION['nama_user'] = $data['nama_user'];
			$_SESSION['id_user'] = $data['id_user'];
			$_SESSION['level'] = "Waiter";
			header("location:../dashboard/index.php?home");
		} elseif ($data['id_level'] == 3) {
			$_SESSION['nama_user'] = $data['nama_user'];
			$_SESSION['id_user'] = $data['id_user'];
			$_SESSION['level'] = "Kasir";
			header("location:../dashboard/index.php?home");
		} else {
			header("location:index.php?pesan=gagal");
		}
	} else {
		// password salah
		header("location:index.php?pesan=gagal");
	}
} else {
	// username tidak ditemukan
	header("location:index.php?pesan=gagal");
}
