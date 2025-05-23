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
                                         <?php    }  else { ?>
 <td><?php echo htmlspecialchars($file['file_name']); ?></td>
                                          <?php    }?>
                                           
                                            <td><?php echo htmlspecialchars($file['file_name']); ?></td>
                                            <td><?php echo htmlspecialchars(str_replace('image/', '', $file['file_type'])); ?></td>
                                            <td><?php echo round($file['file_size'] / 1024, 2); ?> KB</td>
                                            <td style="align-content: center; justify-content: center;"><a class="btn btn-danger btn-sm btn-block" href="delete.php?id=<?php echo $file['id']; ?>">Sil</a></td>
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