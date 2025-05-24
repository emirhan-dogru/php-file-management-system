<?php
require_once __DIR__ . '/../system/connection.php';
require_once __DIR__ . '/../system/Utils.php';

class FileController {
    private $pdo;
    private $uploadDir = 'uploads/';
    private $imageTypes = [
        'image/png', 'image/jpeg', 'image/gif', 'image/bmp', 'image/webp',
        'image/tiff', 'image/x-icon', 'image/svg+xml', 'image/heic',
        'image/heif', 'image/avif', 'image/jp2', 'image/x-ms-bmp',
        'image/vnd.wap.wbmp', 'image/x-xbitmap', 'image/x-pcx',
        'image/x-pict', 'image/x-portable-anymap', 'image/x-portable-bitmap',
        'image/x-portable-graymap', 'image/x-portable-pixmap', 'image/x-rgb',
        'image/x-xpixmap'
    ];

    public function __construct($pdo) {
        $this->pdo = $pdo;
        // Upload dizininin varlığını kontrol et, yoksa oluştur
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function uploadFile($file) {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return json_encode([
                'status' => 'error',
                'message' => 'Dosya yüklenmedi veya hata oluştu.'
            ]);
        }

        $fileTmpPath = $file['tmp_name'];
        $originalFileName = $file['name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];

        // GUID oluştur
        $guid = Utils::generateGuid();

        // Dosya uzantısını al
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $newFileName = $guid . '.' . $fileExtension;
        $destination = $this->uploadDir . $newFileName;

        // Resim türünü kontrol et
        $isShow = in_array($fileType, $this->imageTypes) ? 1 : 0;

        // Dosyayı yükle
        if (move_uploaded_file($fileTmpPath, $destination)) {
            try {
                $stmt = $this->pdo->prepare("INSERT INTO files (file_name, file_path, file_type, file_size, isShow) VALUES (:file_name, :file_path, :file_type, :file_size, :isShow)");
                $stmt->execute([
                    ':file_name' => $originalFileName,
                    ':file_path' => $destination,
                    ':file_type' => $fileType,
                    ':file_size' => $fileSize,
                    ':isShow' => $isShow
                ]);

                // Yeni eklenen dosyanın ID'sini al
                $fileId = $this->pdo->lastInsertId();

                return json_encode([
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
                return json_encode([
                    'status' => 'error',
                    'message' => 'Veritabanına kaydetme hatası: ' . $e->getMessage()
                ]);
            }
        } else {
            return json_encode([
                'status' => 'error',
                'message' => 'Dosya yüklenirken bir hata oluştu.'
            ]);
        }
    }

    public function getFiles() {
        try {
            $stmt = $this->pdo->query("SELECT id, file_name, file_type, file_size, isShow, file_path FROM files ORDER BY upload_date DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteFile($fileId) {
    try {
        $stmt = $this->pdo->prepare("SELECT file_path FROM files WHERE id = :id");
        $stmt->execute([':id' => $fileId]);
        $file = $stmt->fetch();
        if ($file) {
            if (file_exists($file['file_path'])) {
                unlink($file['file_path']);
            }
            $stmt = $this->pdo->prepare("DELETE FROM files WHERE id = :id");
            $stmt->execute([':id' => $fileId]);
            return json_encode([
                'status' => 'success',
                'message' => 'Dosya başarıyla silindi.',
                'file_id' => $fileId
            ], JSON_UNESCAPED_UNICODE);
        }
        return json_encode([
            'status' => 'error',
            'message' => 'Dosya bulunamadı.'
        ], JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        return json_encode([
            'status' => 'error',
            'message' => 'Silme hatası: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}
}
?>