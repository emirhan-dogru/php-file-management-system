<?php
include './backend/system/connection.php';

// Rotaları yükle
$routes = require './routes.php';

// Mevcut URL yolunu al
$currentPath = Utils::getCurrentRoutePath($basePath);

// URL'nin ham halini al
$uri = Utils::getCurrentRoutePath($basePath);
// Eğer URL sonda '/' ile bitiyorsa ve boş değilse, eğik çizgiyi kaldır ve yönlendir
if (substr($_SERVER['REQUEST_URI'], -1) === '/' && $uri !== '') {
    $newUrl = rtrim($_SERVER['REQUEST_URI'], '/');
    header('Location: ' . $newUrl, true, 301); // 301 kalıcı yönlendirme
    exit;
}

// Mevcut URL yolunu al ve sondaki eğik çizgiyi kaldır
$currentPath = rtrim($currentPath, '/'); // Güvenlik için tekrar kaldır

// Varsayılan dosya yolu ve 404 durumu
$filePath = './pages/404.php';
$layout = 'dashboard'; // Varsayılan layout, gerekirse değiştirilebilir
$routeParams = []; // Dinamik parametreleri saklamak için

// Rotaları kontrol et
$routeFound = false;
foreach ($routes as $routePattern => $route) {
    // Eğer ana rota ('') ve $currentPath boşsa, özel durum
    if ($routePattern === '' && $currentPath === '') {
        $routeFound = true;
        $filePath = $route['path'];
        $layout = $route['layout'];
        break;
    }

    // Dinamik rotaları işlemek için regex oluştur
    $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $routePattern);
    $pattern = str_replace('/', '\/', $pattern);
    $regex = "#^$pattern$#"; // Eşleşme kontrolü

    // Mevcut yolun rotayla eşleşip eşleşmediğini kontrol et
    if (preg_match($regex, $currentPath, $matches)) {
        $routeFound = true;
        $filePath = $route['path'];
        $layout = $route['layout'];

        // Dinamik parametreleri çıkar
        if (count($matches) > 1) {
            array_shift($matches); // Tam eşleşmeyi kaldır
            $paramKeys = [];
            preg_match_all('#:([\w]+)#', $routePattern, $paramKeys);
            $paramKeys = $paramKeys[1]; // Parametre isimlerini al
            foreach ($paramKeys as $index => $key) {
                $routeParams[$key] = $matches[$index];
            }
        }
        break; // Eşleşme bulundu, döngüden çık
    }
}

// Rota bulunamadıysa veya dosya yoksa 404
if (!$routeFound || !file_exists($filePath)) {
    $filePath = './pages/404.php';
    require './layout.php';
    exit;
}

// Layout'a göre sayfayı yükle
if ($layout === 'dashboard') {
    require './layout.php';
} elseif ($layout === 'auth') {
    // Giriş/Kayıt gibi sade sayfalar için direkt yükle
    require $filePath;
} else {
    $filePath = './pages/404.php';
    require './layout.php';
    exit;
}
?>