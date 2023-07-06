<?php
namespace App\SalesPipeline\Controllers\Admin;

use App\SalesPipeline\Models\Deals;
use App\SalesPipeline\Models\DealOrganisation;
use App\Base\Http\Controllers\Controller;
use App\SalesPipeline\Requests\DealsRequest;
use App\SalesPipeline\Repositories\DealsRepository;
use App\SalesPipeline\Repositories\Admin\ReportsAdminRepository;
use App\SalesPipeline\Repositories\StageDetailRepository;
use App\Exports\DealsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Base\Models\User;
use Illuminate\Http\Request;
use App\SalesPipeline\Policies\DealsPolicy;
use DB;

class ReportsAdminController extends Controller
{

    public function __construct() {
        
    }


    public function index($reportType,ReportsAdminRepository $repository, Request $request)
    {       
        try {                       
                $this->authorize('accessAdminReport', Deals::class);
                $deals = $repository->getReportsListing($reportType,$request->all());                
                return response()->json([
                    'status'   => 'success',
                    'listing' => $deals,
                ]);                
            

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }

    }

    public function getOnlyReportListingData($reportType,ReportsAdminRepository $repository, Request $request)
    {    
        
        try {  
            $this->authorize('accessAdminReport', Deals::class);
            $deals = $repository->getOnlyReportListingData($reportType,$request->all());
                
            return response()->json([
                'status'   => 'success',
                'listing' => $deals,
            ]);
            
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }   

    public function getReportsSummary($reportType,ReportsAdminRepository $repository, Request $request)
    {   

         try {
            $this->authorize('accessAdminReport', Deals::class);
            $data = $repository->getSummary($reportType,$request->all());
            
            return response()->json([
                'status'   => 'success',
                'summary' => $data,
            ]);
         } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function exportData($reportType,ReportsAdminRepository $repository, Request $request)
    {   
        
        try {
            $this->authorize('accessAdminReport', Deals::class);
            $data = $repository->export($reportType,null,$request->all());                
            if ($data['success'] === true) {
                return Excel::download($data['export'], 'deals.xlsx',null, [\Maatwebsite\Excel\Excel::XLSX]);            
            }else{
                return response()->json([
                    'status'   => 'error',
                    'message'   => 'No Data Available'
                ],400);
            }
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }


}



