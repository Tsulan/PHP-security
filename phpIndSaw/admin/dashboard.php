<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Панель администратора</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.png">
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h1>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <nav>
            <ul>
                <li><a href="manage_users.php">Управление пользователями</a></li>
                <li><a href="manage_feedback.php">Управление отзывами</a></li>
                <li><a href="logout.php">Выход</a></li>
            </ul>
        </nav>
    </div>
</body>

</html>