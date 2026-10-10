<?php

namespace App\Controllers;

use App\Models\PackageModel;
use App\Models\HeroSlideModel;
use App\Models\LandingSettingModel;
use App\Models\TestimonialModel;

class Home extends BaseController
{
    public function index(): string
    {
        $packageModel = new PackageModel();
        $packages = $packageModel->where('status', 'aktif')->orderBy('id', 'DESC')->limit(6)->findAll();

        $slideModel = new HeroSlideModel();
        $slides = $slideModel->where('status', 'aktif')->orderBy('order_num', 'ASC')->findAll();

        $testimonialModel = new TestimonialModel();
        $testimonials = $testimonialModel->where('status', 'aktif')->orderBy('order_num', 'ASC')->findAll();

        $settings = LandingSettingModel::getMap();

        $data = [
            'title' => 'Amanah Safar - Perjalanan Ibadah Umroh & Haji Terpercaya',
            'packages' => $packages,
            'slides' => $slides,
            'testimonials' => $testimonials,
            'settings' => $settings,
        ];

        return view('landing', $data);
    }
}
