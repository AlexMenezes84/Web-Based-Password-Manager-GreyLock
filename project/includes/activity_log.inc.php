<?php
function log_user_activity($pdo, $user_id, $username, $activity) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt = $pdo->prepare("INSERT INTO user_activity_logs (user_id, username, activity, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $username, $activity, $ip]);
}