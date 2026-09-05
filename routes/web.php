<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminGalleryController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminClientController;
use App\Http\Controllers\Admin\AdminWaController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\AdminLeadController;
use App\Http\Controllers\Admin\AdminHeroSlideController;
use App\Http\Controllers\Admin\AdminUspController;
use App\Http\Controllers\Admin\AdminCategoryItemController;
use App\Http\Controllers\Admin\AdminProductCategoryController;
use App\Http\Controllers\Admin\AdminCourierController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminApiKeyController;
use App\Http\Controllers\Admin\AdminPromoSectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| Public Routes — buyle.id (Bahasa Indonesia, tanpa prefix locale)
|--------------------------------------------------------------------------
*/

// Region API proxy (avoids CORS and external dependency issues)
Route::prefix('api/region')->group(function() {
    Route::get('/provinces', function() {
        $data = \Illuminate\Support\Facades\Http::timeout(10)->get('https://kanglerian.github.io/api-wilayah-indonesia/api/provinces.json');
        return response()->json($data->json(), 200, ['Access-Control-Allow-Origin' => '*']);
    });
    Route::get('/regencies/{id}', function($id) {
        $data = \Illuminate\Support\Facades\Http::timeout(10)->get("https://kanglerian.github.io/api-wilayah-indonesia/api/regencies/{$id}.json");
        return response()->json($data->json(), 200, ['Access-Control-Allow-Origin' => '*']);
    });
    Route::get('/districts/{id}', function($id) {
        $data = \Illuminate\Support\Facades\Http::timeout(10)->get("https://kanglerian.github.io/api-wilayah-indonesia/api/districts/{$id}.json");
        return response()->json($data->json(), 200, ['Access-Control-Allow-Origin' => '*']);
    });
});

Route::middleware(['track.pageview'])->group(function () {
    // Legacy redirects for cached locale routes
    Route::get('/en', function () { return redirect('/', 301); });

    Route::get('/',               [HomeController::class,    'index'])->name('home');
    Route::get('/c/{slug}/{groupSlug?}', [\App\Http\Controllers\CreatorStoreController::class, 'show'])->name('store.show');
    Route::get('/toko/{slug}', fn($slug) => redirect()->route('store.show', $slug, 301))->name('store.show.legacy');
    Route::get('/tentang-kami',   [AboutController::class,  'index'])->name('about');
    Route::get('/kontak',         [ContactController::class,'index'])->name('contact');
    Route::post('/kontak',        [ContactController::class,'send'])->name('contact.send');
    Route::post('/chat-cs',       [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
    Route::post('/report-abuse',  [\App\Http\Controllers\ReportAbuseController::class, 'store'])->name('report.store');
    Route::get('/galeri',         [GalleryController::class,'index'])->name('gallery');
    Route::get('/galeri/{slug}',  [GalleryController::class,'show'])->name('gallery.show');
    
    // SEO Friendly Category Route
    Route::get('/kategori/{categorySlug}/{subcategorySlug?}', [CategoryController::class, 'show'])->name('category.show');
    
    Route::get('/produk',         [ServiceController::class,'index'])->name('products');
    Route::get('/produk/{slug}',  [ServiceController::class,'show'])->name('products.show');
    Route::get('/artikel',        [ArticleController::class,'index'])->name('articles');
    Route::get('/artikel/{slug}', [ArticleController::class,'show'])->name('articles.show');
    Route::get('/faq',            [FaqController::class,'index'])->name('faqs');
    Route::get('/faq/{slug}',     [FaqController::class,'show'])->name('faqs.show');
    Route::get('/penulis/{slug}', [AuthorController::class, 'show'])->name('author.show');

    // E-commerce: Cart (boleh tanpa login)
    Route::get('/keranjang',           [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah',   [CartController::class, 'add'])->name('cart.add');
    Route::post('/keranjang/update',   [CartController::class, 'update'])->name('cart.update');
    Route::post('/keranjang/hapus',    [CartController::class, 'remove'])->name('cart.remove');

    // E-commerce: Checkout (bisa guest, auto register)
    Route::get('/checkout/login', function () {
        session()->put('url.intended', route('checkout.index'));
        return redirect()->route('login');
    })->name('checkout.login');
    Route::get('/checkout',                [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout',               [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/selesai/{order}',[CheckoutController::class, 'finish'])->name('checkout.finish');

    // API RajaOngkir untuk Checkout
    Route::get('/api/rajaongkir/provinces', [\App\Http\Controllers\CheckoutApiController::class, 'provinces'])->name('api.rajaongkir.provinces');
    Route::get('/api/rajaongkir/cities/{province}', [\App\Http\Controllers\CheckoutApiController::class, 'cities'])->name('api.rajaongkir.cities');
    Route::post('/api/rajaongkir/cost', [\App\Http\Controllers\CheckoutApiController::class, 'cost'])->name('api.rajaongkir.cost');

    // Coupon / Voucher API
    Route::get('/api/coupons', [\App\Http\Controllers\CouponApiController::class, 'index'])->name('api.coupons.index');
    Route::post('/api/coupons/validate', [\App\Http\Controllers\CouponApiController::class, 'validate'])->name('api.coupons.validate');

    // Cek Resi
    Route::get('/cek-resi', [\App\Http\Controllers\ResiController::class, 'index'])->name('cek-resi');
    Route::post('/cek-ongkir', [App\Http\Controllers\ResiController::class, 'cekOngkir'])->name('cek.ongkir');
    Route::post('/cek-resi', [\App\Http\Controllers\ResiController::class, 'track'])->name('cek-resi.track');

    Route::middleware(['auth'])->group(function () {
    });
});

/*
|--------------------------------------------------------------------------
| Non-page Routes
|--------------------------------------------------------------------------
*/

// Lead / Request Order
Route::post('/request-order', [LeadController::class, 'store'])->name('lead.store');

/*
|--------------------------------------------------------------------------
| Buyer Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.submit');

    // Password Reset Routes
    Route::get('/lupa-password',          [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/lupa-password',         [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password',        [AuthController::class, 'resetPassword'])->name('password.update');

    // Google OAuth
    Route::get('/auth/google',          [AuthController::class, 'googleRedirect'])->name('auth.google');
    Route::get('/auth/callback/google', [AuthController::class, 'googleCallback'])->name('auth.google.callback');
});

Route::post('/keluar', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Buyer Account Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('akun')->name('account.')->group(function () {
    Route::get('/',          [AccountController::class, 'overview'])->name('overview');
    Route::get('/wishlist',  [AccountController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/toggle', [AccountController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/pesanan',   [AccountController::class, 'orders'])->name('orders');
    Route::get('/pesanan/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
    Route::post('/pesanan/{order}/selesai', [AccountController::class, 'completeOrder'])->name('orders.complete');
    Route::get('/alamat',    [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/alamat',   [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/alamat/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/alamat/{address}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
    Route::post('/alamat/{address}/utama', [AccountController::class, 'setDefaultAddress'])->name('addresses.default');
    Route::get('/profil',    [AccountController::class, 'profile'])->name('profile');
    Route::post('/profil',   [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/cart',      [AccountController::class, 'cart'])->name('cart');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/creator/onboarding', [\App\Http\Controllers\CreatorOnboardingController::class, 'index'])->name('creator.onboarding');
    Route::post('/creator/onboarding', [\App\Http\Controllers\CreatorOnboardingController::class, 'store'])->name('creator.onboarding.store');
});


// Tracking endpoint
Route::post('/track/{type}', [TrackingController::class, 'track'])->name('track');

// Webhook Midtrans — DEDICATED controller, reply < 1 detik, proses di queue
// (Route ini dikecualikan dari CSRF di bootstrap/app.php)
Route::post('/payment/callback', [\App\Http\Controllers\PaymentWebhookController::class, 'midtrans'])->name('payment.callback');

// Magic Login Link — buyer bisa masuk 1-klik dari email
Route::get('/magic-login', [\App\Http\Controllers\AuthController::class, 'magicLogin'])->name('buyer.magic.login');

// Sitemap & robots
Route::get('/sitemap.xml',  [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $sitemap = url('/sitemap.xml');
    $lines = [
        'User-agent: *',
        'Allow: /',
        '',
        '# Halaman transaksional — tidak perlu diindex Google',
        'Disallow: /admin',
        'Disallow: /admin/',
        'Disallow: /akun/',
        '',
        '# Parameter filter/sort menciptakan URL duplikat — biarkan canonical yang handle',
        'Disallow: /*?q=',
        'Disallow: /*?sort=',
        'Disallow: /*?page=',
        '',
        '# Disallow common bot traps',
        'Disallow: /*.php$',
        '',
        "Sitemap: {$sitemap}",
    ];
    return response(implode("\n", $lines), 200)->withHeaders([
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Content-Disposition' => 'inline; filename="robots.txt"'
    ]);
});

// Deployment Helper
Route::get('/deploy-hostinger', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return 'SUKSES! Symlink storage berhasil dibuat dan Cache berhasil dibersihkan.';
    } catch (\Exception $e) {
        return 'ERROR: ' . $e->getMessage();
    }
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout',[AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/analytics',              [AdminAnalyticsController::class, 'index'])->name('admin.analytics');
        Route::get('/analytics/data',         [AdminAnalyticsController::class, 'data'])->name('admin.analytics.data');
        Route::get('/analytics/realtime',     [AdminAnalyticsController::class, 'realtime'])->name('admin.analytics.realtime');
        Route::get('/analytics/export/xls',   [AdminAnalyticsController::class, 'exportXls'])->name('admin.analytics.export_xls');
        Route::get('/analytics/export/pdf',   [AdminAnalyticsController::class, 'exportPdf'])->name('admin.analytics.export_pdf');

        Route::get('/settings',  [AdminSettingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
        Route::post('/settings/test-email', [AdminSettingsController::class, 'testEmail'])->name('admin.settings.test-email');
        Route::post('upload-image', [\App\Http\Controllers\Admin\AdminUploadController::class, 'uploadImage'])->name('admin.upload.image');

        // ⚡ Secret license management page (tidak ada di sidebar)
        Route::get('/lisensi',  [AdminSettingsController::class, 'license'])->name('admin.license');
        Route::post('/lisensi', [AdminSettingsController::class, 'updateLicense'])->name('admin.license.update');


        Route::get('/api-keys',  [AdminApiKeyController::class, 'index'])->name('admin.apikeys.index');
        Route::post('/api-keys', [AdminApiKeyController::class, 'update'])->name('admin.apikeys.update');

        Route::get('/leads/export',          [AdminLeadController::class, 'export'])->name('admin.leads.export');
        Route::get('/leads/export-pdf',      [AdminLeadController::class, 'exportPdf'])->name('admin.leads.export_pdf');
        Route::post('/leads/mark-read',      [AdminLeadController::class, 'markAllRead'])->name('admin.leads.mark_read');
        Route::get('/leads',                 [AdminLeadController::class, 'index'])->name('admin.leads.index');
        Route::get('/leads/{lead}',          [AdminLeadController::class, 'show'])->name('admin.leads.show');
        Route::post('/leads/{lead}/status',  [AdminLeadController::class, 'updateStatus'])->name('admin.leads.status');
        Route::post('/leads/{lead}/notes',   [AdminLeadController::class, 'updateNote'])->name('admin.leads.notes');
        Route::delete('/leads/{lead}',       [AdminLeadController::class, 'destroy'])->name('admin.leads.destroy');

        // Laporan Penyalahgunaan / Public Reports
        Route::get('/reports', [\App\Http\Controllers\Admin\AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::post('/reports/{report}/status', [\App\Http\Controllers\Admin\AdminReportController::class, 'updateStatus'])->name('admin.reports.update_status');
        Route::delete('/reports/{report}', [\App\Http\Controllers\Admin\AdminReportController::class, 'destroy'])->name('admin.reports.destroy');

        Route::patch('services/{service}/order', [AdminServiceController::class, 'updateOrder'])->name('admin.services.order');
        Route::patch('services/{service}/stock', [AdminServiceController::class, 'updateStock'])->name('admin.services.stock');
        Route::delete('services/{service}/gallery-image', [AdminServiceController::class, 'deleteGalleryImage'])->name('admin.services.gallery.delete');
        Route::resource('services', AdminServiceController::class)->names([
            'index'   => 'admin.services.index',   'create'  => 'admin.services.create',
            'store'   => 'admin.services.store',    'show'    => 'admin.services.show',
            'edit'    => 'admin.services.edit',     'update'  => 'admin.services.update',
            'destroy' => 'admin.services.destroy',
        ]);

        // Kategori Marketplace
        Route::post('product-categories/update-order', [AdminProductCategoryController::class, 'updateOrder'])->name('admin.product-categories.updateOrder');
        Route::post('product-categories/{product_category}/sub', [AdminProductCategoryController::class, 'storeSub'])->name('admin.product-categories.sub.store');
        Route::post('product-categories/{product_category}', [AdminProductCategoryController::class, 'update']);
        Route::put('product-categories/sub/{sub}', [AdminProductCategoryController::class, 'updateSub'])->name('admin.product-categories.sub.update');
        Route::delete('product-categories/sub/{sub}', [AdminProductCategoryController::class, 'destroySub'])->name('admin.product-categories.sub.destroy');
        Route::resource('product-categories', AdminProductCategoryController::class)->names([
            'index'   => 'admin.product-categories.index',
            'create'  => 'admin.product-categories.create',
            'store'   => 'admin.product-categories.store',
            'edit'    => 'admin.product-categories.edit',
            'update'  => 'admin.product-categories.update',
            'destroy' => 'admin.product-categories.destroy',
        ]);

        Route::resource('coupons', \App\Http\Controllers\Admin\AdminCouponController::class)->names([
            'index'   => 'admin.coupons.index',   'create'  => 'admin.coupons.create',
            'store'   => 'admin.coupons.store',   'show'    => 'admin.coupons.show',
            'edit'    => 'admin.coupons.edit',    'update'  => 'admin.coupons.update',
            'destroy' => 'admin.coupons.destroy',
        ]);

        Route::resource('gallery', AdminGalleryController::class)->names([
            'index'   => 'admin.gallery.index',   'create'  => 'admin.gallery.create',
            'store'   => 'admin.gallery.store',   'show'    => 'admin.gallery.show',
            'edit'    => 'admin.gallery.edit',    'update'  => 'admin.gallery.update',
            'destroy' => 'admin.gallery.destroy',
        ]);

        Route::resource('articles', AdminArticleController::class)->names([
            'index'   => 'admin.articles.index',   'create'  => 'admin.articles.create',
            'store'   => 'admin.articles.store',   'show'    => 'admin.articles.show',
            'edit'    => 'admin.articles.edit',    'update'  => 'admin.articles.update',
            'destroy' => 'admin.articles.destroy',
        ]);

        Route::resource('faqs', AdminFaqController::class)->names([
            'index'   => 'admin.faqs.index',   'create'  => 'admin.faqs.create',
            'store'   => 'admin.faqs.store',   'show'    => 'admin.faqs.show',
            'edit'    => 'admin.faqs.edit',    'update'  => 'admin.faqs.update',
            'destroy' => 'admin.faqs.destroy',
        ]);

        Route::resource('authors', \App\Http\Controllers\Admin\AdminAuthorController::class)->names([
            'index'   => 'admin.authors.index',   'create'  => 'admin.authors.create',
            'store'   => 'admin.authors.store',   'show'    => 'admin.authors.show',
            'edit'    => 'admin.authors.edit',    'update'  => 'admin.authors.update',
            'destroy' => 'admin.authors.destroy',
        ]);

        Route::resource('clients', AdminClientController::class)->names([
            'index'   => 'admin.clients.index',   'create'  => 'admin.clients.create',
            'store'   => 'admin.clients.store',   'show'    => 'admin.clients.show',
            'edit'    => 'admin.clients.edit',    'update'  => 'admin.clients.update',
            'destroy' => 'admin.clients.destroy',
        ]);

        Route::resource('testimonials', AdminTestimonialController::class)->names([
            'index'   => 'admin.testimonials.index',   'create'  => 'admin.testimonials.create',
            'store'   => 'admin.testimonials.store',   'show'    => 'admin.testimonials.show',
            'edit'    => 'admin.testimonials.edit',    'update'  => 'admin.testimonials.update',
            'destroy' => 'admin.testimonials.destroy',
        ]);

        Route::get('/wa-settings',         [AdminWaController::class, 'index'])->name('admin.wa.index');
        Route::post('/wa-settings',        [AdminWaController::class, 'update'])->name('admin.wa.update');
        Route::post('/wa-settings/add',    [AdminWaController::class, 'store'])->name('admin.wa.store');
        Route::delete('/wa-settings/{id}', [AdminWaController::class, 'destroy'])->name('admin.wa.destroy');

        Route::resource('hero-slides', AdminHeroSlideController::class)->names([
            'index'   => 'admin.hero_slides.index',   'create'  => 'admin.hero_slides.create',
            'store'   => 'admin.hero_slides.store',   'show'    => 'admin.hero_slides.show',
            'edit'    => 'admin.hero_slides.edit',    'update'  => 'admin.hero_slides.update',
            'destroy' => 'admin.hero_slides.destroy',
        ]);

        Route::resource('usp', AdminUspController::class)->names([
            'index'   => 'admin.usp.index',   'create' => 'admin.usp.create',
            'store'   => 'admin.usp.store',   'edit'   => 'admin.usp.edit',
            'update'  => 'admin.usp.update',  'destroy'=> 'admin.usp.destroy',
        ]);

        Route::resource('category-items', AdminCategoryItemController::class)->names([
            'index'   => 'admin.category-items.index',   'create' => 'admin.category-items.create',
            'store'   => 'admin.category-items.store',   'edit'   => 'admin.category-items.edit',
            'update'  => 'admin.category-items.update',  'destroy'=> 'admin.category-items.destroy',
        ]);
        Route::post('category-items/update-order', [AdminCategoryItemController::class, 'updateOrder'])->name('admin.category-items.updateOrder');

        Route::resource('promo-sections', AdminPromoSectionController::class)->names([
            'index'   => 'admin.promo-sections.index',   'create' => 'admin.promo-sections.create',
            'store'   => 'admin.promo-sections.store',   'show'   => 'admin.promo-sections.show',
            'edit'    => 'admin.promo-sections.edit',    'update' => 'admin.promo-sections.update',
            'destroy' => 'admin.promo-sections.destroy',
        ]);

        // Courier Management
        Route::get('/couriers',                      [AdminCourierController::class, 'index'])->name('admin.couriers.index');
        Route::post('/couriers',                     [AdminCourierController::class, 'store'])->name('admin.couriers.store');
        Route::put('/couriers/{courier}',            [AdminCourierController::class, 'update'])->name('admin.couriers.update');
        Route::post('/couriers/{courier}/toggle',    [AdminCourierController::class, 'toggleActive'])->name('admin.couriers.toggle');
        Route::delete('/couriers/{courier}',         [AdminCourierController::class, 'destroy'])->name('admin.couriers.destroy');

        // User Management (Buyers)
        Route::get('/users',                                    [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}',                             [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::post('/users/{user}/password',                   [AdminUserController::class, 'updatePassword'])->name('admin.users.password');
        Route::post('/users/{user}/toggle',                     [AdminUserController::class, 'toggleActive'])->name('admin.users.toggle');
        Route::delete('/users/{user}/addresses/{address}',      [AdminUserController::class, 'destroyAddress'])->name('admin.users.addresses.destroy');

        // Order Management
        Route::get('/orders/export',                            [\App\Http\Controllers\Admin\AdminOrderController::class, 'export'])->name('admin.orders.export');
        Route::get('/orders',                                   [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/orders/{order}',                           [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/orders/{order}/status',                   [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::post('/orders/{order}/tracking',                 [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateTracking'])->name('admin.orders.tracking');
        Route::post('/orders/{order}/shipping-cost',            [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateShippingCost'])->name('admin.orders.shipping_cost');

        // White Label Approval Management
        Route::get('/whitelabel-approval',                      [\App\Http\Controllers\Admin\AdminWhiteLabelController::class, 'index'])->name('admin.whitelabel.index');
        Route::post('/whitelabel-approval/{product}/approve',    [\App\Http\Controllers\Admin\AdminWhiteLabelController::class, 'approve'])->name('admin.whitelabel.approve');
        Route::post('/whitelabel-approval/{product}/reject',     [\App\Http\Controllers\Admin\AdminWhiteLabelController::class, 'reject'])->name('admin.whitelabel.reject');
    });
});

/*
|--------------------------------------------------------------------------
| Multi-Creator Marketplace Routes (Tahap 1)
|--------------------------------------------------------------------------
*/

// 1. Flash Admin (HVM Internal) - Note: existing admin uses admin.auth, we can add this for new role:super_admin
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Verifikasi Seller & Modul Payout
    Route::resource('payouts', \App\Http\Controllers\Admin\AdminPayoutController::class);
    Route::resource('sellers', \App\Http\Controllers\Admin\AdminSellerController::class);
});

// 2. Seller Dashboard (Creator)
Route::middleware(['auth', 'role:seller'])->prefix('creator')->name('creator.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Creator\SellerController::class, 'dashboard'])->name('dashboard');
    Route::post('/upload-image', [\App\Http\Controllers\Admin\AdminUploadController::class, 'uploadImage'])->name('upload.image');

    // CRUD Produk
    Route::patch('/products/{product}/stock', [\App\Http\Controllers\Creator\SellerProductController::class, 'updateStock'])->name('products.stock');
    Route::patch('/products/{product}/order', [\App\Http\Controllers\Creator\SellerProductController::class, 'updateOrder'])->name('products.order');
    Route::resource('products', \App\Http\Controllers\Creator\SellerProductController::class);

    // AJAX: Validasi URL produk
    Route::post('/validate-link', [\App\Http\Controllers\Creator\SellerProductController::class, 'validateLink'])->name('products.validate-link');

    // CRUD Kelompok Produk
    Route::resource('groups', \App\Http\Controllers\Creator\CreatorGroupController::class);

    // Pengaturan 
    Route::get('/profile', [\App\Http\Controllers\Creator\CreatorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Creator\CreatorProfileController::class, 'update'])->name('profile.update');

    // Modul Pengaturan Bank Account & Penarikan
    Route::get('/payout-settings', [\App\Http\Controllers\Creator\SellerPayoutController::class, 'settings'])->name('payout.settings');
    Route::post('/payout-settings/bank', [\App\Http\Controllers\Creator\SellerPayoutController::class, 'updateBank'])->name('payout.bank.update');
    Route::post('/payout-request', [\App\Http\Controllers\Creator\SellerPayoutController::class, 'requestPayout'])->name('payout.request');

    // Laporan Penjualan
    Route::get('/sales-report', [\App\Http\Controllers\Creator\SellerReportController::class, 'index'])->name('sales.report');
    Route::get('/sales-report/export', [\App\Http\Controllers\Creator\SellerReportController::class, 'export'])->name('sales.report.export');

    // Realtime Stats API (AJAX polling for dashboard)
    Route::get('/stats/realtime', [\App\Http\Controllers\Creator\SellerController::class, 'realtimeStats'])->name('stats.realtime');

    // Membership Plans
    Route::get('/membership', [\App\Http\Controllers\Creator\SellerController::class, 'membership'])->name('membership');

    // Ticket Gate Scanner & Attendance Data
    Route::get('/event-attendance', [\App\Http\Controllers\TicketScannerController::class, 'index'])->name('ticket.scanner');
    Route::get('/scanner', fn() => redirect()->route('creator.ticket.scanner'));
    Route::post('/scanner/verify', [\App\Http\Controllers\TicketScannerController::class, 'verify'])->name('ticket.scanner.verify');
    Route::post('/scanner/toggle-checkin/{ticket}', [\App\Http\Controllers\TicketScannerController::class, 'toggleCheckin'])->name('ticket.scanner.toggle-checkin');
    Route::get('/scanner/export', [\App\Http\Controllers\TicketScannerController::class, 'exportCsv'])->name('ticket.scanner.export');

    // ── Link in Bio ──────────────────────────────────────────────────────
    Route::get('/bio',          [\App\Http\Controllers\Creator\CreatorBioController::class, 'index'])->name('bio.index');
    Route::post('/bio/role',    [\App\Http\Controllers\Creator\CreatorBioController::class, 'setRole'])->name('bio.set-role');
    Route::post('/bio/theme',   [\App\Http\Controllers\Creator\CreatorBioController::class, 'saveTheme'])->name('bio.save-theme');
    Route::post('/bio/profile', [\App\Http\Controllers\Creator\CreatorBioController::class, 'saveProfile'])->name('bio.save-profile');
    Route::post('/bio/blocks',  [\App\Http\Controllers\Creator\CreatorBioController::class, 'storeBlock'])->name('bio.blocks.store');
    Route::put('/bio/blocks/{block}', [\App\Http\Controllers\Creator\CreatorBioController::class, 'updateBlock'])->name('bio.blocks.update');
    Route::delete('/bio/blocks/{block}', [\App\Http\Controllers\Creator\CreatorBioController::class, 'destroyBlock'])->name('bio.blocks.destroy');
    Route::patch('/bio/blocks/{block}/toggle', [\App\Http\Controllers\Creator\CreatorBioController::class, 'toggleBlock'])->name('bio.blocks.toggle');
    Route::post('/bio/blocks/reorder', [\App\Http\Controllers\Creator\CreatorBioController::class, 'reorderBlocks'])->name('bio.blocks.reorder');
    Route::post('/bio/scrape-url', [\App\Http\Controllers\Creator\CreatorBioController::class, 'scrapeUrl'])->name('bio.scrape-url');
});

// 3. Buyer Dashboard
Route::middleware(['auth', 'role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Buyer\BuyerController::class, 'dashboard'])->name('dashboard');

    // Riwayat Pembelian & Akses File/Link (hanya order milik buyer)
    Route::get('/orders', [\App\Http\Controllers\Buyer\BuyerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Buyer\BuyerOrderController::class, 'show'])->name('orders.show');

    // Akses produk digital (verifikasi kepemilikan order, redirect ke link)
    Route::get('/access-product/{product}', [\App\Http\Controllers\Buyer\BuyerOrderController::class, 'accessProduct'])->name('access.product');
});

// ── Public Bio Link Page — MUST be last route (/username)
// Reserved slugs are blocked in BioPageController
Route::get('/{username}/p/{identifier}', [\App\Http\Controllers\BioProductController::class, 'show'])
    ->name('bio.product.show')
    ->where('username', '[a-zA-Z0-9_\-]+');

Route::get('/{username}', [\App\Http\Controllers\BioPageController::class, 'show'])
    ->name('bio.public')
    ->where('username', '[a-zA-Z0-9_\-]+');

