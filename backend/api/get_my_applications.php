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

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
  echo json_encode([
    "status" => "error",
    "message" => "user_id is required"
  ]);
  exit;
}

$sql = "SELECT 
  a.id,
  a.job_id,
  a.user_id,
  a.resume,
  a.resume_skills,
  a.score,
  a.status,
  j.title,
  j.company,
  j.description,
  j.skills
FROM applications a
INNER JOIN jobs j ON a.job_id = j.id
WHERE a.user_id = '$user_id'
ORDER BY a.id DESC";

$result = $conn->query($sql);

if (!$result) {
  echo json_encode([
    "status" => "error",
    "message" => $conn->error
  ]);
  exit;
}

$applications = [];

while ($row = $result->fetch_assoc()) {

  $row['status'] = strtoupper($row['status'] ?? 'PENDING');

  $applications[] = $row;
}

echo json_encode($applications);

?>