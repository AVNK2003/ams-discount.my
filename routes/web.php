<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\CompanyController::class, 'indexForAll'])->name('home');

Route::get('/company/{company}', [\App\Http\Controllers\CompanyController::class, 'showCompanyForAll'])
    ->name('showCompany');

Route::get('/category/{category:slug}', [\App\Http\Controllers\CategoryController::class, 'showCategory'])
    ->name('showCategory');

Route::get('/category/{category:slug}/city/{city:slug}',
    [\App\Http\Controllers\CategoryController::class, 'showCategoryCity'])
    ->name('showCategoryCity');

Route::get('/city/{city:slug}', [\App\Http\Controllers\CityController::class, 'showCity'])->name('showCity');

Route::get('/city/{city:slug}/category/{category:slug}',
    [\App\Http\Controllers\CityController::class, 'showCityCategory'])->name('showCityCategory');

Route::get('/page/{page:slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('showPage');

Route::prefix('cabinet')->middleware(['auth'])->group(
    function () {
        Route::view('/', 'admin.index')->name('admin');

        Route::resource('companies', \App\Http\Controllers\CompanyController::class);

        Route::view('profile', 'admin.profile')->name('profile');

        Route::middleware(['isAdmin'])->group(function () {
//Переключение отображения компании в каталоге
            Route::put('companies/{company}/moderate',
                [\App\Http\Controllers\CompanyController::class, 'toggleActive'])->name('toggleActive');

//Управление партнерами
            Route::resource('partners', \App\Http\Controllers\PartnerController::class);

//Список админов
            Route::get('admins', [\App\Http\Controllers\PartnerController::class, 'admins'])->name('admins');
//Переключение админ\не админ
            Route::put('toggle-admin/{admin}', [\App\Http\Controllers\PartnerController::class, 'toggleAdmin'])
                ->name('partners.admin.toggle');

//Управление городами
            Route::resource('cities', \App\Http\Controllers\CityController::class);

//Управление категориями
            Route::resource('categories', \App\Http\Controllers\CategoryController::class);

//Управление комментариями
            Route::resource('comments', \App\Http\Controllers\CommentController::class);
//Переключение публикации комментария
            Route::put(
                'comments/{comment}/moderate',
                [\App\Http\Controllers\CommentController::class, 'togglePublish']
            )->name('togglePublish');

//Управление страницами
            Route::resource('pages', \App\Http\Controllers\PageController::class);

//Очистка кеша
            Route::post(
                '/clear',
                function () {
                    if (!auth()->user()->tel == '+7 (978) 109-11-56') {
                        abort(403);
                    }

                    Artisan::call('optimize:clear');
                    return redirect()->back()->with('success', 'Кэш очищен');
                }
            )->name('clearAllConfig');

//Страница с настройками бота
            Route::get('bot', [\App\Http\Controllers\BotController::class, 'index'])->name('bot');
//Страница с пользователями бота
            Route::get('bot/users', [\App\Http\Controllers\BotUserController::class, 'index'])
                ->name('botUsers');
//Переключить статус пользователя Активен/Неактивен
            Route::put('bot/users/{botUser}', [\App\Http\Controllers\BotUserController::class, 'toggleActive'])
                ->name('toggleActiveUser');
//Страница для установки токена бота
            Route::get('bot/token', [\App\Http\Controllers\BotController::class, 'setToken'])
                ->name('setToken');
//Установка Webhook
            Route::post('bot/setWebhook', [\App\Http\Controllers\BotController::class, 'setWebhook'])
                ->name('setWebhook');
//Страница с настройками бота
            Route::get('bot/settings', [\App\Http\Controllers\BotController::class, 'botSettings'])
                ->name('botSettings');
//Сохранение настроек бота
            Route::put('bot/settings', [\App\Http\Controllers\BotController::class, 'updateBotSettings'])
                ->name('updateBotSettings');
//Настройка сообщений
            Route::get('bot/messages', [\App\Http\Controllers\BotController::class, 'botMessages'])
            ->name('botMessages');
        });

        Route::put('profile/{partner}',
            [\App\Http\Controllers\PartnerController::class, 'update'])->name('profile.update');
        Route::put('passwordChange', [\App\Http\Controllers\PartnerController::class, 'passwordChange'])
            ->name('passwordChange');
    }
);

Route::post('/bot', [\App\Http\Controllers\BotController::class, 'runBot']);

//TEMP
//Route::post('temp', [\App\Http\Controllers\Controller::class, 'temp'])->name('postPaginate');
//Route::get('temp', [\App\Http\Controllers\Controller::class, 'temp']);

require __DIR__.'/auth.php';
