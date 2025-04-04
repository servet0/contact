<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'error' => 'Lütfen tüm alanları doldurun.']);
        exit;
    }

    try {
        $stmt = $db->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$name, $email, $subject, $message]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Mesajınız başarıyla gönderildi.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Mesaj gönderilirken bir hata oluştu.']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Geçersiz istek.']);
}
?> 