<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

include '../dbconnect.php';

$user_id = $_SESSION['user_id'];

try {
    // Prepare the statement
    $stmt = $db->prepare("SELECT username, email FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<div class='alert alert-danger'>User not found.</div>";
        exit;
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <img src="../img/ad-logo-monogram-circle-with-piece-ribbon-style-vector-29428125.jpg" alt="AD Bikes Logo fs-1" class="img-fluid" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="contact-us.html">Contact us</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown link
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../racefietsen/racefietsen-home.php">Racing Bikes</a></li>
                        <li><a class="dropdown-item" href="../mountain/mountain-home.php">Mountain Bikes</a></li>
                        <li><a class="dropdown-item" href="../stadfietsen/stadfietsen-home.php">City Bikes</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h1 class="card-title">Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
            <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <div class="d-flex gap-3">
                <a href="change_password.php" class="btn btn-primary">Change Password</a>
                <a href="delete_account.php" class="btn btn-danger">Delete Account</a>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>


