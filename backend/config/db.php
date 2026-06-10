<?php

$conn = new mysqli(
    "mysql-23bc45de-vidhyasri0615-61cc.d.aivencloud.com",
    "avnadmin",
    "PASSWORD_FROM_RENDER_ENV",
    "defaultdb",
    26779
);

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "DB connection failed"
    ]);
    exit();
}

?>