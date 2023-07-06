<?php

namespace App\SalesPipeline\Controllers;

use Illuminate\Support\Facades\Route;


Route::middleware('web')->group(function () {
    

    Route::middleware(['auth'])->group(function () {
        Route::any('data/deals', [DealsController::class, 'show']);        
        Route::any('data/deals/users', [DealsController::class, 'deal_users']); 
        Route::any('data/deals/search', [DealsController::class, 'show_search_deals']);
        Route::any('data/deals/upload', [DealsController::class, 'uploadDeals']);
        // Route/::any('data/deals', [DealsController::class, 'show']);
        Route::post('data/deal-stage-update', [DealsController::class, 'updateDealStage']);
        Route::delete('data/deals/{deal}', [DealsController::class, 'delete']);
        // Route::any('data/delete-all-deals', [DealsController::class, 'deleteMultipleDeals']);
        Route::get('data/deals/{deal_id}', [DealsController::class, 'getDeal']);
        Route::post('data/add-deal', [DealsController::class, 'store']);
        Route::post('data/deal-files/{deal_id}', [DealsController::class, 'uploaddealfile']);
        
        //Notes 
        Route::get('data/deal/{deal_id}/notes', [DealsController::class, 'getDealNotes']);
        Route::post('data/deal/add/note', [DealsController::class, 'addDealNote']);
        Route::post('data/deal/update/note', [DealsController::class, 'updateDealNote']);
        Route::delete('data/deal/note/{note_id}', [DealsController::class, 'delete_note']);
        //End Notes

        //Activity
        Route::post('data/deal-activity/{deal_id}', [DealsController::class, 'addDealActivity']);
        Route::post('data/update-deal-activity/{deal_id}', [DealsController::class, 'updateDealActivity']);
        Route::post('data/deal-activity-delete/{deal_id}', [DealsController::class, 'deleteDealActivity']);
        Route::delete('data/deal/activity/{activity_id}', [DealsController::class, 'delete_activity']);
        
        //End Activity

        // Invoice
        Route::post('data/deal-invoice/{deal_id}', [DealsController::class, 'uploaddealinvoice']);
        //End Invoice

         // Deal Lost Reason
        Route::post('data/deal-lost-reason/{deal_id}', [DealsController::class, 'DealLostReason']);
        //End Deal Lost Reason
        Route::get('data/invoice', [DealsController::class, 'dealpdfinvoice']);
        Route::get('data/emailtrigger', [DealsController::class, 'emailtrigger']);

        Route::post('data/deal-invoice-default/{deal_id}', [DealsController::class, 'updateDealInvoiceDefault']);


        Route::post('data/deal/status/update', [DealsController::class, 'dealStatusUpdate']); 
        Route::post('data/deal/color/update', [DealsController::class, 'dealColourUpdate']);

        Route::post('data/deal-organisation/update/{deal_id}', [DealsController::class, 'organisation_update']);
        Route::post('data/deal-transfer-owner/update/{deal_id}', [DealsController::class, 'transfer_owner_update']);
        Route::post('data/deal-person/update/{deal_id}', [DealsController::class, 'contact_person_update']);
        Route::post('data/deal-stage-update', [DealsController::class, 'updateDealStage']);
        Route::delete('data/deals/{deal}', [DealsController::class, 'delete']);
        Route::get('data/deals/{deal_id}', [DealsController::class, 'getDeal']);
        Route::post('data/add-deal', [DealsController::class, 'store']);
        Route::post('data/deal-organisation/update/{deal_id}', [DealsController::class, 'organisation_update']);
        Route::post('data/deal-person/update/{deal_id}', [DealsController::class, 'contact_person_update']);
        Route::get('data/deal/deal-exists/{project_id}', [DealsController::class, 'dealExistsForProject']);
        Route::get('data/export/deals', [DealsController::class, 'dealsExport']);
        Route::get('data/filters/deals-frontend', [DealsController::class, 'getDealsFiltersData']);
        Route::post('data/deal/category/update', [DealsController::class, 'dealCategoryUpdate']);
        Route::get('data/deal/category/update-live-db', [DealsController::class, 'dealCategoryUpdateDB']);
        

        //Admin Routes
        Route::middleware(['auth'])->group(function () {

            Route::get('data/admin/report/deals/{reportType}', [Admin\ReportsAdminController::class, 'index']);
            Route::get('data/admin/report/deals/{reportType}/reports-listing-data', [Admin\ReportsAdminController::class, 'getOnlyReportListingData']);
            Route::get('data/admin/report/deals/{reportType}/summary/data', [Admin\ReportsAdminController::class, 'getReportsSummary']);            
            Route::get('data/admin/report/deals/{reportType}/export/data', [Admin\ReportsAdminController::class, 'exportData']);

        });

        
        //Route::get('sales-pipeline/get/{status}', [SalesPipelineController::class, 'index']);

        // Route::get('sales-pipeline/chart/user/{pipeline}', [SalesPipelineController::class, 'getUserDurationChartData']);

        

        // Route::post('sales-pipelines/{id}', [SalesPipelineController::class, 'update']);

        // Route::delete('sales-pipelines/{pipeline}', [SalesPipelineController::class, 'delete']);

        // Route::post('public-projects/{pipeline}', [PublicSalesPipelineController::class, 'store']);

        // Route::delete('public-projects/{pipeline}', [PublicSalesPipelineController::class, 'delete']);
    });
});

// Api

// Route::middleware(['api', 'auth:api'])->prefix('api')->group(function () {
//     Route::get('sales-pipelines/', [SalesPipelineController::class, 'index']);

//     Route::post('sales-pipelines', [SalesPipelineController::class, 'store']);

//     Route::delete('sales-pipelines/{pipeline}', [SalesPipelineController::class, 'delete']);

//     Route::post('public-sales-pipelines/{pipeline}', [PublicSalesPipelineController::class, 'store']);

//     Route::delete('public-sales-pipelines/{pipeline}', [PublicSalesPipelineController::class, 'delete']);
// });
