<?php

    session_start();
    require_once(__DIR__ . '/connection.php');
    function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && validateCSRFToken($_POST['csrf_token'])) {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
        $password = $_POST["password"];
        $confirm_pass = $_POST["confirmpassword"];
        $validate = 1;
        
        $name_words = str_word_count($name);

        if(strlen($name)==0){
            $validate=0;
            $_SESSION['regist_failed']='Name cannot be empty!';
        }else if($name_words > 20){
            $validate=0;
            $_SESSION['regist_failed']='Name is too long!';
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $validate=0;
            $_SESSION['regist_failed'] = 'Email is empty or not valid!';
        }else if(strlen($username)==0){
            $validate=0;
            $_SESSION['regist_failed']='Username cannot be empty!';
        }else if(strlen($username)<5 || strlen($username)>15){
            $validate=0;
            $_SESSION['regist_failed']='Username length must be 5-15 characters!';
        }else if(is_numeric($phone) == 0){
            $validate=0;
            $_SESSION['regist_failed']='Phone number must be a number!';
        }else if(strlen($phone) < 10 || strlen($phone) >= 13){
            $validate=0;
            $_SESSION['regist_failed']='Phone number must between 10 to 12 characters!';
        }else if(strlen($password)==0){
            $validate=0;
            $_SESSION['regist_failed']='Password cannot be empty!';
        }else if(strlen($password)<8){
            $validate=0;
            $_SESSION['regist_failed']='Password length must be atleast 8 characters!';
        }else if (!preg_match('/[A-Z]/', $password)) {
            $validate=0;
            $_SESSION['regist_failed'] = "Password must contains at least 1 capital character!";
        }else if (!preg_match('/[a-z]/', $password)) {
            $validate=0;
            $_SESSION['regist_failed'] = "Password must contains at least 1 small character!";
        }else if (!preg_match('/[0-9]/', $password)) {
            $validate=0;
            $_SESSION['regist_failed'] = "Password must contains at least 1 number!";
        }else if (!preg_match('/[!@#$%^&*]/', $password)) {
            $validate=0;
            $_SESSION['regist_failed'] = "Password must contains at least 1 special character!";
        }
        
        $phone = intval($phone);
        if ($connection->error){
            echo $connection->error;
        }
        else {
            if($validate==1){
                $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows == 1){
                    $_SESSION['regist_failed'] ='Username has been taken!';
                    header("Location: ../register.php");
                }else if($confirm_pass != $password){
                    $_SESSION['regist_failed'] ='Password did not match!';
                    header("Location: ../register.php");
                }else{
                    $password = password_hash($password,PASSWORD_BCRYPT);
                    $stmt = $connection->prepare("INSERT INTO users VALUES (NULL,?,?,?,?,?,NOW());");
                    $stmt->bind_param("sssis", $name, $username, $email, $phone, $password);
                    $stmt->execute();
                    $connection->close();
                    $_SESSION['regist_successful'] = '<script>alert("Register Successful! Please Login!");</script>';
                    header("Location: ../login.php");
                }
            }else{
                header("Location: ../register.php");
            }
        }
    }
?>