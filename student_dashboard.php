<!-- display_certificates.php -->

<?php
session_start();
require_once 'db_connect.php';

// Redirect to login if not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role_id'])) {
    header("Location: login-page.php");
    exit();
}

$username = $_SESSION['username'];
$role_id = $_SESSION['role_id'];

// Handle search query if present
$search = '';
$sql = "SELECT * FROM certificates";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $sql .= " WHERE cert_id LIKE :search 
           OR certificate_title LIKE :search 
           OR recipient_name LIKE :search 
           OR instructor_name LIKE :search 
           OR completion_date LIKE :search";
}

$sql .= " ORDER BY completion_date DESC";

try {
    $stmt = $pdo->prepare($sql);
    if (!empty($search)) {
        $stmt->execute(['search' => '%' . $search . '%']);
    } else {
        $stmt->execute();
    }
    $certificates = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Failed to fetch certificates: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generated Certificates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: linear-gradient(to right, #00aaff, #0047ab);
            color: #fff;
        }
        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 20px;
            width: 1200px;
            border: none;
            border-radius: 20px;
        }
        button {
            padding: 8px 16px;
            border: none;
            background-color: #007acc;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #005c99;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            overflow: hidden;
            color: #222;
            box-shadow: 0 8px 24px rgba(0, 71, 171, 0.15);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007acc;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        a {
            text-decoration: none;
            color: #007acc;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
            color: #004d99;
        }
        tr:hover {
            background-color: #f0f8ff;
        }

        @media (max-width: 640px) {
            body {
                padding: 10px;
            }
            table, th, td {
                font-size: 14px;
            }
            input[type="text"] {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<h1>FIND YOUR CERTIFICATES</h1>

<form method="GET" action="display_certificates.php">
    <input type="text" name="search" placeholder="Search by title, recipient, instructor..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<table>
    <thead>
        <tr>
            <th>Certificate ID</th>
            <th>Title</th>
            <th>Recipient</th>
            <th>Instructor</th>
            <th>Date</th>
            <th>Download</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($certificates) > 0): ?>
            <?php foreach ($certificates as $cert): 
                $pdf_path = "certificates/{$cert['cert_id']}.pdf";
            ?>
            <tr>
                <td><?= htmlspecialchars($cert['cert_id']) ?></td>
                <td><?= htmlspecialchars($cert['certificate_title']) ?></td>
                <td><?= htmlspecialchars($cert['recipient_name']) ?></td>
                <td><?= htmlspecialchars($cert['instructor_name']) ?></td>
                <td><?= htmlspecialchars($cert['completion_date']) ?></td>
                <td>
                    <?php if (file_exists($pdf_path)) : ?>
                        <a href="view_certificate.php?cert_id=<?= urlencode($cert['cert_id']) ?>" target="_blank">View / Download Certificate</a>
                    <?php else : ?>
                        <em>PDF not found</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">No certificates found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
