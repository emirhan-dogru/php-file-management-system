<?php
$host = 'localhost';
$dbname = 'file_management_db';
$username = 'root';
$password = '';

try {
    // PDO bağlantısı oluşturma
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    // Hata durumunda mesaj gösterme
    echo "Bağlantı hatası: " . $e->getMessage();
}

?>