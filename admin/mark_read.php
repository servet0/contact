<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    die('Yetkisiz erişim!');
}

if (isset($_POST['id'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    try {
        $stmt = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo 'success';
        } else {
            echo 'error';
        }
    } catch(PDOException $e) {
        echo 'error';
    }
} else {
    echo 'error';
}
?> 