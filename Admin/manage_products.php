<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];

        $image = $_FILES['image']['name'];
        $target_file = "../uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        $stmt = $pdo->prepare('INSERT INTO products (name, price, category_id, image) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $price, $category_id, $target_file]);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
    }
}

$categories = $pdo->query('SELECT * FROM categories')->fetchAll();
$products = $pdo->query('SELECT * FROM products')->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Products</h1>
        <form method="post" enctype="multipart/form-data" class="mb-4">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            </div>
            <div class="form-group">
                <input type="number" name="price" class="form-control" placeholder="Price" required>
            </div>
            <div class="form-group">
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="file" name="image" class="form-control-file" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Add Product</button>
        </form>
        <ul class="list-group">
            <?php foreach ($products as $product): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?php echo htmlspecialchars($product['name']); ?></strong> - ₹<?php echo $product['price']; ?>
                        <img src="<?php echo $product['image']; ?>" width="50" alt="">
                    </div>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
    </div>
</body>
</html>
