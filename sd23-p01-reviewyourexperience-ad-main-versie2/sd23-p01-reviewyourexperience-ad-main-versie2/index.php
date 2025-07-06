<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - AD Bikes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand " href="index.php">
            <img src="img/ad-logo-monogram-circle-with-piece-ribbon-style-vector-29428125.jpg" alt="AD Bikes Logo fs-1">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="contact/contact-us.php">Contact us</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- ingelogd -->
                    <li class="nav-item">
                        <a class="nav-link active" href="profile/profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <!-- niet ingelogd -->
                    <li class="nav-item">
                        <a class="nav-link active" href="login/login.php">Log in</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown link
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="text-dark text-decoration-none" href="racefietsen/racefietsen-home.php">Racing Bikes </a></li>
                        <li><a class="text-dark text-decoration-none" href="mountain/mountain-home.php">Mountain Bikes</a></li>
                        <li><a class="text-dark text-decoration-none" href="stadfietsen/stadfietsen-home.php">City Bikes</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<section class="container-main">
    <div class="text-center py-5">
        <h1 class="fw-bolder py-5 home-title">ANYDAY BIKES</h1>
        <div id="carouselExampleIndicators" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <a href="racefietsen/racefietsen-home.php">
                        <img src="img/carousel%20img/racefietsen-carousel.jpg" class="d-block w-100" alt="...">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="mountain/mountain-home.php">
                        <img src="img/carousel%20img/mountain-carousel.jpg" class="d-block w-100" alt="...">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="stadfietsen/stadfietsen-home.php">
                        <img src="img/carousel%20img/stadsfietsen-carousel.jpg" class="d-block w-100" alt="...">
                    </a>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>
<section class="container-footer">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 mx-5">
        <div class="col-md-4 d-flex align-items-center">
            <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
                <img class="footer-logo" src="img/ad-logo-monogram-circle-with-piece-ribbon-style-vector-29428125.jpg" alt="">
            </a>
            <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Company, Inc</span>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
            <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-twitter-x"></i></a></li>
            <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-instagram"></i></a></li>
            <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-facebook"></i></a></li>
        </ul>
    </footer>
</section>
</body>
</html>
