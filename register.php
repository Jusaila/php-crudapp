<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    include 'database.php';

    #define variables and error messages
    $first_name = $last_name = $email = $mobile_number = $user_name = $password = $confirm_password = "";

    $first_nameErr = $last_nameErr = $emailErr = $mobile_numberErr =   $user_nameErr = $passwordErr = $confirm_passwordErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $first_name = trim($_POST["first_name"]);

        $last_name = trim($_POST["last_name"]);

        $email = trim($_POST["email"]);

        $mobile_number = trim($_POST["mobile_number"]);

        $user_name = trim($_POST["user_name"]);

        $password = trim($_POST["password"]);

        $confirm_password = trim($_POST["confirm_password"]);

        $created_on = date('Y-m-d H:i:s');

        $status = 1;

        //error messages
        //firstname

        if (empty($first_name)) {

            $first_nameErr = "Firstname is required";
        } else {

            if (!preg_match("/^[a-zA-Z\. ]{3,20}+$/", $first_name)) {

                $first_nameErr = "First Name must only contain alphabetical and between 3 - 20 characters and '.' (spaces allowed).";
            }
        }

        //lastname

        if (!empty($last_name) && strlen($last_name) < 2 || strlen($last_name) > 20) {

            $last_nameErr = "The LastName character should contain atleast 2 characters and the maximum length is 20 characters";
        }

        //e-mail

        if (empty($email)) {

            $emailErr = "E-mail is required";
        } else {

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $emailErr = "Invalid E-mail format";
            } else {
                // Check if email already exists in the database
                $query = "SELECT * FROM users WHERE email = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Email already exists
                    $emailErr = "This E-mail is already registered";
                }
            }
        }
        //mobilenumber

        if (empty($mobile_number)) {

            $mobile_numberErr = "MobileNumber is required";
        } else {

            if (!preg_match("/^\d{10}$/", $mobile_number)) {

                $mobile_numberErr = "MobileNumber must be contain 10 digits";
            } else {
                // Check if email already exists in the database
                $query = "SELECT * FROM users WHERE mobile_number = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $mobile_number);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Email already exists
                    $mobile_numberErr = "This Mobile Number is already registered";
                }
            }
        }

        //username
        if (empty($user_name)) {
            $user_nameErr = "User Name is required";
        } else {
            // Check if email already exists in the database
            $query = "SELECT * FROM users WHERE user_name = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Email already exists
                $user_nameErr = "This UserName is already Taken";
            }
        }


        //password

        if (empty($password)) {

            $passwordErr = "Password is required.";
        } else {

            if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,}$/", $password)) {

                $passwordErr = "The Password should be at least 6 characters and contain at least 1 number, 1 lowercase letter, 1 uppercase letter, and 1 special character.";
            }
        }

        $password_hashed = md5($password);


        //confirmPassword

        if (empty($confirm_password)) {

            $confirm_passwordErr = "Please Confirm your Password";
        } else if (strcmp($password, $confirm_password) != 0) {

            $repeatPasswordErr = "Passwords not match";
        } else {

            if (!empty($passwordErr)) {

                $repeatPasswordErr = "**";
            }
        }

        //Test to see that the error strings are empty and connect to database

        if ($first_nameErr == "" && $last_nameErr == "" && $emailErr == "" && $mobile_numberErr == "" && $user_nameErr == ""  && $passwordErr == ""  && $confirm_passwordErr == "") {

            $server = "localhost";

            $conn = mysqli_connect($server, "root", "", "registration", "3307");

            if (!$conn) {

                die("Connection failed: " . mysqli_connect_error());
            }

            echo " ";

            $sql = "INSERT INTO users (first_name, last_name, email, mobile_number, user_name, password, status, created_on)
            VALUES ('$first_name', '$last_name', '$email', '$mobile_number', '$user_name', '$password_hashed', '$status', '$created_on')";


            if (mysqli_query($conn, $sql)) {

                header("location:login.php");
            } else {

                echo "0 results";
            }

            $first_name = $last_name = $email = $mobile_number = $user_name = $password = $confirm_password = "";
        }
    }


    ?>


    <div class="container">
        <h2>Registration</h2>
        <form method="post" action="register.php">
            <div id="formField" class="form-group">
                <div class="mb-2">
                    <label for="first_name" class="form-label">First Name:</label><br>
                    <input id="first_name" class="form-control" type="text" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                    <small class="text-danger"><?php echo $first_nameErr; ?></small>
                </div>
                <div class="mb-2">
                    <label for="last_name" class="form-label">Last Name:</label><br>
                    <input id="last_name" class="form-control" type="text" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                    <small class="text-danger"><?php echo $last_nameErr; ?></small>
                </div>
                <div class="mb-2">
                    <label for="email" class="form-label">E-mail:</label><br>
                    <input id="email" class="form-control" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <small class="text-danger"><?php echo $emailErr; ?></small>
                </div>
                <div class="mb-2">
                    <label for="mobile_number" class="form-label">Mobile Number:</label><br>
                    <input id="mobile_number" class="form-control" type="text" name="mobile_number" value="<?php echo htmlspecialchars($mobile_number); ?>">
                    <small class="text-danger"><?php echo $mobile_numberErr; ?></small>
                </div>
                <div class="mb-2">
                    <label for="user_name" class="form-label">User Name:</label><br>
                    <input id="user_name" class="form-control" type="text" name="user_name" value="<?php echo htmlspecialchars($user_name); ?>">
                    <small class="text-danger"><?php echo $user_nameErr; ?></small>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Password:</label><br>
                    <input id="password" class="form-control" type="password" name="password">
                    <small class="text-danger"><?php echo $passwordErr; ?></small>
                </div>
                <div class="mb-2">
                    <label for="confirm_password" class="form-label">Confirm Password:</label><br>
                    <input id="confirm_password" class="form-control" type="password" name="confirm_password">
                    <small class="text-danger"><?php echo $confirm_passwordErr; ?></small>
                </div>
                <br>
                <br>
                <input type="submit" name="submit" value="Register" class="btn btn-primary form-control">
                <br>
                <br>
                <p class="mb-0">Already have an account ? <a href="./login.php">Login</a></p>
        </form>
    </div>
</body>

</html>