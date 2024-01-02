<?php
session_start();

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function isRateLimited() {
    $maxAttempts = 3;
    $cooldownPeriod = 60;

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    if (!isset($_SESSION['last_login_attempt'])) {
        $_SESSION['last_login_attempt'] = 0;
    }

    $currentTime = time();
    $lastAttemptTime = $_SESSION['last_login_attempt'];

    if ($currentTime - $lastAttemptTime > $cooldownPeriod) {
        $_SESSION['login_attempts'] = 0;
    }

    if ($_SESSION['login_attempts'] >= $maxAttempts) {
        return true;
    }

    return false;
}

if (isRateLimited()) {
    $_SESSION['login_failed'] = "Login attempts exceeded. Please try again later.";
    header("Location: login.php");
    exit();
}

if (isset($_POST['login'])) {

    $_SESSION['login_attempts']++;

    $_SESSION['last_login_attempt'] = time();

    $loginSuccess = true;

    if ($loginSuccess) {
        $_SESSION['login_attempts'] = 0;
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['login_failed'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="controllers/loginc.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
        <div class="form-group">
            <label for="">Username</label>
            <input type="text" name="username">
        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password">
        </div>
        <button name="login">Login</button>
        <br>
        <a href="register.php" class="pull-right">Don't Have Account? Register First</a>
    </form>

    <?php
    if (isset($_SESSION['login_failed'])) {
        echo $_SESSION['login_failed'];
        unset($_SESSION['login_failed']);
    }
    ?>

</body>
</html>
