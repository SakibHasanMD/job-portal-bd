<?php
session_start();
include '../config/DBConnection.php';
include '../Controller/auth_check.php';
include '../Model/DBOperations.php'; 

if ($_SESSION['role'] !== 'company') {
    header("Location: index.php");
    exit();
}

$company_id = $_SESSION['ID'];
$profile = $conn->query("SELECT * FROM company_profiles WHERE company_id = $company_id")->fetch_assoc();

if (!$profile || empty($profile['company_name']) || empty($profile['address'])) {
    header("Location: edit_company_profile.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = $_POST['job_title'];
    $requirements = $_POST['requirements'];
    $responsibilities = $_POST['responsibilities'];
    $benefits = $_POST['benefits'];
    $skills = $_POST['skills'];
    $location = $_POST['location'];
    $vacancy_count = $_POST['vacancy_count'];
    $job_category = $_POST['job_category'];
    $age_requirement = $_POST['age_requirement'];
    $experience = $_POST['experience'];
    $salary = $_POST['salary'];
    $employment_type = $_POST['employment_type'];
    $gender = $_POST['gender'];
    $application_deadline = $_POST['application_deadline'];
    $status = "active";
    if (postJob($conn, $company_id, $job_title, $requirements, $responsibilities, $benefits, $skills, $location, $vacancy_count, $job_category, $age_requirement, $experience, $salary, $employment_type, $gender, $application_deadline, $status)) {
        echo "<script>alert('Job posted successfully!'); window.location.href='company_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error posting job: " . $conn->error . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
            font-size: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 0.3rem;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        select {
            background-color: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }



        button {
            background: #ff69b4;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            width: 100%;
            margin-top: 1rem;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #ff1493;
        }

        .back-link {
            display: inline-block;
            color: #ff69b4;
            text-decoration: none;
            margin-top: 1rem;
            font-weight: bold;
        }

        .back-link:hover {
            color: #ff1493;
        }
    </style>
</head>

<body>
    <nav class="nav-bar">
        <a href="../index.php" class="logo">Job Portal BD</a>
        <a href="company_dashboard.php" class="back-link">Back to Dashboard</a>
    </nav>

    <div class="container">
        <h2>Post a Job</h2>

        <form method="POST">
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="job_title" required>
            </div>

            <div class="form-group">
                <label>Job Requirements</label>
                <textarea name="requirements" required></textarea>
            </div>

            <div class="form-group">
                <label>Job Responsibilities</label>
                <textarea name="responsibilities" required></textarea>
            </div>

            <div class="form-group">
                <label>Benefits</label>
                <textarea name="benefits" required></textarea>
            </div>

            <div class="form-group">
                <label>Required Skills</label>
                <textarea name="skills" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" required>
                </div>
                <div class="form-group">
                    <label>Number of Vacancies</label>
                    <input type="number" name="vacancy_count" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Job Category</label>
                    <input type="text" name="job_category" required>
                </div>
                <div class="form-group">
                    <label>Age Requirement</label>
                    <input type="text" name="age_requirement">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Experience Required</label>
                    <input type="text" name="experience">
                </div>
                <div class="form-group">
                    <label>Salary Range</label>
                    <input type="text" name="salary">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Employment Type</label>
                    <select name="employment_type" required>
                        <option value="Full Time">Full Time</option>
                        <option value="Part Time">Part Time</option>
                        <option value="Contract">Contract</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Preferred Gender</label>
                    <select name="gender">
                        <option value="Any">Any</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Application Deadline</label>
                <input type="date" name="application_deadline" required>
            </div>

            <button type="submit">Post Job</button>
        </form>
    </div>
</body>

</html>