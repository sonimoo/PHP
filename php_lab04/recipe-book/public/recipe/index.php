<?php
/**
 * Подключаем хелпер для загрузки рецептов
 */
require_once __DIR__ . '/../../src/helpers.php';

/**
 * Загружаем все рецепты из файла
 * 
 * @var array $recipes Массив рецептов
 */
$recipes = loadRecipes();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles.css">
    <title>Все рецепты</title>
</head>
<body>
    <h1>Все рецепты</h1>

    <?php if (empty($recipes)): ?>
        <p>Нет рецептов для отображения.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($recipes as $recipe): ?>
                <li>
                    <h2><?= htmlspecialchars($recipe['title']) ?></h2>
                    <p><strong>Категория:</strong> <?= htmlspecialchars($recipe['category']) ?></p>
                    <p><strong>Ингредиенты:</strong> <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
                    <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
                    <p><strong>Тэги:</strong> <?= implode(', ', array_map('htmlspecialchars', $recipe['tags'])) ?></p>
                    <p><strong>Дата:</strong> <?= htmlspecialchars($recipe['created_at']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="/recipe/create.php">Добавить новый рецепт</a>
</body>
</html>
