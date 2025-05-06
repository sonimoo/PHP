<?php
/**
 * Основной шаблон сайта (layout.php)
 * 
 * Содержит общую разметку HTML, шапку, подвал и вывод контента.
 */

$days = [
    'Monday' => 'Понедельник',
    'Tuesday' => 'Вторник',
    'Wednesday' => 'Среда',
    'Thursday' => 'Четверг',
    'Friday' => 'Пятница',
    'Saturday' => 'Суббота',
    'Sunday' => 'Воскресенье'
];
$today = date('d.m.Y');
$weekday_en = date('l');
$weekday_ru = $days[$weekday_en];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'LessonManager') ?></title>
    <link rel="stylesheet" href="/LessonManager/public/assets/css/style.css">
</head>
<body>
    <header>
        <h1>📘 LessonManager</h1>
        <p style="margin: 5px 0;">Сегодня <?= $today ?>, <?= $weekday_ru ?>.</p>
        <nav>
            <a href="/LessonManager/public/index.php">Главная</a>
            <a href="/LessonManager/public/dashboard.php">Панель задач</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/LessonManager/public/logout.php">🚪 Выйти</a>
            <?php else: ?>
                <a href="/LessonManager/public/login.php">🔐 Войти</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>© <?= date('Y') ?> LessonManager — Учимся с радостью!</p>
    </footer>
</body>
</html>
