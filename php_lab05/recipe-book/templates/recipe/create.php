<?php
ob_start();

// Получаем список категорий для выпадающего списка
$categoryStmt = $pdo->query('SELECT id, name FROM categories');
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Добавить рецепт</h2>



<form action="/recipe-book/public/?action=create" method="post">
    <label for="title">Название:</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($data['title'] ?? '') ?>">
    <?php if (!empty($errors['title'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['title']) ?></p>
    <?php endif; ?>
    <br>

    <label for="category">Категория:</label>
    <select name="category" id="category">
        <option value="">-- выберите категорию --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($data['category'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['category'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['category']) ?></p>
    <?php endif; ?>
    <br>

    <label for="ingredients">Ингредиенты:</label>
    <textarea name="ingredients" id="ingredients"><?= htmlspecialchars($data['ingredients'] ?? '') ?></textarea>
    <?php if (!empty($errors['ingredients'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['ingredients']) ?></p>
    <?php endif; ?>
    <br>

    <label for="description">Описание:</label>
    <textarea name="description" id="description"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
    <?php if (!empty($errors['description'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['description']) ?></p>
    <?php endif; ?>
    <br>

    <label for="tags">Теги (через запятую):</label>
    <input type="text" name="tags" id="tags" value="<?= htmlspecialchars(implode(', ', $data['tags'] ?? [])) ?>">
    <?php if (!empty($errors['tags'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['tags']) ?></p>
    <?php endif; ?>
    <br>

    <label>Шаги приготовления:</label><br>
    <div id="steps">
        <?php
        $stepData = $data['steps'] ?? [''];
        foreach ($stepData as $i => $stepText):
        ?>
            <textarea name="steps[]" placeholder="Введите шаг приготовления <?= $i + 1 ?>"><?= htmlspecialchars($stepText) ?></textarea><br>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($errors['steps'])): ?>
        <p style="color:red"><?= htmlspecialchars($errors['steps']) ?></p>
    <?php endif; ?>

    <button type="button" onclick="addStep()">Добавить шаг</button><br><br>

    <button type="submit">Сохранить</button>
</form>

<script>
    let stepCount = <?= count($stepData) ?>;

    function addStep() {
        stepCount++;
        const newStep = document.createElement('textarea');
        newStep.name = 'steps[]';
        newStep.placeholder = `Введите шаг приготовления ${stepCount}`;
        document.getElementById('steps').appendChild(newStep);
        document.getElementById('steps').appendChild(document.createElement('br'));
    }
</script>


<script>
    let stepCount = 1;

    function addStep() {
        stepCount++;
        const newStep = document.createElement('textarea');
        newStep.name = 'steps[]';
        newStep.placeholder = `Введите шаг приготовления ${stepCount}`;
        document.getElementById('steps').appendChild(newStep);
    }
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
