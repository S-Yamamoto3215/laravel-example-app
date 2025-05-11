<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// 認証が不要なルート
Route::get('/', function () {
    return view('welcome');
});

// 認証が必要なルート
Route::middleware(['auth', 'verified'])->group(function () {
    // カテゴリ関連のルート
    Route::resource('categories', CategoryController::class);

    // タスク関連のルート
    Route::delete('tasks/completed', [TaskController::class, 'destroyCompleted'])->name('tasks.destroy.completed');
    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update.status');
    Route::patch('tasks/{task}/priority', [TaskController::class, 'updatePriority'])->name('tasks.update.priority');

    // ダッシュボードルート
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // プロフィール関連のルート
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breezeが自動生成した認証ルート
require __DIR__ . '/auth.php';
