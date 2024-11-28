<?php
session_start();
include '../config.php';
include 'logger.php';

$error = "";

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        
        if (empty($username)) {
            $error = "Введите имя пользователя.";
            logAction("Попытка входа без имени пользователя.");
            throw new Exception("Попытка входа без имени пользователя.");
        }

        if (empty($password)) {
            $error = "Введите пароль.";
            logAction("Попытка входа без пароля.", "Имя пользователя: {$username}");
            throw new Exception("Попытка входа без пароля.");
        }

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Ошибка подготовки запроса: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                logAction("Вход в систему", "Успешный вход: Роль: {$user['role']}, ID: {$user['id']}");
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Неправильный логин или пароль.";
                logAction("Неудачный вход", "Неправильный пароль для пользователя: {$username}");
            }
        } else {
            $error = "Неправильный логин или пароль.";
            logAction("Неудачный вход", "Пользователь с именем {$username} не найден.");
        }
    }
} catch (Exception $e) {
    logAction("Ошибка входа", $e->getMessage());
    $error = "Произошла ошибка при входе. Попробуйте снова.";
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
        <form method="post" action="login.php">
            <label>Имя пользователя:</label>
            <input type="text" name="username" required><br>
            <label>Пароль:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Войти</button>
        </form>
        <?php if (isset($error)) : ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>

</html>