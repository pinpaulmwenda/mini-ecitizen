<?php
include 'db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $national_id = trim($_POST['national_id']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (national_id, full_name, email, phone, password, role) VALUES (?, ?, ?, ?, ?, 'citizen')");
        $stmt->execute([$national_id, $full_name, $email, $phone, $password]);
        $success = "Registration successful! You can now <a href='login.php'>Login</a>.";
    } catch (PDOException $e) {
        $error = "Error: National ID or Email already exists.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Citizen Registration - Mini eCitizen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center py-5" style="min-height: 100vh;">
    <div class="container">
        <div class="card border-0 shadow-lg mx-auto p-4 rounded-4" style="max-width: 500px;">
            <div class="text-center mb-3">
                <h3 class="fw-bold text-dark">Citizen Signup</h3>
                <p class="text-muted small">Create your account to start requesting services</p>
            </div>

            <?php if($error): ?><div class="alert alert-danger py-2 small"><?= $error ?></div><?php endif; ?>
            <?php if($success): ?><div class="alert alert-success py-2 small"><?= $success ?></div><?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">National ID</label>
                    <input type="text" name="national_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Phone Number</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary small">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100 fw-semibold shadow-sm">Register Account</button>
            </form>
            <p class="mt-3 text-center text-muted small">Already have an account? <a href="login.php" class="text-primary fw-semibold text-decoration-none">Login here</a></p>
        </div>
    </div>
</body>
</html>