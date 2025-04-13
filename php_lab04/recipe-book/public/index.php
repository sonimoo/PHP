<?php
/**
 * Подключаем хелпер для загрузки рецептов
 */
require_once __DIR__ . '/../src/helpers.php';

/**
 * Загружаем все рецепты из файла
 * 
 * @var array $recipes Массив рецептов
 */
$recipes = loadRecipes();

/**
 * Получаем два последних рецепта
 * 
 * @var array $latestRecipes Два последних рецепта
 */
$latestRecipes = array_slice($recipes, -2);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles.css">
    <title>Последние рецепты</title>
</head>
<body>
    <h1>Последние рецепты</h1>

    <?php if (empty($latestRecipes)): ?>
        <p>Пока нет рецептов.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($latestRecipes as $recipe): ?>
                <li>
                    <h2><?= htmlspecialchars($recipe['title']) ?></h2>
                    <p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category']) ?></p>
                    <p><strong>Ингредиенты:</strong> <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
                    <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
                    <p><strong>Тэги:</strong> <?= implode(', ', array_map('htmlspecialchars', $recipe['tags'])) ?></p>
                    <p><strong>Шаги приготовления:</strong></p>
                        <?php foreach ($recipe['steps'] as $step): ?>
                            <p><?= htmlspecialchars($step) ?></p> <!-- Просто абзац для каждого шага -->
                        <?php endforeach; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="/recipe/index.php">Посмотреть все рецепты</a>
    <a href="/recipe/create.php">Добавить новый рецепт</a>
</body>
</html>
