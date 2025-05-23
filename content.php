<?php
// Mevcut path'i al
$basePath = "/file-management/";
$requestUri = $_SERVER['REQUEST_URI'];
$path = str_replace($basePath, '', $requestUri);
$path = trim($path, '/');
$path = ($path === '' || $path === '/') ? '' : $path;

$stmt = $pdo->prepare("SELECT routeFilePath FROM routes WHERE routeName = :path LIMIT 1");
$stmt->execute([':path' => $path]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result && !empty($result['routeFilePath'])) {
    // Eşleşme varsa, ilgili dosyaya yönlendir
    $filePath = $result['routeFilePath'];
    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        // Dosya yoksa 404
        require_once '404.php'; // 404 sayfasını içer
        return;
    }
} else {
    require_once '404.php';
    return;
}
?>



