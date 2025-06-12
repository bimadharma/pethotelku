<?php include 'layouts/header.php'; ?>

<section id="beranda" class="text-center py-5" style="background: url('<?= $base_url ?>/assets/img/hewan.jpg') no-repeat center center; background-size: cover;">
    <div class="container" style="background-color: rgba(255, 255, 255, 0.6); padding: 5rem; border-radius: 15px;">
        <h1 class="display-4 text-black fw-bold">Penitipan Hewan Terbaik dan Terpercaya</h1>
        <p class="lead text-black-50">Tempat di mana hewan kesayangan Anda merasa seperti di rumah sendiri.</p>
        <a href="#layanan" class="btn btn-primary btn-lg mt-3">Lihat Layanan Kami</a>
    </div>
</section>

<section id="tentang" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="text-white fw-bold">Tentang Pet Service</h2>
                <p class="text-white">Kami adalah tim profesional pecinta hewan yang mendedikasikan diri untuk memberikan perawatan terbaik bagi sahabat berbulu Anda. Dengan visi menjadi rumah kedua yang aman dan nyaman, kami membangun fasilitas modern dan merekrut staf berpengalaman untuk menjamin kebahagiaan setiap hewan yang dititipkan.</p>
            </div>
            <div class="col-md-6 text-center">
                <img src="<?= $base_url ?>/assets/img/gambar-landing.jpeg" alt="Logo Pet Service" class="img-fluid" width="450">
            </div>
        </div>
    </div>
</section>

<section id="layanan" class="my-5 py-5 bg-light rounded shadow">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Paket Layanan Kami</h2>
        <div class="row">
            <?php
            $query_layanan = mysqli_query($koneksi, "SELECT * FROM tb_layanan");
            if(mysqli_num_rows($query_layanan) > 0):
                while ($layanan = mysqli_fetch_assoc($query_layanan)):
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center">
                        <div class="icon-circle bg-primary text-white mx-auto mb-3"><i class="bi bi-award fs-1"></i></div>
                        <h4 class="card-title"><?= htmlspecialchars($layanan['nama_layanan']) ?></h4>
                        <p class="card-text text-muted"><?= htmlspecialchars($layanan['deskripsi']) ?></p>
                        <h5 class="card-title fw-bold">Rp <?= number_format($layanan['harga']) ?>,- <small class="text-muted">/hari</small></h5>
                        <a href="<?= $base_url ?>/auth/login.php" class="btn btn-outline-primary mt-3">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
            <?php endwhile; else: ?>
                <p class="text-center">Layanan belum tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


<section id="kontak" class="py-5 bg-light rounded shadow">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Kontak Kami</h2>
            <p class="text-muted">Silakan hubungi kami untuk informasi lebih lanjut mengenai layanan penitipan hewan.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <i class="bi bi-geo-alt-fill fs-2 text-primary mb-3"></i>
                                <h5 class="card-title">Alamat</h5>
                                <p class="card-text text-muted">Jl. Kenangan No. 123, Jakarta Selatan, DKI Jakarta, Indonesia</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 text-center">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <i class="bi bi-envelope-fill fs-2 text-primary mb-3"></i>
                                <h5 class="card-title">Email</h5>
                                <p class="card-text text-muted">info@pethotelku.com</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 text-center">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <i class="bi bi-telephone-fill fs-2 text-primary mb-3"></i>
                                <h5 class="card-title">Telepon / WhatsApp</h5>
                                <p class="card-text text-muted">+62 812-3456-7890</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <h6 class="fw-semibold">Jam Operasional</h6>
                    <p class="text-muted mb-1">Senin - Sabtu: 08.00 – 17.00 WIB</p>
                    <p class="text-muted">Minggu & Hari Libur: Tutup</p>
                </div>
            </div>
        </div>
    </div>
</section>



<?php include 'layouts/footer.php'; ?>