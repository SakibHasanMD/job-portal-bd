<?php
session_start();
include '../config/DBConnection.php';
include '../Model/DBOperations.php';

if ($_SESSION['role'] !== 'company') {
    header("Location: index.php");
    exit();
}

$company_id = $_SESSION['ID'];

try {
    $jobs = getCompanyJobs($conn, $company_id);
    $applications = getCompanyApplications($conn, $company_id);
} catch (Exception $e) {
    // Handle any database errors
    echo "Database Error: " . $e->getMessage();
    exit();
}

if (!$jobs) {
    $jobs = [];
}
if (!$applications) {
    $applications = [];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Company Dashboard</title>
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


        .container {
            width: 90%;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .welcome-text {
            font-size: 2rem;
            color: #333;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #ff69b4;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #ff1493;
        }

        .btn-outline {
            border: 2px solid #ff69b4;
            color: #ff69b4;
            background: white;
        }

        .btn-outline:hover {
            background: #ff69b4;
            color: white;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
        }

        .card-title {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            border-bottom: 2px solid #ff69b4;
            padding-bottom: 0.5rem;
        }

        .job-list,
        .application-list {
            list-style: none;
        }

        .job-list li,
        .application-list li {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .job-list li:last-child,
        .application-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <nav>
        <a href="../index.php" class="logo">Job Portal BD</a>
        <div class="nav-links">
            <a href="edit_company_profile.php">Edit Profile</a>
            <a href="../controller/logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="dashboard-header">
            <h2 class="welcome-text">Welcome, Employer!</h2>
            <div class="action-buttons">
                <a href="post_job.php" class="btn btn-primary">➕ Post a New Job</a>
                <a href="view_applications.php" class="btn btn-outline">📂 View All Applications</a>
            </div>
        </div>


        <div class="dashboard-card">
            <h3 class="card-title">Your Posted Jobs</h3>
            <ul class="job-list">
                <?php if ($jobs && $jobs->num_rows > 0): ?>
                    <?php while ($job = $jobs->fetch_assoc()): ?>
                        <li>
                            <strong><?= htmlspecialchars($job['job_title']) ?></strong>
                            <p style="color: #666;"><?= htmlspecialchars($job['location']) ?></p>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>No jobs posted yet.</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title">Recent Applications</h3>
            <ul class="application-list">
                <?php if ($applications && $applications->num_rows > 0): ?>
                    <?php while ($app = $applications->fetch_assoc()): ?>
                        <li>
                            <strong><?= htmlspecialchars($app['full_name']) ?></strong>
                            <p style="color: #666;">Applied for: <?= htmlspecialchars($app['job_title']) ?></p>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>No applications received yet.</li>
                <?php endif; ?>
            </ul>
        </div>


    </div>


</body>

</html>