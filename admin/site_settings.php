<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Mevcut ayarları getir
$stmt = $db->query("SELECT * FROM site_settings");
$settings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Form gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Her bir ayarı güncelle
        foreach ($_POST['settings'] as $key => $value) {
            $stmt = $db->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$value, $key]);
        }
        $success = "Ayarlar başarıyla güncellendi!";
        
        // Ayarları yeniden yükle
        $stmt = $db->query("SELECT * FROM site_settings");
        $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Bir hata oluştu: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Ayarları - Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Mesajlar</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="site_settings.php">Site Ayarları</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Site Ayarları</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="card">
                <div class="card-body">
                    <?php foreach ($settings as $setting): ?>
                        <div class="form-group">
                            <label for="<?= $setting['setting_key'] ?>"><?= $setting['label'] ?></label>
                            <?php if ($setting['type'] === 'textarea'): ?>
                                <textarea class="form-control" id="<?= $setting['setting_key'] ?>" 
                                    name="settings[<?= $setting['setting_key'] ?>]" rows="5"><?= htmlspecialchars($setting['setting_value']) ?></textarea>
                            <?php else: ?>
                                <input type="text" class="form-control" id="<?= $setting['setting_key'] ?>" 
                                    name="settings[<?= $setting['setting_key'] ?>]" value="<?= htmlspecialchars($setting['setting_value']) ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    
                    <button type="submit" class="btn btn-primary">Ayarları Kaydet</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 