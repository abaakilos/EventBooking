<?php
session_start(); // Start the session

$isLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Event Booking Home</title>
</head>
<body>
    <!-- Header Section -->
    <header>
        <!-- <div class="header-content">
            <div class="login-link">
                <a href="./auth/login.php">Login</a>
            </div>
            <div class="login-link">
                <a href="./auth/signup.php">Signup</a>
            </div>
        </div> -->
        <div class="header-content">
            <?php if ($isLoggedIn): ?>

                    <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</p>
                    <a href="./auth/logout.php" class="login-link">Logout</a>

            <?php else: ?>
                <div class="login-link">
                    <a href="./auth/login.php">Login</a>
                </div>
                <div class="login-link">
                    <a href="./auth/signup.php">Signup</a>
                </div>
            <?php endif; ?>
        </div>
        <img src="./images/headerLogo.png" />
        <div class="search-bar">
            <input type="text" placeholder="Location">
            <input type="date" placeholder="Date">
            <button>Search</button>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <section class="event-section">
            <h2>Popular</h2>
            <div class="event-list" id="event-list">
                <!-- Events will be loaded here dynamically -->
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>Event Booking System &copy; 2024. All Rights Reserved.</p>
    </footer>

    <script>
        // JavaScript to load events dynamically
        document.addEventListener('DOMContentLoaded', function() {
            fetch('./events/getEvents.php')
                .then(response => response.json())
                .then(events => {
                    let eventList = document.getElementById('event-list');
                    events.forEach(event => {
                        let eventCard = `
                            <div class="event-card">
                                <h3>${event.title}</h3>
                                <p>Organized by: ${event.organizer}</p>
                                <p>${event.date} - ${event.time}</p>
                                <button>Buy Ticket - $${event.price}</button>
                            </div>
                        `;
                        eventList.innerHTML += eventCard;
                    });
                })
                .catch(error => console.error('Error loading events:', error));
        });
    </script>
</body>
</html>