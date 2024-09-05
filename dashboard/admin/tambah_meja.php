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
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header">
					<strong>Tambah Meja</strong>
				</div>
				<div class="card-body">
					<form action="fungsi/tambahMeja.php" method="post">
						<div class="form-group">
							<label class="form-label" for="meja_id">Nomor Meja</label>
							<input type="text" class="form-control" id="meja_id" name="meja_id">
						</div>
						<div class="form-group d-none">
							<label class="form-label" for="status">Status</label>
							<input type="number" class="form-control" id="status" name="status" min="0">
						</div>
						<button type="submit" class="btn btn-primary">Submit</button>
						<button type="button" class="btn btn-danger" onclick="history.back()">Kembali</button>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>