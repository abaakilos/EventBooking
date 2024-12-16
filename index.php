<?php
    session_start(); // Start the session

    $isLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../EventBooking/css/style.css">
    <title>Event Booking Home</title>
</head>
<body>
    <!-- Header Section -->
    <header>

        <?php 
            include './header/header.php'; // Include the header
        ?>

        <img src="../EventBooking/images/headerLogo.png" />
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
    <?php 
        include '../EventBooking/footer/footer.php'; // Include the header
    ?>

    <script>
        // JavaScript to load events dynamically
        document.addEventListener('DOMContentLoaded', function() {
        fetch('/EventBooking/events/getEvents.php')
            .then(response => response.json())
            .then(events => {
                let eventList = document.getElementById('event-list');
                events.forEach(event => {
                    let eventCard = `
                        <div class="event-card">
                            <h3>${event.title}</h3>
                            <p>Organized by: ${event.organizer}</p>
                            <p>${event.date} - ${event.time}</p>
                            <a href="/EventBooking/booking/eventDetails.php?id=${event.event_id}" class="event-link">
                                <button>View Details</button>
                            </a>
                        </div>
                    `;
                    eventList.innerHTML += eventCard;
                });
                console.log(events);
            })
            .catch(error => console.error('Error loading events:', error));
        }); 
    </script>
</body>
</html>