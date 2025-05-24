<?php
require_once './backend/oauth/FileController.php';
require_once './backend/system/connection.php';

// JSON yanıtı için başlık
header('Content-Type: application/json; charset=utf-8');


$database = Database::getInstance();

$controller = $database->bootstrap();

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['file_id'])) {
    $fileId = filter_input(INPUT_GET, 'file_id', FILTER_VALIDATE_INT);
    if ($fileId === false || $fileId === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Geçersiz dosya ID.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo $controller->deleteFile($fileId);
    exit;
}
?>