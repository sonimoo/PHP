<?php
/**
 * Перенаправление пользователей по ролям после авторизации
 * 
 * Проверяет роль пользователя и перенаправляет на соответствующую страницу:
 * - Администраторов → в админ-панель
 * - Учеников → в раздел с заданиями
 * - Неавторизованных → показывает приветствие
 */

session_start();

// Обработка неавторизованных пользователей
if (!isset($_SESSION['role'])) {
    $title = "Добро пожаловать";
    ob_start();
    echo "<p>🔐 Войдите в систему, чтобы продолжить.</p>";
    $content = ob_get_clean();
    include '../includes/layout.php';
    exit;
}

// Перенаправление по ролям
if ($_SESSION['role'] === 'admin') {
    header('Location: /lessonmanager/admin/index.php');
    exit;
} elseif ($_SESSION['role'] === 'student') {
    header('Location: /lessonmanager/student/my_homework.php');
    exit;
}