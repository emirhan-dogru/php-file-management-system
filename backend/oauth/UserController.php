<?php
require_once __DIR__ . '/../system/connection.php';
require_once __DIR__ . '/../system/utils.php';
require_once __DIR__ . '/../system/settings.php';

class UserController
{
    private $pdo;
    private $jwtSecret;
    private $domain;

    public function __construct($pdo)
    {
        global $domain, $jwtSecret;

        $this->pdo = $pdo;
        $this->domain = $domain;
        $this->jwtSecret = $jwtSecret;
    }

    public function login($email, $password)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id, email, password, username FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Eski token'ları geçersiz kıl
                $stmt = $this->pdo->prepare("UPDATE tokens SET is_valid = 0 WHERE user_id = :user_id AND is_valid = 1");
                $stmt->execute([':user_id' => $user['id']]);

                $payload = [
                    'iss' => $this->domain,
                    'aud' => $this->domain,
                    'iat' => time(),
                    'exp' => time() + 3600, // 1 saat
                    'user_id' => $user['id'],
                    'email' => $user['email'],
                    'username' => $user['username']
                    // jti, Utils::createJwt tarafından otomatik eklenecek
                ];

                $jwt = Utils::createJwt($payload, $this->jwtSecret);

                // Token'ı veritabanına kaydet
                $expiresAt = date('Y-m-d H:i:s', $payload['exp']);
                $stmt = $this->pdo->prepare("INSERT INTO tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
                $stmt->execute([
                    ':user_id' => $user['id'],
                    ':token' => $jwt,
                    ':expires_at' => $expiresAt
                ]);

                return json_encode([
                    'status' => 'success',
                    'message' => 'Giriş başarılı.',
                    'token' => $jwt,
                    'user' => [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'username' => $user['username']
                    ]
                ], JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Geçersiz e-posta veya parola.'
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (PDOException $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Veritabanı hatası: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function verifyToken($token)
    {
        try {
            // Önce JWT imzasını ve süresini kontrol et
            $payload = Utils::verifyJwt($token, $this->jwtSecret);
            if (!$payload) {
                return false;
            }

            // Veritabanında token'ın geçerliliğini kontrol et
            $stmt = $this->pdo->prepare("SELECT * FROM tokens WHERE token = :token AND user_id = :user_id AND is_valid = 1 AND expires_at > NOW()");
            $stmt->execute([
                ':token' => $token,
                ':user_id' => $payload['user_id']
            ]);
            $tokenRecord = $stmt->fetch();

            if ($tokenRecord) {
                return $payload;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Token doğrulama hatası: ' . $e->getMessage());
            return false;
        }
    }

    public function invalidateToken($token)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE tokens SET is_valid = 0 WHERE token = :token");
            $stmt->execute([':token' => $token]);
            return true;
        } catch (PDOException $e) {
            error_log('Token iptal hatası: ' . $e->getMessage());
            return false;
        }
    }
}