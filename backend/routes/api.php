<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PlayerRatingController;
use App\Http\Controllers\MonthlyRecordController;
use App\Http\Controllers\TrainingSessionController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\CoachAttendanceController;
use App\Http\Controllers\ExcusedSessionController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Player routes
    Route::get('/players/my-profile', [PlayerController::class, 'myProfile']);
    Route::post('/players/create-with-user', [PlayerController::class, 'createWithUser']);
    Route::apiResource('players', PlayerController::class);
    Route::post('/players/{id}/move-session', [PlayerController::class, 'moveSession']);
    Route::post('/players/{id}/update-sports-manager-notes', [PlayerController::class, 'updateSportsManagerNotes']);
    Route::post('/players/{id}/set-excused-absences', [PlayerController::class, 'setExcusedAbsences']);

    // Schedule routes
    Route::apiResource('schedules', ScheduleController::class);

    // Training Session routes (configurable sessions)
    Route::get('/training-sessions/for-date', [TrainingSessionController::class, 'getSessionsForDate']);
    Route::apiResource('training-sessions', TrainingSessionController::class);

    // Attendance routes
    Route::get('/attendances/sessions-for-date', [AttendanceController::class, 'getSessionsForDate']);
    Route::get('/attendances/players-for-session/{sessionId}', [AttendanceController::class, 'getPlayersForSession']);
    Route::post('/attendances/mark-multiple', [AttendanceController::class, 'markMultiple']);
    Route::apiResource('attendances', AttendanceController::class);

    // Excused Session routes
    Route::get('/excused-sessions/pending/{playerId}', [ExcusedSessionController::class, 'getPendingForPlayer']);
    Route::post('/excused-sessions/{id}/mark-makeup', [ExcusedSessionController::class, 'markMakeupTaken']);
    Route::post('/excused-sessions/{id}/mark-discounted', [ExcusedSessionController::class, 'markAsDiscounted']);
    Route::apiResource('excused-sessions', ExcusedSessionController::class);

    // Rating routes
    Route::apiResource('ratings', PlayerRatingController::class);

    // Coach routes
    Route::get('/coaches/{id}/stats', [CoachController::class, 'stats']);
    Route::post('/coaches/{coachId}/assign-player', [CoachController::class, 'assignPlayer']);
    Route::post('/players/{playerId}/remove-coach', [CoachController::class, 'removePlayer']);
    Route::apiResource('coaches', CoachController::class);

    // Coach Attendance routes
    Route::apiResource('coach-attendances', CoachAttendanceController::class);

    // Fee routes
    Route::get('/fees/revenue', [FeeController::class, 'revenue']);
    Route::post('/fees/withdrawal', [FeeController::class, 'recordWithdrawal']);
    Route::post('/fees/renewal-with-discounts', [FeeController::class, 'createRenewalWithDiscounts']);
    Route::apiResource('fees', FeeController::class);

    // Manager/Admin routes
    Route::middleware('manager')->group(function () {
        Route::apiResource('branches', BranchController::class);
        Route::get('/monthly-records/statistics', [MonthlyRecordController::class, 'statistics']);
        Route::apiResource('monthly-records', MonthlyRecordController::class);
        
        // Settings routes
        Route::get('/settings', [SettingController::class, 'index']);
        Route::get('/settings/{key}', [SettingController::class, 'get']);
        Route::put('/settings/{key}', [SettingController::class, 'update']);
        Route::post('/settings/period-dates', [SettingController::class, 'updatePeriodDates']);
    });
});
