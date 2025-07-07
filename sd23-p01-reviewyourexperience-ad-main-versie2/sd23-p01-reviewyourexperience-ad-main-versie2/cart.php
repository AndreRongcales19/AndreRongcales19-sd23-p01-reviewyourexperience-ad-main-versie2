<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Handle remove from cart
if (isset($_POST['remove_id'])) {
    foreach ($cart as $i => $item) {
        if ($item['id'] == $_POST['remove_id']) {
            unset($cart[$i]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($cart);
    header('Location: cart.php');
    exit;
}

$total = 0;
$total_count = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
    $total_count += $item['quantity'];
}
function get_cart_img_src($item) {
    if (isset($item['img_path'])) {
        return $item['img_path'] . $item['img'];
    }
    // fallback for old items
    return 'img/mountain/' . $item['img'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>
    <?php if (empty($cart)): ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php else: ?>
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars(get_cart_img_src($item)) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="height:60px;"></td>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>€<?= number_format($item['price'], 2, ',', '.') ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>€<?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="remove_id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <h4>Total (<?= $total_count ?> items): <span class="text-success">€<?= number_format($total, 2, ',', '.') ?></span></h4>
        </div>
    <?php endif; ?>
    <div class="mt-4">
        <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
    </div>
</div>
</body>
</html> 