<?php
session_start();
include '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validation
    if (empty($email) || empty($password)) {
        echo "<div class='alert alert-danger'>Email and password are required.</div>";
    } else {
        try {
            // Prepare the SQL statement
            $stmt = $db->prepare("SELECT id, username, password FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['password'])) {
                    // Save user info in session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Redirect to profile
                    header("Location: ../profile/profile.php");
                    exit;
                } else {
                    echo "<div class='alert alert-danger'>Invalid email or password.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>No user found with this email.</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- Logo bovenaan -->
    <div class="d-flex justify-content-center logo my-4">
        <a href="../index.php"><img src="../img/ad-logo-monogram-circle-with-piece-ribbon-style-vector-29428125.jpg" alt="AD Bikes Logo"> <!-- Replace with actual logo path --></a>
    </div>

    <!-- Main container -->
    <section class="container container-account-main">
        <div class="row">
            <!-- Sign in -->
            <div class="col-md-5 form-container">
                <h3>Sign In</h3>
                <br>
                <form method="post">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>">
                        <br>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <br>
                    </div>
                    <button type="submit" class="btn btn-black btn-block sign-in-button">Sign In</button>
                    <a href="#" class="d-block mt-2">Forgot Password?</a>
                </form>
            </div>

            <div class="col-md-2 d-flex justify-content-center">
                <div class="divider" ></div>
            </div>

            <!-- Sign up -->
            <div class="col-md-5 form-container">
                <h3>Sign Up</h3>
                <a href="register.php"><button type="button" class="btn btn-black btn-block create-account-button">Create Account</button></a>
            </div>
        </div>
    </section>
    <section class="container-footer">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 mx-5">
            <div class="col-md-4 d-flex align-items-center">
                <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
                    <img class="footer-logo" src="../img/ad-logo-monogram-circle-with-piece-ribbon-style-vector-29428125.jpg" alt="">
                </a>
                <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Company, Inc</span>
            </div>

            <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
                <!-- Add social media links here -->
            </ul>
        </footer>
    </section>
</body>
</html>
