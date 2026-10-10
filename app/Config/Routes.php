<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public routes
$routes->get('/', 'Home::index');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');
$routes->get('/logout', 'Auth::logout');

// Auth required
$routes->get('/verify', 'Auth::verify');
$routes->post('/verify', 'Auth::verify');
$routes->get('/agreement', 'Auth::agreement');
$routes->post('/agreement', 'Auth::agreement');

// Dashboard
$routes->get('/dashboard', 'Dashboard::index');

// Katalog & Order
$routes->get('/catalog', 'Catalog::index');
$routes->get('/catalog/(:num)', 'Catalog::detail/$1');
$routes->post('/order', 'Order::create');
$routes->get('/orders', 'Order::myOrders');
$routes->get('/orders/(:num)', 'Order::detail/$1');

// Super Admin
$routes->group('superadmin', ['filter' => 'roleFilter:super_admin'], function($routes) {
    $routes->get('/', 'SuperAdmin\Dashboard::index');
    $routes->get('vouchers', 'SuperAdmin\Voucher::index');
    $routes->post('vouchers/generate', 'SuperAdmin\Voucher::generate');
    $routes->post('vouchers/disable/(:num)', 'SuperAdmin\Voucher::disable/$1');
    $routes->get('packages', 'SuperAdmin\Package::index');
    $routes->get('packages/create', 'SuperAdmin\Package::create');
    $routes->post('packages/store', 'SuperAdmin\Package::store');
    $routes->get('packages/(:num)/edit', 'SuperAdmin\Package::edit/$1');
    $routes->post('packages/(:num)/update', 'SuperAdmin\Package::update/$1');
    $routes->post('packages/(:num)/delete', 'SuperAdmin\Package::delete/$1');
    $routes->get('users', 'SuperAdmin\User::index');
    $routes->post('users/update/(:num)', 'SuperAdmin\User::update/$1');
    $routes->post('users/deactivate/(:num)', 'SuperAdmin\User::deactivate/$1');
    $routes->post('users/activate/(:num)', 'SuperAdmin\User::activate/$1');
    $routes->get('commissions', 'SuperAdmin\Commission::index');
    $routes->get('tasks', 'SuperAdmin\Task::index');
    $routes->post('tasks/store', 'SuperAdmin\Task::store');
    $routes->get('verifications', 'SuperAdmin\Verification::index');
    
    // Landing Page Management
    $routes->get('landing', 'SuperAdmin\LandingContent::index');
    $routes->post('landing/settings', 'SuperAdmin\LandingContent::updateSettings');
    $routes->post('landing/slides/store', 'SuperAdmin\LandingContent::storeSlide');
    $routes->post('landing/slides/update/(:num)', 'SuperAdmin\LandingContent::updateSlide/$1');
    $routes->get('landing/slides/delete/(:num)', 'SuperAdmin\LandingContent::deleteSlide/$1');
    $routes->post('landing/testimonials/store', 'SuperAdmin\LandingContent::storeTestimonial');
    $routes->post('landing/testimonials/update/(:num)', 'SuperAdmin\LandingContent::updateTestimonial/$1');
    $routes->get('landing/testimonials/delete/(:num)', 'SuperAdmin\LandingContent::deleteTestimonial/$1');
});

// Admin
$routes->group('admin', ['filter' => 'roleFilter:admin'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('packages', 'Admin\Package::index');
    $routes->get('packages/create', 'Admin\Package::create');
    $routes->post('packages/store', 'Admin\Package::store');
    $routes->get('packages/(:num)/edit', 'Admin\Package::edit/$1');
    $routes->post('packages/(:num)/update', 'Admin\Package::update/$1');
    $routes->get('tasks', 'Admin\Task::index');
    $routes->post('tasks/store', 'Admin\Task::store');
});

// Member (Pusat, Cabang, Manager, Mitra)
$routes->group('app', ['filter' => 'roleFilter:pusat,cabang,manager_mitra,mitra'], function($routes) {
    $routes->get('dashboard', 'Member\Dashboard::index');
    $routes->get('binaan', 'Member\Binaan::index');
    $routes->get('orders', 'Member\Order::index');
    $routes->get('orders/(:num)', 'Member\Order::detail/$1');
    $routes->get('tasks', 'Member\Task::index');
});
