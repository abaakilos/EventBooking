<?php
// config.php: Database Configuration

include_once '../config/init.php';



try {
    $conn = new PDO("mysql:host=$server; dbname=$db", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle Form Submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $user_type = 'attendee';

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO User (email, password, username, phone_number, created_at, user_type) VALUES (:email, :password, :username, :phone_number, NOW(), :user_type)";
    $stmt = $conn->prepare($query);

    try {
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_type', $user_type);
        $stmt->execute();
        $message = "Signup successful! You can now login.";
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') { // Duplicate entry error
            $message = "Email is already registered.";
        } else {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="form-container">
        <?php if ($message): ?> 
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <h1>Signup</h1>
        <form method="POST">
            <input type="username" name="username" placeholder="Username" required> 
            <input type="email" name="email" placeholder="Email" required>
            <input type="number" name="phone_number" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Signup</button>
        </form>
    </div>
</body>
</html>