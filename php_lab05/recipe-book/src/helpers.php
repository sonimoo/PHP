<?php

/**
 * Проверяет и возвращает ошибки валидации рецепта
 */
function validateRecipe($title, $category, $ingredients, $description, $tags, $steps): array {
    $errors = [];

    if (empty($title)) {
        $errors['title'] = 'Введите название рецепта.';
    }

    if (empty($category)) {
        $errors['category'] = 'Выберите категорию.';
    }

    if (empty($ingredients)) {
        $errors['ingredients'] = 'Укажите ингредиенты.';
    }

    if (empty($description)) {
        $errors['description'] = 'Добавьте описание рецепта.';
    }

    if (empty($steps) || !is_array($steps) || count(array_filter($steps)) === 0) {
        $errors['steps'] = 'Добавьте хотя бы один шаг приготовления.';
    }

    if (!empty($tags) && is_string($tags)) {
        $tags = array_filter(array_map('trim', explode(',', $tags)));
    }

    if (empty($tags) || !is_array($tags)) {
        $errors['tags'] = 'Добавьте хотя бы один тег.';
    }

    return $errors;
}




/**
 * Получает рецепт по ID из базы данных
 */
function getRecipeById(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$id]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recipe) {
        
        if (isset($recipe['tags']) && is_string($recipe['tags'])) {
            $recipe['tags'] = array_filter(array_map('trim', explode(',', $recipe['tags'])));
        }
        

        if (isset($recipe['steps']) && is_string($recipe['steps'])) {
            $recipe['steps'] = json_decode($recipe['steps'], true);
        }        

        return $recipe;
    }

    return null;
}


