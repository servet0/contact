<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Mesajları getir
$stmt = $db->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Mesajlar</title>
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
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Mesajlar</a>
                    </li>
                    <li class="nav-item">
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
        <h2>Gelen Mesajlar</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Durum</th>
                        <th>İsim</th>
                        <th>E-posta</th>
                        <th>Konu</th>
                        <th>Mesaj</th>
                        <th>Tarih</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                    <tr>
                        <td>
                            <?php if ($message['is_read']): ?>
                                <i class="fas fa-envelope-open text-muted"></i>
                            <?php else: ?>
                                <i class="fas fa-envelope text-primary"></i>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($message['name']) ?></td>
                        <td><?= htmlspecialchars($message['email']) ?></td>
                        <td><?= htmlspecialchars($message['subject']) ?></td>
                        <td><?= htmlspecialchars(substr($message['message'], 0, 50)) ?>...</td>
                        <td><?= date('d.m.Y H:i', strtotime($message['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-sm btn-info view-message" data-id="<?= $message['id'] ?>">
                                Görüntüle
                            </button>
                            <?php if (!$message['is_read']): ?>
                            <button class="btn btn-sm btn-success mark-read" data-id="<?= $message['id'] ?>">
                                Okundu İşaretle
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mesaj Detay Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mesaj Detayı</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="messageDetails"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        // Mesaj görüntüleme
        $('.view-message').click(function() {
            var id = $(this).data('id');
            $.get('get_message.php', {id: id}, function(response) {
                $('#messageDetails').html(response);
                $('#messageModal').modal('show');
            });
        });

        // Okundu işaretleme
        $('.mark-read').click(function() {
            var button = $(this);
            var id = button.data('id');
            $.post('mark_read.php', {id: id}, function(response) {
                if (response.success) {
                    button.closest('tr').find('.fa-envelope')
                        .removeClass('fa-envelope text-primary')
                        .addClass('fa-envelope-open text-muted');
                    button.remove();
                }
            }, 'json');
        });
    });
    </script>
</body>
</html> 