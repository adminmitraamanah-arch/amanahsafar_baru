<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public routes
$routes->get('/', 'Home::index');
$routes->get('/maintenance', 'Maintenance::index');
$routes->get('/login', 'Auth::login', ['filter' => 'portalMaintenance']);
$routes->post('/login', 'Auth::login', ['filter' => 'portalMaintenance']);
$routes->get('/register', 'Auth::register', ['filter' => 'portalMaintenance']);
$routes->post('/register', 'Auth::register', ['filter' => 'portalMaintenance']);
$routes->get('/logout', 'Auth::logout');
$routes->get('/superadmin/login', 'Auth::login');
$routes->post('/superadmin/login', 'Auth::login');

// Auth required
$routes->get('/verify', 'Auth::verify', ['filter' => 'portalMaintenance']);
$routes->post('/verify', 'Auth::verify', ['filter' => 'portalMaintenance']);
$routes->get('/agreement', 'Auth::agreement', ['filter' => 'portalMaintenance']);
$routes->post('/agreement', 'Auth::agreement', ['filter' => 'portalMaintenance']);

// Partners Flow (New)
$routes->get('/partners/login', 'Partners::login', ['filter' => 'portalMaintenance']);
$routes->post('/partners/login', 'Partners::login', ['filter' => 'portalMaintenance']);
$routes->get('/partners/register', 'Partners::register', ['filter' => 'portalMaintenance']);
$routes->post('/partners/register', 'Partners::register', ['filter' => 'portalMaintenance']);
$routes->get('/partners/verification', 'Partners::verification', ['filter' => 'portalMaintenance']);
$routes->post('/partners/verification', 'Partners::verification', ['filter' => 'portalMaintenance']);
$routes->get('/partners/agreement', 'Partners::agreement', ['filter' => 'portalMaintenance']);
$routes->post('/partners/agreement', 'Partners::agreement', ['filter' => 'portalMaintenance']);

// Dashboard
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'portalMaintenance']);

// Profil Saya
$routes->get('/profile', 'Profile::index', ['filter' => 'roleFilter']);
$routes->post('/profile/update', 'Profile::update', ['filter' => 'roleFilter']);
$routes->post('/profile/upload-photo', 'Profile::uploadPhoto', ['filter' => 'roleFilter']);
$routes->get('/profile/pks/download', 'Profile::downloadPks', ['filter' => 'roleFilter']);

// Katalog
$routes->get('/catalog', 'Catalog::index');
$routes->get('/catalog/(:num)', 'Catalog::detail/$1');
$routes->get('/orders', function() { return redirect()->to('/app/orders'); });
$routes->get('/orders/(:num)', function($id) { return redirect()->to('/app/orders/' . $id); });

// Super Admin
$routes->group('superadmin', ['filter' => 'roleFilter:super_admin'], function($routes) {
    $routes->get('/', 'SuperAdmin\Dashboard::index');
    $routes->get('vouchers', 'SuperAdmin\Voucher::index');
    $routes->post('vouchers/generate', 'SuperAdmin\Voucher::generate');
    $routes->post('vouchers/disable/(:num)', 'SuperAdmin\Voucher::disable/$1');
    $routes->post('vouchers/delete/(:num)', 'SuperAdmin\Voucher::delete/$1');
    $routes->get('vouchers/delete/(:num)', 'SuperAdmin\Voucher::delete/$1');
    $routes->post('vouchers/bulk-delete', 'SuperAdmin\Voucher::bulkDelete');
    $routes->get('packages', 'SuperAdmin\Package::index');
    $routes->get('packages/create', 'SuperAdmin\Package::create');
    $routes->post('packages/store', 'SuperAdmin\Package::store');
    $routes->get('packages/(:num)/edit', 'SuperAdmin\Package::edit/$1');
    $routes->post('packages/(:num)/update', 'SuperAdmin\Package::update/$1');
    $routes->post('packages/(:num)/delete', 'SuperAdmin\Package::delete/$1');
    $routes->post('packages/bulk-delete', 'SuperAdmin\Package::bulkDelete');
    $routes->get('users', 'SuperAdmin\User::index');
    $routes->post('users/update/(:num)', 'SuperAdmin\User::update/$1');
    $routes->post('users/deactivate/(:num)', 'SuperAdmin\User::deactivate/$1');
    $routes->post('users/activate/(:num)', 'SuperAdmin\User::activate/$1');
    $routes->post('users/delete/(:num)', 'SuperAdmin\User::delete/$1');
    $routes->post('users/bulk-delete', 'SuperAdmin\User::bulkDelete');
    $routes->post('users/restore/(:num)', 'SuperAdmin\User::restore/$1');
    $routes->get('users/restore/(:num)', 'SuperAdmin\User::restore/$1');
    $routes->post('users/purge/(:num)', 'SuperAdmin\User::purge/$1');
    $routes->get('users/purge/(:num)', 'SuperAdmin\User::purge/$1');
    $routes->get('commissions', 'SuperAdmin\Commission::index');
    $routes->post('commissions/store', 'SuperAdmin\Commission::store');
    $routes->post('commissions/(:num)/update', 'SuperAdmin\Commission::update/$1');
    $routes->post('commissions/(:num)/delete', 'SuperAdmin\Commission::delete/$1');
    $routes->get('withdrawals', 'SuperAdmin\Commission::withdrawals');
    $routes->post('withdrawals/(:num)/process', 'SuperAdmin\Commission::processWithdrawal/$1');
    
    // Orders Management
    $routes->get('orders', 'SuperAdmin\Order::index');
    $routes->get('orders/(:num)', 'SuperAdmin\Order::detail/$1');
    $routes->post('orders/payments/(:num)/verify', 'SuperAdmin\Order::verifyPayment/$1');
    $routes->post('orders/(:num)/status', 'SuperAdmin\Order::updateStatus/$1');

    // Tasks
    $routes->get('tasks', 'SuperAdmin\Task::index');
    $routes->get('tasks/edit/(:num)', 'SuperAdmin\Task::edit/$1');
    $routes->get('tasks/(:num)/edit', 'SuperAdmin\Task::edit/$1');
    $routes->post('tasks/store', 'SuperAdmin\Task::store');
    $routes->post('tasks/(:num)/update', 'SuperAdmin\Task::update/$1');
    $routes->post('tasks/(:num)/delete', 'SuperAdmin\Task::delete/$1');
    $routes->post('tasks/bulk-delete', 'SuperAdmin\Task::bulkDelete');
    $routes->get('tasks/(:num)/download', 'SuperAdmin\Task::download/$1');
    $routes->get('verifications', 'SuperAdmin\Verification::index');
    $routes->get('verifications/(:num)', 'SuperAdmin\Verification::show/$1');
    $routes->get('verifications/document/(:num)/(:segment)', 'SuperAdmin\Verification::document/$1/$2');
    $routes->get('verifications/(:num)/pks', 'SuperAdmin\Verification::pks/$1');
    $routes->post('verifications/(:num)/approve', 'SuperAdmin\Verification::approve/$1');
    $routes->post('verifications/(:num)/reject', 'SuperAdmin\Verification::reject/$1');
    
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
$routes->group('admin', ['filter' => 'roleFilter:admin,pusat'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    
    // Packages
    $routes->get('packages', 'Admin\Package::index');
    $routes->get('packages/create', 'Admin\Package::create');
    $routes->post('packages/store', 'Admin\Package::store');
    $routes->get('packages/(:num)/edit', 'Admin\Package::edit/$1');
    $routes->post('packages/(:num)/update', 'Admin\Package::update/$1');
    $routes->post('packages/(:num)/delete', 'Admin\Package::delete/$1');
    
    // Commissions
    $routes->get('commissions', 'Admin\Commission::index');
    $routes->post('commissions/store', 'Admin\Commission::store');
    $routes->post('commissions/(:num)/update', 'Admin\Commission::update/$1');
    $routes->post('commissions/(:num)/delete', 'Admin\Commission::delete/$1');
    
    // Orders Management
    $routes->get('orders', 'Admin\Order::index');
    $routes->get('orders/(:num)', 'Admin\Order::detail/$1');
    $routes->post('orders/payments/(:num)/verify', 'Admin\Order::verifyPayment/$1');
    $routes->post('orders/(:num)/status', 'Admin\Order::updateStatus/$1');

    // Tasks
    $routes->get('tasks', 'Admin\Task::index');
    $routes->get('tasks/edit/(:num)', 'Admin\Task::edit/$1');
    $routes->get('tasks/(:num)/edit', 'Admin\Task::edit/$1');
    $routes->post('tasks/store', 'Admin\Task::store');
    $routes->post('tasks/(:num)/update', 'Admin\Task::update/$1');
    $routes->post('tasks/(:num)/delete', 'Admin\Task::delete/$1');
    $routes->get('tasks/(:num)/download', 'Admin\Task::download/$1');
});

// Member (Pusat, Cabang, Manager, Mitra)
$routes->group('app', ['filter' => ['portalMaintenance', 'roleFilter:pusat,cabang,manager_mitra,mitra']], function($routes) {
    $routes->get('dashboard', 'Member\Dashboard::index');
    $routes->get('packages', 'Member\Package::index');
    $routes->post('packages/order', 'Member\Package::order');
    $routes->get('binaan', 'Member\Binaan::index');
    $routes->get('orders', 'Member\Order::index');
    $routes->get('orders/(:num)', 'Member\Order::detail/$1');
    $routes->post('orders/(:num)/pay', 'Member\Order::pay/$1');
    $routes->get('commissions', 'Member\Commission::index');
    $routes->post('commissions/withdraw', 'Member\Commission::withdraw');
    $routes->get('tasks', 'Member\Task::index');
    $routes->get('tasks/(:num)/download', 'Member\Task::download/$1');
});

$routes->get('/partners/test', 'Partners::test');