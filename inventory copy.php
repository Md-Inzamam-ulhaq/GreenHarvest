<?php  
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "greenharvest"; // Change to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all produce items from the database
$sql = "SELECT id, farmer_name, category, name, variety, quantity, price, address, mobile, photo FROM produce ORDER BY created_at DESC"; // Added id field for editing and deleting
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - GreenHarvest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .inventory-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }
        .produce-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            width: 300px; /* Fixed width for uniform card size */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex; /* Use flexbox for vertical alignment */
            flex-direction: column; /* Stack elements vertically */
            justify-content: space-between; /* Space between elements */
        }
        .produce-card img {
            max-width: 100%;
            height: 200px; /* Fixed height for uniform images */
            object-fit: cover; /* Maintain aspect ratio */
            border-radius: 5px;
        }
        .produce-details {
            margin-top: 15px;
        }
        .produce-details h3 {
            margin: 0;
            color: #4CAF50;
        }
        .produce-details p {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .edit-button {
            background-color: #FFA500; /* Orange color for edit button */
        }
        .delete-button {
            background-color: #f44336; /* Red color for delete button */
        }
        .button:hover {
            filter: brightness(90%);
        }
    </style>
</head>
<body>

<h1 style="text-align: center;">Inventory</h1>

<div class="inventory-container">
    <?php
    if ($result->num_rows > 0) {
        // Output data for each row
        while($row = $result->fetch_assoc()) {
            echo '<div class="produce-card">'; // Use produce-card class for styling
            echo '<img src="' . $row["photo"] . '" alt="Goods Image">';
            echo '<div class="produce-details">'; // Group details together
            echo '<h3>' . $row["name"] . '</h3>';
            echo '<p><strong>Category:</strong> ' . $row["category"] . '</p>';
            echo '<p><strong>Variety:</strong> ' . $row["variety"] . '</p>';
            echo '<p><strong>Quantity:</strong> ' . $row["quantity"] . ' KG</p>';
            echo '<p><strong>Price:</strong> Rs. ' . $row["price"] . '</p>';
            echo '<p><strong>Farmer:</strong> ' . $row["farmer_name"] . '</p>';
            echo '<p><strong>Address:</strong> ' . $row["address"] . '</p>'; // Added address
            echo '</div>'; // Close produce-details
            echo '<button class="button edit-button" onclick="editItem(' . $row["id"] . ')">Edit</button>'; // Edit button
            echo '<button class="button delete-button" onclick="deleteItem(' . $row["id"] . ')">Delete</button>'; // Delete button
            echo '</div>'; // Close produce-card
        }
    } else {
        echo "<p>No goods available.</p>";
    }
    ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
