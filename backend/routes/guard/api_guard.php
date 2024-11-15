<?php

use App\Http\Controllers\AssignModuleController;
use App\Http\Controllers\AssignPermissionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceStatusController;
use App\Http\Controllers\Guard\NfcTagsController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SdkLogcsvfileController;
use App\Http\Controllers\Shift\MultiInOutShiftController;
use App\Http\Controllers\Shift\SingleShiftController;
use App\Http\Controllers\TradeLicenseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::apiResource('guard_tracking_logs',  NfcTagsController::class);

Route::post('sync_guard_logs_server', [NfcTagsController::class, 'syncGuardLogs']);
