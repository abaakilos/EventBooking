<?php
session_start();
include_once '../config/init.php';

// Fetch Event ID from the URL and event details
if (isset($_GET['id'])) {

    $conn = new PDO("mysql:host=$server; dbname=$db", $user, $password);

    $eventId = $_GET['id'];

    $stmt = $conn->prepare("SELECT e.*, u.username AS organizer_name FROM Event e
                            JOIN User u ON e.organizer_id = u.user_id
                            WHERE e.event_id = :event_id");
    $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found.");
    }
} else {
    die("No event ID specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/eventDetails.css">
    <title>Event Details - <?= htmlspecialchars($event['title']) ?></title>
</head>
<body>
    <!-- Include Header -->
    <?php include '../header/header.php'; ?>

    <!-- Event Details Section -->
    <main>
        <section class="event-details">
            <h1><?= htmlspecialchars($event['title']) ?></h1>
            <p>Organized by: <?= htmlspecialchars($event['organizer_name']) ?></p>
            <p>Date: <?= htmlspecialchars($event['start_time']) ?></p>
            <p>Description: <?= nl2br(htmlspecialchars($event['description'])) ?></p>

            <!-- Booking Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="../events/processBooking.php" method="POST">
                    <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                    <button type="submit">Book Now</button>
                </form>
            <?php else: ?>
                <p>You need to <a href="login.php">login</a> to book this event.</p>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer Section -->
    <?php include '../footer/footer.php'; ?>
</body>
</html>
