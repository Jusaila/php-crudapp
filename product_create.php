<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - Create</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .container {
            max-width: 800px;
            margin: 100px auto;
            padding: 40px;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }
    </style>
</head>

<body>

    <?php
    include 'database.php';

    $title = $category_id = $description = $price = $image = "";
    $titleErr = $categoryErr = $descriptionErr = $priceErr = $imageErr = "";

    $categorysQuery = "SELECT id, name FROM categories";
    $result = $conn->query($categorysQuery);
    $categories = $result->fetch_all(MYSQLI_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


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

        //image
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $file_ext_arr = explode('.', $file_name);
            $file_ext = strtolower(end($file_ext_arr));

            $newfilename = "product_" . time(); // Example of defining a new file name

            $extensions = array("jpeg", "jpg", "png");

            if (!in_array($file_ext, $extensions)) {
                $imageErr = "Please Choose JPEG, JPG or PNG file only.";
            } elseif ($file_size > 8097152) {
                $imageErr = "File size must be under 2MB only";
            } else {
                if (empty($imageErr)) {
                    move_uploaded_file($file_tmp, "images/" . $newfilename . "." . $file_ext);

                    // Use $newfilename . "." . $file_ext as the value to save in the database
                    $image = $newfilename . "." . $file_ext;
                    echo "Success";
                } else {
                    print_r($imageErr);
                }
            }
        } else {
            $imageErr = "Image is required";
        }


        if (empty($titleErr) && empty($categoryErr) && empty($descriptionErr) && empty($priceErr)  && empty($imageErr)) {

            $sql = "INSERT INTO products (title, category_id, description, price, image) VALUES ('$title', '$category_id', '$description', '$price', '$image')";

            if (mysqli_query($conn, $sql)) {
                header("location: product_list.php");
            } else {
                echo "Error:  " . $conn->error;
            }
        }
    }

    ?>

    <div class="container">
        <h2>Product Create</h2><br>
        <form method="post" action="product_create.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
                <small class="text-danger"><?php echo $titleErr ?></small>
            </div>
            <div class="form-group">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-control" id="category_id" name="category_id">
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category["id"]; ?>" <?php echo $category_id == $category["id"]  ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category["name"]); ?>
                        </option>
                    <?php endforeach; ?>
                    <!-- Add more categories as needed -->
                </select>
                <small class="text-danger"><?php echo $categoryErr; ?></small>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" value="<?php echo htmlspecialchars($description); ?>"></textarea>
                <small class="text-danger"><?php echo $descriptionErr; ?></small>

            </div>
            <div class="form-group">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>">
                <small class="text-danger"><?php echo $priceErr; ?></small>

            </div>
            <div class="form-group">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>">
                <small class="text-danger"><?php echo $imageErr; ?></small>

            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>

</body>

</html>