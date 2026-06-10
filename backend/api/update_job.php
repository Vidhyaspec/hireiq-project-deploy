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

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;

$title = $data->title;
$description = $data->description;
$skills = $data->skills;
$company = $data->company;
$location = $data->location;

$sql = "

UPDATE jobs

SET

title='$title',
description='$description',
skills='$skills',
company='$company',
location='$location'

WHERE id='$id'

";

if ($conn->query($sql)) {

    echo json_encode([
        "message" => "Job Updated Successfully"
    ]);

} else {

    echo json_encode([
        "message" => "Update Failed"
    ]);
}
?>