<!-- generate_certificate_pdf.php -->

<?php
require_once __DIR__ . '/vendor/autoload.php';  // Composer autoload



function generateCertificatePDF($cert_id, $certificate_title, $student_name, $course_name, $completion_date, $instructor_name, $reason_purpose) {
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("Certificate {$cert_id}");
    $pdf->AddPage();

    // Certificate content
    $html = "
    <h1 style='text-align: center;'>Certificate of {$certificate_title}</h1>
    <h3 style='text-align: center;'>Awarded to:</h3>
    <h2 style='text-align: center;'>{$student_name}</h2>
    <p style='text-align: center;'>For {$reason_purpose} in <strong>{$course_name}</strong></p>
    <p style='text-align: center;'>Date of Completion: {$completion_date}</p>
    <p style='text-align: center;'>Instructor: {$instructor_name}</p>
    <p style='text-align: center;'>Certificate ID: {$cert_id}</p>
    ";

    $pdf->writeHTML($html, true, false, true, false, '');

    // Save PDF to 'certificates/' folder
    $output_dir = __DIR__ . '/certificates';
    if (!file_exists($output_dir)) {
        mkdir($output_dir, 0777, true);
    }

    $file_path = $output_dir . "/{$cert_id}.pdf";
    $pdf->Output($file_path, 'F');  // 'F' = save to file

    return $file_path;
}
