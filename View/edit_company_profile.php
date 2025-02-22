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


$company = getCompanyProfile($conn, $company_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['company_name'];
    $industry = $_POST['industry'];
    $address = $_POST['address'];
    $business_description = $_POST['business_description'];
    $website = $_POST['website'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (saveCompanyProfile($conn, $company_id, $name, $industry, $address, $business_description, $website, $email, $phone)) {
        echo "Profile saved successfully!";
    } else {
        echo "Error saving profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company Profile</title>
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

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-login {
            background: transparent;
            color: #333;
        }

        .btn-signup {
            background: #333;
            color: white;
        }

        .container {
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
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-weight: 500;
            color: #555;
        }

        input,
        textarea {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        button[type="submit"] {
            background: #ff6b95;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 1rem;
        }

        button[type="submit"]:hover {
            background: #ff4f82;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #666;
            text-decoration: none;
        }

        .back-link:hover {
            color: #ff6b95;
        }
    </style>
</head>

<body>

    <nav>
        <a href="../index.php" class="logo">Job Portal BD</a>
        <div class="nav-links">
            <a href="company_dashboard.php">Back to Dashboard</a>
        </div>
    </nav>



    <div class="container">
        <h2>Edit Company Profile</h2>
        <form method="POST">
            <label>Company Name:</label>
            <input type="text" name="company_name" value="<?= htmlspecialchars($company['company_name'] ?? '') ?>"
                required>

            <label>Address:</label>
            <input type="text" name="address" value="<?= htmlspecialchars($company['address'] ?? '') ?>" required>

            <label>Industry:</label>
            <input type="text" name="industry" value="<?= htmlspecialchars($company['industry'] ?? '') ?>" required>

            <label>Description:</label>
            <textarea name="business_description"
                required><?= htmlspecialchars($company['business_description'] ?? '') ?></textarea>

            <label>Website:</label>
            <input type="url" name="website" value="<?= htmlspecialchars($company['website'] ?? '') ?>">

            <label>Phone:</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($company['phone'] ?? '') ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($company['email'] ?? '') ?>" required>

            <label>Logo:</label>
            <input type="text" name="logo" value="<?= htmlspecialchars($company['logo'] ?? '') ?>" required>

            <button type="submit">Update Profile</button>
        </form>

        <a href="company_dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>

</html>