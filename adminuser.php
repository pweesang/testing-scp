<?php

    function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    generateCSRFToken();
    session_start();
    require_once(__DIR__ . '/controllers/connection.php');
    if($_SESSION['is_admin'] !== true){
        header("Location: login.php");
    }
    ?>


<!DOCTYPE html>
<html lang="en">
    
    <head>
        <title>Admin User Page</title>
    <style>
        header {
            background-color: #333;
            display: block;
            color: #fff;
            padding: 1%;
            position:sticky;
            
            top:0px;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        li {
            display: inline;
            margin-right: 3%;
        }

        a {
            text-decoration: none;
            color: #fff;
        }
        #login{
            display: inline;
            margin-right : 3%;
        }
        /* footer{
            position:sticky;
            bottom:0px;
            background-color: white;
            color: #fff;
            padding: 0%;
        } */
        .footer{
            color:black;
            margin-left:3%;
            margin-right:3%;
            margin-top:90%;
        }
        hr{
            color:lightgrey;
            
        }
        .custom-table {
         width: 100%;
         border-collapse: collapse;
         
        }
        .custom-table td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        margin-right : 3%;
         margin-left: 3%;
        }
        /* .custom-table th {
        background-color: #333;
        color: #fff;
        } */
        .custom-table tbody tr:nth-child(4){
        background-color: #fff;
        margin-right : 3%;
        margin-left: 3%;
        }
        .adminn{
            margin-bottom: 10px;
        }
    </style>
    </head>
    
    <body>
    <header>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="adminuser.php">Users</a></li>
                <li><a href="admin.php">Reports</a></li>
                <li><a href="adminitem.php">Items</a></li>
                <div id='login'><a href='settings.php'>Settings</a></div>
                <div id='login'><a href='controllers/logoutc.php'>Logout</a></div>
            <!-- <li><a href="#">Services</a></li>-->
            </ul>
        </header>

        <h1>Admin User</h1>
        <form action="adminuser.php" method=post></form>
        <?php
            echo"<h3>List user yang ada</h3>";
            if($connection->error){
                die($connection->error);
            }

            $stmt = $connection->prepare("SELECT * FROM users");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo '<form action="controllers/adminc.php" method="post">';
                echo '<input type="hidden" name="csrf_token" value="' . $_SESSION["csrf_token"] .'">';
                echo '<input type="hidden" name="user_id_delete" value="' . password_hash(htmlspecialchars($row["user_id"]), PASSWORD_BCRYPT) . '">';
                echo "Name: " . $row["username"] . "<br>";
                echo "Email: " . $row["email"] . "<br>";
                if($row["username"] === 'admin'){
                    echo "<div class = 'adminn'>Notes: This Account!</div>";
                    continue;
                }
                echo '<button type="submit" name="delete_user">Delete</button>';
                echo '</form><br><br>';
            }
        ?>

    </body>

</html>