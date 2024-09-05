<?php  
error_reporting(0);
session_start();
include "../../koneksi.php";

// ambil data dari form
$id = $_GET['id'];
$meja = $_POST['meja_id'];
$status = $_POST['status'];
$link = header("location:../index.php?nomeja");


$query = "UPDATE tb_meja SET meja_id='$meja', status='$status'WHERE id='$id'";
	$sql = mysqli_query($kon, $query);		

	if ($sql) {
		$_SESSION['pesan'] = '
    <div class="alert alert-success mb-2 alert-dismissible text-small " role="alert">
    	<b>Berhasil!</b> Data berhasil diubah
    	<button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
    </div>
    ';
		$link;
	} else {
		$_SESSION['pesan'] = '
    <div class="alert alert-danger mb-2 alert-dismissible text-small " role="alert">
    	<b>Gagal!</b> Data gagal diubah
    	<button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
    </div>
    ';
		$link;
	}
?>