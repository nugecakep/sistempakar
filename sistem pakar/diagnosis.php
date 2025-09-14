<?php
// Include config file
require_once "includes/config.php";
require_once "includes/functions.php";

// Process form submission
$diagnosis_result = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['diagnose'])) {
    $risk_factors = isset($_POST['risk_factors']) ? $_POST['risk_factors'] : [];
    $symptoms = isset($_POST['symptoms']) ? $_POST['symptoms'] : [];
    
    // Perform diagnosis
    $diagnosis_result = diagnose($risk_factors, $symptoms);
    
    // Save to history if user info is provided
    if (isset($_POST['save_history']) && $_POST['save_history'] == 1) {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
        
        if (!empty($name) && !empty($email)) {
            saveDiagnosisHistory($name, $email, $age, $diagnosis_result);
            $alert_message = "Hasil diagnosis berhasil disimpan.";
            $alert_type = "success";
        }
    }
}

// Get risk factors and symptoms for the form
$risk_factors = getRiskFactors();
$symptoms = getSymptoms();

// Include header
include "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-stethoscope" style="margin-right: 10px; color: var(--primary-color);"></i>Diagnosis Perawatan Kulit</h2>
        <?php
        // Cek status API Python
        $api_status = callPythonApi('risk_factors', 'GET') !== false;
        ?>
        <div style="float: right; font-size: 0.8rem;">
            Model: <span style="color: <?php echo $api_status ? '#1cc88a' : '#858796'; ?>; font-weight: bold;">
                <?php echo $api_status ? 'Python (API)' : 'PHP (Database)'; ?>
            </span>
        </div>
    </div>
    <div class="card-body">
        <?php if (!$diagnosis_result): ?>
        <form id="diagnosis-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-intro">
                <div class="form-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="form-intro-text">
                    <h3>Analisis Kebutuhan Kulit Anda</h3>
                    <p>Isi formulir di bawah ini untuk mendapatkan rekomendasi perawatan yang sesuai dengan kondisi kulit Anda.</p>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-exclamation-triangle" style="color: var(--warning-color);"></i>
                    </div>
                    <div class="section-title">
                        <h4>Faktor Resiko</h4>
                        <p class="form-text">Pilih faktor resiko yang Anda miliki:</p>
                    </div>
                </div>
                

                <div class="checkbox-group">
                    <?php foreach ($risk_factors as $rf): ?>
                    <div class="checkbox-item">
                        <div class="custom-checkbox">
                            <input type="checkbox" name="risk_factors[]" id="rf-<?php echo $rf['id']; ?>" value="<?php echo $rf['id']; ?>">
                            <label for="rf-<?php echo $rf['id']; ?>">
                                <span class="checkbox-icon"></span>
                                <span class="checkbox-text"><?php echo $rf['description']; ?></span>
                            </label>
                        </div>
                        <?php if (!empty($rf['info'])): ?>
                        <div class="tooltip">
                            <i class="fas fa-info-circle" style="color: var(--info-color);"></i>
                            <span class="tooltip-text"><?php echo $rf['info']; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-heartbeat" style="color: var(--danger-color);"></i>
                    </div>
                    <div class="section-title">
                        <h4>Gejala</h4>
                        <p class="form-text">Pilih gejala yang Anda alami:</p>
                    </div>
                </div>
                

                <div class="checkbox-group">
                    <?php foreach ($symptoms as $s): ?>
                    <div class="checkbox-item">
                        <div class="custom-checkbox">
                            <input type="checkbox" name="symptoms[]" id="s-<?php echo $s['id']; ?>" value="<?php echo $s['id']; ?>">
                            <label for="s-<?php echo $s['id']; ?>">
                                <span class="checkbox-icon"></span>
                                <span class="checkbox-text"><?php echo $s['description']; ?></span>
                            </label>
                        </div>
                        <?php if (!empty($s['info'])): ?>
                        <div class="tooltip">
                            <i class="fas fa-info-circle" style="color: var(--info-color);"></i>
                            <span class="tooltip-text"><?php echo $s['info']; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="form-submit">
                <button type="submit" name="diagnose" value="1" class="btn btn-primary btn-lg btn-block">
                    <i class="fas fa-search"></i> Analisis Kulit Saya
                </button>
            </div>
        </form>
        <?php else: ?>
        <div id="diagnosis-results">
            <h3><i class="fas fa-clipboard-check" style="margin-right: 10px; color: var(--primary-color);"></i>Hasil Diagnosis</h3>
            <p class="timestamp"><i class="far fa-clock"></i> Waktu diagnosis: <?php echo $diagnosis_result['timestamp']; ?></p>
            
            <?php if (isset($diagnosis_result['accuracy'])): ?>
            <div class="result-section">
                <h3><i class="fas fa-chart-pie" style="margin-right: 10px; color: var(--primary-color);"></i>Akurasi Diagnosis</h3>
                <div class="progress" style="height: 25px; background-color: #eaecf4; border-radius: 0.35rem; margin-bottom: 10px;">
                    <?php 
                    // Ensure accuracy is capped at 100%
                    $accuracy = min((float)$diagnosis_result['accuracy'], 100);
                    $accuracy = number_format($accuracy, 1);
                    
                    // Determine color based on accuracy level
                    if ($accuracy >= 90) {
                        $color = '#1cc88a'; // Green for high accuracy
                    } elseif ($accuracy >= 70) {
                        $color = '#36b9cc'; // Info color for good accuracy
                    } elseif ($accuracy >= 50) {
                        $color = '#f6c23e'; // Warning for moderate accuracy
                    } else {
                        $color = '#e74a3b'; // Danger for low accuracy
                    }
                    ?>
                    <div class="progress-bar" role="progressbar" style="width: <?php echo $accuracy; ?>%; background-color: <?php echo $color; ?>;" 
                         aria-valuenow="<?php echo $accuracy; ?>" aria-valuemin="0" aria-valuemax="100">
                        <?php echo $accuracy; ?>%
                    </div>
                </div>
                <p class="accuracy-info">
                    <?php if ($accuracy >= 90): ?>
                        <i class="fas fa-check-circle" style="color: #1cc88a;"></i> Akurasi sangat tinggi. Rekomendasi dapat diandalkan.
                    <?php elseif ($accuracy >= 70): ?>
                        <i class="fas fa-info-circle" style="color: #36b9cc;"></i> Akurasi baik. Rekomendasi cukup dapat diandalkan.
                    <?php elseif ($accuracy >= 50): ?>
                        <i class="fas fa-exclamation-circle" style="color: #f6c23e;"></i> Akurasi sedang. Pertimbangkan untuk konsultasi lebih lanjut.
                    <?php else: ?>
                        <i class="fas fa-exclamation-triangle" style="color: #e74a3b;"></i> Akurasi rendah. Disarankan untuk konsultasi langsung dengan ahli.
                    <?php endif; ?>
                </p>
                
                <div class="model-info">
                    <h4><i class="fas fa-code-branch"></i> Model Diagnosis</h4>
                    <div class="model-details">
                        <?php if ($api_status): ?>
                            <div class="model-badge python-model">
                                <i class="fab fa-python"></i> Python Model
                            </div>
                            <p>Diagnosis menggunakan model machine learning berbasis Python dengan forward chaining untuk analisis faktor risiko dan gejala.</p>
                        <?php else: ?>
                            <div class="model-badge php-model">
                                <i class="fab fa-php"></i> PHP Model
                            </div>
                            <p>Diagnosis menggunakan model rule-based dengan algoritma forward chaining berbasis database untuk analisis faktor risiko dan gejala.</p>
                        <?php endif; ?>
                        
                        <div class="model-metrics">
                            <div class="metric">
                                <span class="metric-label">Input Factors:</span>
                                <span class="metric-value"><?php echo count($_POST['risk_factors'] ?? []); ?> faktor risiko, <?php echo count($_POST['symptoms'] ?? []); ?> gejala</span>
                            </div>
                            <div class="metric">
                                <span class="metric-label">Rule Matches:</span>
                                <span class="metric-value"><?php echo isset($diagnosis_result['rule_matches']) ? $diagnosis_result['rule_matches'] : count($diagnosis_result['skin_types'] ?? []) + count($diagnosis_result['problems'] ?? []); ?></span>
                            </div>
                            <div class="metric">
                                <span class="metric-label">Confidence Score:</span>
                                <span class="metric-value"><?php echo $accuracy; ?>%</span>
                            </div>
                            <?php if (isset($diagnosis_result['overall_certainty_factor'])): ?>
                            <div class="metric">
                                <span class="metric-label">Certainty Factor:</span>
                                <span class="metric-value"><?php echo round($diagnosis_result['overall_certainty_factor'], 3); ?></span>
                            </div>
                            <div class="metric">
                                <span class="metric-label">Confidence Level:</span>
                                <span class="metric-value"><?php echo $diagnosis_result['overall_confidence_level'] ?? 'N/A'; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="result-section">
                <h3><i class="fas fa-tint" style="margin-right: 10px; color: var(--info-color);"></i>Jenis Kulit</h3>
                <?php if (!empty($diagnosis_result['skin_types'])): ?>
                    <?php foreach ($diagnosis_result['skin_types'] as $skin_type): ?>
                    <?php 
                        $skin_class = '';
                        if (stripos($skin_type['name'], 'normal') !== false) $skin_class = 'skin-normal';
                        elseif (stripos($skin_type['name'], 'kering') !== false) $skin_class = 'skin-dry';
                        elseif (stripos($skin_type['name'], 'berminyak') !== false) $skin_class = 'skin-oily';
                        elseif (stripos($skin_type['name'], 'kombinasi') !== false) $skin_class = 'skin-combination';
                    ?>
                    <div class="result-item <?php echo $skin_class; ?>">
                        <div class="skin-type-icon"></div>
                        <div class="item-content">
                            <span class="item-name"><?php echo $skin_type['id']; ?>: <?php echo $skin_type['name']; ?></span>
                            <?php if (isset($skin_type['certainty_factor'])): ?>
                            <div class="cf-info">
                                <span class="cf-value">CF: <?php echo $skin_type['certainty_factor']; ?></span>
                                <span class="cf-level">(<?php echo $skin_type['confidence_level']; ?>)</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak dapat menentukan jenis kulit berdasarkan faktor resiko yang dipilih.</p>
                <?php endif; ?>
            </div>
            
            <div class="result-section">
                <h3><i class="fas fa-exclamation-circle" style="margin-right: 10px; color: var(--danger-color);"></i>Kelainan Kulit</h3>
                <?php if (!empty($diagnosis_result['problems'])): ?>
                    <?php foreach ($diagnosis_result['problems'] as $problem): ?>
                    <div class="result-item">
                        <div class="item-content">
                            <span class="item-name"><?php echo $problem['id']; ?>: <?php echo $problem['name']; ?></span>
                            <?php if (isset($problem['certainty_factor'])): ?>
                            <div class="cf-info">
                                <span class="cf-value">CF: <?php echo $problem['certainty_factor']; ?></span>
                                <span class="cf-level">(<?php echo $problem['confidence_level']; ?>)</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak terdeteksi kelainan kulit berdasarkan gejala yang dipilih.</p>
                <?php endif; ?>
            </div>
            
            <div class="result-section">
                <h3><i class="fas fa-flask" style="margin-right: 10px; color: var(--secondary-color);"></i>Rekomendasi Serum</h3>
                <?php if (!empty($diagnosis_result['serums'])): ?>
                    <?php foreach ($diagnosis_result['serums'] as $serum): ?>
                    <div class="result-item">
                        <div class="item-content">
                            <span class="item-name"><?php echo $serum['id']; ?>: <?php echo $serum['name']; ?></span>
                            <?php if (isset($serum['certainty_factor'])): ?>
                            <div class="cf-info">
                                <span class="cf-value">CF: <?php echo $serum['certainty_factor']; ?></span>
                                <span class="cf-level">(<?php echo $serum['confidence_level']; ?>)</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada rekomendasi serum berdasarkan kelainan kulit yang terdeteksi.</p>
                <?php endif; ?>
            </div>
            
            <div class="result-section">
                <h3><i class="fas fa-spa" style="margin-right: 10px; color: var(--accent-color);"></i>Rekomendasi Treatment</h3>
                <?php if (!empty($diagnosis_result['treatments'])): ?>
                    <?php foreach ($diagnosis_result['treatments'] as $treatment): ?>
                    <div class="result-item">
                        <div class="item-content">
                            <span class="item-name"><?php echo $treatment['id']; ?>: <?php echo $treatment['name']; ?></span>
                            <?php if (isset($treatment['certainty_factor'])): ?>
                            <div class="cf-info">
                                <span class="cf-value">CF: <?php echo $treatment['certainty_factor']; ?></span>
                                <span class="cf-level">(<?php echo $treatment['confidence_level']; ?>)</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Tidak ada rekomendasi treatment berdasarkan kelainan kulit yang terdeteksi.</p>
                <?php endif; ?>
            </div>
            
            <div class="action-buttons">
                <button type="button" id="print-results" class="btn btn-primary">
                    <i class="fas fa-print"></i> Cetak Hasil
                </button>
                <button type="button" id="save-pdf" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Simpan PDF
                </button>
                <button type="button" id="save-results" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan ke Riwayat
                </button>
                <a href="diagnosis.php" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Diagnosis Baru
                </a>
            </div>
            
            <div id="user-info-form" style="margin-top: 20px; display: none;">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h3><i class="fas fa-user" style="margin-right: 10px; color: var(--primary-color);"></i>Informasi Pengguna</h3>
                    
                    <div class="form-group">
                        <label for="name"><i class="fas fa-id-card"></i> Nama</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="age"><i class="fas fa-birthday-cake"></i> Usia</label>
                        <input type="number" name="age" id="age" class="form-control" min="1" max="120">
                    </div>
                    
                    <?php foreach ($_POST['risk_factors'] ?? [] as $rf): ?>
                    <input type="hidden" name="risk_factors[]" value="<?php echo htmlspecialchars($rf); ?>">
                    <?php endforeach; ?>
                    
                    <?php foreach ($_POST['symptoms'] ?? [] as $s): ?>
                    <input type="hidden" name="symptoms[]" value="<?php echo htmlspecialchars($s); ?>">
                    <?php endforeach; ?>
                    
                    <input type="hidden" name="save_history" value="1">
                    <input type="hidden" name="diagnose" value="1">
                    
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
include "includes/footer.php";
?> 