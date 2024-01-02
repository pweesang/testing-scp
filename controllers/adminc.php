<?php

    function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
        
    session_start();
    require_once(__DIR__ . '/connection.php');
    if($_SESSION['is_admin'] !== true){
        header("Location: login.php");
    }
    if(isset($_POST['csrf_token']) && validateCSRFToken($_POST['csrf_token'])){

        if(isset($_POST['add_item'])){
            $item_name = htmlspecialchars($_POST['item_name']);
            $item_picture = htmlspecialchars($_POST['item_picture']);
            $item_desc = htmlspecialchars($_POST['item_desc']);
            $item_stock = htmlspecialchars($_POST['item_stock']);
            
            if(is_numeric($item_stock) == 0){
                $_SESSION['item_failed'] = "Stock must be a number!"; 
                header("Location: ../adminitem.php");
            }

            $insert_query = "INSERT INTO items VALUES (NULL, ?, ?, ?, ?)";
            $stmt = $connection->prepare($insert_query);
            $stmt->bind_param("sssi", $item_name, $item_picture, $item_desc, $item_stock);
            $stmt->execute();
            $connection->close();
            $_SESSION['item_success'] = "Item has been successfully added!";
            header("Location: ../adminitem.php");
        }

        if (isset($_POST['edit_item'])) {
            $item_id = $_POST['item_id'];

            $item_name = htmlspecialchars($_POST['item_name']);
            $item_picture = htmlspecialchars($_POST['item_picture']);
            $item_desc = htmlspecialchars($_POST['item_desc']);
            $item_stock = htmlspecialchars($_POST['item_stock']);

            $stmt = $connection->prepare("SELECT * FROM items");
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                if(password_verify($row['item_id'], $item_id)){
                    if(is_numeric($item_stock) == 0){
                        $_SESSION['item_failed'] = "Stock must be a number!"; 
                        header("Location: ../adminitem.php");
                    }

                    $edit_query = "UPDATE items SET item_name = ?, item_picture = ?, item_desc = ?, item_stock = ? WHERE item_id = ?";
                    $stmt = $connection->prepare($edit_query);
                    $stmt->bind_param("sssii", $item_name, $item_picture, $item_desc, $item_stock, $row['item_id']);
                    $stmt->execute();
                    $connection->close();
                    $_SESSION['item_success'] = "Item has been successfully updated!";
                    header("Location: ../adminitem.php");
                }
            }
            header("Location: ../adminitem.php");
        }

        if (isset($_POST['delete_item'])) {
            $item_id = $_POST['item_id'];

            $stmt = $connection->prepare("SELECT * FROM items");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                if(password_verify($row['item_id'], $item_id)){
                    $delete_query = "DELETE FROM items WHERE item_id = ?";
                    $stmt = $connection->prepare($delete_query);
                    $stmt->bind_param("i", $row['item_id']);
                    $stmt->execute();
                    $connection->close();
                    $_SESSION['item_success'] = "Item has been successfully deleted!";
                    header("Location: ../adminitem.php");
                }
            }
            header("Location: ../adminitem.php");
        }

        if (isset($_POST['delete_user'])) {
            $user_id = $_POST['user_id_delete'];

            $stmt = $connection->prepare("SELECT * FROM users");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                if(password_verify($row['user_id'], $user_id) && ($row['user_id'] !== 0)){
                    $delete_query = "DELETE FROM users WHERE user_id = ?";
                    $stmt = $connection->prepare($delete_query);
                    $stmt->bind_param("i", $row['user_id']);
                    $stmt->execute();
                    $connection->close();
                    header("Location: ../adminuser.php");
                }
            }
            header("Location: ../adminuser.php");
        }


        if (isset($_POST['delete_report'])) {
            
            $report_id = $_POST['report_id_delete'];
            $stmt = $connection->prepare("SELECT * FROM reports");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if(password_verify($row['report_id'], $report_id)){
                    $delete_query = "DELETE FROM reports WHERE report_id = ?";
                    $stmt = $connection->prepare($delete_query);
                    $stmt->bind_param("i", $row['report_id']);
                    $stmt->execute();
                    $connection->close();
                    header("Location: ../admin.php");
                }
            }
            header("Location: ../admin.php");
        }
    }
header("Location: ../admin.php");
