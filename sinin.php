<?php
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = ""; // Default MySQL password
$dbname = "final"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration
if (isset($_POST['register'])) {
    // Collect user inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Check if the email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo "This email address is already registered.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (name, email, password, phone, address) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $password, $phone, $address);

        if ($stmt->execute()) {
            echo "Registration successful!";
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MealMorph</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
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
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white background */
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary-color); /* Dark color for text */
        }

        .form-container label {
            font-weight: 600;
            color: var(--primary-color); /* Dark color for labels */
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
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: var(--primary-color); /* Hover effect */
        }

        .form-container a {
            text-align: center;
            display: block;
            margin-top: 10px;
            color: var(--accent-color);
            text-decoration: none;
        }

        .form-container a:hover {
            color: var(--primary-color); /* Hover color change */
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create an Account</h2>
        <form method="post" action="">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            
            <label for="phone">Phone:</label><br>
            <input type="text" id="phone" name="phone" required><br><br>
            
            <label for="address">Address:</label><br>
            <input type="text" id="address" name="address" required><br><br>

            <button type="submit" name="register">Register</button>
        </form>
    </div>
  
</body>
</html>



