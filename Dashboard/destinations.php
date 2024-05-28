<?php
session_start(); 


if (!isset($_SESSION['user_id'])) {
    header("Location: ../access_denied.php");
    exit;
}


$user_access_level = $_SESSION['access_level'] ?? 0;


if ($user_access_level <= 1) {
    header("Location: ../access_denied.php");
    exit;
}

include('includes/header.php');
include('includes/navbar.php');
require_once '../db/db_connection.php';
?>
<link rel="stylesheet" href="includes/style.css">
<body>

<?php

require_once '../db/db_connection.php';

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM destinations WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_sql)) {
        echo "Destination deleted successfully.";
    } else {
        echo "Error deleting Destination: " . mysqli_error($conn);
    }
}


$sql = "SELECT id, name, location, image, rating FROM destinations";
$result = mysqli_query($conn, $sql);

if ($result->num_rows > 0) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Name</th><th>Location</th><th>Image</th><th>Rating</th><th></th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['location'] . '</td>';
        echo '<td>' . $row['image'] . '</td>';
        echo '<td>' . $row['rating'] . '</td>';
        echo '<td><a href="destinations.php?delete_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this destinations?\')">Delete</a></td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No Destination found';
}


mysqli_close($conn);
?>
<?php
include('includes/script.php');

?>