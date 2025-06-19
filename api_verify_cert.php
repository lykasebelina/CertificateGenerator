<?php
require_once 'db.php';

header('Content-Type: application/json');

// Step 1: Check API Token
$headers = apache_request_headers();
$token = isset($headers['Authorization']) ? trim(str_replace('Bearer', '', $headers['Authorization'])) : null;

if (!$token) {
    http_response_code(401);
    echo json_encode(["error" => "No API token provided"]);
    exit;
}

$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("SELECT * FROM api_tokens WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(["error" => "Invalid or expired API token"]);
    exit;
}

// Step 2: Get certificate code from URL query
$cert_code = isset($_GET['code']) ? $_GET['code'] : null;

if (!$cert_code) {
    http_response_code(400);
    echo json_encode(["error" => "Missing certificate code"]);
    exit;
}

// Step 3: Lookup Certificate
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
