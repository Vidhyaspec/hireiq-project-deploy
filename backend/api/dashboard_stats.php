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
$jobs =
mysqli_num_rows(

    mysqli_query(
        $conn,
        "SELECT * FROM jobs"
    )

);

$applicants =
mysqli_num_rows(

    mysqli_query(
        $conn,
        "SELECT * FROM applications"
    )

);

$shortlisted =
mysqli_num_rows(

    mysqli_query(

        $conn,

        "SELECT * FROM applications
         WHERE status='Shortlisted'"

    )

);
 $avgScore = 0;

echo json_encode([

    "jobs" => $jobs,

    "applicants" => $applicants,

    "shortlisted" => $shortlisted,

    "avgScore" => $avgScore

]);

?>
