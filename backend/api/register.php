<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/db.php";
include "send_email.php";

/* READ JSON INPUT */
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

/* DEBUG FALLBACK */
if (!$data) {
    echo json_encode([
        "status" => "error",
        "message" => "No data received",
        "raw" => $raw
    ]);
    exit;
}

$name = $data['name'] ?? null;
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
$role = $data['role'] ?? null;

/* VALIDATION */
if (!$name || !$email || !$password || !$role) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing fields"
    ]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* INSERT */
$sql = "INSERT INTO users(name,email,password,role)
VALUES('$name','$email','$hashedPassword','$role')";

if ($conn->query($sql)) {

$subject = "Welcome to HireIQ ";

$body = "
<div style='max-width:650px;margin:auto;font-family:Segoe UI,Arial,sans-serif;background:#ffffff;border-radius:18px;overflow:hidden;'>

<div style='background:linear-gradient(135deg,#2563eb,#7c3aed);padding:25px;text-align:center;color:white;'>

<h1>Welcome to HireIQ 🚀</h1>

</div>

<div style='padding:30px;'>

<h2>Hello {$name} 👋</h2>

<p>
Your account has been created successfully.
</p>

<p>
You can now:
</p>

<ul>
<li>Apply for jobs</li>
<li>Track application status</li>
<li>Build your profile</li>
<li>Receive hiring updates</li>
</ul>

<p>
Thank you for joining HireIQ.
</p>

</div>

<div style='background:#0f172a;color:white;padding:20px;text-align:center;'>

© 2026 HireIQ

</div>

</div>
";

sendMail(
    $email,
    $name,
    $subject,
    $body
);

    echo json_encode([
        "status" => "success",
        "message" => "Registered Successfully ✅"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "DB Error",
        "db_error" => $conn->error
    ]);
}

?>