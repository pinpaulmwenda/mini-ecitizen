<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT sr.*, s.service_name FROM service_requests sr JOIN services s ON sr.service_id = s.id WHERE sr.user_id = ? ORDER BY sr.submitted_at DESC");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Citizen Dashboard - Mini eCitizen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm px-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fa-solid fa-shield-halved me-2"></i> eCitizen Portal</span>
            <div class="d-flex align-items-center text-white">
                <span class="me-3 small">Welcome, <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm px-3">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark">My Applications & Status Tracking</h4>
            <a href="apply.php" class="btn btn-success fw-semibold"><i class="fa-solid fa-plus me-1"></i> Apply for New Service</a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Tracking No</th>
                            <th>Service</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($requests)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">No applications found. Submit your first request above!</td></tr>
                        <?php else: ?>
                            <?php foreach ($requests as $req): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= $req['tracking_number'] ?></span></td>
                                <td class="fw-semibold"><?= htmlspecialchars($req['service_name']) ?></td>
                                <td><?= htmlspecialchars($req['details']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $req['status']=='Approved'?'success':($req['status']=='Rejected'?'danger':($req['status']=='Under Review'?'info':'warning text-dark')) ?>">
                                        <?= $req['status'] ?>
                                    </span>
                                </td>
                                <td class="text-muted small"><?= $req['submitted_at'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>