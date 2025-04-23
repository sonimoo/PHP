<?php

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../src/helpers.php';

function updateRecipe(PDO $pdo, $id, $data) {
    $title = $data['title'] ?? '';
    $category = $data['category'] ?? '';
    $ingredients = $data['ingredients'] ?? '';
    $description = $data['description'] ?? '';
    $tags = $data['tags'] ?? [];
    $steps = $data['steps'] ?? [];

    $errors = validateRecipe($title, $category, $ingredients, $description, $tags, $steps);

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE recipes SET title = ?, category = ?, ingredients = ?, description = ?, tags = ?, steps = ? WHERE id = ?");
        $stmt->execute([
            $title,
            $category,
            $ingredients,
            $description,
            implode(',', $tags), // Преобразуем массив в строку
            json_encode($steps),
            $id
        ]);
        return ['success' => true];
    }

    return ['errors' => $errors];
}


// Теперь можно работать с рецептом
$pdo = getPdoConnection();
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID рецепта не указан.";
    exit;
}

$recipe = getRecipeById($pdo, $id);

if (!$recipe) {
    echo "Рецепт не найден.";
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Всегда работаем с массивом тегов
    $tagsArray = array_filter(array_map('trim', explode(',', $_POST['tags'] ?? '')));
    $steps = array_filter($_POST['steps'] ?? [], fn($step) => trim($step) !== '');

    $data = [
        'title' => $_POST['title'] ?? '',
        'category' => $_POST['category'] ?? '',
        'ingredients' => $_POST['ingredients'] ?? '',
        'description' => $_POST['description'] ?? '',
        'tags' => $tagsArray, // передаём массив
        'steps' => $steps,
    ];

    $result = updateRecipe($pdo, $id, $data);

    if (!empty($result['success'])) {
        header("Location: /recipe-book/public/?action=show&id=$id");
        exit;
    }

    $errors = $result['errors'] ?? [];
    $recipe = array_merge($recipe, $data); // сохраняем введённые данные
}



include __DIR__ . '/../../../templates/recipe/edit.php';
