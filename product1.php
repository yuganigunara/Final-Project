<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="MealMorph - Your Meal Planning Partner">
        <meta name="author" content="MealMorph Team">

        <title>MealMorph - Welcome</title>

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
    background: linear-gradient(135deg, #8ea6b8, #738589, #a9cce3, #7f8c8d); /* Multiple gradient colors */
    background-size: 300% 300%; /* Expanded background for smooth motion */
    color: #2c3e50; /* Text color for good readability */
    padding: 20px 0; /* Adds padding */
    margin: 0;
    animation: gradientShift 10s ease-in-out infinite; /* Enhanced animation */
}

/* Keyframes for a smoother, visually appealing animation */
@keyframes gradientShift {
    0% {
        background-position: 0% 50%; /* Start at the left */
    }
    25% {
        background-position: 50% 0%; /* Top middle */
    }
    50% {
        background-position: 100% 50%; /* Right middle */
    }
    75% {
        background-position: 50% 100%; /* Bottom middle */
    }
    100% {
        background-position: 0% 50%; /* Back to the start */
    }
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

            .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            color: var(--secondary-color);
            background-color: transparent;
        }
            .hero {
        background: url("images/slider/ella-olsson-rD3YrnhTmf0-unsplash.jpg") no-repeat center center;
        background-size: cover;
        text-align: center;
        padding: 80px 0;
        color: #ffffff; /* Ensure the text is readable on the background */
    }

    

/* Enhanced Keyframes */
@keyframes enhancedFadeInSlideUp {
    0% {
        opacity: 0;
        transform: translateY(30px) scale(0.95); /* Start below and slightly smaller */
        color: rgba(255, 255, 255, 0.7); /* Softer color at the start */
    }
    50% {
        opacity: 0.7;
        transform: translateY(15px) scale(1.02); /* Slight bounce above original */
        color: rgba(255, 255, 255, 0.9); /* Slightly brighter mid-animation */
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1); /* Final position and size */
        color: var(--accent-color); /* Final color */
    }
}


 footer {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: #222;
            color: #fff;
            padding: 20px;
            margin-top: 20px;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        .footer-contact p {
            margin: 5px 0;
        }



 

<style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .logo span {
            color: red;
        }

        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #000;
        }

        .icons i {
            margin-left: 10px;
            cursor: pointer;
        }

        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #000080;
        }

        .hero-text h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #FF5733;
        }

        .hero-text input {
            padding: 10px;
            width: 300px;
            border: 1px solid #FF5733;
            border-radius: 5px;
        }

        .hero-image img {
            width: 200px;
            height: auto;
        }

        .categories {
            display: flex;
            justify-content: center;
            background-color: #fff;
            padding: 10px;
        }

        .categories a {
            margin: 0 10px;
            text-decoration: none;
            color: #000;
            font-size: 16px;
        }

        .products {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .product-card {
            background-color: #fff;
            width: 200px;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        .product-card img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }

        footer {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: #222;
            color: #fff;
            padding: 20px;
            margin-top: 20px;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }

        .footer-contact p {
            margin: 5px 0;
        }
    </style>


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
                <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="Ourplan.html">Plans</a></li>
                <li class="nav-item"><a class="nav-link" href="Shopping.php">Shopping</a></li>
                <li class="nav-item"><a class="nav-link" href="add_feedback.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="My_profile.php">My Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Log in</a></li>
            </ul>

        </div>
    </div>
</nav>

<section class="hero">
        <div class="hero-text">
            <h1>Get the best product at your home</h1>
            <input type="text" placeholder="Search your favorite product">
        </div>
        <div class="hero-image">
            <img src="headphones.jpg" alt="Headphones">
        </div>
    </section>
    
    <section class="categories">
        <a href="#">Dairy</a>
        <a href="#">Vagetabels</a>
        <a href="#">Fruits</a>
        <a href="#">Baverages</a>
        <a href="#">Meals</a>
        <a href="#">See all</a>
    </section>
    
    <section class="products">
        <div class="product-card">
            <img src="images/slider/BVE.jpg" alt="Puredale Kirithe - 400.00 g">
            <h3>Puredale Kirithe - 400.00 g</h3>
            <p>Rs900.00</p>
        </div>

        <div class="product-card">
            <img src="images/slider/DY1.jpg" alt="Pelwatte Butter - 200.00 g">
            <h3>Pelwatte Butter - 200.00 g  </h3>
            <p>Rs722.00</p>
        </div>

        <div class="product-card">
            <img src="images/slider/DY3.jpg" alt="Kotmale Processed Cheese Wedges - 120.00 g">
            <h3>Kotmale Processed Cheese Wedges - 120.00 g</h3>
            <p>Rs488.00</p>
        </div>

        <div class="product-card">
            <img src="images/slider/DY4.jpg" alt="Kotmale Drinking Yoghurt Strawberry - 180.00 ml">
            <h3>Kotmale Drinking Yoghurt Strawberry - 180.00 ml</h3>
            <p>Rs128.00</p>
        </div>

        <div class="product-card">
            <img src="images/slider/DY40.jpg" alt="Newdale Set Yoghurt Multi Pack 80 g - 8.00 pcs">
            <h3>Newdale Set Yoghurt Multi Pack 80 g - 8.00 pcs</h3>
            <p>Rs560.00</p>
        </div>
        <div class="product-card">
            <img src="images/slider/BVE04.jpg" alt="Richlife Chocolate Flavoured Milk - 180.00 ml">
            <h3>Richlife Chocolate Flavoured Milk - 180.00 ml</h3>
            <p>Rs140.00</p>
        </div>
        <div class="product-card">
            <img src="images/slider/BV3.jpg" alt="Enfamama A+ Vanilla - 400.00 g">
            <h3>Enfamama A+ Vanilla - 400.00 g</h3>
            <p>Rs3800.00</p>
        </div>
       <div class="product-card">
            <img src="images/slider/BV1.jpg" alt="Ambewela Fresh Milk - 1.00 l">
            <h3>Ambewela Fresh Milk - 1.00 l</h3>
            <p>Rs550.00</p>
        </div>

       <div class="product-card">
            <img src="images/slider/DYE0.jpg" alt="Kotmale Butter Salted - 200.00 g">
            <h3>Kotmale Butter Salted - 200.00 g</h3>
            <p>Rs792.00</p>
        </div>

       <div class="product-card">
            <img src="images/slider/DY9.jpg" alt="HAPPY HEN EGGS LARGE - 10.00 S">
            <h3>HAPPY HEN EGGS LARGE - 10.00 S</h3>
            <p>Rs570.00</p>
        </div>

       <div class="product-card">
            <img src="images/slider/BV00.jpg" alt="Ratthi Full Cream Milk Powder - 400.00 g">
            <h3>Ratthi Full Cream Milk Powder - 400.00 g</h3>
            <p>Rs1050.00</p>
        </div>
       <div class="product-card">
            <img src="images/slider/BV08.jpg" alt="Anchor Full Cream Milk Powder - 400.00 g">
            <h3>Anchor Full Cream Milk Powder - 400.00 g</h3>
            <p>Rs1055.00</p>
        </div>
      
    </section>
    <footer class="footer">
            <p>
                &copy; 2025 MealMorph. Designed for healthier living. Follow us on
                <a href="#">Facebook</a>, <a href="#">Instagram</a>, and <a href="#">Twitter</a>.
            </p>
            <p>Email: <a href="mailto:support@mealmorph.com">support@mealmorph.com</a></p>
            <p>Phone: (+94) 123 456 789</p>
            <p>Address: 123 MealMorph Street, Colombo, Sri Lanka</p>
        </footer>
