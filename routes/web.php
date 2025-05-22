<?php

use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\superadmin\addUserController;
use App\Http\Controllers\superadmin\SuperAdminController;
use App\Http\Controllers\userController\allUserController;
use App\Http\Middleware\DissableBackBtn;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/input-evaluation/{employee_id?}', [HomeController::class, 'index'])->name('input-evaluation');
Route::post('/send-otp', [HomeController::class, 'sendOtp'])->name('evaluation-send-otp');
Route::post('/evaluation-verify-otp', [HomeController::class, 'evaluationverifyOtp'])->name('evaluation-verify-otp');
Route::post('/insert-evaluation', [HomeController::class, 'submitEvaluation'])->name('insert-data-evaluation');

//Login



//Forget Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('forgot-password.send-otp');
Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyOtpForm'])->name('forgot-password.verify');
Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOther'])->name('forgot-password.verify.other');
Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('forgot-password.reset');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset');


//data insert route
Route::get('/test-page', [SuperAdminController::class, 'testPageShow'])->name('show-page');

Route::post('/insertd', [SuperAdminController::class, 'insertData'])->name('insert-data');

Route::post("/login-users", [SuperAdminController::class, 'loginAutenticacao'])->name("user-login");
Route::post("/verify-otp", [SuperAdminController::class, 'verifyOtp'])->name("verify-otp");


//Super Admin

Route::post('/Save-user', [addUserController::class, 'addUser'])->name('save-user');

Route::group(['middleware' => DissableBackBtn::class], function () {
    Route::group(['middleware' => CheckRole::class], function () {
        Route::get('/admin-dashboard', [allUserController::class, 'admin'])->name('admin-dashboard');
        Route::get('/hr-dashboard', [allUserController::class, 'hr'])->name('hr-dashboard');
        Route::get('/user-dashboard', [allUserController::class, 'user'])->name('users-dashboard');
        Route::get('/manager-dashboard', [allUserController::class, 'manager'])->name('manager-dashboard');
        Route::get('/admin-review-section', [allUserController::class, 'adminReviewSection'])->name('admin-review');
        Route::get('/hr-review-section', [allUserController::class, 'hrReviewSection'])->name('hr-review');
        Route::get('/manager-review-section', [allUserController::class, 'managerReviewSection'])->name('manager-review');
        Route::get('/client-dashboard', [allUserController::class, 'viewClientDashBoard'])->name('client-dashboard');
        Route::get('/client-review-section', [allUserController::class, 'clientReviewSection'])->name('client-review');


        Route::get('/add-user', [addUserController::class, 'indexAddUser'])->name('add-user');
        Route::get('/super-admin-search', [SuperAdminController::class, 'searchUser'])->name('super.search');
        Route::get('/super-admin-search-bar', [SuperAdminController::class, 'superAdminSearchUser'])->name('super-user-search-bar');
        Route::get('/user-list', [SuperAdminController::class, 'userListView'])->name('userlist');
        Route::get('/get-active-users', [SuperAdminController::class, 'getActiveUsers'])->name('active-user');
        Route::get('/appraisal', [SuperAdminController::class, 'appraisalView'])->name('appraisal-view');
        Route::get('/financial', [SuperAdminController::class, 'financialView'])->name('financial.view');
       // Route::post('/loged-out', [addUserController::class, 'logedOut'])->name('logged-Out');
    });
});

// Route::get('/', [SuperAdminController::class, 'index'])->name('login-index');
Route::get('/view-super-admin-dashboard', [SuperAdminController::class, 'indexSuperAdminDashBoard'])->name('super-admin-view');
Route::get('/admin/super-admin-dashboard', [SuperAdminController::class, 'showDashboard'])->name('super-admin-dashboard');

//User's login route
Route::get('/', [allUserController::class, 'indexUserLogin'])->name('all-user-login');
Route::post('/log-out-users', [allUserController::class, 'userLogOut'])->name('logout-users');
//User Login
// Route::get("/User's-Login",[\App\Http\Controllers\userController\allUserController::class,"indexLoginUser"])->name("user's-login-page");
Route::post('/user-login', [allUserController::class, 'loginUserAutenticacaon'])->name('log-in');
Route::post('/verify-otp-login-users', [allUserController::class, 'loginUserVerifyOtp'])->name('verify-otp-login-users');


//User Review Reports
Route::get('/review-reports/{emp_id}', [allUserController::class, 'reviewUserReport'])->name('get-review-reports');





//Admin, Hr, Manager,  review section
Route::get('/search', [allUserController::class, 'searchUser'])->name('user-search');
Route::post('/submit-admin-review', [allUserController::class, 'adminReviewStore'])->name('admin.review.submit');
Route::post('/submit-hr-review', [allUserController::class, 'hrReviewStore'])->name('hr.review.submit');
Route::post('/submit-manager-review', [allUserController::class, 'managerReviewStore'])->name('manager.review.submit');
Route::post('/submit-client-review', [allUserController::class, 'clientReviewStore'])->name('client.review.submit');

//Client
Route::get('/client-search-user',[allUserController::class,'clientSearch'])->name('client-search');


//client
// Route::get('/client-dashboard', [allUserController::class, 'viewClientDashBoard'])->name('client-dashboard');
// Route::get('/client-review-section', [allUserController::class, 'clientReviewSection'])->name('client-review');

//Apprisal 
Route::get('/apprisal-data', [SuperAdminController::class, 'getAppraisalData'])->name('apprisal.data');
// Route::post('/toggle-status/{id}', [SuperAdminController::class, 'toggleStatus'])->name('toggle-status');
Route::post('/toggle-status/{user_type}/{identifier}', [SuperAdminController::class, 'toggleStatus']);

Route::get('/search-employee', [SuperAdminController::class, 'searchEmployee']);
// Route::post('/toggle-status/{id}', [SuperAdminController::class, 'toggleStatus']);

//Finalcial
Route::get('/financial-data', [SuperAdminController::class, 'getFinancialData'])->name('financial.data');
Route::post('/financial-data-store', [FinancialYearController::class, 'storeFinancialData'])->name('financial-data-store');
Route::get('/financial-view-table',[FinancialYearController::class,'financialTableView'])->name('financial-view-tables');
Route::get('/super/user/search', [FinancialYearController::class, 'searchEmployee'])->name('super.user.search.bar');





Route::get('/employee/details/{emp_id}', [SuperAdminController::class, 'viewDetailsAll'])->name('employee.details');
Route::get('/hr/review/details/{emp_id}', [SuperAdminController::class, 'getSuperAdminHrReview'])->name('hr.review.details');
Route::get('/admin/review/details/{emp_id}', [SuperAdminController::class, 'getSuperAdminAdminReview'])->name('admin.review.details');
Route::get('/manager/review/details/{emp_id}', [SuperAdminController::class, 'getSuperAdminManagerReview'])->name('manager.review.details');
Route::get('/employee/evaluation/{emp_id}', [SuperAdminController::class, 'getSuperAdminEvaluationView'])->name('evaluation.details');
Route::get('/client/review/details/{emp_id}', [SuperAdminController::class, 'getSuperAdminClientReview'])->name('client.review.details');





Route::get('evaluation/details/{emp_id}', [allUserController::class, 'evaluationDetails'])->name('evaluation.details');
Route::get('manager/report/{emp_id}', [allUserController::class, 'managerReport'])->name('manager.report');
Route::get('admin/report/{emp_id}', [allUserController::class, 'adminReport'])->name('admin.report');
Route::get('hr/report/{emp_id}', [allUserController::class, 'hrReport'])->name('hr.report');
Route::get('client/report/{emp_id}', [allUserController::class, 'clientReport'])->name('client.report');
Route::get('report/{reportType}/{emp_id}', [allUserController::class, 'loadReport'])->name('report.load');


Route::get('/hr-review-list',[allUserController::class,'getHrReviewsList'])->name('hr-review-list');
Route::get('/user/details/hr/{employee_id}',[allUserController::class,'showDetailsHr'])->name('user-hr-details');
Route::get('/admin-review-list',[allUserController::class,'getAdminReviewList'])->name('admin-review-list');
Route::get('/user/details/admin/{employee_id}', [allUserController::class, 'showDetailsAdmin'])->name('user-admin-details');
Route::get('/manager-review-list',[allUserController::class,'getManagerReviewList'])->name('manager-review-list');
Route::get('/user/details/manager/{employee_id}',[allUserController::class,'showDetailsManager'])->name('user-manager-details');
Route::get('/client-review-list',[allUserController::class,'getClientReviewList'])->name('client-review-list');
Route::get('/user/details/client/{employee_id}',[allUserController::class,'showDetailsClient'])->name('user-client-details');
//Evaluation View
Route::get('/evaluation-view-hr/{employee_id}',[allUserController::class,'showEvaluationDetails'])->name('user-report-view-evaluation');
Route::post('/evaluation-report-submit/{emp_id}',[HomeController::class,'submitEvaluationDirector'])->name('director-submit-from');


Route::get('/setting',[FinancialYearController::class,'getSettingView'])->name('setting-view');
Route::post('save-apprisal',[FinancialYearController::class,'setApprisalPercentage'])->name('submit-apprisal-all');
Route::get('/probation-period',[SuperAdminController::class,'getProbationPeriod'])->name('get-probation');





// Routes for updating status and probation date
// Route::post('/employee/{id}/status', [SuperAdminController::class, 'updateStatus']);

Route::post('/employee/{employeeId}/status', [SuperAdminController::class, 'updateStatus']);
Route::post('/employee/{employeeId}/probation-date', [SuperAdminController::class, 'updateProbationDate']);
Route::post('/check-duplicate-evaluation', [HomeController::class, 'checkDuplicateSubmission'])->name('check-duplicate-evaluation');


//Appraisal peding list
Route::get('/appraisal-pending',[SuperAdminController::class,'getPendingAppraisalView'])->name('get-pending-apprasil');



//Financila year dropdown 
Route::post('/employees/filter-financial-year', [FinancialYearController::class, 'filterByFinancialYear']);
Route::post('/financial/filter-financial-year', [FinancialYearController::class, 'filterFinancialTableByYear']);
Route::post('/filter-by-financial-year', [SuperAdminController::class, 'filterByFinancialYear'])->name('appraisal.filter.by.year');
Route::post('/employees/filter-financial-year-employee-review',[SuperAdminController::class,'filterByFinancialYearEmployeeReview'])->name('employees-filter-financial-year-employee-review');


// In routes/web.php
// Route::get('/validate-financial-year', [SuperAdminController::class, 'validateFinancialYear']);


//test mail
// Route::get('/test-email', [\App\Http\Controllers\superadmin\SuperAdminController::class, 'testEmail']);


//User Review Report Handle 
// routes/web.php
Route::get('/employee/review-scores', [allUserController::class, 'getReviewScores'])->name('employee.review-scores');
Route::post('/employee/review-score/super-user',[SuperAdminController::class,'getReviewScoresSuperAdmin'])->name('employee.review-score-super-user');
//Get Manager Name is Add User Dashboard
Route::get('/get-managers',[addUserController::class,'getManagers'])->name('get.managers');