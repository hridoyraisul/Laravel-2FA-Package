<?php

namespace App\Utility;


use RaisulHridoy\Laravel2FA\Exceptions\CharacterCountException;
use RaisulHridoy\Laravel2FA\Exceptions\GoogleAuthenticateFailedException;
use RaisulHridoy\Laravel2FA\Exceptions\SecretKeyException;

class SecretKeyService
{
    const VALID_FOR_B32 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    const VALID_FOR_B32_SCRAMBLED = '234567QWERTYUIOPASDFGHJKLZXCVBNM';
    const SHA1 = 'sha1';
    const SHA256 = 'sha256';
    const SHA512 = 'sha512';
    private $enforceGoogleAuthenticatorCompatibility = true;
    private function generateBase32RandomKey($length = 16): string
    {
        $validChars = $this::VALID_FOR_B32;
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $validChars[random_int(0, strlen($validChars) - 1)];
        }
        $this->validateSecret($secret);
        return $secret;
    }
    private function validateSecret($b32)
    {
        if (preg_replace('/[^'.$this::VALID_FOR_B32.']/', '', $b32) !== $b32)
        {
            throw new SecretKeyException();
        }
        if ($this->enforceGoogleAuthenticatorCompatibility && $this->isCharCountNotAPowerOfTwo($b32))  // Google Authenticator requires it to be a power of 2 base32 length string
        {
            throw new GoogleAuthenticateFailedException();
        }
        if ($this->charCountBits($b32) < 128) {
           throw new CharacterCountException();
        }
    }
    private function isCharCountNotAPowerOfTwo($b32): bool
    {
        return (strlen($b32) & (strlen($b32) - 1)) !== 0;
    }
    private function charCountBits($b32)
    {
        return strlen($b32) * 8;
    }
    private function base32_decode(string $input): string
    {
        $base32chars = $this::VALID_FOR_B32;
        $base32lookup = array_flip(str_split($base32chars));
        $input = rtrim($input, '=');
        $padding = strlen($input) % 8;
        if ($padding !== 0) {
            $input .= str_repeat('=', 8 - $padding);
        }
        $output = '';
        $input = str_split($input, 8);
        foreach ($input as $chunk) {
            $chunk = str_split($chunk);
            $buffer = 0;
            $bufferLength = 0;
            foreach ($chunk as $char) {
                $buffer = ($buffer << 5) | $base32lookup[$char];
                $bufferLength += 5;
                if ($bufferLength >= 8) {
                    $output .= chr(($buffer >> ($bufferLength - 8)) & 255);
                    $bufferLength -= 8;
                }
            }
        }
        return $output;
    }
    private function getOTP($secretKey, $otpValidityInterval = 30)
    {
        $decodedSecretKey = $this->base32_decode($secretKey);
        $currentTimestamp = time();
        $counter = floor($currentTimestamp / $otpValidityInterval);
        $binaryCounter = pack('N*', 0) . pack('N*', $counter);
        $hash = hash_hmac('sha1', $binaryCounter, $decodedSecretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $binaryCode = substr($hash, $offset, 4);
        $otp = unpack('N', $binaryCode)[1];
        $otp = $otp & 0x7FFFFFFF;
        return str_pad($otp % 1000000, 6, '0', STR_PAD_LEFT);
    }


    /**
     * @return string
     */
    public function generateSecretKey(): string
    {
        return $this->generateBase32RandomKey(16);
    }

    /**
     * @param $secret
     * @return string|null
     */
    public function generateOTP($secret): ?string
    {
        try {
            $secretKey = $secret??'';
            return $this->getOTP($secretKey,30);
        } catch (\Exception $e){
            return null;
        }
    }

    /**
     * @param $secret
     * @param $userInputOTP
     * @return bool
     */
    public function verifyOTPWithSecret($secret, $userInputOTP): bool
    {
        try {
            $secretKey = $secret??'';
            $otp = $this->getOTP($secretKey,30);
            return $otp === $userInputOTP;
        } catch (\Exception $e) {
            return false;
        }
    }



}
