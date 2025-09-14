<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob: https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.gstatic.com https://img.icons8.com;">
    <title><?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="https://img.icons8.com/color/48/000000/beauty-products.png">
    <script src="js/form.js" defer></script>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1><i class="fas fa-spa" style="margin-right: 12px; color: var(--secondary-color);"></i><?php echo SITE_NAME; ?></h1>
            <p>Sistem Pakar untuk Menentukan Jenis Perawatan Kulit Berdasarkan Faktor Resiko, Jenis Kulit, dan Gejala</p>
        </div>
    </header>
    
    <div class="container">
        <nav class="nav">
            <div class="nav-item">
                <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Beranda
                </a>
            </div>
            <div class="nav-item">
                <a href="diagnosis.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'diagnosis.php' ? 'active' : ''; ?>">
                    <i class="fas fa-stethoscope"></i> Diagnosis
                </a>
            </div>
            <div class="nav-item">
                <a href="history.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i> Riwayat Diagnosis
                </a>
            </div>
            <div class="nav-item">
                <a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
                    <i class="fas fa-info-circle"></i> Tentang
                </a>
            </div>
        </nav>
        
        <?php if (isset($alert_message) && isset($alert_type)): ?>
        <div class="alert alert-<?php echo $alert_type; ?>">
            <i class="fas fa-<?php echo $alert_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?>" style="margin-right: 8px;"></i>
            <?php echo $alert_message; ?>
        </div>
        <?php endif; ?> 