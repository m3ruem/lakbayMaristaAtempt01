<?php
session_start();
require './db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $place_name = $_POST['place_name'];


    $stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname);
    $stmt->fetch();
    $stmt->close();

    $full_name = $firstname . ' ' . $lastname;


    $stmt = $conn->prepare("INSERT INTO bookings (user_full_name, place_name) VALUES (?, ?)");
    $stmt->bind_param("ss", $full_name, $place_name);

    if ($stmt->execute()) {
        echo 'Booking successful.';
    } else {
        echo 'Booking failed: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();


    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?booked=1');
    exit();
}
?>
