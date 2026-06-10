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

$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$skills = $_POST['skills'] ?? '';
$company = $_POST['company'] ?? '';
$location = $_POST['location'] ?? '';
$posted_by = $_POST['posted_by'] ?? 1;

if (!$title || !$description || !$skills || !$company || !$location) {
  echo json_encode([
    "status" => "error",
    "message" => "All fields required"
  ]);
  exit;
}

$sql = "INSERT INTO jobs (title, description, skills, company, location, posted_by)
VALUES ('$title','$description','$skills','$company','$location','$posted_by')";

if ($conn->query($sql)) {
  echo json_encode([
    "status" => "success",
    "message" => "Job Added Successfully"
  ]);
} else {
  echo json_encode([
    "status" => "error",
    "message" => $conn->error
  ]);
}
?>