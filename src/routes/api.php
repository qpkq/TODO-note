<?php

use App\Http\Controllers\API\Item\ItemController;
use App\Http\Controllers\API\List\ListController;
use App\Http\Controllers\API\Tag\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'todo'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['middleware' => 'auth:sanctum'], function () {

            /*
             * Lists
             */
            Route::group(['prefix' => 'lists'], function () {
                Route::get('/', [ListController::class, 'index'])->name('list.index');
                Route::post('/create', [ListController::class, 'store'])->name('list.create');
                Route::post('/{list}/update', [ListController::class, 'update'])->name('list.update');
                Route::post('/{list}/delete', [ListController::class, 'destroy'])->name('list.destroy');

                /*
                 * Items
                 */
                Route::group(['prefix' => '{list}/items'], function () {
                    Route::get('/', [ItemController::class, 'index'])->name('list.items.index');
                    Route::post('/create', [ItemController::class, 'store'])->name('list.items.create');
                    Route::post('/{item}/update', [ItemController::class, 'update'])->name('list.items.update');
                    Route::post('/{item}/delete-image', [ItemController::class, 'delete_image'])->name('list.items.delete_image');
                    Route::post('/{item}/delete', [ItemController::class, 'destroy'])->name('list.items.destroy');
                    Route::post('/sort', [ItemController::class, 'sort'])->name('list.items.sort');
                    Route::post('/search', [ItemController::class, 'search'])->name('list.items.search');

                    /*
                     * Tags
                     */
                    Route::group(['prefix' => '{item}/tags'], function () {
                        Route::get('/', [TagController::class, 'index'])->name('list.index.tag.index');
                        Route::post('/create', [TagController::class, 'store'])->name('list.index.tag.create');
                        Route::post('/{tag}/delete', [TagController::class, 'destroy'])->name('list.index.tag.destroy');
                    });
                });
            });
        });
    });
});
