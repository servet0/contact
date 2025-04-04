<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die('Yetkisiz erişim!');
}

if (isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    try {
        $stmt = $db->prepare("SELECT * FROM messages WHERE id = ?");
        $stmt->execute([$id]);
        $message = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($message) {
            echo '<div class="message-details">';
            echo '<p><strong>İsim:</strong> ' . htmlspecialchars($message['name']) . '</p>';
            echo '<p><strong>E-posta:</strong> ' . htmlspecialchars($message['email']) . '</p>';
            echo '<p><strong>Konu:</strong> ' . htmlspecialchars($message['subject']) . '</p>';
            echo '<p><strong>Mesaj:</strong></p>';
            echo '<p>' . nl2br(htmlspecialchars($message['message'])) . '</p>';
            echo '<p><strong>Tarih:</strong> ' . date('d.m.Y H:i', strtotime($message['created_at'])) . '</p>';
            echo '</div>';
        } else {
            echo 'Mesaj bulunamadı.';
        }
    } catch(PDOException $e) {
        echo 'Bir hata oluştu.';
    }
} else {
    echo 'Geçersiz istek.';
}
?> 