<?php
require_once './backend/system/connection.php';
require_once './backend/oauth/UserController.php';
require_once './backend/system/utils.php';


$database = Database::getInstance();

// Tüm kontrolörleri al
$controllers = $database->bootstrap();

// UserController'ı diziden al
$userController = $controllers['UserController'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = Utils::secure($_POST['name']);
    $surname = Utils::secure($_POST['surname']);
    $email = Utils::secure($_POST['email']);
    $password = Utils::secure($_POST['password']);
    $repassword = Utils::secure($_POST['repassword']);

    // Register metodunu çağır
    $response = $userController->register($name, $surname, $email, $password, $repassword);

    $responseData = json_decode($response, true);

    if ($responseData['status'] == 'success') {
        Utils::setMessage('register_message', $responseData['message']);
    }

    header('Content-Type: application/json; charset=utf-8');
    // JSON yanıtını döndür
    echo $response;
    exit;
}
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

    <!-- jQuery Validation CSS (hata mesajları için stil) -->
    <style>
        .validation-error {
            color: #e74c3c;
            margin-top: 0.35em;
            font-size: 0.875em;
        }

        .form-group {
            margin-bottom: 1.5rem;
            /* Form grupları arasında yeterli boşluk */
        }

        .form-row .col-md-6 .validation-error {
            width: 100%;
            /* Hata mesajı kolon genişliğine uyum sağlasın */
        }

        .is-invalid {
            border-color: #e74c3c !important;
            /* Hata durumunda kırmızı border */
        }

        .is-valid {
            border-color: #28a745 !important;
            /* Doğrulama başarılıysa yeşil border (isteğe bağlı) */
        }

        .password-strength-wrapper {
            margin-top: 10px;
        }

        .password-strength-wrapper .progress {
            height: 10px;
            border-radius: 5px;
        }

        .password-wrapper {
            display: flex;
            align-items: center;
        }

        .password-wrapper .form-control {
            margin-bottom: 0;
            /* Input ile progress bar arasındaki boşluğu kaldırmak için */
        }

        .toggle-password {
            cursor: pointer;
            font-size: 1.2em;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
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
                                    <?php if (isset($_GET['error'])): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <?php echo htmlspecialchars($_GET['error']); ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Giriş Yap</h1>
                                    </div>
                                    <form class="user" method="POST" id="registerForm" autocomplete="off">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control form-control-lg" name="name" autocomplete="rname"
                                                    id="inputName" placeholder="Ad">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control form-control-lg" name="surname" autocomplete="rsurname"
                                                    id="inputSurname" placeholder="Soyad">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-lg" name="email" autocomplete="off"
                                                id="inputEmail" placeholder="Email Adresi...">
                                        </div>
                                        <div class="form-group">
                                            <div class="row password-wrapper">
                                                <div class="col-10">
                                                    <input type="password" class="form-control form-control-lg" name="password"
                                                        id="inputPassword" placeholder="Parola">
                                                </div>
                                                <div class="col-2 toggle-password" id="togglePassword">
                                                    <i class="fas fa-eye"></i>
                                                </div>
                                            </div>
                                            <!-- Şifre gücü progress bar -->
                                            <div class="password-strength-wrapper">
                                                <div class="progress">
                                                    <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-lg" name="repassword"
                                                id="inputRepassword" placeholder="Parolanızı onaylayın">
                                        </div>
                                        <button type="submit" class="btn btn-secondary btn-user btn-block">
                                            Kayıt Ol
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="./login">Hızlı Giriş!</a>
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

    <!-- jQuery Validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

    <!-- Form Doğrulama Script'i -->
    <script>
        $(document).ready(function() {
            // Şifre gücünü hesaplayan fonksiyon
            function calculatePasswordStrength(password, name, surname, email) {
                let strength = 0;
                const lowerCase = /[a-z]/;
                const upperCase = /[A-Z]/;
                const numbers = /[0-9]/;
                const specialChars = /[^a-zA-Z0-9]/;

                // Ad, soyad veya e-posta içeriyorsa puan düşür
                if (name && password.toLowerCase().includes(name.toLowerCase())) {
                    strength -= 30;
                }
                if (surname && password.toLowerCase().includes(surname.toLowerCase())) {
                    strength -= 30;
                }
                if (email && password.toLowerCase().includes(email.toLowerCase().split('@')[0])) {
                    strength -= 30;
                }

                // Uzunluk kontrolü (en az 8 karakter)
                if (password.length >= 8) {
                    strength += 30;
                } else if (password.length > 0) {
                    strength += password.length * 2; // 8'den küçükse her karakter için 2 puan
                }

                // Küçük harf kontrolü
                if (lowerCase.test(password)) {
                    strength += 20;
                }

                // Büyük harf kontrolü
                if (upperCase.test(password)) {
                    strength += 20;
                }

                // Sayı kontrolü
                if (numbers.test(password)) {
                    strength += 20;
                }

                // Özel karakter kontrolü
                if (specialChars.test(password)) {
                    strength += 10;
                }

                // Arka arkaya sayılar kontrolü (1234, 4321 gibi)
                const sequenceNumbers = /(0123|1234|2345|3456|4567|5678|6789|9876|8765|7654|6543|5432|4321|3210)/;
                if (sequenceNumbers.test(password)) {
                    strength -= 20;
                }

                // Maksimum 100'e sınırla ve minimum 0'a sabitle
                strength = Math.max(0, Math.min(100, strength));

                return strength;
            }

            // Şifre gücünü kontrol eden özel doğrulama metodu
            $.validator.addMethod("strongPassword", function(value, element) {
                const name = $("#inputName").val();
                const surname = $("#inputSurname").val();
                const email = $("#inputEmail").val();
                const strength = calculatePasswordStrength(value, name, surname, email);
                return strength >= 70;
            }, "Güçsüz şifre. Lütfen güçlü bir şifre kullanın.");

            // Şifre input'una her yazıldığında çalışacak
            $("#inputPassword").on("input", function() {
                const password = $(this).val();
                const name = $("#inputName").val();
                const surname = $("#inputSurname").val();
                const email = $("#inputEmail").val();
                const strength = calculatePasswordStrength(password, name, surname, email);

                // Progress bar'ı güncelle
                $("#password-strength-bar")
                    .css("width", strength + "%")
                    .removeClass("bg-danger bg-warning bg-success")
                    .addClass(strength < 40 ? "bg-danger" : strength < 70 ? "bg-warning" : "bg-success")
                    .attr("aria-valuenow", strength);
            });

            // Şifre göster/gizle özelliği
            $("#togglePassword").on("click", function() {
                const $input = $("#inputPassword");
                const type = $input.attr("type") === "password" ? "text" : "password";
                $input.attr("type", type);
                $(this).find("i").toggleClass("fa-eye fa-eye-slash");
            });

            // jQuery Validation
            $("#registerForm").validate({
                errorClass: "validation-error",
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    surname: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        strongPassword: true
                    },
                    repassword: {
                        required: true,
                        equalTo: "#inputPassword"
                    }
                },
                messages: {
                    name: {
                        required: "Lütfen adınızı girin.",
                        minlength: "Adınız en az 2 karakter olmalı."
                    },
                    surname: {
                        required: "Lütfen soyadınızı girin.",
                        minlength: "Soyadınız en az 2 karakter olmalı."
                    },
                    email: {
                        required: "Lütfen e-posta adresinizi girin.",
                        email: "Geçerli bir e-posta adresi girin."
                    },
                    password: {
                        required: "Lütfen parolanızı girin.",
                        minlength: "Parolanız en az 8 karakter olmalı."
                    },
                    repassword: {
                        required: "Lütfen parolanızı onaylayın.",
                        equalTo: "Parolalar eşleşmiyor."
                    }
                },
                errorElement: "div",
                errorPlacement: function(error, element) {
                    error.addClass("validation-error");
                    if (element.attr("id") === "inputPassword") {
                        error.insertAfter(".password-wrapper");
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid").addClass("is-valid");
                },
                invalidHandler: function(form, validator) {
                    if (validator.errorList.length) {
                        $(validator.errorList[0].element).focus();
                    }
                },
                submitHandler: function(form) {
                    // Formun doğrulama başarılıysa AJAX ile gönder
                    $.ajax({
                        url: './register',
                        type: 'POST',
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                window.location.href = './login';
                            } else {
                                var errorMessage = $('<div>').addClass('alert alert-danger alert-dismissible fade show').attr('role', 'alert')
                                    .html(response.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>');
                                $('.p-5').prepend(errorMessage);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                    return false; // Formun varsayılan submit davranışını engelle
                }
            });

            // Kullanıcı yazarken input'un durumunu kontrol et
            $("#registerForm input").on("input", function() {
                var $input = $(this);
                if ($input.valid()) {
                    $input.removeClass("is-invalid").addClass("is-valid");
                } else {
                    $input.addClass("is-invalid").removeClass("is-valid");
                }
            });
        });
    </script>
</body>

</html>