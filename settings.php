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

if ($connection->error) {
    die($connection->error);
}

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Settings</title>
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
    </style>
</head>
<body>
    <header>
    <?php if($_SESSION['is_admin'] === true){ ?>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about-us.php">About</a></li>
                <li><a href="admin.php">Admin Panel</a></li>
                <div id='login'><a href='controllers/logoutc.php'>Logout</a></div>
            </ul>
            <?php }else{ ?>
                <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about-us.php">About</a></li>
                <li><a href="report.php">Report</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="search.php">Search</a></li>
                <li><a href="controllers/logoutc.php">Logout</a></li>
            </ul>
            <?php } ?>
    </header>
    
    <h1>Profile Settings</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
        <label for="new_username">New Username:</label>
        <input type="text" name="new_username" id="new_username">
        <br>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password">
        <br>
        <label for="new_email">New Email:</label>
        <input type="email" name="new_email" id="new_email">
        <br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

function updateEmail($user_id, $email, $new_email, $connection) {
    if (strlen($new_email) == 0){
        return;
    }else if($new_email === $email){
        return "Email can't be the same!";
    }else if(!filter_var($new_email, FILTER_VALIDATE_EMAIL)){
        return 'Email is not valid!';
    }
    $update_email_sql = $connection->prepare("UPDATE users SET email = ? WHERE user_id = ?");
    $update_email_sql->bind_param("si", $new_email, $user_id);
    if ($update_email_sql->execute()) {
        $update_email_sql->close();
        $_SESSION['email'] = $new_email;
        return "Email berhasil diubah.";
    } else {
        return "Gagal mengubah email.";
    }
}

function gantiUsername($user_id, $username, $new_username, $connection) {
    if (strlen($new_username) == 0){
        return;
    }else if($new_username === $username){
        return "Username can't be the same!";
    }
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $new_username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1){
        return "Username has been taken! ";
    }else if(strlen($new_username)<5 || strlen($new_username)>15){
        return "Username length must be 5-15 characters!";
    }
    $update_username_sql = $connection->prepare("UPDATE users SET username = ? WHERE user_id = ?");
    $update_username_sql->bind_param("si", $new_username, $user_id);
    if ($update_username_sql->execute()) {
        $update_username_sql->close();
        $_SESSION['username'] = $new_username;
        return "Username berhasil diubah. ";
    } else {
        return "Gagal mengubah username. ";
    }
}

function gantiPassword($user_id, $password, $new_password, $connection) {
    if (strlen($new_password) == 0){
        return;
    }else if(password_verify($new_password, $password)){
        return "Password can't be the same!";
    }else if(strlen($new_password)<8){
        return 'Password length must be atleast 8 characters!';
    }else if (!preg_match('/[A-Z]/', $new_password)) {
        return "Password must contains at least 1 capital character!";
    }else if (!preg_match('/[a-z]/', $new_password)) {
        return "Password must contains at least 1 small character!";
    }else if (!preg_match('/[0-9]/', $new_password)) {
        return "Password must contains at least 1 number!";
    }else if (!preg_match('/[!@#$%^&*]/', $new_password)) {
        return "Password must contains at least 1 special character!";
    }
    $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
    $update_password_sql = $connection->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $update_password_sql->bind_param("si", $new_password_hash, $user_id);
    if ($update_password_sql->execute()) {
        $update_password_sql->close();
        $_SESSION['password'] = $new_password_hash;
        return "Password berhasil diubah. ";
    } else {
        return "Gagal mengubah password. ";
    }
}
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && validateCSRFToken($_POST['csrf_token'])){
    $pesan = gantiUsername($user_id, $username, htmlspecialchars($_POST['new_username'], ENT_QUOTES, 'UTF-8'), $connection);
    echo $pesan;

    $new_password = $_POST['new_password'];
    $pesan = gantiPassword($user_id, $password, $new_password, $connection);
    echo $pesan;

    $new_email = htmlspecialchars($_POST['new_email'], ENT_QUOTES, 'UTF-8');
    $pesan = updateEmail($user_id, $email, $new_email, $connection);
    echo $pesan;

    $user_id = $_SESSION['user_id'];
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    echo "<br>";
}

echo "Current Email: " . $email . "<br>";
echo "Current Username: " . $username . "<br>";
?>