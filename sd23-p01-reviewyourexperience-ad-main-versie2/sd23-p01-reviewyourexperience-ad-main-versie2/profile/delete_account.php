<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

include '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];

    try {
        // Use a named placeholder in the query
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Destroy the session and redirect to login
        session_destroy();
        header("Location: ../login/login.php");
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error deleting account: " . htmlspecialchars($e->getMessage()) . "</div>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Account</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <form method="post">
        <p>Are you sure you want to delete your account? This action is irreversible.</p>
        <button type="submit">Delete Account</button>
    </form>
</body>
</html>
