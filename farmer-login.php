<?php
session_start();
include 'db_connect.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $password = $_POST['password'];

    // Prepare SQL query to select farmer
    $sql = "SELECT * FROM farmers WHERE full_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $full_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $farmer = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $farmer['password'])) {
            // Set session variables
            $_SESSION['farmer_id'] = $farmer['id'];
            $_SESSION['farmer_name'] = $farmer['full_name'];
            header("Location: farmer-upload.php"); // Redirect to upload page
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No farmer found with that name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Login - GreenHarvest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center; /* Center align text */
            overflow: hidden; /* Prevents scrolling */
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
            transition: opacity 0.5s ease-in-out; /* Smooth opacity transition */
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
        <h1>Farmer Login</h1>
        <p>Access your account to upload and manage your produce</p>
    </header>

    <img src="7718435.jpg" alt="Fresh farm produce" class="full-screen-image">

    <main>
        <section class="login-form">
            <form action="farmer-login.php" method="POST">
                <div>
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required>
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
            <p>Don't have an account? <a href="farmer-register.php">Register here</a></p>
        </section>
    </main>
</body>
</html>
