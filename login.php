<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "final";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Get user from database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;  // Store user data in session
            header("Location: My_profile.php");
            exit();
        } else {
            $error_message = "Invalid email or password!";
        }
    } else {
        $error_message = "No user found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        


        :root {
    --primary-color: #2c3e50;
    --secondary-color: #ecf0f1;
    --accent-color: #e74c3c;
    --light-color: #fdf8f2;
}

body {
    font-family: 'Open Sans', sans-serif;
    background: linear-gradient(135deg, #8ea6b8, #738589, #a9cce3, #7f8c8d);
    background-size: 300% 300%;
    color: #2c3e50;
    padding: 20px 0;
    margin: 0;
    animation: gradientShift 10s ease-in-out infinite;
}

@keyframes gradientShift {
    0% {
        background-position: 0% 50%;
    }
    25% {
        background-position: 50% 0%;
    }
    50% {
        background-position: 100% 50%;
    }
    75% {
        background-position: 50% 100%;
    }
    100% {
        background-position: 0% 50%;
    }
}


        .form-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: var(--primary-color);
        }

        .form-container a {
            text-align: center;
            display: block;
            margin-top: 10px;
            color: var(--accent-color);
            text-decoration: none;
        }

        .form-container a:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>

    

   

    <div class="form-container">
        <h2>Login</h2>
        <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>
        <form method="post" action="login.php">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <a href="sinin.php">Create an Account</a>

</body>
</html>










