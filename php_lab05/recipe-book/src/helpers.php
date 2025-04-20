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

function validateRecipe($title, $category, $ingredients, $description, $tags, $steps) {
    $errors = [];

    if (trim($title) === '') {
        $errors['title'] = 'Название обязательно';
    }

    if (trim($category) === '') {
        $errors['category'] = 'Категория обязательна';
    }

    if (trim($ingredients) === '') {
        $errors['ingredients'] = 'Ингредиенты обязательны';
    }

    if (trim($description) === '') {
        $errors['description'] = 'Описание обязательно';
    }

    if (!is_array($tags) || count($tags) === 0) {
        $errors['tags'] = 'Выберите хотя бы один тэг';
    }

    if (!is_array($steps) || count(array_filter($steps, fn($s) => trim($s) !== '')) === 0) {
        $errors['steps'] = 'Добавьте хотя бы один шаг приготовления';
    }

    return $errors;
}


/**
 * Возвращает список рецептов с пагинацией.
 *
 * @param int $page Текущий номер страницы (начиная с 1).
 * @param int $perPage Количество рецептов на странице (по умолчанию 5).
 *
 * @return array Ассоциативный массив с ключами:
 *   - 'recipes' => array список рецептов на текущей странице,
 *   - 'total_pages' => int общее количество страниц,
 *   - 'current_page' => int скорректированный текущий номер страницы.
 */

function getPaginatedRecipes($page, $perPage = 5) {
    $allRecipes = loadRecipes();
    $totalRecipes = count($allRecipes);
    $totalPages = max(1, ceil($totalRecipes / $perPage));

    // Ограничиваем номер страницы диапазоном [1, totalPages]
    $page = max(1, min($page, $totalPages));

    $offset = ($page - 1) * $perPage;
    $recipes = array_slice($allRecipes, $offset, $perPage);

    return [
        'recipes' => $recipes,
        'total_pages' => $totalPages,
        'current_page' => $page
    ];
}

