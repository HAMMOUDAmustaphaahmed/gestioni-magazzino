<!DOCTYPE html>
<html>
<body>

<style>
    /* Add some basic CSS styling */
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


<h2>Update Product</h2>
<?php
// Include the database connection
require_once 'dbconnection.php';

// Initialize variables
$productId = $newName = $newEntrée = $newSortie = $newPlace = '';

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
}

// Fetch existing values for the product
$sql = "SELECT * FROM products WHERE id=$productId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $newName = $row['name'];
    $Entrée = $row['entrée'];
    $newSortie = $row['sortie'];
    $newPlace = $row['place'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_submit'])) {
    $newName = $_POST['new_name'];
    $newEntrée = $_POST['new_entrée'];
    $newSortie = $_POST['new_sortie'];
    $newPlace = $_POST['new_place'];

    // Check if values are not empty
    if (!empty($newName) && !empty($newEntrée) && !empty($newPlace)) {
        $sql = "UPDATE products 
        SET name = '$newName', entrée = '$newEntrée', sortie = '$newSortie',quantity='$newEntrée'-'$newSortie', place = '$newPlace'
        WHERE id = $productId";

        // Execute the SQL statement
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Product updated successfully');</script>";
            header('Location: products.php'); // Redirect to the dashboard page

            // Record the modification in the history_product table
            $modification_time = date("Y-m-d H:i:s");
            
            $quantity = $newEntrée - $sortie;
            $sql_history = "INSERT INTO `history_product`(`id`, `name`, `entrée`, `sortie`, `quantity`, `place`, `modification_time`) 
                VALUES ('$productId','$newName','$newEntrée','$newSortie','$quantity','$newPlace','$modification_time')";
            
            if ($conn->query($sql_history) === TRUE) {
                echo "<script>alert('Product modification recorded in history.');</script>";
            } else {
                echo "Error recording product modification in history: " . $conn->error;
            }
        } else {
            echo "<script>alert('Error updating product: ');</script>" . $conn->error;
        }
    } else {
        echo "<script>alert('One or more values are empty. Make sure all values are provided before updating.');</script>";
    }
}
?>

<form action="update_product.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
    <label for="new_name">New Name:</label><br>
    <input type="text" id="new_name" name="new_name" value="<?php echo $newName; ?>"><br>
    <label for="new_entrée">New Entrée:</label><br>
    <input type="text" id="new_entrée" name="new_entrée" value="<?php echo $Entrée; ?>"><br>
    <label for="new_entrée">New Sortie:</label><br>
    <input type="text" id="new_sortie" name="new_sortie" value="<?php echo $newSortie; ?>"><br>
    <label for="new_place">New Place:</label><br>
    <input type="text" id="new_place" name="new_place" value="<?php echo $newPlace; ?>"><br><br>
    <input type="submit" name="update_submit" value="Update Product">
</form>



</body>
</html>
