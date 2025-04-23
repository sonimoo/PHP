<?php

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../src/helpers.php';

function createRecipe(PDO $pdo, array $postData): array {
    $title = trim($postData['title'] ?? '');
    $category = trim($postData['category'] ?? '');
    $ingredients = trim($postData['ingredients'] ?? '');
    $description = trim($postData['description'] ?? '');
    $tagsInput = $postData['tags'] ?? '';
    $stepsInput = $postData['steps'] ?? [];

    // Преобразуем строку тегов в массив
    $tags = array_filter(array_map('trim', explode(',', $tagsInput)));
    // Очищаем и фильтруем шаги
    $steps = array_values(array_filter(array_map('trim', $stepsInput), fn($s) => $s !== ''));

    $errors = validateRecipe($title, $category, $ingredients, $description, $tags, $steps);

    if (!empty($errors)) {
        return [
            'errors' => $errors,
            'data' => [
                'title' => $title,
                'category' => $category,
                'ingredients' => $ingredients,
                'description' => $description,
                'tags' => $tags,
                'steps' => $steps,
            ]
        ];
    }

    $stmt = $pdo->prepare('
        INSERT INTO recipes (title, category, ingredients, description, tags, steps, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ');

    $stmt->execute([
        $title,
        $category,
        $ingredients,
        $description,
        json_encode($tags, JSON_UNESCAPED_UNICODE),
        json_encode($steps, JSON_UNESCAPED_UNICODE),
    ]);

    return ['success' => true];
}
