<?php
session_start();
include 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: citizen_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Mini eCitizen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center py-5" style="min-height: 100vh;">
    <div class="container">
        <div class="card border-0 shadow-lg mx-auto p-4 rounded-4" style="max-width: 420px;">
            <div class="text-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-landmark fa-xl"></i>
                </div>
                <h3 class="fw-bold text-dark">Portal Login</h3>
                <p class="text-muted small">Sign in to manage your public service requests</p>
            </div>

            <?php if($error): ?><div class="alert alert-danger py-2 small"><?= $error ?></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg fs-6" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary small">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg fs-6" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold shadow-sm">Secure Login</button>
            </form>
            <p class="mt-4 text-center text-muted small">Don't have an account? <a href="register.php" class="text-primary fw-semibold text-decoration-none">Register here</a></p>
        </div>
    </div>
</body>
</html>