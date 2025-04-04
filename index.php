<?php
require_once 'config/db.php';

// Site ayarlarını getir
$stmt = $db->query("SELECT * FROM site_settings");
$settings = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['site_title'] ?? 'İletişim Formu') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .contact-info i {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 1rem;
        }
        .contact-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4"><?= htmlspecialchars($settings['header_title'] ?? 'İletişime Geçin') ?></h1>
            <p class="lead"><?= htmlspecialchars($settings['header_description'] ?? 'Bizimle iletişime geçmek için aşağıdaki formu kullanabilirsiniz.') ?></p>
        </div>
    </header>

    <!-- Contact Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Contact Information -->
                <div class="col-lg-4 mb-4">
                    <div class="contact-info text-center">
                        <div class="mb-4">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4>Adres</h4>
                            <p><?= nl2br(htmlspecialchars($settings['address'] ?? '')) ?></p>
                        </div>
                        <div class="mb-4">
                            <i class="fas fa-phone"></i>
                            <h4>Telefon</h4>
                            <p><?= htmlspecialchars($settings['phone'] ?? '') ?></p>
                        </div>
                        <div class="mb-4">
                            <i class="fas fa-envelope"></i>
                            <h4>E-posta</h4>
                            <p><?= htmlspecialchars($settings['email'] ?? '') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="contact-form">
                        <h3 class="mb-4"><?= htmlspecialchars($settings['form_title'] ?? 'Bize Mesaj Gönderin') ?></h3>
                        <form id="contactForm" action="process.php" method="POST">
                            <div class="form-group">
                                <label for="name">İsim</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-posta</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="subject">Konu</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Mesaj</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gönder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-0"><?= htmlspecialchars($settings['footer_text'] ?? '') ?></p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                type: 'POST',
                url: 'process.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Mesajınız başarıyla gönderildi!');
                        $('#contactForm')[0].reset();
                    } else {
                        alert('Hata: ' + response.error);
                    }
                },
                error: function() {
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            });
        });
    });
    </script>
</body>
</html>