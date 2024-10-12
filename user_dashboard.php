<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "greenharvest";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch produce data
$sql = "SELECT id, farmer_name, category, name, variety, quantity, price, photo FROM produce";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - GreenHarvest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: rgba(76, 175, 80, 0.9); /* Green with transparency */
            color: white;
            text-align: center;
            padding: 15px 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .produce-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            width: 300px; /* Fixed width for uniform card size */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column; /* Stack elements vertically */
            justify-content: space-between; /* Space between elements */
            background-color: white;
        }
        .produce-card img {
            max-width: 100%;
            height: 200px; /* Fixed height for uniform images */
            object-fit: cover; /* Maintain aspect ratio */
            border-radius: 5px;
        }
        .produce-card h2, .produce-card p {
            margin: 10px 0;
        }
        .button {
            background-color: #4CAF50; /* Green button */
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>

<header>
    <h1>User Dashboard</h1>
    <h2>Browse Goods and Buy</h2>
</header>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        // Output data for each row
        while($row = $result->fetch_assoc()) {
            echo '<div class="produce-card">'; // Use produce-card class for styling
            echo '<img src="' . $row["photo"] . '" alt="Produce Image">';
            echo '<h2>' . $row["name"] . '</h2>';
            echo '<p><strong>Category:</strong> ' . $row["category"] . '</p>';
            echo '<p><strong>Variety:</strong> ' . $row["variety"] . '</p>';
            echo '<p><strong>Quantity:</strong> ' . $row["quantity"] . ' KG</p>';
            echo '<p><strong>Price:</strong> Rs. ' . $row["price"] . '</p>';
            echo '<p><strong>Farmer:</strong> ' . $row["farmer_name"] . '</p>';
            echo '<button class="button" onclick="buyNow(' . $row["id"] . ')">Buy Now</button>'; // Updated button class
            echo '</div>'; // Close produce-card
        }
    } else {
        echo "<p>No goods available.</p>";
    }
    $conn->close();
    ?>
</div>

<script>
function buyNow(produceId) {
    // Redirect to buy page or handle the purchase logic here
    window.location.href = "buy.php?produce_id=" + produceId;
}
</script>

</body>
</html>
