<?php
session_start(); // Start the session

$host = "localhost";
$user = "root";
$pass = "";
$db = "healthcare";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $username, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        // Store user information in session
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $id;

        // Redirect to homepage after successful login
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid credentials!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <div class="form-container">
        <!-- Logo -->
        <img src="assets/logo.gif" alt="HealthCare Logo" class="logo">
        
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Register here</a>.</p> <!-- Link to signup page -->
    </div>
</body>
</html>
