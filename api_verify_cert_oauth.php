<?php
require_once 'db.php';
header('Content-Type: application/json');


$headers = apache_request_headers();
$auth_header = $headers['Authorization'] ?? '';
$token = str_replace('Bearer ', '', $auth_header);


$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("SELECT * FROM oauth_access_tokens WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$token_result = $stmt->get_result();

if ($token_result->num_rows === 0) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid or expired token"]);
    exit;
}


$cert_code = $_GET['code'] ?? '';
if (!$cert_code) {
    http_response_code(400);
    echo json_encode(["error" => "Missing certificate code"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM certificates WHERE certificate_code = ?");
$stmt->bind_param("s", $cert_code);
$stmt->execute();
$cert = $stmt->get_result()->fetch_assoc();

if (!$cert) {
    http_response_code(404);
    echo json_encode(["error" => "Certificate not found"]);
    exit;
}

echo json_encode([
    "status" => "valid",
    "certificate" => $cert
]);
