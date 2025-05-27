<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipientController;
use App\Models\Event;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home', [
    'events' => Event::with('user:id,name')->where('status', 'pending')->latest()->paginate(6),
])->name("home");

Route::get('/events', [EventController::class, 'index'])->name("events");
Route::get('/events/{event}', [EventController::class, 'show'])->name("events.show");


Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Reset Password
    Route::get('/forgot-password', [ResetPasswordController::class, 'requestPass'])->name('password.request');
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetHandler'])->name('password.update');
});


Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Email Verification
    // Email Verification notice
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    // Email Verification Handler
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'handler'])
    ->middleware('signed')->name('verification.verify');
    // Email Verification Resend
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
    ->middleware('throttle:6,1')->name('verification.send');

    // Password Confirmation
    Route::get('/confirm-password', [ConfirmPasswordController::class, 'create'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmPasswordController::class, 'store'])->middleware('throttle:6,1');


    // Profile Page
    Route::get('/profile', [ProfileController::class, 'edit'])->middleware('password.confirm')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::put('/profile', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->middleware('role:admin')->name('admin.dashboard');
    Route::get('/donor/dashboard', [DonorController::class, 'index'])->middleware('role:donor')->name('donor.dashboard');
    Route::get('/recipient/dashboard', [RecipientController::class, 'index'])->middleware('role:recipient')->name('recipient.dashboard');
    Route::get('/hospital/dashboard', [HospitalController::class, 'index'])->middleware('role:hospital')->name('hospital.dashboard');
    Route::get('/organization/dashboard', [OrganizationController::class, 'index'])->middleware('role:organization')->name('organization.dashboard');
    Route::resource('events', EventController::class)->except(['index', 'show', 'create']);
    Route::get('/event/create', [EventController::class, 'create'])->name('events.create');
    
    Route::resource('enroll', EventRegistrationController::class)->except(['store']);
    Route::post('/event/{event}/enroll', [EventRegistrationController::class, 'store'])->name('enroll.store');

    Route::resource('requests', BloodRequestController::class);
});

// Adding Missing Routes

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Admin specific routes
    Route::get('/admin/manage-users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::get('/admin/add-users', [AdminController::class, 'addUser'])->name('admin.addusers');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->middleware('password.confirm')->name('admin.profile');
    Route::get('/admin/events', [AdminController::class, 'events'])->name('admin.events');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/inventory', [AdminController::class, 'inventory'])->name('admin.inventory');
    Route::resource('users', UserController::class)->except(['show', 'index']);
});

// Donor Routes
Route::middleware(['auth', 'verified', 'role:donor'])->group(function () {
    Route::get('/donor/donations', [DonorController::class, 'donations'])->name('donor.donations');
    Route::get('/donor/events', [DonorController::class, 'events'])->name('donor.events');
    Route::get('/donor/requests', [DonorController::class, 'requests'])->name('donor.requests');
    Route::get('/donor/profile', [DonorController::class, 'profile'])->middleware('password.confirm')->name('donor.profile');
    
    Route::post('/blood-requests/{bloodRequest}/donate', [BloodRequestController::class, 'donate'])
    ->name('donor.donate');

    

});
// Track Donation requests
Route::get('/donor/request/{id}', [BloodRequestController::class, 'viewRequest'])
->name('request.view');

// Recipient Routes
Route::middleware(['auth', 'verified', 'role:recipient'])->group(function () {
    Route::get('/recipient/request-blood', [RecipientController::class, 'requestBlood'])->name('recipient.request');
    Route::get('/recipient/my-requests', [RecipientController::class, 'myRequests'])->name('recipient.requests');
    Route::get('/recipient/find-donors', [RecipientController::class, 'findDonors'])->name('recipient.find-donors');
    Route::get('/recipient/profile', [RecipientController::class, 'profile'])->middleware('password.confirm')->name('recipient.profile');
});

// Hospital routes
Route::middleware(['auth', 'verified', 'role:hospital'])->group(function () {
    Route::get('/hospital/manage-requests', [HospitalController::class, 'manageRequests'])->name('hospital.requests');
    Route::get('/hospital/donor-database', [HospitalController::class, 'donorDatabase'])->name('hospital.donors');
    Route::get('/hospital/inventory', [HospitalController::class, 'inventory'])->name('hospital.inventory');
    Route::get('/hospital/events', [HospitalController::class, 'events'])->name('hospital.events');
    Route::get('/hospital/reports', [HospitalController::class, 'reports'])->name('hospital.reports');
    Route::get('/hospital/profile', [HospitalController::class, 'profile'])->middleware('password.confirm')->name('hospital.profile');

    Route::middleware(['auth', 'role:hospital'])->group(function () {
        Route::post('/assign/{bloodRequestId}', [BloodRequestController::class, 'assignBloodToRecipient'])->name('blood.assign');
    });
    Route::resource('inventory', BloodInventoryController::class);
});

// Organization Routes
Route::middleware(['auth', 'verified', 'role:organization'])->group(function () {
    Route::get('/organization/events', [OrganizationController::class, 'events'])->name('organization.events');
    Route::get('/organization/dashboard', [OrganizationController::class, 'index'])->name('organization.dashboard');
    Route::get('/organization/reports', [OrganizationController::class, 'reports'])->name('organization.reports');
    Route::get('/organization/profile', [OrganizationController::class, 'profile'])->name('organization.profile');
});

