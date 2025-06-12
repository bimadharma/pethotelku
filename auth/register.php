<?php include '../layouts/header.php'; ?>
<div class="row justify-content-center" style="margin-top: 10vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-warning text-center">
                <h3 class="my-4">Daftar Akun Baru</h3>
            </div>
            <div class="card-body">
                <form action="proses_register.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
                        <label for="nama">Nama Lengkap</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                        <label for="email">Alamat Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="no_hp" name="no_hp" placeholder="Nomor Hp" required>
                        <label for="no_hp">Nomor Hp</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Alamat lengkap" id="alamat" name="alamat" style="height: 100px" required></textarea>
                        <label for="alamat">Alamat</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Daftar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small">Sudah punya akun? <a href="login.php">Login di sini</a></div>
            </div>
        </div>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>