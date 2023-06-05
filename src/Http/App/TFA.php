<?php

namespace RaisulHridoy\Laravel2FA\Http\App;

use App\Utility\QRService;
use App\Utility\SecretKeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TFA
{

    public static function generateSecretKey()
    {
        try {
            $keyInstance = new SecretKeyService();
            return $keyInstance->generateSecretKey();
        } catch (\Exception $e){
            throw new \Exception($e->getMessage(). 'in line number '.$e->getLine(). ' in file '.$e->getFile());
        }
    }

    public static function generateQRCode($appName, $clientIdentifier, $secretKey)
    {
        try {
            $appName = urlencode($appName);
            $clientIdentifier = urlencode($clientIdentifier);
            $qrInstance = new QRService();
            return $qrInstance->generateQRCode($appName, $clientIdentifier, $secretKey);
        } catch (\Exception $e){
            throw new \Exception($e->getMessage(). 'in line number '.$e->getLine(). ' in file '.$e->getFile());
        }
    }

    public static function generateOTP($secretKey)
    {
        try {
            $keyInstance = new SecretKeyService();
            return $keyInstance->generateOTP($secretKey);
        } catch (\Exception $e){
            throw new \Exception($e->getMessage(). 'in line number '.$e->getLine(). ' in file '.$e->getFile());
        }
    }

    public static function verifyOTPWithSecret($code, $secretKey)
    {
        try {
            $keyInstance = new SecretKeyService();
            return $keyInstance->verifyOTPWithSecret($code, $secretKey);
        } catch (\Exception $e){
            throw new \Exception($e->getMessage(). 'in line number '.$e->getLine(). ' in file '.$e->getFile());
        }
    }

}
