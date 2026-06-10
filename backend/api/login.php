<?php

header("Access-Control-Allow-Origin: https://hireiq-project.vercel.app");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// HANDLE PRE-FLIGHT PROPERLY
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json; charset=UTF-8");

include "../config/db.php";

// ================= SAFE INPUT (NO FEATURES REMOVED) =================
$email = '';
$password = '';

// 1. FORM DATA (MOST STABLE)
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
} 
// 2. JSON INPUT (FOR VERCEL AXIOS DEFAULT)
else {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (is_array($data)) {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
    }
}

// ================= VALIDATION =================
if (!$email || !$password) {
    echo json_encode([
        "status" => "error",
        "message" => "No data received"
    ]);
    exit;
}

// ================= USER CHECK =================
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        echo json_encode([
            "status" => "success",
            "message" => "Login Successful",
            "user" => $user
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Wrong Password"
        ]);
    }

} else {

    echo json_encode([
        "status" => "error",
        "message" => "User Not Found"
    ]);
}
?>