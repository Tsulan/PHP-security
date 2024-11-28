<?php
session_start();
include 'config.php';

$query = "SELECT * FROM feedback ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($query);

// $new_password = password_hash('admin', PASSWORD_DEFAULT);
// $query = "UPDATE users SET password = ? WHERE username = 'admin'";
// $stmt = $conn->prepare($query);
// $stmt->bind_param('s', $new_password);
// $stmt->execute();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Главная страница</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Добро пожаловать</h1>
        <h2>Последние отзывы</h2>
        <ul>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <li>
                    <strong><?php echo htmlspecialchars($row['name']); ?></strong>:
                    <?php echo htmlspecialchars($row['message']); ?><br><br>
                    <small><?php echo "Отзыв создан: " . htmlspecialchars($row['created_at']); ?></small>
                    <br>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') : ?>
                        <form method="post" action="admin/manage_feedback.php" style="display:inline-block;">
                            <input type="hidden" name="delete_feedback_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?');" id="deleteButton">Удалить</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>

</html>