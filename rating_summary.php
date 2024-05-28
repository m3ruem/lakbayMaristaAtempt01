<?php
require './db/db_connection.php';

header('Content-Type: application/json');

$place_name = $_GET['place_name'];


$stmt = $conn->prepare("SELECT AVG(rating) as average_rating, COUNT(rating) as total_ratings FROM ratings WHERE place_name = ?");
$stmt->bind_param("s", $place_name);
$stmt->execute();
$stmt->bind_result($average_rating, $total_ratings);
$stmt->fetch();
$stmt->close();

$average_rating = round($average_rating, 2);


$rating_percentages = [];
for ($i = 1; $i <= 5; $i++) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM ratings WHERE place_name = ? AND rating = ?");
    $stmt->bind_param("si", $place_name, $i);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    $percentage = ($total_ratings > 0) ? ($count / $total_ratings) * 100 : 0;
    $rating_percentages[] = round($percentage, 2);
}

echo json_encode([
    'success' => true,
    'average_rating' => $average_rating,
    'total_ratings' => $total_ratings,
    'rating_percentages' => $rating_percentages
]);

$conn->close();
?>
