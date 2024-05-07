<?php
include 'database.php'; // Include your database connection

$query = "SELECT products.id, products.title, categories.name AS category_name, products.description, products.price, products.image 
FROM products 
JOIN categories ON products.category_id = categories.id"; // Join to get category names
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2>Product List</h2>
            <a href="product_create.php" class="btn btn-primary">Add New Product</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th class="text-center">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row) : ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td>
                            <?php if (!empty($row['image'])) : ?>
                                <img src="./images/<?php echo htmlspecialchars($row['image']); ?>" width="100" height="100" alt="Product Image">
                            <?php else : ?>
                                No Image Available
                            <?php endif; ?>
                        </td>
                        <!-- Update Button -->
                        <td>
                            <a href="product_update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Update</a>

                        </td>
                        <!-- Delete Button -->
                        <td>
                            <form action="product_delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>