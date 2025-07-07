<?php
session_start();
global $db;
try {
    include_once('../../classes/Fiets.php');
    include '../../dbconnect.php';
    include '../../includes/review-filter.php';
} catch(PDOException $e) {
    die('Geen database server actief');
}

// onderstaande code paakt de img uit de database en word op de pagina geinjecteerd
$query = $db->prepare('SELECT img FROM fietsen WHERE id = 6');
$query->execute();
$query->setFetchMode(PDO::FETCH_CLASS, 'Fiets');
$fiets=$query->fetch();

//REVIEW CODES

$bike_id = 3;
$errors = [];
$include = [];

if (isset($_POST["send"])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_SPECIAL_CHARS);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

    $name = trim($name);
    $review = trim($review);
    
    // Validatie
    if (empty($name)) {
        $errors['name'] = "Please enter your name.";
    } else {
        $include['name'] = $name;
    }
    
    if (empty($review)) {
        $errors['review'] = "Please enter your review.";
    } else {
        $include['review'] = $review;
    }
    
    if (!$rating || $rating < 1 || $rating > 5) {
        $errors['rating'] = "Please select a valid rating (1-5 stars).";
    } else {
        $include['rating'] = $rating;
    }

    if (count($errors) === 0) {
        $stmt = $db->prepare("INSERT INTO review (bike_id, name, content, rating) VALUES (:bike_id, :name, :content, :rating)");
        $stmt->bindParam(':bike_id', $bike_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':content', $review);
        $stmt->bindParam(':rating', $rating);
        $stmt->execute();
        header("Location: bike-3.php");
        exit;
    }
}

// Add PHP logic at the top to handle add to cart
if (isset($_POST['add_to_cart'])) {
    $cart_bike = [
        'id' => $bike_id,
        'name' => 'SuperSix EVO LAB71',
        'img' => $fiets->img,
        'price' => 15499,
        'quantity' => 1,
        'img_path' => '/sd23-p01-reviewyourexperience-ad-main-versie2/sd23-p01-reviewyourexperience-ad-main-versie2/img/mountain/'
    ];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $cart_bike['id']) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    unset($item);
    if (!$found) {
        $_SESSION['cart'][] = $cart_bike;
    }
    header('Location: bike-3.php');
    exit;
}

// Date and Rating Filter Logic
$filter_type = $_GET['filter_type'] ?? 'all';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$min_rating = (int)($_GET['min_rating'] ?? 0);
$reviewer_name = $_GET['reviewer_name'] ?? '';

// Get filtered reviews using the reusable function
$review_list = getFilteredReviews($db, $bike_id, $filter_type, $start_date, $end_date, $min_rating, $reviewer_name);

// Get total review count using the reusable function
$total_count = getTotalReviewCount($db, $bike_id);

// Get average rating using the reusable function
$average_rating = getAverageRating($db, $bike_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperSix EVO LAB71</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <?php renderReviewFilterStyles(); ?>
</head>
<body>
<section class="container-fluid px-5">
    <nav class="navbar d-flex justify-content-between align-items-center">
        <div>
            <a class="text-center navbar-brand" href="../../index.php">
                <img src="../../img/ad-logo-monogram-circle-with-piece-ribbon-style-vector-29428125.jpg" alt="AD Bikes Logo fs-1">
            </a>
        </div>
        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="nav-link text-dark" href="../../../login/login.php">Logged In</a>
            <?php else: ?>
                <a class="nav-link text-dark" href="../../login/login.php">Log In</a>
            <?php endif; ?>
            <a href="../../cart.php" class="position-relative ms-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1h1a.5.5 0 0 1 .485.379L2.89 5H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 14H4a.5.5 0 0 1-.491-.408L1.01 2H.5a.5.5 0 0 1-.5-.5zm3.14 4l1.25 6.5h7.22l1.25-6.5H3.14z"/>
                </svg>
                <?php $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?>
                <?php if ($cart_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $cart_count ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </nav>
</section>
<section class="container-fluid py-4">
    <div class="row">
        <div class="col-8"><img src="../../img/mountain/<?= $fiets->img?>" alt="bike-3" class="img-fluid product-img"></div>
        <div class="col-3 p-5">
            <p class="fs-2">SuperSix EVO</p>
            <h2 class="fs-2">LAB71</h2>
            <h2 class="fs-2 fw-normal">€15,499</h2>
            <div class="col">
                <button class="bike-size-button mt-5 m-1">44</button>
                <button class="bike-size-button m-1">48</button>
                <button class="bike-size-button m-1">51</button>
                <button class="bike-size-button m-1">54</button>
                <button class="bike-size-button m-1">56</button>
                <button class="bike-size-button m-1">58</button>
                <button class="bike-size-button m-1">61</button>
            </div>
            <div class="col d-flex justify-content-center">
                <form method="post" action="">
                    <input type="hidden" name="add_to_cart" value="1">
                    <button type="submit" class="buy-button mt-5">Bestel nu!</button>
                </form>
            </div>

            <!-- Review Form -->
            <?php renderReviewForm($errors, $include); ?>

            <!-- Review Filter Section -->
            <?php renderReviewFilterForm($filter_type, $start_date, $end_date, $min_rating, $reviewer_name, 'bike-3.php'); ?>

            <!-- Display Reviews -->
            <?php renderReviewsSection($review_list, $total_count, $average_rating); ?>
        </div>
    </div>
</section>
</body>
</html>
