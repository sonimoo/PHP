<?php
/**
 * Подключаем хелпер для работы с рецептами
 */
require_once __DIR__ . '/../../src/helpers.php';

/**
 * Обрабатывает отправку формы рецепта
 *
 * @param array $data Данные из POST-запроса
 * @return array Результат обработки (успех или массив ошибок)
 */
function handleRecipeSubmission($data) {
    // Получаем данные из POST-запроса
    $title = $data['title'] ?? '';
    $category = $data['category'] ?? '';
    $ingredients = $data['ingredients'] ?? '';
    $description = $data['description'] ?? '';
    $tags = $data['tags'] ?? [];
    $steps = $data['steps'] ?? [];

    /**
     * Выполняем валидацию данных
     * 
     * @var array $errors Массив ошибок валидации
     */
    $errors = validateRecipe($title, $category, $ingredients, $description, $tags, $steps);

    // Если ошибок нет, сохраняем рецепт
    if (empty($errors)) {
        saveRecipe($title, $category, $ingredients, $description, $tags, $steps);
        return ['success' => true];
    }

    return ['errors' => $errors];
}
