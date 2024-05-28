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

<style>

#addDestinationForm {
    padding-top: 100px;
  width: 30%;
  margin: 0 auto;
}


label {
  font-weight: bold;
}


input[type="text"],
input[type="number"],
input[type="file"],
button {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}


button {
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
}


button:hover {
  background-color: #45a049;
}

</style>


<form id="addDestinationForm" action="../destination.php" method="post" enctype="multipart/form-data">
    <label for="destinationName">Destination Name:</label>
    <input type="text" id="destinationName" name="destinationName" required><br><br>
    
    <label for="destinationLocation">Location:</label>
    <input type="text" id="destinationLocation" name="destinationLocation" required><br><br>
    
    <label for="destinationImage">Image:</label>
    <input type="file" id="destinationImage" name="destinationImage" required><br><br>
    
    <label for="destinationRating">Rating:</label>
    <input type="number" id="destinationRating" name="destinationRating" min="0" max="5" step="0.1" required><br><br>
    
    <button type="submit" name="submit">Add Destination</button>
</form>


<section class="featured">
    <div class="gallery">
        <?php
        require '../db/db_connection.php';

        $sql = "SELECT * FROM destinations";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<a href='/destinations/" . strtolower(str_replace(' ', '', $row['name'])) . ".php' class='box-link'>";
                echo "<div class='box box3'>";
                echo "<span class='label'></span>";
                echo "<img src='" . $row['image'] . "' alt='Destination Image'>";
                echo "<div class='content'>";
                echo "<h2>" . $row['name'] . "</h2>";
                echo "<p>" . $row['location'] . "</p>";
                echo "<div class='review-and-idr'>";
                echo "<div class='review'><i class='fa fa-star'></i> " . $row['rating'] . " | review</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</a>";
                echo "</div>";
            }
        }
        $conn->close();
        ?>
    </div>
</section>

<?php
include('includes/script.php'); 
?>
