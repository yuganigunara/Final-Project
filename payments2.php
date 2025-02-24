<?php
// Database connection details
$servername = "localhost";  // Replace with your server
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "final";          // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data from form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // SQL query to insert payment data into the database
    $sql = "INSERT INTO payments2(name, email, card_number, expiry_date, cvv) 
            VALUES ('$name', '$email', '$card_number', '$expiry_date', '$cvv')";

    if ($conn->query($sql) === TRUE) {
        // Payment successful, display modal and redirect
        echo "<script>
                window.onload = function() {
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    setTimeout(function() {
                        window.location.href = 'paidmeal2.php'; // Redirect after 3 seconds
                    }, 3000); // Delay for 3 seconds
                };
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons for the icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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
        color: var(--primary-color);
        animation: gradientShift 10s ease-in-out infinite;
        margin: 0;
        padding: 20px 0;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .container {
        max-width: 500px;
        background-color: var(--light-color);
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }

    h1 {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-color);
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .form-container {
        background: linear-gradient(135deg, #f5f7fa, #e6e9f0); /* Subtle gradient */
        padding: 30px;
        border-radius: 15px;
        max-width: 550px; /* Wider width */
        margin: 0 auto; /* Center align */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: bold;
        color: #555;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        width: 100%;
        transition: border-color 0.3s ease-in-out;
        background: rgba(255, 255, 255, 0.85); /* Slightly transparent input background */
    }

    .form-control:focus {
        border-color:rgb(8, 103, 205);
        box-shadow: 0 0 5px rgba(1, 85, 175, 0.5);
        outline: none;
    }

    .btn-primary {
        background: linear-gradient(135deg,rgb(4, 101, 205),rgb(3, 62, 124));
        color: #fff;
        padding: 12px 25px;
        border: none;
        border-radius: 25px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0056b3, #003f7f);
        transform: translateY(-2px);
    }

    /* Mobile-Friendly Styling */
    @media (max-width: 576px) {
        .form-container {
            padding: 20px;
            margin: 10px;
        }

        .form-control {
            font-size: 14px;
        }

        .btn-primary {
            padding: 10px 20px;
            font-size: 14px;
        }
    }

    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Complete Your Payment</h1>
    <form id="paymentForm" method="POST" action="payments2.php" class="mt-4 form-container">
        <!-- Plan Details (Hidden Fields) -->
        <input type="hidden" name="plan" value="Standard Plan">
        <input type="hidden" name="price" value="$49">

        <!-- User Details -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
        </div>

        <!-- Payment Details -->
        <div class="mb-3">
            <label for="card_number" class="form-label">Card Number</label>
            <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" required>
        </div>
        <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
        </div>
        <div class="mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="password" class="form-control" id="cvv" name="cvv" placeholder="***" maxlength="4" required>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" class="btn-primary">Complete Payment</button>
        </div>
    </form>
</div>


    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Payment Successful!</h5>
                    <p>Your payment has been processed successfully.</p>
                    <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





