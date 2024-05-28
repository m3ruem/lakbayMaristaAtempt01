<?php
session_start();
require '../db/db_connection.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$loggedin = isset($_SESSION['loggedin']) ? $_SESSION['loggedin'] : false;

if ($loggedin) {
    $stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstname, $lastname);
    $stmt->fetch();
    $stmt->close();
    
    $full_name = $firstname . ' ' . $lastname;
} else {
    $full_name = 'Guest';
}

$place_name = '7-falls';

$stmt = $conn->prepare("SELECT * FROM bookings WHERE user_full_name = ? AND place_name = ?");
$stmt->bind_param("ss", $full_name, $place_name);
$stmt->execute();
$result = $stmt->get_result();
$is_booked = $result->num_rows > 0;
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE place_name = ?");
$stmt->bind_param("s", $place_name);
$stmt->execute();
$stmt->bind_result($total_bookings);
$stmt->fetch();
$stmt->close();

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/destination-min.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <title>7-falls</title>

<body id="top">
    <style>
        .navbar-list a {
            color: black;
        }

        .login a {
            color: rgb(255, 255, 255);
        }

        .booked {
            color: black;
        }

        .rating-section {
            margin-top: 20px;
        }

        .star {
            font-size: 2rem;
            cursor: pointer;
            color: lightgray;
        }

        .star.rated,
        .star:hover,
        .star:hover~.star {
            color: gold;
        }

        .rating-summary {
            margin-top: 10px;
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
                        <a href="/index.php" class="logo-lm">
                            <img src="../assets/images/logoLM-dark.png" alt="Lakbay Marista" data-original-src="../assets/images/logoLM-dark.png">
                        </a>

                    </li>
                </ul>

                <nav class="navbar" data-navbar>

                    <div class="navbar-top">

                        <a href="/index.php" class="logo">
                            <img src="../assets/images/logo-text-v2.png" alt="Lakbay Marista">
                        </a>

                        <button class="nav-close-btn" aria-label="Close Menu" data-nav-close-btn>
                            <ion-icon name="close-outline"></ion-icon>
                        </button>

                    </div>

                    <ul class="navbar-list">
                        <li>
                            <a href="/index.php" class="navbar-link" data-nav-link>home</a>
                        </li>
                        <li>
                            <a href="/gallery.php" class="navbar-link" data-nav-link>gallery</a>
                        </li>
                        <li>
                            <a href="/destination.php" class="navbar-link" data-nav-link>destinations</a>
                        </li>
                        <li>
                            <a href="/contactus.php" class="navbar-link" data-nav-link>contact us</a>
                        </li>
                        <li>
                            <?php if (isset($_SESSION['loggedin'])) : ?>
                                <div class="dropdown">
                                    <a href="#" class="navbar-link dropdown-toggle">
                                        <div class="profile"><ion-icon name="person-circle-outline"></ion-icon></div>
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="/profile.php" class="dropdown-item">Profile</a>
                                        <a href="/activity.php" class="dropdown-item">Activity</a>
                                        <a href="/subscription.php" class="dropdown-item">Subscription</a>
                                        <a href="/logout.php" class="dropdown-item">Logout</a>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="login"> <a href="/login.php" class="btn btn-primary">Login</a></div>

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


    <main class='main' style="padding-top: 80px;">
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">

                <section class="islands swiper-slide">
                    <img src="https://southcotabato.gov.ph/wp-content/uploads/2021/07/Falls-scaled-1.jpg" alt="" class="islands__bg">

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle">Falls</h2>
                            <h1 class="islands__title">7-Falls</h1>
                            <p class="islands__description">Situated in highland town of South Cotabato, the Seven Falls is an amazing natural wonder in Barangay Lake Siloton of the municipality of Lake Sebu.</p>
                            <a href="/destinationcontents/7falls.html" class="islands__button">View More <i class='bx bx-right-arrow-alt islands__button-icon'></i></a>
                        </div>
                    </div>
                </section>


                <section class="islands swiper-slide">
                    <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhDSR-ihkS_gfC56m04OXacRpkytpO83ipYL7ARw_h-WcD8WoS1cEWqr1Jqyqefck3MUokfqnQIkhcrowt5RO3zsFPT1mKxhYiOh61RVBe7jvseiAXf-FBAVyeJ_MmpCMz_ndWkDcV5OdtC/s1600/05+lake+sebu+seven+falls+zipline.jpg" alt="" class="islands__bg">

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle">Falls</h2>
                            <h1 class="islands__title">7-Falls</h1>
                            <p class="islands__description">Challenge yourself with the 774-step climb to reach "Hikong Bente" (immeasurable), the tallest of the seven.</p>
                            <a href="/destinationcontents/7falls.html" class="islands__button">View More <i class='bx bx-right-arrow-alt islands__button-icon'></i></a>
                        </div>
                    </div>
                </section>
                <section class="islands swiper-slide">
                    <img src="/assets/images/gallery/7-falls.jpg" alt="" class="islands__bg">

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle">Falls</h2>
                            <h1 class="islands__title">7-Falls</h1>
                            <p class="islands__description">Destination that promises a refreshing escape, cultural immersion, and a chance to reconnect with nature's beauty.</p>
                            <a href="/destinationcontents/7falls.html" class="islands__button">View More <i class='bx bx-right-arrow-alt islands__button-icon'></i></a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        </div>

        <!--========== CONTROLS ==========-->
        <div class="controls gallery-thumbs">
            <div class="controls__container swiper-wrapper">
                <img src="https://southcotabato.gov.ph/wp-content/uploads/2021/07/Falls-scaled-1.jpg" alt="" class="controls__img swiper-slide">
                <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhDSR-ihkS_gfC56m04OXacRpkytpO83ipYL7ARw_h-WcD8WoS1cEWqr1Jqyqefck3MUokfqnQIkhcrowt5RO3zsFPT1mKxhYiOh61RVBe7jvseiAXf-FBAVyeJ_MmpCMz_ndWkDcV5OdtC/s1600/05+lake+sebu+seven+falls+zipline.jpg" alt="" class="controls__img swiper-slide">
                <img src="/assets/images/gallery/7-falls.jpg" alt="" class="controls__img swiper-slide">
            </div>
        </div>
    </main>

    <link rel="stylesheet" href="../assets/css/destination-min.css">



    <div id="content">
        <div class="wrapper">
            <div class="postbody">
                <article id="post-40871" class="post-40871 hentry" itemscope="itemscope" itemtype="http://schema.org/CreativeWorkSeries">
                    <div class="ts-breadcrumb bixbox">
                        <ol itemscope="" itemtype="http://schema.org/BreadcrumbList">
                            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                <a itemprop="item" href="../index.php"><span itemprop="name">Lakbay Marista</span></a>
                                <meta itemprop="position" content="1">
                            </li>
                            ›
                            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                <a itemprop="item" href="../destinations/7-falls.php"><span itemprop="name">7-falls</span></a>
                                <meta itemprop="position" content="2">
                            </li>
                        </ol>
                    </div>
                    <div class="bixbox animefull">
                        <div class="bigcontent nobigcover">
                            <div class="thumbook">
                                <div class="thumb" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                    <img class="wp-post-image" src="../assets/images/gallery/7-falls.jpg" title="" alt="7-falls" decoding="async" itemprop="image" fetchpriority="high">
                                </div>
                                <div class="rt">
                                    <div data-id="40871" class="bookmark <?php echo $is_booked ? 'booked' : ''; ?>">
                                        <button id="bookingBtn" class="<?php echo $is_booked ? 'booked' : ''; ?>" onclick="handleBooking()">
                                            <?php echo $is_booked ? 'Booked' : 'Booking'; ?>
                                        </button>
                                    </div>
                                    <div class="rating-section">
                                        <div id="starRating">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <span class="star" data-value="<?php echo $i; ?>">&#9733;</span>
                                            <?php endfor; ?>
                                        </div>
                                        <div id="ratingSummary" class="rating-summary"></div>
                                    </div>
                                    <div class="tsinfo">
                                        <div class="imptdt">
                                            Status <i>Open</i>
                                        </div>
                                        <div class="imptdt">
                                            Type <a href="">Falls</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="infox">
                                <h1 class="entry-title" itemprop="name">7-falls</h1>
                                <div class='socialts'>
                                    <a href="https://www.facebook.com/pages/Lake%20Sebu%207%20Falls%20South%20Cotabato/200821290049587/" target="_blank" class="fb">
                                        <i class="fab fa-facebook-f"></i>
                                        <span>Facebook</span>
                                    </a>

                                </div>

                                <div class="wd-full">

                                    <div class="entry-content entry-content-single" itemprop="description">
                                        <p>These enchanting falls are a captivating natural wonder in Lake Sebu, South Cotabato, and their beauty is truly awe-inspiring! 😊 You can explore them and enjoy the lush green surroundings when you visit this paradise. 🌿🌊

                                            If you’re up for adventure, Lake Sebu also boasts the highest zip line in Southeast Asia, offering spectacular views of the falls and the surrounding greenery12. Happy exploring! 🌟</p>
                                        <p>1. Hikong Alo (Passage Falls)</p>
                                        <p>2. Hikong Bente (Immeasurable Falls)</p>
                                        <p>3. Hikong B’Lebel (Zigzag Falls)</p>
                                        <p>4. Hikong Lowig (Booth Falls)</p>
                                        <p>5. Hikong K’Fo-I (Wild Flower Falls)</p>
                                        <p>6. Hikong Ukol (Short Falls)</p>
                                        <p>7. Hikong Tonok (Soil Falls)</p>


                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                    </div>
                                </div>
                                <div class="flex-wrap">
                                    <div class="fmed">
                                        <b>Location</b>
                                        <span>
                                            Lakesebu, South Cotabato
                                        </span>
                                    </div>
                                    <div class="fmed">
                                        <b>Mobile Number</b>
                                        <span>
                                            +63XXXXXXX
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-wrap">
                                    <div class="fmed">
                                        <b>Owner</b>
                                        <span>
                                            Unidentified
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-wrap">
                                    <div class="fmed">
                                        <b>Posted On</b>
                                        <span>
                                            <time itemprop="datePublished" datetime="2021-03-23T19:16:32+00:00">March 13, 2024</time>
                                        </span>
                                    </div>
                                    <div class="fmed">
                                        <b>Updated On</b>
                                        <span>
                                            <time itemprop="dateModified" datetime="2024-05-11T08:25:49+00:00">May 11, 2024</time>
                                        </span>
                                    </div>
                                </div>
                                <div class="wd-full">
                                    <b>Genres</b>
                                    <span class="mgen">
                                        <a href="" rel="tag">New</a>
                                        <a href="" rel="tag">Popular</a>
                                        <a href="" rel="tag">Featured</a>
                                        <a href="" rel="tag">Closed</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="disqus_thread"></div>
                    <script>
                        (function() {
                            var d = document,
                                s = d.createElement('script');
                            s.src = 'https://lakbaymarista.disqus.com/embed.js';
                            s.setAttribute('data-timestamp', +new Date());
                            (d.head || d.body).appendChild(s);
                        })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                </article>
            </div>
        </div>
    </div>
    <form id="bookingForm" action="../booking.php" method="POST" style="display: none;">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="place_name" value="7-Falls">
    </form>

    <form id="cancelBookingForm" action="../cancel_booking.php" method="POST" style="display: none;">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="place_name" value="7-Falls">
    </form>
    <form id="ratingForm" action="/rating.php" method="POST" style="display: none;">
        <input type="hidden" name="place_name" value="<?php echo htmlspecialchars($place_name); ?>">
        <input type="hidden" name="rating" id="ratingInput">
    </form>


    <script src="../assets/js/gsap.min.js"></script>
    <script src="../assets/js/swiper-bundle.min.js"></script>
    <script src="../destinations/script.js"></script>
    <script src="../assets/js/main.js"></script>
    <script>
        function handleBooking() {
            const bookingBtn = document.getElementById('bookingBtn');
            if (bookingBtn.classList.contains('booked')) {
                if (confirm('Do you want to cancel the booking?')) {
                    document.getElementById('cancelBookingForm').submit();
                }
            } else {
                document.getElementById('bookingForm').submit();
            }
        }

        function handleRating() {
            const stars = document.querySelectorAll('.star');
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-value');
                    submitRating(rating);
                });

                star.addEventListener('mouseover', function() {
                    stars.forEach(s => s.classList.remove('rated'));
                    this.classList.add('rated');
                    let prev = this.previousElementSibling;
                    while (prev) {
                        prev.classList.add('rated');
                        prev = prev.previousElementSibling;
                    }
                });

                star.addEventListener('mouseout', function() {
                    stars.forEach(s => s.classList.remove('rated'));
                });
            });
        }

        function submitRating(rating) {
            const placeName = "<?php echo htmlspecialchars($place_name); ?>";
            const formData = new FormData();
            formData.append('place_name', placeName);
            formData.append('rating', rating);

            fetch('/rating.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchRatingSummary();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        async function fetchRatingSummary() {
            const response = await fetch('/rating_summary.php?place_name=<?php echo urlencode($place_name); ?>');
            const data = await response.json();
            const ratingSummary = document.getElementById('ratingSummary');
            if (data.success) {
                ratingSummary.innerHTML = `
                <p>Average Rating: ${data.average_rating} (${data.total_ratings} ratings)</p>
                ${data.rating_percentages.map((percentage, index) => `
                    <p>${index + 1} star: ${percentage}%</p>
                `).join('')}
            `;
            } else {
                ratingSummary.innerHTML = '<p>Could not fetch rating summary.</p>';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            handleRating();
            fetchRatingSummary();
        });
    </script>

</body>

</html>