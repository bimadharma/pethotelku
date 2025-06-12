</main>
    </div>
    </div>

    <!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin logout dari sistem?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="<?= $base_url ?>/auth/logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </div>
</div>

<script src="<?= $base_url ?>/assets/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleButton = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar-wrapper");
    const icon = document.getElementById("menu-icon");

    toggleButton.addEventListener("click", () => {
        sidebar.classList.toggle("active");

        // Ganti ikon antara bi-list dan bi-x
        if (icon.classList.contains("bi-list")) {
            icon.classList.remove("bi-list");
            icon.classList.add("bi-x");
        } else {
            icon.classList.remove("bi-x");
            icon.classList.add("bi-list");
        }
    });
</script>
</body>
</html>