<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lakbaymarista";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $email = mysqli_real_escape_string($conn, $email);

    $sql = "SELECT id, firstname, lastname, email, mobile, password, access_level FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $user_id = $row['id'];
        $access_level = $row['access_level'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['fname'] = $row['firstname'];
            $_SESSION['lname'] = $row['lastname'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['mobile'] = $row['mobile'];
            $_SESSION['access_level'] = $access_level;
            $conn->close();
            if ($access_level == 4) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/login-register.css">
    <style>

    </style>
</head>

<body style="background-color: black;">
    <?php
    if (isset($_GET['error']) && $_GET['error'] == 'invalid_credentials') {
        echo '<p style="color:red;">Invalid email or password. Please try again.</p>';
    }
    ?>

    <div class="container" id="container">
        <div id="successMessage" class="alert alert-success" role="alert" style="display: none;">
            <strong>Success!</strong> You have successfully logged in.
        </div>
        <div id="errorMessage" class="alert alert-danger" role="alert" style="display: none;">
            <strong>Error!</strong> Invalid email or password. Please try again.
        </div>

        <div class="form-container sign-up-container">
            <button type="button" id="cancelButton" onclick="cancelRegistration()">X</button>
            <form id="signupForm" method="post" action="register.php">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social"><i class="fab fa-github"></i></a>
                </div>
                <span>or use your email for registration</span>
                <input type="text" name="firstname" id="firstname" placeholder="First Name" required />
                <span id="firstNameError" class="error"></span>
                <input type="text" name="lastname" id="lastname" placeholder="Last Name" required />
                <span id="lastNameError" class="error"></span>
                <input type="email" name="email" id="email" placeholder="Email" required />
                <span id="emailError" class="error"></span>
                <input type="text" name="mobile" id="mobile" placeholder="Mobile Number" required />
                <span id="mobileError" class="error"></span>
                <input type="password" name="password" id="password" placeholder="Password" required />
                <span id="passwordError" class="error"></span>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
                <span id="confirmPasswordError" class="error"></span>
                <button type="submit" id="submit">Sign Up</button>
            </form>
        </div>


        <div class="form-container sign-in-container">
            <button type="button" id="cancelButton" onclick="cancelRegistration()">X</button>
            <form action="login.php" method="post">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>or use your account</span>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot your password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function cancelRegistration() {
            window.location.href = "index.php";
        }
        $(document).ready(function() {

            $("#signUp").click(function() {
                $("#container").addClass("right-panel-active");
            });


            $("#signIn").click(function() {
                $("#container").removeClass("right-panel-active");
            });


            $("#signupForm").submit(function(event) {
                event.preventDefault();

                var firstname = $("#firstname").val();
                var lastname = $("#lastname").val();
                var email = $("#email").val();
                var mobile = $("#mobile").val();
                var password = $("#password").val();
                var confirm_password = $("#confirm_password").val();
                var birth_month = $("#birth_month").val();
                var birth_day = $("#birth_day").val();
                var birth_year = $("#birth_year").val();

                $(".error").text("");

                if (firstname === "") {
                    displayError("firstname", "Name is required");
                    return;
                }
                if (lastname === "") {
                    displayError("lastname", "Name is required");
                    return;
                }
                if (birth_month === "") {
                    displayError("birth_month", "You must complete your birthdate");
                    return;
                }

                if (password === "") {
                    displayError("password", "Password is required");
                    return;
                }
                if (password.length < 8) {
                    displayError("password", "Password must be at least 8 characters");
                    return;
                }
                if (confirm_password === "") {
                    displayError("confirmPassword", "Confirm Password is required");
                    return;
                }
                if (password !== confirm_password) {
                    displayError("confirmPassword", "Passwords do not match");
                    return;
                }
                if (email === "") {
                    displayError("email", "Email is required");
                    return;
                }
                $.ajax({
                    url: "register.php",
                    method: "POST",
                    data: {
                        firstname: firstname,
                        lastname: lastname,
                        password: password,
                        mobile: mobile,
                        confirm_password: confirm_password,
                        email: email,
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.trim() === 'success') {
                            $("#successPopup").show();
                            setTimeout(function() {
                                window.location.href = "index.php";
                            }, 2000);
                        } else {
                            displayError("email", response);
                        }
                    },

                    error: function(xhr, status, error) {
                        displayError("email", "An error occurred. Please try again later.");
                    }
                });
            });
        });

        function displayError(inputFieldId, message) {
            $("#" + inputFieldId + "Error").text(message);
        }

        var name = $("#firstname").val() + " " + $("#lastname").val();
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        var email = $("#email").val();
        var mobile = $("#mobile").val();
    </script>
</body>

</html>