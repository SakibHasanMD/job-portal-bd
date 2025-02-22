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
$profile = getJobSeekerProfile($conn, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => $_POST['full_name'] ?? '',
        'address' => $_POST['address'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'career_objective' => $_POST['career_objective'] ?? '',
        'education' => $_POST['education'] ?? '',
        'looking_for' => $_POST['looking_for'] ?? '',
        'available_for' => $_POST['available_for'] ?? '',
        'expected_salary' => $_POST['expected_salary'] ?? '',
        'preferred_job_category' => $_POST['preferred_job_category'] ?? '',
        'skills' => $_POST['skills'] ?? '',
        'experience' => $_POST['experience'] ?? '',
        'date_of_birth' => $_POST['date_of_birth'] ?? '',
        'gender' => $_POST['gender'] ?? '',
        'marital_status' => $_POST['marital_status'] ?? '',
        'nationality' => $_POST['nationality'] ?? '',
        'current_location' => $_POST['current_location'] ?? '',
        'linkedin' => $_POST['linkedin'] ?? '',
        'portfolio' => $_POST['portfolio'] ?? '',
        'photo' => $profile['photo'] ?? '',
        'resume' => $profile['resume'] ?? '',
    ];

    if (!is_dir('../Assets/uploads')) {
        mkdir('../Assets/uploads', 0777, true);
    }

    if (!empty($_FILES['photo']['name'])) {
        $data['photo'] = '../Assets/uploads/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $data['photo']);
    }

    if (!empty($_FILES['resume']['name'])) {
        $data['resume'] = '../Assets/uploads/' . basename($_FILES['resume']['name']);
        move_uploaded_file($_FILES['resume']['tmp_name'], $data['resume']);
    }

    if (updateJobSeekerProfile($conn, $user_id, $data)) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Job Portal BD</title>
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


        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .profile-header {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .profile-header h2 {
            color: #333;
            border-bottom: 2px solid #ffd1dc;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .section-title {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #ffd1dc;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
            font-weight: 500;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
            border-color: #ff69b4;
            outline: none;
        }

        .photo-preview {
            margin: 1rem 0;
        }

        .photo-preview img {
            max-width: 150px;
            border-radius: 5px;
        }

        .file-input-group {
            margin: 1rem 0;
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
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
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

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="../index.php" class="logo">Job Portal BD</a>
    </nav>

    <div class="container">
        <div class="profile-header">
            <h2>Edit Your Profile</h2>
        </div>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-section">
                <h3 class="section-title">Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name:</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <input type="date" name="date_of_birth"
                            value="<?= htmlspecialchars($profile['date_of_birth'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Gender:</label>
                        <select name="gender">
                            <option value="Male" <?= ($profile['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male
                            </option>
                            <option value="Female" <?= ($profile['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female
                            </option>
                            <option value="Other" <?= ($profile['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Marital Status:</label>
                        <select name="marital_status">
                            <option value="Single" <?= ($profile['marital_status'] ?? '') == 'Single' ? 'selected' : '' ?>>
                                Single</option>
                            <option value="Married" <?= ($profile['marital_status'] ?? '') == 'Married' ? 'selected' : '' ?>>Married</option>
                            <option value="Divorced" <?= ($profile['marital_status'] ?? '') == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                            <option value="Widowed" <?= ($profile['marital_status'] ?? '') == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="form-section">
                <h3 class="section-title">Profile Photo & Resume</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Profile Photo:</label>
                        <div class="photo-preview">
                            <?php if (!empty($profile['photo'])): ?>
                                <img src="<?= htmlspecialchars($profile['photo']) ?>" alt="Profile Photo">
                            <?php else: ?>
                                <p>No profile photo uploaded.</p>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="photo">
                    </div>
                    <div class="form-group">
                        <label>Resume:</label>
                        <div class="file-input-group">
                            <?php if (!empty($profile['resume'])): ?>
                                <p><a href="<?= htmlspecialchars($profile['resume']) ?>" target="_blank">View Current
                                        Resume</a></p>
                            <?php else: ?>
                                <p>No resume uploaded.</p>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="resume">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Contact Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Address:</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($profile['address'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Current Location:</label>
                        <input type="text" name="current_location"
                            value="<?= htmlspecialchars($profile['current_location'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Nationality:</label>
                        <input type="text" name="nationality"
                            value="<?= htmlspecialchars($profile['nationality'] ?? '') ?>" required>
                    </div>
                </div>
            </div>


            <div class="form-section">
                <h3 class="section-title">Professional Information</h3>
                <div class="form-group">
                    <label>Career Objective:</label>
                    <textarea name="career_objective"
                        required><?= htmlspecialchars($profile['career_objective'] ?? '') ?></textarea>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Education:</label>
                        <input type="text" name="education" value="<?= htmlspecialchars($profile['education'] ?? '') ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Skills:</label>
                        <input type="text" name="skills" value="<?= htmlspecialchars($profile['skills'] ?? '') ?>"
                            required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Experience:</label>
                    <textarea name="experience"
                        required><?= htmlspecialchars($profile['experience'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Job Preferences</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Looking For:</label>
                        <select name="looking_for">
                            <option value="Entry Level Job" <?= ($profile['looking_for'] ?? '') == 'Entry Level Job' ? 'selected' : '' ?>>Entry Level</option>
                            <option value="Mid Level Job" <?= ($profile['looking_for'] ?? '') == 'Mid Level Job' ? 'selected' : '' ?>>Mid Level</option>
                            <option value="Senior Level Job" <?= ($profile['looking_for'] ?? '') == 'Senior Level Job' ? 'selected' : '' ?>>Senior Level</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Available For:</label>
                        <select name="available_for">
                            <option value="Full Time" <?= ($profile['available_for'] ?? '') == 'Full Time' ? 'selected' : '' ?>>Full Time</option>
                            <option value="Part Time" <?= ($profile['available_for'] ?? '') == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                            <option value="Internship" <?= ($profile['available_for'] ?? '') == 'Internship' ? 'selected' : '' ?>>Internship</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Expected Salary:</label>
                        <input type="text" name="expected_salary"
                            value="<?= htmlspecialchars($profile['expected_salary'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Preferred Job Category:</label>
                        <input type="text" name="preferred_job_category"
                            value="<?= htmlspecialchars($profile['preferred_job_category'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Online Presence</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>LinkedIn Profile:</label>
                        <input type="text" name="linkedin"
                            value="<?= htmlspecialchars($profile['linkedin_profile'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Portfolio Website:</label>
                        <input type="text" name="portfolio"
                            value="<?= htmlspecialchars($profile['portfolio'] ?? '') ?>">
                    </div>
                </div>
            </div>


            <div class="buttons">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="job_seeker_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </form>
    </div>
</body>

</html>