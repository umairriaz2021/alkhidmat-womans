<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('admin.login'),
       
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::get('/test-me', function() { return "Laravel is working!"; });
Route::get('/{slug?}', [PageController::class, 'show'])->name('pages.show');
// 2. Admin Routes (Sab ek jagah prefix ke saath)
Route::prefix('admin')->group(function () {

    // Sirf Guests (Log out users) ke liye
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserController::class, 'login'])->name('admin.login');
        Route::get('/register', [UserController::class, 'register'])->name('admin.register');
        Route::post('/login', [UserController::class, 'store'])->name('admin.login.submit');
    });

    // Sirf Authenticated Super Admins ke liye
    Route::middleware(['auth', 'role:super_admin'])->group(function () {
        // Users List
        Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
        Route::get('/all-users',[DashboardController::class,'AllUsers'])->name('admin.users');
        Route::get('/user-edit/{id}',[DashboardController::class,'editUser'])->name('admin.edit.user');
        Route::match(['get','post'],'/create-user',[DashboardController::class,'createUser'])->name('admin.create.user');
        Route::patch('/user-edit/{id}',[DashboardController::class,'updateUser'])->name('admin.user.update');
        Route::delete('/delete-user/{id}',[DashboardController::class,'deleteUser'])->name('admin.user.delete');
        //Media
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media/upload', [MediaController::class, 'store'])->name('media.upload');
        //Pages
        Route::prefix('pages')->group(function() {
            Route::get('/all-pages',[PageController::class,'index'])->name('admin.all.pages');
            Route::match(['get','post'],'/create-page',[PageController::class,'createPages'])->name('admin.add.pages');
            Route::get('/edit/{id}',[PageController::class,'editPage'])->name('admin.edit.pages');
            Route::patch('/update-page/{id}',[PageController::class,'updatePage'])->name('admin.update.pages');
            Route::delete('/delete-page/{id}',[PageController::class,'deletePage'])->name('admin.delete.pages');
        });
        //Templates
        Route::prefix('templates')->group(function() {
            Route::get('/all-templates',[TemplateController::class,'index'])->name('admin.all.template');
            Route::match(['get','post'],'/create-page-templates',[TemplateController::class,'createPageTemplates'])->name('admin.add.templates');
            Route::get('/edit/{id}',[TemplateController::class,'editTemplate'])->name('admin.edit.template');
            Route::patch('/update-template/{id}',[TemplateController::class,'updateTemplate'])->name('admin.update.template');
            //Route::delete('/delete-page/{id}',[PageController::class,'deletePage'])->name('admin.delete.pages');
        });
        //Sliders
        Route::prefix('sliders')->group(function() {
              Route::get('/all-slides',[SliderController::class,'index'])->name('admin.slider');
              Route::match(['get','post'],'/create-slider',[SliderController::class,'createSlider'])->name('admin.add.slider');
              Route::get('/edit-slider/{id}',[SliderController::class,'edit'])->name('admin.edit.slider');
              Route::patch('/update-slider/{id}',[SliderController::class,'update'])->name('admin.update.slider');
              Route::delete('/delete-slider/{id}',[SliderController::class,'deleteSlider'])->name('admin.delete.slider');
              });
        //Sliders
        Route::prefix('menus')->group(function() {
               Route::get('/all-menus',[MenuController::class,'index'])->name('admin.menus');
               Route::match(['get','post'],'/create-menu',[MenuController::class,'createMenu'])->name('admin.create.menus');
               Route::get('/edit-menu/{id}',[MenuController::class,'editMenu'])->name('admin.edit.menu');
               Route::patch('/update-menu/{id}',[MenuController::class,'updateMenu'])->name('admin.update.menu');
               Route::delete('/delete-menu/{id}',[MenuController::class,'deleteMenu'])->name('admin.delete.menu');
               // Mega menu 
               Route::get('/mega-menus',[MenuController::class,'showMegaMenus'])->name('admin.show.megamenu');
               Route::match(['get','post'],'/all-mega-menus',[MenuController::class,'createMegaMenus'])->name('admin.create.megamenus');
               Route::get('/admin/megamenus/edit/{id}', [MenuController::class, 'editMegaMenus'])->name('admin.edit.megamenus');
               Route::patch('/admin/megamenus/update/{id}', [MenuController::class, 'updateMegaMenus'])->name('admin.update.megamenus');
                Route::delete('/admin/megamenus/delete-menu/{id}',[MenuController::class,'deleteMegaMenu'])->name('admin.delete.megamenu');
               });
        // Admin Logout
        //Settings
        Route::prefix('settings')->group(function() {
             Route::match(['get','post'],'/settings',[SettingsController::class,'settingUpdate'])->name('admin.settings');
        });
        Route::post('/logout', [UserController::class, 'logout'])->name('admin.logout');
    });
});

require __DIR__.'/auth.php';
