<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lakbaymarista";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$user_password = isset($_POST['password']) ? $_POST['password'] : '';

$hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

$access_level = 1;

$check_email_query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($check_email_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Error: Email already exists";
    exit(); 
}

$sql = "INSERT INTO users (firstname, lastname, email, mobile, password, access_level) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $firstname, $lastname, $email, $mobile, $hashed_password, $access_level);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $stmt->error;
    var_dump($stmt->error); 
}

$conn->close();
?>
