<?php

namespace App\Helpers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QRMarkupSVG;

class QrHelper
{
    /**
     * Generate QR code sebagai inline SVG (tidak butuh GD extension)
     * Ukuran besar agar mudah dibaca scanner kamera
     */
    public static function toSvg(string $data, int $scale = 10): string
    {
        $options = new QROptions([
            'outputInterface'      => QRMarkupSVG::class,
            'eccLevel'             => EccLevel::M,
            'scale'                => $scale,
            'quietzoneSize'        => 4,
            'svgAddXmlHeader'      => false,
            'svgUseFillAttributes' => true,
            'outputBase64'         => false,
            // Warna hitam putih kontras tinggi
            'moduleValues'         => [
                // dark modules = hitam
                1536 => '#000000',
                // light modules = putih
                6    => '#ffffff',
            ],
        ]);

        return (new QRCode($options))->render($data);
    }

    /**
     * Generate QR code sebagai base64 data URI SVG untuk PDF
     */
    public static function toDataUri(string $data, int $scale = 8): string
    {
        $options = new QROptions([
            'outputInterface'      => QRMarkupSVG::class,
            'eccLevel'             => EccLevel::M,
            'scale'                => $scale,
            'quietzoneSize'        => 4,
            'svgAddXmlHeader'      => true,
            'svgUseFillAttributes' => true,
            'outputBase64'         => true,
        ]);

        return (new QRCode($options))->render($data);
    }
}
