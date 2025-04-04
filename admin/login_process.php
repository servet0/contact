<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = 'Geçersiz kullanıcı adı veya şifre!';
            header('Location: login.php');
            exit;
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = 'Bir hata oluştu: ' . $e->getMessage();
        header('Location: login.php');
        exit;
    }
}

header('Location: login.php');
exit;
?> 