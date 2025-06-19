<?php
// Example data you want to verify
$data_to_verify = 'efw|ewf|2025-06-18|CERT-685276057fb58';

// The base64-encoded signature to verify
$signature_base64 = 'qEgY4o/Qa4rDio8/u2eoIwzykx5Wix3e5FxgEtgmJiQFefjkN4Gt7xuvkNT3Gii0JRvvvDgzu9Wi00hikCg1B+JLkyK4wklOIm2ytEp1/oMFhcVFmpVuFAeZxUo489Vn5mOyTFy0XYbz8XNO+qzf9ua6cj9vaDoDXe26f1N2HD+2Js+j1GH0Gtm3tmESdPzb8q+F7kbmbo42N51aqgS1nD0A+PyxNjkOuCmvz0VdMLvxAPJlHsJR4yjFd5FR3UaOB1oKwXZkfUDkUm7UbMXJcCReY8RohPGvrlYfzLoMDO8CvgQOCXLAKpKwnnrDd3vmaP1BZo4khWPzdoxCoI6w9g==';

// Decode the base64 signature
$signature = base64_decode($signature_base64);

// Load public key
$public_key_content = file_get_contents('public.key');
if (!$public_key_content) {
    die("Public key not found.");
}

// Verify signature
$ok = openssl_verify($data_to_verify, $signature, $public_key_content, OPENSSL_ALGO_SHA256);

if ($ok === 1) {
    echo "✔️ Signature is valid!";
} elseif ($ok === 0) {
    echo "❌ Signature is invalid.";
} else {
    echo "⚠️ Error verifying signature.";
}
?>
