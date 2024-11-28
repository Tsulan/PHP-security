<?php
function logAction($action, $details = "")
{
    $logFile = '../logs/security.log';

    $timestamp = date('Y-m-d H:i:s');

    session_start();
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Гость';

    $logMessage = "[$timestamp] Пользователь: $user | Действие: $action | Детали: $details" . PHP_EOL;

    if (!file_exists(dirname($logFile))) {
        mkdir(dirname($logFile), 0777, true);
    }

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}
?>
