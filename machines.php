<!DOCTYPE html>
<html>

<head>
    <style>
        /* Add some basic CSS styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
            width: 60%;
            margin: 0 auto;
        }
        h2 {
            color: #333;
        }
        form {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
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
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            justify-content: space-evenly;
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


<div class="container">
    <h2>Manage Machines</h2>

    <form action="machines.php" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br><br>
        <input type="submit" name="add_machine" value="Add Machine">
    </form>

    <?php
require_once './dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_machine'])) {
    $name = $_POST['name'];
    $creation_time = date("Y-m-d H:i:s");
    // Perform the necessary checks and database operations here
    if (!empty($name)) {
        $sql = "INSERT INTO machine (name_machine, creation_time) VALUES ('$name', '$creation_time')";

        // For the sake of example, let's assume the addition was successful
        if ($conn->query($sql) === TRUE) {
            echo '<p class="success">Adding machine successfully</p>';
        } else {
            echo '<p class="error">Not successful</p>';
        }
        // Redirect to the dashboard page
    } else {
        echo '<p class="error">One or more values are empty</p>';
    }
}
?>




<form action="machines.php" method="get">
    <label for="search">Search:</label>
    <input type="text" id="search" name="search_query">
    <input type="submit" value="Search">
    </form>
    <h3>Search results</h3>
    <table style='width: 100%; border-collapse: collapse; border: 1px solid #ddd;'>
    <?php
 if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search_query'])) {
    $searchQuery = $_GET['search_query'];
if (!empty($searchQuery)) {
    // Modify the SQL query to incorporate the search functionality
    $sql = "SELECT * FROM machine WHERE name_machine LIKE '%$searchQuery%'";
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id_machine"] . "</td>
                    <td>" . $row["name_machine"] . "</td>
                    <td>" . $row["creation_time"] . "</td>
                    <td class='action-buttons'>
                        <form action='delete_machine.php' method='post'>
                            <input type='hidden' name='machine_id' value='" . $row["id_machine"] . "'>
                            <button type='submit' name='delete_machine' style='background-color: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'>Delete</button>
                        </form>
                        <form action='update_machine.php' method='post'>
                            <input type='hidden' name='machine_id' value='" . $row["id_machine"] . "'>
                            <button type='submit' name='update_machine' style='background-color: #4CAF50; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'>Update</button>
                        </form>
                    </td>
                </tr>";
            }
        } else {
            echo "<p class='error'>No results found for the search query: $searchQuery</p></br>";
        }
    }else{echo"<p class='error'> The search query is empty </p></br>";} 
     }
    
        ?>
</table>

<h3>List Of Machines</h3>


<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Creation Time</th>
        <th>Actions</th>
    </tr>

    <?php
    $sql = "SELECT * FROM machine";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id_machine"] . "</td>
                    <td>" . $row["name_machine"] . "</td>
                    <td>" . $row["creation_time"] . "</td>
                    <td class='action-buttons'>
                        <form action='delete_machine.php' method='post'>
                            <input type='hidden' name='machine_id' value='" . $row["id_machine"] . "'>
                            <button type='submit' name='delete_machine' style='background-color: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'>Delete</button>
                        </form>
                        <form action='update_machine.php' method='post'>
                            <input type='hidden' name='machine_id' value='" . $row["id_machine"] . "'>
                            <button type='submit' name='update_machine' style='background-color: #4CAF50; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'>Update</button>
                        </form>
                    </td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>0 results</td></tr>";
    }
    $conn->close();
    ?>
</table>

</div>

</body>
<?php
require_once'footer.php';
?>
</html>
