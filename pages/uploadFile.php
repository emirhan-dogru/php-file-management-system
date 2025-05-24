<?php
require_once './backend/oauth/FileController.php';
require_once './backend/system/connection.php';

// JSON yanıtı için başlık
header('Content-Type: application/json; charset=utf-8');


$database = Database::getInstance();

$controller = $database->bootstrap();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // uploadFile metodunu çağır ve doğrudan JSON yanıtı döndür
    echo $controller->uploadFile($_FILES['file']);
    exit; // Yanıttan sonra betiği sonlandır
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Geçersiz istek veya dosya yüklenmedi.'
    ]);
    exit;
}
?>