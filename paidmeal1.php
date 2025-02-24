<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "final";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// AI API integration settings
$api_key = "c4732d21e0c743b5a657c9cfb1015d2b";
$endpoint = "https://ai-senuk.openai.azure.com/";
$deployment_name = "senuk-ai";

// Function to call OpenAI API
function callAI($user_message) {
    global $api_key, $endpoint, $deployment_name;

    $url = $endpoint . "openai/deployments/$deployment_name/chat/completions?api-version=2023-12-01-preview";

    $headers = [
        "Content-Type: application/json",
        "api-key: $api_key"
    ];

    $payload = json_encode([
        "messages" => [
            ["role" => "system", "content" => "You are an AI assistant."],
            ["role" => "user", "content" => $user_message]
        ],
        "max_tokens" => 100
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Initialize variables
$bmi = null;
$calorie_needs = null;
$meal_plan = '';
$form_submitted = false;
$pregnant = false; // Initialize $pregnant variable

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $form_submitted = true;

    // Retrieve form inputs
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $exercise = $_POST['exercise'];
    $diet = strtolower($_POST['diet']);
    $allergies = isset($_POST['allergies']) ? array_map('trim', explode(',', $_POST['allergies'])) : [];
    $pregnant = isset($_POST['pregnant']) ? $_POST['pregnant'] : false;
    $meal_plan_type = $_POST['meal_plan_type']; // Assuming a field for meal plan type is included in the form
    $meal_preference = $_POST['meal_preference']; // Assuming a field for meal preference is included in the form
    $delivery_address = $_POST['delivery_address'];
    $delivery_time = $_POST['delivery_time'];
    $delivery_days = $_POST['delivery_days']; // Comma-separated list of days
    $custom_meal_plan = $_POST['custom_meal_plan'];

    // Convert $allergies array to a string
    $allergies_string = implode(",", $allergies); // Converts array to string

    // Insert data into the database
    $sql = "INSERT INTO paidmeal1 (height, weight, age, gender, exercise, diet, allergies, pregnant, meal_plan_type, meal_preference, delivery_address, delivery_time, delivery_days, custom_meal_plan)
            VALUES ('$height', '$weight', '$age', '$gender', '$exercise', '$diet', '$allergies_string', '$pregnant', '$meal_plan_type', '$meal_preference', '$delivery_address', '$delivery_time', '$delivery_days', '$custom_meal_plan')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Record inserted successfully.');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }

    // Check if all required fields are provided
    if (!empty($height) && !empty($weight) && !empty($age) && !empty($gender)) {
        // Calculate BMI
        $bmi = round($weight / (($height / 100) ** 2), 1);

        // Calculate BMR (Basal Metabolic Rate)
        $bmr = ($gender === "male")
            ? 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age)
            : 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);

        // Adjust BMR for pregnancy
        if ($pregnant && $gender === "female") {
            $bmr += 300;
        }

        // Adjust for exercise level
        $activity_factor = match ($exercise) {
            'none' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'intense' => 1.725,
            default => 1.2,
        };

        // Calculate daily calorie needs
        $calorie_needs = round($bmr * $activity_factor);

        // AI meal suggestion
        $user_message = "Give me a personalized meal plan based on my BMI ($bmi) and daily calorie needs ($calorie_needs) for a $age-year-old $gender with a $diet diet and the following allergies: $allergies_string.";
        $ai_response = callAI($user_message);
        $meal_plan = "<p><strong>Best Advice with AI:</strong> " . $ai_response['choices'][0]['message']['content'] . "</p>";
        
        // Generate meal plan table
        $selected_meals = [
            'breakfast' => [
                'Oatmeal with berries and almond milk',
                'Scrambled eggs with spinach and avocado',
                'Whole wheat toast with peanut butter and banana',
                'Greek yogurt with granola and honey',
                'Smoothie with spinach, banana, and protein powder',
                'Chia seed pudding with coconut milk',
                'Avocado toast with poached egg'
            ],
            'lunch' => [
                'Grilled chicken salad with mixed greens',
                'Quinoa bowl with chickpeas, avocado, and veggies',
                'Lentil soup with whole grain bread',
                'Turkey and avocado wrap',
                'Tuna salad with olive oil and lemon dressing',
                'Grilled fish with steamed broccoli and rice',
                'Vegetable stir fry with tofu and rice noodles'
            ],
            'dinner' => [
                'Baked salmon with roasted sweet potatoes and asparagus',
                'Chicken breast with quinoa and steamed spinach',
                'Vegetarian chili with beans and cornbread',
                'Grilled shrimp with garlic and lemon pasta',
                'Stuffed bell peppers with rice and ground turkey',
                'Zucchini noodles with pesto and grilled chicken',
                'Beef stir fry with broccoli and brown rice'
            ],
            'snacks' => [
                'Apple slices with almond butter',
                'Mixed nuts (unsalted)',
                'Carrot sticks with hummus',
                'Greek yogurt with a handful of walnuts',
                'Protein bar (low sugar)',
                'Rice cakes with peanut butter and banana',
                'Cucumber slices with tzatziki sauce'
            ]
        ];

        $meal_plan .= "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>
                            <thead>
                                <tr>
                                    <th style='border: 1px solid #ddd; text-align: center;'>Meal Type</th>
                                    <th style='border: 1px solid #ddd; text-align: center;'>Day 1</th>
                                    <th style='border: 1px solid #ddd; text-align: center;'>Day 2</th>
                                    <th style='border: 1px solid #ddd; text-align: center;'>Day 3</th>
                                    <th style='border: 1px solid #ddd; text-align: center;'>Day 4</th>
                                    <th style='border: 1px solid #ddd; text-align: center;'>Day 5</th>
                                    <th style='border: 1px solid #ddd; text-align: center;'>Day 6</th>
                                    <th style='border: 1px solid #ddd; text-align: center;'>Day 7</th>
                                </tr>
                            </thead>
                            <tbody>";

        foreach (['breakfast', 'lunch', 'dinner', 'snacks'] as $meal_type) {
            $meal_plan .= "<tr style='border: 1px solid #ddd;'>
                            <td style='border: 1px solid #ddd; padding: 8px;'>$meal_type</td>";
            foreach ($selected_meals[$meal_type] as $meal) {
                $meal_plan .= "<td style='border: 1px solid #ddd; padding: 8px; text-align: center;'>$meal</td>";
            }
            $meal_plan .= "</tr>";
        }

        $meal_plan .= "</tbody></table>";
    }
}

$conn->close();
?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MealMorph - Your Meal Planning Partner">
    <meta name="author" content="MealMorph Team">
    <title>MealMorph - Personalized Meal Plan Generator</title>

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
        }
           /* Background */
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
         /* Navbar */
         .navbar {
            background-color: rgba(44, 62, 80, 0.95); /* Slightly darker transparent background */
            border-bottom: 3px solid var(--accent-color); /* Thicker bottom border for emphasis */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* More pronounced shadow */
            position: sticky; /* Stays at the top while scrolling */
            top: 0;
            z-index: 1000; /* Ensures the navbar stays on top */
            padding: 20px 0; /* Increased vertical padding */
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
    animation: cycleImages 10s infinite; /* Apply the animation */
}

@keyframes cycleImages {
    0% {
        background-image: url("images/slider/ella-olsson-rD3YrnhTmf0-unsplash.jpg");
    }
    25% {
        background-image: url("images/slider/c1.jpg");
    }
    50% {
        background-image: url("images/slider/c2.jpg");
    }
    75% {
        background-image: url("images/slider/c6.jpg");
    }
    100% {
        background-image: url("images/slider/ella-olsson-rD3YrnhTmf0-unsplash.jpg");
    }
}

    .hero h1 {
    font-size: 3rem; /* Large and bold for emphasis */
    font-weight: bold; /* Ensures text is bold */
    color: var(--accent-color); /* Accent color for visibility */
    font-family: 'Roboto', sans-serif; /* Clean and widely used font */
    letter-spacing: 1.5px; /* Slightly spaced out letters */
    text-transform: uppercase; /* Make text uppercase */
    text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.25); /* Soft shadow for contrast */
    margin-bottom: 20px; /* Adds space below the heading */
    opacity: 0; /* Initially hidden */
    transform: translateY(30px) scale(0.95); /* Start slightly lower and smaller */
    animation: enhancedFadeInSlideUp 2s ease-out forwards; /* Enhanced animation */
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

/* Hover effect for further emphasis */
.hero h1:hover {
    transform: scale(1.1); /* Slight enlargement */
    color: #e74c3c; /* Highlight color */
    text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.4); /* Enhanced shadow */
}

.hero p {
    font-size: 1.2rem; /* Slightly larger font size for better readability */
    font-family: 'Roboto', sans-serif; /* Consistent font family */
    color: var(--primary-color); /* Matches the primary theme color */
    margin-top: 20px; /* Adds a gap between the paragraph and the heading */
    line-height: 1.6; /* Improves line spacing for better readability */
    text-align: center; /* Centers the paragraph text */
}



form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 30px;
    border: 1px solid rgba(0, 0, 0, 0.2); /* Subtle semi-transparent border */
    border-radius: 15px;
    background: linear-gradient(135deg, #f9f9f9, #e9ecef); /* Gradient background */
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15); /* Enhanced shadow */
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    width: 90%; /* Default width for larger screens */
    max-width: 700px; /* Limit form's maximum width */
    margin: 20px auto; /* Centered form */
    backdrop-filter: blur(10px); /* Glass effect */
    position: relative;
}

form:hover {
    transform: scale(1.02); /* Slight scaling effect */
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.25); /* Stronger shadow on hover */
}

form::before {
    content: '';
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    z-index: -1;
    background: linear-gradient(135deg, rgba(24, 18, 96, 0.4), rgba(12, 41, 72, 0.3)); /* Gradient glow */
    filter: blur(15px);
    border-radius: 20px;
    transition: opacity 0.4s ease-in-out;
    opacity: 0;
}

form:hover::before {
    opacity: 1;
}

h3 {
    text-align: center;
    font-size: 2rem;
    color: #202124;
    margin-bottom: 25px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
}

label {
    font-weight: bold;
    color: #333;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
}

input, select {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.8); /* Semi-transparent input background */
    color: #202124;
    font-size: 15px;
    transition: border 0.3s ease, box-shadow 0.3s ease;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

input:focus, select:focus {
    border-color: rgb(24, 18, 96); /* Enhanced blue border */
    outline: none;
    box-shadow: 0 0 10px rgba(24, 18, 96, 0.4); /* Focus glow effect */
}

.btn-theme {
    background: linear-gradient(135deg, rgb(24, 18, 96), rgb(12, 41, 72)); /* Gradient button */
    color: #fff;
    padding: 12px 25px;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.3s, background 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3); /* Subtle text shadow */
}

.btn-theme:hover {
    transform: translateY(-3px); /* Lift effect */
    background: linear-gradient(135deg, rgb(15, 46, 79), rgb(24, 18, 96)); /* Reverse gradient on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
}

.button-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 30px;
}

@media (min-width: 1200px) {
    form {
        width: 60%; /* Adjust width for extra-large screens */
        max-width: 600px; /* Larger max width for bigger screens */
    }
}

@media (max-width: 768px) {
    form {
        padding: 20px;
        width: 95%; /* Adjust width for smaller devices */
        gap: 15px;
    }

    h3 {
        font-size: 1.6rem; /* Smaller heading on mobile */
    }

    .btn-theme {
        font-size: 14px;
        padding: 10px 20px;
    }
}

@media (max-width: 480px) {
    form {
        padding: 15px;
        gap: 10px;
    }

    h3 {
        font-size: 1.4rem;
    }

    label {
        font-size: 0.85rem;
    }

    input, select {
        padding: 10px;
        font-size: 13px;
    }

    .btn-theme {
        font-size: 13px;
        padding: 8px 16px;
    }
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
        margin-top: 15px;
    }
}
    </style>
</head>
<body>
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
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="My_profile.php">My Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Log in</a></li>
            </ul>
        </div>
    </div>
</nav>
    <div class="hero">
        <h1>Plan Your Meals with Ease</h1>
        <p>Personalized weekly meal plans tailored to your preferences</p>
    </div>
    <div class="container">
        <?php if ($form_submitted): ?>
            <h3>Your Results</h3>
            <p><strong>BMI:</strong> <?php echo $bmi; ?></p>
            <p><strong>Daily Calorie Needs:</strong> <?php echo $calorie_needs; ?> kcal</p>
            <?php echo $meal_plan; ?>
        <?php else: ?>
            
            <form method="post" action="">
    <h3>Enter Your Details</h3>

    <label for="height">Height (cm):</label>
    <input type="number" id="height" name="height" class="form-control" required>

    <label for="weight">Weight (kg):</label>
    <input type="number" id="weight" name="weight" class="form-control" required>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" class="form-control" required>

    <label for="gender">Gender:</label>
    <select id="gender" name="gender" class="form-select" required>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>

    <label for="diet">Dietary Preferences:</label>
    <input type="text" id="diet" name="diet" class="form-control" placeholder="E.g., Vegan, Keto">

    <label for="allergies">Allergies:</label>
    <input type="text" id="allergies" name="allergies" class="form-control" placeholder="E.g., Nuts, Gluten">

    <label for="exercise">Exercise Level:</label>
    <select id="exercise" name="exercise" class="form-select">
        <option value="none">None</option>
        <option value="light">Light</option>
        <option value="moderate">Moderate</option>
        <option value="intense">Intense</option>
    </select>

    <label for="pregnant">Pregnant:</label>
    <input type="checkbox" id="pregnant" name="pregnant" value="true" class="form-check-input">

    <label for="meal_plan_type">Meal Plan Type:</label>
    <input type="text" id="meal_plan_type" name="meal_plan_type" class="form-control" placeholder="E.g., Standard, Custom" required>

    <label for="meal_preference">Meal Preferences:</label>
    <textarea id="meal_preference" name="meal_preference" class="form-control" placeholder="E.g., Vegetarian, No Spicy" required></textarea>

    <label for="delivery_address">Delivery Address:</label>
    <input type="text" id="delivery_address" name="delivery_address" class="form-control" required>

    <label for="delivery_time">Delivery Time:</label>
    <input type="time" id="delivery_time" name="delivery_time" class="form-control" required>

    <label for="delivery_days">Delivery Days:</label>
    <input type="text" id="delivery_days" name="delivery_days" class="form-control" placeholder="E.g., Monday, Wednesday, Friday" required>

    <label for="custom_meal_plan">Custom Meal Plan (optional):</label>
    <textarea id="custom_meal_plan" name="custom_meal_plan" class="form-control" placeholder="Optional custom meal details"></textarea>

    <div class="button-container">
        <button type="submit" class="btn btn-theme mt-3">Generate Meal Plan</button>
    </div>
</form>

        <?php endif; ?>
    </div>
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

        <script src="script.js"></script>
</body>
</html>






