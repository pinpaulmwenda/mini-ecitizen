<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['update_status'])) {
    $req_id = $_POST['request_id'];
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE service_requests SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $req_id]);
}

// Analytics Metrics
$total_cases = $pdo->query("SELECT COUNT(*) FROM service_requests")->fetchColumn();
$pending_cases = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'Pending'")->fetchColumn();
$review_cases = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'Under Review'")->fetchColumn();
$approved_cases = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'Approved'")->fetchColumn();
$rejected_cases = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'Rejected'")->fetchColumn();

$query = "SELECT sr.*, u.full_name, u.national_id, s.service_name 
          FROM service_requests sr 
          JOIN users u ON sr.user_id = u.id 
          JOIN services s ON sr.service_id = s.id 
          ORDER BY sr.submitted_at DESC";
$requests = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Mini eCitizen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm px-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fa-solid fa-gauge me-2"></i> Admin Control Panel</span>
            <div class="d-flex align-items-center text-white">
                <span class="me-3 small">Admin: <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm px-3">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <h4 class="fw-bold mb-3">Application Analytics</h4>
        <div class="row text-center mb-4 g-3">
            <div class="col">
                <div class="card border-0 shadow-sm p-3 bg-primary text-white rounded-3">
                    <span class="small uppercase">Total</span>
                    <h3 class="fw-bold mb-0"><?= $total_cases ?></h3>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm p-3 bg-warning text-dark rounded-3">
                    <span class="small uppercase">Pending</span>
                    <h3 class="fw-bold mb-0"><?= $pending_cases ?></h3>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm p-3 bg-info text-white rounded-3">
                    <span class="small uppercase">Review</span>
                    <h3 class="fw-bold mb-0"><?= $review_cases ?></h3>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm p-3 bg-success text-white rounded-3">
                    <span class="small uppercase">Approved</span>
                    <h3 class="fw-bold mb-0"><?= $approved_cases ?></h3>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm p-3 bg-danger text-white rounded-3">
                    <span class="small uppercase">Rejected</span>
                    <h3 class="fw-bold mb-0"><?= $rejected_cases ?></h3>
                </div>
            </div>
        </div>

        <h4 class="fw-bold mb-3">Citizen Requests Management</h4>
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Tracking No</th>
                            <th>Citizen Name</th>
                            <th>Service</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($requests)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No service requests found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($requests as $r): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= $r['tracking_number'] ?></span></td>
                                <td class="fw-semibold"><?= htmlspecialchars($r['full_name']) ?><br><span class="text-muted small">ID: <?= $r['national_id'] ?></span></td>
                                <td><?= htmlspecialchars($r['service_name']) ?></td>
                                <td><?= htmlspecialchars($r['details']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $r['status'] == 'Approved' ? 'success' : ($r['status'] == 'Rejected' ? 'danger' : ($r['status'] == 'Under Review' ? 'info' : 'warning text-dark')) ?>">
                                        <?= $r['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="" method="POST" class="d-flex gap-1">
                                        <input type="hidden" name="request_id" value="<?= $r['id'] ?>">
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="Pending" <?= $r['status']=='Pending'?'selected':''?>>Pending</option>
                                            <option value="Under Review" <?= $r['status']=='Under Review'?'selected':''?>>Under Review</option>
                                            <option value="Approved" <?= $r['status']=='Approved'?'selected':''?>>Approved</option>
                                            <option value="Rejected" <?= $r['status']=='Rejected'?'selected':''?>>Rejected</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-primary px-2">Update</button>
                                    </form>
                                </td>
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