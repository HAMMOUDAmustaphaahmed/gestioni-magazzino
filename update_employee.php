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


<h2>Update Employee</h2>

<?php
// Include the database connection
require_once 'dbconnection.php';


// Initialize variables
$employeeId = $newName = $newEmail = $newMatricule = $newPassword = '';

if (isset($_POST['employee_id'])) {
    $employeeId = $_POST['employee_id'];
    // Add debugging statement to check the value of $employeeId
   
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $employeeId = $_POST['employee_id'];

    // Fetch existing values for the employee
    $sql = "SELECT * FROM employees WHERE id=$employeeId";
    $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newName = $row['name'];
        $newEmail = $row['email'];
        $newMatricule = $row['matricule'];
        $newPassword = $row['password'];


    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_submit'])) {
    $newName = $_POST['new_name'];
    $newEmail = $_POST['new_email'];
    $newMatricule = $_POST['new_matricule'];
    $newPassword = $_POST['new_password'];

    // Check if values are not empty
    if (!empty($newName) && !empty($newEmail) && !empty($newMatricule) && !empty($newPassword) ) {
        $sql = "UPDATE employees 
        SET name = '$newName', email = '$newEmail', matricule = '$newMatricule' , password = '$newPassword'
        WHERE id = $employeeId";


        // Execute the SQL statement
        if ($conn->query($sql) === TRUE) {
           echo "<script>alert('Employee updated successfully');</script>";

            header('Location: employees.php'); // Redirect to the dashboard page
        } else {
            echo "Error updating employee: " . $conn->error;
        }
    } else {
        echo "<script>alert('One or more values are empty. Make sure all values are provided before updating.');</script>";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $employeeId = $_POST['employee_id'];

    // Fetch existing values for the employee
    $sql = "SELECT * FROM employees WHERE id=$employeeId";
    $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newName = $row['name'];
        $newEmail = $row['email'];
        $newMatricule = $row['matricule'];
        $newPassword = $row['password'];
    }
}
if (empty($newName)) {
    echo 'name empty';
}

if (empty($newEmail)) {
    echo 'email empty';
}

if (empty($newMatricule)) {
    echo 'matricule empty';
}
?>


<form action="update_employee.php" method="post">
    <input type="hidden" name="employee_id" value="<?php echo $employeeId; ?>">
    <label for="new_name">New Name:</label><br>
    <input type="text" id="new_name" name="new_name" value="<?php echo $newName; ?>"><br>
    <label for="new_email">New Email:</label><br>
    <input type="text" id="new_email" name="new_email" value="<?php echo $newEmail; ?>"><br>
    <label for="new_matricule">New Matricule:</label><br>
    <input type="text" id="new_matricule" name="new_matricule" value="<?php echo $newMatricule; ?>"><br><br>
    <label for="new_password">New Password:</label><br>
    <input type="text" id="new_password" name="new_password" value="<?php echo $newPassword; ?>"><br><br>
    <input type="submit" name="update_submit" value="Update Employee">
</form>
<?php
if (isset($_POST['employee_id'])) {
    $employeeId = $_POST['employee_id'];
    // Add debugging statement to check the value of $employeeId
   
}
?>

</body>
</html>
