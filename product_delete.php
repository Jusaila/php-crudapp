<?php
include 'database.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $conn->real_escape_string($_POST['id']);

    // SQL to delete a record
    $sql = "DELETE FROM products WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("location: product_list.php");
            exit();
        } else {
            echo "Error executing query.";
        }
        $stmt->close();
    } else {
        echo "Error preparing query.";
    }
    $conn->close();
} else {
    // Redirect to product list if the method is not POST
    header("location: product_list.php");
    exit();
}
