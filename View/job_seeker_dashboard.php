<?php
session_start();
include '../config/DBConnection.php';
include '../Controller/auth_check.php';
include '../Model/DBOperations.php';

if ($_SESSION['role'] !== 'job_seeker') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['ID'];

$jobs = getJobListings($conn);


$saved_jobs = getSavedJobs($conn, $user_id);

$applied_jobs = getAppliedJobs($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard - Job Portal BD</title>
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

        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin: 0.5rem 0;
        }

        .job-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .section-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .job-list {
            list-style: none;
        }

        .job-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .job-title {
            color: #333;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .company-name {
            color: #666;
            font-size: 0.9rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            margin-right: 0.5rem;
        }

        .btn-primary {
            background: #333;
            color: white;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .date {
            color: #888;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
    </style>
</head>

<body>

    <nav>
        <a href="../index.php" class="logo">Job Portal BD</a>
        <div class="nav-links">
            <a href="view_seeker_profile.php">Profile</a>
            <a href="../Controller/logout.php">Logout</a>
        </div>
    </nav>
    <div class="dashboard">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Applied Jobs</h3>
                <div class="stat-number"><?php echo $applied_jobs->num_rows; ?></div>
                <p>Applications Submitted</p>
            </div>
            <div class="stat-card">
                <h3>Saved Jobs</h3>
                <div class="stat-number"><?php echo $saved_jobs->num_rows; ?></div>
                <p>Jobs Bookmarked</p>
            </div>
            <div class="stat-card">
                <h3>Available Jobs</h3>
                <div class="stat-number"><?php echo $jobs->num_rows; ?></div>
                <p>Open Positions</p>
            </div>
        </div>

        <div class="job-sections">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Available Jobs</h3>
                </div>
                <ul class="job-list">
                    <?php while ($job = $jobs->fetch_assoc()): ?>
                        <li class="job-item">

                            <div class="job-title"><?= htmlspecialchars($job['job_title']) ?></div>
                            <div class="company-name"><?= htmlspecialchars($job['company_name']) ?></div>
                            <a href="job_details.php?job_id=<?= $job['job_id'] ?>" class="btn btn-primary">Details</a>
                            <a href="saved_jobs.php?job_id=<?= $job['job_id'] ?>" class="btn btn-secondary">Save Job</a>

                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Applied Jobs</h3>
                </div>
                <ul class="job-list">
                    <?php while ($applied = $applied_jobs->fetch_assoc()): ?>
                        <li class="job-item">
                            <a href="#" class="job-title"><?= htmlspecialchars($applied['job_title']) ?></a>
                            <div class="company-name"><?= htmlspecialchars($applied['company_name']) ?></div>
                            <div class="status-badge status-<?= strtolower($applied['status']) ?>">
                                <?= htmlspecialchars($applied['status']) ?>
                            </div>
                            <div class="date">
                                Applied: <?= date("F j, Y", strtotime($applied['application_date'])) ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Saved Jobs</h3>
                </div>
                <ul class="job-list">
                    <?php while ($saved = $saved_jobs->fetch_assoc()): ?>
                        <li class="job-item">
                            <a href="#" class="job-title"><?= htmlspecialchars($saved['job_title']) ?></a>
                            <div class="company-name"><?= htmlspecialchars($saved['company_name']) ?></div>
                            <a href="apply.php?job_id=<?= $saved['job_id'] ?>" class="btn btn-primary">Apply Now</a>
                            <a href="saved_jobs.php?job_id=<?= $saved['job_id'] ?>" class="btn btn-secondary">Remove</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>