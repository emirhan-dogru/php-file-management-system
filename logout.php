<?php

include './backend/system/connection.php';

$database = Database::getInstance();

$controllers = $database->bootstrap();

$userController = $controllers['UserController'];

$token = $_COOKIE['jwt_token'];

if ($token) {
    $userController->invalidateToken($token);
    
    setcookie('jwt_token', $response['token'], [
        'expires' => time() + 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    header('Location: login?error=' . urlencode('Oturumu Başarıyla Sonlandırdınız'));
} else {
    header('Location: login');
}
die;
