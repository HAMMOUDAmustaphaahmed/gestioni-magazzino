<!DOCTYPE html>
<html>
<head><link rel="icon" href="./cubes-solid.ico" type="image/x-icon"></head>
<body>

<style>
    /* Add some basic CSS styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
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
</style>

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
// Include the database connection
require_once 'dbconnection.php';

// Initialize variables
$productId = '';

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    // Add debugging statement to check the value of $productId
   
}






if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['history_product'])) {
    $productId = $_POST['product_id'];

    // Fetch existing values for the product
    $sql = "SELECT * FROM history_product WHERE id=$productId";
    $result = $conn->query($sql);

    echo'
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
    <tr style="background-color: #f9f9f9;">
        <th style="padding: 10px; border: 1px solid #ddd;">Name</th>
        <th style="padding: 10px; border: 1px solid #ddd;">Entrée</th>
        <th style="padding: 10px; border: 1px solid #ddd;">Sortie</th>
        <th style="padding: 10px; border: 1px solid #ddd;">Quantity</th>
        <th style="padding: 10px; border: 1px solid #ddd;">Place</th>
        <th style="padding: 10px; border: 1px solid #ddd;">Modification Time</th>
        
    </tr>';


        if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr style='border: 1px solid #ddd;'>
    <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["name"] . "</td>
    <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["entrée"] . "</td>
    <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["sortie"] . "</td>
    <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["quantity"]-$row["sortie"] . "</td>
    <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["place"] . "</td>
    <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["modification_time"] . "</td>
    <td style='padding: 10px; text-align:center; border: 1px solid #ddd; text-align: center;'>     
    </td>
</tr></br>"; }
    } else {
        echo "<tr><td colspan='5' style='padding: 10px; border: 1px solid #ddd;'>0 results</td></tr></br>";
    }
}
?>   
</table>
</body>

</html>