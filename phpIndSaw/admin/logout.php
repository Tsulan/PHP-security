<?php
session_start();

include 'logger.php';
if (isset($_SESSION['username'])) {
    logAction("Выход из системы", "Пользователь: {$_SESSION['username']}");
}

session_destroy();
header("Location: login.php");
exit();
?>
