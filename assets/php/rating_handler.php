<?php

require '../db/db_connection.php';

$place_name = basename($_SERVER['PHP_SELF'], ".php");


$stmt = $conn->prepare("SELECT AVG(rating) as average_rating, COUNT(*) as total_ratings FROM ratings WHERE place_name = ?");
$stmt->bind_param("s", $place_name);
$stmt->execute();
$stmt->bind_result($average_rating, $total_ratings);
$stmt->fetch();
$stmt->close();


$rating_percentages = [];
for ($i = 1; $i <= 5; $i++) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM ratings WHERE place_name = ? AND rating = ?");
    $stmt->bind_param("si", $place_name, $i);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $rating_percentages[$i] = $count / $total_ratings * 100;
    $stmt->close();
}

$conn->close();
?>
