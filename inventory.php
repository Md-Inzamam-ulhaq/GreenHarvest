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
$sql = "SELECT id, farmer_name, category, name, variety, quantity, price, address, mobile, photo FROM produce ORDER BY created_at DESC"; 
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
            width: 300px; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
        }
        .produce-card img {
            max-width: 100%;
            height: 200px; 
            object-fit: cover; 
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
            background-color: #FFA500; 
        }
        .delete-button {
            background-color: #f44336; 
        }
        .button:hover {
            filter: brightness(90%);
        }
        .save-button {
            background-color: #4CAF50; 
            border: none;
            color: white;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .cancel-button {
            background-color: #f44336; 
            border: none;
            color: white;
            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .save-button:hover {
            background-color: #45a049; 
            transform: scale(1.05); 
        }
        .cancel-button:hover {
            background-color: #e53935; 
            transform: scale(1.05); 
        }
        .edit-fields {
            display: none; /* Initially hidden */
        }
    </style>
    <script>
        function editItem(id) {
            // Show edit fields
            document.getElementById("edit-fields-" + id).style.display = "block";
            // Hide other elements as needed
        }

        function saveItem(id) {
            // Get values from input fields
            const name = document.getElementById("edit-name-" + id).value;
            const category = document.getElementById("edit-category-" + id).value;
            const variety = document.getElementById("edit-variety-" + id).value;
            const quantity = document.getElementById("edit-quantity-" + id).value;
            const price = document.getElementById("edit-price-" + id).value;
            const address = document.getElementById("edit-address-" + id).value;

            // Make an AJAX request to update the item
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_item.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert("Item updated successfully!");
                    location.reload(); // Reload page to see changes
                }
            };
            xhr.send("id=" + id + "&name=" + name + "&category=" + category + "&variety=" + variety + "&quantity=" + quantity + "&price=" + price + "&address=" + address);
        }

        function deleteItem(id) {
            if (confirm("Are you sure you want to delete this item?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_item.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        alert("Item deleted successfully!");
                        location.reload(); // Reload page to see changes
                    }
                };
                xhr.send("id=" + id);
            }
        }

        function cancelEdit(id) {
            document.getElementById("edit-fields-" + id).style.display = "none";
        }
    </script>
</head>
<body>

<h1 style="text-align: center;">Inventory</h1>

<div class="inventory-container">
    <?php
    if ($result->num_rows > 0) {
        // Output data for each row
        while($row = $result->fetch_assoc()) {
            echo '<div class="produce-card">';
            echo '<img src="' . $row["photo"] . '" alt="Produce Image">';
            echo '<div class="produce-details">';
            echo '<h3>' . $row["name"] . '</h3>';
            echo '<p><strong>Category:</strong> ' . $row["category"] . '</p>';
            echo '<p><strong>Variety:</strong> ' . $row["variety"] . '</p>';
            echo '<p><strong>Quantity:</strong> ' . $row["quantity"] . ' KG</p>';
            echo '<p><strong>Price:</strong> Rs. ' . $row["price"] . '</p>';
            echo '<p><strong>Farmer:</strong> ' . $row["farmer_name"] . '</p>';
            echo '<p><strong>Address:</strong> ' . $row["address"] . '</p>';
            echo '</div>';
            echo '<button class="button edit-button" onclick="editItem(' . $row["id"] . ')">Edit</button>'; 
            echo '<button class="button delete-button" onclick="deleteItem(' . $row["id"] . ')">Delete</button>';
            echo '<div class="edit-fields" id="edit-fields-' . $row["id"] . '">';
            echo '<input type="text" id="edit-name-' . $row["id"] . '" value="' . $row["name"] . '">';
            echo '<input type="text" id="edit-category-' . $row["id"] . '" value="' . $row["category"] . '">';
            echo '<input type="text" id="edit-variety-' . $row["id"] . '" value="' . $row["variety"] . '">';
            echo '<input type="number" id="edit-quantity-' . $row["id"] . '" value="' . $row["quantity"] . '">';
            echo '<input type="number" id="edit-price-' . $row["id"] . '" value="' . $row["price"] . '">';
            echo '<input type="text" id="edit-address-' . $row["id"] . '" value="' . $row["address"] . '">';
            echo '<button class="button save-button" onclick="saveItem(' . $row["id"] . ')">Save</button>';
            echo '<button class="button cancel-button" onclick="cancelEdit(' . $row["id"] . ')">Cancel</button>';
            echo '</div>'; 
            echo '</div>'; 
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
