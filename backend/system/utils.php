<?php
class Utils {
    /**
     * Benzersiz bir GUID (UUID v4) oluşturur.
     * @return string Oluşturulan GUID
     */
    public static function generateGuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 65535), mt_rand(0, 65535), // 32 bit
            mt_rand(0, 65535), // 16 bit
            mt_rand(16384, 20479), // 16 bit (version 4)
            mt_rand(32768, 49151), // 16 bit (variant)
            mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535) // 48 bit
        );
    }
}
?>