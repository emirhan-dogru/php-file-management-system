<?php

require_once './backend/system/connection.php';

// Veritabanı ve controller'lar
$database = Database::getInstance();

$controllers = $database->bootstrap();

$userController = $controllers['UserController'];
$fileController = $controllers['FileController'];

// JWT token kontrolü
$token = $_COOKIE['jwt_token'] ?? '';
$payload = $userController->verifyToken($token);
if (!$payload) {
    http_response_code(401);
    echo "Yetkisiz erişim.";
    exit;
}

// Dosya isteğini al
$requestedFileName = basename($_SERVER['REQUEST_URI']);
$requestedFile = 'uploads/' . $requestedFileName;

// Dosyayı veritabanında ara
$file = $fileController->getFileByGuidName($requestedFile);

if (!$file) {
    http_response_code(404);
    echo "Dosya bulunamadı.";
    exit;
}

// Kullanıcı kontrolü
if ((string)$file['user_id'] !== (string)$payload['user_id']) {
    http_response_code(403);
    echo "Bu dosyayı indirme yetkiniz yok.";
    exit;
}

// Gerçek dosya yolu
$rootDir = realpath(__DIR__ . '/../');
$filePath = $rootDir . '/' . $file['file_path'];

if (file_exists($filePath)) {
    // Header'ları ayarla
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $file['file_type']);
    header('Content-Disposition: attachment; filename="' . basename($requestedFileName) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

    // Dosyayı oku ve indir
    readfile($filePath);
    exit;
} else {
    http_response_code(404);
    echo "Dosya sunucuda bulunamadı.";
    exit;
}

?>
