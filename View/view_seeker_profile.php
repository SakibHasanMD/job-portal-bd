<?php
session_start();
include '../config/DBConnection.php';
include '../Model/DBOperations.php';
include '../Controller/auth_check.php';


$user_id = null;

if (isset($_SESSION['role']) && $_SESSION['role'] === 'job_seeker') {
    $user_id = $_SESSION['ID'];
} else if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
} else {
    header("Location: index.php");
    exit();
}


$profile = getJobSeekerProfile($conn, $user_id);

if (!$profile) {
    header("Location: index.php");
    exit();
}

$is_owner = isset($_SESSION['role']) &&
    $_SESSION['role'] === 'job_seeker' &&
    $_SESSION['ID'] === $user_id;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Job Portal BD</title>
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
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            display: flex;
            align-items: flex-start;
            gap: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #ffd1dc;
            margin-bottom: 2rem;
        }

        .profile-photo {
            width: 200px;
            height: 200px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .profile-info h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .profile-meta {
            color: #666;
            font-size: 1.1rem;
        }



        .section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #eee;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            color: #ff69b4;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title::before {
            content: '';
            display: block;
            width: 30px;
            height: 3px;
            background: #ff69b4;
        }

        .career-objective {
            font-style: italic;
            color: #555;
            line-height: 1.8;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .info-item {
            background: #fff5f6;
            padding: 1rem;
            border-radius: 5px;
        }

        .info-label {
            font-weight: 500;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #333;
            font-size: 1.1rem;
        }

        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .skill-tag {
            background: #ffd1dc;
            color: #333;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .experience-item {
            margin-bottom: 1rem;
            white-space: pre-line;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 2rem;
        }

        .btn-primary {
            background: #ff69b4;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            color: #666;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .social-link:hover {
            color: #ff69b4;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }


        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="index.php" class="logo">Job Portal BD</a>
    </nav>

    <div class="container">
        <div class="profile-header">
            <img src="<?= htmlspecialchars($profile['photo'] ?? '/assets/default-avatar.png') ?>" alt="Profile Photo"
                class="profile-photo">

            <div class="profile-info">
                <h1><?= htmlspecialchars($profile['full_name'] ?? '') ?></h1>
                <div class="profile-meta">
                    <p><?= htmlspecialchars($profile['looking_for'] ?? '') ?></p>
                    <p><?= htmlspecialchars($profile['current_location'] ?? '') ?></p>
                    <p><?= htmlspecialchars($profile['phone'] ?? '') ?></p>
                </div>


                <div class="social-links">
                    <?php if (!empty($profile['linkedin_profile'])): ?>
                        <a href="<?= htmlspecialchars($profile['linkedin_profile']) ?>" class="social-link" target="_blank">
                            <span>LinkedIn</span>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($profile['portfolio'])): ?>
                        <a href="https://<?= htmlspecialchars($profile['portfolio']) ?>" class="social-link"
                            target="_blank">
                            <span>Portfolio</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Career Objective</h2>
            <p class="career-objective"><?= nl2br(htmlspecialchars($profile['career_objective'] ?? '')) ?></p>
        </div>

        <div class="section">
            <h2 class="section-title">Professional Summary</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Looking For</div>
                    <div class="info-value"><?= htmlspecialchars($profile['looking_for'] ?? '') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Available For</div>
                    <div class="info-value"><?= htmlspecialchars($profile['available_for'] ?? '') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Expected Salary</div>
                    <div class="info-value"><?= htmlspecialchars($profile['expected_salary'] ?? '') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Preferred Category</div>
                    <div class="info-value"><?= htmlspecialchars($profile['preferred_job_category'] ?? '') ?></div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Skills</h2>
            <div class="skills-list">
                <?php
                $skills = explode(',', $profile['skills'] ?? '');
                foreach ($skills as $skill):
                    if (trim($skill)):
                        ?>
                        <span class="skill-tag"><?= htmlspecialchars(trim($skill)) ?></span>
                        <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Experience</h2>
            <div class="experience-item">
                <?= nl2br(htmlspecialchars($profile['experience'] ?? '')) ?>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Education</h2>
            <div class="experience-item">
                <?= nl2br(htmlspecialchars($profile['education'] ?? '')) ?>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Personal Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value"><?= htmlspecialchars($profile['date_of_birth'] ?? '') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Gender</div>
                    <div class="info-value"><?= htmlspecialchars($profile['gender'] ?? '') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Marital Status</div>
                    <div class="info-value"><?= htmlspecialchars($profile['marital_status'] ?? '') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nationality</div>
                    <div class="info-value"><?= htmlspecialchars($profile['nationality'] ?? '') ?></div>
                </div>
            </div>
        </div>

        <?php if (!empty($profile['resume'])): ?>
            <div class="section">
                <h2 class="section-title">Resume</h2>
                <a href="<?= htmlspecialchars($profile['resume']) ?>" class="btn btn-primary" target="_blank">
                    View Resume
                </a>
            </div>
        <?php endif; ?>
        <?php if ($is_owner): ?>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a> <?php endif; ?>
    </div>
</body>

</html>