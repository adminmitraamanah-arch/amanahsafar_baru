<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;

class MediaHelper
{
    // Maksimal ukuran upload: 600 MB (dalam bytes)
    public const MAX_FILE_SIZE = 629145600; // 600 * 1024 * 1024

    protected static array $imageMimes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/webp'
    ];

    protected static array $videoMimes = [
        'video/mp4', 'video/webm', 'video/quicktime', 'video/x-matroska', 'video/ogg'
    ];

    /**
     * Memproses upload berkas gambar atau video dengan kompresi otomatis untuk gambar.
     */
    public static function handleUpload(?UploadedFile $file): array
    {
        if (!$file || !$file->isValid()) {
            return ['success' => false, 'error' => 'File tidak valid atau gagal diunggah'];
        }

        if ($file->hasMoved()) {
            return ['success' => false, 'error' => 'File sudah dipindahkan'];
        }

        $size = $file->getSize();
        if ($size > self::MAX_FILE_SIZE) {
            return ['success' => false, 'error' => 'Ukuran file melebihi batas maksimal 600 MB'];
        }

        $mime = $file->getMimeType();
        $isImage = in_array($mime, self::$imageMimes, true) || str_starts_with($mime, 'image/');
        $isVideo = in_array($mime, self::$videoMimes, true) || str_starts_with($mime, 'video/');

        if (!$isImage && !$isVideo) {
            return ['success' => false, 'error' => 'Format file tidak didukung. Harap upload gambar (JPG/PNG/WEBP) atau video (MP4/WEBM/MOV)'];
        }

        $type = $isImage ? 'image' : 'video';
        $originalName = $file->getClientName();
        $targetDir = FCPATH . 'uploads/tasks';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = strtolower($file->getClientExtension());
        if (empty($extension)) {
            $extension = $isImage ? 'jpg' : 'mp4';
        }

        $randomName = $file->getRandomName();

        // Kompresi otomatis untuk gambar jika GD aktif
        if ($isImage && extension_loaded('gd')) {
            $tempPath = $file->getTempName();
            $destPath = $targetDir . DIRECTORY_SEPARATOR . $randomName;
            $compressed = self::compressImage($tempPath, $destPath, $mime);

            if ($compressed) {
                $finalSize = filesize($destPath);
                return [
                    'success' => true,
                    'file_path' => $randomName,
                    'file_type' => $type,
                    'file_name' => $originalName,
                    'file_size' => $finalSize,
                ];
            }
        }

        // Jika video atau kompresi gambar fallback: pindahkan langsung
        $file->move($targetDir, $randomName);
        $finalSize = filesize($targetDir . DIRECTORY_SEPARATOR . $randomName);

        return [
            'success' => true,
            'file_path' => $randomName,
            'file_type' => $type,
            'file_name' => $originalName,
            'file_size' => $finalSize,
        ];
    }

    /**
     * Kompres dan optimasi gambar secara otomatis (mengurangi dimensi jika > 1920px dan mengoptimasi kualitas)
     */
    public static function compressImage(string $sourcePath, string $destPath, string $mime): bool
    {
        try {
            $img = null;
            if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                $img = @imagecreatefromjpeg($sourcePath);
            } elseif ($mime === 'image/png') {
                $img = @imagecreatefrompng($sourcePath);
            } elseif ($mime === 'image/webp') {
                $img = @imagecreatefromwebp($sourcePath);
            }

            if (!$img) {
                return false;
            }

            $origWidth = imagesx($img);
            $origHeight = imagesy($img);

            // Resize jika dimensi melebihi batas 1920px untuk menghemat ukuran penyimpanan
            $maxDim = 1920;
            if ($origWidth > $maxDim || $origHeight > $maxDim) {
                if ($origWidth > $origHeight) {
                    $newWidth = $maxDim;
                    $newHeight = (int)round(($origHeight / $origWidth) * $maxDim);
                } else {
                    $newHeight = $maxDim;
                    $newWidth = (int)round(($origWidth / $origHeight) * $maxDim);
                }

                $resized = imagecreatetruecolor($newWidth, $newHeight);

                // Preserve alpha/transparency untuk PNG/WebP
                if ($mime === 'image/png' || $mime === 'image/webp') {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                    imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
                }

                imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagedestroy($img);
                $img = $resized;
            }

            // Simpan gambar dengan kualitas optimal (82% quality)
            $success = false;
            if ($mime === 'image/png') {
                imagealphablending($img, false);
                imagesavealpha($img, true);
                $success = imagepng($img, $destPath, 7); // kompresi 0-9
            } elseif ($mime === 'image/webp') {
                $success = imagewebp($img, $destPath, 82);
            } else {
                $success = imagejpeg($img, $destPath, 82);
            }

            imagedestroy($img);
            return $success;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Hapus berkas fisik jika ada di disk
     */
    public static function deleteFile(?string $filePath): void
    {
        if (empty($filePath)) {
            return;
        }

        $fullPath = FCPATH . 'uploads/tasks/' . $filePath;
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    /**
     * Output file download yang aman dengan chunk streaming untuk berkas besar (hingga 600MB)
     */
    public static function download(string $filePath, ?string $downloadName = null): void
    {
        $fullPath = FCPATH . 'uploads/tasks/' . $filePath;
        if (!file_exists($fullPath) || !is_file($fullPath)) {
            header('HTTP/1.0 404 Not Found');
            echo 'Berkas tidak ditemukan di server.';
            exit;
        }

        $filename = !empty($downloadName) ? $downloadName : basename($filePath);
        // Sanitize download filename
        $filename = str_replace(['"', "'", "\r", "\n"], '', $filename);

        $size = filesize($fullPath);
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';

        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $size);

        // Chunked readfile agar aman untuk memory RAM server
        $chunkSize = 1024 * 1024 * 4; // 4MB per chunk
        $handle = fopen($fullPath, 'rb');
        if ($handle === false) {
            exit;
        }

        while (!feof($handle)) {
            echo fread($handle, $chunkSize);
            flush();
        }

        fclose($handle);
        exit;
    }
}
