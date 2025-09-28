<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantLeadController;

// Tenant Lead Management Routes
Route::resource('tenant-leads', TenantLeadController::class);
Route::post('tenant-leads/{tenantLead}/update-status', [TenantLeadController::class, 'updateStatus'])->name('tenant-leads.update-status');
Route::post('tenant-leads/{tenantLead}/assign', [TenantLeadController::class, 'assign'])->name('tenant-leads.assign');
Route::post('tenant-leads/{tenantLead}/add-note', [TenantLeadController::class, 'addNote'])->name('tenant-leads.add-note');
Route::post('tenant-leads/{tenantLead}/set-follow-up', [TenantLeadController::class, 'setFollowUp'])->name('tenant-leads.set-follow-up');
Route::get('tenant-leads-stats', [TenantLeadController::class, 'stats'])->name('tenant-leads.stats');
Route::get('tenant-leads-export', [TenantLeadController::class, 'export'])->name('tenant-leads.export');
