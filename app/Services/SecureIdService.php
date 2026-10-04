<?php

namespace App\Services;

class SecureIdService
{
    protected static string $cipher = 'AES-128-CBC';
    protected static string $secretKey = 'MaisonSvalaLuxuryBag2026Key';

    /**
     * Encrypt numeric ID into a URL-friendly secure token
     */
    public static function encrypt(int|string $id): string
    {
        $iv = substr(hash('sha256', self::$secretKey), 0, 16);
        $encrypted = openssl_encrypt((string) $id, self::$cipher, self::$secretKey, 0, $iv);
        
        // URL-safe base64 without padding
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }

    /**
     * Decrypt URL-safe secure token back into numeric ID
     */
    public static function decrypt(string $token): ?int
    {
        // Support fallback if numeric ID is passed (internal transitions)
        if (is_numeric($token)) {
            return (int) $token;
        }

        try {
            $iv = substr(hash('sha256', self::$secretKey), 0, 16);
            $base64 = strtr($token, '-_', '+/');
            // Add padding if needed
            $remainder = strlen($base64) % 4;
            if ($remainder) {
                $base64 .= str_repeat('=', 4 - $remainder);
            }
            $decrypted = openssl_decrypt(base64_decode($base64), self::$cipher, self::$secretKey, 0, $iv);
            
            return is_numeric($decrypted) ? (int) $decrypted : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
