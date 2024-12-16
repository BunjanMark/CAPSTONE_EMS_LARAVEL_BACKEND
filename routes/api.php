<?php
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendingUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AccountManagementController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AccountRoleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PackageServiceController;
use App\Http\Controllers\EventPackageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SmsTwilioController;
use App\Events\NewServiceCreated;
use App\Http\Controllers\TwilioSmsController;
use App\Http\Controllers\NotificationUpcomingEventController;
use App\Http\Controllers\FeedbackController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

 

// user Management authentication
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthenticatedSessionController::class, 'loginAccount']);
    Route::post('/user/logout', [AuthenticatedSessionController::class, 'logout']);
    Route::post('signup', [AuthenticatedSessionController::class, 'signupAccount']);
    // Route::post('/signup', [AuthenticatedSessionController::class, 'signupAccount']);
    Route::patch('update', [AuthenticatedSessionController::class, 'accountUpdate'])->middleware(['auth:sanctum']);
    Route::get('/me', [AuthenticatedSessionController::class, 'show'])->middleware(['auth:sanctum']);
    Route::post('switch-account', [AuthenticatedSessionController::class, 'switchAccount'])->middleware(['auth:sanctum']);
    Route::post('/createProfileServiceProvider', [AuthenticatedSessionController::class, 'createServiceProvider'])->middleware(['auth:sanctum']);
    Route::post('/createProfileCustomer', [AuthenticatedSessionController::class, 'createCustomer'])->middleware(['auth:sanctum']);
    Route::post('password-recovery', [AuthenticatedSessionController::class, 'sendRecoveryEmail']);
    Route::post('/password/recovery', [AuthenticatedSessionController::class, 'recoverPassword']);

});

// for email verification
// Route::post('/verify-email', [AuthenticatedSessionController::class, 'sendVerificationEmail']);
// Route::post('/verify-email-code', [AuthenticatedSessionController::class, 'verifyCode']);
// test email view
Route::get('/test-email-view', function () {
    return view('emails.verification', ['verificationCode' => '123456']);
});
Route::post('/verify-email', [EmailVerificationController::class, 'sendVerificationEmail']);
Route::post('/verify-email-code', [EmailVerificationController::class, 'verifyCode']);
//for switch account profile admin
Route::post('/admin/account-management', [AccountManagementController::class, 'addProfile'])->middleware(['auth:sanctum']);
Route::get('/admin/account-management', [AccountManagementController::class, 'getProfile'])->middleware(['auth:sanctum']);
Route::get('/admin/account-management/{user}', [AccountManagementController::class, 'edit']);
Route::patch('/admin/account-management/{user}', [AccountManagementController::class, 'update']);
Route::delete('/admin/account-management/{user}', [AccountManagementController::class, 'destroy']);
Route::get('/account-management', [AccountManagementController::class, 'getProfile'])->middleware(['auth:sanctum']);
Route::put('/auth/update', [AccountManagementController::class, 'update'])->middleware(['auth:sanctum']);



// Route::middleware(['admin'])->get('/admin', [AdminController::class, 'index']);
// test route respond hello world
Route::get('/hello', function () {
//     echo(url('/login'));
//  echo(route('login'));
return response()->json(['status' => 200, 'message' => 'Hello World!']);
// return response()->json(compact('token'))->header("Access-Control-Allow-Origin",  "*");
});

// Admin Package Management
Route::post('/admin/packages', [PackageController::class, 'store']);
Route::get('/admin/packages', [PackageController::class, 'index']);
Route::put('/admin/packages/{id}', [PackageController::class, 'update']);
Route::delete('/admin/packages/{id}', [PackageController::class, 'destroy']);
// Route::get('/admin/packages/{packageId}/services',[PackageServiceController::class, 'packageService']);
Route::get('/admin/packages/{packageId}/services',[PackageServiceController::class, 'getPackageServices']);
Route::get('/admin/packages/{packageId}/serviceProviders', [PackageServiceController::class, 'getPackageServiceProvidersId']);
// Admin Event Management
Route::middleware('auth:sanctum')->post('/admin/events', [EventController::class, 'store']);
Route::middleware('auth:sanctum')->get('/my-events', [EventController::class, 'getEventsByUserId']);
 
// Route::post('/admin/events', [EventController::class, 'store']);
Route::get('/admin/events', [EventController::class, 'index']);
Route::get('/admin/events/{id}', [EventController::class, 'showEventById']);
Route::delete('/admin/events/{id}', [EventController::class, 'deleteEvent']);
Route::put('/admin/events/{event}', [EventController::class, 'updateEvent']);
Route::get('/events/active', [EventController::class, 'getActiveEvents']);
Route::get('/events/archived', [EventController::class, 'getArchivedEvents']);
Route::post('/events/{id}/archive', [EventController::class, 'archiveEvent']);
Route::get('/admin/events/by-type', [EventController::class, 'fetchEventsByType']);
Route::get('/admin/events/type/{type}', [EventController::class, 'getEventsByType']);// Guest management 
Route::get('/admin/events/by-date/{date}', [EventController::class, 'fetchEventsByDate']);
Route::post('/admin/events/{id}/restore', [EventController::class, 'restoreEvent']);
Route::get('/admin/events/{id}/packages', [EventPackageController::class, 'getEventPackages']);
Route::get('/admin/events/{eventId}/user/{userId}', [EventController::class, 'getServiceProviderInfoByUserId']);
Route::get('/admin/events/{id}/user', [EventController::class, 'getUserBookingEvents'])->middleware('auth');
Route::put('/admin/events/bookings/{eventId}', [EventController::class, 'updateEventStatus']);
Route::put('/admin/events/bookings/decline/{eventId}', [EventController::class, 'declineEventStatus']);
Route::put('/admin/events/bookings/complete/{eventId}', [EventController::class, 'updateEventStatusComplete']); //to complete
Route::get('/events/month/{month}', [EventController::class, 'eventsByMonth']); 
Route::put('/admin/events/{id}/payment-status', [EventController::class, 'updatePaymentStatus']);
Route::get('/events/{eventId}/services', [EventController::class, 'getEventServices']);


// Route::get('/admin/events', [EventController::class, 'getEvents']);



Route::get('/guest', [GuestController::class, 'index']);
Route::post('/guest', [GuestController::class, 'store']);
Route::get('guest/{eventid}', [GuestController::class, 'getGuestByEvent']);
Route::put('/guest/{id}', [GuestController::class, 'update']);
Route::delete('/guest/{id}', [GuestController::class, 'destroy']);


// Notification handlers
Route::get('/notifications', [NotificationController::class, 'index']);
Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);


Route::get('/events-by-service/{userId}', [EventController::class, 'getEventsByService']);






// service management
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/services', [ServiceController::class, 'index']);         // Get all services for the authenticated user
    Route::get('/services/myservice', [ServiceController::class, 'myService']); // Get the services of the authenticated user
    Route::post('/services', [ServiceController::class, 'store']);       // Create a new service
    Route::get('/services/{id}', [ServiceController::class, 'show']);    // Get a specific service
    Route::put('/services/{id}', [ServiceController::class, 'update']);   // Update a specific service
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']); // Delete a specific service
    // Route::post('/services/myservice', [ServiceController::class, 'storeOwnService']);

    // equipment
    Route::get('/my-equipment', [EquipmentController::class, 'myEquipment']);
    Route::middleware('auth:api')->post('/equipment', [EquipmentController::class, 'store']);

    // geteventswithmyservices
    Route::get('/events/my-services', [EventController::class, 'getEventsWithMyServices']);




});
Route::get('/trigger-event', function () {
    $newService = Service::find(1); // Or create a new service for testing
    event(new NewServiceCreated($newService));

    return 'Event triggered';
});


// Protected Routes for admin
// sample
// Route::controller(ProfileController::class)->middleware(['auth','admin'])->group(function() {

//     Route::get('/admin/categories/index', 'index')->name('admin.category.index');
//     Route::get('/admin/categories/create','create')->name('admin.category.create');
//     Route::POST('/admin/categories/index','store')->name('admin.category.store');
//     Route::get('/admin/categories/edit/{category}', 'edit')->name('admin.category.edit');
//     Route::PUT('/admin/categories/update/{category}','update')->name('admin.category.update');
//     Route::DELETE('/admin/categories/delete/{category}', 'delete')->name('admin.category.delete');
    
//   });

Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::get('/api/events/{date}', [EventController::class, 'eventsForDay']);
Route::get('equipment', [EquipmentController::class, 'index']);
Route::post('equipment', [EquipmentController::class, 'store']);
Route::put('equipment/{id}', [EquipmentController::class, 'update']);
Route::delete('equipment/{id}', [EquipmentController::class, 'destroy']);
Route::get('/event/{eventId}/equipment', [EquipmentController::class, 'getEquipmentForEvent']);
Route::get('/event/{eventId}/equipment/{userId}', [EquipmentController::class, 'getEquipmentForEventForUserId']);

Route::post('/equipment/{eventId}', [EquipmentController::class, 'store']);


Route::get('pending', [PendingUserController::class, 'index']);
Route::post('/pending', [PendingUserController::class, 'register']);

Route::post('/accept-pending-user/{id}', [UserController::class, 'acceptPendingUser']);
Route::delete('/decline-pending-user/{id}', [UserController::class, 'declinePendingUser']);

Route::post('/login', [UserController::class, 'login']);



// 

Route::get('/inventories', 'InventoryController@index');
Route::post('/inventories', 'InventoryController@store');
Route::get('/inventories/{id}', 'InventoryController@show');
Route::put('/inventories/{id}', 'InventoryController@update');
Route::delete('/inventories/{id}', 'InventoryController@destroy');

Route::get('/inventories',  [EventController::class, 'index']);
Route::post('/inventories', [EventController::class, 'store']);
Route::get('/inventories/{id}', [EventController::class, 'show']);
Route::put('/inventories/{id}', [EventController::class, 'update']);
Route::delete('/inventories/{id}', [EventController::class, 'destroy']);


Route::get('/send-sms', [NotificationUpcomingEventController::class, 'sendReminder']);
Route::post('event/{id}/send-schedule-notice', [EventController::class, 'sendEventScheduleNotice']);


use Illuminate\Support\Facades\Mail;
use App\Mail\EventReminder;
use App\Models\Event;

Route::get('/test-email', function () {
    Mail::raw('This is a test email.', function ($message) {
        $message->to('bunjan.mark476@gmail.com')
                ->subject('Test Email');
    });
    return 'Test email sent!';
});

 
use App\Http\Controllers\EventReminderController;
Route::get('event/{eventId}/reminder', [EventReminderController::class, 'sendEventReminder']);



use App\Http\Controllers\PasswordResetController;

Route::post('/account-recovery', [PasswordResetController::class, 'sendResetCode']);
Route::post('/verify-password-reset-code', [PasswordResetController::class, 'verifyResetCode']);
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);



Route::get('/equipment/{eventId}', [EquipmentController::class, 'index']);
Route::post('/equipment', [EquipmentController::class, 'store']);
Route::put('/equipment/{id}', [EquipmentController::class, 'update']);
Route::delete('/equipment/{id}', [EquipmentController::class, 'destroy']);

// For feedback web

// use App\Http\Controllers\FeedbackController;
// Route::get('/feedback', [FeedbackController::class, 'showForm']);

// Route::post('/feedback/submit', [FeedbackController::class, 'submitFeedback'])->name('feedback.submit');

