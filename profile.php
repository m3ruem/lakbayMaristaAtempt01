<?php
session_start();
require './db/db_connection.php';
// Check if the profile picture session variable is set

// Check if the current password matches before updating the profile information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['current_password']) && isset($_POST['new_password'])) {
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];

  // Verify the current password
  $email = $_SESSION['email'];
  $sql = "SELECT id, password FROM users WHERE email=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    if (password_verify($current_password, $hashed_password)) {
      // If the current password is correct, update the password
      $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
      $update_sql = "UPDATE users SET password=? WHERE id=?";
      $update_stmt = $conn->prepare($update_sql);
      $update_stmt->bind_param("si", $new_password_hashed, $row['id']);
      $update_stmt->execute();
    } else {
      // If the current password is incorrect, redirect with an error message
      header('Location: profile.php?error=invalid_password');
      exit;
    }
  } else {
    // Redirect with an error message if no user found
    header('Location: profile.php?error=user_not_found');
    exit;
  }
}
// Check if the profile picture is being updated
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])) {
  // Your existing code for uploading the file goes here...

  if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
    // File uploaded successfully, update database with file path or binary data
    $profile_pic_path = $target_file;
    $update_sql = "UPDATE users SET profile_pic=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
      // Error handling for SQL statement preparation
      die('Error: ' . $conn->error);
    }
    $update_stmt->bind_param("si", $profile_pic_path, $_SESSION['user_id']);
    if (!$update_stmt->execute()) {
      // Error handling for SQL statement execution
      die('Error: ' . $update_stmt->error);
    }

    // Update session variable if necessary
    $_SESSION['profile_picture'] = $profile_pic_path;
  } else {
    // Error handling for file upload failure
    die('Error uploading file.');
  }
}


// Check if other profile information is being updated
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['mobile'])) {
  // Get form data
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];

  // Update user information in the database
  $sql = "UPDATE users SET firstname=?, lastname=?, email=?, mobile=? WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssi", $fname, $lname, $email, $mobile, $_SESSION['user_id']);
  $stmt->execute();

  // Update session variables
  $_SESSION['fname'] = $fname;
  $_SESSION['lname'] = $lname;
  $_SESSION['email'] = $email;
  $_SESSION['mobile'] = $mobile;

  // Redirect back to the profile page
  header('Location: profile.php');
  exit;
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $email = mysqli_real_escape_string($conn, $email);

  $sql = "SELECT id, firstname, lastname, email, mobile, password, access_level FROM users WHERE email=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    if (password_verify($password, $hashed_password)) {
      $_SESSION['loggedin'] = true;
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['firstname'] . ' ' . $row['lastname'];
      $_SESSION['fname'] = $row['firstname'];
      $_SESSION['lname'] = $row['lastname'];
      $_SESSION['email'] = $row['email'];
      $_SESSION['mobile'] = $row['mobile'];
      $_SESSION['access_level'] = $row['access_level'];
      $_SESSION['profile_picture'] = $row['profile_picture'];

      $conn->close();

      if ($row['access_level'] == 4) {
        header('Location: dashboard.php');
      } else {
        header('Location: index.php');
      }
      exit;
    } else {
      $conn->close();
      header('Location: login.php?error=invalid_credentials');
      exit;
    }
  } else {
    $conn->close();
    header('Location: login.php?error=invalid_credentials');
    exit;
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>

  <link rel="shortcut icon" href="./assets/images/logoLM-dark.png" type="image/svg+xml">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link type="text/css" rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0px;
    }

    .profile-container {
      background-color: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      border-radius: 12px;
      padding: 40px;
      margin-top: 50px;
    }

    .profile-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .profile-header h2 {
      font-size: 36px;
      color: #333;
      margin-bottom: 20px;
    }

    .profile-header p {
      font-size: 18px;
      color: #666;
      margin-bottom: 0;
    }

    .profile-info {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .profile-info-item {
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .profile-info-item h3 {
      font-size: 20px;
      color: #333;
      margin-bottom: 10px;
    }

    .profile-info-item p {
      font-size: 16px;
      color: #666;
      margin: 0;
    }

    .profile-actions {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .profile-actions button {
      padding: 12px 24px;
      font-size: 16px;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      color: #fff;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .edit-btn {
      background-color: #3498db;
      margin-right: 10px;
    }

    .edit-btn:hover {
      background-color: #2980b9;
    }

    .change-password-btn {
      background-color: #e74c3c;
    }

    .change-password-btn:hover {
      background-color: #c0392b;
    }

    .edit-profile-form {
      display: none;
      background-color: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      border-radius: 12px;
      padding: 40px;
      margin-top: 50px;
    }

    .edit-profile-form h2 {
      font-size: 36px;
      color: #333;
      margin-bottom: 20px;
    }

    .edit-profile-form .form-group {
      margin-bottom: 20px;
    }

    .edit-profile-form label {
      font-size: 18px;
      color: #333;
    }

    .edit-profile-form input[type="text"],
    .edit-profile-form input[type="email"] {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-top: 8px;
      box-sizing: border-box;
    }

    .edit-profile-form .save-btn {
      background-color: #3498db;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 12px 24px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .edit-profile-form .save-btn:hover {
      background-color: #2980b9;
    }
  </style>
</head>

<body id="top">
  <style>
    .navbar-list a {
      color: black;
    }

    .filter-buttons {
      display: inline-block;
      justify-content: center;

      align-items: center;

      margin-bottom: 250px;

    }

    .login a {
      color: white;

    }
  </style>

  <header class="header" data-header>

    <div class="overlay" data-overlay></div>
    <div class="header-top">
      <div class="container">
        <div class="toggle-switch">
          <input type="checkbox" id="mode-switch" onclick="toggleMode()">
          <label for="mode-switch"></label>
        </div>
        <ul class="social-list">
          <li>
            <a href="index.php" class="logo-lm">
              <img src="./assets/images/logoLM-dark.png" alt="Lakbay Marista" data-original-src="./assets/images/logoLM-dark.png">
            </a>

          </li>
        </ul>

        <nav class="navbar" data-navbar>

          <div class="navbar-top">

            <a href="#" class="logo">
              <img src="./assets/images/logo-text-v2.png" alt="Lakbay Marista">
            </a>

            <button class="nav-close-btn" aria-label="Close Menu" data-nav-close-btn>
              <ion-icon name="close-outline"></ion-icon>
            </button>

          </div>

          <ul class="navbar-list">
            <li>
              <a href="index.php" class="navbar-link" data-nav-link>home</a>
            </li>
            <li>
              <a href="gallery.php" class="navbar-link" data-nav-link>gallery</a>
            </li>
            <li>
              <a href="destination.php" class="navbar-link" data-nav-link>destinations</a>
            </li>
            <li>
              <a href="contactus.php" class="navbar-link" data-nav-link>contact us</a>
            </li>
            <li>
              <?php if (isset($_SESSION['loggedin']) && $_SESSION['access_level'] >= 20) : ?>
            <li>
              <div class="dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                  <div class="profile">
                    <ion-icon id="profileIcon" name="person-circle-outline"></ion-icon>
                  </div>
                </a>
                <div class="dropdown-menu">
                  <a href="profile.php" class="dropdown-item">Profile</a>
                  
                  <a href="membership.php" class="dropdown-item">Membership</a>
                  <a href="./dashboard/" class="dropdown-item">Dashboard</a>
                  <a href="logout.php" class="dropdown-item">Logout</a>
                </div>
              </div>
            </li>
          <?php elseif (isset($_SESSION['loggedin'])) : ?>
            <li>
              <div class="dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                  <div class="profile"><ion-icon name="person-circle-outline"></ion-icon></div>
                </a>
                <div class="dropdown-menu">
                  <a href="profile.php" class="dropdown-item">Profile</a>
                  
                  <a href="membership.php" class="dropdown-item">Membership</a>
                  <a href="logout.php" class="dropdown-item">Logout</a>
                </div>
              </div>
            </li>
          <?php else : ?>
            <li>
              <div class="login"> <a href="login.php" class="btn btn-primary">Login</a></div>
            </li>
          <?php endif; ?>
          </li>
          </ul>

        </nav>


        <div class="header-btn-group">

          <button class="nav-open-btn" aria-label="Open Menu" data-nav-open-btn>
            <ion-icon name="menu-outline"></ion-icon>
          </button>

        </div>

      </div>
    </div>

    <div class="header-bottom">
      <div class="container">

      </div>
    </div>

  </header>
  <main>
    <div class="container">
      <div class="profile-container">
        <div class="profile-header">
          <h2>Welcome to Your Profile</h2>
          <p>This is your profile page.</p>
        </div>
        <div class="profile-info">
          <div class="profile-info-item">
            <h3>Name</h3>
            <p><?php echo isset($_SESSION['fname']) && isset($_SESSION['lname']) ? htmlspecialchars($_SESSION['fname'] . ' ' . $_SESSION['lname']) : 'N/A'; ?></p>
          </div>
          <div class="profile-info-item">
            <h3>Email</h3>
            <p><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'N/A'; ?></p>
          </div>
          <div class="profile-info-item">
            <h3>Phone Number</h3>
            <p><?php echo isset($_SESSION['mobile']) ? htmlspecialchars($_SESSION['mobile']) : 'N/A'; ?></p>
          </div>
          <div class="profile-info-item">
            <h3>User ID</h3>
            <p><?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : 'N/A'; ?></p>
          </div>
          <div class="profile-info-item">
            <h3>Access Level</h3>
            <p><?php echo isset($_SESSION['access_level']) ? htmlspecialchars($_SESSION['access_level']) : 'N/A'; ?></p>
          </div>
        </div>
        <div class="profile-actions">
          <button class="edit-btn">Edit Profile</button>
        </div>
      </div>
    </div>
    <!-- Edit profile form -->
    <div class="edit-profile-form" style="display: none;">
      <h2>Edit Profile</h2>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        
        <div class="form-group">
          <label for="fname">First Name</label>
          <input type="text" id="fname" name="fname" value="<?php echo isset($_SESSION['fname']) ? htmlspecialchars($_SESSION['fname']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="lname">Last Name</label>
          <input type="text" id="lname" name="lname" value="<?php echo isset($_SESSION['lname']) ? htmlspecialchars($_SESSION['lname']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="mobile">Phone Number</label>
          <input type="text" id="mobile" name="mobile" value="<?php echo isset($_SESSION['mobile']) ? htmlspecialchars($_SESSION['mobile']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="current_password">Current Password</label>
          <input type="password" id="current_password" name="current_password" value="<?php echo isset($_POST['current_password']) ? htmlspecialchars($_POST['current_password']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="new_password">New Password</label>
          <input type="password" id="new_password" name="new_password">
        </div>
        <button type="submit" class="save-btn">Save Changes</button>
      </form>
    </div>
    </div>
  </main>

  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up-outline"></ion-icon>
  </a>

  <script src="./assets/js/script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    // Toggle edit profile form visibility
    $(document).ready(function() {
      $('.edit-btn').click(function() {
        $('.profile-info').hide();
        $('.edit-profile-form').show();
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Change event handler for file input
      $('#profile_picture').change(function() {
        // Check if a file is selected
        if (this.files && this.files[0]) {
          // Create a FileReader object
          var reader = new FileReader();

          // Set a callback function to execute when the file is read successfully
          reader.onload = function(e) {
            // Update the src attribute of the ion-icon element
            $('#profileIcon').attr('src', e.target.result);
          };

          // Read the selected file as a data URL
          reader.readAsDataURL(this.files[0]);
        }
      });
    });
  </script>


</body>

</html>