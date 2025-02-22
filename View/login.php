<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Job Portal BD</title>
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
            display: flex;
            flex-direction: column;
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
            text-decoration: none;
        }

        .login-container {
            max-width: 400px;
            margin: 4rem auto;
            padding: 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff99cc;
        }

        .error {
            color: #ff4444;
            font-size: 0.9rem;
            margin-top: 0.3rem;
            display: none;
        }

        button {
            width: 100%;
            padding: 1rem;
            background: #333;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #444;
        }

        .server-error {
            background: #ffe6e6;
            color: #ff4444;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 1rem;
        }

        .register-link a {
            color: #ff66b2;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    include '../Config/DBConnection.php';

    if (isset($_SESSION['ID'])) {
        if ($_SESSION['role'] == 'company') {
            header("Location: company_dashboard.php");
            exit();
        } else if ($_SESSION['role'] == 'job_seeker') {
            header("Location: job_seeker_dashboard.php");
            exit();
        }
    }
    $errors = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['ID'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'company') {
                    header("Location: company_dashboard.php");
                } else {
                    header("Location: job_seeker_dashboard.php");
                }
                exit();
            } else {
                $errors[] = "Invalid password!";
            }
        } else {
            $errors[] = "User not found!";
        }
    }
    ?>

    <nav>
        <a href="../index.php" class="logo">Job Portal BD</a>
    </nav>

    <div class="login-container">
        <h2>Welcome Back</h2>

        <?php if (!empty($errors)): ?>
            <div class="server-error">
                <?php foreach ($errors as $error)
                    echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" id="loginForm" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <div class="error" id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <div class="error" id="passwordError"></div>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>

    <script>
        function validateForm() {
            let isValid = true;
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');

            emailError.style.display = 'none';
            passwordError.style.display = 'none';

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                emailError.textContent = 'Please enter a valid email address';
                emailError.style.display = 'block';
                isValid = false;
            }


            if (password.value.length < 4) {
                passwordError.textContent = 'Password must be at least 4 characters long';
                passwordError.style.display = 'block';
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>

</html>