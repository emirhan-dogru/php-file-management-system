<?php
// Database sınıfından örnek al
$database = Database::getInstance();

// İsteğe bağlı: FilesController örneği oluştur
// Tüm kontrolörleri al
$controllers = $database->bootstrap();

// UserController'ı diziden al
$userController = $controllers['UserController'];
$fileController = $controllers['FileController'];

$pdo = $database->getConnection();

// Token kontrolü
$token = $_COOKIE['jwt_token'] ?? '';

if (!$token) {
    header("Location: $domain/login");
    exit;
} else if (!$payload = $userController->verifyToken($token)) {
    header("Location: $domain/login?error=" . urlencode('Oturum geçersiz. Lütfen giriş yapın.'));
    exit;
}

$userId = $payload['user_id'];

// Kullanıcı bilgilerini al
$user = $userController->getUserById($userId);
if (!$user) {
    header('Location: ./login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="<?= $domain ?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= $domain ?>/assets/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* Input'u gizlemek için */
        #fileInput {
            display: none;
        }

        /* Buton stilini özelleştirme (opsiyonel) */
        .btn-primary {
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>