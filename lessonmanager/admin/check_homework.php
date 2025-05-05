<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAuth('admin');

// Обработка POST-запроса на выставление оценки
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submission_id'], $_POST['grade'])) {
    $submission_id = (int)$_POST['submission_id'];
    $grade = trim($_POST['grade']);

    $stmt = $pdo->prepare("UPDATE submissions SET grade = ? WHERE id = ?");
    $stmt->execute([$grade, $submission_id]);
    $message = "<p style='color: green;'>Оценка сохранена.</p>";
}

// Получение всех отправленных заданий
$stmt = $pdo->query("
    SELECT 
        s.id AS submission_id,
        s.answer_text,
        s.grade,
        s.submitted_at,
        h.title AS homework_title,
        u.full_name AS student_name
    FROM submissions s
    JOIN homework h ON s.homework_id = h.id
    JOIN users u ON s.student_id = u.id
    ORDER BY s.submitted_at DESC
");

$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h2>Проверка заданий</h2>

<?= $message ?>

<?php if (empty($submissions)): ?>
    <p>Нет отправленных заданий.</p>
<?php else: ?>
    <?php foreach ($submissions as $s): ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:15px;">
            <h3><?= htmlspecialchars($s['homework_title']) ?></h3>
            <p><strong>Ученик:</strong> <?= htmlspecialchars($s['student_name']) ?></p>
            <p><strong>Дата отправки:</strong> <?= htmlspecialchars($s['submitted_at']) ?></p>
            <p><strong>Ответ:</strong></p>
            <blockquote><?= nl2br(htmlspecialchars($s['answer_text'])) ?></blockquote>
            <form method="post">
                <input type="hidden" name="submission_id" value="<?= $s['submission_id'] ?>">
                <label>Оценка:
                    <input type="text" name="grade" value="<?= htmlspecialchars($s['grade'] ?? '') ?>">
                </label>
                <button type="submit">Сохранить</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<p><a href="/LessonManager/admin/index.php">Назад</a></p>

<?php
$content = ob_get_clean();
$title = 'Проверка заданий';
require '../includes/layout.php';
?>
