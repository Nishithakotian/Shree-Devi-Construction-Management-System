<?php
$conn = new mysqli("localhost", "root", "rootbca123", "cms_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>