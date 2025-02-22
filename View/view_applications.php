<?php
session_start();
include '../config/DBConnection.php';
include '../Controller/auth_check.php';
include '../Model/DBOperations.php';

if ($_SESSION['role'] !== 'company') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['ID'];

$company_id = getCompanyIdByUserId($conn, $user_id);

if (!$company_id) {
    echo "Company profile not found.";
    exit();
}

$applications = getApplicationsByCompanyId($conn, $company_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['status'])) {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    if (updateApplicationStatus($conn, $application_id, $status)) {
        echo "Application updated successfully!";
        header("Refresh:0");
        exit();
    } else {
        echo "Error updating application.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications</title>
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
            width: 95%;
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .applications-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .applications-table th,
        .applications-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .applications-table th {
            background: #ff69b4;
            color: white;
            font-weight: bold;
        }

        .applications-table tr:hover {
            background: #f9f9f9;
        }

        .applications-table a {
            color: #ff69b4;
            text-decoration: none;
        }

        .applications-table a:hover {
            text-decoration: underline;
        }

        select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 0.5rem;
        }

        button {
            padding: 0.5rem 1rem;
            background: #ff69b4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #ff1493;
        }

        .back-link {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.8rem 1.5rem;
            background: #ff69b4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .back-link:hover {
            background: #ff1493;
        }

        .status-pending {
            color: #ffa500;
        }

        .status-hired {
            color: #28a745;
        }

        .status-rejected {
            color: #dc3545;
        }

        .status-shortlisted {
            color: rgb(220, 167, 53);
        }
    </style>
</head>

<body>
    <nav class="nav">

        <a href="../index.php" class="logo">Job Portal BD</a>

        <a href="company_dashboard.php" class="back-link">Back to Dashboard</a>
    </nav>

    <div class="container">
        <h2>Job Applications</h2>

        <table class="applications-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>LinkedIn</th>
                    <th>Portfolio</th>

                    <th>View Profile</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($app = $applications->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($app['job_title']) ?></td>
                        <td><?= htmlspecialchars($app['full_name']) ?></td>
                        <td><?= htmlspecialchars($app['email']) ?></td>
                        <td><?= htmlspecialchars($app['phone']) ?></td>
                        <td><a href="<?= htmlspecialchars($app['linkedin_profile']) ?>" target="_blank">Linkedin Profile</a>
                        </td>
                        <td><a href="https://<?= htmlspecialchars($app['portfolio']) ?>" target="_blank">Portfolio</a></td>

                        <td><a href="view_seeker_profile.php?user_id=<?= htmlspecialchars($app['seeker_id']) ?>">View
                                Profile</a></td>
                        <td class="status-<?= strtolower($app['status']) ?>">
                            <?= htmlspecialchars($app['status']) ?>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                                <select name="status">
                                    <option value="pending" <?= $app['status'] === 'pending' ? 'selected' : '' ?>>Pending
                                    </option>
                                    <option value="shortlisted" <?= $app['status'] === 'shortlisted' ? 'selected' : '' ?>>
                                        Shortlisted</option>
                                    <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Rejected
                                    </option>
                                    <option value="hired" <?= $app['status'] === 'hired' ? 'selected' : '' ?>>Hired</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>