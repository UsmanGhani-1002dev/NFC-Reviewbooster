<?php

use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ManageBusinessController;
use App\Http\Controllers\ManageSubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubscriptionPlanController;

use App\Http\Controllers\TrackingController;
use App\Mail\UserRegisteredAndSubscribed;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::post('/validate-step1', [RegisteredUserController::class, 'validateStep1'])->name('validate.step1');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'aboutUs'])->name('about');
Route::get('/how-its-work', [HomeController::class, 'howitswork'])->name('howitswork');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/shipping-returns', [HomeController::class, 'shippingReturns'])->name('shipping-returns');
Route::get('/terms-of-service', [HomeController::class, 'termsOfService'])->name('terms-of-service');
Route::get('/privacy-policy', [HomeController::class, 'privacypolicy'])->name('privacypolicy');

// Dynamic Sitemap
Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home'), 'lastmod' => now()->toAtomString(), 'priority' => '1.0'],
        ['loc' => route('about'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
        ['loc' => route('howitswork'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
        ['loc' => route('shop.index'), 'lastmod' => now()->toAtomString(), 'priority' => '0.9'],
        ['loc' => route('contact'), 'lastmod' => now()->toAtomString(), 'priority' => '0.7'],
        ['loc' => route('shipping-returns'), 'lastmod' => now()->toAtomString(), 'priority' => '0.5'],
        ['loc' => route('terms-of-service'), 'lastmod' => now()->toAtomString(), 'priority' => '0.5'],
        ['loc' => route('privacypolicy'), 'lastmod' => now()->toAtomString(), 'priority' => '0.5'],
        ['loc' => route('blog.index'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
        ['loc' => route('landing.cards'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
        ['loc' => route('landing.stand'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
        ['loc' => route('landing.keychain'), 'lastmod' => now()->toAtomString(), 'priority' => '0.8'],
    ];

    // Add Shop Products to Sitemap
    $products = \App\Models\Product::all();
    foreach ($products as $product) {
        $urls[] = [
            'loc' => route('shop.show', $product->slug),
            'lastmod' => $product->updated_at->toAtomString(),
            'priority' => '0.8'
        ];
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $url) {
        $xml .= '<url>';
        $xml .= '<loc>' . $url['loc'] . '</loc>';
        $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
        $xml .= '<priority>' . $url['priority'] . '</priority>';
        $xml .= '</url>';
    }
    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'text/xml');
});

// Shop & Order Tracking Routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/checkout', [ShopController::class, 'checkout'])->name('shop.checkout');
Route::post('/shop/checkout', [ShopController::class, 'processCheckout'])->name('shop.process-checkout');
Route::post('/shop/payment-intent', [ShopController::class, 'createPaymentIntent'])->name('shop.payment-intent');
Route::post('/shop/upload-logo', [ShopController::class, 'uploadCustomLogo'])->name('shop.upload-logo')->middleware('throttle:30,1');

// Public analytics beacon (add-to-cart tracking)
Route::post('/track/event', [TrackingController::class, 'event'])->name('track.event')->middleware('throttle:120,1');

// Public AI support chatbot (Google Gemini). Key stays server-side; throttled.
Route::post('/chat', [\App\Http\Controllers\ChatbotController::class, 'send'])->name('chatbot.send')->middleware('throttle:20,1');
Route::get('/shop/order/success', [ShopController::class, 'success'])->name('shop.success');
Route::get('/track-order', [ShopController::class, 'trackOrder'])->name('orders.track');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Search Intent Landing Pages
Route::get('/google-review-cards', [LandingPageController::class, 'cards'])->name('landing.cards');
Route::get('/google-review-stand', [LandingPageController::class, 'stand'])->name('landing.stand');
Route::get('/google-review-keychain', [LandingPageController::class, 'keychain'])->name('landing.keychain');

// Public NFC tap route (Logic handled in ReviewController to check subscription)
Route::get('/r/{token}', [ReviewController::class, 'show'])->name('reviews.gate');

// Reviews Routes
Route::get('/gate', [ReviewController::class, 'GateReview'])->name('gatereviews');
Route::get('/login', function () {
    return redirect()->route('login');
});

// This is the correct route for card-specific feedback
Route::get('/f/{token}', [ReviewController::class, 'showFeedbackForm'])->name('reviews.feedback.general');

Route::get('/feedback', [ReviewController::class, 'badreview'])->name('reviews.feedback');

Route::get('/t/{token}', [ReviewController::class, 'trackGoogleReview'])->name('reviews.track');

Route::get('/review', [ReviewController::class, 'showForm'])->name('reviews.form');
Route::post('/feedback', [ReviewController::class, 'feedbackstore'])->name('reviews.feedbackstore');
// Route::get('/reviews', [ReviewController::class, 'showPositiveReviews'])->name('reviews.positive');
Route::get('/reviews/success', [ReviewController::class, 'success'])->name('reviews.success');

// Auth routes are handled in auth.php at the end of this file 
// Auth::routes(); 

// Protected Routes (Authenticated users only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/dismiss-warning', [DashboardController::class, 'dismissWarning'])->name('dashboard.dismiss-warning');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Business Routes
    Route::get('/business/reviews', [BusinessController::class, 'businessReviews'])->name('business.reviews');
    Route::get('/business/reviews/feedback', [BusinessController::class, 'businessAllReviews'])->name('business.reviews.feedback');

    // Admin route (role-based protection)
    Route::get('/admin/reviews', [ReviewController::class, 'adminReviews'])->name('admin.reviews');
    Route::get('/admin/reviews/rating', [ReviewController::class, 'rating'])->name('admin.reviews.rating');
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    Route::patch('/reviews/{review}/status', [BusinessController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::post('/ai/generate-response', [\App\Http\Controllers\AIController::class, 'generateResponse'])->name('ai.generate-response');
});

Route::middleware(['auth','role:bussiness_owner'])->group(function () {
    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    Route::get('/cards/{card}/edit', [CardController::class, 'edit'])->name('cards.edit');
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('cards.update');
    Route::get('/user/subscription', [SubscriptionPlanController::class, 'showSubscriptionPage'])->name('user.subscription.index');
    
    Route::post('/user/subscription/intent', [SubscriptionPlanController::class, 'createStripeIntent'])->name('user.subscription.create-intent');
    Route::post('/user/subscription', [SubscriptionPlanController::class, 'userUpdateSubscription'])->name('user.subscription.update');
    
    Route::patch('/reviews/{id}/status/{status}', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    
    // Show list of businesses
    Route::get('/businesses', [ManageBusinessController::class, 'index'])->name('businesses.index');
    Route::get('/businesses/create', [ManageBusinessController::class, 'create'])->name('businesses.create');
    Route::post('/businesses', [ManageBusinessController::class, 'store'])->name('businesses.store');
    Route::get('/businesses/{id}/edit', [ManageBusinessController::class, 'edit'])->name('businesses.edit');
    Route::put('/businesses/{id}', [ManageBusinessController::class, 'update'])->name('businesses.update');
    Route::delete('/businesses/{id}', [ManageBusinessController::class, 'destroy'])->name('businesses.destroy');
    Route::post('/businesses/switch', [ManageBusinessController::class, 'switch'])->name('businesses.switch');
    Route::get('/dashboard/report', [DashboardController::class, 'downloadReport'])->name('dashboard.report');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/command-search', [\App\Http\Controllers\Admin\CommandPaletteController::class, 'search'])->name('command-search')->middleware('throttle:60,1');

    Route::get('/users', [RegisteredUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [RegisteredUserController::class, 'adminCreate'])->name('users.create');
    Route::post('/users', [RegisteredUserController::class, 'storeAdminUser'])->name('users.store');
    Route::post('/users/toggle-status/{user}', [RegisteredUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/partner-status', [RegisteredUserController::class, 'updatePartnerStatus'])->name('users.partner-status');
    Route::post('/users/bulk-delete', [RegisteredUserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::delete('/admin/users/{user}', [RegisteredUserController::class, 'destroy'])->name('users.destroy');
    Route::get('subscription-plans', [SubscriptionPlanController::class, 'index'])->name('subscription-plans.index');
    Route::get('subscription-plans/create', [SubscriptionPlanController::class, 'create'])->name('subscription-plans.create');
    Route::post('subscription-plans', [SubscriptionPlanController::class, 'store'])->name('subscription-plans.store');
    Route::get('subscription-plans/{subscriptionPlan}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscription-plans.edit');
    Route::put('subscription-plans/{subscriptionPlan}', [SubscriptionPlanController::class, 'update'])->name('subscription-plans.update');
    Route::delete('subscription-plans/{subscriptionPlan}', [SubscriptionPlanController::class, 'destroy'])->name('subscription-plans.destroy');
    Route::get('/users/{user}/edit', [RegisteredUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [RegisteredUserController::class, 'update'])->name('users.update');
    
    Route::get('/manage-subscription', [ManageSubscriptionController::class, 'index'])->name('manage-subscription.index');
    Route::get('/subscriptions/{id}/edit', [ManageSubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('/subscriptions/{id}', [ManageSubscriptionController::class, 'update'])->name('subscriptions.update');

    Route::get('/manage_business',[ManageBusinessController::class, 'admin_index'])->name('manage_business.index');
    Route::get('/manage_business/create',[ManageBusinessController::class, 'admin_create'])->name('manage_business.create');
    Route::post('/manage_business/store',[ManageBusinessController::class, 'admin_store'])->name('manage_business.store');
    Route::get('/manage_business/{id}',[ManageBusinessController::class, 'admin_view_business'])->name('manage_business.view');
    Route::delete('/manage-business/{id}/delete', [ManageBusinessController::class, 'admin_delete'])->name('manage_business.delete');
    Route::put('/manage-business/{id}/update-status', [ManageBusinessController::class, 'admin_update_status'])->name('manage_business.update_status');

    Route::get('/manage-business/{business_id}/cards/create', [CardController::class, 'admin_create'])->name('manage_business.cards.create');
    Route::post('/manage-business/{business_id}/cards/store', [CardController::class, 'admin_store'])->name('manage_business.cards.store');
    Route::get('/manage-business/{business_id}/cards/{card}/edit', [CardController::class, 'admin_edit'])->name('manage_business.cards.edit');
    Route::put('/manage-business/{business_id}/cards/{card}', [CardController::class, 'admin_update'])->name('manage_business.cards.update');
    Route::delete('/manage-business/{business_id}/cards/{card}', [CardController::class, 'admin_destroy'])->name('manage_business.cards.destroy');


    Route::get('/contact-submissions', [ContactController::class, 'submissions'])->name('contact-submissions.index');
    Route::get('/admin/contact-submissions/suggestions', [ContactController::class, 'suggestions'])->name('contact-submissions.suggestions');
    Route::get('/view-submissions/{id}/view', [ContactController::class, 'view_contact'])->name('contact-submissions.view');
    Route::put('/view-submissions/{id}', [ContactController::class, 'update_sub_Status'])->name('contact-submissions.update');
    Route::post('/view-submissions/{id}/reply', [ContactController::class, 'reply'])->name('contact-submissions.reply');
    Route::delete('/contact-submissions/{submission}', [ContactController::class, 'destroy'])->name('contact-submissions.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Product Management
    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

    // Order Management
    Route::get('/orders', [\App\Http\Controllers\Admin\ProductController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}/view', [\App\Http\Controllers\Admin\ProductController::class, 'editOrder'])->name('orders.edit');
    Route::get('/orders/{order}/print', [\App\Http\Controllers\Admin\ProductController::class, 'printOrder'])->name('orders.print');
    Route::get('/orders/{order}/label', [\App\Http\Controllers\Admin\ProductController::class, 'printLabel'])->name('orders.label');
    Route::get('/orders/{order}/design', [\App\Http\Controllers\Admin\ProductController::class, 'printDesign'])->name('orders.design');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\ProductController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::put('/orders/{order}/location', [\App\Http\Controllers\Admin\ProductController::class, 'updateOrderLocation'])->name('orders.update-location');
    Route::put('/orders/{order}/items/{item}/location', [\App\Http\Controllers\Admin\ProductController::class, 'updateOrderItemLocation'])->name('orders.item-location');
    Route::post('/orders/{order}/email', [\App\Http\Controllers\Admin\ProductController::class, 'sendCustomerEmail'])->name('orders.send-email');
    Route::delete('/orders/{order}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyOrder'])->name('orders.destroy');
    
    // Blog Admin
    Route::resource('blogs', AdminBlogController::class);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/notifications', [DashboardController::class, 'getNotifications'])->name('dashboard.notifications');
    Route::post('/notifications/{id}/read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/dashboard/apply-partner', [DashboardController::class, 'applyPartner'])->name('dashboard.apply-partner');
    Route::get('/user/orders', [ShopController::class, 'userOrders'])->name('user.orders.index');
    Route::get('/user/orders/{order_number}', [ShopController::class, 'userOrderShow'])->name('user.orders.show');
});

Route::post('/create-intent', [PaymentController::class, 'createIntent'])->name('create.intent');

Route::get('/offline', function () {
    return view('offline');
})->name('offline');


require __DIR__.'/auth.php';
