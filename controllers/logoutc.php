<?php
    session_start();

    if($_SESSION['is_admin'] === true || $_SESSION['is_login'] === true){
        session_unset();
        session_destroy();
        header("Location: ../index.php");
    }else{
        header("Location: ../index.php");
    }