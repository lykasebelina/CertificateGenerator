<?php
require_once 'db.php';
header('Content-Type: application/json');

$db = new Database();
$conn = $db->connect();

$input = json_decode(file_get_contents('php://input'), true);
$client_id = $input['client_id'] ?? '';
$client_secret = $input['client_secret'] ?? '';


$stmt = $conn->prepare("SELECT * FROM oauth_clients WHERE client_id = ? AND client_secret = ?");
$stmt->bind_param("ss", $client_id, $client_secret);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid client credentials"]);
    exit;
}


$token = bin2hex(random_bytes(32));
$expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

$stmt = $conn->prepare("INSERT INTO oauth_access_tokens (client_id, token, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $client_id, $token, $expires_at);
$stmt->execute();

echo json_encode([
    "access_token" => $token,
    "token_type" => "Bearer",
    "expires_in" => 3600
]);
