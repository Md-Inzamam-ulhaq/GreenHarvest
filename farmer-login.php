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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Farmer Login</h1>
        <p>Access your account to upload and manage your produce</p>
    </header>

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
