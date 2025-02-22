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


$seekerQuery = $conn->prepare("SELECT seeker_id FROM job_seeker_profiles WHERE user_id = ?");
$seekerQuery->bind_param("i", $user_id);
$seekerQuery->execute();
$result = $seekerQuery->get_result();
$seeker = $result->fetch_assoc();

if (!$seeker) {
    echo "Profile not found.";
    exit();
}

$seeker_id = $seeker['seeker_id'];

if (!$job_id) {
    echo "Invalid Job ID.";
    exit();
}

$check = $conn->prepare("SELECT * FROM saved_jobs WHERE seeker_id = ? AND job_id = ?");
$check->bind_param("ii", $seeker_id, $job_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {

    $delete = $conn->prepare("DELETE FROM saved_jobs WHERE seeker_id = ? AND job_id = ?");
    $delete->bind_param("ii", $seeker_id, $job_id);
    $delete->execute();
    echo "Job removed from saved jobs.";
} else {
    $stmt = $conn->prepare("INSERT INTO saved_jobs (seeker_id, job_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $seeker_id, $job_id);
    if ($stmt->execute()) {
        echo "Job saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<a href="job_seeker_dashboard.php">Back to Dashboard</a>