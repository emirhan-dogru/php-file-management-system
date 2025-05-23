<?php

include 'settings.php';

try {
    // PDO bağlantısı oluşturma
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Veritabanından dosyaları çek
     $stmt = $pdo->query("SELECT id, file_name, file_type, file_size, isShow, file_path FROM files ORDER BY upload_date DESC");
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Hata durumunda mesaj gösterme
    echo "Bağlantı hatası: " . $e->getMessage();
}

?>