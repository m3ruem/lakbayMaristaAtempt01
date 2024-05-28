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


$uploadDir = '../assets/images/gallery/';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $file = $_FILES["image"];


    if ($file["error"] == UPLOAD_ERR_OK) {

        $fileName = basename($file["name"]);

        $destination = $uploadDir . $fileName;


        if (move_uploaded_file($file["tmp_name"], $destination)) {
            $message = "Image uploaded successfully!";
        } else {
            $message = "Error uploading image. Please try again.";
        }
    } else {
        $message = "Error: " . $file["error"];
    }
}
?>
<?php


if (!isset($_SESSION['user_id'])) {
    header("Location: ../access_denied.php");
    exit;
}


$user_access_level = $_SESSION['access_level'] ?? 0;


if ($user_access_level <= 1) {
    header("Location: ../access_denied.php");
    exit;
}

?>

<?php 
include('includes/header.php');
include('includes/navbar.php');
require_once '../db/db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container{
            align-content: center;
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="file"] {
            display: none;
        }

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
            background-color: #f0f0f0;
        }

        .btn-submit {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .message {
            margin-top: 10px;
            text-align: center;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Image</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="file-upload" class="custom-file-upload">
                Choose File
            </label>
            <input id="file-upload" type="file" name="image">
            <button type="submit" class="btn-submit">Upload</button>
        </form>
        <div class="message"><?php echo $message; ?></div>
    </div>
</body>
</html>
