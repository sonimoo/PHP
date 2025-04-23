<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Книга рецептов' ?></title>
    <link rel="stylesheet" href="/recipe-book/public/styles.css">
</head>
<body>
    <header>
        
        <h1>Книга рецептов</h1>
    </header>

    <main>
        <?= $content ?? '' ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Recipe Book</p>
    </footer>
</body>
</html>
