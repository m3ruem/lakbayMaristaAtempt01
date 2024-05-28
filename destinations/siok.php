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

$place_name = 'siok';

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


    <title>siok</title>

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
                            <a href="#contact" class="navbar-link" data-nav-link>contact us</a>
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
                                        <a href="/membership.php" class="dropdown-item">Membership</a>
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
                    <img src="https://scontent.fmnl4-8.fna.fbcdn.net/v/t39.30808-6/357432406_700534005418192_2584949224610982422_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeEfoqYPgeTkGmFLpu9qCsOZuDVSeGrWAuC4NVJ4atYC4CmQ9jsZdIm_GO-m4z7IwSx_AA4bio89L-QGAn4YFLcJ&_nc_ohc=jv7xlbbSQZIQ7kNvgGNa3wt&_nc_ht=scontent.fmnl4-8.fna&oh=00_AYCXiWZtWK_nRwnnJLhMYj-VbFopHdDiJK3iIdVZLJlO1g&oe=6647E756" alt="" class="islands__bg">
                    

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle"></h2>
                            <h1 class="islands__title">Siok Falls</h1>
                            <p class="islands__description">The trek through the verdant forests provides a glimpse into the diverse flora and fauna of the region. Keep an eye out for exotic birds flitting through the branches and vibrant butterflies dancing on the forest floor.


                            </p>
                            <a href="/destinationcontents/siok.html" class="islands__button">View More <i class='bx bx-right-arrow-alt islands__button-icon'></i></a>
                        </div>
                    </div>
                </section>


                <section class="islands swiper-slide">
                    <img src="https://scontent-mnl1-2.xx.fbcdn.net/v/t1.6435-9/51451651_296576714310175_8090667140878696448_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeF6rqst5JsDeotMpbL3Mw5zOsqHD_AjdC86yocP8CN0L47A7gCF0QH3v75Uq6d20jQW-g9q56DYga7K1rDcEY8X&_nc_ohc=R7gUmQ9-S7oQ7kNvgHcXyxw&_nc_ht=scontent-mnl1-2.xx&oh=00_AYDEI0AK1yBg9OoS2wx9bVPkP8rlv0vWzjTVppnrXLIPFA&oe=66697AF5" alt="" class="islands__bg">

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle"></h2>
                            <h1 class="islands__title">Siok Falls</h1>
                            <p class="islands__description">Siok Falls isn't just one spectacular cascade; it's a series of cascading tiers.  Each tier offers a unique perspective, allowing you to fully appreciate the power and the beauty of the water.

</p>
                            <a href="/destinationcontents/siok.html" class="islands__button">View More <i class='bx bx-right-arrow-alt islands__button-icon'></i></a>
                        </div>
                    </div>
                </section>
                <section class="islands swiper-slide">
                    <img src="https://welcometokoronadal.files.wordpress.com/2016/10/c0309-siokkoronadal6.jpg?w=820" alt="" class="islands__bg">

                    <div class="islands__container bd-container">
                        <div class="islands__data">
                            <h2 class="islands__subtitle"></h2>
                            <h1 class="islands__title">Siok Falls</h1>
                            <p class="islands__description">It's a sanctuary for relaxation and connection with nature.  Spread a picnic blanket by the pool's edge and listen to the soothing sounds of the cascading water.</p>
                            <a href="/destinationcontents/siok.html" class="islands__button">View More <i class='bx bx-right-arrow-alt islands__button-icon'></i></a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        </div>

        <!--========== CONTROLS ==========-->
        <div class="controls gallery-thumbs">
            <div class="controls__container swiper-wrapper">
                <img src="https://scontent.fmnl4-8.fna.fbcdn.net/v/t39.30808-6/357432406_700534005418192_2584949224610982422_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeEfoqYPgeTkGmFLpu9qCsOZuDVSeGrWAuC4NVJ4atYC4CmQ9jsZdIm_GO-m4z7IwSx_AA4bio89L-QGAn4YFLcJ&_nc_ohc=jv7xlbbSQZIQ7kNvgGNa3wt&_nc_ht=scontent.fmnl4-8.fna&oh=00_AYCXiWZtWK_nRwnnJLhMYj-VbFopHdDiJK3iIdVZLJlO1g&oe=6647E756" alt="" class="controls__img swiper-slide">
                <img src="https://scontent-mnl1-2.xx.fbcdn.net/v/t1.6435-9/51451651_296576714310175_8090667140878696448_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeF6rqst5JsDeotMpbL3Mw5zOsqHD_AjdC86yocP8CN0L47A7gCF0QH3v75Uq6d20jQW-g9q56DYga7K1rDcEY8X&_nc_ohc=R7gUmQ9-S7oQ7kNvgHcXyxw&_nc_ht=scontent-mnl1-2.xx&oh=00_AYDEI0AK1yBg9OoS2wx9bVPkP8rlv0vWzjTVppnrXLIPFA&oe=66697AF5" alt="" class="controls__img swiper-slide">
                <img src="https://images.trvl-media.com/lodging/40000000/39560000/39559900/39559815/3e8ddf72.jpg?impolicy=resizecrop&rw=1200&ra=fit" alt="" class="controls__img swiper-slide">
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
                                <a itemprop="item" href="../destinations/siok.php"><span itemprop="name">Siok Falls</span></a>
                                <meta itemprop="position" content="2">
                            </li>
                        </ol>
                    </div>
                    <div class="bixbox animefull">
                        <div class="bigcontent nobigcover">
                            <div class="thumbook">
                                <div class="thumb" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                    <img class="wp-post-image" src="https://scontent.fmnl4-8.fna.fbcdn.net/v/t1.6435-9/116295127_157971199226157_3265540733167906730_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeG09ehPfq005dJ94hx2eoPdKVKnsuRkUPYpUqey5GRQ9hb1bhMxngUy2UdyC8eYcH5yRL56adubLtTJ64Ol9bkg&_nc_ohc=Ue1oMY9N3x0Q7kNvgELUvls&_nc_ht=scontent.fmnl4-8.fna&oh=00_AYDnY9nF7VWVzw-M_S279GiFGIREv-HSjhzD3rfKBIM5bg&oe=66698D53" title="" alt="siok" decoding="async" itemprop="image" fetchpriority="high">
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
                                <h1 class="entry-title" itemprop="name">Siok Falls</h1>
                                <div class='socialts'>
                                    <a href="" target="_blank" class="fb">
                                        <i class="fab fa-facebook-f"></i>
                                        <span>Facebook</span>
                                    </a>
                                    <a href="" target="_blank" class="twt">
                                        <i class="fab fa-twitter"></i>
                                        <span>Twitter</span>
                                    </a>
                                    <a href="" target="_blank" class="wa">
                                        <i class="fab fa-whatsapp"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                    <a href="" target="_blank" class="pntrs">
                                        <i class="fab fa-pinterest-p"></i>
                                        <span>Pinterest</span>
                                    </a>
                                </div>

                                <div class="wd-full">

                                    <div class="entry-content entry-content-single" itemprop="description">
                                        <p>Siok Falls awaits! Lace up your hiking boots, embrace the adventure, and be captivated by the beauty and serenity of this hidden gem in South Cotabato.  Whether you're seeking a refreshing escape, a thrilling adventure, or simply a chance to reconnect with nature, Siok Falls has something to offer everyone.

                                        </p>



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
                                            Barangay Esperanza, South Cotabato
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
                </article>
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
    <form id="bookingForm" action="../booking.php" method="POST" style="display: none;">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="place_name" value="siok">
    </form>

    <form id="cancelBookingForm" action="../cancel_booking.php" method="POST" style="display: none;">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="place_name" value="siok">
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
            star.addEventListener('click', function () {
                const rating = this.getAttribute('data-value');
                submitRating(rating);
            });

            star.addEventListener('mouseover', function () {
                stars.forEach(s => s.classList.remove('rated'));
                this.classList.add('rated');
                let prev = this.previousElementSibling;
                while (prev) {
                    prev.classList.add('rated');
                    prev = prev.previousElementSibling;
                }
            });

            star.addEventListener('mouseout', function () {
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

    document.addEventListener('DOMContentLoaded', function () {
        handleRating();
        fetchRatingSummary();
    });
    </script>

</body>

</html>