<?php

$files = $fileController->getFiles();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // uploadFile metodunu çağır ve doğrudan JSON yanıtı döndür
    header('Content-Type: application/json; charset=utf-8');
    echo $fileController->uploadFile($_FILES['file']);
    exit; // Yanıttan sonra betiği sonlandır
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['file_id'])) {
    $fileId = filter_input(INPUT_GET, 'file_id', FILTER_VALIDATE_INT);
    header('Content-Type: application/json; charset=utf-8');
    if ($fileId === false || $fileId === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Geçersiz dosya ID.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo $fileController->deleteFile($fileId);
    exit;
}

?>

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dosyalarım</h1>
        <form id="uploadForm" action="" method="POST" enctype="multipart/form-data">
            <input type="file" id="fileInput" name="file">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">Dosya Yükle</button>
        </form>
    </div>





    <!-- Content Row -->
    <div class="row">

        <!-- Content Column -->

        <div class="col-lg-12 mb-4">

            <!-- Page Heading -->

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Dosya</th>
                                    <th>Dosya Adı</th>
                                    <th>Tipi</th>
                                    <th>Boyutu</th>
                                    <th style="width: 150px;"></th>
                                    <th style="width: 120px;"></th>
                                    <th style="width: 100px;"></th>
                                </tr>
                            </thead>
                            <tbody id="fileTableBody">
                                <?php if (empty($files)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Henüz dosya yüklenmedi.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($files as $file): ?>
                                        <tr data-file-id="<?php echo $file['id']; ?>">

                                            <?php if ($file['isShow']) { ?>
                                                <td><img class="img-fluid" width="100" src="<?php echo $domain . '/' . htmlspecialchars($file['file_path']); ?>" alt="<?php echo htmlspecialchars($file['file_name']); ?>"></td>
                                            <?php    } else { ?>
                                                <td><?php echo htmlspecialchars($file['file_name']); ?></td>
                                            <?php    } ?>

                                            <td><?php echo htmlspecialchars($file['file_name']); ?></td>
                                            <td><?php echo htmlspecialchars(str_replace('image/', '', $file['file_type'])); ?></td>
                                            <td><?php echo round($file['file_size'] / 1024, 2); ?> KB</td>
                                            <td style="align-content: center; justify-content: center;"><a href="./home/<?= substr($file['file_path'], strlen('uploads/')); ?>" target="_blank" class="btn bg-gradient-primary text-white btn-sm btn-block"><i class="fa fa-eye mr-1"></i> Görüntüle</a></td>
                                            <td style="align-content: center; justify-content: center;"><a href="./home/download/<?= substr($file['file_path'], strlen('uploads/')); ?>" class="btn btn-success btn-sm btn-block" data-file-id="<?php echo $file['id']; ?>"><i class="fa fa-save mr-1"></i> Kaydet</a></td>
                                            <td style="align-content: center; justify-content: center;"><button class="btn btn-danger btn-sm btn-block delete-file" data-file-id="<?php echo $file['id']; ?>">Sil</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>