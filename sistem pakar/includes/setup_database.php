<?php
// Include config file
require_once "config.php";

// Create tables if they don't exist
$tables = [
    // Risk Factors table
    "CREATE TABLE IF NOT EXISTS risk_factors (
        id VARCHAR(10) PRIMARY KEY,
        description TEXT NOT NULL
    )",
    
    // Skin Types table
    "CREATE TABLE IF NOT EXISTS skin_types (
        id VARCHAR(10) PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )",
    
    // Risk to Skin Type mapping
    "CREATE TABLE IF NOT EXISTS risk_to_skin (
        risk_id VARCHAR(10),
        skin_type_id VARCHAR(10),
        PRIMARY KEY (risk_id, skin_type_id),
        FOREIGN KEY (risk_id) REFERENCES risk_factors(id),
        FOREIGN KEY (skin_type_id) REFERENCES skin_types(id)
    )",
    
    // Symptoms table
    "CREATE TABLE IF NOT EXISTS symptoms (
        id VARCHAR(10) PRIMARY KEY,
        description TEXT NOT NULL
    )",
    
    // Skin Problems table
    "CREATE TABLE IF NOT EXISTS skin_problems (
        id VARCHAR(10) PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )",
    
    // Symptoms to Problems mapping
    "CREATE TABLE IF NOT EXISTS symptoms_to_problems (
        symptom_id VARCHAR(10),
        problem_id VARCHAR(10),
        PRIMARY KEY (symptom_id, problem_id),
        FOREIGN KEY (symptom_id) REFERENCES symptoms(id),
        FOREIGN KEY (problem_id) REFERENCES skin_problems(id)
    )",
    
    // Serums table
    "CREATE TABLE IF NOT EXISTS serums (
        id VARCHAR(10) PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )",
    
    // Problems to Serums mapping
    "CREATE TABLE IF NOT EXISTS problems_to_serums (
        problem_id VARCHAR(10),
        serum_id VARCHAR(10),
        PRIMARY KEY (problem_id, serum_id),
        FOREIGN KEY (problem_id) REFERENCES skin_problems(id),
        FOREIGN KEY (serum_id) REFERENCES serums(id)
    )",
    
    // Treatments table
    "CREATE TABLE IF NOT EXISTS treatments (
        id VARCHAR(10) PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )",
    
    // Problems to Treatments mapping
    "CREATE TABLE IF NOT EXISTS problems_to_treatments (
        problem_id VARCHAR(10),
        treatment_id VARCHAR(10),
        PRIMARY KEY (problem_id, treatment_id),
        FOREIGN KEY (problem_id) REFERENCES skin_problems(id),
        FOREIGN KEY (treatment_id) REFERENCES treatments(id)
    )",
    
    // Diagnosis History table
    "CREATE TABLE IF NOT EXISTS diagnosis_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_name VARCHAR(100),
        user_email VARCHAR(100),
        user_age INT,
        results TEXT
    )"
];

// Execute each table creation query
$success = true;
foreach ($tables as $sql) {
    if (!mysqli_query($conn, $sql)) {
        echo "Error creating table: " . mysqli_error($conn) . "<br>";
        $success = false;
    }
}

// Insert initial data if tables were created successfully
if ($success) {
    // Insert Risk Factors
    $risk_factors = [
        ['FR01', 'Bekerja di ruangan AC (air conditioner)'],
        ['FR02', 'Keriput'],
        ['FR03', 'Memiliki alergi'],
        ['FR04', 'Mengikuti program KB (Keluarga Berencana)'],
        ['FR05', 'Menyukai makanan pedas'],
        ['FR06', 'Sering menyentuh muka'],
        ['FR07', 'Sering memakan makanan berprotein tinggi'],
        ['FR08', 'Memiliki kulit berpori-pori besar']
    ];
    
    foreach ($risk_factors as $rf) {
        $sql = "INSERT IGNORE INTO risk_factors (id, description) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $rf[0], $rf[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Skin Types
    $skin_types = [
        ['JK01', 'Kulit Normal'],
        ['JK02', 'Kulit Kering'],
        ['JK03', 'Kulit Berminyak'],
        ['JK04', 'Kulit Kombinasi']
    ];
    
    foreach ($skin_types as $st) {
        $sql = "INSERT IGNORE INTO skin_types (id, name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $st[0], $st[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Risk to Skin Type mappings
    $risk_to_skin = [
        ['FR01', 'JK02'], ['FR01', 'JK04'],
        ['FR02', 'JK02'], ['FR02', 'JK04'],
        ['FR03', 'JK02'], ['FR03', 'JK04'],
        ['FR04', 'JK02'], ['FR04', 'JK04'],
        ['FR05', 'JK03'], ['FR05', 'JK04'],
        ['FR06', 'JK03'], ['FR06', 'JK04'],
        ['FR07', 'JK03'], ['FR07', 'JK04'],
        ['FR08', 'JK03'], ['FR08', 'JK04']
    ];
    
    foreach ($risk_to_skin as $rs) {
        $sql = "INSERT IGNORE INTO risk_to_skin (risk_id, skin_type_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $rs[0], $rs[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Symptoms
    $symptoms = [
        ['G01', 'Bintik-bintik hitam di wajah'],
        ['G02', 'Garis halus disekitar mata atau mulut'],
        ['G03', 'Bintik-bintik merah di wajah'],
        ['G04', 'Bernanah'],
        ['G05', 'Bintik-bintik hitam disekitar hidung atau pipi'],
        ['G06', 'Bintik-bintik putih dan keras'],
        ['G07', 'Tidak ada gejala']
    ];
    
    foreach ($symptoms as $s) {
        $sql = "INSERT IGNORE INTO symptoms (id, description) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $s[0], $s[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Skin Problems
    $skin_problems = [
        ['KK01', 'Flek'],
        ['KK02', 'Keriput'],
        ['KK03', 'Jerawat Tidak Meradang'],
        ['KK04', 'Jerawat Meradang'],
        ['KK05', 'Komedo'],
        ['KK06', 'Milia'],
        ['KK07', 'Tidak ada kelainan kulit']
    ];
    
    foreach ($skin_problems as $sp) {
        $sql = "INSERT IGNORE INTO skin_problems (id, name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $sp[0], $sp[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Symptoms to Problems mappings
    $symptoms_to_problems = [
        ['G01', 'KK01'],
        ['G02', 'KK02'],
        ['G03', 'KK03'],
        ['G04', 'KK04'],
        ['G05', 'KK05'],
        ['G06', 'KK06'],
        ['G07', 'KK07']
    ];
    
    foreach ($symptoms_to_problems as $sp) {
        $sql = "INSERT IGNORE INTO symptoms_to_problems (symptom_id, problem_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $sp[0], $sp[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Serums
    $serums = [
        ['S01', 'Vitamin C Brightening Serum'],
        ['S02', 'Niacinamide Spot Corrector'],
        ['S03', 'Alpha Arbutin Serum'],
        ['S04', 'Hyaluronic Acid Hydration Serum'],
        ['S05', 'Kojic Acid Pigment Fader'],
        ['S06', 'Retinol Anti-Wrinkle Serum'],
        ['S07', 'Salicylic Acid BHA Serum'],
        ['S08', 'Tea Tree Anti-Acne Serum'],
        ['S09', 'Charcoal Pore Control Serum'],
        ['S10', 'Centella Soothing Serum']
    ];
    
    foreach ($serums as $s) {
        $sql = "INSERT IGNORE INTO serums (id, name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $s[0], $s[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Problems to Serums mappings
    $problems_to_serums = [
        ['KK01', 'S01'], ['KK01', 'S02'], ['KK01', 'S03'], ['KK01', 'S04'], ['KK01', 'S05'],
        ['KK02', 'S01'], ['KK02', 'S02'], ['KK02', 'S03'], ['KK02', 'S04'], ['KK02', 'S06'],
        ['KK03', 'S07'], ['KK03', 'S08'], ['KK03', 'S09'],
        ['KK04', 'S07'], ['KK04', 'S08'], ['KK04', 'S09'],
        ['KK05', 'S07'], ['KK05', 'S08'], ['KK05', 'S09'],
        ['KK06', 'S10'],
        ['KK07', 'S01']
    ];
    
    foreach ($problems_to_serums as $ps) {
        $sql = "INSERT IGNORE INTO problems_to_serums (problem_id, serum_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $ps[0], $ps[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Treatments
    $treatments = [
        ['T01', 'Whitening Facial'],
        ['T02', 'Anti-Spot Treatment'],
        ['T03', 'Brightening Laser'],
        ['T04', 'Anti-Aging Facial'],
        ['T05', 'Collagen Lifting'],
        ['T06', 'Acne Facial'],
        ['T07', 'Deep Pore Cleansing'],
        ['T08', 'Anti-Inflammatory Therapy'],
        ['T09', 'Blackhead Removal Treatment'],
        ['T10', 'Skin Rejuvenation & Maintenance']
    ];
    
    foreach ($treatments as $t) {
        $sql = "INSERT IGNORE INTO treatments (id, name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $t[0], $t[1]);
        mysqli_stmt_execute($stmt);
    }
    
    // Insert Problems to Treatments mappings
    $problems_to_treatments = [
        ['KK01', 'T01'], ['KK01', 'T02'], ['KK01', 'T03'],
        ['KK02', 'T04'], ['KK02', 'T05'],
        ['KK03', 'T06'], ['KK03', 'T07'],
        ['KK04', 'T06'], ['KK04', 'T07'],
        ['KK05', 'T05'],  // DIPERBAIKI: Komedo -> Collagen Lifting
        ['KK06', 'T08'],
        ['KK07', 'T09'], ['KK07', 'T10']
    ];
    
    foreach ($problems_to_treatments as $pt) {
        $sql = "INSERT IGNORE INTO problems_to_treatments (problem_id, treatment_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $pt[0], $pt[1]);
        mysqli_stmt_execute($stmt);
    }
    
    echo "Database setup completed successfully!";
} else {
    echo "There were errors setting up the database.";
}

mysqli_close($conn);
?> 