<?php

namespace App\Libraries;

class RegistrationWhatsApp
{
    public static function normalizeNumber(string $raw): string
    {
        $number = preg_replace('/[\s().-]+/', '', trim($raw));
        if (!preg_match('/^\+?[0-9]+$/D', $number)) {
            throw new \InvalidArgumentException('Nomor WhatsApp konsultasi belum tersedia atau tidak valid. Silakan hubungi pengelola.');
        }
        if (str_starts_with($number, '+')) {
            $number = substr($number, 1);
        } elseif (str_starts_with($number, '00')) {
            $number = substr($number, 2);
        } elseif (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }
        if (!preg_match('/^[1-9][0-9]{7,14}$/D', $number)) {
            throw new \InvalidArgumentException('Format nomor WhatsApp konsultasi tidak valid. Silakan hubungi pengelola.');
        }
        return $number;
    }

    public static function buildUrl(string $number, array $user, ?string $pksNumber): string
    {
        $number = self::normalizeNumber($number);
        $message = "Assalamu'alaikum warahmatullahi wabarakatuh.\n\n"
            . "Saya ingin mengonfirmasi pendaftaran Mitra Amanah dan menyatakan bahwa saya telah menyelesaikan tahap persetujuan PKS.\n\n"
            . 'Nama: ' . $user['nama_lengkap'] . "\n"
            . 'Nomor HP: ' . $user['no_wa'] . "\n";
        if ($pksNumber !== null && $pksNumber !== '') {
            $message .= 'Nomor PKS: ' . $pksNumber . "\n";
        }
        $message .= "\nMohon konfirmasi dan arahan untuk tahap selanjutnya.\n\nTerima kasih.";
        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
    }
}
