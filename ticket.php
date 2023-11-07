<?php

require_once 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_name = $_POST['employee_name'];
    $machine_name = $_POST['machine_name'];
    $issue_type = $_POST['issue_type'] === 'electrical_issue' ? 1 : 2; // 1 for electrical_issue, 2 for mechanical_issue
    $description = $_POST['description'];
    $creation_time = date("Y-m-d H:i:s");
    $etat = 1; // Default state is not resolved

    $sql = "INSERT INTO ticket (name_user,name_machine,type_probleme, description, creation_time, etat) 
            VALUES ('$employee_name','$machine_name','$issue_type', '$description', '$creation_time', '$etat')";

    if ($conn->query($sql) === TRUE) {
        header('Location: user.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}



?>
