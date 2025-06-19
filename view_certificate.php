<!-- view_certificates.php -->

<?php
require_once 'db_connect.php';

$cert_id = $_GET['cert_id'] ?? '';

if (!$cert_id) {
    die("No certificate ID provided.");
}

$stmt = $pdo->prepare("SELECT * FROM certificates WHERE cert_id = ?");
$stmt->execute([$cert_id]);
$cert = $stmt->fetch();

if (!$cert) {
    die("Certificate not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Certificate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f8ff;
            padding: 20px;
        }
        .certificate {
            border: 12px solid #4a90e2;
            border-radius: 32px;
            padding: 48px 64px;
            text-align: center;
            color: #004a99;
            background: #f0f8ff;
            position: relative;
            box-shadow: 0 12px 30px rgba(0, 74, 153, 0.25);
            margin: auto;
            max-width: 900px;
        }
        .cert-title {
            font-size: 2.8rem;
            font-weight: 900;
            margin-bottom: 16px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #002c66;
        }
        .cert-name {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 24px;
            border-bottom: 3px solid #4a90e2;
            display: inline-block;
            padding-bottom: 8px;
        }
        .cert-text {
            font-size: 1.15rem;
            max-width: 600px;
            margin: 0 auto 48px;
            line-height: 1.5;
        }
        .cert-details {
            display: flex;
            justify-content: space-around;
            margin-top: 32px;
        }
        .cert-detail {
            text-align: center;
            font-weight: 600;
        }
        .signature {
            font-family: 'Cursive', 'Brush Script MT', cursive;
            font-size: 1.4rem;
        }
        .cert-id {
            margin-top: 30px;
            font-size: 0.9rem;
        }
        .qr-code {
            margin-top: 20px;
        }
        .qr-code canvas {
            border: 1px solid #4a90e2;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        .button-group button {
            background: #4a90e2;
            color: white;
            font-weight: 600;
            border: none;
            padding: 12px 24px;
            margin: 0 8px;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="certificate" id="certificate">
    <h2 class="cert-title">Certificate of <?= strtoupper(htmlspecialchars($cert['certificate_title'])) ?></h2>
    <div style="margin-top: 20px; font-size: 1.2rem;">Awarded to</div>
    <div class="cert-name"><?= htmlspecialchars($cert['recipient_name']) ?></div>
    <div class="cert-text">
        For successfully completing the course.
    </div>
    <div class="cert-details">
        <div class="cert-detail">
            <div><?= date('F j, Y', strtotime($cert['completion_date'])) ?></div>
            <small>Completion Date</small>
        </div>
        <div class="cert-detail">
            <div class="signature"><?= htmlspecialchars($cert['instructor_name']) ?></div>
            <small>Instructor</small>
        </div>
    </div>
    <div class="cert-id">Certificate ID: <?= htmlspecialchars($cert['cert_id']) ?></div>

    <!-- QR Code -->
    <div class="qr-code">
        <canvas id="qr-code"></canvas>
    </div>
</div>

<!-- Buttons -->
<div class="button-group">
    <button onclick="downloadCertificate();">Download Certificate</button>
</div>

<!-- Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    // Generate QR code from digital signature value
    var qr = new QRious({
        element: document.getElementById('qr-code'),
        value: '<?= $cert['digital_signature_key'] ?>',
        size: 150
    });

    // Download with password prompt
    function downloadCertificate() {
        var password = prompt("Enter the secure password to download this certificate:");
        var correctPassword = "cert1234";

        if (password === null) return;
        if (password !== correctPassword) {
            alert("Incorrect password. Download cancelled.");
            return;
        }

        var element = document.getElementById('certificate');
        var opt = {
            margin: 0.5,
            filename: 'certificate-<?= $cert_id ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>

</body>
</html>
