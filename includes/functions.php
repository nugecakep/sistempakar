<?php
// Include config file
require_once "config.php";

/**
 * Get all risk factors from database or Python model
 */
function getRiskFactors() {
    // Coba ambil dari API Python jika tersedia
    $python_data = callPythonApi('risk_factors', 'GET');
    
    if ($python_data !== false) {
        return $python_data;
    }
    
    // Fallback ke database jika API Python tidak tersedia
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM risk_factors ORDER BY id");
    $risk_factors = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $risk_factors[] = $row;
    }
    
    return $risk_factors;
}

/**
 * Get all symptoms from database or Python model
 */
function getSymptoms() {
    // Coba ambil dari API Python jika tersedia
    $python_data = callPythonApi('symptoms', 'GET');
    
    if ($python_data !== false) {
        return $python_data;
    }
    
    // Fallback ke database jika API Python tidak tersedia
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM symptoms ORDER BY id");
    $symptoms = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $symptoms[] = $row;
    }
    
    return $symptoms;
}

/**
 * Diagnose skin condition based on risk factors and symptoms using Python model
 */
function diagnose($risk_factors, $symptoms) {
    // Metode 1: Menggunakan API Flask (jika berjalan)
    $diagnosis_result = callPythonApi('diagnose', 'POST', [
        'risk_factors' => $risk_factors,
        'symptoms' => $symptoms
    ]);
    
    if ($diagnosis_result !== false) {
        return $diagnosis_result;
    }
    
    // Metode 2: Menggunakan shell_exec untuk memanggil script Python langsung
    $diagnosis_result = callPythonScript($risk_factors, $symptoms);
    
    if ($diagnosis_result !== false) {
        return $diagnosis_result;
    }
    
    // Metode 3: Fallback ke implementasi PHP jika kedua metode di atas gagal
    return diagnosePHP($risk_factors, $symptoms);
}

/**
 * Call Python API
 */
function callPythonApi($endpoint, $method = 'GET', $data = []) {
    $api_url = "http://localhost:5000/{$endpoint}";
    
    $curl = curl_init();
    
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5, // 5 detik timeout
    ];
    
    if ($method === 'POST') {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = json_encode($data);
        $options[CURLOPT_HTTPHEADER] = ['Content-Type: application/json'];
    }
    
    curl_setopt_array($curl, $options);
    curl_setopt($curl, CURLOPT_URL, $api_url);
    
    $response = curl_exec($curl);
    $error = curl_error($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    curl_close($curl);
    
    if ($error || $http_code != 200) {
        return false;
    }
    
    return json_decode($response, true);
}

/**
 * Call Python script directly using shell_exec
 */
function callPythonScript($risk_factors, $symptoms) {
    $risk_factors_str = implode(',', $risk_factors);
    $symptoms_str = implode(',', $symptoms);
    
    // Escape untuk keamanan
    $risk_factors_str = escapeshellarg($risk_factors_str);
    $symptoms_str = escapeshellarg($symptoms_str);
    
    // Jalankan script Python
    $command = "python model.py {$risk_factors_str} {$symptoms_str}";
    $output = shell_exec($command);
    
    if ($output === null) {
        return false;
    }
    
    return json_decode($output, true);
}

/**
 * Fallback diagnosis using PHP implementation
 */
function diagnosePHP($risk_factors, $symptoms) {
    global $conn;
    $diagnosis = [
        'timestamp' => date('Y-m-d H:i:s'),
        'risk_factors' => $risk_factors,
        'symptoms' => $symptoms,
        'skin_types' => [],
        'problems' => [],
        'serums' => [],
        'treatments' => [],
        'rule_matches' => 0,
        'total_rules' => 0,
        'accuracy' => 0
    ];
    
    // Step 1: Determine skin types based on risk factors
    if (!empty($risk_factors)) {
        $risk_ids = implode("','", array_map(function($rf) use ($conn) {
            return mysqli_real_escape_string($conn, $rf);
        }, $risk_factors));
        
        $query = "SELECT DISTINCT st.id, st.name 
                 FROM skin_types st 
                 JOIN risk_to_skin rts ON st.id = rts.skin_type_id 
                 WHERE rts.risk_id IN ('$risk_ids')";
        
        $result = mysqli_query($conn, $query);
        $skin_type_matches = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $diagnosis['skin_types'][] = $row;
            $skin_type_matches++;
        }
        
        // Count total possible skin type rules
        $query_total = "SELECT COUNT(DISTINCT skin_type_id) as total FROM risk_to_skin WHERE risk_id IN ('$risk_ids')";
        $result_total = mysqli_query($conn, $query_total);
        $total_skin_types = mysqli_fetch_assoc($result_total)['total'];
        
        $diagnosis['rule_matches'] += $skin_type_matches;
        $diagnosis['total_rules'] += $total_skin_types;
    }
    
    // Step 2: Determine skin problems based on symptoms
    if (!empty($symptoms)) {
        $symptom_ids = implode("','", array_map(function($s) use ($conn) {
            return mysqli_real_escape_string($conn, $s);
        }, $symptoms));
        
        $query = "SELECT DISTINCT sp.id, sp.name 
                 FROM skin_problems sp 
                 JOIN symptoms_to_problems stp ON sp.id = stp.problem_id 
                 WHERE stp.symptom_id IN ('$symptom_ids')";
        
        $result = mysqli_query($conn, $query);
        $problem_matches = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $diagnosis['problems'][] = $row;
            $problem_matches++;
        }
        
        // Count total possible problem rules
        $query_total = "SELECT COUNT(DISTINCT problem_id) as total FROM symptoms_to_problems WHERE symptom_id IN ('$symptom_ids')";
        $result_total = mysqli_query($conn, $query_total);
        $total_problems = mysqli_fetch_assoc($result_total)['total'];
        
        $diagnosis['rule_matches'] += $problem_matches;
        $diagnosis['total_rules'] += $total_problems;
    }
    
    // Step 3: Get recommended serums based on skin problems
    if (!empty($diagnosis['problems'])) {
        $problem_ids = implode("','", array_map(function($p) {
            return $p['id'];
        }, $diagnosis['problems']));
        
        $query = "SELECT DISTINCT s.id, s.name 
                 FROM serums s 
                 JOIN problems_to_serums pts ON s.id = pts.serum_id 
                 WHERE pts.problem_id IN ('$problem_ids')";
        
        $result = mysqli_query($conn, $query);
        $serum_matches = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $diagnosis['serums'][] = $row;
            $serum_matches++;
        }
        
        $diagnosis['rule_matches'] += $serum_matches;
    }
    
    // Step 4: Get recommended treatments based on skin problems
    if (!empty($diagnosis['problems'])) {
        $problem_ids = implode("','", array_map(function($p) {
            return $p['id'];
        }, $diagnosis['problems']));
        
        $query = "SELECT DISTINCT t.id, t.name 
                 FROM treatments t 
                 JOIN problems_to_treatments ptt ON t.id = ptt.treatment_id 
                 WHERE ptt.problem_id IN ('$problem_ids')";
        
        $result = mysqli_query($conn, $query);
        $treatment_matches = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $diagnosis['treatments'][] = $row;
            $treatment_matches++;
        }
        
        $diagnosis['rule_matches'] += $treatment_matches;
    }
    
    // Calculate accuracy based on rule matches, input factors, and result relevance
    $input_count = count($risk_factors) + count($symptoms);
    $output_count = count($diagnosis['skin_types']) + count($diagnosis['problems']) + 
                    count($diagnosis['serums']) + count($diagnosis['treatments']);
    
    if ($input_count > 0 && $diagnosis['total_rules'] > 0) {
        // Base accuracy on rule match percentage
        $rule_accuracy = ($diagnosis['rule_matches'] / max(1, $diagnosis['total_rules'])) * 100;
        
        // Adjust based on input-output ratio (more outputs from fewer inputs = higher confidence)
        $io_ratio = min(1, $output_count / max(1, $input_count));
        $io_factor = 0.2; // Weight for input-output ratio
        
        // Calculate final accuracy
        $diagnosis['accuracy'] = min(100, $rule_accuracy * (1 - $io_factor) + ($io_ratio * 100 * $io_factor));
        
        // Ensure minimum accuracy of 30% if we have any matches at all
        if ($diagnosis['rule_matches'] > 0 && $diagnosis['accuracy'] < 30) {
            $diagnosis['accuracy'] = 30;
        }
    } else {
        $diagnosis['accuracy'] = 0;
    }
    
    return $diagnosis;
}

/**
 * Save diagnosis result to history
 */
function saveDiagnosisHistory($name, $email, $age, $results) {
    global $conn;
    
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $age = (int)$age;
    $results_json = mysqli_real_escape_string($conn, json_encode($results));
    
    $query = "INSERT INTO diagnosis_history (user_name, user_email, user_age, results) 
              VALUES ('$name', '$email', $age, '$results_json')";
    
    return mysqli_query($conn, $query);
}

/**
 * Get diagnosis history
 */
function getDiagnosisHistory() {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM diagnosis_history ORDER BY timestamp DESC");
    $history = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $row['results'] = json_decode($row['results'], true);
        $history[] = $row;
    }
    
    return $history;
}
?> 