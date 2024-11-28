<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_feedback_id'])) {
    $delete_feedback_id = intval($_POST['delete_feedback_id']);
    $delete_query = "DELETE FROM feedback WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param('i', $delete_feedback_id);

    if ($stmt->execute()) {
        $message = "Отзыв успешно удален.";
    } else {
        $error = "Ошибка при удалении отзыва.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_action'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $feedback_message = htmlspecialchars($_POST['message']);

    if ($_POST['feedback_action'] === 'add') {
        if (!empty($name) && !empty($email) && !empty($feedback_message)) {
            $add_query = "INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($add_query);
            $stmt->bind_param("sss", $name, $email, $feedback_message);

            if ($stmt->execute()) {
                $message = "Отзыв добавлен.";
            } else {
                $error = "Ошибка при добавлении отзыва.";
            }

            $stmt->close();
        } else {
            $error = "Заполните все поля.";
        }
    } elseif ($_POST['feedback_action'] === 'edit' && isset($_POST['feedback_id'])) {
        $feedback_id = intval($_POST['feedback_id']);

        if (!empty($name) && !empty($email) && !empty($feedback_message)) {
            $update_query = "UPDATE feedback SET name = ?, email = ?, message = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssi", $name, $email, $feedback_message, $feedback_id);

            if ($stmt->execute()) {
                $message = "Отзыв обновлен.";
            } else {
                $error = "Ошибка при обновлении отзыва.";
            }

            $stmt->close();
        } else {
            $error = "Заполните все поля.";
        }
    }
}

$feedbacks = $conn->query("SELECT * FROM feedback");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Управление отзывами</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.png">
    <link rel="stylesheet" type="text/css" href="../styles.css">
</head>

<body>
    <?php include '../header.php'; ?>
    <div class="container">
        <h1>Управление отзывами</h1>

        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if (isset($error)) : ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <h2>Добавить новый отзыв</h2>
        <form method="post" action="manage_feedback.php">
            <input type="hidden" name="feedback_action" value="add">
            <label>Имя:</label>
            <input type="text" name="name"><br>
            <label>Email:</label>
            <input type="email" name="email"><br>
            <label>Сообщение:</label>
            <textarea name="message"></textarea><br>
            <button type="submit">Добавить</button>
        </form>

        <h2>Список отзывов</h2>
        <ul>
            <?php while ($row = $feedbacks->fetch_assoc()) : ?>
                <li>
                    <small><?php echo "id отзыва: <b>" . htmlspecialchars($row['id']) ?></b></small><br><br>
                    Отзыв:<br><b><?php echo htmlspecialchars($row['name']); ?></b>: <?php echo htmlspecialchars($row['message']); ?><br>
                    <small><?php echo "Емейл: " . htmlspecialchars($row['email']) ?></small><br>
                    <small><?php echo "Отзыв создан: " . htmlspecialchars($row['created_at']); ?></small><br>
                    <form method="post" action="manage_feedback.php" style="display:inline-block;">
                        <input type="hidden" name="delete_feedback_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?');" id="deleteButton">Удалить</button>
                    </form>

                    <br>

                    <h3>Обновить детали отзыва</h3>
                    <form method="post" action="manage_feedback.php">
                        <input type="hidden" name="feedback_action" value="edit">
                        <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                        <label>Имя:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                        <label>Емейл:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                        <label>Сообщение:</label>
                        <textarea name="message"><?php echo htmlspecialchars($row['message']); ?></textarea>
                        <button type="submit">Обновить</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>

</html>