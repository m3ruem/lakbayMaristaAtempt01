<?php
include('header.php');
require_once './db/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./access_denied.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if the subscription_plan column exists
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'subscription_plan'");
if ($result->num_rows == 0) {
    // Add the column if it doesn't exist
    $conn->query("ALTER TABLE users ADD COLUMN subscription_plan VARCHAR(255) AFTER id");
}

$stmt = $conn->prepare("SELECT subscription_plan FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($current_plan);
$stmt->fetch();
$stmt->close();

$valid_plans = ['staff', 'moderator', 'administrator'];
$is_any_subscribed = in_array($current_plan, $valid_plans);

function renderPlan($plan_name, $plan_title, $price, $features, $current_plan, $is_any_subscribed) {
    $is_subscribed = ($current_plan === $plan_name);
    $button_text = $is_subscribed ? "You're already subscribed" : "Get Started";
    $button_class = $is_subscribed ? "button button--disabled" : "button";
    $button_disabled = $is_subscribed || $is_any_subscribed ? "disabled" : "";
    $button_html = "<button class=\"$button_class\" type=\"submit\" name=\"plan\" value=\"$plan_name\" $button_disabled>$button_text</button>";

    echo "
    <div class=\"planItem planItem--$plan_name\">
        <form method=\"POST\" action=\"subscription.php\">
            <div class=\"card\">
                <div class=\"card__header\">
                    <div class=\"card__icon symbol\"></div>
                    <h2>$plan_title</h2>
                </div>
                <div class=\"card__desc\"></div>
            </div>
            <div class=\"price\">₱$price<span>/ month</span></div>
            <ul class=\"featureList\">
                $features
            </ul>
            $button_html
        </form>
    </div>
    ";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_plan = $_POST['plan'];

    $access_level = 0;
    $price = 0;
    switch ($selected_plan) {
        case 'staff':
            $access_level = 20;
            $price = 150;
            break;
        case 'moderator':
            $access_level = 30;
            $price = 350;
            break;
        case 'administrator':
            $access_level = 40;
            $price = 0;
            break;
    }

    if ($price > 0) {
        header("Location: payment_gateway.php?plan=$selected_plan&price=$price");
        exit;
    } else {
        $stmt = $conn->prepare("UPDATE users SET subscription_plan = ?, access_level = ? WHERE id = ?");
        $stmt->bind_param("sii", $selected_plan, $access_level, $user_id);
        if ($stmt->execute()) {
            echo "Subscription plan updated successfully!";
        } else {
            echo "Error updating subscription plan: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="/assets/css/subscription.css">

<body>
    <style>
        .plans__container {
            padding-top: 200px;
        }
        .button--disabled {
            background-color: grey;
            cursor: not-allowed;
        }
    </style>

    <section class="plans__container">
        <div class="plans">
            <div class="planItem__container">
                <?php
                $features_staff = "
                    <li>Viewing bookings</li>
                    <li>Managing customer inquiries</li>
                    <li>Updating basic information</li>
                    <li>Limited access to the admin dashboard.</li>
                    <li class='disabled'>Upload Images in Gallery</li>
                ";

                $features_moderator = "
                    <li>Expanded access to the admin dashboard</li>
                    <li>Managing Destinations</li>
                    <li>Add and edit destination details</li>
                    <li>Upload images</li>
                    <li>Moderate user comments</li>
                ";

                $features_administrator = "
                    <li>Full access to the admin dashboard and all features</li>
                    <li>Booking management</li>
                    <li>Destination management</li>
                    <li>Gallery updates</li>
                    <li>Advanced features such as analytics, marketing tools, and customization options</li>
                    <li>Priority customer support and assistance</li>
                ";

                renderPlan('staff', 'Staff', '150', $features_staff, $current_plan, $is_any_subscribed);
                renderPlan('moderator', 'Moderator', '350', $features_moderator, $current_plan, $is_any_subscribed);
                renderPlan('administrator', 'Administrator', 'Let\'s Talk', $features_administrator, $current_plan, $is_any_subscribed);
                ?>
            </div>
        </div>
    </section>
</body>
</html>
