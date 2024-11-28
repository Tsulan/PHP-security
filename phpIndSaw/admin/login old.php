<!-- before try caatch -->

<?php
session_start();
include '../config.php';


$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
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