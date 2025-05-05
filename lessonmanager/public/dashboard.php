<?php
session_start();

if (!isset($_SESSION['role'])) {
    // Пользователь не авторизован
    $title = "Добро пожаловать";
    ob_start();
    echo "<p>🔐 Войдите в систему, чтобы продолжить.</p>";
    $content = ob_get_clean();
    include '../includes/layout.php';
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header('Location: admin/index.php');
    exit;
} elseif ($_SESSION['role'] === 'student') {
    header('Location: student/my_homework.php');
    exit;
}
?>
