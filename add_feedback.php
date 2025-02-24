<?php
// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "final";  // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch feedback from the database
$sql = "SELECT * FROM feedback"; // Assuming a 'feedback' table with 'title', 'feedback', 'stars', 'author'
$result = $conn->query($sql);

// Handle form submission for adding feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $feedback = $_POST['feedback'];
    $stars = $_POST['stars'];
    $author = $_POST['author'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO feedback (title, feedback, stars, author) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $feedback, $stars, $author);

    if ($stmt->execute()) {
        echo "";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    /* Root Variables for Theme Colors */
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #ecf0f1;
        --accent-color: #e74c3c;
        --light-color: #fdf8f2;
    }

    /* Smooth Gradient Background */
    body {
        font-family: 'Open Sans', sans-serif;
        background: linear-gradient(135deg, #8ea6b8, #738589, #a9cce3, #7f8c8d);
        background-size: 300% 300%; /* Expanded for smooth motion */
        color: var(--primary-color); /* Text color for readability */
        padding: 20px 0;
        margin: 0;
        animation: gradientShift 10s ease-in-out infinite;
    }

    /* Keyframes for Gradient Animation */
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        25% { background-position: 50% 0%; }
        50% { background-position: 100% 50%; }
        75% { background-position: 50% 100%; }
        100% { background-position: 0% 50%; }
    }

    /* Navbar Styles */
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

    .about-section {
    background-color: var(--light-color);
    padding: 50px;
    margin: 30px auto;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    max-width: 1200px;
}

.about-section img {
    border-radius: 10px;
    max-width: 100%;
    height: auto;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.about-section h2 {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--primary-color); /* Updated to use --primary-color */
}

.about-section p {
    font-size: 1.1rem;
    color: var(--primary-color); /* Updated to use --primary-color for consistency */
    line-height: 1.8;
    margin-bottom: 20px;
}

.about-section ul {
    padding-left: 0;
    list-style-type: none;
}

.about-section ul li {
    font-size: 1rem;
    margin-bottom: 10px;
}

.about-section ul li i {
    color: var(--primary-color); /* Updated to use --primary-color */
    margin-right: 10px;
}

/* Custom Button */
.btn-custom {
    background-color: var(--primary-color); /* Updated to use --primary-color */
    color: white;
    border-radius: 30px;
    font-weight: bold;
    padding: 12px 30px;
    transition: all 0.3s ease;
}

.btn-custom:hover {
    background-color: #34495e; /* Slightly darker shade for hover effect */
}

.btn-custom:focus {
    box-shadow: none;
}

    /* Feedback Card */
    .feedback-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .feedback-stars .fas {
        color:rgb(8, 6, 52);
    }

    /* Modal Header */
    .modal-header {
        background-color: var(--primary-color);
        color: white;
    }
    .customer-feedback-title {
    color:rgb(12, 12, 68); /* Dark blue shade resembling black-blue */
    font-weight: bold; /* Optional: Makes the text more prominent */
    text-align: center; /* Optional: Centers the text */
    margin-bottom: 20px; /* Adds spacing below the title */
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
<!-- About Section -->
<div class="about-section">
    <div class="row align-items-center">
        <div class="col-lg-6">
        <img src="images/slider/f2.jpg" alt="Description of the image">

        </div>
        <div class="col-lg-6">
            <h2 class="mb-4"><i class="fas fa-lightbulb"></i> Why Choose Us?</h2>
            <p>
                At our platform, we prioritize innovation and customer satisfaction. Your feedback helps us evolve and create exceptional services tailored to your needs. Together, we can shape the future of our platform with your insights.
            </p>
            <ul>
                <li><i class="fas fa-check-circle"></i> Dynamic Feedback Features</li>
                <li><i class="fas fa-check-circle"></i> Personalized Customer Experiences</li>
                <li><i class="fas fa-check-circle"></i> Commitment to Excellence</li>
            </ul>
           
        <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#feedbackModal">
            <i class="fas fa-plus-circle"></i> Add Feedback
        </button>
   
        </div>
    </div>
</div>

<!-- Customer Feedback Section -->
<div class="container mt-5">
<h2 class="customer-feedback-title">Customer Feedback</h2>

    <!-- Add Feedback Button -->
   

    <!-- Feedback Carousel -->
    <div id="customerFeedbackCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $index = 0; ?>
                <?php while ($feedback = $result->fetch_assoc()): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="feedback-card text-center">
                            <p class="feedback-stars">
                                <?php for ($i = 0; $i < $feedback['stars']; $i++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </p>
                            <h5 class="feedback-title"><?php echo htmlspecialchars($feedback['title']); ?></h5>
                            <p class="feedback-text"><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                            <p class="feedback-author text-muted">- <?php echo htmlspecialchars($feedback['author']); ?></p>
                        </div>
                    </div>
                    <?php $index++; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="carousel-item active">
                    <div class="feedback-card text-center">
                        <p>No feedback available. Be the first to add feedback!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#customerFeedbackCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#customerFeedbackCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<!-- Add Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel"><i class="fas fa-plus-circle"></i> Add Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Feedback Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Feedback</label>
                        <textarea name="feedback" id="feedback" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Your Name</label>
                        <input type="text" name="author" id="author" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="stars" class="form-label">Rating (1 to 5)</label>
                        <input type="number" name="stars" id="stars" class="form-control" min="1" max="5" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit Feedback</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>









