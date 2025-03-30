<?php
/**
 * Подключение обработчика рецептов.
 */
require_once __DIR__ . '/../../src/handlers/recipe-handler.php';

$errors = [];

/**
 * Проверка отправки формы и обработка данных.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /** @var array $result Результат обработки формы */
    $result = handleRecipeSubmission($_POST);

    if (isset($result['success']) && $result['success'] === true) {
        // Перенаправление на главную страницу после успешного сохранения
        header('Location: /');
        exit;
    } else {
        // Сохранение ошибок для отображения
        $errors = $result['errors'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles.css">
    <title>Добавить рецепт</title>
</head>
<body>
    <h1>Добавить рецепт</h1>

    <form action="" method="POST">
        <div>
            <label for="title">Название рецепта:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            <?php if (isset($errors['title'])): ?>
                <p style="color:red"><?= $errors['title'] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="category">Категория:</label>
            <select name="category" id="category">
                <option value="">Выберите категорию</option>
                <option value="soup" <?= isset($_POST['category']) && $_POST['category'] === 'soup' ? 'selected' : '' ?>>Супы</option>
                <option value="salad" <?= isset($_POST['category']) && $_POST['category'] === 'salad' ? 'selected' : '' ?>>Салаты</option>
                <option value="dessert" <?= isset($_POST['category']) && $_POST['category'] === 'dessert' ? 'selected' : '' ?>>Десерты</option>
                <option value="main_course" <?= isset($_POST['category']) && $_POST['category'] === 'main_course' ? 'selected' : '' ?>>Основные блюда</option>
                <option value="appetizer" <?= isset($_POST['category']) && $_POST['category'] === 'appetizer' ? 'selected' : '' ?>>Закуски</option>
                <option value="beverage" <?= isset($_POST['category']) && $_POST['category'] === 'beverage' ? 'selected' : '' ?>>Напитки</option>
                <option value="bakery" <?= isset($_POST['category']) && $_POST['category'] === 'bakery' ? 'selected' : '' ?>>Выпечка</option>
                <option value="seafood" <?= isset($_POST['category']) && $_POST['category'] === 'seafood' ? 'selected' : '' ?>>Морепродукты</option>
            </select>
            <?php if (isset($errors['category'])): ?>
                <p style="color:red"><?= $errors['category'] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="ingredients">Ингредиенты:</label>
            <textarea name="ingredients" id="ingredients"><?= htmlspecialchars($_POST['ingredients'] ?? '') ?></textarea>
            <?php if (isset($errors['ingredients'])): ?>
                <p style="color:red"><?= $errors['ingredients'] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="description">Описание:</label>
            <textarea name="description" id="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            <?php if (isset($errors['description'])): ?>
                <p style="color:red"><?= $errors['description'] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="tags">Тэги:</label>
            <select name="tags[]" id="tags" multiple>
                <option value="vegan">Веган</option>
                <option value="gluten_free">Без глютена</option>
                <option value="quick">Быстро</option>
                <option value="low_carb">Низкоуглеводное</option>
                <option value="high_protein">Белковое</option>
                <option value="dairy_free">Без молочных продуктов</option>
                <option value="nut_free">Без орехов</option>
                <option value="spicy">Острое</option>
                <option value="low_fat">Низкожирное</option>
                <option value="sugar_free">Без сахара</option>
            </select>
            <?php if (isset($errors['tags'])): ?>
                <p style="color:red"><?= $errors['tags'] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="steps">Шаги приготовления:</label>
            <div id="steps">
                <div>
                    <input type="text" name="steps[]" value="">
                </div>
            </div>
            <button type="button" id="addStep">Добавить шаг</button>
        </div>

        <div>
            <button type="submit">Сохранить рецепт</button>
        </div>
    </form>

    <script>
        document.getElementById('addStep').addEventListener('click', function() {
            let stepsDiv = document.getElementById('steps');
            let newStep = document.createElement('div');
            newStep.innerHTML = '<input type="text" name="steps[]" value="">';
            stepsDiv.appendChild(newStep);
        });
    </script>
</body>
</html>
