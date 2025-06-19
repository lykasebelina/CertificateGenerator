


<?php
session_start();
include 'logcon.php'; // database connection file

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role_id = $_POST["role"];

    

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=? AND role_id=?");
    $stmt->bind_param("ssi", $username, $password, $role_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION["username"] = $user["username"];
        $_SESSION["role_id"] = $user["role_id"];
        $_SESSION['user_id'] = $user['id'];


        // Redirect based on role
        if ($user["role_id"] == 1) {
            header("Location: admin_dashboard.php");
        } elseif ($user["role_id"] == 2) {
            header("Location: professor_dashboard.php");
        } elseif ($user["role_id"] == 3) {
            header("Location: student_dashboard.php");
        }
        exit();
    } else {
        $message = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Certificate Generator - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        /* Reset & base */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            color: #f0f4ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        /* Container */
        .container {
            background: rgba(17, 24, 39, 0.9);
            box-shadow: 0 8px 24px rgba(9, 33, 71, 0.3);
            border-radius: 16px;
            max-width: 420px;
            width: 100%;
            padding: 40px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        /* Header */
        .header {
            text-align: center;
        }
        .title {
            font-weight: 700;
            font-size: 2.4rem;
            line-height: 1.2;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #a78bfa, #22d3ee);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        /* Forms */
        form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        label {
            font-weight: 500;
            font-size: 0.9rem;
            color: #cbd5e1;
        }
        input[type="text"],
        input[type="password"],
        select {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #4b5563;
            background: #1e293b;
            color: #e0e7ff;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #a78bfa;
            outline: none;
            box-shadow: 0 0 8px #a78bfa;
        }
        /* Submit button */
        button.submit-btn {
            background: linear-gradient(135deg, #8b5cf6, #06b6d4);
            border: none;
            padding: 16px;
            border-radius: 16px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            transition: background 0.4s ease, transform 0.2s ease;
        }
        button.submit-btn:hover {
            background: linear-gradient(135deg, #06b6d4, #8b5cf6);
            transform: scale(1.05);
            outline: none;
            box-shadow: 0 0 15px #06b6d4;
        }
        /* Message area */
        .message {
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
            padding: 12px;
            border-radius: 12px;
            color: red;
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <h1 class="title">Certificate Generator</h1>
            <p>Login to generate certificates securely.</p>
        </header>

        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required />
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required />
            
            <label for="role">Select Role</label>
            <select id="role" name="role" required>
                <option value="">-- Select Role --</option>
                <option value="1">Admin</option>
                <option value="2">Professor</option>
                <option value="3">Student</option>
            </select>
            
            <button type="submit" class="submit-btn">Login</button>
            <p class="message"><?php echo $message; ?></p>
        </form>
    </main>
</body>
</html>
