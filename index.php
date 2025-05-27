<?php require_once './header.php'; ?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once './sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once './navbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <?php require_once './content.php'; ?>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php require_once './footer.php'; ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="./assets/vendor/jquery/jquery.min.js"></script>
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="./assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="./assets/js/demo/chart-area-demo.js"></script>
    <script src="./assets/js/demo/chart-pie-demo.js"></script>

    <script>
        $(document).ready(function() {
            // Dosya input'una olay dinleyici ekleme
            $('#fileInput').on('change', function(event) {
                const file = event.target.files[0];
                if (!file) return; // Dosya seçilmediyse çık

                const $form = $('#uploadForm');
                const $button = $form.find('button');
                $button.prop('disabled', true).text('Yükleniyor...');

                const formData = new FormData($form[0]);

                $.ajax({
                    url: '<?php echo $domain ?>/upload-file',
                    type: 'POST',
                    data: formData,
                    processData: false, // FormData için gerekli
                    contentType: false, // FormData için gerekli
                    dataType: 'json',
                    success: function(result) {
                        if (result.status === 'success') {
                            // Tabloya yeni satır ekle
                            const $tbody = $('#fileTableBody');
                            const $noFilesRow = $tbody.find('tr td.text-center');
                            if ($noFilesRow.length) {
                                $tbody.empty(); // "Henüz dosya yüklenmedi" satırını kaldır
                            }

                            // isShow kontrolüne göre görüntüleme hücresini oluştur
                            const displayCell = result.file.isShow ?
                                `<td><img class="img-fluid" width="100" src="<?php echo $domain ?>/${result.file.file_path}" alt="${encodeURIComponent(result.file.file_name)}"></td>` :
                                `<td>${result.file.file_name}</td>`;

                            const newRow = `
                            <tr data-file-id="${result.file.id}">
                                ${displayCell}
                                <td>${result.file.file_name}</td>
                                <td>${result.file.file_type.replace('image/', '')}</td>
                                <td>${(result.file.file_size / 1024).toFixed(2)} KB</td>
                                <td style="align-content: center; justify-content: center;"><button class="btn btn-danger btn-sm btn-block delete-file" data-file-id="${result.file.id}">Sil</button></td>
                            </tr>
                        `;
                            $tbody.prepend(newRow); // Yeni dosyayı en üste ekle

                            // Formu sıfırla
                            $form[0].reset();
                        } else {
                            alert('Dosya yüklenemedi: ' + result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Hata:', error);
                        alert('Bir hata oluştu: ' + (xhr.responseJSON?.message || error));
                    },
                    complete: function() {
                        // Yükleme göstergesini kaldır
                        $button.prop('disabled', false).text('Dosya Yükle');
                    }
                });
            });

            // Silme butonuna tıklama olayı
            $(document).on('click', '.delete-file', function() {
                const fileId = $(this).data('file-id');
                $.ajax({
                    url: 'delete-file?file_id=' + fileId,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(result) {
                        if (result.status === 'success') {
                            // Tabodan satırı kaldır
                            $(`tr[data-file-id="${fileId}"]`).remove();
                        } else {
                            alert('Dosya silinemedi: ' + result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Hatası:', xhr.status, error);
                        $('#message').removeClass('success error').addClass('error')
                            .text('Dosya silinirken bir hata oluştu: ' + error);
                    }
                });
            });
        });
    </script>

</body>

</html>