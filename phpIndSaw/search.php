<?php
session_start();
include 'config.php';
$search = '';
$searchType = 'content';
$result = null;

if (isset($_GET['search']) && isset($_GET['searchType'])) {
    $search = htmlspecialchars($_GET['search']);
    $searchType = htmlspecialchars($_GET['searchType']);

    if ($searchType == 'content') {
        $query = "SELECT * FROM feedback WHERE message LIKE ?";
    } else {
        $query = "SELECT * FROM feedback WHERE name LIKE ?";
    }

    $stmt = $conn->prepare($query);
    $searchParam = '%' . $search . '%';
    $stmt->bind_param('s', $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Поиск отзывов</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h1>Поиск отзывов</h1>
        <form method="get" action="search.php">
            <label for="searchInput">Поиск:</label>
            <input type="text" id="searchInput" name="search" oninput="checkInput()" value="<?php echo htmlspecialchars($search); ?>">
            <label for="searchType">Искать по:</label>
            <select id="searchType" name="searchType">
                <option value="content" <?php echo $searchType == 'content' ? 'selected' : ''; ?>>Содержимому отзыва</option>
                <option value="name" <?php echo $searchType == 'name' ? 'selected' : ''; ?>>Имени отправителя</option>
            </select>
            <button type="submit" id="searchButton">Поиск</button>
        </form>
        <ul>
            <?php if (isset($result)) : ?>
                <?php if ($result->num_rows > 0) : ?>
                    <?php $feedbackNumber = 1; ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <li>
                            <small><?php echo "Отзыв <b>№" . $feedbackNumber ?></b></small><br><br>
                            <b><?php echo htmlspecialchars($row['name']); ?></b>:
                            <?php echo htmlspecialchars($row['message']); ?><br>
                            <small><?php echo "<br>Отзыв создан: " . htmlspecialchars($row['created_at']); ?></small>
                            <br>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') : ?>
                                <form method="post" action="admin/manage_feedback.php" style="display:inline-block;">
                                    <input type="hidden" name="delete_feedback_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?');" id="deleteButton">Удалить</button>
                                </form>
                            <?php endif; ?>
                        </li>
                        <?php $feedbackNumber++; ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <li>Данные не найдены.</li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </div>
</body>

<script>
    function checkInput() {
        var input = document.getElementById("searchInput").value;
        var button = document.getElementById("searchButton");
        if (input.trim() === "") {
            button.disabled = true;
        } else {
            button.disabled = false;
        }
    }

    window.onload = function() {
        checkInput();
    }
</script>

</html>