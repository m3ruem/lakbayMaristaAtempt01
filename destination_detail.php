<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['destination_name'])) {
    $destinationName = $_POST['destination_name'];
    header("Location: destination_detail.php?name=" . urlencode($destinationName));
    exit();
}

$destinationName = $_GET['name'];

$destinationFilePath = __DIR__ . '/destinations/' . strtolower(str_replace(' ', '-', $destinationName)) . '.php';

if (file_exists($destinationFilePath)) {
    include $destinationFilePath;
} else {
    echo "Destination not found.";
}
?>
