<?php
include 'database.php';
$imageErr = "";
// Check if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch product details from the database based on the ID
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        // Product not found, handle error
        echo "Product not found.";
        exit;
    }
} else {
    // ID not provided, handle error
    echo "Invalid product ID.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated product details from the form
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    //image
    // Only process image if a new file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Your existing image processing code
        $image = $newfilename . "." . $file_ext; // Prepare image filename for database update
    } else {
        // No new image uploaded, use existing image from the database
        $image = $product['image'];
    }

    // Update product details in the database, including the image only if a new one is provided
    $sql = "UPDATE products SET title = ?, category_id = ?, description = ?, price = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisdsi", $title, $category_id, $description, $price, $image, $id);
    $stmt->execute();

    // Redirect back to product list page after updating
    header("Location: product_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Product</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Update Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($product['title']); ?>">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category ID</label>
                <input type="number" class="form-control" id="category_id" name="category_id" value="<?php echo htmlspecialchars($product['category_id']); ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
            </div>
            <div class="form-group">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>">
                <?php if (!empty($product['image'])) : ?>
                    <img src="images/<?php echo $product['image']; ?>" width="100"><br>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
</body>

</html>