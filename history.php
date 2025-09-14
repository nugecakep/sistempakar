<?php
// Include config file
require_once "includes/config.php";
require_once "includes/functions.php";

// Get diagnosis history
$history = getDiagnosisHistory();

// Include header
include "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        <h2>Riwayat Diagnosis</h2>
    </div>
    <div class="card-body">
        <?php if (empty($history)): ?>
        <p>Belum ada riwayat diagnosis.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Usia</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $item): ?>
                    <tr>
                        <td><?php echo $item['timestamp']; ?></td>
                        <td><?php echo htmlspecialchars($item['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['user_email']); ?></td>
                        <td><?php echo $item['user_age']; ?></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" onclick="toggleDetails('details-<?php echo $item['id']; ?>')">Lihat Detail</button>
                        </td>
                    </tr>
                    <tr id="details-<?php echo $item['id']; ?>" style="display: none;">
                        <td colspan="5">
                            <div class="result-section">
                                <h3>Jenis Kulit</h3>
                                <?php if (!empty($item['results']['skin_types'])): ?>
                                    <?php foreach ($item['results']['skin_types'] as $skin_type): ?>
                                    <div class="result-item">
                                        <span><?php echo $skin_type['id']; ?>:</span> <?php echo $skin_type['name']; ?>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Tidak dapat menentukan jenis kulit.</p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="result-section">
                                <h3>Kelainan Kulit</h3>
                                <?php if (!empty($item['results']['problems'])): ?>
                                    <?php foreach ($item['results']['problems'] as $problem): ?>
                                    <div class="result-item">
                                        <span><?php echo $problem['id']; ?>:</span> <?php echo $problem['name']; ?>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Tidak terdeteksi kelainan kulit.</p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="result-section">
                                <h3>Rekomendasi Serum</h3>
                                <?php if (!empty($item['results']['serums'])): ?>
                                    <?php foreach ($item['results']['serums'] as $serum): ?>
                                    <div class="result-item">
                                        <span><?php echo $serum['id']; ?>:</span> <?php echo $serum['name']; ?>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Tidak ada rekomendasi serum.</p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="result-section">
                                <h3>Rekomendasi Treatment</h3>
                                <?php if (!empty($item['results']['treatments'])): ?>
                                    <?php foreach ($item['results']['treatments'] as $treatment): ?>
                                    <div class="result-item">
                                        <span><?php echo $treatment['id']; ?>:</span> <?php echo $treatment['name']; ?>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Tidak ada rekomendasi treatment.</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleDetails(id) {
    var element = document.getElementById(id);
    if (element.style.display === "none") {
        element.style.display = "table-row";
    } else {
        element.style.display = "none";
    }
}
</script>

<?php
// Include footer
include "includes/footer.php";
?> 