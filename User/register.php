<?php
session_start();
require '../db.php';

$error = '';

$captcha_code = '';
for ($i = 0; $i < 6; $i++) {
    $captcha_code .= chr(rand(97, 122));
}
$_SESSION['captcha'] = $captcha_code;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $captcha = $_POST['captcha'];
    if ($captcha !== $_SESSION['captcha']) {
        $error = "CAPTCHA verification failed. Please try again.";
    } else {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];

        $stmt = $pdo->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
        $stmt->execute([$username, $password, $email]);
        
        header('Location: login.php');
        exit;
    }
}

function generateCaptchaImage($code) {
    $width = 120;
    $height = 40;
    $image = imagecreatetruecolor($width, $height);

    $background_color = imagecolorallocate($image, 255, 255, 255);
    $text_color = imagecolorallocate($image, 0, 0, 0);
    $line_color = imagecolorallocate($image, 0, 0, 255);

    imagefill($image, 0, 0, $background_color);

    for ($i = 0; $i < 5; $i++) {
        imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
    }

    imagestring($image, 5, 10, 10, $code, $text_color);

    header('Content-Type: image/png');
    imagepng($image);
    imagedestroy($image);
}

if (isset($_GET['captcha'])) {
    generateCaptchaImage($captcha_code);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>User Registration</h2>
        <form method="post">
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="captcha">CAPTCHA:</label><br>
                <img src="register.php?captcha=true" alt="CAPTCHA" class="mb-2"><br>
                <input type="text" name="captcha" class="form-control" placeholder="Enter CAPTCHA" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mt-2"><?php echo $error; ?></div>
        <?php endif; ?>
        <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>