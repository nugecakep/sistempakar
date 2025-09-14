<?php
// Include setup_database file
require_once "includes/setup_database.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Sistem Pakar Perawatan Kulit</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Setup Sistem Pakar Perawatan Kulit</h1>
            <p>Inisialisasi Database dan Tabel</p>
        </div>
    </header>
    
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Status Setup</h2>
            </div>
            <div class="card-body">
                <p>Setup database telah selesai. Silakan kembali ke <a href="index.php">halaman utama</a>.</p>
            </div>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sistem Pakar Perawatan Kulit. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
</body>
</html> 