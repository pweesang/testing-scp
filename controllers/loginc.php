<?php
    session_start();
    require_once(__DIR__ . '/connection.php');

    $maxLoginAttempts = 5;
    if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $maxLoginAttempts) {
        $_SESSION['login_failed'] = "Too many failed login attempts. Please try again later.";
    }
    function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && validateCSRFToken($_POST['csrf_token'])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $validate = 1;

        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        
        if(strlen($username)==0){
            $validate=0;
            $_SESSION['login_failed']='Email cannot be empty!';
        }else if(strlen($password)==0){
            $validate=0;
            $_SESSION['login_failed']='Password Cannot be Empty!';
        }
        if ($connection->error){
            echo $connection->error;
        }
        else{
            if($validate==1){
                $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $connection->close();
                if($result->num_rows == 1){
                    $dataresult = $result->fetch_assoc();
                    if($username === 'admin' && password_verify($password,$dataresult["password"])){
                        unset($_SESSION['login_attempts']);
                        $_SESSION["is_admin"] = true;
                        $_SESSION["is_login"] = true;
                        $_SESSION["user_id"] = $dataresult["user_id"];
                        $_SESSION["name"] = $dataresult["name"];
                        $_SESSION["username"] = $dataresult["username"];
                        $_SESSION["email"] = $dataresult["email"];
                        $_SESSION["phone_number"] = $dataresult["phone_number"];
                        $_SESSION["password"] = $dataresult["password"];
                        header("Location: ../admin.php");
                    }else if(password_verify($password,$dataresult["password"])){
                        unset($_SESSION['login_attempts']);
                        $_SESSION["is_login"] = true;
                        $_SESSION["user_id"] = $dataresult["user_id"];
                        $_SESSION["name"] = $dataresult["name"];
                        $_SESSION["username"] = $dataresult["username"];
                        $_SESSION["email"] = $dataresult["email"];
                        $_SESSION["phone_number"] = $dataresult["phone_number"];
                        $_SESSION["password"] = $dataresult["password"];
                        $_SESSION["loggedin"] = "Welcome $username!";
                        header("Location: ../index.php");
                    }else{
                        if (isset($_SESSION['login_attempts'])) {
                            $_SESSION['login_attempts']++;
                        } else {
                            $_SESSION['login_attempts'] = 1;
                        }
                        $_SESSION['login_failed'] ='Invalid Username or Password!';
                        header("Location: ../login.php");
                    }
                }else{
                    if (isset($_SESSION['login_attempts'])) {
                        $_SESSION['login_attempts']++;
                    } else {
                        $_SESSION['login_attempts'] = 1;
                    }
                    $_SESSION['login_failed'] ='Invalid Username or Password!';
                    header("Location: ../login.php");
                }
            }else{
                header("Location: ../login.php");
            }
        }
    }
?>