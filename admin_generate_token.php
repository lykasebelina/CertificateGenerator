<?php
require_once 'db.php';

$token = bin2hex(random_bytes(16));
$expires = date('Y-m-d H:i:s', strtotime('+30 days'));

$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("INSERT INTO api_tokens (token, expires_at) VALUES (?, ?)");
$stmt->bind_param("ss", $token, $expires);
$stmt->execute();

echo "Token generated: $token";
