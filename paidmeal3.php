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

// Initialize variables with default values
$form_submitted = false;
$meal_plans = [];
$bmi = null; // Default value
$calorie_needs = null; // Default value

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $form_submitted = true;

    // Retrieve form inputs with validation, providing default values if necessary
    $members = isset($_POST['members']) ? intval($_POST['members']) : 1;
    $diet = isset($_POST['diet']) ? $_POST['diet'] : 'vegetarian';  // Default to vegetarian if not set
    $allergies = isset($_POST['allergies']) ? $_POST['allergies'] : [];
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $delivery_time = isset($_POST['delivery_time']) ? $_POST['delivery_time'] : '';
    $delivery_days = isset($_POST['delivery_days']) ? $_POST['delivery_days'] : '';

    // Convert allergies array to string (empty or selected items)
    $allergies_string = !empty($allergies) ? implode(", ", $allergies) : "None"; // Convert array to string

    for ($i = 1; $i <= $members; $i++) {
        $name = isset($_POST["member{$i}_name"]) ? $_POST["member{$i}_name"] : '';
        $age = isset($_POST["member{$i}_age"]) ? intval($_POST["member{$i}_age"]) : 0;
        $gender = isset($_POST["member{$i}_gender"]) ? $_POST["member{$i}_gender"] : '';
        $height = isset($_POST["member{$i}_height"]) ? floatval($_POST["member{$i}_height"]) : 0;
        $weight = isset($_POST["member{$i}_weight"]) ? floatval($_POST["member{$i}_weight"]) : 0;
        $activity_level = isset($_POST["member{$i}_activity_level"]) ? $_POST["member{$i}_activity_level"] : 'sedentary';

        // BMI Calculation
        $bmi = ($height > 0) ? round($weight / (($height / 100) ** 2), 1) : 0;

        // BMR Calculation
        if ($gender === "male") {
            $bmr = 66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
        } else {
            $bmr = 655 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
        }

        // Activity level adjustment
        $activity_factors = [
            'sedentary' => 1.2,
            'lightly_active' => 1.375,
            'moderately_active' => 1.55,
            'very_active' => 1.725,
            'super_active' => 1.9,
        ];
        $activity_factor = $activity_factors[$activity_level] ?? 1.2;
        $calorie_needs = round($bmr * $activity_factor);

        // Generate meal plan
        $meal_plan = generateMealPlan($diet, $calorie_needs);
        $meal_plans[$name] = ['bmi' => $bmi, 'calorie_needs' => $calorie_needs, 'meal_plan' => $meal_plan];

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO paidmeal3 (name, age, gender, height, weight, activity_level, diet, allergies, address, delivery_time, delivery_days, meal_plan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssssssss", $name, $age, $gender, $height, $weight, $activity_level, $diet, $allergies_string, $address, $delivery_time, $delivery_days, $meal_plan);

        if (!$stmt->execute()) {
            echo "<script>alert('Error: {$stmt->error}');</script>";
        }
    }
    echo "<script>alert('Records inserted successfully.');</script>";
}
$conn->close();

// Function to generate meal plan
function generateMealPlan($diet, $calorie_needs) {
    $meal_plans = [
        'vegetarian' => ['Breakfast' => 'Veggie Scramble', 'Lunch' => 'Lentil Salad', 'Dinner' => 'Vegetable Stir-fry', 'Snack' => 'Hummus'],
        'non-vegetarian' => ['Breakfast' => 'Scrambled Eggs', 'Lunch' => 'Chicken Salad', 'Dinner' => 'Grilled Chicken', 'Snack' => 'Greek Yogurt'],
        'gluten-free' => ['Breakfast' => 'GF Pancakes', 'Lunch' => 'Quinoa Salad', 'Dinner' => 'Grilled Fish', 'Snack' => 'Rice Cakes'],
        'low-calorie' => ['Breakfast' => 'Smoothie', 'Lunch' => 'Grilled Salad', 'Dinner' => 'Zucchini Noodles', 'Snack' => 'Almonds'],
    ];
    return $meal_plans[$diet] ?? [];
}

if (!empty($meal_plans)) {
    end($meal_plans);
    $last_member = key($meal_plans);
    $bmi = $meal_plans[$last_member]['bmi'];
    $calorie_needs = $meal_plans[$last_member]['calorie_needs'];

    $user_message = "Provide a meal plan for a $age-year-old $gender with BMI ($bmi) and daily calorie needs ($calorie_needs), $diet diet, allergies: $allergies_string.";
    $ai_response = callAI($user_message);
    
    if (isset($ai_response['choices'][0]['message']['content'])) {
        echo "<p><strong>AI Advice:</strong> " . htmlspecialchars($ai_response['choices'][0]['message']['content']) . "</p>";
    } else {
        echo "<p><strong>AI Advice:</strong> Unable to retrieve AI response.</p>";
    }
}

?>






<<!DOCTYPE html>
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

        /* Hero Section */
        .hero {
            background: url("images/slider/ella-olsson-rD3YrnhTmf0-unsplash.jpg") no-repeat center center;
            background-size: cover;
            text-align: center;
            padding: 80px 0;
            color: #ffffff; /* Ensure the text is readable on the background */
            margin-bottom: 40px;
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

        @keyframes enhancedFadeInSlideUp {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Meal Plan Form */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 30px;
            border-radius: 15px;
            background: linear-gradient(135deg, #f9f9f9, #e9ecef);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
            width: 80%;
            max-width: 700px;
            margin: 20px auto;
            backdrop-filter: blur(10px);
            position: relative;
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
        }

        input, select, textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
            color: #202124;
            font-size: 15px;
            width: 100%;
        }

        .btn-theme {
            background: linear-gradient(135deg, rgb(24, 18, 96), rgb(12, 41, 72));
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .footer {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            text-align: center;
            padding: 20px 10px;
            font-size: 0.9rem;
            margin-top: 20px;
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
        <table border="1" style="width: 100%; text-align: left;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>BMI</th>
                    <th>Calorie Needs</th>
                    <th>Weekly Meal Plan</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($meal_plans as $name => $details): ?>
            <tr>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($details['bmi']) ?></td>
                <td><?= htmlspecialchars($details['calorie_needs']) ?></td>
                <td>
                    <?php if (isset($details['weekly_plan'])): ?>
                        <?= htmlspecialchars($details['weekly_plan']) ?>
                    <?php else: ?>
                        <em>No plan available</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <form method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="height">Height (cm)</label>
            <input type="number" id="height" name="height" required>

            <label for="weight">Weight (kg)</label>
            <input type="number" id="weight" name="weight" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <button type="submit" class="btn-theme">Submit</button>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

 <script src="script.js"></script>
</html>
