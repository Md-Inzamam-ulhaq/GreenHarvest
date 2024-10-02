<?php
// Include the database connection file
include 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $full_name = $_POST['full_name'];
    $mobile_number = $_POST['mobile_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];

    // Prepare SQL query to insert new farmer
    $sql = "INSERT INTO farmers (full_name, mobile_number, password, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Check if the statement was prepared successfully
    if ($stmt) {
        $stmt->bind_param("ssss", $full_name, $mobile_number, $password, $address);

        // Execute the query and handle potential errors
        if ($stmt->execute()) {
            // Redirect to farmer login page after successful registration
            header("Location: farmer-login.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    } else {
        $error = "Error preparing statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registration - GreenHarvest</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Include your CSS styles here */
    </style>
</head>
<body>
    <header>
        <h1>Farmer Registration</h1>
    </header>

    <main>
        <section class="registration-container">
            <form action="farmer-register.php" method="POST">
                <div>
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                <div>
                    <label for="mobile_number">Mobile Number:</label>
                    <input type="text" id="mobile_number" name="mobile_number" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="farmer-login.php">Login here</a></p>
        </section>
    </main>

     
</body>
</html>
