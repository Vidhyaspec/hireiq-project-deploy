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

$id = $_GET['id'];

$sql = "DELETE FROM jobs WHERE id='$id'";

if ($conn->query($sql)) {

    echo json_encode([
        "message" => "Job Deleted Successfully"
    ]);

} else {

    echo json_encode([
        "message" => "Delete Failed"
    ]);
}
?>