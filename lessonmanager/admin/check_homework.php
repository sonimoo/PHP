<?php
/**
 * Файл проверки и оценки заданий (admin/grade_submissions.php)
 *
 * Этот скрипт позволяет администратору:
 * - Просматривать отправленные учениками задания
 * - Оценивать работы (от 0 до 10 баллов)
 * - Искать работы по имени ученика
 */

require_once '../includes/auth.php';
require_once '../includes/db.php';

// Проверка аутентификации пользователя с ролью 'admin'
checkAuth('admin');

/**
 * @var string $message Сообщение о результате операции (успех/ошибка)
 */
$message = '';

/**
 * Обрабатывает POST-запрос для сохранения оценки
 * 
 * Проверяет:
 * - Наличие submission_id и grade в запросе
 * - Корректность submission_id (целое число)
 * - Корректность оценки (0-10 баллов)
 * 
 * При успешной проверке обновляет оценку в базе данных
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'], $_POST['grade'])) {
    $submission_id = filter_var($_POST['submission_id'], FILTER_VALIDATE_INT);
    $grade = trim($_POST['grade']);

    if ($submission_id !== false && $grade !== '') {
        // Проверка формата оценки (0-10 баллов)
        if (preg_match('/^(10|[0-9])$/', $grade)) {
            $stmt = $pdo->prepare("UPDATE submissions SET grade = ? WHERE id = ?");
            $stmt->execute([$grade, $submission_id]);
            $message = "<p style='color: green;'>Оценка сохранена.</p>";
        } else {
            $message = "<p style='color: red;'>Некорректная оценка.</p>";
        }
    } else {
        $message = "<p style='color: red;'>Ошибка в переданных данных.</p>";
    }
}

/**
 * @var string $search Поисковый запрос (имя ученика)
 */
$search = trim($_GET['search'] ?? '');

// SQL-запрос для получения списка отправленных заданий
$sql = "
    SELECT 
        s.id AS submission_id,
        s.answer_text,
        s.grade,
        s.submitted_at,
        h.title AS homework_title,
        h.subject,
        u.full_name AS student_name
    FROM submissions s
    JOIN homework h ON s.homework_id = h.id
    JOIN users u ON s.student_id = u.id
";

/**
 * @var array $params Параметры для SQL-запроса
 */
$params = [];

// Добавление условия поиска, если указано имя ученика
if (!empty($search)) {
    $sql .= " WHERE u.full_name LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

$sql .= " ORDER BY s.submitted_at DESC";

// Выполнение запроса и получение результатов
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Начало буферизации вывода
ob_start();
?>

<h2>Проверка заданий</h2>

<?= $message ?>

<!-- Форма поиска по имени ученика -->
<form method="get" style="margin-bottom: 20px;">
    <label for="search">Поиск ученика:</label>
    <input type="text" name="search" id="search" value="<?= htmlspecialchars($search, ENT_QUOTES) ?>" placeholder="Введите имя ученика">
    <button type="submit">Найти</button>
</form>

<?php if (empty($submissions)): ?>
    <p>Нет отправленных заданий.</p>
<?php else: ?>
    <!-- Вывод списка заданий -->
    <?php foreach ($submissions as $s): ?>
        <div style="border:3px solid #ccc; padding:10px; margin-bottom:15px;">
            <h3><?= htmlspecialchars($s['homework_title']) ?></h3>
            <p><strong>Предмет:</strong> <?= htmlspecialchars($s['subject']) ?></p>
            <p><strong>Ученик:</strong> <?= htmlspecialchars($s['student_name']) ?></p>
            <p><strong>Дата отправки:</strong> <?= htmlspecialchars($s['submitted_at']) ?></p>
            <p><strong>Ответ:</strong></p>
            <blockquote><?= nl2br(htmlspecialchars($s['answer_text'])) ?></blockquote>
            <!-- Форма для оценки задания -->
            <form method="post">
                <input type="hidden" name="submission_id" value="<?= (int)$s['submission_id'] ?>">
                <label>Оценка:
                    <input type="text" name="grade" value="<?= htmlspecialchars($s['grade'] ?? '') ?>" required>
                </label>
                <button type="submit">Сохранить</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<p><a href="/LessonManager/admin/index.php">Назад</a></p>

<?php
// Получение содержимого буфера и его очистка
$content = ob_get_clean();
$title = 'Проверка заданий';
require '../includes/layout.php';