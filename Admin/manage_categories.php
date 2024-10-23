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
        $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
        $stmt->execute([$name]);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
    }
}

$categories = $pdo->query('SELECT * FROM categories')->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Categories</h1>
        <form method="post" class="mb-4">
            <div class="input-group">
                <input type="text" name="name" class="form-control" placeholder="Category Name" required>
                <div class="input-group-append">
                    <button type="submit" name="add" class="btn btn-primary">Add</button>
                </div>
            </div>
        </form>
        <ul class="list-group">
            <?php foreach ($categories as $category): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php echo htmlspecialchars($category['name']); ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
    </div>
</body>
</html>
