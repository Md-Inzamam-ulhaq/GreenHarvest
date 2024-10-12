<?php
// Include the database connection file
include 'db_connect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact_number = $_POST['contact_number'];
    $password = $_POST['password'];

    // Prepare SQL query to check user's credentials
    $sql = "SELECT * FROM users WHERE name = ? AND contact_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $contact_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            
            // Redirect to user dashboard
            header("Location: user_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that name and contact number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - GreenHarvest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center; /* Center align text */
            overflow: hidden; /* Prevent scrolling */
        }

        header {
            background-color: rgba(76, 175, 80, 0.9); /* Green with transparency */
            color: white;
            padding: 10px 0;
            position: relative; /* Allows positioning of the header */
            z-index: 2; /* Ensure header is above the image */
        }

        .full-screen-image {
            position: fixed; /* Fixed positioning for full screen */
            top: 60px; /* Starts below the header */
            left: 0;
            width: 100%; /* Full width */
            height: calc(100% - 60px); /* Full height minus header height */
            object-fit: cover; /* Cover the entire area */
            opacity: 0.5; /* 50% transparency */
            z-index: 1; /* Ensure image is below other elements */
        }

        .login-form {
            position: absolute; /* Position form over the image */
            top: 50%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Adjust for the form size */
            background-color: white; /* White background for form */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
            z-index: 2; /* Ensure form is on top */
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s; /* Smooth transitions */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Initial shadow effect */
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.1); /* Scale effect on hover */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Shadow effect on hover */
        }

        .error {
            color: red;
            margin-bottom: 15px; /* Space below error message */
        }

        p {
            margin-top: 15px; /* Space above paragraph */
        }
    </style>
</head>
<body>
    <header>
        <h1>User Login</h1>
        <p>Login to explore fresh produce directly from farmers</p>
    </header>

    <img src="7718435.jpg" alt="Fresh farm produce" class="full-screen-image">

    <main>
        <section class="login-form">
            <form action="user-login.php" method="POST">
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" id="contact_number" name="contact_number" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="user_register.php">Register here</a></p>
        </section>
    </main>
</body>
</html>
