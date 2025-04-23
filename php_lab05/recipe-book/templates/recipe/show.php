<?php ob_start(); 
$tags = is_array($recipe['tags']) ? $recipe['tags'] : explode(',', $recipe['tags'] ?? '');
// Получение категории по её ID
$categoryId = $recipe['category']; // Получаем ID категории из рецепта
$stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetchColumn(); // Получаем название категории
?>

<h2><?= htmlspecialchars($recipe['title']) ?></h2>

<p><strong>Категория:</strong> <br><?= htmlspecialchars($category) ?></p>
<p><strong>Ингредиенты:</strong> <br><?= nl2br(htmlspecialchars($recipe['ingredients'])) ?></p>
<p><strong>Описание:</strong> <br><?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
<p><strong>Теги:</strong> <br> <?= implode(', ', array_map('trim', $tags)) ?></p>
<p><strong>Дата создания:</strong> <br><?= htmlspecialchars($recipe['created_at']) ?></p>
<p><strong>Шаги:</strong></p>
<div style="text-align: center;">
    <ol style="display: inline-block; text-align: left;">
        <?php foreach ($recipe['steps'] as $step): ?>
            <li><?= htmlspecialchars($step) ?></li>
        <?php endforeach; ?>
    </ol>
</div>



<a href="/recipe-book/public/?action=edit&id=<?= $recipe['id'] ?>">Редактировать</a> |
<a href="/recipe-book/public/?action=delete&id=<?= $recipe['id'] ?>" onclick="return confirm('Удалить рецепт?')">Удалить</a>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
