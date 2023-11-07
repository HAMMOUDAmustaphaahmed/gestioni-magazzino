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
<head><link rel="icon" href="./cubes-solid.ico" type="image/x-icon"></head>
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

<div id="products" class="form-section">
    <h2>Manage Products</h2>
    <?php

    
    require_once './dbconnection.php';




    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $entrée = $_POST['entrée'];
        $place = $_POST['place'];
        $minQuantity=$_POST['min_quantity'];
        
        // Perform the necessary checks and database operations here
        if(!empty($name) && !empty($entrée) && !empty($place) ){
        $sql = "INSERT INTO products (name, entrée, quantity,place,min_quantity) VALUES ('$name', '$entrée', '$entrée','$place','$minQuantity')";

        // For the sake of example, let's assume the addition was successful
        if ($conn->query($sql) === TRUE) {
            echo '<p class="success">Adding product successfully</p>';
        } else {
            echo '<p class="error">Not successful</p>';
        }
         // Redirect to the dashboard page
    }
else {
    echo '<p class="error">One or more values are empty</p>';
}

}
    ?>
    <form action="products.php" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="entrée">Entrée:</label><br>
        <input type="text" id="entrée" name="entrée"><br>

        <label for="min_quantity">Min Quantity:</label><br>
        <input type="text" id="min_quantity" name="min_quantity"><br>

        <label for="place">Place:</label><br>
        <input type="text" id="place" name="place"><br><br>
        <input type="submit" name="add_product" value="Add Product">
    </form>


    <form action="products.php" method="get">
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
    $sql = "SELECT * FROM products WHERE name LIKE '%$searchQuery%'";
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        echo '<table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
        <tr style="background-color: #f9f9f9;">
            <th style="padding: 10px; border: 1px solid #ddd;">Name</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Entrée</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Sortie</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Quantity</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Place</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Creation Time</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Actions</th>
        </tr>';
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "
            <tr style='background-color: #f9f9f9;'>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["name"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["entrée"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["sortie"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["entrée"]-$row["sortie"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["place"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["creation_time"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd; text-align: center;'>
            <form style='display: inline-block;' action='delete_product.php' method='post'>
                <input type='hidden' name='product_id' value='" . $row["id"] . "'>
                <button type='submit' name='delete_product' style='background-color: #f44336; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'><i class='fa fa-trash'></i> Delete</button>
            </form>
            <form style='display: inline-block;' action='update_product.php' method='post'>
                <input type='hidden' name='product_id' value='" . $row["id"] . "'>
                <button type='submit' name='update_product' style='background-color: #4CAF50; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;'><i class='fa fa-pencil'></i> Update</button>
            </form>
            <form style='display: inline-block;' action='history_product.php' method='post'>
                <input type='hidden' name='product_id' value='" . $row["id"] . "'>
                <button type='submit' name='history_product' style='background-color: #0CA0F0; color: white; padding: 5px 5px; border: none; border-radius: 5px; cursor: pointer;'><i class='fa fa-pencil'></i> History</button>
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





    <!-- Display the list of products -->
    <h3>List of Products</h3>
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
        <tr style="background-color: #f9f9f9;">
            <th style="padding: 10px; border: 1px solid #ddd;">Name</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Entrée</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Sortie</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Quantity</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Place</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Creation Time</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Actions</th>
        </tr>
        <!-- Add a search form -->


    
        <?php
        // Retrieve and display the list of products
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr style='border: 1px solid #ddd;'>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["name"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["entrée"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["sortie"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["quantity"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["place"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd;'>" . $row["creation_time"] . "</td>
        <td style='padding: 10px; text-align:center; border: 1px solid #ddd; text-align: center;'>
            <form style='display: inline-block;' action='delete_product.php' method='post'>
                <input type='hidden' name='product_id' value='" . $row["id"] . "'>
                <button type='submit' name='delete_product' style='background-color: #f44336; color: white; padding: 5px 5px; border: none; border-radius: 5px; cursor: pointer;'><i class='fa fa-trash'></i> Delete</button>
            </form>
            <form style='display: inline-block;' action='update_product.php' method='post'>
                <input type='hidden' name='product_id' value='" . $row["id"] . "'>
                <button type='submit' name='update_product' style='background-color: #4CAF50; color: white; padding: 5px 5px; border: none; border-radius: 5px; cursor: pointer;'><i class='fa fa-pencil'></i> Update</button>
            </form>
            <form style='display: inline-block;' action='history_product.php' method='post'>
                <input type='hidden' name='product_id' value='" . $row["id"] . "'>
                <button type='submit' name='history_product' style='background-color: #0CA0F0; color: white; padding: 5px 5px; border: none; border-radius: 5px; cursor: pointer;'><i class='fa fa-pencil'></i> History</button>
            </form>
        </td>
    </tr></br>"; }
        } else {
            echo "<tr><td colspan='5' style='padding: 10px; border: 1px solid #ddd;'>0 results</td></tr></br>";
        }
        ?>
    </table>

    <!-- Add other CRUD forms for product management as needed -->
</div>
<?php
require_once'footer.php';
?>

