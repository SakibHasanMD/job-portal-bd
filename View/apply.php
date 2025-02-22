<?php
session_start();
include '../config/DBConnection.php';
include '../Controller/auth_check.php';

if ($_SESSION['role'] !== 'job_seeker') {
    header("Location: index.php");
    exit();
}


$user_id = $_SESSION['ID'];
$job_id = $_GET['job_id'] ?? null;
$profile = $conn->query("SELECT * FROM job_seeker_profiles WHERE user_id = $user_id")->fetch_assoc();

if (!$profile || empty($profile['full_name']) || empty($profile['resume'])) {
    header("Location: edit_profile.php");
    exit();
}

if (!$job_id) {
    echo "Invalid Job ID.";
    exit();
}

$check = $conn->query("SELECT * FROM applications WHERE seeker_id = $user_id AND job_id = $job_id");

if ($check->num_rows > 0) {
    echo "You have already applied for this job.";
} else {

    $stmt = $conn->prepare("INSERT INTO applications (seeker_id, job_id, status, application_date) VALUES (?, ?, 'Pending', NOW())");
    $stmt->bind_param("ii", $user_id, $job_id);
    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<a href="job_seeker_dashboard.php">Back to Dashboard</a>