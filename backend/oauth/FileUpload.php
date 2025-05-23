<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    header('Content-Type: application/json; charset=utf-8');

    // Yükleme klasörünü belirt
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmpPath = $_FILES['file']['tmp_name'];
    $originalFileName = $_FILES['file']['name']; // Orijinal dosya adı
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    
    // GUID oluştur
    $guid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 65535), mt_rand(0, 65535), // 32 bit
        mt_rand(0, 65535), // 16 bit
        mt_rand(16384, 20479), // 16 bit (version 4)
        mt_rand(32768, 49151), // 16 bit (variant)
        mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bit
    );
    
    // Dosya uzantısını al
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    // GUID ile yeni dosya adını oluştur
    $newFileName = $guid . '.' . $fileExtension;
    $destination = $uploadDir . $newFileName;

    // Resim türlerini kontrol et
    $imageTypes = [
        'image/png',        // PNG
        'image/jpeg',       // JPEG, JPG
        'image/gif',        // GIF
        'image/bmp',        // BMP
        'image/webp',       // WebP
        'image/tiff',       // TIFF
        'image/x-icon',     // ICO (Icon)
        'image/svg+xml',    // SVG
        'image/heic',       // HEIC (High Efficiency Image Format)
        'image/heif',       // HEIF
        'image/avif',       // AVIF
        'image/jp2',        // JPEG 2000
        'image/x-ms-bmp',   // Microsoft BMP
        'image/vnd.wap.wbmp', // WBMP (Wireless Bitmap)
        'image/x-xbitmap',  // XBM (X Bitmap)
        'image/x-pcx',      // PCX
        'image/x-pict',     // PICT
        'image/x-portable-anymap', // PNM (Portable Anymap)
        'image/x-portable-bitmap', // PBM (Portable Bitmap)
        'image/x-portable-graymap', // PGM (Portable Graymap)
        'image/x-portable-pixmap', // PPM (Portable Pixmap)
        'image/x-rgb',      // RGB
        'image/x-xpixmap'   // XPM (X Pixmap)
    ];
    $isShow = in_array($fileType, $imageTypes) ? 1 : 0;

    // Dosyayı yükle
    if (move_uploaded_file($fileTmpPath, $destination)) {
        // Veritabanına kaydet
        try {
            $stmt = $pdo->prepare("INSERT INTO files (file_name, file_path, file_type, file_size, isShow) VALUES (:file_name, :file_path, :file_type, :file_size, :isShow)");
            $stmt->execute([
                ':file_name' => $originalFileName,
                ':file_path' => $destination,
                ':file_type' => $fileType,
                ':file_size' => $fileSize,
                ':isShow' => $isShow
            ]);

            // Yeni eklenen dosyanın ID'sini al
            $fileId = $pdo->lastInsertId();

            echo json_encode([
                'status' => 'success',
                'message' => 'Dosya başarıyla yüklendi ve veritabanına kaydedildi: ' . $originalFileName,
                'file' => [
                    'id' => $fileId,
                    'file_name' => $originalFileName,
                    'file_type' => $fileType,
                    'file_size' => $fileSize,
                    'isShow' => (bool)$isShow,
                    'file_path' => $destination
                ]
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Veritabanına kaydetme hatası: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Dosya yüklenirken bir hata oluştu.'
        ]);
    }
    exit; // PHP işlemini burada sonlandır
}

// Veritabanından dosyaları çek
// try {
//     $stmt = $pdo->query("SELECT id, file_name, file_type, file_size FROM files ORDER BY upload_date DESC");
//     $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     $files = [];
//     echo "<p>Veritabanı hatası: " . $e->getMessage() . "</p>";
// }
?>