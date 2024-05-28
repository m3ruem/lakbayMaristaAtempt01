<?php
session_start(); 


if (!isset($_SESSION['user_id'])) {
    header("Location: ../access_denied.php");
    exit;
}


$user_access_level = $_SESSION['access_level'] ?? 0;


if ($user_access_level <= 10) {
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
    $delete_sql = "DELETE FROM users WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_sql)) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}


$sql = "SELECT id, firstname, lastname, email, mobile, password, access_level FROM users";
$result = mysqli_query($conn, $sql);

if ($result->num_rows > 0) {
    echo '<table>';
    echo '<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Mobile</th><th>Password</th><th>Access Level</th><th>Action</th></tr>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['firstname'] . '</td>';
        echo '<td>' . $row['lastname'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td>' . $row['mobile'] . '</td>';
        echo '<td>' . $row['password'] . '</td>';
        echo '<td>' . $row['access_level'] . '</td>';
        echo '<td><a href="users.php?delete_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a></td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No users found';
}


mysqli_close($conn);
?>
<?php
include('includes/script.php');

?>