<?php

$currentUsername = htmlspecialchars($user['username']) ?? '';
$usernameParts = !empty($currentUsername) ? explode(' ', $currentUsername, 2) : ['', ''];
$currentName = $usernameParts[0] ?? '';
$currentSurname = $usernameParts[1] ?? '';

$flashMessage = Utils::getMessage('profile_message');
$flashMessageType = Utils::getMessage('profile_message_type');

// POST isteği ile güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['current_password'])) {
        // Şifre değiştirme işlemi
        $currentPassword = Utils::secure($_POST['current_password']);
        $newPassword = Utils::secure($_POST['new_password']);
        $confirmPassword = Utils::secure($_POST['confirm_password']);

        $response = $userController->changePassword($userId, $currentPassword, $newPassword, $confirmPassword);
        $responseData = json_decode($response, true);

        if ($responseData['status'] === 'success') {
            Utils::setMessage('profile_message', $responseData['message']);
            Utils::setMessage('profile_message_type', 'password');
            header('Location: ./profile');
            exit;
        } else {
            $passwordError = $responseData['message'];
        }
    } else {
        // Profil bilgilerini güncelleme
        $name = Utils::secure($_POST['name']);
        $surname = Utils::secure($_POST['surname']);

        $response = $userController->updateProfile($userId, $name, $surname, $user['email']);
        $responseData = json_decode($response, true);

        if ($responseData['status'] === 'success') {
            Utils::setMessage('profile_message', $responseData['message']);
            Utils::setMessage('profile_message_type', 'profile');
            header('Location: ./profile');
            exit;
        } else {
            $errorMessage = $responseData['message'];
        }
    }
}

?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profilim</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Content Column -->
        <div class="col-lg-12 mb-4">
            <!-- Hesap Bilgilerim -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hesap Bilgilerim</h6>
                </div>
                <div class="card-body">
                    <?php if ($flashMessage && $flashMessageType == 'profile'): ?>
                        <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
                            <?= htmlspecialchars($flashMessage); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($errorMessage)): ?>
                        <div class="alert alert-danger alert-dismissible fade show flash-message" role="alert">
                            <?= htmlspecialchars($errorMessage); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <form  method="POST" autocomplete="off">
                        <div class="form-group">
                            <label for="inputName">Ad</label>
                            <input type="text" class="form-control form-control-user" name="name" id="inputName" value="<?= $currentName; ?>" placeholder="Adınız" required>
                        </div>
                        <div class="form-group">
                            <label for="inputSurname">Soyad</label>
                            <input type="text" class="form-control form-control-user" name="surname" id="inputSurname" value="<?= $currentSurname; ?>" placeholder="Soyadınız" required>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail">E-posta Adresi</label>
                            <input type="email" class="form-control form-control-user" name="email" id="inputEmail" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="E-posta Adresiniz" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Bilgileri Güncelle
                        </button>
                    </form>
                </div>
            </div>

            <!-- Şifre Değiştir -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Şifre Değiştir</h6>
                </div>
                <div class="card-body">
                     <?php if ($flashMessage && $flashMessageType == 'password'): ?>
                        <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
                            <?= htmlspecialchars($flashMessage); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($passwordError)): ?>
                        <div class="alert alert-danger alert-dismissible fade show flash-message" role="alert">
                            <?php echo htmlspecialchars($passwordError); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <form  method="POST" autocomplete="off">
                        <div class="form-group">
                            <label for="currentPassword">Mevcut Şifre</label>
                            <input type="password" class="form-control form-control-user" name="current_password" id="currentPassword" placeholder="Mevcut Şifreniz" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword">Yeni Şifre</label>
                            <input type="password" class="form-control form-control-user" name="new_password" id="newPassword" placeholder="Yeni Şifreniz" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Yeni Şifreyi Onayla</label>
                            <input type="password" class="form-control form-control-user" name="confirm_password" id="confirmPassword" placeholder="Yeni Şifreyi Onaylayın" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Şifreyi Değiştir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>