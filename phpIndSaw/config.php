<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feedback_site";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Не удалось подключиться к базе данных: " . $conn->connect_error);
}
?>