<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user_id'])) {
        $delete_user_id = intval($_POST['delete_user_id']);
        $delete_query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('i', $delete_user_id);

        if ($stmt->execute()) {
            $message = "Пользователь успешно удален.";
        } else {
            $error = "Ошибка при удалении пользователя.";
        }
    } else {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        $role = htmlspecialchars($_POST['role']);

        if (!empty($username) && !empty($password)) {
            $passwordHashed = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $passwordHashed, $role);
            if ($stmt->execute()) {
                $message = "Пользователь добавлен.";
            } else {
                $error = "Ошибка при добавлении пользователя.";
            }
            $stmt->close();
        } else {
            $error = "Заполните все поля.";
        }
    }
}

$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Управление пользователями</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.png">
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h1>Управление пользователями</h1>

        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <h2>Добавить нового пользователя</h2>
        <form method="post" action="manage_users.php">
            <label>Имя пользователя:</label>
            <input type="text" name="username"><br>
            <label>Пароль:</label>
            <input type="password" name="password"><br>
            <label>Роль:</label>
            <select name="role">
                <option value="admin">Администратор</option>
                <option value="user" selected>Пользователь</option>
            </select>
            <br>
            <button type="submit">Добавить пользователя</button>
        </form>

        <h2>Список пользователей</h2>
        <ul>
            <?php while ($row = $users->fetch_assoc()) : ?>
                <li>
                    <small><?php echo "id пользователя: <b>" . htmlspecialchars($row['id']) ?></b></small><br><br>
                    Имя пользователя: <?php echo htmlspecialchars($row['username']); ?><br>
                    Роль: <?php echo htmlspecialchars($row['role']); ?><br><br>
                    <form method="post" action="manage_users.php" onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');" style="display:inline-block;">
                        <input type="hidden" name="delete_user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" id="deleteButton">Удалить</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>

</html>