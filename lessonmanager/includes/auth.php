<?php
session_start();

/**
 * Проверяет аутентификацию пользователя и его роль
 * 
 * @param string|null $role Требуемая роль пользователя (опционально)
 * 
 * @return void
 * 
 * @throws RedirectException Перенаправляет на страницу входа, если пользователь не авторизован
 * @throws RedirectException Перенаправляет на главную страницу, если роль не совпадает
 */
function checkAuth($role = null) {
    // Проверка наличия идентификатора пользователя в сессии
    if (!isset($_SESSION['user_id'])) {
        header("Location: /lessonmanager/login.php");
        exit();
    }

    if ($role !== null) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            // Перенаправление, если роль не совпадает
            header("Location: index.php");
            exit();
        }
    }
}