<?php
session_start();
include_once '../config/init.php';

$conn = new PDO("mysql:host=$server; dbname=$db", $user, $password);


if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to make a booking.");
}

// Get the event ID from the form
if (isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];

    // Check if the event exists
    $stmt = $conn->prepare("SELECT * FROM Event WHERE event_id = :event_id");
    $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        die("Event not found.");
    }

    // Check if the user has already booked the event
    $stmt = $conn->prepare("SELECT * FROM Booking WHERE user_id = :user_id AND event_id = :event_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
    $stmt->execute();
    $existingBooking = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingBooking) {
        die("You have already booked this event.");
    }

    // Process booking
    try {
        $conn->beginTransaction();  // Start transaction

        // Insert the booking
        $stmt = $conn->prepare("INSERT INTO Booking (user_id, event_id, booked_at) VALUES (:user_id, :event_id, NOW())");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();

        // Commit transaction (trigger will update event capacity)
        $conn->commit();

        echo "Booking successful!";
    } catch (PDOException $e) {
        $conn->rollBack();  // Rollback in case of error
        if ($e->getCode() == '45000') {
            // Event is fully booked (trigger error)
            echo "Error: Event is fully booked.";
        } else {
            echo "Error: " . $e->getMessage();
        }
    }
} else {
    die("No event ID specified.");
}
?>
