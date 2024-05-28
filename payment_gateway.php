<?php
session_start();
require_once './db/db_connection.php'; // Corrected path

if (!isset($_SESSION['user_id'])) {
    header("Location: ./access_denied.php"); // Adjusted path
    exit;
}

$user_id = $_SESSION['user_id'];
$selected_plan = $_GET['plan'];
$price = $_GET['price'];

$payment_success = true; // This should be replaced with actual payment logic

if ($payment_success) {
    $access_level = 0;
    switch ($selected_plan) {
        case 'staff':
            $access_level = 20;
            break;
        case 'moderator':
            $access_level = 30;
            break;
        case 'administrator':
            $access_level = 40;
            break;
    }

    $stmt = $conn->prepare("UPDATE users SET subscription_plan = ?, access_level = ? WHERE id = ?");
    $stmt->bind_param("sii", $selected_plan, $access_level, $user_id);
    if ($stmt->execute()) {
        echo "Subscription plan updated successfully!";
        header("Location: subscription_success.php"); // Adjusted path
        exit;
    } else {
        echo "Error updating subscription plan: " . $conn->error;
    }
    $stmt->close();
} else {
    echo "Payment failed. Please try again.";
}
?>

