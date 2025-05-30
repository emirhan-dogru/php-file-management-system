<?php
require_once './backend/system/connection.php';
require_once './backend/oauth/UserController.php';
require_once './backend/system/utils.php';


$database = Database::getInstance();
$userController = new UserController($database->getConnection());

$token = $_COOKIE['jwt_token'] ?? '';
if ($token && $userController->verifyToken($token)) {
    header('Location:' . $domain);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = Utils::secure(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = Utils::secure($_POST['password']);

    if (empty($email) || empty($password)) {
        header('Location: login?error=' . urlencode('E-posta ve parola gerekli.'));
        exit;
    }

    $response = json_decode($userController->login($email, $password), true);

    if ($response['status'] === 'success') {
        // Token'ı çerezde sakla
        setcookie('jwt_token', $response['token'], [
            'expires' => time() + 3600, // 1 saat
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        header('Location:' . $domain);
        exit;
    } else {
        header('Location: login?error=' . urlencode($response['message']));
        exit;
    }
} 

$flashMessage = Utils::getMessage('register_message');
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ED Dosya Yönetim Sistemi - Giriş Yap</title>

    <!-- Custom fonts for this template-->
    <link href="./assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-dark">

    <div class="container">

        <!-- Outer Row -->
        <div class="row" style="min-height: 100vh; display: flex; justify-content: center; align-items: center;">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row" style="justify-content: center;">
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <?php if ($flashMessage): ?>
                                        <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
                                            <?php echo htmlspecialchars($flashMessage); ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($_GET['error'])): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <?php echo htmlspecialchars($_GET['error']); ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Giriş Yap</h1>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-lg" name="email"
                                               
                                                placeholder="Kullanıcı adı...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-lg"  name="password"
                                                id="exampleInputPassword" placeholder="Parola">
                                        </div>
                                        <button type="submit" class="btn btn-secondary btn-user btn-block">
                                            Giriş Yap
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="./register">Ücretsiz Kayıt ol!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

</body>

</html>