<?php
    function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    generateCSRFToken();
    session_start();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form action="controllers/registerc.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
    <div class="form-group">
        <label for="">Name</label>
        <input type="text" name="name">
    </div>
    <div class="form-group">
        <label for="">Email</label>
        <input type="email" name="email">
    </div>
    <div class="form-group">
        <label for="">Username</label>
        <input type="text" name="username">
    </div>
    <div class="form-group">
        <label for="">Phone number (code area + number (ex: 6212345678901))</label>
        <input type="number" name="phone">
    </div>
    <div class="form-group">
        <label for="">Password</label>
        <input type="password" name="password">
    </div>
    <div class="form-group">
        <label for="">Confirm Password</label>
        <input type="password" name="confirmpassword">
    </div>
    <button name="register">Register</button>
    <br>
    <a href="login.php" class="pull-right">Already Have Account? Login Now</a>
    </form>
</body>

<?php
if(isset($_SESSION['regist_failed'])) {
    echo $_SESSION['regist_failed'];
    unset($_SESSION['regist_failed']);
}
?>