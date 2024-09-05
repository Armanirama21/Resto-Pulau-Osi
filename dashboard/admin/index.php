<div class="container mt-3">
  <?php if (isset($_SESSION['pesan'])) : ?>
    <?= $_SESSION['pesan'] ?>
  <?php unset($_SESSION['pesan']);
  endif; ?>

  <?php
  $query = "SELECT * FROM tb_user WHERE id_user='$_SESSION[id_user]'";
  $sql = mysqli_query($kon, $query);
  $data = mysqli_fetch_array($sql);
  ?>

  <div class="card mb-3">
    <img src="assets/image/resto.jpg" class="card-img-top" height="180">
    <div class="card-body">
      <?php if ($_SESSION['level'] == ""): ?>
        <h3 class="card-title">Selamat Datang di Resto Pulau Osi</h3>
        <p>Silahkan lihat semua menu makanan dan minuman yang tersedia di resto kami</p>
      <?php else: ?>
        <h4>Hallo👋 <?= $data['nama_user'] ?> </h4>
      <?php endif; ?>
    </div>
  </div>

  <!-- Makanan Section -->
  <h3 class="mb-3">Makanan</h3>
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <?php
        // Pagination for Masakan
        $limit = 7; // Number of records per page
        $page = (isset($_GET['page_masakan']) && is_numeric($_GET['page_masakan'])) ? $_GET['page_masakan'] : 1;
        $offset = ($page - 1) * $limit;

        // Get total records for Masakan
        $total_query = "SELECT COUNT(*) as total FROM tb_masakan WHERE kategori_masakan='Makanan'";
        $total_result = mysqli_query($kon, $total_query);
        $total_row = mysqli_fetch_assoc($total_result);
        $total_records = $total_row['total'];
        $total_pages_masakan = ceil($total_records / $limit);

        // Pagination Query for Masakan
        $query = "SELECT * FROM tb_masakan WHERE kategori_masakan='Makanan' ORDER BY id_masakan LIMIT $offset, $limit";
        $sql = mysqli_query($kon, $query);
        while ($data = mysqli_fetch_array($sql)) :
        ?>
          <div class="col-lg-3 mb-3">
            <div class="card">
              <img class="card-img-top" src="assets/image/makanan/<?= $data['foto'] ?>" alt="Card image cap">
              <div class="card-body">
                <div class="mb-1">
                  <?php if ($data['status_masakan'] == 1): ?>
                    <span class="badge badge-success">Tersedia</span>
                  <?php else: ?>
                    <span class="badge badge-danger">Tidak Tersedia</span>
                  <?php endif; ?>
                </div>
                <h4 class="card-title"><?= $data['nama_masakan'] ?></h4>
                <?php
                $harga = $data['harga_masakan'];
                if ($_SESSION['level'] == "") {
                  $harga = $data['harga_masakan'];
                }
                ?>
                <p class="card-text"><strong>Rp. <?= rupiah($harga) ?></strong></p>
                <?php if ($_SESSION['level'] == "Waiter"): ?>
                  <?php if ($data['status_masakan'] == 1): ?>
                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#masakan_<?= $data['id_masakan']; ?>">
                      Beli
                    </button>
                  <?php else: ?>
                    <a href="index.php?tambah=<?= $data['id_masakan'] ?>" class="btn btn-primary btn-sm btn-block disabled">Beli</a>
                  <?php endif; ?>
                <?php else: ?>
                  <?php if ($data['status_masakan'] == 1): ?>
                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#masakan_<?= $data['id_masakan']; ?>">
                      Detail
                    </button>
                  <?php else: ?>
                    <a href="index.php?tambah=<?= $data['id_masakan'] ?>" class="btn btn-primary btn-sm btn-block disabled">Detail</a>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="masakan_<?= $data['id_masakan']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    <?php if ($_SESSION['level'] == "Waiter"): ?>
                      Beli <?= $data['nama_masakan'] ?>
                    <?php else: ?>
                      Detail <?= $data['nama_masakan'] ?>
                    <?php endif; ?>
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="fungsi/orderMakanan.php" method="POST">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-6">
                        <img src="assets/image/makanan/<?= $data['foto'] ?>" alt="" class="card-img-top">
                      </div>
                      <div class="col-md-6">
                        <input type="hidden" name="id_masakan" value="<?= $data['id_masakan'] ?>">
                        <div class="form-group">
                          <label>Menu</label>
                          <input type="text" readonly class="form-control" value="<?= $data['nama_masakan'] ?>">
                        </div>
                        <div class="form-group">
                          <label>Harga</label>
                          <input type="text" readonly class="form-control" value="Rp. <?= rupiah1($data['harga_masakan']) ?>">
                        </div>
                        <?php if ($_SESSION['level'] == "" || $_SESSION['level'] == "Admin"): ?>
                          <div class="form-group">
                            <label>Keterangan</label>
                            <?php if ($data['keterangan'] == ""): ?>
                              <div>-</div>
                            <?php else: ?>
                              <div><?= $data['keterangan'] ?></div>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                        <?php if ($_SESSION['level'] == "Waiter"): ?>
                          <div class="form-group">
                            <label>Jumlah Pesanan</label>
                            <input type="number" class="form-control" name="jumlah" value="1" min="1" max="20">
                          </div>
                          <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control"></textarea>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <?php if ($_SESSION['level'] == "Waiter"): ?>
                      <button type="submit" class="btn btn-primary">Pesan</button>
                    <?php endif; ?>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

  <!-- Pagination for Masakan -->
  <nav>
    <ul class="pagination justify-content-end">
      <?php if ($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?page_masakan=<?= $page - 1; ?>">Previous</a></li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $total_pages_masakan; $i++): ?>
        <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page_masakan=<?= $i; ?>"><?= $i; ?></a></li>
      <?php endfor; ?>
      <?php if ($page < $total_pages_masakan): ?>
        <li class="page-item"><a class="page-link" href="?page_masakan=<?= $page + 1; ?>">Next</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- Minuman Section -->
  <h3 class="mb-3 border-top py-3">Minuman</h3>
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <?php
        // Pagination for Masakan
        $limit = 7; // Number of records per page
        $page = (isset($_GET['page_minuman']) && is_numeric($_GET['page_minuman'])) ? $_GET['page_minuman'] : 1;
        $offset = ($page - 1) * $limit;

        // Get total records for Masakan
        $total_query = "SELECT COUNT(*) as total FROM tb_masakan WHERE kategori_masakan='Minuman'";
        $total_result = mysqli_query($kon, $total_query);
        $total_row = mysqli_fetch_assoc($total_result);
        $total_records = $total_row['total'];
        $total_pages_masakan = ceil($total_records / $limit);

        // Pagination Query for Masakan
        $query2 = "SELECT * FROM tb_masakan WHERE kategori_masakan='Minuman' ORDER BY id_masakan LIMIT $offset, $limit";
        $sql2 = mysqli_query($kon, $query2);
        while ($data = mysqli_fetch_array($sql2)) :
        ?>

          <div class="col-lg-3 mb-3">
            <div class="card">
              <img class="card-img-top" src="assets/image/makanan/<?= $data['foto'] ?>" alt="Card image cap">
              <div class="card-body">
                <div class="mb-1">
                  <?php if ($data['status_masakan'] == 1): ?>
                    <span class="badge badge-success">Tersedia</span>
                  <?php else: ?>
                    <span class="badge badge-danger">Tidak Tersedia</span>
                  <?php endif; ?>
                </div>
                <h4 class="card-title"><?= $data['nama_masakan'] ?></h4>
                <?php
                $harga = $data['harga_masakan'];
                if ($_SESSION['level'] == "") {
                  $harga = $data['harga_masakan'];
                }
                ?>
                <p class="card-text"><strong>Rp. <?= rupiah($harga) ?></strong></p>
                <?php if ($_SESSION['level'] == "Waiter"): ?>
                  <?php if ($data['status_masakan'] == 1): ?>
                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#masakan_<?= $data['id_masakan']; ?>">
                      Beli
                    </button>
                  <?php else: ?>
                    <a href="index.php?tambah=<?= $data['id_masakan'] ?>" class="btn btn-primary btn-sm btn-block disabled">Beli</a>
                  <?php endif; ?>
                <?php else: ?>
                  <?php if ($data['status_masakan'] == 1): ?>
                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#masakan_<?= $data['id_masakan']; ?>">
                      Detail
                    </button>
                  <?php else: ?>
                    <a href="index.php?tambah=<?= $data['id_masakan'] ?>" class="btn btn-primary btn-sm btn-block disabled">Detail</a>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Modal -->
          <div class="modal fade" id="masakan_<?= $data['id_masakan']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    <?php if ($_SESSION['level'] == "Waiter"): ?>
                      Beli <?= $data['nama_masakan'] ?>
                    <?php else: ?>
                      Detail <?= $data['nama_masakan'] ?>
                    <?php endif; ?>
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="fungsi/orderMakanan.php" method="POST">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-6">
                        <img src="assets/image/makanan/<?= $data['foto'] ?>" alt="" class="card-img-top">
                      </div>
                      <div class="col-md-6">
                        <input type="hidden" name="id_masakan" value="<?= $data['id_masakan'] ?>">
                        <div class="form-group">
                          <label>Menu</label>
                          <input type="text" readonly class="form-control" value="<?= $data['nama_masakan'] ?>">
                        </div>
                        <div class="form-group">
                          <label>Harga</label>
                          <input type="text" readonly class="form-control" value="Rp. <?= rupiah1($data['harga_masakan']) ?>">
                        </div>
                        <?php if ($_SESSION['level'] == "" || $_SESSION['level'] == "Admin"): ?>
                          <div class="form-group">
                            <label>Keterangan</label>
                            <?php if ($data['keterangan'] == ""): ?>
                              <div>-</div>
                            <?php else: ?>
                              <div><?= $data['keterangan'] ?></div>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                        <?php if ($_SESSION['level'] == "Waiter"): ?>
                          <div class="form-group">
                            <label>Jumlah Pesanan</label>
                            <input type="number" class="form-control" name="jumlah" value="1" min="1" max="20">
                          </div>
                          <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control"></textarea>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <?php if ($_SESSION['level'] == "Waiter"): ?>
                      <button type="submit" class="btn btn-primary">Pesan</button>
                    <?php endif; ?>
                  </div>
                </form>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
  <!-- Pagination for Masakan -->
  <nav>
    <ul class="pagination justify-content-end">
      <?php if ($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?page_minuman=<?= $page - 1; ?>">Previous</a></li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $total_pages_masakan; $i++): ?>
        <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page_minuman=<?= $i; ?>"><?= $i; ?></a></li>
      <?php endfor; ?>
      <?php if ($page < $total_pages_masakan): ?>
        <li class="page-item"><a class="page-link" href="?page_minuman=<?= $page + 1; ?>">Next</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>