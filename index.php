<!DOCTYPE html>
<html>

<head>
    <style>
        /* Add some basic CSS styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        label {
            color: #666;
        }

        .success {
            color: #008000;
        }

        .error {
            color: #FF0000;
        }
    </style>
    <link rel="icon" href="./cubes-solid.ico" type="image/x-icon">
</head>

<body>
    <?php
    require_once 'dbconnection.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM employees WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($sql);

        if ($email === 'admin@gmail.com' && $password === 'admin.2024')  {
            // Redirect to the admin dashboard if the user is an admin
            
                header('Location: dashboard.php');
                exit;}
        else { if($result->num_rows > 0){

                // Check if there is an sql injection   
        $input = $_POST['email'];
        //test function
        function checkInput($input) {
        $unsafe_characters = array("'", '"', '=', ';');
        foreach ($unsafe_characters as $char) {
            if (strpos($input, $char) !== false) {
                echo "<script>alert('Input contains potentially harmful characters. Please try again.');</script>";
                return null;
            }
        }
        return $input;
    }
    //testing
    $clean_input = checkInput($input);

    if ($clean_input !== null) {
        // Proceed with using the clean input
        header('Location: user.php');
                exit;
    }else{
        header('Location: unsafe.php');
    }
                // Redirect to the user page if the user is not an admin
                }
                else {
                    echo "<script>alert('Invalid username or password. Please try again.');</script>";
                }
            }
        } 

        $conn->close();
    
    ?>

    <div class="login-form">
        <form action="" method="post">
            <h2 style="text-align: center;">Login</h2>
            <label for="email">Email:</label><br>
            <input type="text" id="email" name="email"><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br><br>
            <input type="submit" value="Login">
        </form>
    </div>

    <?php
    
    ?>

</body>

</html>
