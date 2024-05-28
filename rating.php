<?php
session_start();
require './db/db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$place_name = $_POST['place_name'];
$rating = $_POST['rating'];

$stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($firstname, $lastname);
$stmt->fetch();
$stmt->close();

$full_name = $firstname . ' ' . $lastname;

$stmt = $conn->prepare("SELECT * FROM ratings WHERE user_id = ? AND place_name = ?");
$stmt->bind_param("is", $user_id, $place_name);
$stmt->execute();
$result = $stmt->get_result();
$is_rated = $result->num_rows > 0;
$stmt->close();

if ($is_rated) {
    $stmt = $conn->prepare("UPDATE ratings SET rating = ? WHERE user_id = ? AND place_name = ?");
    $stmt->bind_param("iis", $rating, $user_id, $place_name);
} else {
    $stmt = $conn->prepare("INSERT INTO ratings (user_id, user_full_name, place_name, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $full_name, $place_name, $rating);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error submitting rating: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
