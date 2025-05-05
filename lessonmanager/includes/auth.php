<?php
session_start();


// Функция для проверки, авторизован ли пользователь и имеет ли он нужную роль
function checkAuth($role = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /lessonmanager/login.php");
        exit();
    }

    if ($role) {
        if ($_SESSION['role'] !== $role) {
            // Если роль пользователя не совпадает с требуемой
            header("Location: index.php");
            exit();
        }
    }
}

?>
