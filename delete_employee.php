<?php
// Include the database connection
require_once 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_employee'])) {
    $employeeId = $_POST['employee_id'];

    // Prepare the SQL statement to delete the employee
    $sql = "DELETE FROM employees WHERE id = $employeeId";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // employee deleted successfully, redirect to the employees.php page
        header('Location: employees.php');
        exit();
    } else {
        // If an error occurs, you can handle it accordingly
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
