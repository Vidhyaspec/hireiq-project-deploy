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

$sql = "
SELECT 
applications.id,
applications.job_id,
applications.user_id,
applications.resume,
applications.resume_skills,
applications.score,
UPPER(IFNULL(applications.status,'PENDING')) AS status,
jobs.title,
jobs.company,
users.name AS user_name
FROM applications
INNER JOIN jobs ON applications.job_id = jobs.id
INNER JOIN users ON applications.user_id = users.id
ORDER BY applications.id DESC
";

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
  $applications[] = $row;
}

echo json_encode($applications);

?>