<?php

namespace App\Base\Http\Controllers;

use Illuminate\Support\Facades\Route;

/**********************************
    Login
 **********************************/

Route::get('login', [Auth\LoginController::class, 'showLoginForm']);

Route::post('login', [Auth\LoginController::class, 'login']);

Route::post('data/logout', [Auth\LoginController::class, 'logout']);

// Route::get('/{vue_capture?}', function () {
//     return view('layouts.app');
// })->where('vue_capture', '[\/\w\.-]*');

/**********************************
    Password
 **********************************/

Route::post('password/email', [Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);

Route::get('password/reset', [Auth\ForgotPasswordController::class, 'showLinkRequestForm']);

Route::post('password/reset', [Auth\ResetPasswordController::class, 'reset']);

Route::get('password/reset/{token}', [Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');

/**********************************
    Registration
 **********************************/

// Route::post('register/invite-link', [InvitationController::class, 'store'])->middleware(['auth', 'admin']);

// Route::get('register/invite-link', [InvitationController::class, 'show'])->middleware(['auth', 'admin']);

// Route::get('register/invite-link/{token}', [InvitationController::class, 'showRegistrationForm'])->middleware('guest');

// Route::post('register/invite-link/{token}', [Auth\RegisterController::class, 'registerViaLink']);

Route::post('data/register/invite', [UserController::class, 'sentInvitationToRegister'])->middleware(['auth', 'admin']);

Route::get('register', [Auth\RegisterController::class, 'showRegistrationForm'])->middleware('guest');

Route::post('register', [Auth\RegisterController::class, 'confirmNewRegistration']);

Route::get('register/completed', [Auth\RegisterController::class, 'registrationCompleted']);




/**********************************
    Impersonate User
 **********************************/

Route::impersonate();

Route::get('/{vue_capture?}', [HomeController::class, 'index'])->where('vue_capture', '^(?!data).*$')->name('home')->middleware('auth');

/**********************************
        Group (Project/Team/Office)
 **********************************/

Route::group(['middleware' => 'auth'], function () {
    Route::get('groups/settings', [GroupSettingsController::class, 'index']);

    Route::put('groups/settings', [GroupSettingsController::class, 'update']);

    Route::get('groups/permissions', [GroupPermissionController::class, 'index']);

    Route::post('groups/permissions/{permission}/roles/{role}', [GroupPermissionController::class, 'store']);

    Route::delete('groups/permissions/{permission}/roles/{role}', [GroupPermissionController::class, 'delete']);

    Route::get('groups/tags', [GroupTagsController::class, 'index']);

    Route::post('groups/tags/{tag}', [GroupTagsController::class, 'store']);

    Route::delete('groups/tags/{tag}', [GroupTagsController::class, 'delete']);
});

/**********************************
        Member
 **********************************/

Route::group(['middleware' => 'auth'], function () {
    Route::get('data/members', [MemberController::class, 'index']);

    Route::post('data/members', [MemberController::class, 'store']);

    Route::delete('data/members', [MemberController::class, 'destroy']);
});

/**********************************
        Event
 **********************************/

Route::group(['middleware' => 'auth'], function () {
    Route::get('events', [EventController::class, 'index']);

    Route::post('events', [EventController::class, 'store']);

    Route::get('events/{event}', [EventController::class, 'index']);
});

/**********************************
        File
 **********************************/

Route::get('files', [FileController::class, 'index']);

Route::group(['middleware' => 'auth'], function () {
    Route::get('files/{file}', [FileController::class, 'index']);

    Route::post('files', [FileController::class, 'store']);

    Route::delete('files/{file}', [FileController::class, 'delete']);
});

/**********************************
        Message
 **********************************/



/**********************************
        Comment
 **********************************/

Route::get('/comments', [CommentController::class, 'index']);

Route::group(['middleware' => 'auth'], function () {
    Route::post('/comments', [CommentController::class, 'store']);

    Route::delete('/comments/{comment}', [CommentController::class, 'delete']);
});

/**********************************
    Cycle
 **********************************/

Route::get('cycles', [CycleController::class, 'index']);

Route::post('cycles', [CycleController::class, 'store'])->middleware('auth');


/**********************************
User Status And Delete With Token
**********************************/

Route::get('data/users/{user}/status-update/{toggle}/{token}', [UserController::class, 'updateUserStatus']);

Route::get('data/users/{user}/delete-user/{token}', [UserController::class, 'deleteUser']);

// Route::get('users/user-updated', [UserController::class, 'userDataUpdated'])->name('user-updated');



Route::group(['middleware' => 'auth'], function () {


    Route::post('data/users/delete/{user_id}', [UserController::class, 'deleteUserFromDashboard']);
    /**********************************
        Category
     **********************************/

    Route::get('categories', [CategoryController::class, 'index']);

    Route::post('categories', [CategoryController::class, 'store']);

    /**********************************
        Roadmap
     **********************************/

    Route::get('cycles/{cycle}/roadmap', [RoadmapController::class, 'show']);

    /**********************************
        Tags
     **********************************/

    Route::get('tags', [TagController::class, 'index']);

    Route::post('tags', [TagController::class, 'store']);

    /**********************************
        Notification
     **********************************/

    Route::get('data/notifications', [NotificationController::class, 'index']);

    Route::get('data/unread-notifications', [UnreadNotificationController::class, 'index']);

    Route::put('data/notifications', [NotificationController::class, 'update']);

    Route::post('data/logs/activity', [ActivityLogController::class, 'index']);

    /**********************************
        User
     **********************************/

    Route::get('data/users', [UserController::class, 'index']);

    Route::get('data/online-users', [UserController::class, 'getOnlineUsers']);

    Route::get('data/available-users', [UserController::class, 'getAvailableUsers']);

    Route::get('data/users/{user}', [UserController::class, 'show']);

    Route::get('data/username', [UserController::class, 'checkUsername']);

    Route::put('data/users/{user}/account', [UserAccountController::class, 'update']);  

    Route::put('data/users/{user}/profile', [UserProfileController::class, 'update']);

    Route::post('data/users/profile/update/{user}', [UserProfileController::class, 'updateUser']); 

    Route::post('data/users/{user}/avatar', [UserAvatarController::class, 'store']);

    /**********************************
        Github Service
     **********************************/

    Route::get('services/github/repos', [GithubRepoController::class, 'index']);

    Route::get('services/github/connected-repos', [ConnectedGithubRepoController::class, 'index']);

    Route::post('services/github/connected-repos', [ConnectedGithubRepoController::class, 'store']);

    /**********************************
        Role
     **********************************/

    Route::get('data/roles', [RoleController::class, 'index']);

    Route::get('activities', [ActivityController::class, 'index']);

    /**********************************
        Settings
     **********************************/

    Route::view('settings', 'users.settings');

    /**********************************
        Common data
     **********************************/


    Route::get('data/common-data', [CommonController::class, 'getCommonData']);

    Route::post('data/transfer-data', [CommonController::class, 'transfertDataOfProjectsAndDealsThenDeleteUser']);

    /**********************************
        Permissions check for certain actions
     **********************************/


    Route::post('data/permissions/{group}/{action}/{group_id}', [PermissionController::class, 'checkUserHasPermmission']);

});

/**********************************
        Admin
 **********************************/

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index']);

    Route::get('data/roles/{role}/permissions', [RolePermissionController::class, 'index']);

    Route::post('data/roles/{role}/permissions/{permission}', [RolePermissionController::class, 'store']);

    Route::delete('data/roles/{role}/permissions/{permission}', [RolePermissionController::class, 'delete']);

    Route::get('data/permissions', [PermissionController::class, 'index']);

    Route::get('data/activities', [ActivityController::class, 'index']);

    Route::get('data/services', [ServiceController::class, 'index']);

    Route::post('data/services', [ServiceController::class, 'store']);

    Route::put('data/services/{service}', [ServiceController::class, 'update']);

    Route::get('data/check-for-update', [AboutController::class, 'checkForUpdate']);

    Route::get('data/app-settings', [AppSettingController::class, 'index']);

    Route::patch('data/app-settings/{appSetting}', [AppSettingController::class, 'update']);
});

/**********************************
       User Requests to admin
 **********************************/

Route::group(['middleware' => 'auth'], function () {

    Route::post('data/request/{resource}/{request_type}', [UserRequestsController::class, 'handleUserRequest']);

});

// Routes based on token for admin
Route::get('data/request/{request_id}/{status}/{token}', [UserRequestsController::class, 'updateUserDataForRequest']);


