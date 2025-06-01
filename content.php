<?php
// Mevcut path'i al
$currentPath = Utils::getCurrentRoutePath($basePath);

$stmt = $pdo->prepare("SELECT routeFilePath FROM routes WHERE routeName = :path LIMIT 1");
$stmt->execute([':path' => $currentPath]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result && !empty($result['routeFilePath'])) {
    // Eşleşme varsa, ilgili dosyaya yönlendir
    $filePath = $result['routeFilePath'];
    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        // Dosya yoksa 404
        require_once './pages/404.php'; // 404 sayfasını içer
        return;
    }
} else {
    require_once './pages/404.php';
    return;
}
?>



