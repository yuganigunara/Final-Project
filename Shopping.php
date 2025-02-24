




<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="MealMorph - Your Meal Planning Partner">
    <meta name="author" content="MealMorph Team">

    <title>MealMorph - Shopping</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        /* Styles from the provided design */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #ecf0f1;
            --accent-color: #e74c3c;
            --light-color: #fdf8f2;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #8ea6b8, #738589, #a9cce3, #7f8c8d); /* Multiple gradient colors */
            background-size: 300% 300%;
            color: #2c3e50;
            padding: 20px 0;
            margin: 0;
            animation: gradientShift 10s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            25% { background-position: 50% 0%; }
            50% { background-position: 100% 50%; }
            75% { background-position: 50% 100%; }
            100% { background-position: 0% 50%; }
        }

        .navbar {
            background-color: rgba(44, 62, 80, 0.95);
            border-bottom: 3px solid var(--accent-color);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 20px 0;
        }

        .navbar-brand {
            font-size: 1.6rem;
            font-weight: bold;
            color: var(--accent-color);
        }

        .nav-link {
            color: var(--secondary-color);
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--accent-color);
        }

        .shopping-card {
            position: relative;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .shopping-card img {
            width: 100%;
            transition: opacity 0.3s ease;
        }

        .shopping-card .info {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: #ffffff;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        .shopping-card:hover .info {
            opacity: 1;
        }

        .footer {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            text-align: center;
            padding: 20px 10px;
            border-top: 3px solid var(--accent-color);
            font-size: 0.9rem;
            margin-top: 20px;
            box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.1);
        }

        .footer a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--secondary-color);
        }

    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">MealMorph</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon text-white"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="Ourplan.html">Plans</a></li>
                    <li class="nav-item"><a class="nav-link" href="Shopping.php">Shopping</a></li>
                    <li class="nav-item"><a class="nav-link" href="add_feedback.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="My_profile.php">My Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Log in</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Shopping Section -->
    <div class="container mt-5">
        <div class="row">
            <!-- Product 1 -->
            <div class="col-md-4">
                <div class="shopping-card">
                    <a href="product2.php" class="image-link">
                        <img src="images/slider/y2.jpg" alt="Product 1">
                    </a>
                    <div class="info">
                        <h4>Meat Iteam</h4>
                        <p>Discover this amazing product and boost your meal planning.</p>
                        <button class="btn btn-theme">Shop Now</button>
                    </div>
                </div>
            </div>

            <!-- Product 2 -->
            <div class="col-md-4">
                <div class="shopping-card">
                    <a href="product1.php" class="image-link">
                        <img src="images/slider/r4.jpg" alt="Product 2">
                    </a>
                    <div class="info">
                        <h4>Dairy Item</h4>
                        <p>Perfect for healthy meal planning and preparation.</p>
                        <button class="btn btn-theme">Shop Now</button>
                    </div>
                </div>
            </div>

            <!-- Product 3 -->
            <div class="col-md-4">
                <div class="shopping-card">
                    <a href="product3.php" class="image-link">
                        <img src="images/slider/5w.jpg" alt="Product 3">
                    </a>
                    <div class="info">
                        <h4>Vegitable Iteam</h4>
                        <p>A great addition to your kitchen for quick and healthy meals.</p>
                        <button class="btn btn-theme">Shop Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
            <p>
                &copy; 2025 MealMorph. Designed for healthier living. Follow us on
                <a href="#">Facebook</a>, <a href="#">Instagram</a>, and <a href="#">Twitter</a>.
            </p>
            <p>Email: <a href="mailto:support@mealmorph.com">support@mealmorph.com</a></p>
            <p>Phone: (+94) 123 456 789</p>
            <p>Address: 123 MealMorph Street, Colombo, Sri Lanka</p>
        </footer>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
