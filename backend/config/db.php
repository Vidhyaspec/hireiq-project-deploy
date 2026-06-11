<?php

$conn = new mysqli(
    getenv("DB_HOST"),
    getenv("DB_USER"),
    getenv("DB_PASS"),
    getenv("DB_NAME"),
    (int)getenv("DB_PORT")
);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}