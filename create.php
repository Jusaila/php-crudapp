<?php
include "connection.php";

//errors initialisation
$title = $category_id = $description = $price = $image = "";
$titleErr = $categoryErr = $descriptionErr = $priceErr = $imageErr = "";

//fetch data fron coloum named categories

$categortquery = "SELECT id, name FROM categories";
$result = $conn->query($categortquery);
$categories = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUIST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $category_id = trim($_POST["category_id"]);
    $description = trim($_POST["description"]);
    $price = trim($_POST["price"]);

    if (empty($title)) {
        $titleErr = "Title is required";
    } else {
        if (strlen($title) < 3) {
            $titleErr = "Title is atleast 3 characters";
        }
    }

    if (empty($category_id)) {
        $categoryErr = "category is required";
    }

    if (empty($description)) {
        $descriptionErr = "Description is required";
    } else {
        if (strlen($description) < 5) {
            $descriptionErr = "Title is atleast 5 characters";
        }
    }

    if (empty($price)) {
        $priceErr = "Title is required";
    } else {
        if (!is_numeric($price)) {
            $priceErr = "Valid Password is Required";
        }
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $filename = $_FILES['image']['name'];
        $filesize = $_FILES['image']['size'];
        $filetmp = $_FILES['image']['tmp_name'];
        $filetype = $_FILES['image']['type'];
        $file_ext_err = explode('.', $filename);
        $fileext = strtoupper(end($file_ext_err));


        $extension = array("jpeg", "png", "jpg");
        $nF = "product_" . time();
        if (!in_array($fileext, $extension)) {
            $imageErr = "choose jpeg or png";
        } elseif ($filesize > 2345678) {
        } else {
            if (empty($imageErr)) {
                move_uploaded_file($filetmp, "./images" . $nF . "." . $fileext);

                $image = $nF . "." . $fileext;
                echo "success";
            }
        }

        //
    }
}


?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
</head>

<body>



</body>

</html>