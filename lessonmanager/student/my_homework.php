<?php
// Подключаем необходимые файлы
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAuth('student');

$stmt = $pdo->query("SELECT h.*, s.answer_text, s.grade FROM homework h
    LEFT JOIN submissions s ON h.id = s.homework_id AND s.student_id = " . $_SESSION['user_id'] . "
    ORDER BY h.due_date DESC");
$homeworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h2>Мои домашние задания</h2>

<?= $message ?? '' ?>

<?php foreach ($homeworks as $hw): ?>
    <div class="homework-container">
        <h3><?= htmlspecialchars($hw['title']) ?></h3>
        <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($hw['description'])) ?></p>
        <p><strong>Срок сдачи:</strong> <?= htmlspecialchars($hw['due_date']) ?></p>

        <?php if ($hw['answer_text']): ?>
            <p style="color:green;"><strong>Вы отправили ответ:</strong></p>
            <blockquote><?= nl2br(htmlspecialchars($hw['answer_text'])) ?></blockquote>
            <p><strong>Оценка:</strong> <?= htmlspecialchars($hw['grade'] ?? 'ещё не выставлена') ?></p>
        <?php else: ?>
            <form method="post">
                <input type="hidden" name="homework_id" value="<?= $hw['id'] ?>">
                <label>Ответ:<br><textarea name="submission_text" rows="4" cols="50" required></textarea></label><br>
                <button type="submit">Отправить</button>
            </form>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<p><a href="../dashboard.php">Назад</a></p>

<?php
$content = ob_get_clean();
$title = 'Мои задания';
require '../includes/layout.php';
