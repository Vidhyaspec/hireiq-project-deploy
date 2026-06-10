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

/* GET DATA */
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
  echo json_encode([
    "status" => "error",
    "message" => "No data received"
  ]);
  exit;
}

$id = $data->id;
$status = strtoupper($data->status); // 🔥 FIX CAPS HERE

/* UPDATE STATUS */
$sql = "UPDATE applications SET status='$status' WHERE id='$id'";

if ($conn->query($sql)) {

  /* GET USER + JOB DETAILS */
  $getUser = $conn->query("
    SELECT users.name, users.email, jobs.title
    FROM applications
    INNER JOIN users ON applications.user_id = users.id
    INNER JOIN jobs ON applications.job_id = jobs.id
    WHERE applications.id='$id'
  ");

  $user = $getUser->fetch_assoc();

  if ($user) {

    $subject = "Application Status Updated";

    $color = "#f59e0b";
$message = "Your application is currently under review.";

if($status == "SHORTLISTED"){
    $color = "#22c55e";
    $message = "🎉 Congratulations! You have been shortlisted.";
}

if($status == "REJECTED"){
    $color = "#ef4444";
    $message = "Thank you for applying. We encourage you to apply again in the future.";
}

$body = "
<div style='max-width:650px;margin:auto;font-family:Segoe UI,Arial,sans-serif;background:#ffffff;border-radius:18px;overflow:hidden;box-shadow:0 4px 25px rgba(0,0,0,0.15);'>

<div style='background:linear-gradient(135deg,#2563eb,#7c3aed);padding:30px;text-align:center;color:white;'>

<h1 style='margin:0;'>HireIQ</h1>

<p style='margin-top:10px;'>
Smart Recruitment Platform
</p>

</div>

<div style='padding:35px;'>

<h2 style='color:#0f172a;'>
Hello {$user['name']} 👋
</h2>

<p style='color:#475569;line-height:1.8;'>

Your application for

<b>{$user['title']}</b>

has been updated.

</p>

<div style='background:#f8fafc;padding:20px;border-left:6px solid {$color};border-radius:10px;margin:25px 0;'>

<h3 style='margin:0;color:{$color};'>
{$status}
</h3>

<p style='margin-top:10px;color:#475569;'>
{$message}
</p>

</div>

<p style='color:#64748b;'>

Login to HireIQ to view more details about your application.

</p>

</div>

<div style='background:#0f172a;color:#cbd5e1;text-align:center;padding:20px;'>

© 2026 HireIQ. All Rights Reserved.

</div>

</div>
";    

    /* EMAIL SEND */
    $mailResult = sendMail(
      $user['email'],
      $user['name'],
      $subject,
      $body
    );

  }

  echo json_encode([
    "status" => "success",
    "message" => "Status Updated + Email Sent"
  ]);

} else {

  echo json_encode([
    "status" => "error",
    "message" => "Failed to update status"
  ]);
}

?>