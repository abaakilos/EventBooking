<?php
    $isLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>

    <div class="header-content">

        <?php if ($isLoggedIn): ?>

            <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
            <a href="../auth/logout.php" class="login-link">Logout</a>

            <?php else: ?>
            <div class="login-link">
            <a href="../auth/login.php">Login</a>
            </div>
            <div class="login-link">
            <a href="../auth/signup.php">Signup</a>
            </div>

        <?php endif; ?>
    </div>
</body>