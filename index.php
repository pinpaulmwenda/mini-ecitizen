<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mini eCitizen Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container text-center py-5">
        <div class="card border-0 shadow-lg mx-auto p-5 rounded-4" style="max-width: 600px;">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 70px; height: 70px;">
                <i class="fa-solid fa-landmark fa-2x"></i>
            </div>
            <h1 class="h3 fw-bold text-dark mb-2">Public Service Request System</h1>
            <p class="text-muted mb-4">Access county and public government services seamlessly online.</p>
            <hr class="mb-4">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="d-grid gap-2">
                    <a href="<?= $_SESSION['role'] === 'admin' ? 'admin_dashboard.php' : 'citizen_dashboard.php' ?>" class="btn btn-primary btn-lg fw-semibold">Go to Dashboard</a>
                    <a href="logout.php" class="btn btn-outline-danger btn-lg">Logout</a>
                </div>
            <?php else: ?>
                <div class="d-grid gap-2">
                    <a href="login.php" class="btn btn-primary btn-lg fw-semibold">Login to Portal</a>
                    <a href="register.php" class="btn btn-outline-success btn-lg fw-semibold">Register Citizen Account</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>