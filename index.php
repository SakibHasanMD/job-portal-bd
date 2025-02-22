<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal BD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #ffd1dc 0%, #ff99cc 100%);
            min-height: 100vh;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: rgba(255, 255, 255, 0.9);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }


        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .auth-buttons a {
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .login {
            color: #333;
        }

        .signup {
            background: #333;
            color: white;
        }

        main {
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
            text-align: center;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: url('data:image/svg+xml,<svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-repeat: no-repeat;
            background-size: cover;
            z-index: -1;
        }
    </style>
</head>

<body>
    <?php session_start(); ?>
    <nav>
        <div class="logo">Job Portal BD</div>

        <div class="auth-buttons">
            <?php if (isset($_SESSION['ID'])): ?>

                <?php if ($_SESSION['role'] == 'company'): ?>
                    <a href="View/company_dashboard.php" class="login">Dashboard</a>
                <?php else: ?> <a href="View/job_seeker_dashboard.php" class="login">Dashboard</a>
                <?php endif; ?>
                <a href="Controller/logout.php" class="signup">Logout</a>
            <?php else: ?>
                <a href="View/login.php" class="login">Login</a>
                <a href="View/register.php" class="signup">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        <h1>Find Talent..<br> Find Opportunity</h1>

        <img src="Assets/Images/index.png" height="400px" />
        <p>Connecting companies with top talent and job seekers with their dream roles.</p>

    </main>

    <div class="wave-bg"></div>
</body>

</html>