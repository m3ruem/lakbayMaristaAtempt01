<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Lakbay Marista</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="shortcut icon" href="./assets/images/logoLM-dark.png" type="image/svg+xml">

  <link rel="stylesheet" href="./assets/css/style.css">
  <link type="text/css" rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg-color: #f5f5f5;
      --text-color: #333;
      --contact-info-bg: #fff;
      --contact-info-text: black;
      --accent-color: #ff6347;
    }

    body.dark-mode {
      --bg-color: #333;
      --text-color: #f5f5f5;
      --contact-info-bg: #444;
      --contact-info-text: black;
      /* Ensuring black text remains black */
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--bg-color);
      color: var(--text-color);
    }

    .navbar-list a {
      color: black;
    }

    .filter-buttons {
      display: inline-block;
      justify-content: center;
      /* Center horizontally */
      align-items: center;
      /* Center vertically */
      margin-bottom: 250px;
      /* Adjust as needed */
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 15px;
    }

    .contact-section {
      padding: 80px 0;
    }

    .contact-section h1 {
      text-align: center;
      font-size: 36px;
      margin-bottom: 40px;
      color: var(--text-color);
    }

    .contact-info {
      background-color: var(--contact-info-bg);
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .contact-info h3 {
      font-size: 24px;
      margin-bottom: 20px;
      color: var(--contact-info-text);
    }

    .contact-info p {
      font-size: 16px;
      margin-bottom: 30px;
      color: var(--contact-info-text);
    }

    .contact-info ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .contact-info ul li {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .contact-info ul li i {
      font-size: 20px;
      color: var(--accent-color);
      margin-right: 10px;
    }

    #contact-form {
      background-color: var(--contact-info-bg);
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    #contact-form .form-group {
      margin-bottom: 20px;
    }

    #contact-form label {
      font-size: 16px;
      font-weight: 600;
      color: var(--text-color);
    }

    #contact-form .form-control {
      border-radius: 4px;
      border: 1px solid #ddd;
      padding: 10px;
      font-size: 16px;
      color: #666;
    }

    #contact-form .btn-primary {
      background-color: var(--accent-color);
      border: none;
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      padding: 12px 24px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    #contact-form .btn-primary:hover {
      background-color: #e64a35;
    }
  </style>
</head>

<body>
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
                  <div class="profile"><ion-icon name="person-circle-outline"></ion-icon></div>
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
    <section class="contact-section">
      <div class="container">
        <h1>Contact Us</h1>
        <div class="row">
          <div class="col-md-6">
            <div class="contact-info">
              <h3>Get in Touch</h3>
              <p>We'd love to hear from you! Please fill out the form, and we'll get back to you as soon as possible.</p>
              <ul>
                <li><i class="fas fa-map-marker-alt"></i> NDMU, Koronadal City, Philippines</li>
                <li><i class="fas fa-phone"></i> +63912345678</li>
                <li><i class="fas fa-envelope"></i> noreply@lakbaymarista.com</li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <form id="contact-form" action="./assets/php/submit_contact.php" method="POST">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
              </div>
              <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer class="footer">
    <div class="footer-top">
      <div class="container">
        <div class="footer-brand">
          <a href="#" class="logo-lm">
            <img src="./assets/images/logo-text-white.png" alt="Tourly logo">
          </a>
          <p class="footer-text">
            "Embark on your next journey with us. Explore, discover, and create unforgettable memories together."
          </p>
        </div>
        <div class="footer-contact">
          <h4 class="contact-title">Contact Us</h4>
          <p class="contact-text">Feel free to contact and reach us !!</p>
          <ul>
            <li class="contact-item">
              <ion-icon name="call-outline"></ion-icon>
              <a href="tel:+01123456790" class="contact-link">+639123456789</a>
            </li>
            <li class="contact-item">
              <ion-icon name="mail-outline"></ion-icon>
              <a href="mailto:lakbaymarista@gmail.com" class="contact-link">lakbaymarista@gmail.com</a>
            </li>
            <li class="contact-item">
              <ion-icon name="location-outline"></ion-icon>
              <address>Koronadal, South Cotabato</address>
            </li>
          </ul>
        </div>
        <div class="footer-form">
          <p class="form-text">Subscribe our newsletter for more update & news !!</p>
          <form action="" class="form-wrapper">
            <input type="email" name="email" class="input-field" placeholder="Enter Your Email" required>
            <button type="submit" class="btn btn-secondary">Subscribe</button>
          </form>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p class="copyright">
          &copy; 2024 <a href="">LakbayMarista</a>. All rights reserved
        </p>
        <ul class="footer-bottom-list">
          <li>
            <a href="#" class="footer-bottom-link">Privacy Policy</a>
          </li>
          <li>
            <a href="#" class="footer-bottom-link">Term & Condition</a>
          </li>
          <li>
            <a href="#" class="footer-bottom-link">FAQ</a>
          </li>
        </ul>
      </div>
    </div>
  </footer>
  <a href="#top" class="go-top" data-go-top>
    <ion-icon name="chevron-up-outline"></ion-icon>
  </a>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="./assets/js/script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
    function toggleMode() {
      document.body.classList.toggle('dark-mode');
    }
  </script>
</body>

</html>