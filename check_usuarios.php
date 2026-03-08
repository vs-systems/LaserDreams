<?php
require __DIR__ . '/includes/db.php';
try {
    $stmt = $pdo->query("DESCRIBE usuarios");
    echo "<pre>";
    print_r($stmt->fetchAll());
    echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
