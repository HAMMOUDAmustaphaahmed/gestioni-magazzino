<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add some basic CSS styling */
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color: white;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            background-color: #888;
            padding: 10px 20px;
            border-radius: 20px;
           
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 14px 16px;
            transition: background-color 0.3s;
            border-radius: 20px;
        }
        .navbar a:hover {
            background-color: #000;
            box-shadow: 0 0 15px #005, 0 0 30px #00a, 0 0 45px #00f, 0 0 60px #00f;
            border-radius: 20px;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8);
            margin-top: 50px;
        }
        .button {
            margin: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            
        }
    </style>
    <style>
        /* Add some basic CSS styling */
        body {
            background-color: black;
        }
        #clock {
            font-family: Arial, sans-serif;
            font-size: 72px;
            text-align: center;
            margin-top: 100px;
            color: white;
        }
        #date {
            font-family: Arial, sans-serif;
            font-size: 36px;
            text-align: center;
            margin-bottom: 50px;
            color: white;
        }
    </style>
    <link rel="icon" href="./cubes-solid.ico" type="image/x-icon">
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <?php
    require_once 'dbconnection.php';
    $sql = "SELECT id, name, quantity, min_quantity FROM products";
    $result = $conn->query($sql);

    $alertCount = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productQuantity = $row['quantity'];
            $minQuantity = $row['min_quantity'];
            if ($productQuantity - $minQuantity <= 0) {
                $alertCount++;
            }
        }
    }

    if ($alertCount > 0) {
        echo '<a href="products.php">Manage Produits <span style="background-color: red; color: white; border-radius: 50%; padding: 5px;">' . $alertCount . '</span></a>';
    } else {
        echo '<a href="products.php">Manage Produits</a>';
    }
    ?>
    <a href="employees.php">Manage Employees</a>
    <a href="machines.php">Manage Machines</a>
    <?php
    require_once 'dbconnection.php';
    $sql = "SELECT COUNT(*) as count FROM ticket WHERE etat = 1";
    $result = $conn->query($sql);
    $count = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row['count'];
    }
    if ($count > 0) {
        echo '<a href="alert.php">Alert <span style="background-color: red; color: white; border-radius: 50%; padding: 5px;">' . $count . '</span></a>';
    } else {
        echo '<a href="alert.php">Alert</a>';
    }
    ?>
    <a href="dashboard.php?logout=true">Logout</a>
</div>



    <div id="clock-container" style="background-color: black;">
        <div id="clock"></div>
        <div id="date"></div>
    </div>
    
<div class="logo" style="text-align:center;">
    <img src="./logo.gif">
    </img>
</div>
    <?php
    if (isset($_GET['logout'])) {
        session_unset(); // Unset all of the session variables
        session_destroy(); // Destroy the session
        header('Location: index.php'); // Redirect to the login page
        exit;
    }
    ?>
<script>
        function displayDateTime() {
            const clock = document.getElementById('clock');
            const date = document.getElementById('date');
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };
            const dateString = now.toLocaleDateString('fr-FR', options);
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            const timeString = hours + ':' + minutes + ':' + seconds;
            clock.textContent = timeString;
            date.textContent = dateString;
        }

        // Call the function to display the date and time immediately on page load
        displayDateTime();

        // Set an interval to update the clock every second
        setInterval(displayDateTime, 1000);
    </script>
</body>
<?php
require_once'footer.php';
?>
</html>
