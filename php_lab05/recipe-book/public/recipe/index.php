<?php
/**
 * Подключаем хелпер для загрузки рецептов
 */
require_once __DIR__ . '/../../src/helpers.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$pagination = getPaginatedRecipes($page);

$recipes = $pagination['recipes'];
$totalPages = $pagination['total_pages'];
$currentPage = $pagination['current_page'];

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

        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?= ($i === $currentPage) ? "<strong>$i</strong>" : "<a href='?page=$i'>$i</a>" ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <a href="/recipe/create.php">Добавить новый рецепт</a>
</body>
</html>
