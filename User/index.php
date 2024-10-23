<?php
require '../db.php';
session_start();

$categories = $pdo->query('SELECT * FROM categories')->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Categories</h1>
        <div class="list-group">
            <?php foreach ($categories as $category): ?>
                <a href="product.php?id=<?php echo $category['id']; ?>" class="list-group-item list-group-item-action">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <a href="login.php" class="btn btn-secondary mt-3">Login</a>
        <a href="register.php" class="btn btn-secondary mt-3">Register</a>
    </div>
</body>
</html>
