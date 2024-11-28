<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        $stmt->execute();
        $stmt->close();
        echo "Ваш отзыв отправлен!";
    } else {
        echo '<span style="color:red;">Пожалуйста, заполните все поля.</span>';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Форма обратной связи</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Оставить отзыв</h1>
        <form method="post" action="feedback_form.php">
            <label>Имя:</label>
            <input type="text" name="name"><br>
            <label>Email:</label>
            <input type="email" name="email"><br>
            <label>Сообщение:</label>
            <textarea name="message"></textarea><br>
            <button type="submit">Отправить</button>
        </form>
    </div>
</body>

</html>