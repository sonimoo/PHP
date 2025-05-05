<?php
require_once '../includes/auth.php';
checkAuth('admin');

ob_start();
?>

<h2 class="centered">👩‍🏫 Панель администратора</h2>

<div class="admin-panel">
    <h3>Управление панелью</h3>
    <ul class="admin-links">
        <li><a href="manage_homework.php" class="admin-link">Управление заданиями</a></li>
        <li><a href="add_user.php" class="admin-link">Создание пользователей</a></li>
        <li><a href="check_homework.php" class="admin-link">Проверка заданий</a></li>
    </ul>
</div>


<p class="centered"><a href="../public/logout.php" class="logout-link">🚪 Выйти</a></p>

<?php
$content = ob_get_clean();
$title = 'Панель администратора';
include '../includes/layout.php';
