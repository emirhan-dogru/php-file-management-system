<?php

require_once './backend/system/connection.php';

// Tüm kontrolörleri al
$database = Database::getInstance();

$controllers = $database->bootstrap();

$userController = $controllers['UserController'];
$fileController = $controllers['FileController'];

$token = $_COOKIE['jwt_token'] ?? '';
$payload = $userController->verifyToken($token);
if (!$payload) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

$requestedFile = basename($_SERVER['REQUEST_URI']);
$requestedFile = 'uploads/' . $requestedFile;

// Dosyayı veritabanında ara
$file = $fileController->getFileByGuidName($requestedFile);

if (!$file) {
    http_response_code(404);
    echo "Dosya bulunamadı.";
    exit;
}

// Kullanıcı ID kontrolü
if ((string)$file['user_id'] !== (string)$payload['user_id']) {
    http_response_code(403);
    echo "Bu dosyayı görüntüleme yetkiniz yok.";
    exit;
}

// Dosya varsa ve erişim yetkisi varsa, dosya içeriğini göster
$rootDir = realpath(__DIR__ . '/../');
$filePath = $rootDir . '/' . $file['file_path'];

if (file_exists($filePath)) {
    header('Content-Type: ' . $file['file_type']);
    readfile($filePath);
    exit;
} else {
    http_response_code(404);
    echo "Dosya sunucuda bulunamadı.";
    exit;
}


?>