<?php
return [
    '' => [
        'path' => './pages/homepage.php',
        'layout' => 'dashboard'
    ],
    'login' => [
        'path' => './pages/login.php',
        'layout' => 'auth'
    ],
    'register' => [
        'path' => './pages/register.php',
        'layout' => 'auth'
    ],
    'logout' => [
        'path' => './pages/logout.php',
        'layout' => 'auth'
    ],
    'profile' => [
        'path' => './pages/profile.php',
        'layout' => 'dashboard'
    ],
    'home' => [
        'path' => './pages/datas.php',
        'layout' => 'dashboard'
    ],
    'home/:file_name' => [
        'path' => './pages/viewFile.php',
        'layout' => 'auth'
    ],
    'home/download/:file_name' => [
        'path' => './pages/downloadFile.php',
        'layout' => 'auth'
    ],
    'api/data/upload' => [
        'path' => './pages/uploadFile.php',
        'layout' => 'auth'
    ],
    'api/data/delete' => [
        'path' => './pages/deleteFile.php',
        'layout' => 'auth'
    ],
];
