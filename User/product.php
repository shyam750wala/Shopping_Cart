<?php
require '../db.php';
session_start();

$category_id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM products WHERE category_id = ?');
$stmt->execute([$category_id]);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Products</h1>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">$<?php echo $product['price']; ?></p>
                            <a href="#" class="btn btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="index.php" class="btn btn-secondary mt-3">Back to Categories</a>
    </div>
</body>
</html>
