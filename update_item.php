<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greenharvest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $variety = $_POST['variety'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $address = $_POST['address'];

    $sql = "UPDATE produce SET name=?, category=?, variety=?, quantity=?, price=?, address=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdisi", $name, $category, $variety, $quantity, $price, $address, $id);

    if ($stmt->execute()) {
        echo "Item updated successfully!";
    } else {
        echo "Error updating item: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
