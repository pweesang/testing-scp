<?php
    function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    session_start();
    require_once(__DIR__ . '/connection.php');
    $report_typelist = array(
        "1" => "Kritik dan Saran",
        "2" => "Pengajuan Keluhan",
        "3" => "Lainnya"
    );
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['csrf_token']) && validateCSRFToken($_POST['csrf_token'])){
        if(isset($_POST['send_data'])){
            $report_type = $_POST['report_type'];
            $description = $_POST['description'];

            $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

            $error = false;

            if(!in_array($report_type,array_keys($report_typelist))){
                $_SESSION['error_report'] = "Please enter a valid report type!";
                $error = true;
            }else if(strlen($description) < 20 ||strlen($description) > 150){
                $_SESSION['error_report'] = "Description length must be between 20 until 150 characters!";
                $error = true;
            }
            if($error){
                header("Location: ../report.php");
                die;
            }

            if ($connection->error){
                echo $connection->error;
                die;
            }
            $user_id = $_SESSION['user_id'];
            $report_type = $report_typelist[$report_type];
            
            $stmt = $connection->prepare("INSERT INTO reports VALUES (NULL,?,?,?,NOW());");
            $stmt->bind_param("iss", $user_id, $report_type, $description);
            $stmt->execute();
            $connection->close();

            $_SESSION['report_success'] = "Report has been successfully sent!";

            header("Location: ../report.php");
        }
    }
    
