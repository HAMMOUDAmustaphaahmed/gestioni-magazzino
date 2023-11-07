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
            align-items: center;
            background-color: #888;
            padding: 10px 20px;
            border-radius: 20px;
            text-align: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 14px 16px;
            transition: background-color 0.3s;
            border-radius: 20px;
            text-align: center;
        }

        .navbar a:hover {
            background-color: #000;
            box-shadow: 0 0 15px #005, 0 0 30px #00a, 0 0 45px #00f, 0 0 60px #00f;
            border-radius: 20px;
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .user-form {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        textarea {
            width: calc(100% - 40px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
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
</head>

<body>
    <div class="navbar">
        <div style="flex-grow: 1;"></div>
        <a href="index.php">Logout</a>
    </div>

    <div class="container">
        <div class="user-form">
            <form action="ticket.php" method="post">
                <h2>Créer une Ticket</h2>

                
                <label for="employee_name">Nom de l'Employé:</label><br>
                <select  name="employee_name">
                    <?php
                    require_once 'dbconnection.php';
                    $sql = "SELECT * FROM employees";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option >' . $row["name"] . '</option>';
                        }
                    }
                    
                    ?>
                </select><br><br>

                <label for="machine_name">Nom de la Machine:</label><br>
                <select  name="machine_name">
                    <?php
                    require_once 'dbconnection.php';
                    $sql = "SELECT * FROM machine";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option >' . $row["name_machine"] . '</option>';
                        }
                    }
                    $conn->close();
                    ?>
                </select><br><br>
                
                <label for="issue_type">Type de Problème:</label><br>
                <input type="radio" id="electrical_issue" name="issue_type" value="electrical_issue">
                <label for="electrical_issue">Problème Electrique</label><br>
                <input type="radio" id="mechanical_issue" name="issue_type" value="mechanical_issue">
                <label for="mechanical_issue">Problème Mécanique</label><br>
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>
                <input type="submit" value="Créer une Ticket">
            </form>
        </div>
    </div>
</body>

</html>
