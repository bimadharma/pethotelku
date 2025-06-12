<?php 
// /admin/data_users.php
include '../layouts/admin_header.php'; 
?>

<h1 class="h3 mb-4 text-white">Data Pengguna</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna Terdaftar</h6>
    </div>
    <div class="card-body">
        <p class="text-muted">Manajemen data user</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID User</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $koneksi->query("SELECT id_user, nama, email, no_hp, alamat, role FROM tb_users ORDER BY id_user ASC");
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['id_user'] ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['no_hp']) ?></td>
                        <td><?= htmlspecialchars($row['alamat']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/admin_footer.php'; ?>