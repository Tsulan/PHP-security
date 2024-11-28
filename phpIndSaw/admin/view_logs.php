<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$logFile = '../logs/security.log';

if (!file_exists($logFile)) {
    $logs = "Лог-файл не найден.";
} else {
    $logs = file_get_contents($logFile);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Просмотр логов</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h1>Просмотр логов</h1>
        <pre style="background: #f4f4f4; padding: 10px; border: 1px solid #ddd; overflow: auto;">
            <?php echo htmlspecialchars($logs); ?>
        </pre>
    </div>
</body>

</html>
