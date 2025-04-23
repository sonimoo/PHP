<?php ob_start(); ?>

<a href="/recipe-book/public">Все рецепты</a>

<a href="/recipe-book/public/?action=create">Добавить рецепт</a>

<?php if (empty($recipes)): ?>
    <p>Рецептов пока нет.</p>
<?php else: ?>
    <ul>
        <?php foreach ($recipes as $recipe): ?>
            <li>
                <a href="/recipe-book/public/?action=show&id=<?= $recipe['id'] ?>">
                    <?= htmlspecialchars($recipe['title']) ?>
                </a> <br>
                (<?= htmlspecialchars($recipe['tags']) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layout.php'; ?>

<!-- Пагинация -->
<?php if ($totalPages > 1): ?>
    <nav class="pagination">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="?page=<?= $p ?>" <?= $p == $page ? 'style="font-weight:bold;"' : '' ?>>
                <?= $p ?>
            </a>
        <?php endfor; ?>
    </nav>
<?php endif; ?>
