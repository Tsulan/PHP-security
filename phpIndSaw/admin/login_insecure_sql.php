<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // $query = "SELECT * FROM users WHERE username = '$username'";
    $query = "SELECT * FROM users WHERE username = '" . $username . "'";
    // $query = "SELECT * FROM users WHERE username = \"$username\"";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            include 'logger.php';
            logAction("Вход в систему", "Роль: {$user['role']}, ID: {$user['id']}");

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Неправильный логин или пароль";
        }
    } else {
        $error = "Неправильный логин или пароль";
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Вход администратора</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.png">
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h1>Вход администратора</h1>
        <form method="post" action="login_insecure_sql.php">
            <label>Имя пользователя:</label>
            <input type="text" name="username"><br>
            <label>Пароль:</label>
            <input type="password" name="password"><br>
            <button type="submit">Войти</button>
        </form>
        <?php if (isset($error)) : ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>

</html>