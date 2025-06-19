<?php
require_once '../db_connect.php';

header('Content-Type: application/json');

// Validate API key
$headers = getallheaders();
$api_key = isset($headers['X-API-KEY']) ? $headers['X-API-KEY'] : '';

if (empty($api_key)) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Missing API key"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM api_keys WHERE api_key = ? AND status = 'active'");
$stmt->execute([$api_key]);

if ($stmt->rowCount() === 0) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Invalid or inactive API key"]);
    exit;
}

// Validate Certificate ID
if (!isset($_GET['cert_id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing cert_id"]);
    exit;
}

$cert_id = $_GET['cert_id'];

// Fetch certificate details
$stmt = $pdo->prepare("SELECT * FROM certificates WHERE cert_id = ?");
$stmt->execute([$cert_id]);

if ($stmt->rowCount() === 0) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Certificate not found"]);
    exit;
}

$certificate = $stmt->fetch(PDO::FETCH_ASSOC);

// Return cert details
echo json_encode([
    "status" => "success",
    "certificate" => $certificate
]);

?>
