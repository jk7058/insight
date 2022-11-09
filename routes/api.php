<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\CallEvolutionController;
use App\Http\Controllers\EscalationController;
use App\Http\Controllers\Api\HierarchyController;
use App\Http\Controllers\Api\FormDetailsController;
use App\Http\Controllers\Api\FormStructureController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Api\FormCommentsLogicController;
use App\Http\Controllers\Api\FormConditionalLogicController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\Api\CustomHierarchyController;
use App\Http\Controllers\Api\DashboardController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::group(['middleware'=>'api'], function($routes) {
//     Route::post('/auth/logout', [UserController::class, 'logout']);
//     Route::post('/dashboard', function () {
//         echo "test";
//     });

// });


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('refresh', [UserController::class, 'refresh']);

      /// Hierarchy Routes
    Route::post('hierarchy/add', [HierarchyController::class,'store']);
    Route::post('hierarchy/update', [HierarchyController::class,'update']);
    Route::post('hierarchy/delete', [HierarchyController::class,'destroy']);
    Route::post('hierarchy/show{id?}', [HierarchyController::class,'show']);

    Route::post('hierarchy/getcustom1', [HierarchyController::class,'getcustom1']);
    Route::post('hierarchy/getcustom2', [HierarchyController::class,'getcustom2']);
    Route::post('hierarchy/getcustom3', [HierarchyController::class,'getcustom3']);
    Route::post('hierarchy/getcustom4', [HierarchyController::class,'getcustom4']);

    Route::get('hierarchy/get-custom-hierarchy', [CustomHierarchyController::class,'show']);

    # Route::apiResource('roles', RoleController::class)->except(['index','create','edit']);
    Route::post('roles/add', [RoleController::class, 'store']);
    Route::post('roles/update', [RoleController::class, 'update']);
    Route::post('roles/delete', [RoleController::class, 'destroy']);
    Route::post('roles/show{id?}', [RoleController::class, 'show']);

    // Agents Routes
    Route::apiResource('agents', AgentController::class);
    Route::post('agents/bulk', [AgentController::class, 'bulk']);
    Route::post('agents/bulk/update', [AgentController::class, 'bulkUpdate']);
    Route::get('agents/export/csv', [AgentController::class, 'export']);
    Route::post('agents/search', [AgentController::class, 'search']);
    Route::post('agents/list', [AgentController::class, 'list']);

    // User Management Routes
    Route::post('create-user', [EmployeeController::class, 'add_user']);
    Route::post('update-user', [EmployeeController::class, 'update_user']);
    Route::post('view-user', [EmployeeController::class, 'view_user']);
    Route::post('delete-user', [EmployeeController::class, 'delete_user']);
    Route::post('inactive-user', [EmployeeController::class, 'inactive_user']);
    Route::post('user-bulk-upload', [EmployeeController::class, 'user_bulk_upload']);
    Route::get('user-export', [EmployeeController::class, 'user_export']);
    Route::post('create-module-access', [EmployeeController::class, 'add_module_access']);
    Route::post('change-password', [EmployeeController::class, 'change_password']);

    /// Alert Routes
    Route::post('alerts/add', [AlertController::class,'store']);
    Route::post('alerts/update', [AlertController::class,'update']);
    Route::post('alerts/delete', [AlertController::class,'destroy']);
    Route::post('alerts/show{id?}', [AlertController::class,'show']);

    /// Call Routes
     Route::post('calls/add', [CallEvolutionController::class,'store']);
     Route::post('calls/add-evaluation', [CallEvolutionController::class,'AddEvolution']);
     Route::get('calls/getunassignedcalls', [CallEvolutionController::class,'getUnassignedCalls']);
     Route::get('calls/getallcalls', [CallEvolutionController::class,'getAllCalls']);
     Route::post('calls/getmycalls', [CallEvolutionController::class,'getMyCalls']);
     Route::post('calls/assign-later', [CallEvolutionController::class,'AssignCallLater']);
     Route::post('calls/get-form-name', [CallEvolutionController::class,'getFormName']);
     Route::post('calls/get-agents', [CallEvolutionController::class,'getAgents']);
     Route::post('calls/get-agents-super-visor', [CallEvolutionController::class,'getAgentSuperVisor']);
     Route::post('calls/get-other-evaluators', [CallEvolutionController::class,'getOtherEvaluators']);
     Route::post('calls/update-call-status', [CallEvolutionController::class,'updateCallStatus']);

    // Route::post('calls/delete', [CallEvolutionController::class,'destroy']);
    // Route::post('calls/show{id?}', [CallEvolutionController::class,'show']);

    // Escalation Management Routes
    Route::post('create-escalation', [EscalationController::class, 'add_escalation']);
    Route::post('update-escalation', [EscalationController::class, 'update_escalation']);
    Route::post('view-escalation', [EscalationController::class, 'view_escalation']);
    Route::post('delete-escalation', [EscalationController::class, 'delete_escalation']);

    /// Forms Routes

    Route::post('forms/addformdetails', [FormDetailsController::class,'store']);
    Route::post('forms/get-form-custom-meta', [FormDetailsController::class,'getFormCustomMeta']);
    Route::post('forms/addstructure', [FormStructureController::class,'store']);
    Route::post('forms/get-form-data', [FormStructureController::class,'getFormData']);
    Route::post('forms/add-form-comments-logic', [FormCommentsLogicController::class,'store']);
    Route::post('forms/add-form-conditional-logic', [FormConditionalLogicController::class,'store']);
    Route::post('forms/get-form-comments-logic', [FormCommentsLogicController::class,'show']);
    Route::post('forms/get-form-conditional-logic', [FormConditionalLogicController::class,'show']);

    // ATA Routes
    Route::post('audits/available-audits', [AuditController::class,'availableAudits']);
    Route::post('audits/assign-auditor', [AuditController::class,'assignAudits']);
    Route::post('audits/my-audits', [AuditController::class,'myAudits']);
    Route::post('audits/my-audits/export', [AuditController::class,'myAuditsExport']);
    Route::post('audits/audit-history', [AuditController::class,'auditHistory']);
    Route::post('common/filter', [AuditController::class,'commonFilter']);



    // ATA Management Routes
    Route::post('available-audits', [EscalationController::class, 'available_audits']);
    Route::post('my-audits', [EscalationController::class, 'my_audits']);
    Route::post('audits-history', [EscalationController::class, 'audits_history']);

    // Review Management Routes
    Route::post('add-review', [EscalationController::class, 'add_review']);
    Route::post('evaluation-summary', [EscalationController::class, 'evaluation_summary']);


    //Custom Route
    Route::get('custom/1', [CustomController::class, 'custom1']);
    Route::post('custom/2', [CustomController::class, 'custom2']);
    Route::post('custom/3', [CustomController::class, 'custom3']);
    Route::post('custom/4', [CustomController::class, 'custom4']);
    Route::get('supervisor/get', [CustomController::class, 'getSupervisor']);

    //Feedback Settings
    Route::post('feedback/setting', [SettingController::class, 'feedbackSetting']);
    Route::get('feedback/setting', [SettingController::class, 'getFeedbackSetting']);
    Route::post('feedback/submit', [FeedbackController::class, 'store']);
    Route::post('feedback', [FeedbackController::class, 'getFeedback']);

    Route::get('dasboard', [DashboardController::class, 'index']);


});

Route::post('/auth/login', [EmployeeController::class, 'loginUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);
Route::post('forgot-password', [UserController::class, 'sendResetLinkResponse']);
Route::post('reset', [UserController::class, 'sendResetResponse']);
