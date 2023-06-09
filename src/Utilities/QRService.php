<?php

namespace App\Utility;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer as BaconQrCodeWriter;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QRService
{
    /**
     * @var ImageBackEndInterface|null $imageBackEnd
     */
    protected $imageBackEnd;

    /**
     * Google2FA constructor.
     *
     * @param ImageBackEndInterface|null $imageBackEnd
     */
    public function __construct($imageBackEnd = null)
    {
        $this->imageBackEnd = $imageBackEnd;
    }

    /**
     * Generates a QR code data url to display inline.
     *
     * @param string $string
     * @param int    $size
     * @param string $encoding Default to UTF-8
     *
     * @return string
     */
    public function getQRCodeInline($string, $size = 200, $encoding = 'utf-8')
    {
        $renderer = new ImageRenderer(
            (new RendererStyle($size))->withSize($size),
            $this->getImageBackEnd()
        );

        $bacon = new Writer($renderer);

        $data = $bacon->writeString($string, $encoding);

        if ($this->getImageBackEnd() instanceof ImagickImageBackEnd) {
            return 'data:image/png;base64,' . base64_encode($data);
        }

        return $data;
    }

    /**
     * Check if Imagick is available
     *
     * @return int
     */
    public function imagickIsAvailable()
    {
        return extension_loaded('imagick');
    }

    /**
     * Get image backend
     *
     * @return ImageRenderer
     */
    public function getImageBackend()
    {
        if (empty($this->imageBackEnd)) {
            $this->imageBackEnd = !$this->imagickIsAvailable()
                ? new SvgImageBackEnd()
                : new ImagickImageBackEnd();
        }

        $this->setImageBackEnd($this->imageBackEnd);

        return $this->imageBackEnd;
    }

    /**
     * Set image backend
     *
     * @param $imageBackEnd
     * @return $this
     */
    public function setImageBackend($imageBackEnd)
    {
        $this->imageBackEnd = $imageBackEnd;
        return $this;
    }


    public function getQRCodeUrl($company, $holder, $secret)
    {
        return 'otpauth://totp/'.
            rawurlencode($company).
            ':'.
            rawurlencode($holder).
            '?secret='.
            $secret.
            '&issuer='.
            rawurlencode($company).
            '&algorithm='.
            rawurlencode(strtoupper('sha1')).
            '&digits='.
            rawurlencode(strtoupper('6')).
            '&period='.
            rawurlencode(strtoupper('30')).
            '';
    }

    public function generateQRCode($company, $userEmail, $secret)
    {
        $qrCodeUrl = $this->getQRCodeUrl($company, $userEmail, $secret);
        $qrCode = $this->getQRCodeInline($qrCodeUrl);
        return $qrCode;
    }
}
