<?php ob_start(); ?>

<h2>Редактировать рецепт</h2>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="/recipe-book/public/?action=edit&id=<?= $recipe['id'] ?>" method="post">
    <label>Название:
        <input type="text" name="title" value="<?= htmlspecialchars($recipe['title'] ?? '') ?>">
    </label><br>

    <label>Категория:
        <input type="text" name="category" value="<?= htmlspecialchars($recipe['category'] ?? '') ?>">
    </label><br>

    <label>Ингредиенты:
        <textarea name="ingredients"><?= htmlspecialchars($recipe['ingredients'] ?? '') ?></textarea>
    </label><br>

    <label>Описание:
        <textarea name="description"><?= htmlspecialchars($recipe['description'] ?? '') ?></textarea>
    </label><br>

    <label>Теги (через запятую):
        <input type="text" name="tags" value="<?= htmlspecialchars(implode(', ', $recipe['tags'] ?? [])) ?>">
    </label><br>

    <label>Шаги приготовления:</label><br>
    <?php for ($i = 0; $i < 5; $i++): ?>
        <textarea name="steps[]"><?= htmlspecialchars($recipe['steps'][$i] ?? '') ?></textarea><br>
    <?php endfor; ?>

    <button type="submit">Сохранить изменения</button>
</form>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layout.php'; ?>
