<?php
class Utils
{
    public static function getCurrentRoutePath(string $basePath)
    {
        $requestUri = $_SERVER['REQUEST_URI'];

        // Sorgu parametrelerini temizle (?param=123 vs)
        $requestUri = parse_url($requestUri, PHP_URL_PATH);

        // basePath'i temizle
        $path = str_replace($basePath, '', $requestUri);

        // Baştaki ve sondaki / karakterlerini temizle
        $path = trim($path, '/');

        return ($path === '' || $path === '/') ? '' : $path;
    }

    public static function isActiveRoute(string $route): string
    {
        global $basePath; // Eğer dışarıda tanımlıysa
        return Utils::getCurrentRoutePath($basePath) === $route ? 'active' : '';
    }

    public static function generateGuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public static function setMessage($key, $value)
    {
        $_SESSION['flash'][$key] = $value;
    }

    public static function getMessage($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return null;
    }

    public static function secure(string $text)
    {
        // Alanın varlığını kontrol et, yoksa boş string ata
        $value = isset($text) ? $text : '';

        // Süzgeçten geçir: trim ve htmlspecialchars
        $value = trim($value);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        // Temizlenmiş veriyi diziye ekle
        $filteredData = $value;

        return $filteredData;
    }

    public static function base64UrlEncode($data)
    {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, '+/', '-_');
        return rtrim($base64Url, '=');
    }

    public static function base64UrlDecode($data)
    {
        $base64 = strtr($data, '-_', '+/');
        $base64Padded = str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($base64Padded);
    }

    public static function createJwt($payload, $secret)
    {
        // Benzersiz bir jti ekle (isteğe bağlı olarak generateGuid kullanılabilir)
        if (!isset($payload['jti'])) {
            $payload['jti'] = self::generateGuid();
        }

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $encodedHeader = self::base64UrlEncode(json_encode($header));
        $encodedPayload = self::base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $secret, true);
        $encodedSignature = self::base64UrlEncode($signature);

        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }

    public static function verifyJwt($jwt, $secret)
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return false;
        }

        list($encodedHeader, $encodedPayload, $encodedSignature) = $parts;

        $signature = self::base64UrlDecode($encodedSignature);
        $expectedSignature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $secret, true);

        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }

        $header = json_decode(self::base64UrlDecode($encodedHeader), true);
        if (!$header || $header['alg'] !== 'HS256' || $header['typ'] !== 'JWT') {
            return false;
        }

        $payload = json_decode(self::base64UrlDecode($encodedPayload), true);
        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }
}
