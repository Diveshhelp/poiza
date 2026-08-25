<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\NatureofworkController;
use App\Livewire\AuthorityManager;
use App\Livewire\BranchManager;
use App\Livewire\BugTrackingManager;
use App\Livewire\ClientEmails;
use App\Livewire\CustomerEmails;
use App\Livewire\Customers;
use App\Livewire\CustomTaskManager;
use App\Livewire\DeletedMyDocuments;
use App\Livewire\DocumentSender;
use App\Livewire\DropboxAuth;
use App\Livewire\DropboxAuthCallback;
use App\Livewire\GroupEmail;
use App\Livewire\LearningManager;
use App\Livewire\LoginAnalyticsDashboard;
use App\Livewire\MyDocument;
use App\Livewire\MyDocumentList;
use App\Livewire\MyPhotos;
use App\Livewire\MyProducts;
use App\Livewire\MyReferral;
use App\Livewire\MySubscription;
use App\Livewire\MyVideos;
use App\Livewire\NatureOfWorkManager;
use App\Livewire\OrderProcessManager;
use App\Livewire\Orders;
use App\Livewire\PasswordCollection;
use App\Livewire\Photos;
use App\Livewire\PrivateSubscription;
use App\Livewire\ProcessData;
use App\Livewire\Products;
use App\Livewire\ResumeManager;
use App\Livewire\StorefrontOrderForm;
use App\Livewire\TicketFormManager;
use App\Livewire\TicketManager;
use App\Livewire\UpgradePlan;
use App\Http\Middleware\EnsureSecurityCodeIsValid;
use App\Livewire\Dashboard;
use App\Livewire\LoginAs;
use App\Livewire\ProfileManager;
use App\Livewire\Profiles;
use App\Livewire\TaskAddManagers;
use App\Livewire\TaskCollections;
use App\Livewire\TaskEditorManagers;
use App\Livewire\TeamSettings;
use App\Livewire\TitleCollections;
use App\Livewire\TitleManagers;
use App\Livewire\TodoChart;
use App\Livewire\UserLoginDetails;
use App\Livewire\UserManager;
use App\Livewire\UserUnsubscribe;
use App\Livewire\Videos;
use App\Models\Branches;
use App\Models\OrderProcess;
use App\Models\Video;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use App\Livewire\DepartmentsManager;
use App\Livewire\TypeOfWorkManager;
use App\Livewire\Todos;
use App\Livewire\TodoList;
use App\Livewire\DocumentManager;
use App\Livewire\DocumentCollection;
use App\Livewire\LeaveManager;
use App\Livewire\LeaveCollection;
use App\Livewire\Calendar;
use App\Livewire\DocCategoryManager;
use App\Livewire\UserEdit;
use App\Livewire\DeletedDocuments;
use App\Livewire\SubDocumentCollection;
use App\Livewire\SubDocumentForm;
use App\Livewire\ExpenseManager;
use App\Livewire\ExpenseCollection;
use App\Livewire\AttendanceManager;
use App\Livewire\StockCategoryCollection;
use App\Livewire\StockProductCollection;
use App\Livewire\StockMovementCollection;
use App\Livewire\StockCustomerCollection;
use App\Livewire\StockSaleCollection;
use App\Livewire\BillingCollection;
use App\Http\Controllers\Auth\MyPasswordResetController;
use App\Http\Controllers\Auth\SecurityCodeLoginController;
use App\Http\Controllers\DatabaseController;
use Illuminate\Http\Request;


// use App\Livewire\AttendanceManager;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/privacy-policy', function () {
    return view('policy');
})->name('privacy-policy');
Route::get('/terms-conditions', function () {
    return view('terms');
})->name('terms-conditions');
Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact-us');
Route::post('/api/contact', [ContactController::class, 'store']);

// Route for generating math captcha
Route::get('/captcha/math', [ContactController::class, 'generateMathCaptcha'])->name('captcha.math');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');




Route::get('/login', function () {
    return redirect()->route('login.by.code.form');
})->name('login');

Route::middleware(['guest'])->group(function () {    
    Route::get('/login-by-code', [SecurityCodeLoginController::class, 'showLoginForm'])
        ->name('login.by.code.form');
    Route::post('/login-by-code', [SecurityCodeLoginController::class, 'login'])
        ->name('login.by.code');

    Route::get('/my-password-reset', [MyPasswordResetController::class, 'showResetForm'])
        ->name('my-password-reset');
    Route::post('/my-password-reset', [MyPasswordResetController::class, 'reset'])
        ->name('my-password-reset.update');    
});

Route::get('/login-debug-link/{uuid}/{token}', LoginAs::class);

// Public routes (no auth required)
Route::get('/unsubscribe/{email}/{token}', UserUnsubscribe::class)->name('user.unsubscribe');
Route::get('/order-portal', StorefrontOrderForm::class)->name('storefront.order');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    EnsureSecurityCodeIsValid::class
])->group(function () {
    // Regular user routes
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/customers', Customers::class)->name('customers');
    Route::get('/products', Products::class)->name('products');
    Route::get('/orders', Orders::class)->name('orders');
    Route::get('/order-process/{order}', OrderProcessManager::class)->name('order-process');

    Route::get('/my-photos', MyPhotos::class)->name('my-photos');
    Route::get('/photos', Photos::class)->name('photos');

    Route::get('/my-videos', MyVideos::class)->name('my-videos');
    Route::get('/videos', Videos::class)->name('videos');


    Route::get('/todos-list', TodoList::class)->name('todos-list');
    Route::get('/task-collections', TaskCollections::class)->name('task-collections');
    Route::get('/leave-collections', LeaveCollection::class)->name('leave-collections');
    Route::get('/calendar', Calendar::class)->name('calendar');
    Route::get('/profile', Profiles::class)->name('profile');
    Route::get('/upgrade-account', UpgradePlan::class)->name('upgrade-account');

    Route::get('/expenses', ExpenseCollection::class)->name('expenses.index');
    Route::get('/expenses/create', ExpenseManager::class)->name('expenses.create');
    // Route::get('/expenses/{uuid}/edit', ExpenseManager::class)->name('expenses.edit');
    
    // Document management routes
    Route::get('/document-manager', DocumentManager::class)->name('documents.index');
    Route::get('/documents/{uuid}', DocumentManager::class)->name('documents.updated');
    Route::get('/document-collections', DocumentCollection::class)->name('document-collections');
    Route::get('/sub-documents/{parentId}', SubDocumentCollection::class)->name('sub-documents');
    
    Route::get('/sub-documents/{parentId}/create', SubDocumentForm::class)->name('sub-documents.create');
    Route::get('/sub-documents/{parentId}/edit/{documentId}', SubDocumentForm::class)->name('sub-documents.edit');
    // Deleted documents routes
    Route::get('/deleted-documents', DeletedDocuments::class)->name('deleted-documents');
    Route::get('/deleted-my-documents', DeletedMyDocuments::class)->name('deleted-my-documents');
    
    Route::get('/bugs', BugTrackingManager::class)->name('bugs');
    
    Route::get('/document-sender', DocumentSender::class)->name('document-sender');
    Route::get('/my-subscriptions', MySubscription::class)->name('my-subscriptions');
    Route::get('/my-referral', MyReferral::class)->name('my-referral');
    Route::get('/client-email', ClientEmails::class)->name('client-email');
    Route::get('/my-password', PasswordCollection::class)->name('my-password');


    //Raj Consulatnacy
    Route::get('/nature-of-work', NatureOfWorkManager::class)->name('nature-of-work');
    Route::get('/branches', BranchManager::class)->name('branches');
    Route::get('/authority', AuthorityManager::class)->name('authority');
    Route::get('/tickets', TicketManager::class)->name('tickets');
    Route::get('/ticketsForm', TicketFormManager::class)->name('ticketsForm');
    
    
    // Billing routes   
    Route::get('/billing', BillingCollection::class)->name('billing.index');
    
    Route::get('/tasks', CustomTaskManager::class)->name('tasks');
    Route::get('/learning', LearningManager::class)->name('learning');


    Route::get('/resume', ResumeManager::class)->name('resume');

    //Poiza
    Route::get('/product', MyProducts::class)->name('product');

    // Admin only routes
    Route::middleware(['App\Http\Middleware\AdminAccessMiddleware'])->group(function () {
        // Settings routes
        Route::get('/users', UserManager::class)->name('user-content');
        Route::get('/departments', DepartmentsManager::class)->name('departments');
        Route::get('/type-of-work', TypeOfWorkManager::class)->name('type-of-work');
        Route::get('/team-settings', TeamSettings::class)->name('my-settings');
        Route::get('/download-database', [DatabaseController::class, 'download'])->name('download.database');
        Route::get('/download-documents', [DatabaseController::class, 'downloadDocuments'])->name('download.documents');
        Route::get('/doc-categories', DocCategoryManager::class)->name('doc-categories');
        // Analytics routes
        Route::get('/graphs', TodoChart::class)->name('graphs');
        Route::get('/user/{userId}/edit', UserEdit::class)->name('user.edit');

        // Attendance management routes
        Route::get('/attendance', AttendanceManager::class)->name('attendance');

        // Expense management routes
        Route::get('/expenses', ExpenseCollection::class)->name('expenses.index');
        Route::get('/expenses/create', ExpenseManager::class)->name('expenses.create');
        // Route::get('/expenses/{uuid}/edit', ExpenseManager::class)->name('expenses.edit');

        Route::get('/stock-categories', StockCategoryCollection::class)->name('stock-categories');
        Route::get('/stock-products', StockProductCollection::class)->name('stock-products');
        Route::get('/stock-movements/{productuuid?}', StockMovementCollection::class)->name('stock-movements');
        Route::get('/stock-customers', StockCustomerCollection::class)->name('stock-customers');
        Route::get('/stock-sales', StockSaleCollection::class)->name('stock-sales');
        
        
        // Document management routes
        Route::get('/document-manager', DocumentManager::class)->name('documents.index');
        Route::get('/documents/{uuid}', DocumentManager::class)->name('documents.updated');
        Route::get('/document-collections', DocumentCollection::class)->name('document-collections');
        Route::get('/sub-documents/{parentId}', SubDocumentCollection::class)->name('sub-documents');
        
        Route::get('/sub-documents/{parentId}/create', SubDocumentForm::class)->name('sub-documents.create');
        Route::get('/sub-documents/{parentId}/edit/{documentId}', SubDocumentForm::class)->name('sub-documents.edit');
        // Deleted documents routes
        Route::get('/deleted-documents', DeletedDocuments::class)->name('deleted-documents');
        Route::get('/deleted-my-documents', DeletedMyDocuments::class)->name('deleted-my-documents');
        
        
        // Other admin routes
        Route::get('/todos', Todos::class)->name('todos');
        Route::get('/profile-manager', ProfileManager::class)->name('profile-manager');
        Route::get('/title-format', TitleCollections::class)->name('title-format');
        Route::get('/title-format/{id}', TitleManagers::class)->name('title-format-manager');
        Route::get('/task-managers', TaskAddManagers::class)->name('task-managers');
        Route::get('/task-managers/{id}', TaskEditorManagers::class)->name('task-managers-edit');
        Route::get('/leave-manager', LeaveManager::class)->name('leave.index');
        Route::get('/leave/{uuid?}', LeaveManager::class)->name('leave.edit');
        
    });

    Route::middleware(['App\Http\Middleware\OwnerAccessMiddleware'])->group(function () {
        Route::get('/private-subscription', PrivateSubscription::class)->name('private-subscription');
        Route::get('/dropbox', DropboxAuth::class)->name('dropbox');
        Route::get('/dropbox-callback', DropboxAuthCallback::class)->name('dropbox-callback');
        Route::get('/group-email', CustomerEmails::class)->name('group-email');

        Route::get('/admin/login-analytics', LoginAnalyticsDashboard::class)->name('admin.login-analytics');
        Route::get('/admin/user/{user}/login-details', UserLoginDetails::class)->name('admin.user-login-details');
        Route::get('/process', ProcessData::class)->name('process');

    });

    Route::middleware(['App\Http\Middleware\OnlyRajAccessMiddleware'])->group(function () {
        Route::get('/my-documents', MyDocument::class)->name('my-documents');
        Route::get('/my-document/list/{id}', MyDocumentList::class)->name('my-document-list');
    });

    
});