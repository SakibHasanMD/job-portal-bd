<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Job Portal BD</title>
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


        .register-container {
            max-width: 500px;
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

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ff99cc;
        }

        .form-group select {
            background-color: white;
            cursor: pointer;
        }

        .error {
            color: #ff4444;
            font-size: 0.9rem;
            margin-top: 0.3rem;
            display: none;
        }

        .server-error {
            background: #ffe6e6;
            color: #ff4444;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
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
            margin-top: 1rem;
        }

        button:hover {
            background: #444;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }

        .login-link a {
            color: #ff66b2;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .role-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .role-option {
            flex: 1;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .role-option.selected {
            background: #333;
            color: white;
            border-color: #333;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    include '../config/DBConnection.php';
    if (isset($_SESSION['ID'])) {
        // Redirect based on role
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
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $phone = trim($_POST['phone']);
        $role = $_POST['role'];

        $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $result = $checkEmail->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Email already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $full_name, $email, $password, $phone, $role);

            if ($stmt->execute()) {
                $_SESSION['ID'] = $stmt->insert_id;
                $_SESSION['role'] = $role;

                if ($role === 'company') {
                    header("Location: company_dashboard.php");
                } else {
                    header("Location: job_seeker_dashboard.php");
                }
                exit();
            } else {
                $errors[] = "Registration failed!";
            }
        }
    }
    ?>

    <nav>
        <a href="../index.php" class="logo">Job Portal BD</a>
    </nav>

    <div class="register-container">
        <h2>Create Account</h2>
        <p class="subtitle">Join our community and start your journey</p>

        <?php if (!empty($errors)): ?>
            <div class="server-error">
                <?php foreach ($errors as $error)
                    echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="post" id="registerForm" onsubmit="return validateForm()">
            <div class="role-selector">
                <div class="role-option selected" onclick="selectRole('job_seeker', this)">Job Seeker</div>
                <div class="role-option" onclick="selectRole('company', this)">Company</div>
            </div>
            <input type="hidden" name="role" id="roleInput" value="job_seeker">

            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
                <div class="error" id="nameError"></div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
                <div class="error" id="emailError"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <div class="error" id="passwordError"></div>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
                <div class="error" id="phoneError"></div>
            </div>

            <button type="submit">Create Account</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>

    <script>
        function selectRole(role, element) {

            document.querySelectorAll('.role-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('roleInput').value = role;
        }

        function validateForm() {
            let isValid = true;
            const name = document.getElementById('full_name');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const phone = document.getElementById('phone');

            document.querySelectorAll('.error').forEach(error => error.style.display = 'none');


            if (name.value.length < 2) {
                document.getElementById('nameError').textContent = 'Name must be at least 2 characters long';
                document.getElementById('nameError').style.display = 'block';
                isValid = false;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                document.getElementById('emailError').textContent = 'Please enter a valid email address';
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }

            if (password.value.length < 4) {
                document.getElementById('passwordError').textContent = 'Password must be at least 4 characters long';
                document.getElementById('passwordError').style.display = 'block';
                isValid = false;
            }

            const phonePattern = /^\d{11}$/;
            if (!phonePattern.test(phone.value)) {
                document.getElementById('phoneError').textContent = 'Please enter a valid 11-digit phone number';
                document.getElementById('phoneError').style.display = 'block';
                isValid = false;
            }

            return isValid;
        }
    </script>
</body>

</html>