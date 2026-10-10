<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\HeroSlideModel;
use App\Models\LandingSettingModel;
use App\Models\TestimonialModel;

class LandingContent extends BaseController
{
    protected $slideModel;
    protected $settingModel;
    protected $testimonialModel;

    public function __construct()
    {
        $this->slideModel = new HeroSlideModel();
        $this->settingModel = new LandingSettingModel();
        $this->testimonialModel = new TestimonialModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $slides = $this->slideModel->orderBy('order_num', 'ASC')->findAll();
        $testimonials = $this->testimonialModel->orderBy('order_num', 'ASC')->findAll();
        $settings = LandingSettingModel::getMap();

        $data = [
            'title' => 'Pengaturan Landing Page',
            'slides' => $slides,
            'testimonials' => $testimonials,
            'settings' => $settings,
        ];

        return view('superadmin/landing/index', $data);
    }

    public function updateSettings()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $seconds = $this->request->getPost('slide_interval_seconds');
        if (is_numeric($seconds) && (int)$seconds >= 1) {
            LandingSettingModel::setVal('slide_interval_seconds', (string)(int)$seconds);
        }

        $announcement = $this->request->getPost('site_announcement');
        if ($announcement !== null) {
            LandingSettingModel::setVal('site_announcement', trim($announcement));
        }

        $whatsapp = $this->request->getPost('contact_whatsapp');
        if ($whatsapp !== null) {
            LandingSettingModel::setVal('contact_whatsapp', trim($whatsapp));
        }

        $phone = $this->request->getPost('contact_phone');
        if ($phone !== null) {
            LandingSettingModel::setVal('contact_phone', trim($phone));
        }

        $email = $this->request->getPost('contact_email');
        if ($email !== null) {
            LandingSettingModel::setVal('contact_email', trim($email));
        }

        $address = $this->request->getPost('contact_address');
        if ($address !== null) {
            LandingSettingModel::setVal('contact_address', trim($address));
        }

        $waCsUrl = $this->request->getPost('whatsapp_cs_url');
        if ($waCsUrl !== null) {
            LandingSettingModel::setVal('whatsapp_cs_url', trim($waCsUrl));
        }

        return redirect()->to('/superadmin/landing')->with('success', 'Pengaturan landing page berhasil disimpan!');
    }

    public function storeSlide()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        // Set memory limit and time limit for large uploads (up to 1GB)
        @ini_set('upload_max_filesize', '1024M');
        @ini_set('post_max_size', '1024M');
        @ini_set('max_execution_time', '600');
        @ini_set('max_input_time', '600');
        @ini_set('memory_limit', '512M');

        $mediaUrl = $this->request->getPost('image_url');
        $mediaType = 'image';

        $mediaFile = $this->request->getFile('media_file');
        if ($mediaFile && $mediaFile->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$mediaFile->isValid()) {
                return redirect()->to('/superadmin/landing')->with('error', 'Gagal upload file media: ' . $mediaFile->getErrorString());
            }
            $mime = $mediaFile->getMimeType();
            $ext = strtolower($mediaFile->getClientExtension());
            $isVideo = (strpos($mime, 'video/') === 0) || in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
            $mediaType = $isVideo ? 'video' : 'image';

            $newFileName = $mediaFile->getRandomName();
            $mediaFile->move(FCPATH . 'uploads/slides', $newFileName);
            $mediaUrl = base_url('uploads/slides/' . $newFileName);
        } else {
            // Cek apakah URL yang diinput adalah video
            if (!empty($mediaUrl) && preg_match('/\.(mp4|webm|ogg|mov)$/i', $mediaUrl)) {
                $mediaType = 'video';
            }
        }

        if (empty($mediaUrl)) {
            return redirect()->to('/superadmin/landing')->with('error', 'Silakan unggah file gambar/video atau masukkan URL.');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'badge_text' => $this->request->getPost('badge_text'),
            'description' => $this->request->getPost('description'),
            'image_url' => $mediaUrl,
            'media_type' => $mediaType,
            'cta_primary_text' => $this->request->getPost('cta_primary_text'),
            'cta_primary_url' => $this->request->getPost('cta_primary_url'),
            'cta_secondary_text' => $this->request->getPost('cta_secondary_text'),
            'cta_secondary_url' => $this->request->getPost('cta_secondary_url'),
            'order_num' => (int)$this->request->getPost('order_num'),
            'status' => $this->request->getPost('status') ?? 'aktif',
        ];

        $this->slideModel->insert($data);

        return redirect()->to('/superadmin/landing')->with('success', 'Slide baru berhasil ditambahkan!');
    }

    public function updateSlide($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        @ini_set('upload_max_filesize', '1024M');
        @ini_set('post_max_size', '1024M');
        @ini_set('max_execution_time', '600');
        @ini_set('max_input_time', '600');
        @ini_set('memory_limit', '512M');

        $slide = $this->slideModel->find($id);
        if (!$slide) {
            return redirect()->to('/superadmin/landing')->with('error', 'Slide tidak ditemukan.');
        }

        $mediaUrl = $this->request->getPost('image_url') ?: $slide['image_url'];
        $mediaType = $slide['media_type'] ?? 'image';

        $mediaFile = $this->request->getFile('media_file');
        if ($mediaFile && $mediaFile->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$mediaFile->isValid()) {
                return redirect()->to('/superadmin/landing')->with('error', 'Gagal upload file media: ' . $mediaFile->getErrorString());
            }
            $mime = $mediaFile->getMimeType();
            $ext = strtolower($mediaFile->getClientExtension());
            $isVideo = (strpos($mime, 'video/') === 0) || in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'avi']);
            $mediaType = $isVideo ? 'video' : 'image';

            $newFileName = $mediaFile->getRandomName();
            $mediaFile->move(FCPATH . 'uploads/slides', $newFileName);
            $mediaUrl = base_url('uploads/slides/' . $newFileName);

            // Hapus file lama jika disimpan di uploads/slides
            $oldFilename = basename($slide['image_url']);
            $oldPath = FCPATH . 'uploads/slides/' . $oldFilename;
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        } elseif (!empty($this->request->getPost('image_url'))) {
            $inputUrl = $this->request->getPost('image_url');
            if (preg_match('/\.(mp4|webm|ogg|mov)$/i', $inputUrl)) {
                $mediaType = 'video';
            } else {
                $mediaType = 'image';
            }
            $mediaUrl = $inputUrl;
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'subtitle' => $this->request->getPost('subtitle'),
            'badge_text' => $this->request->getPost('badge_text'),
            'description' => $this->request->getPost('description'),
            'image_url' => $mediaUrl,
            'media_type' => $mediaType,
            'cta_primary_text' => $this->request->getPost('cta_primary_text'),
            'cta_primary_url' => $this->request->getPost('cta_primary_url'),
            'cta_secondary_text' => $this->request->getPost('cta_secondary_text'),
            'cta_secondary_url' => $this->request->getPost('cta_secondary_url'),
            'order_num' => (int)$this->request->getPost('order_num'),
            'status' => $this->request->getPost('status') ?? 'aktif',
        ];

        $this->slideModel->update($id, $data);

        return redirect()->to('/superadmin/landing')->with('success', 'Slide berhasil diperbarui!');
    }

    public function deleteSlide($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $slide = $this->slideModel->find($id);
        if (!$slide) {
            return redirect()->to('/superadmin/landing')->with('error', 'Slide tidak ditemukan.');
        }

        // Hapus file dari folder uploads jika ada
        if (!empty($slide['image_url'])) {
            $oldFilename = basename($slide['image_url']);
            $oldPath = FCPATH . 'uploads/slides/' . $oldFilename;
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $this->slideModel->delete($id);

        return redirect()->to('/superadmin/landing')->with('success', 'Slide berhasil dihapus!');
    }

    public function storeTestimonial()
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $fotoUrl = null;
        $fotoFile = $this->request->getFile('foto_file');
        if ($fotoFile && $fotoFile->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$fotoFile->isValid()) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto: ' . $fotoFile->getErrorString());
            }

            $uploadDir = FCPATH . 'uploads/testimonials';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $newFileName = $fotoFile->getRandomName();
            $fotoFile->move($uploadDir, $newFileName);
            $fotoUrl = base_url('uploads/testimonials/' . $newFileName);
        }

        $data = [
            'nama_jamaah' => trim((string)$this->request->getPost('nama_jamaah')),
            'foto' => $fotoUrl,
            'program_paket' => trim((string)($this->request->getPost('program_paket') ?: 'Umroh Reguler')),
            'tahun_keberangkatan' => trim((string)($this->request->getPost('tahun_keberangkatan') ?: date('Y'))),
            'kota_asal' => trim((string)($this->request->getPost('kota_asal') ?: 'Indonesia')),
            'testimoni' => trim((string)$this->request->getPost('testimoni')),
            'rating' => (int)($this->request->getPost('rating') ?: 5),
            'order_num' => (int)($this->request->getPost('order_num') ?: 1),
            'status' => $this->request->getPost('status') ?? 'aktif',
        ];

        $this->testimonialModel->insert($data);

        return redirect()->to('/superadmin/landing')->with('success', 'Testimoni jamaah berhasil ditambahkan!');
    }

    public function updateTestimonial($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $existing = $this->testimonialModel->find($id);
        if (!$existing) {
            return redirect()->to('/superadmin/landing')->with('error', 'Testimoni tidak ditemukan.');
        }

        $fotoUrl = $existing['foto'];
        $fotoFile = $this->request->getFile('foto_file');
        if ($fotoFile && $fotoFile->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$fotoFile->isValid()) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengunggah foto: ' . $fotoFile->getErrorString());
            }

            $uploadDir = FCPATH . 'uploads/testimonials';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            if (!empty($existing['foto'])) {
                $oldFile = basename($existing['foto']);
                $oldPath = $uploadDir . '/' . $oldFile;
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $newFileName = $fotoFile->getRandomName();
            $fotoFile->move($uploadDir, $newFileName);
            $fotoUrl = base_url('uploads/testimonials/' . $newFileName);
        }

        $data = [
            'nama_jamaah' => trim((string)$this->request->getPost('nama_jamaah')),
            'foto' => $fotoUrl,
            'program_paket' => trim((string)($this->request->getPost('program_paket') ?: 'Umroh Reguler')),
            'tahun_keberangkatan' => trim((string)($this->request->getPost('tahun_keberangkatan') ?: date('Y'))),
            'kota_asal' => trim((string)($this->request->getPost('kota_asal') ?: 'Indonesia')),
            'testimoni' => trim((string)$this->request->getPost('testimoni')),
            'rating' => (int)($this->request->getPost('rating') ?: 5),
            'order_num' => (int)($this->request->getPost('order_num') ?: 1),
            'status' => $this->request->getPost('status') ?? 'aktif',
        ];

        $this->testimonialModel->update($id, $data);

        return redirect()->to('/superadmin/landing')->with('success', 'Testimoni jamaah berhasil diperbarui!');
    }

    public function deleteTestimonial($id)
    {
        if (!session('logged_in') || session('role') !== 'super_admin') {
            return redirect()->to('/login');
        }

        $testimonial = $this->testimonialModel->find($id);
        if (!$testimonial) {
            return redirect()->to('/superadmin/landing')->with('error', 'Testimoni tidak ditemukan.');
        }

        if (!empty($testimonial['foto'])) {
            $oldFilename = basename($testimonial['foto']);
            $oldPath = FCPATH . 'uploads/testimonials/' . $oldFilename;
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $this->testimonialModel->delete($id);

        return redirect()->to('/superadmin/landing')->with('success', 'Testimoni jamaah berhasil dihapus!');
    }
}
