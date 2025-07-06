<?php
session_start();
global $db;
try {
    include_once('../classes/Fiets.php');
    include '../dbconnect.php';
} catch(PDOException $e) {
    die('Geen database server actief');
}

$query_1 = $db->prepare('SELECT img FROM fietsen WHERE id = 7');
$query_2 = $db->prepare('SELECT img FROM fietsen WHERE id = 8');
$query_3 = $db->prepare('SELECT img FROM fietsen WHERE id = 9');

$query_1->execute();
$query_2->execute();
$query_3->execute();
// Dit zorg ervoor dat je alleen objecten krijg
$query_1->setFetchMode(PDO::FETCH_CLASS, 'Fiets');
$query_2->setFetchMode(PDO::FETCH_CLASS, 'Fiets');
$query_3->setFetchMode(PDO::FETCH_CLASS, 'Fiets');

$fiets_1=$query_1->fetch();
$fiets_2=$query_2->fetch();
$fiets_3=$query_3->fetch();

//$fiets_1->showImage();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stadfietsen</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
:root {
margin: 0;
padding: 0;
}

/* voor de logo */
.navbar-brand img {
height: 50px;
}
.product-img {
width: 100%;
}

/* home-title */
.home-title {
font-size: 5vw;
}
</style>

<body>
    <!-- Navbar met logo en login -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
    <a class="navbar-brand " href="index.php">
            <img src="../img/ad-logo-monogram-circle-with-piece-ribbon-style-vector-29428125.jpg" alt="AD Bikes Logo fs-1">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="../contact/contact-us.php">Contact us</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- ingelogd -->
                    <li class="nav-item">
                        <a class="nav-link active" href="../profile/profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- niet ingelogd -->
                    <li class="nav-item">
                        <a class="nav-link active" href="../login/login.php">Log in</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown link
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="text-dark text-decoration-none" href="../racefietsen/racefietsen-home.php">Racing Bikes </a></li>
                        <li><a class="text-dark text-decoration-none" href="../mountain/mountain-home.php">Mountain Bikes</a></li>
                        <li><a class="text-dark text-decoration-none" href="stadfietsen/stadfietsen-home.php">City Bikes</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
     <section class="container-fluid">
        <div class="container-md text-center py-5">
            <!-- Fiets afbeeldingen -->
            <div class="row py-5">
                <div class="col-md-4">
                    <a href="bike/bike-1.php">
                        <img src="../img/stadfietsen/<?= $fiets_1->img?>" alt="" class="product-img img-fluid">
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="bike/bike-2.php">
                        <img src="../img/stadfietsen/<?= $fiets_2->img?>" alt="" class="product-img img-fluid">
                    </a>
                </div>
                <div class="col-md-4">            
                    <a href="bike/bike-3.php">
                        <img src="../img/stadfietsen/<?= $fiets_3->img?>" alt="" class="product-img img-fluid">
                    </a>
                </div>
            </div>
        </div>
     </section>
</body>
</html>
