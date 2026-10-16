<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (!function_exists('format_tanggal_indonesia')) {
    /**
     * Format tanggal ke dalam format bahasa Indonesia (WIB / Asia/Jakarta)
     *
     * @param string|int|\DateTimeInterface|null $datetime
     * @param bool $includeDay
     * @return string
     */
    function format_tanggal_indonesia($datetime = null, bool $includeDay = true): string
    {
        $timezone = new \DateTimeZone('Asia/Jakarta');
        if (empty($datetime)) {
            $dt = new \DateTime('now', $timezone);
        } elseif ($datetime instanceof \DateTimeInterface) {
            $dt = (clone $datetime)->setTimezone($timezone);
        } elseif (is_numeric($datetime)) {
            $dt = (new \DateTime('@' . $datetime))->setTimezone($timezone);
        } else {
            try {
                $dt = new \DateTime($datetime, $timezone);
            } catch (\Throwable $e) {
                $dt = new \DateTime('now', $timezone);
            }
        }

        $days = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];

        $months = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $dayName   = $days[$dt->format('l')] ?? '';
        $dayNum    = $dt->format('j');
        $monthName = $months[(int)$dt->format('n')] ?? '';
        $yearNum   = $dt->format('Y');

        if ($includeDay) {
            return "{$dayName}, tanggal {$dayNum} {$monthName} {$yearNum}";
        }

        return "{$dayNum} {$monthName} {$yearNum}";
    }
}

