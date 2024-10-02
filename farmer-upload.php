<?php
session_start();
include 'db_connect.php';

// Check if the farmer is logged in
if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer-login.php");
    exit();
}

// Fetch farmer's name using their ID
$farmer_id = $_SESSION['farmer_id'];
$sql = "SELECT name FROM farmers WHERE farmer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $farmer_id); // Bind the farmer ID
$stmt->execute();
$stmt->bind_result($farmer_name);
$stmt->fetch();
$stmt->close();

$uploadOk = 1; // Initialize upload status

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $category = $_POST['category'];
    $name = $_POST['name']; 
    $variety = $_POST['variety'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $address = $_POST['address'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["photo"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            // Prepare SQL query to insert produce details
            $sql = "INSERT INTO produce (farmer_id, category, name, variety, quantity, price, address, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issidsis", $farmer_id, $category, $name, $variety, $quantity, $price, $address, $target_file);

            if ($stmt->execute()) {
                echo "Produce uploaded successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Produce - GreenHarvest</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Function to preview image before uploading
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block'; // Show the preview image
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body>
    <header>
        <h1>Upload Produce</h1>
        <h2>Welcome, <?php echo htmlspecialchars($farmer_name); ?></h2> <!-- Display farmer's name -->
    </header>

    <main>
        <section class="upload-form">
            <form action="farmer-upload.php" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="category">Category:</label>
                    <select id="category" name="category" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="Fruit">Fruit</option>
                        <option value="Vegetable">Vegetable</option>
                        <option value="Flower">Flower</option>
                    </select>
                </div>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="variety">Variety:</label>
                    <input type="text" id="variety" name="variety" required>
                </div>
                <div>
                    <label for="quantity">Quantity (in KG, ton, quintal):</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                <div>
                    <label for="price">Price (in Rupees):</label>
                    <input type="number" id="price" name="price" required>
                </div>
                <div>
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <div>
                    <label for="photo">Photo:</label>
                    <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(event)" required>
                    <img id="imagePreview" width="100" height="100" alt="Image preview" style="display: none;">
                </div>
                <button type="submit">Upload</button>
            </form>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $uploadOk == 1): ?>
            <div class="produce-box">
                <img src="<?php echo htmlspecialchars($target_file); ?>" alt="Produce photo" width="100" height="100">
                <div class="produce-details">
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($category); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                    <p><strong>Variety:</strong> <?php echo htmlspecialchars($variety); ?></p>
                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($quantity); ?></p>
                    <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($price); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
