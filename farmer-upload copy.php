<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Produce - GreenHarvest</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow-y: auto;
        }

        header {
            background-color: rgba(76, 175, 80, 0.9); /* Green with transparency */
            color: white;
            padding: 10px 0;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .full-screen-image {
            position: fixed;
            top: 60px;
            left: 0;
            width: 100%;
            height: calc(100% - 60px);
            object-fit: cover;
            opacity: 0.5;
            z-index: 1;
            transition: opacity 0.5s ease-in-out;
        }

        main {
            position: relative;
            z-index: 3;
            padding-top: 80px;
        }

        .upload-form {
            position: relative;
            margin: 0 auto;
            top: 10px; /* Increased from 50px */
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%; /* Increased from 80% */
            max-width: 800px; /* Increased from 600px */
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="tel"],
        input[type="file"],
        select,
        textarea {
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
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        #imagePreview {
            margin-top: 10px;
            display: none;
            border: 1px solid #ccc; /* Border for the image preview */
            max-width: 100px; /* Maximum width for the preview */
        }

        .success-message {
            color: green;
            text-align: center;
            margin-top: 20px;
        }
        #heading{
            text-align: center;
        }
        /* Reset default styles for the header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #4CAF50; /* Green background */
    padding: 10px 20px; /* Spacing around the header */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
}

/* Style for the navigation links */
nav {
    display: flex;
    gap: 20px; /* Space between links */
}

nav a {
    color: white; /* White text color */
    text-decoration: none; /* Remove underline */
    font-weight: bold; /* Bold text */
    transition: color 0.3s; /* Smooth transition for hover effect */
}

/* Change text color on hover */
nav a:hover {
    color: #ffeb3b; /* Yellow color on hover */
}

/* Style for the logout button */
  

    </style>
    
</head>
<body>
    
    
<header>
    <nav>
        <a href="farmer-upload.php">Home</a>
        <a href="inventory.php">Inventory</a>
    </nav>

    
    </form>
</header>


     <div id="heading">
        
     <h1>Upload Produce</h1>
        <h2>Welcome, Farmer</h2>
     </div>

    <img src="7718435.jpg" alt="Fresh farm produce" class="full-screen-image">

    <main>
        <section class="upload-form">
            <form id="uploadForm" action="" method="POST" enctype="multipart/form-data" onsubmit="handleSubmit(event)">
                <div>
                    <label for="farmer_name">Farmer Name:</label>
                    <input type="text" id="farmer_name" name="farmer_name" required>
                </div>
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
                    <label for="quantity">Quantity (in KG):</label>
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
                    <label for="mobile">Mobile Number:</label>
                    <input type="tel" id="mobile" name="mobile" required>
                </div>
                <div>
                    <label for="photo">Photo:</label>
                    <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(event)" required>
                    <img id="imagePreview" width="100" height="100" alt="Image preview">
                </div>
                <button type="submit">Upload</button>
            </form>

            <!-- Display success message if set -->
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Database connection
                $servername = "localhost"; // Your server name
                $username = "root"; // Your MySQL username
                $password = ""; // Your MySQL password
                $dbname = "greenharvest"; // Your database name
                
                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Define the target directory for uploaded files
                $targetDir = "uploads/";
                $uniqueFileName = uniqid() . '_' . basename($_FILES["photo"]["name"]);
                $targetFile = $targetDir . $uniqueFileName;
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                // Check if image file is a real image or a fake one
                $check = getimagesize($_FILES["photo"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo "<script>alert('File is not an image.');</script>";
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($targetFile)) {
                    echo "<script>alert('Sorry, file already exists.');</script>";
                    $uploadOk = 0;
                }

                // Check file size (limit to 5MB)
                if ($_FILES["photo"]["size"] > 5000000) {
                    echo "<script>alert('Sorry, your file is too large.');</script>";
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
                    $uploadOk = 0;
                }

                // Check if everything is ok before uploading
                if ($uploadOk == 0) {
                    echo "<script>alert('Sorry, your file was not uploaded.');</script>";
                } else {
                    // If everything is ok, try to upload the file
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
                        // Prepare SQL statement to insert data
                        $stmt = $conn->prepare("INSERT INTO produce (farmer_name, category, name, variety, quantity, price, address, mobile, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssidsss", $_POST['farmer_name'], $_POST['category'], $_POST['name'], $_POST['variety'], $_POST['quantity'], $_POST['price'], $_POST['address'], $_POST['mobile'], $targetFile);

                        // Execute the statement and provide feedback
                        if ($stmt->execute()) {
                            $successMessage = "The file " . htmlspecialchars($uniqueFileName) . " has been uploaded successfully.";
                        } else {
                            echo "<script>alert('There was an error uploading your file.');</script>";
                        }
                    } else {
                        echo "<script>alert('There was an error moving your uploaded file.');</script>";
                    }

                    $stmt->close();
                }

                $conn->close();
            }
            ?>

            <!-- Display success message if set -->
            <?php if (isset($successMessage)): ?>
                <div class="success-message">
                    <strong><?php echo $successMessage; ?></strong>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.src = URL.createObjectURL(event.target.files[0]);
            imagePreview.style.display = 'block';
        }

        function handleSubmit(event) {
           
        }
    </script>
</body>
</html>
