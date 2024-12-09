<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

include_once '../config/init.php';

$conn = new PDO("mysql:host=$server; dbname=$db", $user, $password);

$query = "SELECT event_id, title, 'World Organizers' AS organizer, '11 June 2024' AS date, '7:00 PM' AS time, '18' AS price FROM Event";
$stmt = $conn->prepare($query);
$stmt->execute();

$events = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $event = array(
        "event_id"=> $event_id,
        "title" => $title,
        "organizer" => $organizer,
        "date" => $date,
        "time" => $time,
        "price" => $price
    );
    array_push($events, $event);
}

echo json_encode($events);
?>
