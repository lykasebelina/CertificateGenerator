<?php
require_once 'db.php';

$db = new Database();
$conn = $db->connect();

$client_id = 'client123'; // Make sure this exists in oauth_clients table
$token = bin2hex(random_bytes(32)); // Generates a 64-character token
$expires_at = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 hour expiry

$stmt = $conn->prepare("INSERT INTO oauth_access_tokens (client_id, token, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $client_id, $token, $expires_at);

if ($stmt->execute()) {
    echo "✅ Token inserted successfully:<br>";
    echo "🔑 Bearer Token: <strong>$token</strong><br>";
    echo "⏰ Expires at: $expires_at";
} else {
    echo "❌ Failed to insert token.";
}
