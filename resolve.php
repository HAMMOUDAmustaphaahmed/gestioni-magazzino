<?php
// Check if the ticket ID is set
if (isset($_POST['ticket_id'])) {
    // Retrieve the ticket ID from the form
    $ticket_id = $_POST['ticket_id'];

    // Perform the resolving action in the database
    require_once 'dbconnection.php';
    $resolved_time = date("Y-m-d H:i:s");
    // Update the etat to 2, indicating that the ticket has been resolved
    $sql = "UPDATE ticket SET etat = 0, resolved_time = '$resolved_time' WHERE id_ticket = '$ticket_id'";


    if ($conn->query($sql) === TRUE) {
        // Redirect to the alert page after resolving the issue
        header('Location: alert.php');
        exit;
    } else {
        // Handle the case when the query fails
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
} else {
    // Handle the case when the ticket ID is not set
    echo "Ticket ID not provided.";
}
?>
