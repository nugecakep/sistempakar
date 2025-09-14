<?php
// Include config file
require_once "includes/config.php";
require_once "includes/functions.php";

// Include header
include "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-spa" style="margin-right: 10px; color: var(--secondary-color);"></i>Selamat Datang di Sistem Pakar Perawatan Kulit</h2>
    </div>
    <div class="card-body">
        <div class="welcome-message">
            <!-- <img src="https://img.icons8.com/color/96/000000/beauty-products.png" alt="Skincare Icon" class="welcome-icon"> -->
            <p>Sistem pakar ini dirancang untuk membantu Anda menentukan jenis perawatan kulit yang sesuai dengan kondisi kulit Anda. Sistem ini menggunakan pendekatan berbasis aturan (rule-based) untuk memberikan rekomendasi berdasarkan:</p>
        </div>
        
        <div class="feature-list">
            <div class="feature-item">
                <i class="fas fa-exclamation-triangle" style="color: var(--warning-color);"></i>
                <span>Faktor resiko yang Anda miliki</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-heartbeat" style="color: var(--danger-color);"></i>
                <span>Gejala yang Anda alami</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-tint" style="color: var(--info-color);"></i>
                <span>Jenis kulit yang teridentifikasi</span>
            </div>
        </div>
        
        <p>Sistem akan menganalisis input Anda dan memberikan rekomendasi:</p>
        
        <div class="feature-list">
            <div class="feature-item">
                <i class="fas fa-tint" style="color: var(--info-color);"></i>
                <span>Jenis kulit yang teridentifikasi</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-exclamation-circle" style="color: var(--danger-color);"></i>
                <span>Kelainan kulit yang terdeteksi</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-flask" style="color: var(--secondary-color);"></i>
                <span>Rekomendasi serum yang sesuai</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-spa" style="color: var(--accent-color);"></i>
                <span>Rekomendasi treatment yang disarankan</span>
            </div>
        </div>
        
        <p class="cta-text">Untuk memulai diagnosis, silakan klik tombol di bawah ini:</p>
        
        <a href="diagnosis.php" class="btn btn-primary btn-lg cta-button">
            <i class="fas fa-stethoscope"></i> Mulai Diagnosis
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-info-circle" style="margin-right: 10px; color: var(--primary-color);"></i>Tentang Sistem Pakar</h2>
    </div>
    <div class="card-body">
        <p>Sistem pakar ini dikembangkan menggunakan metode forward chaining, yaitu metode inferensi yang memulai pencarian dari sekumpulan data atau fakta, kemudian bergerak maju melalui premis-premis untuk menuju kesimpulan.</p>
        
        <div class="info-box">
            <h3>Basis Pengetahuan</h3>
            <p>Basis pengetahuan sistem ini terdiri dari:</p>
            
            <div class="knowledge-stats">
                <div class="stat-item">
                    <div class="stat-icon" style="background-color: var(--warning-color);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">8</span>
                        <span class="stat-label">Faktor Resiko</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="background-color: var(--danger-color);">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">7</span>
                        <span class="stat-label">Gejala</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="background-color: var(--info-color);">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">4</span>
                        <span class="stat-label">Jenis Kulit</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="background-color: var(--danger-color);">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">7</span>
                        <span class="stat-label">Kelainan Kulit</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="background-color: var(--secondary-color);">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">10</span>
                        <span class="stat-label">Serum Perawatan</span>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon" style="background-color: var(--accent-color);">
                        <i class="fas fa-spa"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">10</span>
                        <span class="stat-label">Treatment Perawatan</span>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="more-info">Untuk informasi lebih lanjut tentang sistem pakar ini, silakan kunjungi halaman <a href="about.php" class="link-with-icon"><i class="fas fa-info-circle"></i> Tentang</a>.</p>
    </div>
</div>

<?php
// Include footer
include "includes/footer.php";
?> 