<?php
// Database connection
include 'db_connect.php'; // Make sure this file contains the database connection logic

// Fetch produce from the database
$sql = "SELECT * FROM produce";
$result = $conn->query($sql);
$produceData = [];

if ($result->num_rows > 0) {
    // Fetch all produce into an array
    while ($row = $result->fetch_assoc()) {
        $produceData[] = $row;
    }
} else {
    echo "No produce available.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - GreenHarvest</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Available Produce</h1>
    </header>

    <main>
        <section class="produce-list">
            <?php foreach ($produceData as $item): ?>
                <div class="item">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <img src="<?php echo htmlspecialchars($item['photo']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?> Image" width="100" height="100">
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
                    <p><strong>Variety:</strong> <?php echo htmlspecialchars($item['variety']); ?></p>
                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity']); ?> KG</p>
                    <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($item['price']); ?> Rupees</p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($item['address']); ?></p>
                    <button onclick="buyNow(<?php echo $item['id']; ?>)">Buy Now</button>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

    <script>
        // Function to handle the purchase action
        function buyNow(itemId) {
            const itemElement = document.querySelector(`.item:nth-child(${itemId})`);
            const itemName = itemElement.querySelector('h3').innerText;
            const itemPrice = itemElement.querySelector('p:nth-of-type(4)').innerText;

            alert(`You have chosen to buy ${itemName} for ${itemPrice}.`);
            // Implement actual purchase logic here (e.g., redirect to payment page)
        }
    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        header {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
        }

        .produce-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 10px;
            width: calc(30% - 40px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .item img {
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .item {
                width: calc(45% - 40px);
            }
        }

        @media (max-width: 480px) {
            .item {
                width: calc(100% - 40px);
            }
        }
    </style>
</body>
</html>
