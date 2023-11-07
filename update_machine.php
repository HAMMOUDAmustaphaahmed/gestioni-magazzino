<!DOCTYPE html>
<html>
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


<h2>Update Machine</h2>

<?php
// Include the database connection
require_once 'dbconnection.php';


// Initialize variables
$machineId = $newName = $newcreation_time = '';

if (isset($_POST['machine_id'])) {
    $machineId = $_POST['machine_id'];
    // Add debugging statement to check the value of $employeeId
   
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_machine'])) {
    $machineId = $_POST['machine_id'];

    // Fetch existing values for the employee
    $sql = "SELECT * FROM machine WHERE id_machine=$machineId";
    $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newName = $row['name_machine'];
        $newcreation_time = $row['creation_time'];


    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_submit'])) {
    $newName = $_POST['new_name'];

    // Check if values are not empty
    if (!empty($newName) ) {
        $sql = "UPDATE machine
        SET name_machine = '$newName'
        WHERE id_machine = $machineId";


        // Execute the SQL statement
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Machine updated successfully.');</script>";
            header('Location: machines.php'); // Redirect to the dashboard page
        } else {
            echo "Error updating Machine: " . $conn->error;
        }
    } else {
        echo "<script>alert('One or more values are empty. Make sure all values are provided before updating.');</script>";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_machine'])) {
    $machineId = $_POST['machine_id'];

    // Fetch existing values for the employee
    $sql = "SELECT * FROM machine WHERE id_machine=$machineId";
    $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newName = $row['name_machine'];
        $newcreation_time = $row['creation_time'];
    }
}
if (empty($newName)) {
    echo 'name empty';
}


?>


<form action="update_machine.php" method="post">
    <input type="hidden" name="machine_id" value="<?php echo $machineId; ?>">
    <label for="new_name">New Name:</label><br>
    <input type="text" id="new_name" name="new_name" value="<?php echo $newName; ?>"><br>
    <input type="submit" name="update_submit" value="Update Machine">
</form>
<?php
if (isset($_POST['machine_id'])) {
    $machineId = $_POST['machine_id'];
    // Add debugging statement to check the value of $employeeId
   
}
?>

</body>
</html>
