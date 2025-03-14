<?php
session_start();
session_destroy();  
session_unset();    


if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}


header("Location: ../index.php");
exit();
?>
