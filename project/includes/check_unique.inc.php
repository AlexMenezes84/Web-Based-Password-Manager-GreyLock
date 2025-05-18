<?php

if (isset($_GET['field']) && isset($_GET['value'])) {
    require 'dbh.inc.php'; // Include the database connection

    $field = $_GET['field'];
    $value = htmlspecialchars($_GET['value'], ENT_QUOTES, 'UTF-8');

    // Validate the field to prevent SQL injection
    if (!in_array($field, ['username', 'email'])) {
        echo json_encode(['error' => 'Invalid field']);
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE $field = :value");
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Return JSON response
        echo json_encode(['unique' => $count == 0]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}