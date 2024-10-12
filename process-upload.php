<?php
session_start();
include 'db_connect.php'; // Include database connection

// Check if the farmer is logged in
if (!isset($_SESSION['farmer_name'])) {
    header("Location: farmer-login.php");
    exit();
}

// Fetch the farmer's name from the session
$farmer_name = $_SESSION['farmer_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $category = $_POST['category'];
    $name = $_POST['name'];
    $variety = $_POST['variety'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];

    // Handle file upload
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        // Insert data into database
        $sql = "INSERT INTO produce (farmer_name, category, name, variety, quantity, price, address, mobile, photo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssddsss", $farmer_name, $category, $name, $variety, $quantity, $price, $address, $mobile, $photo);

        if ($stmt->execute()) {
            echo "Produce uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $conn->close();
    header("Location: inventory.php"); // Redirect to inventory page after upload
    exit();
}
?>
