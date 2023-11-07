<!DOCTYPE html>
<html>

<head>
    <style>
        /* Add your CSS styling here */
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
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        .success {
        color: #008000;
    }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
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


    <?php
    require_once 'dbconnection.php';

    $sql = "SELECT * FROM ticket WHERE etat = 1";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            echo'<h1>Liste des problemes qui ne sont pas resolues :</h1>';
            // Display the unresolved issues
            echo "<table>";
            echo "<tr><th>Issue ID</th><th>User</th><th>Machine Name</th><th>Problem Type</th><th>Description</th><th>Creation Time</th><th>Action</th></tr>";
            while ($row = $result->fetch_assoc()) {

                $name=$row["name_user"];

                echo "<tr>";
                echo "<td>" . $row["id_ticket"] . "</td>";
                echo "<td>" . $name . "</td>";
                echo "<td>" . $row["name_machine"] . "</td>";
                echo "<td>" . $row["type_probleme"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td>" . $row["creation_time"] . "</td>";
                echo "<td><form action='resolve.php' method='post'><input type='hidden' name='ticket_id' value='" . $row["id_ticket"] . "'><input type='submit' value='Resolve'></form></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<h3><p class=success>Tous les tickets sont résolus</p></h3>";
        }
    } else {
        echo "Error description: " . mysqli_error($conn);
    }

    
    ?>

<form action="alert.php" method="get">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search_query">
        <input type="submit" value="Search">
    </form>
    <h3>Search results</h3>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search_query'])) {
        $searchQuery = $_GET['search_query'];
        if (!empty($searchQuery)) {
            // Modify the SQL query to incorporate the search functionality
            $sql = "SELECT * FROM ticket WHERE name_machine LIKE '%$searchQuery%'";
            $result = $conn->query($sql);
            
            if ($result !== false) {
                if ($result->num_rows > 0) {
                    echo '<table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                    <tr style="background-color: #f9f9f9;">
                        <th style="padding: 10px; border: 1px solid #ddd;">Ticket Id</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">User</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Machine Name</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Problem Type</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Description</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Creation Time</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Resolved Time</th>
                    </tr>';
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr style='background-color: #f9f9f9;'>
                            <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["id_ticket"] . "</td>
                            <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" .$row["name_user"] .  "</td>
                            <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["name_machine"] .  "</td>
                            <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["type_probleme"] .  "</td>
                            <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["description"] . "</td>
                            <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["creation_time"] . "</td>
                            <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["resolved_time"] . "</td>
                            <td style='padding: 10px; text-align:center; border: 1px solid #ddd; text-align: center;'></td>
                        </tr>";
                    }echo"</table>";
                } else {
                    echo "<p class='error'>No results found for the search query: $searchQuery</p></br>";
                }
            } else {
                echo "<p class='error'>Error executing the SQL query: " . $conn->error . "</p>";
            }
        } else {
            echo"<p class='error'> The search query is empty </p></br>";
        }
    }

    ?>

    <h1>Liste des problemes resolues:</h1>

    <?php
    $sql = "SELECT * FROM ticket WHERE etat = 0";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // Display the unresolved issues
            echo "<table>";
            echo "<tr><th>Ticket ID</th><th>User</th><th>Machine Name</th><th>Problem Type</th><th>Description</th><th>Creation Time</th><th>Resolved Time</th></tr>";
            while ($row = $result->fetch_assoc()) {

                $name = $row["name_user"];

                echo "<tr>";
                echo "<td>" . $row["id_ticket"] . "</td>";
                echo "<td>" . $name . "</td>";
                echo "<td>" . $row["name_machine"] . "</td>";
                echo "<td>" . $row["type_probleme"] . "</td>";
                echo "<td>" . $row["description"] . "</td>";
                echo "<td>" . $row["creation_time"] . "</td>";
                echo "<td>" . $row["resolved_time"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } else {
        echo "Error description: " . mysqli_error($conn);
    }
    ?>



</body>
<?php
require_once'footer.php';
?>
</html>
