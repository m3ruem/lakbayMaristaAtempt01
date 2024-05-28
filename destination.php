<?php
session_start();

require './db/db_connection.php';

if (isset($_POST["submit"])) {

  $destinationName = $_POST['destinationName'];
  $destinationLocation = $_POST['destinationLocation'];
  $destinationRating = $_POST['destinationRating'];


  $targetDir = "destinations/";
  $targetFile = $targetDir . basename($_FILES["destinationImage"]["name"]);
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


  $check = getimagesize($_FILES["destinationImage"]["tmp_name"]);
  if ($check !== false) {

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
      echo "Sorry, only JPG, JPEG, PNG files are allowed.";
      exit();
    } else {

      if (move_uploaded_file($_FILES["destinationImage"]["tmp_name"], $targetFile)) {

        $stmt = $conn->prepare("INSERT INTO destinations (name, location, image, rating) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $destinationName, $destinationLocation, $targetFile, $destinationRating);
        $stmt->execute();
        $stmt->close();

        header("Location: destination.php");
        exit();
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
  } else {
    echo "File is not an image.";
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lakbay Marista | Destinations </title>

  <link rel="shortcut icon" href="./assets/images/logoLM-dark.png" type="image/svg+xml">
  <link rel="stylesheet" href="./assets/css/destinations.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <link type="text/css" rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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

  <section class="featured">
    <div class="gallery">
      <div class="filter-buttons">
        <button id="popular-btn">Popular</button>
        <button id="featured-btn">Featured</button>
        <button id="new-btn">New</button>
        <button id="all-btn">All</button>
      </div>


      <div>
        <a href="/destinations/lakeholon.php" class="box-link">
          <div class="box box1">
            <span class="label">Featured</span>
            <img src="/imgs/destinations/lakeholon.jpg" alt="">
            <div class="content">
              <h2 style="font-family: Kanit, sans-serif;">LAKE HOLON</h2>
              <p>T'boli, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.9 | 853 review</div>
                <p></p>
              </div>
              <div></div>
            </div>
          </div>
        </a>
      </div>


      <div>
        <a href="/destinations/7-falls.php" class="box-link">
          <div class="box box2">

            <span class="label">Popular</span>
            <img src="/imgs/destinations/7-falls.jpg" alt="">
            <div class="content">
              <h2 style="font-family: Kanit, sans-serif;">7-FALLS</h2>
              <p>Lake Sebu, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.8 | 852 review</div>
                <p></p>
              </div>
            </div>

          </div>
        </a>
      </div>

      <div>
        <a href="/destinations/sgfarm.php" class="box-link">
          <div class="box box3">

            <span class="label"></span>
            <img src="/imgs/destinations/sgfarm.png" alt="">
            <div class="content">
              <h2>SG-FARM</h2>
              <p>Tupi, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.7 | 851 review</div>
                <p></p>
              </div>
            </div>

          </div>
        </a>
      </div>




      <div>
        <a href="/destinations/surralah.php" class="box-link">
          <div class="box box3">
            <span class="label"></span>
            <img src="https://4.bp.blogspot.com/-PP43Q8RCgjs/VuBq8m0CEwI/AAAAAAAAdiM/zR0uW9Ohwwo/s1600/DSC08992.JPG" alt="">
            <div class="content">
              <h2>Surralah Tri-People Monument</h2>
              <p>Surralah, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.7 | 851 review</div>
                <p></p>
              </div>
            </div>
          </div>
        </a>
      </div>


      <div>
        <a href="/destinations/matutum.php" class="box-link">
          <div class="box box3">
            <span class="label"></span>
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEimGFEEJfww4Q4jOUTQSddyzI0fOMBcLLj5UV0PSjVQyBvUcFIqxqo_C1AaWaM59DFSrnPTYDN8zkgxpOQ9evGh1CDnsZE_WVIMIrMC15ONNDT5w9O-Iwoz8OvSjhGZxkIsg6ote8tbEFRr/s1600/DSCF0896.JPG" alt="">
            <div class="content">
              <h2>Mt. Matutum</h2>
              <p>Polomolok, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.7 | 851 review</div>
                <p></p>
              </div>
            </div>
          </div>
        </a>
      </div>


      <div>
        <a href="/destinations/paraiso.php">
          <div class="box box3">
            <span class="label"></span>
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgu7y-AgbyhHj6ZPSIkTWRwCPe5Su4_2pm6wMhYeuIpMw6Lb3VhfvR8P_TdnKH4HG1PyQzjqiWk9N5qWS6wMJJkCXqqD4AkH2IXc1n1i2Xfa_vBi3Ulba55qdA8243PpbptyiUba_YW8oI/s1600/paraiso.jpg" alt="">
            <div class="content">
              <h2>Paraiso Verde Resort & Water Park</h2>
              <p>Koronadal City, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.7 | 851 review</div>
                <p></p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div>
        <a href="/destinations/siok.php">
          <div class="box box3">
            <span class="label"></span>
            <img src="https://scontent-mnl1-1.xx.fbcdn.net/v/t1.6435-9/116154248_157971259226151_4581289750263432113_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeEwYAYRqQQT8j0911ilsSKZfY2F99NrVvB9jYX302tW8F3EdhsrrzQEmt5FPewJ7iUKdWmTRkurl-zyqwHwWP_k&_nc_ohc=kUk5PYyMVCUQ7kNvgHASUFS&_nc_ht=scontent-mnl1-1.xx&oh=00_AYAliTw2VQWttM93hDNbgdIfBun2rUVRdcac0WMxIgJF7g&oe=66680D49" alt="">
            <div class="content">
              <h2>Siok Falls</h2>
              <p>Barangay Esperanza, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.7 | 851 review</div>
                <p></p>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div>
        <a href="/destinations/bakngeb.php">
          <div class="box box3">
            <span class="label"></span>
            <img src="https://cdn-0.danielsecotravels.com/wp-content/uploads/2020/04/14947477_1219205421486879_1546680201691883658_n-1.jpg?ezimgfmt=ng:webp/ngcb14" alt="">
            <div class="content">
              <h2>Bakngeb River Cave Adventure in Tboli
              </h2>
              <p>T'boli, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.7 | 851 review</div>
                <p></p>
              </div>
            </div>
          </div>
        </a>
      </div>


      <div>
        <a href="/destinations/gensantuna.php">
          <div class="box box3">
            <span class="label"></span>
            <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjVQP2hRClGnED3UgxXv-lXBxnWQkQlytVH2PRj-v3SaU8aT_93-EZNqeaxSoAYOpGC-wiiTgNvTHzotAK3NnZnxruDi3qwisEvLboZjwej1B412T0DmfKLdZqVEpntFYRPPznkgmESjcDe/s1600/DSCF1025.JPG" alt="">
            <div class="content">
              <h2>GenSanFish Port Complex</h2>
              <p>General Santos City</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.7 | 851 review</div>
                <p></p>
              </div>
            </div>
          </div>
        </a>
      </div>


      <div>
        <a href="/destinations/divinemercy.php">
          <div class="box box3">
            <span class="label"></span>
            <img src="https://pandamentals.files.wordpress.com/2017/07/img_9781.jpg?w=840" alt="">
            <div class="content">
              <h2>Divine Mercy Sanctuary</h2>
              <p>Lake Sebu, South Cotabato</p>
              <div class="review-and-idr">
                <div class="review"><i class="fa fa-star"></i> 4.7 | 851 review</div>
                <p></p>
              </div>
            </div>
          </div>
        </a>
      </div>
      <?php
      require './db/db_connection.php';

      $sql = "SELECT * FROM destinations";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
          echo "<div>";
          echo "<a href='/destinations/" . strtolower(str_replace(' ', '', $row['name'])) . ".php' class='box-link'>";
          echo "<div class='box box3'>";
          echo "<span class='label'></span>";
          echo "<img src='" . $row['image'] . "' alt='Destination Image'>";
          echo "<div class='content'>";
          echo "<h2>" . $row['name'] . "</h2>";
          echo "<p>" . $row['location'] . "</p>";
          echo "<div class='review-and-idr'>";
          echo "<div class='review'><i class='fa fa-star'></i> " . $row['rating'] . "</div>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
          echo "</a>";
          echo "</div>";
        }
      } else {
        echo "0 results";
      }
      $conn->close();
      ?>




    </div>
  </section>
  </div>

  <script src="imgs\destinations\featured.js"></script>
  <script src="/assets/js/script.js"></script>
  <script>
    var galleryItems = document.querySelectorAll(".box");

    galleryItems.forEach(function(item) {
      item.addEventListener("click", function() {
        var destinationName = this.querySelector("h2").textContent;
        window.location.href = "destination_detail.php?name=" + encodeURIComponent(destinationName);
      });
    });
  </script>
  <script>
    document.getElementById('popular-btn').addEventListener('click', function() {
      filterDestinations('popular');
    });

    document.getElementById('featured-btn').addEventListener('click', function() {
      filterDestinations('featured');
    });

    document.getElementById('new-btn').addEventListener('click', function() {
      filterDestinations('new');
    });

    document.getElementById('all-btn').addEventListener('click', function() {
      filterDestinations('all');
    });

    function filterDestinations(category) {
      var boxes = document.querySelectorAll('.box');
      boxes.forEach(function(box) {
        var isPopular = box.querySelector('.label') && box.querySelector('.label').innerText === 'Popular';
        var isNew = box.querySelector('.label') && box.querySelector('.label').innerText === 'New';
        var isFeatured = box.querySelector('.label') && box.querySelector('.label').innerText === 'Featured';
        var isNull = box.querySelector('.label') && box.querySelector('.label').innerText === '';
        if (category === 'all' || (category === 'popular' && isPopular) || (category === 'featured' && isFeatured) || (category === 'new' && isNew)) {
          box.style.display = 'block';
        } else {
          box.style.display = 'none';
        }
      });
    }
  </script>

</body>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</html>