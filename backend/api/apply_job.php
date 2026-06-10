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

$user_id = $_POST['user_id'] ?? null;
$job_id = $_POST['job_id'] ?? null;
$resume_skills = $_POST['resume_skills'] ?? '';
$score = $_POST['score'] ?? 0;

$status = "PENDING"; // 🔥 FIXED ALWAYS

if (!$user_id || !$job_id) {
  echo json_encode([
    "status" => "error",
    "message" => "Missing user or job"
  ]);
  exit;
}

if (!isset($_FILES['resume'])) {
  echo json_encode([
    "status" => "error",
    "message" => "Resume required"
  ]);
  exit;
}

$resume = time() . "_" . $_FILES['resume']['name'];

if (!move_uploaded_file($_FILES['resume']['tmp_name'], "../uploads/" . $resume)) {
  echo json_encode([
    "status" => "error",
    "message" => "File upload failed"
  ]);
  exit;
}

/* INSERT */
$sql = "INSERT INTO applications 
(user_id, job_id, resume, resume_skills, score, status)
VALUES
('$user_id','$job_id','$resume','$resume_skills','$score','$status')";

if ($conn->query($sql)) {

  /* GET USER DETAILS */
  $userQuery = $conn->query("
    SELECT name,email
    FROM users
    WHERE id='$user_id'
  ");

  $user = $userQuery->fetch_assoc();

  /* GET JOB DETAILS */
  $jobQuery = $conn->query("
    SELECT title,company
    FROM jobs
    WHERE id='$job_id'
  ");

  $job = $jobQuery->fetch_assoc();

  if ($user && $job) {

    $subject = "Application Submitted Successfully";

    $body = "
    <div style='max-width:650px;margin:auto;font-family:Segoe UI,Arial,sans-serif;background:#ffffff;border-radius:18px;overflow:hidden;'>

      <div style='background:linear-gradient(135deg,#2563eb,#7c3aed);padding:25px;text-align:center;color:white;'>

        <h1>HireIQ</h1>

        <p>Your application has been received</p>

      </div>

      <div style='padding:30px;'>

        <h2>Hello {$user['name']} 👋</h2>

        <p>
          Thank you for applying through HireIQ.
        </p>

        <p>
          <b>Job Title:</b> {$job['title']}
        </p>

        <p>
          <b>Company:</b> {$job['company']}
        </p>

        <p>
          <b>Current Status:</b>
          <span style='color:#f59e0b;font-weight:bold;'>
          PENDING
          </span>
        </p>

        <p>
          Our recruitment team will review your application shortly.
        </p>

      </div>

      <div style='background:#0f172a;color:white;padding:20px;text-align:center;'>

        © 2026 HireIQ

      </div>

    </div>
    ";

    sendMail(
      $user['email'],
      $user['name'],
      $subject,
      $body
    );
  }

  echo json_encode([
    "status" => "success",
    "message" => "Application Submitted"
  ]);

} else {
  echo json_encode([
    "status" => "error",
    "message" => $conn->error
  ]);
}

?>