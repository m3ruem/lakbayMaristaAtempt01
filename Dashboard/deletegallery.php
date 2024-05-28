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

$galleryDir = '../assets/images/gallery/';
$message = '';

function deleteFile($filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $fileName = $_POST["delete"];
    $filePath = $galleryDir . $fileName;
    if (deleteFile($filePath)) {
        $message = "Image '$fileName' deleted successfully!";
    } else {
        $message = "Error deleting image '$fileName'.";
    }
}

$images = glob($galleryDir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            grid-gap: 20px;
        }

        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .image-container img {
            width: 100%;
            height: auto;
            transition: transform 0.3s ease-in-out;
        }

        .image-container:hover img {
            transform: scale(1.05);
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff4d4d;
            color: #fff;
            border: none;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .delete-btn:hover {
            background-color: #ff3333;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Image Gallery</h2>
        <div class="gallery">
            <?php foreach ($images as $image): ?>
                <?php $fileName = basename($image); ?>
                <div class="image-container">
                    <img src="<?php echo $image; ?>" alt="<?php echo $fileName; ?>">
                    <form action="" method="post">
                        <button type="submit" class="delete-btn" name="delete" value="<?php echo $fileName; ?>">X</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="message"><?php echo $message; ?></div>
    </div>
</body>
</html>
