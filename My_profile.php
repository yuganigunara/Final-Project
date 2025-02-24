<?php
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MealMorph - Your Meal Planning Partner">
    <meta name="author" content="MealMorph Team">

    <title>My Profile - MealMorph</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #fdfdfd;
            --accent-color: #e74c3c;
            --gradient-bg: linear-gradient(135deg, #8ea6b8, #a9cce3);
        }

        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            background: var(--gradient-bg);
            background-size: 300% 300%;
            animation: gradientShift 10s ease-in-out infinite;
            color: var(--primary-color);
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .navbar {
            background-color: rgba(44, 62, 80, 0.9);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: var(--accent-color);
            font-size: 1.5rem;
            font-weight: bold;
        }

        .sidebar {
            position: fixed;
            left: -250px;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: var(--primary-color);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            color: var(--secondary-color);
            padding: 20px 0;
            transition: left 0.3s ease-in-out;
            z-index: 1000;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px; /* Initially hidden */
            background-color: #333;
            padding-top: 60px;
            transition: 0.3s;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        /* Button to open sidebar */
        .open-btn {
            font-size: 24px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            position: fixed;
            top: 15px;
            left: 15px;
            border-radius: 5px;
        }

        .open-btn:hover {
            background-color: #0056b3;
        }


        .content {
            padding: 20px;
            margin-left: 0;
            transition: margin-left 0.3s ease-in-out;
        }

        .hero {
            text-align: center;
            padding: 80px 20px;
            color: var(--secondary-color);
            background: url("images/slider/ella-olsson-rD3YrnhTmf0-unsplash.jpg") no-repeat center center / cover;
        }

        .hero h1 {
            font-size: 2.5rem;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            text-align: center;
            padding: 20px;
            background-color: var(--primary-color);
            color: #fff;
            border-radius: 10px 10px 0 0;
        }

        .profile-content {
            padding: 20px;
        }

        .profile-content .info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .profile-content .info i {
            margin-right: 15px;
            color: var(--accent-color);
        }

        .logout-button a {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--accent-color);
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }

        .footer {
    background-color: var(--primary-color); /* Dark background for footer */
    color: var(--secondary-color); /* Light text for better readability */
    text-align: center;
    padding: 20px 10px; /* Reduced padding for a slimmer look */
    border-top: 3px solid var(--accent-color); /* Bold top border for a defined separation */
    font-size: 0.9rem; /* Slightly smaller text size */
    margin-top: 20px; /* Adds a gap above the footer */
    box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.1); /* Soft shadow to elevate */
}

.footer a {
    color: var(--accent-color); /* Highlight links */
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.footer a:hover {
    color: var(--secondary-color); /* Invert link color on hover */
}

.footer p {
    margin: 0; /* Removes unnecessary margin */
}

.footer-small-gap {
    height: 20px; /* Adds a small space to resemble a gap-like effect at the bottom */
    background-color: var(--light-color); /* Slightly lighter background to blend smoothly */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .footer {
        padding: 15px 5px; /* Reduce padding for smaller screens */
        font-size: 0.8rem; /* Adjust font size for readability */
    }
}
    </style>
</head>
<body>
<div class="open-btn" onclick="toggleSidebar()">&#9776; </div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <a href="index.html">Home</a>
    <a href="Ourplan.html">plans</a>
    <a href="Shopping.php">Shopping</a>
    <a href="add_feedback.php">About</a>
    <a href="logout.php">Logout</a>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        if (sidebar.style.left === "-250px") {
            sidebar.style.left = "0";
        } else {
            sidebar.style.left = "-250px";
        }
    }
</script>

    <div class="content" id="content">
        <div class="hero">
            <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <p>Start your healthy journey with us!</p>
        </div>

        <div class="profile-container">
            <div class="profile-header">
                <h2>Profile Details</h2>
            </div>
            <div class="profile-content">
                <div class="info">
                    <i class="fas fa-user"></i>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                </div>
                <div class="info">
                    <i class="fas fa-envelope"></i>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            <div class="logout-button text-center">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>

  <!-- AI Chat Button -->
<button onclick="myBtn()" id="myBtn" class="chat-btn" title="Chat with AI">CHAT WITH AI</button>



<script>
    function myBtn() {
        console.log("Button Clicked");
        window.location.href = "senuk/index.html";
    }
</script>

<!-- Button Styling -->
<style>
    .chat-btn {
        background: linear-gradient(45deg, #007bff, #6610f2);
        color: white;
        font-size: 18px;
        font-weight: bold;
        padding: 12px 25px;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.4);
        transition: all 0.3s ease;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .chat-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 15px rgba(0, 123, 255, 0.6);
    }

    .chat-btn:active {
        transform: scale(0.95);
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    }
</style>

          

        <!-- Footer Text -->
        <footer class="footer">
            <p>
                &copy; 2025 MealMorph. Designed for healthier living. Follow us on
                <a href="#">Facebook</a>, <a href="#">Instagram</a>, and <a href="#">Twitter</a>.
            </p>
            <p>Email: <a href="mailto:support@mealmorph.com">support@mealmorph.com</a></p>
            <p>Phone: (+94) 123 456 789</p>
            <p>Address: 123 MealMorph Street, Colombo, Sri Lanka</p>
        </footer>

</body>
</html>










