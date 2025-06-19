
<!-- certificate-generator.php -->

<?php
require_once 'db_connect.php';
require_once 'generate_certificate_pdf.php';



// Certificate Generator: Single page PHP app
// Processes POST data and displays either input form or generated certificate

// Helper function to sanitize input fields
$student_names_from_db = [];
try {
    $stmt = $pdo->query("SELECT name FROM student_users"); 
    $student_names_from_db = $stmt->fetchAll(PDO::FETCH_COLUMN);
   
} catch (PDOException $e) {
    die("Failed to fetch names: " . $e->getMessage());
}

$instructor_names_from_db = [];
try {
 
    
    $stmt = $pdo->query("SELECT name FROM prof_users");// Replace 'students' with your actual table name
    $instructor_names_from_db = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Failed to fetch names: " . $e->getMessage());
}

// Certificate Generator: Single page PHP app
// Processes POST data and displays either input form or generated certificate

// Helper function to sanitize input fields
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// If form submitted, get data or set defaults
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
$student_name = $submitted ? sanitize($_POST['student_name'] ?? '') : '';
$course_name = $submitted ? sanitize($_POST['course_name'] ?? '') : '';
$completion_date = $submitted ? sanitize($_POST['completion_date'] ?? date('Y-m-d')) : date('Y-m-d');
$instructor_name = $submitted ? sanitize($_POST['instructor_name'] ?? '') : '';
$certificate_id = $submitted ? sanitize($_POST['certificate_id'] ?? uniqid('CERT-')) : uniqid('CERT-');
$certificate_title = $submitted ? sanitize($_POST['certificate_title'] ?? '') : '';
$reason_purpose = $submitted ? sanitize($_POST['reason_purpose'] ?? '') : '';



if ($submitted) {
  
  $data_to_sign = $student_name . '|' . $course_name . '|' . $completion_date . '|' . $certificate_id;

  // Load private key
  $private_key_content = file_get_contents(__DIR__ . '/private.key');
  if (!$private_key_content) {
      die("Private key file not found.");
  }

  $private_key = openssl_pkey_get_private($private_key_content);
  if (!$private_key) {
      die("Failed to load private key.");
  }

  // Create signature
  $signature = '';
  openssl_sign($data_to_sign, $signature, $private_key, OPENSSL_ALGO_SHA256);

  // Encode signature in base64 so it can be displayed or stored
  $signature_base64 = base64_encode($signature);

  // Insert certificate details into certificates table
  try {
    $stmt = $pdo->prepare("INSERT INTO certificates (cert_id, certificate_title, completion_date, recipient_name, instructor_name, digital_signature_key) 
                           VALUES (:cert_id, :certificate_title, :completion_date, :recipient_name, :instructor_name, :digital_signature)");
    
    $stmt->execute([
        ':cert_id' => $certificate_id,
        ':certificate_title' => $certificate_title,
        ':completion_date' => $completion_date,
        ':recipient_name' => $student_name,
        ':instructor_name' => $instructor_name,
        ':digital_signature' => $signature_base64
        
    ]);

    $pdf_path = generateCertificatePDF(
      $certificate_id,
      $certificate_title,
      $student_name,
      $course_name,
      $completion_date,
      $instructor_name,
      $reason_purpose
  );
  
  if (file_exists($pdf_path)) {
      // Optional: echo success message for testing
      // echo "PDF successfully created at: $pdf_path";
  } else {
      die("Failed to generate PDF.");
  }
  


} catch (PDOException $e) {
    die("Failed to save certificate record: " . $e->getMessage());
}


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Modern Certificate Generator</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<!-- html2pdf library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

<style>
  /* Reset and base */
  *, *::before, *::after {
    box-sizing: border-box;
  }
  body {
    margin: 0; padding: 0;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #4a90e2 0%, #50e3c2 100%);
    color: #222;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  header {
    background: linear-gradient(90deg, #0066ff, #00d2ff);
    color: white;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    position: sticky;
    top: 0;
    z-index: 10;
  }
  .material-icons {
    font-size: 32px;
  }
  header h1 {
    font-weight: 700;
    font-size: 1.8rem;
    flex-grow: 1;
    user-select: none;
  }
  main {
    flex-grow: 1;
    padding: 40px 16px 64px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
  }

  .container {
    max-width: 900px;
    width: 100%;
    background: white;
    border-radius: 24px;
    box-shadow: 0 12px 26px rgba(0,0,0,0.25);
    padding: 32px 48px;
    display: flex;
    flex-direction: column;
    gap: 32px;
  }

  /* FORM STYLES */
  form {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }
  label {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: #444;
  }
  input[type="text"],
  input[type="date"] {
    font-size: 1rem;
    padding: 14px 16px;
    border: 2px solid #ddd;
    border-radius: 12px;
    transition: border-color 0.3s ease;
    width: 100%;
  }
  input[type="text"]:focus,
  input[type="date"]:focus {
    outline: none;
    border-color: #4a90e2;
    box-shadow: 0 0 8px #4a90e2aa;
  }

  button {
    align-self: flex-start;
    background: linear-gradient(135deg, #4a90e2, #50e3c2);
    border: none;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    padding: 14px 40px;
    border-radius: 16px;
    cursor: pointer;
    transition: box-shadow 0.3s ease, transform 0.2s ease;
    user-select: none;
    box-shadow: 0 4px 12px rgba(74,144,226,0.5);
  }
  button:hover {
    box-shadow: 0 8px 24px rgba(74,144,226,0.75);
    transform: translateY(-3px);
  }
  button:active {
    transform: translateY(0);
    box-shadow: 0 3px 10px rgba(74,144,226,0.6);
  }

  /* CERTIFICATE STYLES */
  .certificate {
    border: 12px solid #4a90e2;
    border-radius: 32px;
    padding: 48px 64px;
    text-align: center;
    color: #004a99;
    background: #f0f8ff;
    position: relative;
    box-shadow: 0 12px 30px rgba(0, 74, 153, 0.25);
  }
  .cert-title {
    font-size: 2.8rem;
    font-weight: 900;
    margin-bottom: 16px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #002c66;
    user-select: none;
  }
  .cert-subtitle {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 48px;
    color: #007acc;
    user-select: none;
  }
  .cert-name {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 24px;
    border-bottom: 3px solid #4a90e2;
    display: inline-block;
    padding-bottom: 8px;
    user-select: text;
  }
  .cert-text {
    font-size: 1.15rem;
    max-width: 600px;
    margin: 0 auto 48px;
    line-height: 1.5;
    user-select: none;
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
  .cert-detail small {
    display: block;
    font-weight: 400;
    font-size: 0.85rem;
    color: #0055aa;
  }
  .signature {
    font-family: 'Cursive', 'Brush Script MT', cursive;
    font-size: 1.4rem;
    color: #004a99;
    user-select: text;
  }
  .signature-label {
    font-size: 0.9rem;
    color: #0055aa;
    margin-top: 6px;
    user-select: none;
  }

  /* Responsive container padding */
  @media (max-width: 768px) {
    .container {
      padding: 24px 32px;
    }
    main {
      padding: 24px 12px 48px;
    }
    .cert-title {
      font-size: 2rem;
    }
    .cert-name {
      font-size: 2rem;
    }
  }

  /* Hide print and generate buttons on print */
  @media print {
    button {
      display: none !important;
    }
    header {
      display: none !important; 
    }
    main {
      padding: 0 !important;
      margin: 0 !important;
      min-height: auto !important;
      justify-content: flex-start !important;
      background: white !important;
    }
    .container {
      box-shadow: none !important;
      max-width: 100% !important;
      border: none !important;
      border-radius: 0 !important;
      padding: 0 !important;
    }
  }

  /* Additional styles omitted for brevity */
  .qr-code {
            margin-top: 16px;
            display: flex;
            justify-content: center;
        }
  canvas {
            border: 1px solid #4a90e2; /* Optional: Add a border to the QR code */
        }
      
</style>

</head>
<body>
<header>
  <span class="material-icons" aria-hidden="true">school</span>
  <h1>Certificate Generator</h1>
</header>
<main>
<div class="container" role="main">

<?php if (!$submitted) : ?>
  <form method="post" action="" aria-label="Certificate information form" novalidate>

  <label for="certificate_title">Certificate Title</label>
<input type="text" id="certificate_title" name="certificate_title" placeholder="e.g. Participation, Recognition, Appreciation" required aria-required="true" value="<?= $certificate_title ?>" />

<label for="student_name">Student Full Name</label>
<input list="student-name-options" id="student_name" name="student_name" placeholder="Start typing..." required aria-required="true" value="<?= $student_name ?>" />
<datalist id="student-name-options">
  <?php foreach ($student_names_from_db as $name) : ?>
    <option value="<?= htmlspecialchars($name) ?>"></option>
  <?php endforeach; ?>
</datalist>

<label for="instructor_name">Instructor Full Name</label>
<input list="instructor-name-options" id="instructor_name" name="instructor_name" placeholder="Start typing..." required aria-required="true" value="<?= $instructor_name ?>" />
<datalist id="instructor-name-options">
  <?php foreach ($instructor_names_from_db as $name) : ?>
    <option value="<?= htmlspecialchars($name) ?>"></option>
  <?php endforeach; ?>
</datalist>


<label for="reason_purpose">Reason / Purpose</label>
<input type="text" id="reason_purpose" name="reason_purpose" placeholder="e.g. For successfully completing the advanced PHP course" required aria-required="true" value="<?= $reason_purpose ?>" />


    <label for="course_name">Course Title</label>
    <input type="text" id="course_name" name="course_name" placeholder="e.g. Introduction to PHP" required aria-required="true" />

    <label for="completion_date">Completion Date</label>
    <input type="date" id="completion_date" name="completion_date" value="<?= $completion_date ?>" required aria-required="true" />

    

    <button type="submit" aria-label="Generate Certificate">Generate Certificate</button>
  </form>
  <?php else: ?>
    
<section class="certificate" id="certificate">
  <h2 class="cert-title">Certificate of <?= strtoupper($certificate_title) ?></h2>
  <div style="margin-top: 20px; font-size: 1.2rem;">Awarded to</div>
  <div class="cert-name"><?= htmlspecialchars($student_name) ?></div>
  <div class="cert-text">
      <?= htmlspecialchars($reason_purpose) ?><br />
      <strong><?= htmlspecialchars($course_name) ?></strong>
  </div>
  <div class="cert-details">
      <div class="cert-detail">
          <div><?= date('F j, Y', strtotime($completion_date)) ?></div>
          <small>Completion Date</small>
      </div>
      <div class="cert-detail">
          <div class="signature"><?= htmlspecialchars($instructor_name) ?></div>
          <small>Instructor</small>
      </div>
  </div>
  <div class="cert-id">Certificate ID: <?= htmlspecialchars($certificate_id) ?></div>

  <!-- QR Code -->
  <div class="qr-code">
      <canvas id="qr-code"></canvas>
  </div>
</section>

<!-- Buttons -->
<div class="button-group">
  <button onclick="downloadCertificate();" type="button">Download Certificate</button>
  <button onclick="window.history.back();" type="button" style="background: #ccc; color: #0066cc;">Generate Another</button>
</div>
<?php endif; ?>


</div>
</main>
<script>

function downloadCertificate() {
  const password = prompt("Enter your login password to download the certificate:");

  if (password === null) return; // Cancelled

  fetch('verify_password.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'password=' + encodeURIComponent(password)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Password is correct — download the PDF
      const element = document.querySelector('.certificate');

      const opt = {
        margin:       0.5,
        filename:     'certificate.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
      };

      html2pdf().set(opt).from(element).save();
    } else {
      alert("Incorrect password. Download cancelled.");
    }
  })
  .catch(error => {
    alert("An error occurred during verification.");
    console.error("Error:", error);
  });
}


  // Generate QR code
  window.addEventListener('DOMContentLoaded', () => {
    const qr = new QRious({
      element: document.getElementById('qr-code'),
      size: 150,
      value: "<?= htmlspecialchars($certificate_id) ?>"  // You can change this to any certificate URL or text
    });
  });
</script>



</body>
</html>
