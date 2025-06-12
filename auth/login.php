<?php include '../layouts/header.php'; ?>
<div class="row justify-content-center" style="margin-top: 10vh;">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-warning text-white text-center">
                <h3 class="my-4 text-black">Login Akun</h3>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['flash_message'])) {
                    $message = $_SESSION['flash_message'];
                    unset($_SESSION['flash_message']);
                    echo "<div class='alert alert-{$message['type']} alert-dismissible fade show' role='alert'>
                            {$message['text']}
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                }
                ?>
                <form action="proses_login.php" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                        <label for="email">Alamat Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Login</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small">Belum punya akun? <a href="register.php">Daftar sekarang!</a></div>
            </div>
        </div>
    </div>
</div>
<?php include '../layouts/footer.php'; ?>