<?php
session_start();

function getImagesFromFolder($folder) {
    $images = [];
    $files = scandir($folder);
    foreach ($files as $file) {
        $filePath = $folder . '/' . $file;
       
        if (is_file($filePath) && getimagesize($filePath)) {
            $images[] = $filePath;
        }
    }
    return $images;
}

$galleryImages = getImagesFromFolder('./assets/images/gallery');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lakbay Marista</title>

  <link rel="shortcut icon" href="./assets/images/logoLM-dark.png" type="image/svg+xml">

  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/css/gallery.css">
  <link type="text/css" rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body id="top">
  <style>
    .navbar-list a{
      color: black;
    }
    .login a{
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
              <?php if (isset($_SESSION['loggedin'])) : ?>
                <div class="dropdown">
                  <a href="#" class="navbar-link dropdown-toggle">
                    <div class="profile"><ion-icon name="person-circle-outline"></ion-icon></div>
                  </a>
                  <div class="dropdown-menu">
                    <a href="profile.php" class="dropdown-item">Profile</a>
                    
                    <a href="subscription.php" class="dropdown-item">Subscription</a>
                    <a href="logout.php" class="dropdown-item">Logout</a>
                  </div>
                </div>
              <?php else : ?>
                <div class="login"> <a href="login.php" class="btn btn-primary">Login</a></div>

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
  <div class="gallery">
    <?php foreach ($galleryImages as $image): ?>
      <img src="<?php echo $image; ?>" alt="Gallery Image">
    <?php endforeach; ?>
  </div>


  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up-outline"></ion-icon>
  </a>

  <script src="./assets/js/include.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>