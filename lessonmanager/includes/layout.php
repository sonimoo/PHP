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
