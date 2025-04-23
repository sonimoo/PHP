<?php

function deleteRecipe(PDO $pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ?");
    $stmt->execute([$id]);
}
