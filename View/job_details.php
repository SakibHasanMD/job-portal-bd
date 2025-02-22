<?php
session_start();
include '../config/DBConnection.php';
include '../Controller/auth_check.php';
include '../Model/DBOperations.php';

if ($_SESSION['role'] !== 'job_seeker') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['job_id'])) {
    echo "Invalid job ID.";
    exit();
}

$job_id = intval($_GET['job_id']);
$job = getJobDetails($conn, $job_id);

if (!$job) {
    echo "Job not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details - Job Portal BD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ffd1dc 0%, #ffb6c1 100%);
            min-height: 100vh;
        }

        nav {
            background: rgba(255, 255, 255, 0.9);
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            margin-left: 1.5rem;
            font-weight: 500;
        }

        .nav-links .sign-up {
            background: #333;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .job-header {
            border-bottom: 2px solid #ffd1dc;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .job-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .company-name {
            color: #666;
            font-size: 1.2rem;
        }

        .job-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
            background: #fff5f6;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .info-item {
            margin-bottom: 1rem;
        }

        .info-label {
            font-weight: bold;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .section {
            margin: 2rem 0;
        }

        .section-title {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #ffd1dc;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #ff69b4;
            color: white;
        }

        .btn-secondary {
            background: #333;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="../index.php" class="logo">Job Portal BD</a>
        <div class="nav-links">
            <a href="edit_profile.php">Profile</a>
            <a href="../Controller/logout.php">Logout</a>

        </div>
    </nav>

    <div class="container">
        <div class="job-header">
            <h1 class="job-title"><?= htmlspecialchars($job['job_title']) ?></h1>
            <p class="company-name"><?= htmlspecialchars($job['company_name']) ?></p>
        </div>

        <div class="job-info">
            <div class="info-item">
                <div class="info-label">Location</div>
                <div><?= htmlspecialchars($job['location']) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Salary</div>
                <div><?= htmlspecialchars($job['salary']) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Employment Type</div>
                <div><?= htmlspecialchars($job['employment_type']) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Experience</div>
                <div><?= htmlspecialchars($job['experience']) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Category</div>
                <div><?= htmlspecialchars($job['job_category']) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Application Deadline</div>
                <div><?= htmlspecialchars($job['application_deadline']) ?></div>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Requirements</h3>
            <p><?= nl2br(htmlspecialchars($job['requirements'])) ?></p>
        </div>

        <div class="section">
            <h3 class="section-title">Responsibilities</h3>
            <p><?= nl2br(htmlspecialchars($job['responsibilities'])) ?></p>
        </div>

        <div class="section">
            <h3 class="section-title">Benefits</h3>
            <p><?= nl2br(htmlspecialchars($job['benefits'])) ?></p>
        </div>

        <div class="section">
            <h3 class="section-title">Skills Required</h3>
            <p><?= nl2br(htmlspecialchars($job['skills'])) ?></p>
        </div>

        <div class="buttons">
            <a href="apply.php?job_id=<?= $job['job_id'] ?>" class="btn btn-primary">Apply Now</a>
            <a href="job_seeker_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>

</html>