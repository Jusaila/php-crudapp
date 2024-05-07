<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php



    //connect to database
    include 'database.php';

    session_start();


    //initialise errors & variables
    $user_username = $user_password = "";

    $user_usernameErr = $user_passwordErr = "";

    //when form submit

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty(trim($_POST["user_username"]))) {

            $user_usernameErr = "Please enter your username .";
        } else {

            $user_username = trim($_POST["user_username"]);
        }

        if (empty(trim($_POST["user_password"]))) {

            $user_passwordErr = "Please enter your password.";
        } else {

            $user_password = trim($_POST["user_password"]);
        }

        //validation
        if (empty($user_usernameErr) && empty($user_passwordErr)) {

            $sql = "SELECT id, user_name, password FROM users WHERE user_name = ? AND status = 1";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("s", $user_username);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows == 1) {

                $user = $result->fetch_assoc();


                if (md5($user_password) === $user['password']) {

                    $_SESSION['loggedin'] = true;

                    $_SESSION["id"] = $user['id'];

                    $_SESSION["username"] = $user['user_name'];

                    header("location: product_create.php");

                    exit;
                } else {
                    // If the password does not match, set an error message
                    $user_passwordErr = "Invalid password.";
                }
            } else {
                // If no user found with that username, also set an error message
                $user_usernameErr = "Invalid username.";
            }

            // Close statement
            $stmt->close();
        }
    }
    ?>
    <div class="container">
        <h1>Log In</h1>
        <p>Please login to continue</p>
        <form method="post" action="login.php">
            <div class="form-group">
                <div class="mb-0">
                    <label for="user_userlogin" class="form-label">User Name</label>
                    <input type="text" class="form-control" name="user_username" value="<?php echo htmlspecialchars($user_username); ?>">
                    <small class="text-danger"><?= $user_usernameErr; ?></small>
                </div>
                <div class="mb-0">
                    <label for="user_password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="user_password">
                    <small class="text-danger"><?= $user_passwordErr; ?></small>

                </div>
                <br>
                <br>
                <div class="mt-3">
                    <input type="submit" class="btn btn-primary form-control" name="submit" value="Log In">
                </div>
                <br>
                <p class="mb-3">Don't have an account ? <a href="./register.php">Register</a></p>

            </div>
        </form>
        <div>
        </div>
    </div>
</body>

</html>