<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/helpers.php';

if (!isset($pdo)) {
    echo "Подключение к базе данных не установлено!";
    exit;
}

$action = $_GET['action'] ?? 'home';
$data = $_POST ?? [];

switch ($action) {
    case 'create':
        require_once __DIR__ . '/../src/handlers/recipe/create.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = createRecipe($pdo, $data);

            if (!empty($result['success'])) {
                header('Location: /recipe-book/public/');
                exit;
            }

            $errors = $result['errors'] ?? [];
            $data = $result['data'] ?? [];
        } else {
            $data = []; // при GET – пустая форма
            $errors = [];
        }

        include __DIR__ . '/../templates/recipe/create.php';
        break;

        case 'edit':
            require_once __DIR__ . '/../src/handlers/recipe/edit.php';
            break;
        
        $recipe = getRecipeById($pdo, $id);
        include __DIR__ . '/../templates/recipe/edit.php';
        break;

    case 'show':
        require_once __DIR__ . '/../src/helpers.php'; 
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "ID рецепта не указан.";
            exit;
        }

        $recipe = getRecipeById($pdo, $id);
        if (!$recipe) {
            http_response_code(404);
            echo "Рецепт не найден.";
            exit;
        }   

        include __DIR__ . '/../templates/recipe/show.php';
        break;

    case 'delete':
        require_once __DIR__ . '/../src/handlers/recipe/delete.php';
        $id = $_GET['id'] ?? null;

        if ($id) {
            deleteRecipe($pdo, $id);
        }

        header('Location: /recipe-book/public/');
        exit;

        case 'home':
            // Пагинация
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = 5;
            $offset = ($page - 1) * $limit;
        
            // Подготовленное выражение с вопросами
            $stmt = $pdo->prepare("SELECT * FROM recipes ORDER BY id DESC LIMIT ? OFFSET ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Считаем общее количество рецептов
            $countStmt = $pdo->query("SELECT COUNT(*) FROM recipes");
            $totalRecipes = (int) $countStmt->fetchColumn();
            $totalPages = ceil($totalRecipes / $limit);
        
            include __DIR__ . '/../templates/index.php';
            break;
        

    default:
        http_response_code(404);
        $title = 'Страница не найдена';
        ob_start();
        echo "<h2>404 — Страница не найдена</h2>";
        $content = ob_get_clean();
        include __DIR__ . '/../templates/layout.php';
        break;
}
