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
$allowed_levels = ["Admin", "Kasir"];
if (!in_array($_SESSION['level'], $allowed_levels)) {
  echo "<script>
        window.history.back();
    </script>";
  exit();
}

include '../../koneksi.php';
include '../fungsi/rupiah.php';

// Ambil data laporan transaksi dari database
$laporan = mysqli_query($kon, "SELECT * FROM tb_transaksi ORDER BY id_transaksi DESC");
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

  <title>Print Laporan</title>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-10 mx-auto mt-5">
        <div class="text-center">
          <h3>Laporan Transaksi Penjualan</h3>
          <h5>Resto Pulau Osi</h5>
          <p>WA : 089676244639 | Email : restopulauosi@gmail.com</p>
        </div>
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Id Order</th>
                  <th>Tanggal Transaksi</th>
                  <th>Total Harga</th>
                  <th>Diskon</th>
                  <th>Total Setelah Diskon</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($laporan as $row) :
                  $order_query =  mysqli_query($kon, "SELECT * FROM tb_order WHERE id_order = '$row[id_order]'");
                  $oq = mysqli_fetch_assoc($order_query);
                ?>
                  <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $row['id_order'] ?></td>
                    <td><?= date('d-m-Y H:i', ($oq['tanggal_order'])) ?></td>
                    <td>Rp. <?= rupiah($row['hartot_transaksi']) ?></td>
                    <td><?= $row['diskon_transaksi'] ?>%</td>
                    <td>Rp. <?= rupiah($row['totbar_transaksi']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script>
    window.print();
  </script>
</body>

</html>