<?php 
include '../layouts/admin_header.php'; 
require_once '../config/database.php';

// Ambil data pendapatan per bulan (status selesai)
$query = "SELECT 
            MONTH(tgl_masuk) AS bulan, 
            SUM(total_harga) AS total 
          FROM tb_pesanan
          WHERE status = 'Selesai' 
          GROUP BY bulan 
          ORDER BY bulan";
$result = mysqli_query($koneksi, $query);

// Siapkan data untuk grafik
$bulan_array = [];
$total_array = [];

$nama_bulan = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
    7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
];

while ($row = mysqli_fetch_assoc($result)) {
    $bulan_array[] = $nama_bulan[(int)$row['bulan']];
    $total_array[] = $row['total'];
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white">Dashboard</h1>
    </div>

    <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan per Bulan</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <canvas id="pendapatanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('pendapatanChart').getContext('2d');
const pendapatanChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($bulan_array); ?>,
        datasets: [{
            label: 'Total Pendapatan (Rp)',
            data: <?= json_encode($total_array); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            borderRadius: 5
        }]
    },
    options: {
         maintainAspectRatio: false,
         aspectRatio: 1,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString();
                    }
                }
            }
        },
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>

<?php include '../layouts/admin_footer.php'; ?>
