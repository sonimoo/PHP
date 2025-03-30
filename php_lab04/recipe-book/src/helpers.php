<?php

/**
 * Загружает все рецепты из файла.
 * 
 * Читает файл с рецептами и преобразует каждую строку (JSON) в ассоциативный массив.
 *
 * @return array Массив рецептов (каждый рецепт — ассоциативный массив).
 */
function loadRecipes() {
    $file = __DIR__ . '/../storage/recipes.txt';
    if (!file_exists($file)) {
        return [];
    }
    $recipes = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map(fn($line) => json_decode($line, true), $recipes);
}

/**
 * Сохраняет рецепт в файл.
 * 
 * Преобразует данные рецепта в формат JSON и добавляет их в файл.
 * Все данные (теги, шаги, ингредиенты и описание) проходят очистку через htmlspecialchars
 * для предотвращения XSS-атак.
 *
 * @param string $title Название рецепта.
 * @param string $category Категория рецепта.
 * @param string $ingredients Ингредиенты рецепта.
 * @param string $description Описание рецепта.
 * @param array $tags Массив тегов, связанных с рецептом.
 * @param array $steps Массив шагов приготовления рецепта.
 * 
 * @return void
 */
function saveRecipe($title, $category, $ingredients, $description, $tags, $steps) {
    $file = __DIR__ . '/../storage/recipes.txt';

    // Применение htmlspecialchars для очистки данных
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
    $ingredients = htmlspecialchars($ingredients, ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    $tags = array_map(fn($tag) => htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'), $tags);
    $steps = array_map(fn($step) => htmlspecialchars($step, ENT_QUOTES, 'UTF-8'), $steps);

    $recipe = json_encode([
        'title' => $title,
        'category' => $category,
        'ingredients' => $ingredients,
        'description' => $description,
        'tags' => $tags,
        'steps' => $steps,
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    file_put_contents($file, $recipe . "\n", FILE_APPEND | LOCK_EX);
}

/**
 * Валидирует данные рецепта.
 * 
 * Проверяет обязательные поля, такие как название, категория, ингредиенты, описание, теги и шаги.
 * Если какое-либо поле пустое, добавляется соответствующее сообщение об ошибке в массив ошибок.
 *
 * @param string $title Название рецепта.
 * @param string $category Категория рецепта.
 * @param string $ingredients Ингредиенты рецепта.
 * @param string $description Описание рецепта.
 * @param array $tags Массив тегов, связанных с рецептом.
 * @param array $steps Массив шагов приготовления рецепта.
 * 
 * @return array Массив ошибок (ключ — название поля, значение — сообщение об ошибке).
 */
function validateRecipe($title, $category, $ingredients, $description, $tags, $steps) {
    $errors = [];
    if (empty($title)) $errors['title'] = 'Название рецепта обязательно';
    if (empty($category)) $errors['category'] = 'Выберите категорию';
    if (empty($ingredients)) $errors['ingredients'] = 'Введите ингредиенты';
    if (empty($description)) $errors['description'] = 'Введите описание';
    if (empty($tags)) $errors['tags'] = 'Выберите хотя бы один тег';
    if (empty($steps)) $errors['steps'] = 'Добавьте хотя бы один шаг приготовления';
    return $errors;
}
