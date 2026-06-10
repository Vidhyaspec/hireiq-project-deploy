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

/* SAFE INPUT HANDLING */
$user_id = $_POST['user_id'] ?? "";
$bio = $_POST['bio'] ?? "";
$skills = $_POST['skills'] ?? "";
$education = $_POST['education'] ?? "";
$experience = $_POST['experience'] ?? "";
$github = $_POST['github'] ?? "";
$linkedin = $_POST['linkedin'] ?? "";

/* VALIDATION */
if (!$user_id) {
  echo json_encode([
    "status" => "error",
    "message" => "User ID missing"
  ]);
  exit;
}

/* IMAGE */
$profile_pic = "";

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['name'] != "") {

  $profile_pic = time() . "_" . $_FILES['profile_pic']['name'];

  move_uploaded_file(
    $_FILES['profile_pic']['tmp_name'],
    "../uploads/" . $profile_pic
  );
}

/* BUILD QUERY */
$sql = "UPDATE users SET 
bio='$bio',
skills='$skills',
education='$education',
experience='$experience',
github='$github',
linkedin='$linkedin'";

/* UPDATE IMAGE ONLY IF UPLOADED */
if ($profile_pic != "") {
  $sql .= ", profile_pic='$profile_pic'";
}

$sql .= " WHERE id='$user_id'";

/* EXECUTE */
if ($conn->query($sql)) {

  if (
    $bio == "" &&
    $skills == "" &&
    $education == "" &&
    $experience == "" &&
    $github == "" &&
    $linkedin == ""
  ) {
    echo json_encode([
      "status" => "success",
      "message" => "Profile saved (empty data)"
    ]);
  } else {
    echo json_encode([
      "status" => "success",
      "message" => "Profile Updated Successfully"
    ]);
  }

} else {

  echo json_encode([
    "status" => "error",
    "message" => "Update failed",
    "error" => $conn->error
  ]);
}

?>