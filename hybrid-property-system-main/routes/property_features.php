<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Property Features Routes
|--------------------------------------------------------------------------
|
| These routes handle additional property management features like
| image galleries, document management, facilities, and ownership transfers.
|
*/

// Property Image Gallery Management Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('property/{property}/images')->name('property.images.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PropertyImageController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\PropertyImageController::class, 'store'])->name('store');
        Route::post('/{image}/set-primary', [\App\Http\Controllers\PropertyImageController::class, 'setPrimary'])->name('set-primary');
        Route::delete('/{image}', [\App\Http\Controllers\PropertyImageController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [\App\Http\Controllers\PropertyImageController::class, 'reorder'])->name('reorder');
    });
});

// Property Document Management Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('property/{property}/documents')->name('property.documents.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PropertyDocumentController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\PropertyDocumentController::class, 'store'])->name('store');
        Route::get('/{document}/download', [\App\Http\Controllers\PropertyDocumentController::class, 'download'])->name('download');
        Route::delete('/{document}', [\App\Http\Controllers\PropertyDocumentController::class, 'destroy'])->name('destroy');
    });
});

// Property Facilities Management Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('property/{property}/facilities')->name('property.facilities.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PropertyFacilityController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\PropertyFacilityController::class, 'store'])->name('store');
        Route::delete('/{facility}', [\App\Http\Controllers\PropertyFacilityController::class, 'destroy'])->name('destroy');
    });
});

// Property Ownership Transfer Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('property/{property}/transfer')->name('property.transfer.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PropertyTransferController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\PropertyTransferController::class, 'store'])->name('store');
        Route::get('/{transfer}', [\App\Http\Controllers\PropertyTransferController::class, 'show'])->name('show');
        Route::post('/{transfer}/approve', [\App\Http\Controllers\PropertyTransferController::class, 'approve'])->name('approve');
        Route::post('/{transfer}/reject', [\App\Http\Controllers\PropertyTransferController::class, 'reject'])->name('reject');
    });
});

// Landlord Property Transfer Routes
Route::middleware(['auth', 'role:Landlord'])->prefix('landlord')->name('landlord.')->group(function () {
    Route::prefix('property/{property}/transfer')->name('property.transfer.')->group(function () {
        Route::get('/create', [\App\Http\Controllers\PropertyTransferController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\PropertyTransferController::class, 'store'])->name('store');
        Route::get('/{transfer}', [\App\Http\Controllers\PropertyTransferController::class, 'show'])->name('show');
        Route::get('/{transfer}/edit', [\App\Http\Controllers\PropertyTransferController::class, 'edit'])->name('edit');
        Route::put('/{transfer}', [\App\Http\Controllers\PropertyTransferController::class, 'update'])->name('update');
        Route::put('/{transfer}/cancel', [\App\Http\Controllers\PropertyTransferController::class, 'cancel'])->name('cancel');
        Route::get('/{transfer}/document/{document}/download', [\App\Http\Controllers\PropertyTransferController::class, 'downloadDocument'])->name('document.download');
    });

    // Transfer management routes
    Route::get('/transfers', [\App\Http\Controllers\PropertyTransferController::class, 'index'])->name('transfers.index');
    Route::put('/transfers/{transfer}/accept', [\App\Http\Controllers\PropertyTransferController::class, 'accept'])->name('transfers.accept');
    Route::put('/transfers/{transfer}/reject', [\App\Http\Controllers\PropertyTransferController::class, 'reject'])->name('transfers.reject');
});
