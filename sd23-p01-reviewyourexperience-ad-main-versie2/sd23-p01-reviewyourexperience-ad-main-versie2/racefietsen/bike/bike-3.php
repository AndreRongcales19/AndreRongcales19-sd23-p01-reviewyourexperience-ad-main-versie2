<?php
session_start();
global $db;
try {
    include_once('../../classes/Fiets.php');
    include '../../dbconnect.php';
} catch(PDOException $e) {
    die('Geen database server actief');
}

// onderstaande code paakt de img uit de database en word op de pagina geinjecteerd
$query = $db->prepare('SELECT img FROM fietsen WHERE id = 3');
$query->execute();
$query->setFetchMode(PDO::FETCH_CLASS, 'Fiets');
$fiets=$query->fetch();

//REVIEW CODES

$bike_id = 6;
$errors = [];
$include = [];

if (isset($_POST["send"])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_SPECIAL_CHARS);

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


    if (count($errors) === 0) {
        $stmt = $db->prepare("INSERT INTO review (bike_id, name, content) VALUES (:bike_id, :name, :content)");
        $stmt->bindParam(':bike_id', $bike_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':content', $review);
        $stmt->execute();
        header("Location: bike-3.php");
        exit;
    }
}


$reviews = $db->prepare("SELECT name, content, created_at FROM review WHERE bike_id = :bike_id ORDER BY created_at DESC");
$reviews->bindParam(':bike_id', $bike_id);
$reviews->execute();
$review_list = $reviews->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperSix EVO LAB71 Team</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<section class="container-fluid px-5">
    <nav class="navbar">
        <a class="text-center navbar-brand" href="../../index.php">
            <img src="../../img/ad-logo-monogram-circle-with-piece-ribbon-style-vector-29428125.jpg" alt="AD Bikes Logo fs-1">
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a class="nav-link text-dark" href="../../../login/login.php">Logged In</a>
        <?php else: ?>
            <a class="nav-link text-dark" href="../../login/login.php">Log In</a>
        <?php endif; ?>
    </nav>
</section>

<section class="container-fluid py-4">
    <div class="row">
        <div class="col-8"><img src="../../img/racefietsen/<?= $fiets->img?>" alt="bike-3" class="img-fluid product-img"></div>
        <div class="col-3 p-5">
            <p class="fs-2">SuperSix EVO</p>
            <h2 class="fs-2">LAB71 Team</h2>
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
                <button class="buy-button mt-5">Bestel nu!</button>
            </div>

            <!-- Review Form -->
            <section class="container-fluid py-4">
                <h3>Post a Review</h3>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $include['name'] ?? '' ?>">
                        <div class="form-text text-danger"><?= $errors['name'] ?? '' ?></div>
                    </div>

                    <div class="mb-3">
                        <label for="review" class="form-label">Review</label>
                        <textarea name="review" id="review" class="form-control"><?php echo $include['review'] ?? '' ?></textarea>
                        <div class="form-text text-danger"><?= $errors['review'] ?? '' ?></div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="send">Post Review</button>
                </form>
            </section>

            <!-- Display Reviews -->
            <section class="container-fluid py-4">
                <h3 class="pb-5">Reviews</h3>
                <?php if ($review_list): ?>
                    <?php foreach ($review_list as $review): ?>
                        <div class="card w-75 mb-3 review">
                            <div class="card-body">
                                <div class="d-flex">
                                    <img class="me-1 review-profile-photo" src="../../img/Profile-PNG-File.png" alt="">
                                    <h5 class="card-title"><strong><?= htmlspecialchars($review['name']) ?></strong></h5>
                                </div>
                                <p class="fw-light"><em>(<?= $review['created_at'] ?>)</em></p>
                                <p class="card-text"><?= htmlspecialchars($review['content']) ?></p>
                            </div>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet. Be the first to review!</p>
                <?php endif; ?>
            </section>
        </div>
    </div>
</section>
</body>
</html>
