<?php
function isActive($page)
{
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}
?>

<header>
    <h1>Сайт отзывов</h1>
    <nav>
        <ul>
            <li class="<?php echo isActive('index.php'); ?>"><a href="/phpIndSaw/index.php">Главная</a></li>
            <li class="<?php echo isActive('feedback_form.php'); ?>"><a href="/phpIndSaw/feedback_form.php">Оставить отзыв</a></li>
            <li class="<?php echo isActive('search.php'); ?>"><a href="/phpIndSaw/search.php">Поиск отзывов</a></li>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') : ?>
                <li class="<?php echo isActive('dashboard.php'); ?>"><a href="/phpIndSaw/admin/dashboard.php">Админ панель</a></li>
                <!-- Новая кнопка для просмотра логов -->
                <li class="<?php echo isActive('view_logs.php'); ?>"><a href="/phpIndSaw/admin/view_logs.php">Просмотр логов</a></li>
                <li><a href="/phpIndSaw/admin/logout.php">Выход</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>