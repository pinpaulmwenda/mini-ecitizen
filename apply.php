<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = $_POST['service_id'];
    $details = trim($_POST['details']);
    $user_id = $_SESSION['user_id'];
    
    $tracking_number = 'ECIT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

    $stmt = $pdo->prepare("INSERT INTO service_requests (user_id, service_id, details, tracking_number, status) VALUES (?, ?, ?, ?, 'Pending')");
    if ($stmt->execute([$user_id, $service_id, $details, $tracking_number])) {
        $success = "Application submitted successfully! Your Tracking Number is: <strong>$tracking_number</strong>";
    } else {
        $error = "Failed to submit application. Please try again.";
    }
}

$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply for Service - Mini eCitizen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm px-4">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><i class="fa-solid fa-shield-halved me-2"></i> eCitizen Portal</span>
            <div class="d-flex align-items-center text-white">
                <a href="citizen_dashboard.php" class="btn btn-outline-light btn-sm px-3">← Back to Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 rounded-4 bg-white">
                    <h3 class="text-center mb-3 fw-bold">Apply for Public Service</h3>
                    
                    <?php if($success): ?>
                        <div class="alert alert-success text-center"><?= $success ?></div>
                        <a href="citizen_dashboard.php" class="btn btn-primary w-100 fw-semibold">Back to Dashboard</a>
                    <?php else: ?>
                        <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Select Service</label>
                                <select name="service_id" class="form-control" required>
                                    <option value="">-- Choose a Service --</option>
                                    <?php foreach($services as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['service_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary">Application Details / Supporting Info</label>
                                <textarea name="details" class="form-control" rows="4" placeholder="Provide description, location, or issue details..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 fw-semibold shadow-sm">Submit Application</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>